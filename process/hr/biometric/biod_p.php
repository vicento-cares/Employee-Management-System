<?php
require '../../conn.php';

$method = $_GET['method'];

if ($method == 'get_month_bio_vs_barcode_time_in_chart') {
    $year = $_GET['year'];
    $month = $_GET['month'];

    $color_map = array(
        'Late' => '#ffc107', // Warning
        'Time In OK' => '#28a745', // Success
        'No Entries Both' => '#6c757d', // Gray
        'Absent' => '#f8bbd0', // Light Pink
        'No Barcode In' => '#fd7e14', // orange
        'No Bio In' => '#dc3545', // Danger
        'Early Barcode' => '#007bff', // Blue / Primary
        'Late Barcode' => '#8a2be2', // violet
    );

    $data = [];
    $categories = [];

    $sql = "
            DECLARE @Year INT = ?;  
            DECLARE @Month INT = ?; 

            WITH DateRange AS (
                SELECT 
                    DATEADD(DAY, number, DATEFROMPARTS(@Year, @Month, 1)) AS report_date
                FROM 
                    master.dbo.spt_values
                WHERE 
                    type = 'P' AND 
                    number < DAY(EOMONTH(DATEFROMPARTS(@Year, @Month, 1))) AND
                    DATEADD(DAY, number, DATEFROMPARTS(@Year, @Month, 1)) <= CAST(GETDATE() AS DATE)  
            ),
            AttendanceRemarks AS (
                SELECT 
                    emp.provider, 
                    emp.emp_no, 
                    emp.full_name, 
                    emp.dept, 
                    emp.section, 
                    emp.process, 
                    emp.line_no, 
                    emp.shift_group, 
                    tio.date_updated AS time_in, 
                    tio.time_out, 
                    d.report_date AS day,  
                    b.shift, 
                    b.time_in AS b_time_in, 
                    b.time_out AS b_time_out, 
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
                CROSS JOIN DateRange d
                LEFT JOIN emp_mgt_db.dbo.t_time_in_out tio ON tio.emp_no = emp.emp_no AND tio.day = d.report_date  
                LEFT JOIN emp_mgt_backup.dbo.t_biometric_time_in_out b ON b.emp_no = emp.emp_no AND b.day = d.report_date  
                WHERE 
                    (emp.date_hired <= d.report_date) AND 
                    (emp.resigned_date IS NULL OR emp.resigned_date >= d.report_date) AND 
                    emp.dept = 'PD1' AND emp.section = 'Section 1' AND emp.line_no = '5101'
            )
            -- Final select to get counts based on time_out_remarks and report_date
            SELECT 
                day AS report_date,
                time_in_remarks AS remarks,
                COUNT(*) AS total_count 
            FROM 
                AttendanceRemarks 
            GROUP BY 
                day, time_in_remarks

            UNION ALL

            SELECT 
                day AS report_date,
                'With Time In Discrepancy' AS remarks, 
                COUNT(
                    CASE 
                        WHEN time_in_remarks IN ('No Barcode In', 'No Bio In', 'Early Barcode', 'Late', 'Late Barcode') 
                        THEN 1 
                    END
                ) AS total_count 
            FROM 
                AttendanceRemarks 
            GROUP BY 
                day
            ORDER BY 
                report_date ASC;  -- Order results by report_date in ascending order
            ";

    $params = [];

    $params[] = $year;
    $params[] = $month;

    $stmt = $conn->prepare($sql);

    $stmt->execute($params);

    // Initialize an array to hold the counts for each remarks
    $statusCounts = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Add unique report_date to categories
        if (!in_array($row['report_date'], $categories)) {
            $categories[] = $row['report_date'];
        }

        if (!empty($row['remarks'])) {
            // Create a unique key for remarks
            $remarks = $row['remarks'];

            // Extract month and year from report_date
            $reportDate = new DateTime($row['report_date']);
            $month = (int)$reportDate->format('m');
            $year = (int)$reportDate->format('Y');

            // Get the number of days in the specified month and year
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            // Initialize the statusCounts for this remarks if it doesn't exist
            if (!isset($statusCounts[$remarks])) {
                $statusCounts[$remarks] = array_fill(0, $daysInMonth, 0);
            }

            // Update the count for the specified status
            $dateIndex = array_search($row['report_date'], $categories);
            if ($dateIndex !== false) {
                $statusCounts[$remarks][$dateIndex] += intval($row['total_count']); // Use total_count for counts
            }
        }
    }

    // Create the final data structure
    foreach ($statusCounts as $remarks => $counts) {
        $data[] = [
            'name' => $remarks,
            'data' => $counts
        ];
    }

    // Encode the categories and data as JSON
    echo json_encode(['categories' => $categories, 'data' => $data, 'colorMap' => $color_map]);
}

