<?php
    include '../conn.php';

    $stmt = $conn ->prepare("
    DECLARE @date nvarchar(20) = :date;
    With ShiftRecord AS(
	    SELECT
		    section,
		    COUNT(CASE WHEN emp.shift = 'DS' THEN 1 END) AS dayshift_emp, 
		    COUNT(CASE WHEN emp.shift = 'NS' THEN 1 END) AS nightshift_emp,
		    COUNT(CASE WHEN emp.shift <> 'DS' AND emp.shift <> 'NS' THEN 1 END) AS shift_error,
		    COUNT(id) AS total
	    FROM 
	    m_employees emp 
	    WHERE 
	    (emp.date_hired <= GETDATE()) AND 
	    (emp.resigned_date IS NULL OR emp.resigned_date >= GETDATE())
	    group by emp.section
    ),
    SectionPresent AS (
	    SELECT 
		    a.section,
		    COUNT(DISTINCT CASE WHEN b.shift = 'DS' THEN a.emp_no END) AS ds_present,
		    COUNT(DISTINCT CASE WHEN b.shift = 'NS' THEN a.emp_no END) AS ns_present
	    FROM m_employees a
	    INNER JOIN t_time_in_out b 
		    ON a.emp_no = b.emp_no
	    WHERE b.date_updated BETWEEN CONCAT(@date, ' 00:00:00') AND CONCAT(@date, ' 23:59:59')
	    GROUP BY a.section
    ), SectionAbsents AS (
    SELECT b.section, dayshift_emp + nightshift_emp + shift_error - ds_present - ns_present as absents FROM SectionPresent as a LEFT JOIN ShiftRecord as b on a.section = b.section
    )
    SELECT a.section as Section, c.absents as Absents, b.total, CAST(absents * 1.0 / (SELECT SUM(absents) FROM SectionAbsents) AS FLOAT) as record FROM SectionPresent as a LEFT JOIN ShiftRecord  as b on a.section = b.section LEFT JOIN SectionAbsents as c on a.section = c.section order by absents desc;
    ");

    $stmt -> execute([
        "date" => $_GET['date']
    ]);
    $full_data = $stmt -> fetchAll(PDO::FETCH_ASSOC);
    
    // cumulative fix
    // also includes the absent ratio
    $absents = 0;
    $employees = 0;
    $total = 0;
    $cumulative = 0;
    foreach ($full_data as &$data) {
        $absents += $data['Absents'];
        $total += $data['total'];
        $cumulative += $data['record'];
        $data['cumulative_record'] = round($cumulative, 2);
        // $data['record'] = $data['record'] * 100;
    }

    header('Content-Type: application/json');
    echo json_encode(["data" => $full_data, "absent_ratio" => number_format(($absents / $total) * 100, 2)]);
    exit();