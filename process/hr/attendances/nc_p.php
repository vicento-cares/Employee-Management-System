<?php
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

require '../../conn.php';

$method = $_POST['method'];

if ($method == 'get_top_section_no_time_out_chart') {
    $categories = [];

    $sql = "
                DECLARE @Year INT = YEAR(GETDATE());  -- Specify the year
                DECLARE @Month INT = MONTH(GETDATE());    -- Specify the month

                WITH TimeRecords AS (
                    SELECT 
                        emp.emp_no,
                        emp.section,
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
                        emp.emp_no, emp.section 
                )

                SELECT TOP 10 
                    section,
                    COUNT(CASE 
                        WHEN NullTimeOutCount > 2 THEN 1 
                    END) AS AuditCount,
                    COUNT(CASE 
                        WHEN NullTimeOutCount <= 2 AND NullTimeOutCount > 0 THEN 1 
                    END) AS WarningCount
                FROM 
                    TimeRecords
                WHERE 
                    NullTimeOutCount > 2  -- Filter to show only employees with null time records (0 to see warning)
                GROUP BY 
                    section
                ORDER BY 
                    AuditCount DESC, section ASC;
            ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Add unique section to categories
        if (!in_array($row['section'], $categories)) {
            $categories[] = $row['section'];
        }

        $data['AuditCount'][] = (int)$row['AuditCount'];
    }

    // Create the final data structure
    $finalData = [
        'categories' => $categories,
        'data' => [
            [
                'name' => 'Employee Count',
                'data' => $data['AuditCount']
            ]
        ]
    ];

    // Encode the categories and data as JSON
    echo json_encode($finalData);
}

if ($method == 'get_top_line_no_time_out_chart') {
    $categories = [];

    $sql = "
                DECLARE @Year INT = YEAR(GETDATE());  -- Specify the year
                DECLARE @Month INT = MONTH(GETDATE());    -- Specify the month

                WITH TimeRecords AS (
                    SELECT 
                        emp.emp_no,
                        emp.line_no,
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
                        emp.emp_no, emp.line_no 
                )

                SELECT TOP 10 
                    line_no,
                    COUNT(CASE 
                        WHEN NullTimeOutCount > 2 THEN 1 
                    END) AS AuditCount,
                    COUNT(CASE 
                        WHEN NullTimeOutCount <= 2 AND NullTimeOutCount > 0 THEN 1 
                    END) AS WarningCount
                FROM 
                    TimeRecords
                WHERE 
                    NullTimeOutCount > 2  -- Filter to show only employees with null time records (0 to see warning)
                GROUP BY 
                    line_no
                ORDER BY 
                    AuditCount DESC, line_no ASC;
            ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Add unique line_no to categories
        if (!in_array($row['line_no'], $categories)) {
            $categories[] = $row['line_no'];
        }

        $data['AuditCount'][] = (int)$row['AuditCount'];
    }

    // Create the final data structure
    $finalData = [
        'categories' => $categories,
        'data' => [
            [
                'name' => 'Employee Count',
                'data' => $data['AuditCount']
            ]
        ]
    ];

    // Encode the categories and data as JSON
    echo json_encode($finalData);
}

if ($method == 'get_top_process_no_time_out_chart') {
    $categories = [];

    $sql = "
                DECLARE @Year INT = YEAR(GETDATE());  -- Specify the year
                DECLARE @Month INT = MONTH(GETDATE());    -- Specify the month

                WITH TimeRecords AS (
                    SELECT 
                        emp.emp_no,
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
                        emp.emp_no, emp.process 
                )

                SELECT TOP 10 
                    process,
                    COUNT(CASE 
                        WHEN NullTimeOutCount > 2 THEN 1 
                    END) AS AuditCount,
                    COUNT(CASE 
                        WHEN NullTimeOutCount <= 2 AND NullTimeOutCount > 0 THEN 1 
                    END) AS WarningCount
                FROM 
                    TimeRecords
                WHERE 
                    NullTimeOutCount > 2  -- Filter to show only employees with null time records (0 to see warning)
                GROUP BY 
                    process
                ORDER BY 
                    AuditCount DESC, process ASC;
            ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Add unique process to categories
        if (!in_array($row['process'], $categories)) {
            $categories[] = $row['process'];
        }

        $data['AuditCount'][] = (int)$row['AuditCount'];
    }

    // Create the final data structure
    $finalData = [
        'categories' => $categories,
        'data' => [
            [
                'name' => 'Employee Count',
                'data' => $data['AuditCount']
            ]
        ]
    ];

    // Encode the categories and data as JSON
    echo json_encode($finalData);
}

