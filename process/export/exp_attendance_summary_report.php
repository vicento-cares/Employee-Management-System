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

$c = 0;

$delimiter = ",";

$filename = "EmpMgtSys_AttendanceSummaryReport_";

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

$filename = $filename . ".csv";

// Create a file pointer 
$f = fopen('php://memory', 'w');

// UTF-8 BOM for special character compatibility
fputs($f, "\xEF\xBB\xBF");

// Set column headers 
$fields = array('#', 'Shift Group', 'Department', 'Section', 'Line No.', 'Total MP', 'Present', 'Absent', 'Percentage');
fputcsv($f, $fields, $delimiter);

$results = array();

//MS SQL Server
$sql = "SELECT 
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
			END, 'N2') AS attendance_percentage
		FROM 
			m_employees emp 
		LEFT JOIN 
			t_time_in_out tio ON emp.emp_no = tio.emp_no AND tio.day = ? 
		WHERE 
			dept != ''";

$params = [];

$params[] = $day;

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

$sql = $sql . " AND 
		(emp.resigned_date IS NULL OR emp.resigned_date >= ?) 
	GROUP BY 
		emp.dept, emp.section, emp.line_no, emp.shift_group 
	ORDER BY 
		emp.shift_group";

$params[] = $day;

$stmt = $conn->prepare($sql);
$stmt->execute($params);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$c++;

	$lineData = array(
		$c,
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

// $lineData = array("Total MP :", "", "", "", "", $total_mp, $total_present_mp, $total_absent_mp, $total_attendance_percentage); 
// fputcsv($f, $lineData, $delimiter);

// Move back to beginning of file 
fseek($f, 0);

// Set headers to download file rather than displayed 
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '";');

//output all remaining data on a file pointer 
fpassthru($f);

$conn = null;