if ($method == 'get_month_bio_vs_barcode_time_out_chart') {
    $year = $_GET['year'];
    $month = $_GET['month'];

    $color_map = array(
        'Late Bio' => '#ffc107', // Warning
        'Time Out OK' => '#28a745', // Success
        'No Entries Both' => '#6c757d', // Gray
        'Absent' => '#f8bbd0', // Light Pink
        'No Barcode Out' => '#fd7e14', // orange
        'No Bio Out' => '#dc3545', // Danger
        'Early Bio' => '#8a2be2', // violet
    );

    $data = [];
    $categories = [];

    $sql = "
            DECLARE @Year INT = ?;  
            DECLARE @Month INT = ?; 

            WITH DateRange AS (
                SELECT 
                    DATEADD(DAY, number, DATEFROMPARTS(@Year, @Month, 1)) AS report_date
                FROM 
                    master.dbo.spt_values
                WHERE 
                    type = 'P' AND 
                    number < DAY(EOMONTH(DATEFROMPARTS(@Year, @Month, 1))) AND
                    DATEADD(DAY, number, DATEFROMPARTS(@Year, @Month, 1)) <= CAST(GETDATE() AS DATE)  
            ),
            AttendanceRemarks AS (
                SELECT 
                    emp.provider, 
                    emp.emp_no, 
                    emp.full_name, 
                    emp.dept, 
                    emp.section, 
                    emp.process, 
                    emp.line_no, 
                    emp.shift_group, 
                    tio.date_updated AS time_in, 
                    tio.time_out, 
                    d.report_date AS day,  
                    b.shift, 
                    b.time_in AS b_time_in, 
                    b.time_out AS b_time_out, 
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
                CROSS JOIN DateRange d
                LEFT JOIN emp_mgt_db.dbo.t_time_in_out tio ON tio.emp_no = emp.emp_no AND tio.day = d.report_date  
                LEFT JOIN emp_mgt_backup.dbo.t_biometric_time_in_out b ON b.emp_no = emp.emp_no AND b.day = d.report_date  
                WHERE 
                    (emp.date_hired <= d.report_date) AND 
                    (emp.resigned_date IS NULL OR emp.resigned_date >= d.report_date) AND 
                    emp.dept = 'PD1' AND emp.section = 'Section 1' AND emp.line_no = '5101'
            )
            -- Final select to get counts based on time_out_remarks and report_date
            SELECT 
                day AS report_date,
                time_out_remarks AS remarks,
                COUNT(*) AS total_count 
            FROM 
                AttendanceRemarks 
            GROUP BY 
                day, time_out_remarks

            UNION ALL

            SELECT 
                day AS report_date,
                'With Time Out Discrepancy' AS remarks, 
                COUNT(
                    CASE 
                        WHEN time_out_remarks IN ('No Barcode Out', 'No Bio Out', 'Early Bio', 'Late Bio') 
                        THEN 1 
                    END
                ) AS total_count 
            FROM 
                AttendanceRemarks 
            GROUP BY 
                day
            ORDER BY 
                report_date ASC;  -- Order results by report_date in ascending order
            ";

    $params = [];

    $params[] = $year;
    $params[] = $month;

    $stmt = $conn->prepare($sql);

    $stmt->execute($params);

    // Initialize an array to hold the counts for each remarks
    $statusCounts = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Add unique report_date to categories
        if (!in_array($row['report_date'], $categories)) {
            $categories[] = $row['report_date'];
        }

        if (!empty($row['remarks'])) {
            // Create a unique key for remarks
            $remarks = $row['remarks'];

            // Extract month and year from report_date
            $reportDate = new DateTime($row['report_date']);
            $month = (int)$reportDate->format('m');
            $year = (int)$reportDate->format('Y');

            // Get the number of days in the specified month and year
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            // Initialize the statusCounts for this remarks if it doesn't exist
            if (!isset($statusCounts[$remarks])) {
                $statusCounts[$remarks] = array_fill(0, $daysInMonth, 0);
            }

            // Update the count for the specified status
            $dateIndex = array_search($row['report_date'], $categories);
            if ($dateIndex !== false) {
                $statusCounts[$remarks][$dateIndex] += intval($row['total_count']); // Use total_count for counts
            }
        }
    }

    // Create the final data structure
    foreach ($statusCounts as $remarks => $counts) {
        $data[] = [
            'name' => $remarks,
            'data' => $counts
        ];
    }

    // Encode the categories and data as JSON
    echo json_encode(['categories' => $categories, 'data' => $data, 'colorMap' => $color_map]);
}