if ($method == 'get_month_section_no_time_out_chart') {
    $color_map = array(
        'FAP1 Suzuki' => '#f8bbd0', // Light Pink
        'FAP1 Mazda' => '#ffc107', // Warning
        'FAP2' => '#28a745', // Success
        'Gemba Compliance' => '#fd7e14', // orange
        'FAP4' => '#dc3545', // Danger
        'FAP3' => '#e83e8c', // Dark Pink
        'First Process' => '#007bff', // Blue
        'Secondary 1 Process' => '#6c757d', // Gray
        'Secondary 2 Process' => '#20c997', // Teal
        'Section 1' => '#f8bbd0', // Light Pink
        'Section 2' => '#28a745', // Success
        'Section 3' => '#ffc107', // Warning
        'Section 4' => '#e83e8c', // Dark Pink
        'Section 5' => '#fd7e14', // orange
        'Section 6' => '#007bff', // Blue / Primary
        'Section 7' => '#dc3545', // Danger
        'Section 8' => '#8a2be2', // violet
    );

    $data = [];
    $categories = [];

    $sql = "
            DECLARE @Year INT = YEAR(GETDATE());  -- Get the current year
            DECLARE @Month INT = MONTH(GETDATE()); -- Get the current month

            WITH DateRange AS (
                SELECT 
                    DATEADD(DAY, number, DATEFROMPARTS(@Year, @Month, 1)) AS report_date
                FROM 
                    master.dbo.spt_values
                WHERE 
                    type = 'P' AND 
                    number < DAY(EOMONTH(DATEFROMPARTS(@Year, @Month, 1)))  -- Generate dates for the month
            ),
            EmployeeTimeInOut AS (
                SELECT 
                    emp.emp_no,
                    emp.section,
                    CAST(tio.day AS DATE) AS report_date
                FROM 
                    m_employees emp
                LEFT JOIN
                    t_time_in_out tio ON emp.emp_no = tio.emp_no 
                WHERE
                    emp.resigned = 0 AND 
                    (tio.day >= DATEADD(HOUR, 6, CAST(DATEFROMPARTS(@Year, @Month, 1) AS DATETIME2)) AND 
                    tio.day < DATEADD(HOUR, 6, DATEADD(DAY, 1, CAST(EOMONTH(DATEFROMPARTS(@Year, @Month, 1)) AS DATETIME2)))) 
                    AND CAST(tio.day AS DATE) <> CAST(GETDATE() AS DATE)  -- Exclude today's date
                    AND tio.time_out IS NULL
            )

            SELECT 
                dr.report_date,
                eio.section,
                COUNT(eio.emp_no) AS emp_no_count
            FROM 
                DateRange dr
            LEFT JOIN
                EmployeeTimeInOut eio ON dr.report_date = eio.report_date  -- Join on the report_date
            GROUP BY 
                dr.report_date, eio.section
            ORDER BY 
                dr.report_date ASC, eio.section ASC;
            ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Initialize an array to hold the counts for each section
    $statusCounts = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Add unique report_date to categories
        if (!in_array($row['report_date'], $categories)) {
            $categories[] = $row['report_date'];
        }

        if (!empty($row['section'])) {
            // Create a unique key for section
            $section = $row['section'];

            // Extract month and year from report_date
            $reportDate = new DateTime($row['report_date']);
            $month = (int)$reportDate->format('m');
            $year = (int)$reportDate->format('Y');

            // Get the number of days in the specified month and year
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            // Initialize the statusCounts for this section if it doesn't exist
            if (!isset($statusCounts[$section])) {
                $statusCounts[$section] = array_fill(0, $daysInMonth, 0);
            }

            // Update the count for the specified status
            $dateIndex = array_search($row['report_date'], $categories);
            if ($dateIndex !== false) {
                $statusCounts[$section][$dateIndex] += intval($row['emp_no_count']); // Use emp_no_count for counts
            }
        }
    }

    // Create the final data structure
    foreach ($statusCounts as $section => $counts) {
        $data[] = [
            'name' => $section,
            'data' => $counts
        ];
    }

    // Encode the categories and data as JSON
    echo json_encode(['categories' => $categories, 'data' => $data, 'colorMap' => $color_map]);
}

