<?php
    include '../conn.php';

    $stmt = $conn -> prepare("
         DECLARE @date nvarchar(20) = :date;
        SELECT
            COUNT(CASE
                WHEN a.date_updated >= CONCAT(@date, ' 04:30:00')
                AND a.date_updated <= CONCAT(@date, ' 07:30:00')
                THEN 1
            END) AS on_time,
            COUNT(CASE
                WHEN a.date_updated < CONCAT(@date, ' 04:30:00')
                OR a.date_updated > CONCAT(@date, ' 07:30:00')
                THEN 1
            END) as out_of_window,
			COUNT(*) AS total,
			COUNT(CASE WHEN b.shift <> 'DS' THEN 1 END) AS shift_error
        FROM t_time_in_out as a LEFT JOIN m_employees as b on a.emp_no = b.emp_no where a.date_updated BETWEEN CONCAT(@date, ' 00:00:00') AND CONCAT(@date, ' 23:59:59') AND a.shift = 'DS';

        SELECT
            COUNT(CASE
                WHEN a.date_updated >= CONCAT(@date, ' 16:30:00')
                AND a.date_updated <= CONCAT(@date, ' 19:30:00')
                THEN 1
            END) AS on_time,
            COUNT(CASE
                WHEN a.date_updated < CONCAT(@date, ' 16:30:00')
                OR a.date_updated > CONCAT(@date, ' 19:30:00')
                THEN 1
            END) AS out_of_window,
			COUNT(*) AS total,
			COUNT(CASE WHEN b.shift <> 'NS' THEN 1 END) AS shift_error
        FROM t_time_in_out as a LEFT JOIN m_employees as b on a.emp_no = b.emp_no where a.date_updated BETWEEN CONCAT(@date, ' 00:00:00') AND CONCAT(@date, ' 23:59:59') AND a.shift = 'NS';
    ");

    $stmt -> execute([
        "date" => $_GET['date']
    ]);

    $data_ds = $stmt -> fetchAll(PDO::FETCH_ASSOC);
    $stmt -> nextRowSet();
    $data_ns = $stmt -> fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode([
        "data" => [
            "DS" => $data_ds,
            "NS" => $data_ns,
        ]
    ]);
    exit();