if ($method == 'get_month_compliance_time_in_chart') {
    $year = $_GET['year'];
    $month = $_GET['month'];

    $color_map = array(
        'Compliance' => '#28a745', // Success
        'Non-Compliance' => '#dc3545', // Danger
    );

    $data = [];
    $categories = [];

    $sql = "
            DECLARE @Year INT = ?;  
            DECLARE @Month INT = ?; 

            WITH DateRange AS (
                SELECT 
                    DATEADD(DAY, number, DATEFROMPARTS(@Year, @Month, 1)) AS report_date
                FROM 
                    master.dbo.spt_values
                WHERE 
                    type = 'P' AND 
                    number < DAY(EOMONTH(DATEFROMPARTS(@Year, @Month, 1))) AND
                    DATEADD(DAY, number, DATEFROMPARTS(@Year, @Month, 1)) <= CAST(GETDATE() AS DATE)  
            ),
            AttendanceRemarks AS (
                SELECT 
                    emp.provider, 
                    emp.emp_no, 
                    emp.full_name, 
                    emp.dept, 
                    emp.section, 
                    emp.process, 
                    emp.line_no, 
                    emp.shift_group, 
                    tio.date_updated AS time_in, 
                    tio.time_out, 
                    d.report_date AS day,  
                    b.shift, 
                    b.time_in AS b_time_in, 
                    b.time_out AS b_time_out, 
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
                CROSS JOIN DateRange d
                LEFT JOIN emp_mgt_db.dbo.t_time_in_out tio ON tio.emp_no = emp.emp_no AND tio.day = d.report_date  
                LEFT JOIN emp_mgt_backup.dbo.t_biometric_time_in_out b ON b.emp_no = emp.emp_no AND b.day = d.report_date  
                WHERE 
                    (emp.date_hired <= d.report_date) AND 
                    (emp.resigned_date IS NULL OR emp.resigned_date >= d.report_date) AND 
                    emp.dept = 'PD1' AND emp.section = 'Section 1' AND emp.line_no = '5101'
            )
            -- Final select to get counts based on time_out_remarks and report_date
            SELECT 
                day AS report_date,
                'Compliance' AS remarks, 
                CASE 
                    WHEN COUNT(CASE 
                            WHEN time_in_remarks IN ('No Barcode In', 'No Bio In', 'Early Barcode', 'Late', 'Late Barcode', 'Time In OK') 
                            THEN 1 
                        END) = 0 THEN 0 
                    ELSE 
                    CAST(
                        (COUNT(CASE 
                            WHEN time_in_remarks = 'Time In OK' 
                            THEN 1 
                        END) * 1.0 / 
                        COUNT(CASE 
                            WHEN time_in_remarks IN ('No Barcode In', 'No Bio In', 'Early Barcode', 'Late', 'Late Barcode', 'Time In OK') 
                            THEN 1 
                        END)) * 100.0 
                    AS DECIMAL(10, 2))
                END AS total_percentage 
            FROM 
                AttendanceRemarks 
            GROUP BY 
                day

            UNION ALL

            SELECT 
                day AS report_date,
                'Non-Compliance' AS remarks, 
                CASE 
                    WHEN COUNT(*) = 0 THEN 0 
                    ELSE 
                    CAST(
                        (COUNT(CASE 
                            WHEN time_in_remarks IN ('No Barcode In', 'No Bio In', 'Early Barcode', 'Late', 'Late Barcode') 
                            THEN 1 
                        END) * 1.0 / 
                        COUNT(*)) * 100.0 
                    AS DECIMAL(10, 2))
                END AS total_percentage 
            FROM 
                AttendanceRemarks 
            GROUP BY 
                day
            ORDER BY 
                report_date ASC;  -- Order results by report_date in ascending order
            ";

    $params = [];

    $params[] = $year;
    $params[] = $month;

    $stmt = $conn->prepare($sql);

    $stmt->execute($params);

    // Initialize an array to hold the counts for each remarks
    $statusCounts = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Add unique report_date to categories
        if (!in_array($row['report_date'], $categories)) {
            $categories[] = $row['report_date'];
        }

        if (!empty($row['remarks'])) {
            // Create a unique key for remarks
            $remarks = $row['remarks'];

            // Extract month and year from report_date
            $reportDate = new DateTime($row['report_date']);
            $month = (int)$reportDate->format('m');
            $year = (int)$reportDate->format('Y');

            // Get the number of days in the specified month and year
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            // Initialize the statusCounts for this remarks if it doesn't exist
            if (!isset($statusCounts[$remarks])) {
                $statusCounts[$remarks] = array_fill(0, $daysInMonth, 0);
            }

            // Update the count for the specified status
            $dateIndex = array_search($row['report_date'], $categories);
            if ($dateIndex !== false) {
                $statusCounts[$remarks][$dateIndex] += floatval($row['total_percentage']); // Use total_percentage for counts
            }
        }
    }

    // Create the final data structure
    foreach ($statusCounts as $remarks => $counts) {
        $data[] = [
            'name' => $remarks,
            'data' => $counts
        ];
    }

    // Encode the categories and data as JSON
    echo json_encode(['categories' => $categories, 'data' => $data, 'colorMap' => $color_map]);
}

