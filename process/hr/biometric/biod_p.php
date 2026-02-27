<?php
require '../../conn.php';

$method = $_GET['method'];

$time_in_color_map = array(
    'Late' => '#ffc107', // Warning
    'Time In OK' => '#28a745', // Success
    'No Entries Both' => '#6c757d', // Gray
    'Absent' => '#f8bbd0', // Light Pink
    'No Barcode In' => '#fd7e14', // orange
    'No Bio In' => '#dc3545', // Danger
    'Early Barcode' => '#007bff', // Blue / Primary
    'Late Barcode' => '#8a2be2', // violet
);

$time_out_color_map = array(
    'Late Bio' => '#ffc107', // Warning
    'Time Out OK' => '#28a745', // Success
    'No Entries Both' => '#6c757d', // Gray
    'Absent' => '#f8bbd0', // Light Pink
    'No Barcode Out' => '#fd7e14', // orange
    'No Bio Out' => '#dc3545', // Danger
    'Early Bio' => '#8a2be2', // violet
);

$compliance_color_map = array(
    'Compliance' => '#28a745', // Success
    'Non-Compliance' => '#dc3545', // Danger
);

$section_color_map = array(
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

// Time In Analysis

if ($method == 'get_month_bio_vs_barcode_time_in_chart') {
    $year = $_GET['year'];
    $month = $_GET['month'];

    $data = [];
    $categories = [];

    $sql = "
            DECLARE @Year INT = ?;  
            DECLARE @Month INT = ?; 

            -- Final select to get counts based on time_out_remarks and report_date
            SELECT 
                day AS report_date,
                time_in_remarks AS remarks,
                COUNT(*) AS total_count 
            FROM 
                emp_mgt_backup.dbo.t_biometric_vs_barcode 
            WHERE 
                YEAR([day]) = @Year AND MONTH([day]) = @Month 
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
                emp_mgt_backup.dbo.t_biometric_vs_barcode 
            WHERE 
                YEAR([day]) = @Year AND MONTH([day]) = @Month 
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
    echo json_encode(['categories' => $categories, 'data' => $data, 'colorMap' => $time_in_color_map]);
}

if ($method == 'get_month_section_late_time_in_chart') {
    $year = $_GET['year'];
    $month = $_GET['month'];

    $data = [];
    $categories = [];

    $sql = "
            DECLARE @Year INT = ?;  
            DECLARE @Month INT = ?; 

            -- Final select to get counts based on time_out_remarks and report_date
            SELECT 
                day AS report_date,
                section,
                COUNT(
					CASE 
						WHEN time_in_remarks = 'Late' 
						THEN 1 
					END
				) AS total_count 
            FROM 
                emp_mgt_backup.dbo.t_biometric_vs_barcode 
            WHERE 
                YEAR([day]) = @Year AND MONTH([day]) = @Month 
            GROUP BY 
                day, section
            ORDER BY 
                report_date ASC;  -- Order results by report_date in ascending order
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
                $statusCounts[$section][$dateIndex] += intval($row['total_count']); // Use total_count for counts
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
    echo json_encode(['categories' => $categories, 'data' => $data, 'colorMap' => $section_color_map]);
}

if ($method == 'get_month_section_no_bio_time_in_chart') {
    $year = $_GET['year'];
    $month = $_GET['month'];

    $data = [];
    $categories = [];

    $sql = "
            DECLARE @Year INT = ?;  
            DECLARE @Month INT = ?; 

            -- Final select to get counts based on time_out_remarks and report_date
            SELECT 
                day AS report_date,
                section,
                COUNT(
					CASE 
						WHEN time_in_remarks = 'No Bio In' 
						THEN 1 
					END
				) AS total_count 
            FROM 
                emp_mgt_backup.dbo.t_biometric_vs_barcode 
            WHERE 
                YEAR([day]) = @Year AND MONTH([day]) = @Month 
            GROUP BY 
                day, section
            ORDER BY 
                report_date ASC;  -- Order results by report_date in ascending order
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
                $statusCounts[$section][$dateIndex] += intval($row['total_count']); // Use total_count for counts
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
    echo json_encode(['categories' => $categories, 'data' => $data, 'colorMap' => $section_color_map]);
}

if ($method == 'get_month_section_no_barcode_time_in_chart') {
    $year = $_GET['year'];
    $month = $_GET['month'];

    $data = [];
    $categories = [];

    $sql = "
            DECLARE @Year INT = ?;  
            DECLARE @Month INT = ?; 

            -- Final select to get counts based on time_out_remarks and report_date
            SELECT 
                day AS report_date,
                section,
                COUNT(
					CASE 
						WHEN time_in_remarks = 'No Barcode In' 
						THEN 1 
					END
				) AS total_count 
            FROM 
                emp_mgt_backup.dbo.t_biometric_vs_barcode 
            WHERE 
                YEAR([day]) = @Year AND MONTH([day]) = @Month 
            GROUP BY 
                day, section
            ORDER BY 
                report_date ASC;  -- Order results by report_date in ascending order
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
                $statusCounts[$section][$dateIndex] += intval($row['total_count']); // Use total_count for counts
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
    echo json_encode(['categories' => $categories, 'data' => $data, 'colorMap' => $section_color_map]);
}

if ($method == 'get_month_section_no_entries_time_in_chart') {
    $year = $_GET['year'];
    $month = $_GET['month'];

    $data = [];
    $categories = [];

    $sql = "
            DECLARE @Year INT = ?;  
            DECLARE @Month INT = ?; 

            -- Final select to get counts based on time_out_remarks and report_date
            SELECT 
                day AS report_date,
                section,
                COUNT(
					CASE 
						WHEN time_in_remarks = 'No Entries Both' 
						THEN 1 
					END
				) AS total_count 
            FROM 
                emp_mgt_backup.dbo.t_biometric_vs_barcode 
            WHERE 
                YEAR([day]) = @Year AND MONTH([day]) = @Month 
            GROUP BY 
                day, section
            ORDER BY 
                report_date ASC;  -- Order results by report_date in ascending order
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
                $statusCounts[$section][$dateIndex] += intval($row['total_count']); // Use total_count for counts
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
    echo json_encode(['categories' => $categories, 'data' => $data, 'colorMap' => $section_color_map]);
}

if ($method == 'get_month_section_early_barcode_time_in_chart') {
    $year = $_GET['year'];
    $month = $_GET['month'];

    $data = [];
    $categories = [];

    $sql = "
            DECLARE @Year INT = ?;  
            DECLARE @Month INT = ?; 

            -- Final select to get counts based on time_out_remarks and report_date
            SELECT 
                day AS report_date,
                section,
                COUNT(
					CASE 
						WHEN time_in_remarks = 'Early Barcode' 
						THEN 1 
					END
				) AS total_count 
            FROM 
                emp_mgt_backup.dbo.t_biometric_vs_barcode 
            WHERE 
                YEAR([day]) = @Year AND MONTH([day]) = @Month 
            GROUP BY 
                day, section
            ORDER BY 
                report_date ASC;  -- Order results by report_date in ascending order
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
                $statusCounts[$section][$dateIndex] += intval($row['total_count']); // Use total_count for counts
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
    echo json_encode(['categories' => $categories, 'data' => $data, 'colorMap' => $section_color_map]);
}

if ($method == 'get_month_section_late_barcode_time_in_chart') {
    $year = $_GET['year'];
    $month = $_GET['month'];

    $data = [];
    $categories = [];

    $sql = "
            DECLARE @Year INT = ?;  
            DECLARE @Month INT = ?; 

            -- Final select to get counts based on time_out_remarks and report_date
            SELECT 
                day AS report_date,
                section,
                COUNT(
					CASE 
						WHEN time_in_remarks = 'Late Barcode' 
						THEN 1 
					END
				) AS total_count 
            FROM 
                emp_mgt_backup.dbo.t_biometric_vs_barcode 
            WHERE 
                YEAR([day]) = @Year AND MONTH([day]) = @Month 
            GROUP BY 
                day, section
            ORDER BY 
                report_date ASC;  -- Order results by report_date in ascending order
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
                $statusCounts[$section][$dateIndex] += intval($row['total_count']); // Use total_count for counts
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
    echo json_encode(['categories' => $categories, 'data' => $data, 'colorMap' => $section_color_map]);
}

if ($method == 'get_month_section_top_late_time_in_chart') {
    $year = $_GET['year'];
    $month = $_GET['month'];

    $data = [];
    $categories = [];

    $sql = "
            DECLARE @Year INT = ?;  
            DECLARE @Month INT = ?; 

            WITH BvbTopRemarks AS (
                SELECT 
                    emp_no,
                    section,
                    COUNT(
                        CASE 
                            WHEN time_in_remarks = 'Late' 
                            THEN 1 
                        END
                    ) AS BvbTopCount 
                FROM 
                    emp_mgt_backup.dbo.t_biometric_vs_barcode 
                GROUP BY
                    emp_no, section
            )

            -- Final select to get counts based on time_out_remarks and report_date
            SELECT TOP 10 
                section,
                COUNT(CASE 
                    WHEN BvbTopCount > 2 THEN 1 
                END) AS AuditCount,
                COUNT(CASE 
                    WHEN BvbTopCount <= 2 AND BvbTopCount > 0 THEN 1 
                END) AS WarningCount,
                COUNT(CASE 
                    WHEN BvbTopCount > 0 THEN 1 
                END) AS AllCount 
            FROM 
                BvbTopRemarks
            WHERE 
                BvbTopCount > 0  -- Filter to show only employees with null time records (0 to see warning)
            GROUP BY 
                section
            ORDER BY 
                AllCount DESC, section ASC;
            ";

    $params = [];

    $params[] = $year;
    $params[] = $month;

    $stmt = $conn->prepare($sql);

    $stmt->execute($params);

    // Initialize an array to hold the counts for each section
    $statusCounts = [];

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        do {
            // Add unique section to categories
            if (!in_array($row['section'], $categories)) {
                $categories[] = $row['section'];
            }

            $data['AllCount'][] = (int)$row['AllCount'];
        } while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
    } else {
        $data['AllCount'] = [];
    }

    // Create the final data structure
    $finalData = [
        'categories' => $categories,
        'data' => [
            [
                'name' => 'Employee Count',
                'data' => $data['AllCount']
            ]
        ]
    ];

    // Encode the categories and data as JSON
    echo json_encode($finalData);
}

