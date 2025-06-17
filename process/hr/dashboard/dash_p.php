<?php 
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

include '../../conn.php';

$method = $_POST['method'];

// Dashboard

function get_shift($server_time) {
	if ($server_time >= '06:00:00' && $server_time < '18:00:00') {
		return 'DS';
	} else if ($server_time >= '18:00:00' && $server_time <= '23:59:59') {
		return 'NS';
	} else if ($server_time >= '00:00:00' && $server_time < '06:00:00') {
		return 'NS';
	}
}

function count_emp_by_provider($provider, $search_arr, $conn) {
	$query = "SELECT count(provider) AS total FROM m_employees WHERE provider = ? AND resigned = 0";
	$params = [];
	$params[] = $provider;

	if (!empty($search_arr['dept'])) {
		$query = $query . " AND dept = ?";
		$params[] = $search_arr['dept'];

	}
	if (!empty($search_arr['section'])) {
		$query = $query . " AND section = ?";
		$params[] = $search_arr['section'];
	}
	if (!empty($search_arr['line_no'])) {
		$query = $query . " AND line_no = ?";
		$params[] = $search_arr['line_no'];
	}
	$query = $query . " AND shift_group = ?";
	$params[] = $search_arr['shift_group'];

	$stmt = $conn->prepare($query);
	$stmt->execute($params);

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
		$total = intval($row['total']);
	} else {
		$total = 0;
	}

	return $total;
}

function count_emp_by_provider_tio($provider, $search_arr, $conn) {
	$query = "SELECT count(emp.emp_no) AS total FROM m_employees emp
			LEFT JOIN t_time_in_out tio ON tio.emp_no = emp.emp_no
			WHERE emp.provider = ? AND emp.resigned = 0 AND tio.day = ? AND emp.shift_group = ?";
	$params = [];
	$params[] = $provider;
	$params[] = $search_arr['day'];
	$params[] = $search_arr['shift_group'];
	
	if (!empty($search_arr['dept'])) {
		$query = $query . " AND emp.dept = ?";
		$params[] = $search_arr['dept'];
	}
	if (!empty($search_arr['section'])) {
		$query = $query . " AND emp.section = ?";
		$params[] = $search_arr['section'];
	}
	if (!empty($search_arr['line_no'])) {
		$query = $query . " AND emp.line_no = ?";
		$params[] = $search_arr['line_no'];
	}

	$stmt = $conn->prepare($query);
	$stmt->execute($params);

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
		$total = intval($row['total']);
	} else {
		$total = 0;
	}

	return $total;
}