if ($method == 'get_month_compliance_time_out_chart') {
    $year = $_GET['year'];
    $month = $_GET['month'];

    $color_map = array(
        'Compliance' => '#28a745', // Success
        'Non-Compliance' => '#dc3545', // Danger
    );

    $data = [];
    $categories = [];

    $sql = "
            DECLARE @Year INT = ?;  
            DECLARE @Month INT = ?; 

            WITH DateRange AS (
                SELECT 
                    DATEADD(DAY, number, DATEFROMPARTS(@Year, @Month, 1)) AS report_date
                FROM 
                    master.dbo.spt_values
                WHERE 
                    type = 'P' AND 
                    number < DAY(EOMONTH(DATEFROMPARTS(@Year, @Month, 1))) AND
                    DATEADD(DAY, number, DATEFROMPARTS(@Year, @Month, 1)) <= CAST(GETDATE() AS DATE)  
            ),
            AttendanceRemarks AS (
                SELECT 
                    emp.provider, 
                    emp.emp_no, 
                    emp.full_name, 
                    emp.dept, 
                    emp.section, 
                    emp.process, 
                    emp.line_no, 
                    emp.shift_group, 
                    tio.date_updated AS time_in, 
                    tio.time_out, 
                    d.report_date AS day,  
                    b.shift, 
                    b.time_in AS b_time_in, 
                    b.time_out AS b_time_out, 
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
                CROSS JOIN DateRange d
                LEFT JOIN emp_mgt_db.dbo.t_time_in_out tio ON tio.emp_no = emp.emp_no AND tio.day = d.report_date  
                LEFT JOIN emp_mgt_backup.dbo.t_biometric_time_in_out b ON b.emp_no = emp.emp_no AND b.day = d.report_date  
                WHERE 
                    (emp.date_hired <= d.report_date) AND 
                    (emp.resigned_date IS NULL OR emp.resigned_date >= d.report_date) AND 
                    emp.dept = 'PD1' AND emp.section = 'Section 1' AND emp.line_no = '5101'
            )
            -- Final select to get counts based on time_out_remarks and report_date
            SELECT 
                day AS report_date,
                'Compliance' AS remarks, 
                CASE 
                    WHEN COUNT(CASE 
                            WHEN time_out_remarks IN ('No Barcode Out', 'No Bio Out', 'Early Bio', 'Late Bio', 'Time Out OK') 
                            THEN 1 
                        END) = 0 THEN 0 
                    ELSE 
                    CAST(
                        (COUNT(CASE 
                            WHEN time_out_remarks = 'Time Out OK' 
                            THEN 1 
                        END) * 1.0 / 
                        COUNT(CASE 
                            WHEN time_out_remarks IN ('No Barcode Out', 'No Bio Out', 'Early Bio', 'Late Bio', 'Time Out OK') 
                            THEN 1 
                        END)) * 100.0 
                    AS DECIMAL(10, 2))
                END AS total_percentage 
            FROM 
                AttendanceRemarks 
            GROUP BY 
                day

            UNION ALL

            SELECT 
                day AS report_date,
                'Non-Compliance' AS remarks, 
                CASE 
                    WHEN COUNT(*) = 0 THEN 0 
                    ELSE 
                    CAST(
                        (COUNT(CASE 
                            WHEN time_out_remarks IN ('No Barcode Out', 'No Bio Out', 'Early Bio', 'Late Bio') 
                            THEN 1 
                        END) * 1.0 / 
                        COUNT(*)) * 100.0 
                    AS DECIMAL(10, 2))
                END AS total_percentage 
            FROM 
                AttendanceRemarks 
            GROUP BY 
                day
            ORDER BY 
                report_date ASC;  -- Order results by report_date in ascending order
            ";

    $params = [];

    $params[] = $year;
    $params[] = $month;

    $stmt = $conn->prepare($sql);

    $stmt->execute($params);

    // Initialize an array to hold the counts for each remarks
    $statusCounts = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Add unique report_date to categories
        if (!in_array($row['report_date'], $categories)) {
            $categories[] = $row['report_date'];
        }

        if (!empty($row['remarks'])) {
            // Create a unique key for remarks
            $remarks = $row['remarks'];

            // Extract month and year from report_date
            $reportDate = new DateTime($row['report_date']);
            $month = (int)$reportDate->format('m');
            $year = (int)$reportDate->format('Y');

            // Get the number of days in the specified month and year
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            // Initialize the statusCounts for this remarks if it doesn't exist
            if (!isset($statusCounts[$remarks])) {
                $statusCounts[$remarks] = array_fill(0, $daysInMonth, 0);
            }

            // Update the count for the specified status
            $dateIndex = array_search($row['report_date'], $categories);
            if ($dateIndex !== false) {
                $statusCounts[$remarks][$dateIndex] += floatval($row['total_percentage']); // Use total_percentage for counts
            }
        }
    }

    // Create the final data structure
    foreach ($statusCounts as $remarks => $counts) {
        $data[] = [
            'name' => $remarks,
            'data' => $counts
        ];
    }

    // Encode the categories and data as JSON
    echo json_encode(['categories' => $categories, 'data' => $data, 'colorMap' => $color_map]);
}

