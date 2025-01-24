<?php
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

require('../conn.php');

switch (true) {
	case !isset($_GET['day']):
	case !isset($_GET['shift_group']):
	case !isset($_GET['dept']):
	case !isset($_GET['section']):
	case !isset($_GET['line_no']):
		echo 'Query Parameters Not Set';
		exit();
}

$day = $_GET['day'];
$shift_group = $_GET['shift_group'];
$dept = $_GET['dept'];
$section = $_GET['section'];
$line_no = $_GET['line_no'];

$search_multiple_asr_shift_group_arr = [];
if (isset($_GET['search_multiple_asr_shift_group_arr']) && !empty($_GET['search_multiple_asr_shift_group_arr'])) {
  $search_multiple_asr_shift_group_arr = explode(",", $_GET['search_multiple_asr_shift_group_arr']);
}

$search_multiple_asr_dept_arr = [];
if (isset($_GET['search_multiple_asr_dept_arr']) && !empty($_GET['search_multiple_asr_dept_arr'])) {
  $search_multiple_asr_dept_arr = explode(",", $_GET['search_multiple_asr_dept_arr']);
}

$search_multiple_asr_section_arr = [];
if (isset($_GET['search_multiple_asr_section_arr']) && !empty($_GET['search_multiple_asr_section_arr'])) {
  $search_multiple_asr_section_arr = explode(",", $_GET['search_multiple_asr_section_arr']);
}

$search_multiple_asr_line_no_arr = [];
if (isset($_GET['search_multiple_asr_line_no_arr']) && !empty($_GET['search_multiple_asr_line_no_arr'])) {
  $search_multiple_asr_line_no_arr = explode(",", $_GET['search_multiple_asr_line_no_arr']);
}

$c = 0;

$delimiter = ",";

$filename = "EmpMgtSys_AttendanceSummaryReport_";

if (!empty($search_multiple_asr_shift_group_arr) || 
	!empty($search_multiple_asr_dept_arr) || 
	!empty($search_multiple_asr_section_arr) || 
	!empty($search_multiple_asr_line_no_arr)) {
	$filename = $filename . "MultipleSearch_" . $server_date_only;
} else {
	if (!empty($dept)) {
		$filename = $filename . $dept . "-";
	}
	if (!empty($section)) {
		$filename = $filename . $section . "-";
	}
	if (!empty($line_no)) {
		$filename = $filename . $line_no;
	}
	
	$filename = $filename . "_" . $day;
	
	if (!empty($shift_group)) {
		$filename = $filename . "-" . $shift_group;
	}
}

$filename = $filename . ".csv";

// Create a file pointer 
$f = fopen('php://memory', 'w');

// UTF-8 BOM for special character compatibility
fputs($f, "\xEF\xBB\xBF");

// Set column headers 
$fields = array('#', 'Shift Group', 'Department', 'Section', 'Line No.', 'Total MP', 'Present', 'Absent', 'Percentage');
fputcsv($f, $fields, $delimiter);

//MS SQL Server
$sql = "WITH AttendanceData AS (
			SELECT 
				emp.shift_group, 
				emp.dept, 
				emp.section, 
				ISNULL(emp.line_no, 'No Line') AS line_no, 
				COUNT(emp.emp_no) AS total, 
				COUNT(tio.emp_no) AS total_present, 
				COUNT(emp.emp_no) - COUNT(tio.emp_no) AS total_absent, 
				FORMAT(CASE 
					WHEN COUNT(emp.emp_no) > 0 THEN (COUNT(tio.emp_no) * 100.0 / COUNT(emp.emp_no)) 
					ELSE 0 
				END, 'N2') AS attendance_percentage,
				0 AS table_order
			FROM 
				m_employees emp 
			LEFT JOIN 
				t_time_in_out tio ON emp.emp_no = tio.emp_no AND tio.day = ? 
			WHERE 
				dept != ''";

$params = [];

$params[] = $day;