if ($method == 'count_emp_dashboard') {
	$day = $_POST['day'];
	$dept = $_POST['dept'];
	$section = $_POST['section'];
	$line_no = $_POST['line_no'];

	$total = 0;
	$total_shift_group_a = 0;
	$total_shift_group_b = 0;
	$total_shift_group_ads = 0;

	$where_clause = "";

	$query = "DECLARE @Day NVARCHAR(255) = ?; ";

	$params = [];

	$params[] = $day;

	if (!empty($dept)) {
		$where_clause = $where_clause . " AND dept = ?";
	}
	if (!empty($section)) {
		$where_clause = $where_clause . " AND section LIKE ?";
	}
	if (!empty($line_no)) {
		$where_clause = $where_clause . " AND line_no LIKE ?";
	}

	for ($i = 0; $i < 3; $i++) {
		if (!empty($dept)) {
			$params[] = $dept;
		}
		if (!empty($section)) {
			$section_search = $section . "%";
			$params[] = $section_search;
		}
		if (!empty($line_no)) {
			$line_no_search = $line_no . "%";
			$params[] = $line_no_search;
		}
	}

	$query .= " WITH EmployeeCounts AS (
				SELECT 
					COUNT(CASE WHEN shift_group = 'A' THEN 1 END) AS a,
					COUNT(CASE WHEN shift_group = 'B' THEN 1 END) AS b,
					COUNT(CASE WHEN shift_group = 'ADS' THEN 1 END) AS ads,
					COUNT(id) AS total,
					'Total' AS count_label
				FROM 
					m_employees
				WHERE 
					(resigned_date IS NULL OR resigned_date >= @Day) $where_clause 

				UNION ALL

				SELECT 
					COUNT(CASE WHEN emp.shift_group = 'A' THEN 1 END) AS a,
					COUNT(CASE WHEN emp.shift_group = 'B' THEN 1 END) AS b,
					COUNT(CASE WHEN emp.shift_group = 'ADS' THEN 1 END) AS ads,
					COUNT(emp.emp_no) AS total,
					'Present' AS count_label
				FROM 
					m_employees emp
				LEFT JOIN 
					t_time_in_out tio ON tio.emp_no = emp.emp_no
				WHERE 
					(emp.resigned_date IS NULL OR emp.resigned_date >= @Day) AND 
					tio.day = @Day $where_clause 

				UNION ALL

				SELECT 
					COUNT(CASE WHEN emp.shift_group = 'A' THEN 1 END) AS a,
					COUNT(CASE WHEN emp.shift_group = 'B' THEN 1 END) AS b,
					COUNT(CASE WHEN emp.shift_group = 'ADS' THEN 1 END) AS ads,
					COUNT(emp.emp_no) AS total,
					'Support' AS count_label
				FROM 
					m_employees emp
				LEFT JOIN 
					t_line_support_history ls ON ls.emp_no = emp.emp_no
				WHERE 
					(emp.resigned_date IS NULL OR emp.resigned_date >= @Day) AND 
					ls.day = @Day $where_clause 
			)

			SELECT 
				a,
				b,
				ads,
				total,
				count_label
			FROM 
				EmployeeCounts 

			UNION ALL

			SELECT 
				NULLIF(ec1.a, 0) - CAST(ec2.a AS INT) AS a,
				NULLIF(ec1.b, 0) - CAST(ec2.b AS INT) AS b,
				NULLIF(ec1.ads, 0) - CAST(ec2.ads AS INT) AS ads,
				NULLIF(ec1.total, 0) - CAST(ec2.total AS INT) AS total,
				'Absent' AS count_label
			FROM 
				(SELECT a, b, ads, total 
				FROM EmployeeCounts 
				WHERE count_label = 'Present') AS ec2,
				(SELECT a, b, ads, total 
				FROM EmployeeCounts 
				WHERE count_label = 'Total') AS ec1 

			UNION ALL

			SELECT 
				ROUND((CAST(ec2.a AS FLOAT) / NULLIF(ec1.a, 0)) * 100, 2) AS a,
				ROUND((CAST(ec2.b AS FLOAT) / NULLIF(ec1.b, 0)) * 100, 2) AS b,
				ROUND((CAST(ec2.ads AS FLOAT) / NULLIF(ec1.ads, 0)) * 100, 2) AS ads,
				ROUND((CAST(ec2.total AS FLOAT) / NULLIF(ec1.total, 0)) * 100, 2) AS total,
				'Attendance Percentage' AS count_label
			FROM 
				(SELECT a, b, ads, total 
				FROM EmployeeCounts 
				WHERE count_label = 'Present') AS ec2,
				(SELECT a, b, ads, total 
				FROM EmployeeCounts 
				WHERE count_label = 'Total') AS ec1 

			UNION ALL

			SELECT 
				ROUND(((NULLIF(ec1.a, 0) - CAST(ec2.a AS FLOAT)) * 100) / NULLIF(ec1.a, 0), 2) AS a,
				ROUND(((NULLIF(ec1.b, 0) - CAST(ec2.b AS FLOAT)) * 100) / NULLIF(ec1.b, 0), 2) AS b,
				ROUND(((NULLIF(ec1.ads, 0) - CAST(ec2.ads AS FLOAT)) * 100) / NULLIF(ec1.ads, 0), 2) AS ads,
				ROUND(((NULLIF(ec1.total, 0) - CAST(ec2.total AS FLOAT)) * 100) / NULLIF(ec1.total, 0), 2) AS total,
				'Absent Rate' AS count_label
			FROM 
				(SELECT a, b, ads, total 
				FROM EmployeeCounts 
				WHERE count_label = 'Present') AS ec2,
				(SELECT a, b, ads, total 
				FROM EmployeeCounts 
				WHERE count_label = 'Total') AS ec1;
			";

	$stmt = $conn->prepare($query);
	$stmt->execute($params);

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
		do {
			if ($row['count_label'] == 'Total') {
				$total_shift_group_a = intval($row['a']);
				$total_shift_group_b = intval($row['b']);
				$total_shift_group_ads = intval($row['ads']);
				$total = intval($row['total']);
			}
			if ($row['count_label'] == 'Present') {
				$total_present_ds = intval($row['a']);
				$total_present_ns = intval($row['b']);
				$total_present_ads = intval($row['ads']);
				$total_present = intval($row['total']);
			}
			if ($row['count_label'] == 'Support') {
				$total_support_ds = intval($row['a']);
				$total_support_ns = intval($row['b']);
				$total_support_ads = intval($row['ads']);
				$total_support = intval($row['total']);
			}
			if ($row['count_label'] == 'Absent') {
				$total_absent_ds = intval($row['a']);
				$total_absent_ns = intval($row['b']);
				$total_absent_ads = intval($row['ads']);
				$total_absent = intval($row['total']);
			}
			if ($row['count_label'] == 'Attendance Percentage') {
				$attendance_percentage_ds = floatval($row['a']);
				$attendance_percentage_ns = floatval($row['b']);
				$attendance_percentage_ads = floatval($row['ads']);
				$attendance_percentage_total = floatval($row['total']);
			}
		} while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
	}

	$response_arr = array(
		'total' => $total,
		'attendance_percentage_total' => $attendance_percentage_total,
		'total_shift_group_a' => $total_shift_group_a,
		'total_shift_group_b' => $total_shift_group_b,
		'total_shift_group_ads' => $total_shift_group_ads,
		'attendance_percentage_ds' => $attendance_percentage_ds,
		'attendance_percentage_ns' => $attendance_percentage_ns,
		'attendance_percentage_ads' => $attendance_percentage_ads,
		'total_present_ds' => $total_present_ds,
		'total_absent_ds' => $total_absent_ds,
		'total_support_ds' => $total_support_ds,
		'total_present_ns' => $total_present_ns,
		'total_absent_ns' => $total_absent_ns,
		'total_support_ns' => $total_support_ns,
		'total_present_ads' => $total_present_ads,
		'total_absent_ads' => $total_absent_ads,
		'total_support_ads' => $total_support_ads
	);

	//header('Content-Type: application/json; charset=utf-8');
    echo json_encode($response_arr, JSON_FORCE_OBJECT);
}