if ($method == 'recent_non_compliance_list') {
    $c = 0;

    $query = "
                -- Get Manpower Non-Compliance (No Time Out Only)
                DECLARE @Year INT = YEAR(GETDATE());  -- Specify the year
                DECLARE @Month INT = MONTH(GETDATE());    -- Specify the month

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

    $stmt = $conn->prepare($query);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $c++;

        echo '<tr style="cursor:pointer;" class="modal-trigger" 
                    data-toggle="modal" data-target="#non_compliance_details" 
                    onclick="get_recent_non_compliance_details(&quot;' . 
                    $row['emp_no'] . '~!~' . 
                    $row['full_name'] . '~!~' . 
                    $row['dept'] . '~!~' . 
                    $row['section'] . '~!~' . 
                    $row['line_no'] . '~!~' . 
                    $row['process'] . '~!~' . 
                    $row['NullTimeOutCount'] . '&quot;)">';
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

if ($method == 'get_non_compliance_year_dropdown_search') {
    $sql = "SELECT 
                DISTINCT YEAR(day) AS Year
            FROM 
                t_time_in_out
            ORDER BY 
                Year";

    $stmt = $conn->prepare($sql);

    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        echo '<option selected value="">Select Year</option>';
        do {
            echo '<option value="' . htmlspecialchars($row['Year']) . '">' . htmlspecialchars($row['Year']) . '</option>';
        } while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
    } else {
        echo '<option selected value="">Select Year</option>';
    }
}

if ($method == 'get_top_section_no_time_out_search_chart') {
    $year = $_POST['year'];
    $month = $_POST['month'];

    $categories = [];

    $sql = "
                DECLARE @Year INT = ?;  -- Specify the year
                DECLARE @Month INT = ?;    -- Specify the month

                WITH TimeRecords AS (
                    SELECT 
                        emp.emp_no,
                        emp.section,
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
                        emp.emp_no, emp.section 
                )

                SELECT TOP 10 
                    section,
                    COUNT(CASE 
                        WHEN NullTimeOutCount > 2 THEN 1 
                    END) AS AuditCount,
                    COUNT(CASE 
                        WHEN NullTimeOutCount <= 2 AND NullTimeOutCount > 0 THEN 1 
                    END) AS WarningCount
                FROM 
                    TimeRecords
                WHERE 
                    NullTimeOutCount > 2  -- Filter to show only employees with null time records (0 to see warning)
                GROUP BY 
                    section
                ORDER BY 
                    AuditCount DESC, section ASC;
            ";

    $params = [];

    $params[] = $year;
    $params[] = $month;

    $stmt = $conn->prepare($sql);

    $stmt->execute($params);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Add unique section to categories
        if (!in_array($row['section'], $categories)) {
            $categories[] = $row['section'];
        }

        $data['AuditCount'][] = (int)$row['AuditCount'];
    }

    // Create the final data structure
    $finalData = [
        'categories' => $categories,
        'data' => [
            [
                'name' => 'Employee Count',
                'data' => $data['AuditCount']
            ]
        ]
    ];

    // Encode the categories and data as JSON
    echo json_encode($finalData);
}

if ($method == 'get_top_line_no_time_out_search_chart') {
    $year = $_POST['year'];
    $month = $_POST['month'];

    $categories = [];

    $sql = "
                DECLARE @Year INT = ?;  -- Specify the year
                DECLARE @Month INT = ?;    -- Specify the month

                WITH TimeRecords AS (
                    SELECT 
                        emp.emp_no,
                        emp.line_no,
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
                        emp.emp_no, emp.line_no 
                )

                SELECT TOP 10 
                    line_no,
                    COUNT(CASE 
                        WHEN NullTimeOutCount > 2 THEN 1 
                    END) AS AuditCount,
                    COUNT(CASE 
                        WHEN NullTimeOutCount <= 2 AND NullTimeOutCount > 0 THEN 1 
                    END) AS WarningCount
                FROM 
                    TimeRecords
                WHERE 
                    NullTimeOutCount > 2  -- Filter to show only employees with null time records (0 to see warning)
                GROUP BY 
                    line_no
                ORDER BY 
                    AuditCount DESC, line_no ASC;
            ";

    $params = [];

    $params[] = $year;
    $params[] = $month;

    $stmt = $conn->prepare($sql);

    $stmt->execute($params);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Add unique line_no to categories
        if (!in_array($row['line_no'], $categories)) {
            $categories[] = $row['line_no'];
        }

        $data['AuditCount'][] = (int)$row['AuditCount'];
    }

    // Create the final data structure
    $finalData = [
        'categories' => $categories,
        'data' => [
            [
                'name' => 'Employee Count',
                'data' => $data['AuditCount']
            ]
        ]
    ];

    // Encode the categories and data as JSON
    echo json_encode($finalData);
}