if ($method == 'get_month_section_top_no_bio_time_in_chart') {
    $year = $_GET['year'];
    $month = $_GET['month'];

    $data = [];
    $categories = [];

    $sql = "
            DECLARE @Year INT = ?;  
            DECLARE @Month INT = ?; 

            WITH BvbTopRemarks AS (
                SELECT 
                    emp_no,
                    section,
                    COUNT(
                        CASE 
                            WHEN time_in_remarks = 'No Bio In' 
                            THEN 1 
                        END
                    ) AS BvbTopCount 
                FROM 
                    emp_mgt_backup.dbo.t_biometric_vs_barcode 
                GROUP BY
                    emp_no, section
            )

            -- Final select to get counts based on time_out_remarks and report_date
            SELECT TOP 10 
                section,
                COUNT(CASE 
                    WHEN BvbTopCount > 2 THEN 1 
                END) AS AuditCount,
                COUNT(CASE 
                    WHEN BvbTopCount <= 2 AND BvbTopCount > 0 THEN 1 
                END) AS WarningCount,
                COUNT(CASE 
                    WHEN BvbTopCount > 0 THEN 1 
                END) AS AllCount 
            FROM 
                BvbTopRemarks
            WHERE 
                BvbTopCount > 0  -- Filter to show only employees with null time records (0 to see warning)
            GROUP BY 
                section
            ORDER BY 
                AllCount DESC, section ASC;
            ";

    $params = [];

    $params[] = $year;
    $params[] = $month;

    $stmt = $conn->prepare($sql);

    $stmt->execute($params);

    // Initialize an array to hold the counts for each section
    $statusCounts = [];

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        do {
            // Add unique section to categories
            if (!in_array($row['section'], $categories)) {
                $categories[] = $row['section'];
            }

            $data['AllCount'][] = (int)$row['AllCount'];
        } while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
    } else {
        $data['AllCount'] = [];
    }

    // Create the final data structure
    $finalData = [
        'categories' => $categories,
        'data' => [
            [
                'name' => 'Employee Count',
                'data' => $data['AllCount']
            ]
        ]
    ];

    // Encode the categories and data as JSON
    echo json_encode($finalData);
}