// Get Count Employee By Provider Small Boxes
if ($method == 'count_emp_provider_dashboard') {
	$day = $_POST['day'];
	$dept = $_POST['dept'];
	$section = $_POST['section'];
	$line_no = $_POST['line_no'];
	$shift = get_shift($server_time);
	$shift_group = $_POST['shift_group'];
	$small_box_colors_arr = array('bg-primary', 'bg-navy', 'bg-info', 'bg-warning', 'bg-lightblue', 'bg-purple', 'bg-olive', 'bg-gray');
	$small_box_color_count = count($small_box_colors_arr);
	$provider_count = 0;

	$sql = "SELECT provider FROM m_providers ORDER BY id ASC";

	$stmt = $conn -> prepare($sql);
	$stmt -> execute();

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
		do {
			if ($small_box_color_count == $provider_count) {
				$provider_count = 0;
			}

			$search_arr = array(
				"shift_group" => $shift_group,
				"dept" => $dept,
				"section" => $section,
				"line_no" => $line_no
			);

			$total = count_emp_by_provider($row['provider'], $search_arr, $conn);

			// if ($shift == 'DS') {
			// 	if ($server_time >= '05:00:00' && $server_time <= '23:59:59') {
			// 		$day = $server_date_only;
			// 	} else if ($server_time >= '00:00:00' && $server_time < '05:00:00') {
			// 		$day = $server_date_only_yesterday;
			// 	}
			// } else if ($shift == 'NS') {
			// 	if ($server_time >= '17:00:00' && $server_time <= '23:59:59') {
			// 		$day = $server_date_only;
			// 	} else if ($server_time >= '00:00:00' && $server_time < '17:00:00') {
			// 		$day = $server_date_only_yesterday;
			// 	}
			// }

			$search_arr1 = array(
			  "day" => $day,
			  "shift_group" => $shift_group,
			  "dept" => $dept,
			  "section" => $section,
			  "line_no" => $line_no
			);

			$total_present = count_emp_by_provider_tio($row['provider'], $search_arr1, $conn);
			$total_absent = $total - $total_present;
			if ($total != 0) {
				$attendance_percentage = round(($total_present / $total) * 100, 2);
			} else {
				$attendance_percentage = 0;
			}

			echo '<div class="col-xl-3 col-lg-3 col-md-6 col-12">
			<div class="small-box '.$small_box_colors_arr[$provider_count].'">
			<div class="inner mb-3">
			
			<h4><b>'.htmlspecialchars($row['provider']).'</b></h4>
			<h4 class="mb-3">Employees</h4>
			<div class="bg-light p-2"><div class="row"><div class="col-md-6 col-sm-12"><h4 class="ml-2">Total: </h4><h2 class="ml-2"><b>'.$total.'</b></h2></div><div class="col-md-6 col-sm-12"><h4 class="ml-2">Percentage: </h4><h2 class="ml-2"><b>'.$attendance_percentage.'%</b></h2></div></div><h4 class="ml-2">Present: </h4><h2 class="text-success ml-2"><b>'.$total_present.'</b></h2><h4 class="ml-2">Absent: </h4><h2 class="text-danger ml-2"><b>'.$total_absent.'</b></h2></div>
			</div>
			<div class="icon">
			<i class="ion ion-person"></i>
			</div>
			<div class="small-box-footer"></div>
			</div>
			</div>';
			$provider_count++;
		} while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
	}
}

