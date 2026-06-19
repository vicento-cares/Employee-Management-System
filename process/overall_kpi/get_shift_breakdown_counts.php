<?php
    include '../conn.php';
    $stmt = $conn -> query(" SELECT
		COUNT(CASE WHEN emp.shift = 'DS' THEN 1 END) AS dayshift_emp, 
		COUNT(CASE WHEN emp.shift = 'NS' THEN 1 END) AS nightshift_emp,
		COUNT(CASE WHEN emp.shift <> 'DS' AND emp.shift <> 'NS' THEN 1 END) AS shift_error,
		COUNT(id) AS total

	    FROM m_employees emp 
	    WHERE 
	    (emp.date_hired <= GETDATE()) AND 
	    (emp.resigned_date IS NULL OR emp.resigned_date >= GETDATE())
    ");

    $data = $stmt ->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode(["data" => $data]);
    exit();