if ($method == 'get_top_process_no_time_out_search_chart') {
    $year = $_POST['year'];
    $month = $_POST['month'];

    $categories = [];

    $sql = "
                DECLARE @Year INT = ?;  -- Specify the year
                DECLARE @Month INT = ?;    -- Specify the month

                WITH TimeRecords AS (
                    SELECT 
                        emp.emp_no,
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
                        emp.emp_no, emp.process 
                )

                SELECT TOP 10 
                    process,
                    COUNT(CASE 
                        WHEN NullTimeOutCount > 2 THEN 1 
                    END) AS AuditCount,
                    COUNT(CASE 
                        WHEN NullTimeOutCount <= 2 AND NullTimeOutCount > 0 THEN 1 
                    END) AS WarningCount
                FROM 
                    TimeRecords
                WHERE 
                    NullTimeOutCount > 2  -- Filter to show only employees with null time records (0 to see warning)
                GROUP BY 
                    process
                ORDER BY 
                    AuditCount DESC, process ASC;
            ";

    $params = [];

    $params[] = $year;
    $params[] = $month;

    $stmt = $conn->prepare($sql);

    $stmt->execute($params);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Add unique process to categories
        if (!in_array($row['process'], $categories)) {
            $categories[] = $row['process'];
        }

        $data['AuditCount'][] = (int)$row['AuditCount'];
    }

    // Create the final data structure
    $finalData = [
        'categories' => $categories,
        'data' => [
            [
                'name' => 'Employee Count',
                'data' => $data['AuditCount']
            ]
        ]
    ];

    // Encode the categories and data as JSON
    echo json_encode($finalData);
}

if ($method == 'get_month_section_no_time_out_search_chart') {
    $year = $_POST['year'];
    $month = $_POST['month'];

    $color_map = array(
        'FAP1 Suzuki' => '#f8bbd0', // Light Pink
        'FAP1 Mazda' => '#ffc107', // Warning
        'FAP2' => '#28a745', // Success
        'Gemba Compliance' => '#fd7e14', // orange
        'FAP4' => '#dc3545', // Danger
        'FAP3' => '#e83e8c', // Dark Pink
        'First Process' => '#007bff', // Blue
        'Secondary 1 Process' => '#6c757d', // Gray
        'Secondary 2 Process' => '#20c997', // Teal
        'Section 1' => '#f8bbd0', // Light Pink
        'Section 2' => '#28a745', // Success
        'Section 3' => '#ffc107', // Warning
        'Section 4' => '#e83e8c', // Dark Pink
        'Section 5' => '#fd7e14', // orange
        'Section 6' => '#007bff', // Blue / Primary
        'Section 7' => '#dc3545', // Danger
        'Section 8' => '#8a2be2', // violet
    );

    $data = [];
    $categories = [];

    $sql = "
            DECLARE @Year INT = ?;  -- Get the current year
            DECLARE @Month INT = ?; -- Get the current month

            WITH DateRange AS (
                SELECT 
                    DATEADD(DAY, number, DATEFROMPARTS(@Year, @Month, 1)) AS report_date
                FROM 
                    master.dbo.spt_values
                WHERE 
                    type = 'P' AND 
                    number < DAY(EOMONTH(DATEFROMPARTS(@Year, @Month, 1)))  -- Generate dates for the month
            ),
            EmployeeTimeInOut AS (
                SELECT 
                    emp.emp_no,
                    emp.section,
                    CAST(tio.day AS DATE) AS report_date
                FROM 
                    m_employees emp
                LEFT JOIN
                    t_time_in_out tio ON emp.emp_no = tio.emp_no 
                WHERE
                    emp.resigned = 0 AND 
                    (tio.day >= DATEADD(HOUR, 6, CAST(DATEFROMPARTS(@Year, @Month, 1) AS DATETIME2)) AND 
                    tio.day < DATEADD(HOUR, 6, DATEADD(DAY, 1, CAST(EOMONTH(DATEFROMPARTS(@Year, @Month, 1)) AS DATETIME2)))) 
                    AND CAST(tio.day AS DATE) <> CAST(GETDATE() AS DATE)  -- Exclude today's date
                    AND tio.time_out IS NULL
            )

            SELECT 
                dr.report_date,
                eio.section,
                COUNT(eio.emp_no) AS emp_no_count
            FROM 
                DateRange dr
            LEFT JOIN
                EmployeeTimeInOut eio ON dr.report_date = eio.report_date  -- Join on the report_date
            GROUP BY 
                dr.report_date, eio.section
            ORDER BY 
                dr.report_date ASC, eio.section ASC;
            ";

    $params = [];

    $params[] = $year;
    $params[] = $month;

    $stmt = $conn->prepare($sql);

    $stmt->execute($params);

    // Initialize an array to hold the counts for each section
    $statusCounts = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Add unique report_date to categories
        if (!in_array($row['report_date'], $categories)) {
            $categories[] = $row['report_date'];
        }

        if (!empty($row['section'])) {
            // Create a unique key for section
            $section = $row['section'];

            // Extract month and year from report_date
            $reportDate = new DateTime($row['report_date']);
            $month = (int)$reportDate->format('m');
            $year = (int)$reportDate->format('Y');

            // Get the number of days in the specified month and year
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            // Initialize the statusCounts for this section if it doesn't exist
            if (!isset($statusCounts[$section])) {
                $statusCounts[$section] = array_fill(0, $daysInMonth, 0);
            }

            // Update the count for the specified status
            $dateIndex = array_search($row['report_date'], $categories);
            if ($dateIndex !== false) {
                $statusCounts[$section][$dateIndex] += intval($row['emp_no_count']); // Use emp_no_count for counts
            }
        }
    }

    // Create the final data structure
    foreach ($statusCounts as $section => $counts) {
        $data[] = [
            'name' => $section,
            'data' => $counts
        ];
    }

    // Encode the categories and data as JSON
    echo json_encode(['categories' => $categories, 'data' => $data, 'colorMap' => $color_map]);
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

        echo '<tr style="cursor:pointer;" class="modal-trigger" 
                    data-toggle="modal" data-target="#non_compliance_details" 
                    onclick="get_non_compliance_details(&quot;' . 
                    $row['emp_no'] . '~!~' . 
                    $row['full_name'] . '~!~' . 
                    $row['dept'] . '~!~' . 
                    $row['section'] . '~!~' . 
                    $row['line_no'] . '~!~' . 
                    $row['process'] . '~!~' . 
                    $row['NullTimeOutCount'] . '&quot;)">';
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