if (!empty($search_multiple_asr_shift_group_arr) || 
	!empty($search_multiple_asr_dept_arr) || 
	!empty($search_multiple_asr_section_arr) || 
	!empty($search_multiple_asr_line_no_arr)) {
	
	if (!empty($search_multiple_asr_shift_group_arr)) {
		// Create a placeholder string for the IDs
		$placeholders = implode(',', array_fill(0, count($search_multiple_asr_shift_group_arr), '?'));
		$sql = $sql . " AND emp.shift_group IN ($placeholders)";
		$params = array_merge($params, $search_multiple_asr_shift_group_arr); // Flatten the array
	}
	if (!empty($search_multiple_asr_dept_arr)) {
		// Create a placeholder string for the IDs
		$placeholders = implode(',', array_fill(0, count($search_multiple_asr_dept_arr), '?'));
		$sql = $sql . " AND emp.dept IN ($placeholders)";
		$params = array_merge($params, $search_multiple_asr_dept_arr); // Flatten the array
	}
	if (!empty($search_multiple_asr_section_arr)) {
		// Create a placeholder string for the IDs
		$placeholders = implode(',', array_fill(0, count($search_multiple_asr_section_arr), '?'));
		$sql = $sql . " AND emp.section IN ($placeholders)";
		$params = array_merge($params, $search_multiple_asr_section_arr); // Flatten the array
	}
	if (!empty($search_multiple_asr_line_no_arr)) {
		// Create a placeholder string for the IDs
		$placeholders = implode(',', array_fill(0, count($search_multiple_asr_line_no_arr), '?'));
		$sql = $sql . " AND emp.line_no IN ($placeholders)";
		$params = array_merge($params, $search_multiple_asr_line_no_arr); // Flatten the array
	}
} else {
	if (!empty($shift_group)) {
		$sql = $sql . " AND emp.shift_group = ?";
		$params[] = $shift_group;
	}
	if (!empty($dept)) {
		$sql = $sql . " AND emp.dept LIKE ?";
		$dept_search = $dept . "%";
		$params[] = $dept_search;
	}
	if (!empty($section)) {
		$sql = $sql . " AND emp.section LIKE ?";
		$section_search = $section . "%";
		$params[] = $section_search;
	}
	if (!empty($line_no)) {
		$sql = $sql . " AND emp.line_no LIKE ?";
		$line_no_search = $line_no . "%";
		$params[] = $line_no_search;
	}
}

$sql = $sql . " AND 
					(emp.resigned_date IS NULL OR emp.resigned_date >= ?) 
				GROUP BY 
					emp.dept, emp.section, emp.line_no, emp.shift_group 
			)

			SELECT * FROM AttendanceData

			UNION ALL

			SELECT 
				'Total' AS shift_group, 
				NULL AS dept, 
				NULL AS section, 
				NULL AS line_no, 
				SUM(total) AS total, 
				SUM(total_present) AS total_present, 
				SUM(total_absent) AS total_absent, 
				FORMAT(CASE 
					WHEN SUM(total) > 0 THEN (SUM(total_present) * 100.0 / SUM(total)) 
					ELSE 0 
				END, 'N2') AS attendance_percentage,
				1 AS table_order
			FROM 
				AttendanceData
			ORDER BY 
				table_order ASC, shift_group ASC";

$params[] = $day;

$stmt = $conn->prepare($sql);
$stmt->execute($params);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$c_label = "";
	if ($row['shift_group'] != 'Total') {
		$c++;
		$c_label = $c;
	}

	$lineData = array(
		$c_label,
		$row['shift_group'],
		$row['dept'],
		$row['section'],
		$row['line_no'],
		$row['total'],
		$row['total_present'],
		$row['total_absent'],
		$row['attendance_percentage']
	);
	fputcsv($f, $lineData, $delimiter);
}

// Move back to beginning of file 
fseek($f, 0);

// Set headers to download file rather than displayed 
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '";');

//output all remaining data on a file pointer 
fpassthru($f);

$conn = null;