if ($method == 'get_bio_vs_barcode_data') {
    $c = 0;

    $query = "
                DECLARE @Year INT = 2026;  -- Get the current year
                DECLARE @Month INT = 1; 

                WITH DateRange AS (
                    SELECT 
                        DATEADD(DAY, number, DATEFROMPARTS(@Year, @Month, 1)) AS report_date
                    FROM 
                        master.dbo.spt_values
                    WHERE 
                        type = 'P' AND 
                        number < DAY(EOMONTH(DATEFROMPARTS(@Year, @Month, 1))) AND
                        DATEADD(DAY, number, DATEFROMPARTS(@Year, @Month, 1)) <= CAST(GETDATE() AS DATE)  -- Ensure dates are before today
                )

                SELECT 
                    d.report_date AS day,  -- Updated to use report_date
                    emp.emp_no, 
                    emp.full_name, 
                    emp.dept, 
                    emp.section, 
                    emp.line_no, 
                    emp.process, 
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
                CROSS JOIN DateRange d
                LEFT JOIN emp_mgt_db.dbo.t_time_in_out tio ON tio.emp_no = emp.emp_no AND tio.day = d.report_date  -- Updated to use report_date
                LEFT JOIN emp_mgt_backup.dbo.t_biometric_time_in_out b ON b.emp_no = emp.emp_no AND b.day = d.report_date  -- Updated to use report_date
                WHERE 
                    (emp.date_hired <= d.report_date) AND 
                    (emp.resigned_date IS NULL OR emp.resigned_date >= d.report_date) 
                    AND emp.dept = 'PD1' 
                    AND emp.section = 'Section 1' 
                    AND emp.line_no = '5101' 
                ORDER BY 
                    d.report_date ASC

                OPTION (MAXRECURSION 0);";
    
    $params = [];

	// $params[] = $day;

    $stmt = $conn->prepare($query);
    $stmt->execute($params);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        do {
            $c++;

            echo '<tr>';
            echo '<td>' . $c . '</td>';
            echo '<td>' . $row['day'] . '</td>';
            echo '<td>' . $row['emp_no'] . '</td>';
            echo '<td>' . $row['full_name'] . '</td>';
            echo '<td>' . $row['dept'] . '</td>';
            echo '<td>' . $row['section'] . '</td>';
            echo '<td>' . $row['line_no'] . '</td>';
            echo '<td>' . $row['process'] . '</td>';
            echo '<td>' . $row['time_in_remarks'] . '</td>';
            echo '<td>' . $row['time_out_remarks'] . '</td>';
            echo '</tr>';
        } while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
    }
}

$conn = NULL;