if ($method == 'get_emp_monthly_no_time_out_chart') {
    $year = $_POST['year'];
    $emp_no = $_POST['emp_no'];

    $categories = [];

    $sql = "
                DECLARE @Year INT = ?;  -- Specify the year
                DECLARE @EmpNo NVARCHAR(255) = ?;  -- Place your specific Employee Number here

                WITH DateRange AS (
                    SELECT 
                        FORMAT(DATEADD(MONTH, number, DATEFROMPARTS(@Year, 1, 1)), 'yyyy-MM') AS report_month
                    FROM 
                        master.dbo.spt_values
                    WHERE 
                        type = 'P' AND 
                        number < 12  -- Generate months from January (0) to December (11)
                ),
                EmployeeTimeOut AS (
                    SELECT 
                        emp.emp_no,
                        tio.day,
                        tio.shift,
                        tio.time_in,
                        tio.time_out,
                        DATEADD(MONTH, DATEDIFF(MONTH, 0, tio.day), 0) AS report_month  -- Get the first day of the month for the day
                    FROM  
                        m_employees emp  
                    LEFT JOIN 
                        t_time_in_out tio ON emp.emp_no = tio.emp_no 
                        AND emp.resigned = 0 
                        AND YEAR(tio.day) = @Year  -- Adjusted to include past years
                        AND CAST(tio.day AS DATE) <> CAST(GETDATE() AS DATE)  -- Exclude today's date
                    WHERE
                        emp.emp_no = @EmpNo  -- Filter by specific employee number
                        AND tio.time_out IS NULL  -- This condition checks for employees without time records
                )

                SELECT 
                    dr.report_month,
                    COUNT(et.emp_no) AS null_time_out_count  -- Count the number of null time outs per month
                FROM 
                    DateRange dr
                LEFT JOIN 
                    EmployeeTimeOut et ON dr.report_month = FORMAT(et.report_month, 'yyyy-MM')
                GROUP BY 
                    dr.report_month
                ORDER BY 
                    dr.report_month;
            ";

    $params = [];

    $params[] = $year;
    $params[] = $emp_no;

    $stmt = $conn->prepare($sql);

    $stmt->execute($params);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Add unique report_month to categories
        if (!in_array($row['report_month'], $categories)) {
            $categories[] = $row['report_month'];
        }

        $data['TotalNoTimeOut'][] = (int)$row['null_time_out_count'];
    }

    // Create the final data structure
    $finalData = [
        'categories' => $categories,
        'data' => [
            [
                'name' => 'Total No Time Out Count',
                'data' => $data['TotalNoTimeOut']
            ]
        ]
    ];

    // Encode the categories and data as JSON
    echo json_encode($finalData);
}