if ($method == 'get_month_section_top_no_barcode_time_in_chart') {
    $year = $_GET['year'];
    $month = $_GET['month'];

    $data = [];
    $categories = [];

    $sql = "
            DECLARE @Year INT = ?;  
            DECLARE @Month INT = ?; 

            WITH BvbTopRemarks AS (
                SELECT 
                    emp_no,
                    section,
                    COUNT(
                        CASE 
                            WHEN time_in_remarks = 'No Barcode In' 
                            THEN 1 
                        END
                    ) AS BvbTopCount 
                FROM 
                    emp_mgt_backup.dbo.t_biometric_vs_barcode 
                GROUP BY
                    emp_no, section
            )

            -- Final select to get counts based on time_out_remarks and report_date
            SELECT TOP 10 
                section,
                COUNT(CASE 
                    WHEN BvbTopCount > 2 THEN 1 
                END) AS AuditCount,
                COUNT(CASE 
                    WHEN BvbTopCount <= 2 AND BvbTopCount > 0 THEN 1 
                END) AS WarningCount,
                COUNT(CASE 
                    WHEN BvbTopCount > 0 THEN 1 
                END) AS AllCount 
            FROM 
                BvbTopRemarks
            WHERE 
                BvbTopCount > 0  -- Filter to show only employees with null time records (0 to see warning)
            GROUP BY 
                section
            ORDER BY 
                AllCount DESC, section ASC;
            ";

    $params = [];

    $params[] = $year;
    $params[] = $month;

    $stmt = $conn->prepare($sql);

    $stmt->execute($params);

    // Initialize an array to hold the counts for each section
    $statusCounts = [];

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        do {
            // Add unique section to categories
            if (!in_array($row['section'], $categories)) {
                $categories[] = $row['section'];
            }

            $data['AllCount'][] = (int)$row['AllCount'];
        } while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
    } else {
        $data['AllCount'] = [];
    }

    // Create the final data structure
    $finalData = [
        'categories' => $categories,
        'data' => [
            [
                'name' => 'Employee Count',
                'data' => $data['AllCount']
            ]
        ]
    ];

    // Encode the categories and data as JSON
    echo json_encode($finalData);
}

if ($method == 'get_month_section_top_no_entries_time_in_chart') {
    $year = $_GET['year'];
    $month = $_GET['month'];

    $data = [];
    $categories = [];

    $sql = "
            DECLARE @Year INT = ?;  
            DECLARE @Month INT = ?; 

            WITH BvbTopRemarks AS (
                SELECT 
                    emp_no,
                    section,
                    COUNT(
                        CASE 
                            WHEN time_in_remarks = 'No Entries Both' 
                            THEN 1 
                        END
                    ) AS BvbTopCount 
                FROM 
                    emp_mgt_backup.dbo.t_biometric_vs_barcode 
                GROUP BY
                    emp_no, section
            )

            -- Final select to get counts based on time_out_remarks and report_date
            SELECT TOP 10 
                section,
                COUNT(CASE 
                    WHEN BvbTopCount > 2 THEN 1 
                END) AS AuditCount,
                COUNT(CASE 
                    WHEN BvbTopCount <= 2 AND BvbTopCount > 0 THEN 1 
                END) AS WarningCount,
                COUNT(CASE 
                    WHEN BvbTopCount > 0 THEN 1 
                END) AS AllCount 
            FROM 
                BvbTopRemarks
            WHERE 
                BvbTopCount > 0  -- Filter to show only employees with null time records (0 to see warning)
            GROUP BY 
                section
            ORDER BY 
                AllCount DESC, section ASC;
            ";

    $params = [];

    $params[] = $year;
    $params[] = $month;

    $stmt = $conn->prepare($sql);

    $stmt->execute($params);

    // Initialize an array to hold the counts for each section
    $statusCounts = [];

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        do {
            // Add unique section to categories
            if (!in_array($row['section'], $categories)) {
                $categories[] = $row['section'];
            }

            $data['AllCount'][] = (int)$row['AllCount'];
        } while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
    } else {
        $data['AllCount'] = [];
    }

    // Create the final data structure
    $finalData = [
        'categories' => $categories,
        'data' => [
            [
                'name' => 'Employee Count',
                'data' => $data['AllCount']
            ]
        ]
    ];

    // Encode the categories and data as JSON
    echo json_encode($finalData);
}

if ($method == 'get_month_section_top_early_barcode_time_in_chart') {
    $year = $_GET['year'];
    $month = $_GET['month'];

    $data = [];
    $categories = [];

    $sql = "
            DECLARE @Year INT = ?;  
            DECLARE @Month INT = ?; 

            WITH BvbTopRemarks AS (
                SELECT 
                    emp_no,
                    section,
                    COUNT(
                        CASE 
                            WHEN time_in_remarks = 'Early Barcode' 
                            THEN 1 
                        END
                    ) AS BvbTopCount 
                FROM 
                    emp_mgt_backup.dbo.t_biometric_vs_barcode 
                GROUP BY
                    emp_no, section
            )

            -- Final select to get counts based on time_out_remarks and report_date
            SELECT TOP 10 
                section,
                COUNT(CASE 
                    WHEN BvbTopCount > 2 THEN 1 
                END) AS AuditCount,
                COUNT(CASE 
                    WHEN BvbTopCount <= 2 AND BvbTopCount > 0 THEN 1 
                END) AS WarningCount,
                COUNT(CASE 
                    WHEN BvbTopCount > 0 THEN 1 
                END) AS AllCount 
            FROM 
                BvbTopRemarks
            WHERE 
                BvbTopCount > 0  -- Filter to show only employees with null time records (0 to see warning)
            GROUP BY 
                section
            ORDER BY 
                AllCount DESC, section ASC;
            ";

    $params = [];

    $params[] = $year;
    $params[] = $month;

    $stmt = $conn->prepare($sql);

    $stmt->execute($params);

    // Initialize an array to hold the counts for each section
    $statusCounts = [];

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        do {
            // Add unique section to categories
            if (!in_array($row['section'], $categories)) {
                $categories[] = $row['section'];
            }

            $data['AllCount'][] = (int)$row['AllCount'];
        } while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
    } else {
        $data['AllCount'] = [];
    }

    // Create the final data structure
    $finalData = [
        'categories' => $categories,
        'data' => [
            [
                'name' => 'Employee Count',
                'data' => $data['AllCount']
            ]
        ]
    ];

    // Encode the categories and data as JSON
    echo json_encode($finalData);
}

