<?php
require '../../conn.php';

$method = $_POST['method'];

if ($method == 'biometric_data_list') {
    $day = $_POST['day'];

    $c = 0;

    $query = "SELECT 
                    b.day, b.day_code, b.shift, b.emp_no, CONVERT(VARCHAR, b.time_in, 120) AS time_in, CONVERT(VARCHAR, b.time_out, 120) AS time_out, 
                    emp.full_name, emp.dept, emp.section, emp.line_no 
                FROM 
                    emp_mgt_backup.dbo.t_biometric_time_in_out b 
                LEFT JOIN emp_mgt_db.dbo.m_employees emp ON emp.emp_no = b.emp_no
                WHERE 
                    b.emp_no IS NOT NULL AND b.day = ?";
    
    $params = [];

	$params[] = $day;

    $stmt = $conn->prepare($query);
    $stmt->execute($params);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        do {
            $c++;

            echo '<tr>';
            echo '<td>' . $c . '</td>';
            echo '<td>' . $row['day'] . '</td>';
            echo '<td>' . $row['day_code'] . '</td>';
            echo '<td>' . $row['shift'] . '</td>';
            echo '<td>' . $row['emp_no'] . '</td>';
            echo '<td>' . $row['full_name'] . '</td>';
            echo '<td>' . $row['dept'] . '</td>';
            echo '<td>' . $row['section'] . '</td>';
            echo '<td>' . $row['line_no'] . '</td>';
            echo '<td>' . $row['time_in'] . '</td>';
            echo '<td>' . $row['time_out'] . '</td>';
            echo '</tr>';
        } while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
    }
}

$conn = NULL;
