<?php
require '../../conn.php';

$method = $_POST['method'];

if ($method == 'generate_bvb_data') {
    $day = $_POST['day'];

    // $query = "SELECT TOP (1) id FROM emp_mgt_backup.dbo.t_biometric_time_in_out WHERE day = ?";
    
    // $params = [];

	// $params[] = $day;

    // $stmt = $conn->prepare($query);
    // $stmt->execute($params);

    // $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // if (!$row) {
    //     $conn = null;
    //     exit("Biometric Data as of " . $day . " was not found on Biometric Data Table");
    // }

    $isTransactionActive = false;

    try {
        if (!$isTransactionActive) {
            $conn->beginTransaction();
            $isTransactionActive = true;
        }

        $query = "
                    DECLARE @bvb_day DATE = CONVERT(DATE, ?);

                    DELETE FROM emp_mgt_backup.dbo.t_biometric_vs_barcode WHERE day = @bvb_day;

                    INSERT INTO 
                        emp_mgt_backup.dbo.t_biometric_vs_barcode 
                        (day, shift, shift_group, emp_no, full_name, 
                        dept, section, line_no, process, position, 
                        provider, gender, emp_status, 
                        time_in_remarks, time_out_remarks) 
                    SELECT
                        @bvb_day AS day,
                        COALESCE(NULLIF(emp.shift, ''), 'N/A') AS shift, 
                        COALESCE(NULLIF(emp.shift_group, ''), 'N/A') AS shift_group,
                        emp.emp_no, 
                        emp.full_name, 
                        emp.dept, 
                        emp.section, 
                        emp.line_no, 
                        emp.process, 
                        emp.position, 
                        emp.provider, 
                        COALESCE(NULLIF(emp.gender, ''), 'N/A') AS gender, 
                        COALESCE(NULLIF(emp.emp_status, ''), 'N/A') AS emp_status, 
                        CASE 
                            WHEN (b.time_in IS NULL AND tio.date_updated IS NULL) AND (b.time_out IS NULL AND tio.time_out IS NULL) THEN 'Absent' 
                            WHEN b.time_in IS NULL AND tio.date_updated IS NULL THEN 'No Entries Both'
                            WHEN b.time_in IS NOT NULL AND tio.date_updated IS NULL THEN 'No Barcode In'
                            WHEN b.time_in IS NULL AND tio.date_updated IS NOT NULL THEN 'No Bio In'
                            WHEN b.time_in > tio.date_updated THEN 'Early Barcode'
                            WHEN (b.shift = 'DS' AND CAST(b.time_in AS TIME) > '06:00:00') OR (b.shift = 'NS' AND CAST(b.time_in AS TIME) > '18:00:00') THEN 'Late'
                            WHEN (b.shift = 'DS' AND CAST(tio.date_updated AS TIME) > '06:00:00') OR (b.shift = 'NS' AND CAST(tio.date_updated AS TIME) > '18:00:00') THEN 'Late Barcode'
                            ELSE 'Time In OK' 
                        END AS time_in_remarks, 
                        CASE 
                            WHEN (b.time_in IS NULL AND tio.date_updated IS NULL) AND (b.time_out IS NULL AND tio.time_out IS NULL) THEN 'Absent' 
                            WHEN b.time_out IS NULL AND tio.time_out IS NULL THEN 'No Entries Both'
                            WHEN b.time_out IS NOT NULL AND tio.time_out IS NULL THEN 'No Barcode Out'
                            WHEN b.time_out IS NULL AND tio.time_out IS NOT NULL THEN 'No Bio Out'
                            WHEN b.time_out < tio.time_out THEN 'Early Bio'
                            WHEN b.time_out > tio.time_out AND DATEDIFF(MINUTE, tio.time_out, b.time_out) >= 60 THEN 'Late Bio'
                            ELSE 'Time Out OK' 
                        END AS time_out_remarks 
                    FROM 
                        emp_mgt_db.dbo.m_employees emp 
                    LEFT JOIN emp_mgt_db.dbo.t_time_in_out tio ON tio.emp_no = emp.emp_no AND tio.day = @bvb_day  -- Updated to use @bvb_day
                    LEFT JOIN emp_mgt_backup.dbo.t_biometric_time_in_out b ON b.emp_no = emp.emp_no AND b.day = @bvb_day  -- Updated to use @bvb_day 
                    WHERE 
                        (emp.date_hired <= @bvb_day) AND 
                        (emp.resigned_date IS NULL OR emp.resigned_date >= @bvb_day);
                    ";

        $params = [];

        $params[] = $day;

        $stmt = $conn->prepare($query);
        $stmt->execute($params);

        $conn->commit();
        $isTransactionActive = false;
    } catch (Exception $e) {
        if ($isTransactionActive) {
            $conn->rollBack();
            $isTransactionActive = false;
        }

        $conn = null;
        exit("Failed. Please Try Again or Call IT Personnel Immediately!: ". $e->getMessage());
    }
}

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