if ($method == 'get_month_section_top_late_barcode_time_in_chart') {
    $year = $_GET['year'];
    $month = $_GET['month'];

    $data = [];
    $categories = [];

    $sql = "
            DECLARE @Year INT = ?;  
            DECLARE @Month INT = ?; 

            WITH BvbTopRemarks AS (
                SELECT 
                    emp_no,
                    section,
                    COUNT(
                        CASE 
                            WHEN time_in_remarks = 'Late Barcode' 
                            THEN 1 
                        END
                    ) AS BvbTopCount 
                FROM 
                    emp_mgt_backup.dbo.t_biometric_vs_barcode 
                GROUP BY
                    emp_no, section
            )

            -- Final select to get counts based on time_out_remarks and report_date
            SELECT TOP 10 
                section,
                COUNT(CASE 
                    WHEN BvbTopCount > 2 THEN 1 
                END) AS AuditCount,
                COUNT(CASE 
                    WHEN BvbTopCount <= 2 AND BvbTopCount > 0 THEN 1 
                END) AS WarningCount,
                COUNT(CASE 
                    WHEN BvbTopCount > 0 THEN 1 
                END) AS AllCount 
            FROM 
                BvbTopRemarks
            WHERE 
                BvbTopCount > 0  -- Filter to show only employees with null time records (0 to see warning)
            GROUP BY 
                section
            ORDER BY 
                AllCount DESC, section ASC;
            ";

    $params = [];

    $params[] = $year;
    $params[] = $month;

    $stmt = $conn->prepare($sql);

    $stmt->execute($params);

    // Initialize an array to hold the counts for each section
    $statusCounts = [];

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        do {
            // Add unique section to categories
            if (!in_array($row['section'], $categories)) {
                $categories[] = $row['section'];
            }

            $data['AllCount'][] = (int)$row['AllCount'];
        } while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
    } else {
        $data['AllCount'] = [];
    }

    // Create the final data structure
    $finalData = [
        'categories' => $categories,
        'data' => [
            [
                'name' => 'Employee Count',
                'data' => $data['AllCount']
            ]
        ]
    ];

    // Encode the categories and data as JSON
    echo json_encode($finalData);
}

// Time Out Analysis

if ($method == 'get_month_bio_vs_barcode_time_out_chart') {
    $year = $_GET['year'];
    $month = $_GET['month'];

    $data = [];
    $categories = [];

    $sql = "
            DECLARE @Year INT = ?;  
            DECLARE @Month INT = ?; 

            -- Final select to get counts based on time_out_remarks and report_date
            SELECT 
                day AS report_date,
                time_out_remarks AS remarks,
                COUNT(*) AS total_count 
            FROM 
                emp_mgt_backup.dbo.t_biometric_vs_barcode 
            WHERE 
                YEAR([day]) = @Year AND MONTH([day]) = @Month  
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
                emp_mgt_backup.dbo.t_biometric_vs_barcode 
            WHERE 
                YEAR([day]) = @Year AND MONTH([day]) = @Month 
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
    echo json_encode(['categories' => $categories, 'data' => $data, 'colorMap' => $time_out_color_map]);
}

if ($method == 'get_month_section_no_bio_time_out_chart') {
    $year = $_GET['year'];
    $month = $_GET['month'];

    $data = [];
    $categories = [];

    $sql = "
            DECLARE @Year INT = ?;  
            DECLARE @Month INT = ?; 

            -- Final select to get counts based on time_out_remarks and report_date
            SELECT 
                day AS report_date,
                section,
                COUNT(
					CASE 
						WHEN time_out_remarks = 'No Bio Out' 
						THEN 1 
					END
				) AS total_count 
            FROM 
                emp_mgt_backup.dbo.t_biometric_vs_barcode 
            WHERE 
                YEAR([day]) = @Year AND MONTH([day]) = @Month 
            GROUP BY 
                day, section
            ORDER BY 
                report_date ASC;  -- Order results by report_date in ascending order
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
                $statusCounts[$section][$dateIndex] += intval($row['total_count']); // Use total_count for counts
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
    echo json_encode(['categories' => $categories, 'data' => $data, 'colorMap' => $section_color_map]);
}

if ($method == 'get_month_section_no_barcode_time_out_chart') {
    $year = $_GET['year'];
    $month = $_GET['month'];

    $data = [];
    $categories = [];

    $sql = "
            DECLARE @Year INT = ?;  
            DECLARE @Month INT = ?; 

            -- Final select to get counts based on time_out_remarks and report_date
            SELECT 
                day AS report_date,
                section,
                COUNT(
					CASE 
						WHEN time_out_remarks = 'No Barcode Out' 
						THEN 1 
					END
				) AS total_count 
            FROM 
                emp_mgt_backup.dbo.t_biometric_vs_barcode 
            WHERE 
                YEAR([day]) = @Year AND MONTH([day]) = @Month 
            GROUP BY 
                day, section
            ORDER BY 
                report_date ASC;  -- Order results by report_date in ascending order
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
                $statusCounts[$section][$dateIndex] += intval($row['total_count']); // Use total_count for counts
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
    echo json_encode(['categories' => $categories, 'data' => $data, 'colorMap' => $section_color_map]);
}

if ($method == 'get_month_section_no_entries_time_out_chart') {
    $year = $_GET['year'];
    $month = $_GET['month'];

    $data = [];
    $categories = [];

    $sql = "
            DECLARE @Year INT = ?;  
            DECLARE @Month INT = ?; 

            -- Final select to get counts based on time_out_remarks and report_date
            SELECT 
                day AS report_date,
                section,
                COUNT(
					CASE 
						WHEN time_out_remarks = 'No Entries Both' 
						THEN 1 
					END
				) AS total_count 
            FROM 
                emp_mgt_backup.dbo.t_biometric_vs_barcode 
            WHERE 
                YEAR([day]) = @Year AND MONTH([day]) = @Month 
            GROUP BY 
                day, section
            ORDER BY 
                report_date ASC;  -- Order results by report_date in ascending order
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
                $statusCounts[$section][$dateIndex] += intval($row['total_count']); // Use total_count for counts
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
    echo json_encode(['categories' => $categories, 'data' => $data, 'colorMap' => $section_color_map]);
}