if ($method == 'count_od') {

	$query = "DECLARE @Day NVARCHAR(255) = GETDATE();

			WITH EmployeeCounts AS (
				SELECT 
					0 AS ds,
					0 AS ns,
					COUNT(id) AS total,
					'Total' AS count_label
				FROM 
					m_employees
				WHERE 
					(resigned_date IS NULL OR resigned_date >= @Day) 

				UNION ALL

				SELECT 
					COUNT(CASE WHEN tio.shift = 'DS' THEN 1 END) AS ds,
					COUNT(CASE WHEN tio.shift = 'NS' THEN 1 END) AS ns,
					COUNT(emp.emp_no) AS total,
					'Present' AS count_label
				FROM 
					m_employees emp
				LEFT JOIN 
					t_time_in_out tio ON tio.emp_no = emp.emp_no
				WHERE 
					(emp.resigned_date IS NULL OR emp.resigned_date >= @Day) AND 
					tio.day = @Day 

				UNION ALL

				SELECT 
					COUNT(CASE WHEN ls.shift = 'DS' THEN 1 END) AS ds,
					COUNT(CASE WHEN ls.shift = 'NS' THEN 1 END) AS ns,
					COUNT(emp.emp_no) AS total,
					'Support' AS count_label
				FROM 
					m_employees emp
				LEFT JOIN 
					t_line_support_history ls ON ls.emp_no = emp.emp_no
				WHERE 
					(emp.resigned_date IS NULL OR emp.resigned_date >= @Day) AND 
					ls.day = @Day 
			)

			SELECT 
				ds,
				ns,
				total,
				count_label
			FROM 
				EmployeeCounts

			UNION ALL

			SELECT 
				0 AS ds,
				0 AS ns,
				CAST(ec1.total AS INT) - CAST(ec2.total AS INT) AS total,
				'Absent' AS count_label
			FROM 
				(SELECT ds, ns, total 
				FROM EmployeeCounts 
				WHERE count_label = 'Present') AS ec2,
				(SELECT ds, ns, total 
				FROM EmployeeCounts 
				WHERE count_label = 'Total') AS ec1 

			UNION ALL

			SELECT 
				0 AS ds,
				0 AS ns,
				ROUND((CAST(ec2.total AS FLOAT) / NULLIF(ec1.total, 0)) * 100, 2) AS total,
				'Attendance Percentage' AS count_label
			FROM 
				(SELECT ds, ns, total 
				FROM EmployeeCounts 
				WHERE count_label = 'Present') AS ec2,
				(SELECT ds, ns, total 
				FROM EmployeeCounts 
				WHERE count_label = 'Total') AS ec1 

			UNION ALL

			SELECT 
				0 AS ds,
				0 AS ns,
				ROUND(((CAST(ec1.total AS FLOAT) - CAST(ec2.total AS FLOAT)) * 100) / NULLIF(ec1.total, 0), 2) AS total,
				'Absent Rate' AS count_label
			FROM 
				(SELECT ds, ns, total 
				FROM EmployeeCounts 
				WHERE count_label = 'Present') AS ec2,
				(SELECT ds, ns, total 
				FROM EmployeeCounts 
				WHERE count_label = 'Total') AS ec1;
			";

	$stmt = $conn->prepare($query);
	$stmt->execute();

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
		do {
			if ($row['count_label'] == 'Total') {
				$od_registered_total = intval($row['total']);
			}
			if ($row['count_label'] == 'Present') {
				$od_present_ds = intval($row['ds']);
				$od_present_ns = intval($row['ns']);
				$od_present_total = intval($row['total']);
			}
			if ($row['count_label'] == 'Absent Rate') {
				$od_absent_rate = floatval($row['total']);
			}
		} while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
	}

	$response_arr = array(
		'od_registered_total' => $od_registered_total,
		'od_present_total' => $od_present_total,
		'od_absent_rate' => $od_absent_rate,
		'od_present_ds' => $od_present_ds,
		'od_present_ns' => $od_present_ns
	);

	//header('Content-Type: application/json; charset=utf-8');
    echo json_encode($response_arr, JSON_FORCE_OBJECT);
}

