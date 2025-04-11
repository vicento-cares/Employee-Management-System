<?php
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

require '../../conn.php';

$method = $_POST['method'];

if ($method == 'get_non_compliance_year_dropdown_search') {
    $sql = "SELECT 
                DISTINCT YEAR(day) AS Year
            FROM 
                t_time_in_out
            ORDER BY 
                Year";

    $stmt = $conn->prepare($sql);

    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($results) > 0) {
        echo '<option selected value="">Select Year</option>';
        foreach ($results as $row) {
            echo '<option value="' . htmlspecialchars($row['Year']) . '">' . htmlspecialchars($row['Year']) . '</option>';
        }
    } else {
        echo '<option selected value="">Select Year</option>';
    }
}

if ($method == 'non_compliance_list') {
    $year = $_POST['year'];
    $month = $_POST['month'];

    $c = 0;

    $query = "
                -- Get Manpower Non-Compliance (No Time Out Only)
                DECLARE @Year INT = ?;  -- Specify the year
                DECLARE @Month INT = ?;    -- Specify the month

                WITH TimeRecords AS (
                    SELECT 
                        emp.emp_no, 
                        emp.full_name, 
                        emp.dept, 
                        emp.section, 
                        emp.line_no, 
                        emp.process, 
                        COUNT(CASE WHEN tio.time_out IS NULL THEN 1 END) AS NullTimeOutCount
                    FROM 
                        m_employees emp
                    LEFT JOIN
                        t_time_in_out tio ON emp.emp_no = tio.emp_no 
                        AND emp.resigned = 0 
                        AND MONTH(tio.day) = @Month 
                        AND YEAR(tio.day) = @Year 
                    WHERE
                        CAST(tio.day AS DATE) <> CAST(GETDATE() AS DATE)  -- Exclude today's date
                    GROUP BY 
                        emp.emp_no, 
                        emp.full_name, 
                        emp.dept, 
                        emp.section, 
                        emp.line_no, 
                        emp.process 
                )

                SELECT 
                    emp_no,
                    full_name, 
                    dept, 
                    section, 
                    line_no, 
                    process, 
                    NullTimeOutCount,
                    CASE 
                        WHEN NullTimeOutCount > 2 THEN 'Audit' 
                        ELSE 'Warning' 
                    END AS Status
                FROM 
                    TimeRecords
                WHERE 
                    NullTimeOutCount > 2  -- Filter to show only employees with null time records (0 to see warning)
                ORDER BY 
                    NullTimeOutCount DESC, 
                    emp_no ASC;
            ";

    $params = [];

    $params[] = $year;
    $params[] = $month;

    $stmt = $conn->prepare($query);

    $stmt->execute($params);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $c++;

        echo '<tr>';
        echo '<td>' . $c . '</td>';
        echo '<td>' . $row['emp_no'] . '</td>';
        echo '<td>' . $row['full_name'] . '</td>';
        echo '<td>' . $row['dept'] . '</td>';
        echo '<td>' . $row['section'] . '</td>';
        echo '<td>' . $row['line_no'] . '</td>';
        echo '<td>' . $row['process'] . '</td>';
        echo '<td>' . $row['NullTimeOutCount'] . '</td>';
        echo '</tr>';
    }
}

$conn = NULL;