if ($method == 'get_month_section_early_bio_time_out_chart') {
    $year = $_GET['year'];
    $month = $_GET['month'];

    $data = [];
    $categories = [];

    $sql = "
            DECLARE @Year INT = ?;  
            DECLARE @Month INT = ?; 

            -- Final select to get counts based on time_out_remarks and report_date
            SELECT 
                day AS report_date,
                section,
                COUNT(
					CASE 
						WHEN time_out_remarks = 'Early Bio' 
						THEN 1 
					END
				) AS total_count 
            FROM 
                emp_mgt_backup.dbo.t_biometric_vs_barcode 
            WHERE 
                YEAR([day]) = @Year AND MONTH([day]) = @Month 
            GROUP BY 
                day, section
            ORDER BY 
                report_date ASC;  -- Order results by report_date in ascending order
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
                $statusCounts[$section][$dateIndex] += intval($row['total_count']); // Use total_count for counts
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
    echo json_encode(['categories' => $categories, 'data' => $data, 'colorMap' => $section_color_map]);
}

if ($method == 'get_month_section_late_bio_time_out_chart') {
    $year = $_GET['year'];
    $month = $_GET['month'];

    $data = [];
    $categories = [];

    $sql = "
            DECLARE @Year INT = ?;  
            DECLARE @Month INT = ?; 

            -- Final select to get counts based on time_out_remarks and report_date
            SELECT 
                day AS report_date,
                section,
                COUNT(
					CASE 
						WHEN time_out_remarks = 'Late Bio' 
						THEN 1 
					END
				) AS total_count 
            FROM 
                emp_mgt_backup.dbo.t_biometric_vs_barcode 
            WHERE 
                YEAR([day]) = @Year AND MONTH([day]) = @Month 
            GROUP BY 
                day, section
            ORDER BY 
                report_date ASC;  -- Order results by report_date in ascending order
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
                $statusCounts[$section][$dateIndex] += intval($row['total_count']); // Use total_count for counts
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
    echo json_encode(['categories' => $categories, 'data' => $data, 'colorMap' => $section_color_map]);
}

if ($method == 'get_month_section_top_no_bio_time_out_chart') {
    $year = $_GET['year'];
    $month = $_GET['month'];

    $data = [];
    $categories = [];

    $sql = "
            DECLARE @Year INT = ?;  
            DECLARE @Month INT = ?; 

            WITH BvbTopRemarks AS (
                SELECT 
                    emp_no,
                    section,
                    COUNT(
                        CASE 
                            WHEN time_out_remarks = 'No Bio Out' 
                            THEN 1 
                        END
                    ) AS BvbTopCount 
                FROM 
                    emp_mgt_backup.dbo.t_biometric_vs_barcode 
                GROUP BY
                    emp_no, section
            )

            -- Final select to get counts based on time_out_remarks and report_date
            SELECT TOP 10 
                section,
                COUNT(CASE 
                    WHEN BvbTopCount > 2 THEN 1 
                END) AS AuditCount,
                COUNT(CASE 
                    WHEN BvbTopCount <= 2 AND BvbTopCount > 0 THEN 1 
                END) AS WarningCount,
                COUNT(CASE 
                    WHEN BvbTopCount > 0 THEN 1 
                END) AS AllCount 
            FROM 
                BvbTopRemarks
            WHERE 
                BvbTopCount > 0  -- Filter to show only employees with null time records (0 to see warning)
            GROUP BY 
                section
            ORDER BY 
                AllCount DESC, section ASC;
            ";

    $params = [];

    $params[] = $year;
    $params[] = $month;

    $stmt = $conn->prepare($sql);

    $stmt->execute($params);

    // Initialize an array to hold the counts for each section
    $statusCounts = [];

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        do {
            // Add unique section to categories
            if (!in_array($row['section'], $categories)) {
                $categories[] = $row['section'];
            }

            $data['AllCount'][] = (int)$row['AllCount'];
        } while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
    } else {
        $data['AllCount'] = [];
    }

    // Create the final data structure
    $finalData = [
        'categories' => $categories,
        'data' => [
            [
                'name' => 'Employee Count',
                'data' => $data['AllCount']
            ]
        ]
    ];

    // Encode the categories and data as JSON
    echo json_encode($finalData);
}

if ($method == 'get_month_section_top_no_barcode_time_out_chart') {
    $year = $_GET['year'];
    $month = $_GET['month'];

    $data = [];
    $categories = [];

    $sql = "
            DECLARE @Year INT = ?;  
            DECLARE @Month INT = ?; 

            WITH BvbTopRemarks AS (
                SELECT 
                    emp_no,
                    section,
                    COUNT(
                        CASE 
                            WHEN time_out_remarks = 'No Barcode Out' 
                            THEN 1 
                        END
                    ) AS BvbTopCount 
                FROM 
                    emp_mgt_backup.dbo.t_biometric_vs_barcode 
                GROUP BY
                    emp_no, section
            )

            -- Final select to get counts based on time_out_remarks and report_date
            SELECT TOP 10 
                section,
                COUNT(CASE 
                    WHEN BvbTopCount > 2 THEN 1 
                END) AS AuditCount,
                COUNT(CASE 
                    WHEN BvbTopCount <= 2 AND BvbTopCount > 0 THEN 1 
                END) AS WarningCount,
                COUNT(CASE 
                    WHEN BvbTopCount > 0 THEN 1 
                END) AS AllCount 
            FROM 
                BvbTopRemarks
            WHERE 
                BvbTopCount > 0  -- Filter to show only employees with null time records (0 to see warning)
            GROUP BY 
                section
            ORDER BY 
                AllCount DESC, section ASC;
            ";

    $params = [];

    $params[] = $year;
    $params[] = $month;

    $stmt = $conn->prepare($sql);

    $stmt->execute($params);

    // Initialize an array to hold the counts for each section
    $statusCounts = [];

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        do {
            // Add unique section to categories
            if (!in_array($row['section'], $categories)) {
                $categories[] = $row['section'];
            }

            $data['AllCount'][] = (int)$row['AllCount'];
        } while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
    } else {
        $data['AllCount'] = [];
    }

    // Create the final data structure
    $finalData = [
        'categories' => $categories,
        'data' => [
            [
                'name' => 'Employee Count',
                'data' => $data['AllCount']
            ]
        ]
    ];

    // Encode the categories and data as JSON
    echo json_encode($finalData);
}