if ($method == 'get_daily_absent_rate_chart') {
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
                number < DAY(EOMONTH(DATEFROMPARTS(@Year, @Month, 1))) AND
        		DATEADD(DAY, number, DATEFROMPARTS(@Year, @Month, 1)) <= CAST(GETDATE() AS DATE)  -- Ensure dates are before today
        )

        SELECT 
            CONVERT(VARCHAR(10), dr.report_date, 120) AS report_date,
            CAST(
                CASE 
                    WHEN COUNT(emp.emp_no) > 0 THEN ((COUNT(emp.emp_no) - COUNT(tio.emp_no)) * 100.0 / COUNT(emp.emp_no)) 
                    ELSE 0 
                END AS DECIMAL(10, 2)
            ) AS absent_rate
        FROM 
            DateRange dr
        LEFT JOIN 
            m_employees emp ON (emp.resigned_date IS NULL OR emp.resigned_date >= dr.report_date)
        LEFT JOIN 
            t_time_in_out tio ON emp.emp_no = tio.emp_no AND tio.day = dr.report_date 
        GROUP BY 
            dr.report_date 
        ORDER BY 
            dr.report_date ASC;
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Initialize an array to hold the absent rates for each date
    $absentRates = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Add unique report_date to categories
        if (!in_array($row['report_date'], $categories)) {
            $categories[] = $row['report_date'];
        }

        // Store the absent rate for the corresponding report_date
        $absentRates[$row['report_date']] = floatval($row['absent_rate']);
    }

    // Create the final data structure
    $data[] = [
        'name' => 'Absent Rate',
        'data' => array_map(function($date) use ($absentRates) {
            return $absentRates[$date] ?? 0; // Default to 0 if no data
        }, $categories)
    ];

    // Encode the categories and data as JSON
    echo json_encode(['categories' => $categories, 'data' => $data]);
}