if ($method == 'non_compliance_details_list') {
    $year = $_POST['year'];
    $month = $_POST['month'];
    $emp_no = $_POST['emp_no'];

    $c = 0;

    $query = "
                DECLARE @Year INT = ?;  -- Specify the year
                DECLARE @Month INT = ?;    -- Specify the month

                SELECT 
                    emp.emp_no,
                    tio.emp_no,
                    tio.day,
                    tio.shift,
                    CONVERT(VARCHAR, tio.date_updated, 120) AS date_updated,
                    CONVERT(VARCHAR, tio.time_in, 120) AS time_in,
                    CONVERT(VARCHAR, tio.time_out, 120) AS time_out
                FROM 
                    m_employees emp
                LEFT JOIN
                    t_time_in_out tio ON emp.emp_no = tio.emp_no 
                    AND emp.resigned = 0 
                    AND MONTH(tio.day) = @Month 
                    AND YEAR(tio.day) = @Year
                WHERE
                    tio.emp_no = ?  -- Specific Employee Number Here
                    AND tio.emp_no IS NOT NULL 
                    AND tio.time_out IS NULL  -- This condition checks for employees without time records
                    AND CAST(tio.day AS DATE) <> CAST(GETDATE() AS DATE);  -- Exclude today's date
            ";

    $params = [];

    $params[] = $year;
    $params[] = $month;
    $params[] = $emp_no;

    $stmt = $conn->prepare($query);

    $stmt->execute($params);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $c++;

        echo '<tr>';
        echo '<td>' . $c . '</td>';
        echo '<td>' . $row['emp_no'] . '</td>';
        echo '<td>' . $row['day'] . '</td>';
        echo '<td>' . $row['shift'] . '</td>';
        echo '<td>' . $row['date_updated'] . '</td>';
        echo '<td>' . $row['time_in'] . '</td>';
        echo '<td>' . $row['time_out'] . '</td>';
        echo '</tr>';
    }
}

if ($method == 'past_no_time_out_record_list') {
    $year = $_POST['year'];
    $month = $_POST['month'];
    $emp_no = $_POST['emp_no'];

    $c = 0;

    $query = "
                DECLARE @Year INT = ?;  -- Specify the year
                DECLARE @Month INT = ?;    -- Specify the month

                SELECT 
                    emp.emp_no,
                    tio.emp_no,
                    tio.day,
                    tio.shift,
                    CONVERT(VARCHAR, tio.date_updated, 120) AS date_updated,
                    CONVERT(VARCHAR, tio.time_in, 120) AS time_in,
                    CONVERT(VARCHAR, tio.time_out, 120) AS time_out
                FROM 
                    m_employees emp
                LEFT JOIN
                    t_time_in_out tio ON emp.emp_no = tio.emp_no 
                    AND emp.resigned = 0 
                WHERE
                    tio.emp_no = ?  -- Place your specific Employee Number here
                    AND tio.emp_no IS NOT NULL 
                    AND tio.time_out IS NULL  -- This condition checks for employees without time records
                    AND (
                        YEAR(tio.day) < @Year OR 
                        (YEAR(tio.day) = @Year AND MONTH(tio.day) < @Month)
                    )  -- Filter for records before the specified year and month
                    AND CAST(tio.day AS DATE) <> CAST(GETDATE() AS DATE)  -- Exclude today's date
                ORDER BY
	                tio.day DESC;
            ";

    $params = [];

    $params[] = $year;
    $params[] = $month;
    $params[] = $emp_no;

    $stmt = $conn->prepare($query);

    $stmt->execute($params);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $c++;

        echo '<tr>';
        echo '<td>' . $c . '</td>';
        echo '<td>' . $row['emp_no'] . '</td>';
        echo '<td>' . $row['day'] . '</td>';
        echo '<td>' . $row['shift'] . '</td>';
        echo '<td>' . $row['date_updated'] . '</td>';
        echo '<td>' . $row['time_in'] . '</td>';
        echo '<td>' . $row['time_out'] . '</td>';
        echo '</tr>';
    }
}

$conn = NULL;