if ($method == 'get_month_section_top_no_entries_time_out_chart') {
    $year = $_GET['year'];
    $month = $_GET['month'];

    $data = [];
    $categories = [];

    $sql = "
            DECLARE @Year INT = ?;  
            DECLARE @Month INT = ?; 

            WITH BvbTopRemarks AS (
                SELECT 
                    emp_no,
                    section,
                    COUNT(
                        CASE 
                            WHEN time_out_remarks = 'No Entries Both' 
                            THEN 1 
                        END
                    ) AS BvbTopCount 
                FROM 
                    emp_mgt_backup.dbo.t_biometric_vs_barcode 
                GROUP BY
                    emp_no, section
            )

            -- Final select to get counts based on time_out_remarks and report_date
            SELECT TOP 10 
                section,
                COUNT(CASE 
                    WHEN BvbTopCount > 2 THEN 1 
                END) AS AuditCount,
                COUNT(CASE 
                    WHEN BvbTopCount <= 2 AND BvbTopCount > 0 THEN 1 
                END) AS WarningCount,
                COUNT(CASE 
                    WHEN BvbTopCount > 0 THEN 1 
                END) AS AllCount 
            FROM 
                BvbTopRemarks
            WHERE 
                BvbTopCount > 0  -- Filter to show only employees with null time records (0 to see warning)
            GROUP BY 
                section
            ORDER BY 
                AllCount DESC, section ASC;
            ";

    $params = [];

    $params[] = $year;
    $params[] = $month;

    $stmt = $conn->prepare($sql);

    $stmt->execute($params);

    // Initialize an array to hold the counts for each section
    $statusCounts = [];

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        do {
            // Add unique section to categories
            if (!in_array($row['section'], $categories)) {
                $categories[] = $row['section'];
            }

            $data['AllCount'][] = (int)$row['AllCount'];
        } while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
    } else {
        $data['AllCount'] = [];
    }

    // Create the final data structure
    $finalData = [
        'categories' => $categories,
        'data' => [
            [
                'name' => 'Employee Count',
                'data' => $data['AllCount']
            ]
        ]
    ];

    // Encode the categories and data as JSON
    echo json_encode($finalData);
}

if ($method == 'get_month_section_top_early_bio_time_out_chart') {
    $year = $_GET['year'];
    $month = $_GET['month'];

    $data = [];
    $categories = [];

    $sql = "
            DECLARE @Year INT = ?;  
            DECLARE @Month INT = ?; 

            WITH BvbTopRemarks AS (
                SELECT 
                    emp_no,
                    section,
                    COUNT(
                        CASE 
                            WHEN time_out_remarks = 'Early Bio' 
                            THEN 1 
                        END
                    ) AS BvbTopCount 
                FROM 
                    emp_mgt_backup.dbo.t_biometric_vs_barcode 
                GROUP BY
                    emp_no, section
            )

            -- Final select to get counts based on time_out_remarks and report_date
            SELECT TOP 10 
                section,
                COUNT(CASE 
                    WHEN BvbTopCount > 2 THEN 1 
                END) AS AuditCount,
                COUNT(CASE 
                    WHEN BvbTopCount <= 2 AND BvbTopCount > 0 THEN 1 
                END) AS WarningCount,
                COUNT(CASE 
                    WHEN BvbTopCount > 0 THEN 1 
                END) AS AllCount 
            FROM 
                BvbTopRemarks
            WHERE 
                BvbTopCount > 0  -- Filter to show only employees with null time records (0 to see warning)
            GROUP BY 
                section
            ORDER BY 
                AllCount DESC, section ASC;
            ";

    $params = [];

    $params[] = $year;
    $params[] = $month;

    $stmt = $conn->prepare($sql);

    $stmt->execute($params);

    // Initialize an array to hold the counts for each section
    $statusCounts = [];

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        do {
            // Add unique section to categories
            if (!in_array($row['section'], $categories)) {
                $categories[] = $row['section'];
            }

            $data['AllCount'][] = (int)$row['AllCount'];
        } while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
    } else {
        $data['AllCount'] = [];
    }

    // Create the final data structure
    $finalData = [
        'categories' => $categories,
        'data' => [
            [
                'name' => 'Employee Count',
                'data' => $data['AllCount']
            ]
        ]
    ];

    // Encode the categories and data as JSON
    echo json_encode($finalData);
}

if ($method == 'get_month_section_top_late_bio_time_out_chart') {
    $year = $_GET['year'];
    $month = $_GET['month'];

    $data = [];
    $categories = [];

    $sql = "
            DECLARE @Year INT = ?;  
            DECLARE @Month INT = ?; 

            WITH BvbTopRemarks AS (
                SELECT 
                    emp_no,
                    section,
                    COUNT(
                        CASE 
                            WHEN time_out_remarks = 'Late Bio' 
                            THEN 1 
                        END
                    ) AS BvbTopCount 
                FROM 
                    emp_mgt_backup.dbo.t_biometric_vs_barcode 
                GROUP BY
                    emp_no, section
            )

            -- Final select to get counts based on time_out_remarks and report_date
            SELECT TOP 10 
                section,
                COUNT(CASE 
                    WHEN BvbTopCount > 2 THEN 1 
                END) AS AuditCount,
                COUNT(CASE 
                    WHEN BvbTopCount <= 2 AND BvbTopCount > 0 THEN 1 
                END) AS WarningCount,
                COUNT(CASE 
                    WHEN BvbTopCount > 0 THEN 1 
                END) AS AllCount 
            FROM 
                BvbTopRemarks
            WHERE 
                BvbTopCount > 0  -- Filter to show only employees with null time records (0 to see warning)
            GROUP BY 
                section
            ORDER BY 
                AllCount DESC, section ASC;
            ";

    $params = [];

    $params[] = $year;
    $params[] = $month;

    $stmt = $conn->prepare($sql);

    $stmt->execute($params);

    // Initialize an array to hold the counts for each section
    $statusCounts = [];

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        do {
            // Add unique section to categories
            if (!in_array($row['section'], $categories)) {
                $categories[] = $row['section'];
            }

            $data['AllCount'][] = (int)$row['AllCount'];
        } while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
    } else {
        $data['AllCount'] = [];
    }

    // Create the final data structure
    $finalData = [
        'categories' => $categories,
        'data' => [
            [
                'name' => 'Employee Count',
                'data' => $data['AllCount']
            ]
        ]
    ];

    // Encode the categories and data as JSON
    echo json_encode($finalData);
}