if ($method == 'get_daily_absent_rate_provider_chart') {
	$color_map = array(
        'GOLDENHAND' => '#3d9970', // Olive Green
        'ONE SOURCE' => '#ffc107', // Yellow
        'FAS' => '#007bff', // Blue
        'PKIMT' => '#001f3f', // Navy Blue
        'MAXIM' => '#17a2b8', // Cyan
        'ADD EVEN' => '#8a2be2', // Violet
		'MEGATREND' => '#3c8dbc', // Light Blue
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
                number < DAY(EOMONTH(DATEFROMPARTS(@Year, @Month, 1))) AND
        		DATEADD(DAY, number, DATEFROMPARTS(@Year, @Month, 1)) <= CAST(GETDATE() AS DATE)  -- Ensure dates are before today
        )

        SELECT 
            CONVERT(VARCHAR(10), dr.report_date, 120) AS report_date, 
			emp.provider, 
            CAST(
                CASE 
                    WHEN COUNT(emp.emp_no) > 0 THEN ((COUNT(emp.emp_no) - COUNT(tio.emp_no)) * 100.0 / COUNT(emp.emp_no)) 
                    ELSE 0 
                END AS DECIMAL(10, 2)
            ) AS absent_rate
        FROM 
            DateRange dr
        LEFT JOIN 
            m_employees emp ON (emp.resigned_date IS NULL OR emp.resigned_date >= dr.report_date)
        LEFT JOIN 
            t_time_in_out tio ON emp.emp_no = tio.emp_no AND tio.day = dr.report_date 
        GROUP BY 
            dr.report_date, emp.provider 
        ORDER BY 
            dr.report_date ASC, emp.provider ASC;
    ";

	$stmt = $conn->prepare($sql);
    $stmt->execute();

    // Initialize an array to hold the counts for each section
    $absentRates = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		// Add unique report_date to categories
		if (!in_array($row['report_date'], $categories)) {
			$categories[] = $row['report_date'];
		}

		if (!empty($row['provider'])) {
			// Create a unique key for provider
			$provider = $row['provider'];

			// Extract the report_date as a DateTime object
			$reportDate = new DateTime($row['report_date']);
			$dateString = $reportDate->format('Y-m-d'); // Use a standard date format

			// Initialize the absentRates for this provider if it doesn't exist
			if (!isset($absentRates[$provider])) {
				$absentRates[$provider] = [];
			}

			// Update the count for the specified status
			if (!isset($absentRates[$provider][$dateString])) {
				$absentRates[$provider][$dateString] = 0; // Initialize if not set
			}
			
			// Increment the absent rate for the specific date
			$absentRates[$provider][$dateString] += floatval($row['absent_rate']); // Use absent_rate for counts
		}
	}

    // Create the final data structure
    foreach ($absentRates as $provider => $counts) {
        $data[] = [
            'name' => $provider,
            'data' => $counts
        ];
    }

    // Encode the categories and data as JSON
    echo json_encode(['categories' => $categories, 'data' => $data, 'colorMap' => $color_map]);
}

$conn = NULL;