// Compliance Analysis

if ($method == 'get_month_compliance_time_in_chart') {
    $year = $_GET['year'];
    $month = $_GET['month'];

    $data = [];
    $categories = [];

    $sql = "
            DECLARE @Year INT = ?;  
            DECLARE @Month INT = ?; 

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
                emp_mgt_backup.dbo.t_biometric_vs_barcode 
            WHERE 
                YEAR([day]) = @Year AND MONTH([day]) = @Month 
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
                emp_mgt_backup.dbo.t_biometric_vs_barcode 
            WHERE 
                YEAR([day]) = @Year AND MONTH([day]) = @Month 
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
    echo json_encode(['categories' => $categories, 'data' => $data, 'colorMap' => $compliance_color_map]);
}

if ($method == 'get_month_section_compliance_time_in_chart') {
    $year = $_GET['year'];
    $month = $_GET['month'];

    $data = [];
    $categories = [];

    $sql = "
            DECLARE @Year INT = ?;  
            DECLARE @Month INT = ?; 

            -- Final select to get counts based on time_out_remarks and report_date
            SELECT 
                day AS report_date,
                section,
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
                emp_mgt_backup.dbo.t_biometric_vs_barcode 
            WHERE 
                YEAR([day]) = @Year AND MONTH([day]) = @Month 
            GROUP BY 
                day, section 
            ORDER BY 
                report_date ASC;  -- Order results by report_date in ascending order
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
                $statusCounts[$section][$dateIndex] += floatval($row['total_percentage']); // Use total_percentage for counts
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
    echo json_encode(['categories' => $categories, 'data' => $data, 'colorMap' => $section_color_map]);
}

if ($method == 'get_month_section_non_compliance_time_in_chart') {
    $year = $_GET['year'];
    $month = $_GET['month'];

    $data = [];
    $categories = [];

    $sql = "
            DECLARE @Year INT = ?;  
            DECLARE @Month INT = ?; 

            -- Final select to get counts based on time_out_remarks and report_date
            SELECT 
                day AS report_date,
                section,
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
                emp_mgt_backup.dbo.t_biometric_vs_barcode 
            WHERE 
                YEAR([day]) = @Year AND MONTH([day]) = @Month 
            GROUP BY 
                day, section 
            ORDER BY 
                report_date ASC;  -- Order results by report_date in ascending order
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
                $statusCounts[$section][$dateIndex] += floatval($row['total_percentage']); // Use total_percentage for counts
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
    echo json_encode(['categories' => $categories, 'data' => $data, 'colorMap' => $section_color_map]);
}

if ($method == 'get_month_section_top_compliance_time_in_chart') {
    $year = $_GET['year'];
    $month = $_GET['month'];

    $data = [];
    $categories = [];

    $sql = "
            DECLARE @Year INT = ?;  
            DECLARE @Month INT = ?; 

            WITH BvbTopCompliance AS (
                SELECT 
                    section,
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
                    emp_mgt_backup.dbo.t_biometric_vs_barcode 
                WHERE 
                    YEAR([day]) = @Year AND MONTH([day]) = @Month 
                GROUP BY 
                    section
            )

            SELECT TOP 10
                section, 
                total_percentage
            FROM 
                BvbTopCompliance
            ORDER BY
                total_percentage DESC, section ASC;
            ";

    $params = [];

    $params[] = $year;
    $params[] = $month;

    $stmt = $conn->prepare($sql);

    $stmt->execute($params);

    // Initialize an array to hold the counts for each section
    $statusCounts = [];

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        do {
            // Add unique section to categories
            if (!in_array($row['section'], $categories)) {
                $categories[] = $row['section'];
            }

            $data['total_percentage'][] = (int)$row['total_percentage'];
        } while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
    } else {
        $data['total_percentage'] = [];
    }

    // Create the final data structure
    $finalData = [
        'categories' => $categories,
        'data' => [
            [
                'name' => 'Percentage',
                'data' => $data['total_percentage']
            ]
        ]
    ];

    // Encode the categories and data as JSON
    echo json_encode($finalData);
}

if ($method == 'get_month_section_top_non_compliance_time_in_chart') {
    $year = $_GET['year'];
    $month = $_GET['month'];

    $data = [];
    $categories = [];

    $sql = "
            DECLARE @Year INT = ?;  
            DECLARE @Month INT = ?; 

            WITH BvbTopCompliance AS (
                SELECT 
                    section,
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
                    emp_mgt_backup.dbo.t_biometric_vs_barcode 
                WHERE 
                    YEAR([day]) = @Year AND MONTH([day]) = @Month 
                GROUP BY 
                    section 
            )

            SELECT TOP 10
                section, 
                total_percentage
            FROM 
                BvbTopCompliance
            ORDER BY
                total_percentage DESC, section ASC;
            ";

    $params = [];

    $params[] = $year;
    $params[] = $month;

    $stmt = $conn->prepare($sql);

    $stmt->execute($params);

    // Initialize an array to hold the counts for each section
    $statusCounts = [];

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        do {
            // Add unique section to categories
            if (!in_array($row['section'], $categories)) {
                $categories[] = $row['section'];
            }

            $data['total_percentage'][] = (int)$row['total_percentage'];
        } while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
    } else {
        $data['total_percentage'] = [];
    }

    // Create the final data structure
    $finalData = [
        'categories' => $categories,
        'data' => [
            [
                'name' => 'Percentage',
                'data' => $data['total_percentage']
            ]
        ]
    ];

    // Encode the categories and data as JSON
    echo json_encode($finalData);
}

if ($method == 'get_month_compliance_time_out_chart') {
    $year = $_GET['year'];
    $month = $_GET['month'];

    $data = [];
    $categories = [];

    $sql = "
            DECLARE @Year INT = ?;  
            DECLARE @Month INT = ?; 

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
                emp_mgt_backup.dbo.t_biometric_vs_barcode 
            WHERE 
                YEAR([day]) = @Year AND MONTH([day]) = @Month 
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
                emp_mgt_backup.dbo.t_biometric_vs_barcode 
            WHERE 
                YEAR([day]) = @Year AND MONTH([day]) = @Month 
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
    echo json_encode(['categories' => $categories, 'data' => $data, 'colorMap' => $compliance_color_map]);
}

if ($method == 'get_month_section_compliance_time_out_chart') {
    $year = $_GET['year'];
    $month = $_GET['month'];

    $data = [];
    $categories = [];

    $sql = "
            DECLARE @Year INT = ?;  
            DECLARE @Month INT = ?; 

            -- Final select to get counts based on time_out_remarks and report_date
            SELECT 
                day AS report_date,
                section, 
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
                emp_mgt_backup.dbo.t_biometric_vs_barcode 
            WHERE 
                YEAR([day]) = @Year AND MONTH([day]) = @Month 
            GROUP BY 
                day, section 
            ORDER BY 
                report_date ASC;  -- Order results by report_date in ascending order
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
                $statusCounts[$section][$dateIndex] += floatval($row['total_percentage']); // Use total_percentage for counts
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
    echo json_encode(['categories' => $categories, 'data' => $data, 'colorMap' => $section_color_map]);
}

if ($method == 'get_month_section_non_compliance_time_out_chart') {
    $year = $_GET['year'];
    $month = $_GET['month'];

    $data = [];
    $categories = [];

    $sql = "
            DECLARE @Year INT = ?;  
            DECLARE @Month INT = ?; 

            -- Final select to get counts based on time_out_remarks and report_date
            SELECT 
                day AS report_date,
                section,
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
                emp_mgt_backup.dbo.t_biometric_vs_barcode 
            WHERE 
                YEAR([day]) = @Year AND MONTH([day]) = @Month 
            GROUP BY 
                day, section
            ORDER BY 
                report_date ASC;  -- Order results by report_date in ascending order
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
                $statusCounts[$section][$dateIndex] += floatval($row['total_percentage']); // Use total_percentage for counts
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
    echo json_encode(['categories' => $categories, 'data' => $data, 'colorMap' => $section_color_map]);
}

if ($method == 'get_month_section_top_compliance_time_out_chart') {
    $year = $_GET['year'];
    $month = $_GET['month'];

    $data = [];
    $categories = [];

    $sql = "
            DECLARE @Year INT = ?;  
            DECLARE @Month INT = ?; 

            WITH BvbTopCompliance AS (
                SELECT 
                    section, 
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
                    emp_mgt_backup.dbo.t_biometric_vs_barcode 
                WHERE 
                    YEAR([day]) = @Year AND MONTH([day]) = @Month 
                GROUP BY 
                    section 
            )

            SELECT TOP 10
                section, 
                total_percentage
            FROM 
                BvbTopCompliance
            ORDER BY
                total_percentage DESC, section ASC;
            ";

    $params = [];

    $params[] = $year;
    $params[] = $month;

    $stmt = $conn->prepare($sql);

    $stmt->execute($params);

    // Initialize an array to hold the counts for each section
    $statusCounts = [];

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        do {
            // Add unique section to categories
            if (!in_array($row['section'], $categories)) {
                $categories[] = $row['section'];
            }

            $data['total_percentage'][] = (int)$row['total_percentage'];
        } while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
    } else {
        $data['total_percentage'] = [];
    }

    // Create the final data structure
    $finalData = [
        'categories' => $categories,
        'data' => [
            [
                'name' => 'Percentage',
                'data' => $data['total_percentage']
            ]
        ]
    ];

    // Encode the categories and data as JSON
    echo json_encode($finalData);
}

if ($method == 'get_month_section_top_non_compliance_time_out_chart') {
    $year = $_GET['year'];
    $month = $_GET['month'];

    $data = [];
    $categories = [];

    $sql = "
            DECLARE @Year INT = ?;  
            DECLARE @Month INT = ?; 

            WITH BvbTopCompliance AS (
                SELECT 
                    section,
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
                    emp_mgt_backup.dbo.t_biometric_vs_barcode 
                WHERE 
                    YEAR([day]) = @Year AND MONTH([day]) = @Month 
                GROUP BY 
                    section
            )

            SELECT TOP 10
                section, 
                total_percentage
            FROM 
                BvbTopCompliance
            ORDER BY
                total_percentage DESC, section ASC;
            ";

    $params = [];

    $params[] = $year;
    $params[] = $month;

    $stmt = $conn->prepare($sql);

    $stmt->execute($params);

    // Initialize an array to hold the counts for each section
    $statusCounts = [];

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        do {
            // Add unique section to categories
            if (!in_array($row['section'], $categories)) {
                $categories[] = $row['section'];
            }

            $data['total_percentage'][] = (int)$row['total_percentage'];
        } while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
    } else {
        $data['total_percentage'] = [];
    }

    // Create the final data structure
    $finalData = [
        'categories' => $categories,
        'data' => [
            [
                'name' => 'Percentage',
                'data' => $data['total_percentage']
            ]
        ]
    ];

    // Encode the categories and data as JSON
    echo json_encode($finalData);
}

// Biometric Vs Barcode Data

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
                    day, emp_no, full_name, dept, section, line_no, process, time_in_remarks, time_out_remarks 
                FROM 
                    emp_mgt_backup.dbo.t_biometric_vs_barcode 
                WHERE 
                    YEAR([day]) = @Year AND MONTH([day]) = @Month AND 
                    dept = 'PD1' AND section = 'Section 1' AND line_no = '5101' 

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
