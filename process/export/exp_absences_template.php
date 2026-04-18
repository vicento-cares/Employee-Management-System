<?php
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

require('../conn.php');

switch (true) {
    case !isset($_GET['day']):
    case !isset($_GET['shift_group']):
        echo 'Query Parameters Not Set';
        exit();
}

$day = $_GET['day'];
$shift_group = $_GET['shift_group'];

if (!empty($_SESSION['emp_no_hr'])) {
	if (!empty($_GET['dept'])) {
		$dept = $_GET['dept'];
	} else {
		$dept = '';
	}
	if (!empty($_GET['section'])) {
		$section = $_GET['section'];
	} else {
		$section = '';
	}
	if (!empty($_GET['line_no'])) {
		$line_no = $_GET['line_no'];
	} else {
		$line_no = '';
	}
} else if (!empty($_SESSION['emp_no_control_area'])) {
	$dept = $_SESSION['dept'];
	$section = $_SESSION['section'];
	if (isset($_GET['line_no'])) {
		$line_no = $_GET['line_no'];
	} else {
		$line_no = '';
	}
} else {
	if (!empty($_GET['dept'])) {
		$dept = $_GET['dept'];
	} else {
		$dept = '';
	}
	$section = '';
	$line_no = $_SESSION['line_no'];
}

if (isset($_GET['attendance_status'])) {
	$attendance_status = intval($_GET['attendance_status']);
}

$delimiter = ","; 

$filename = "absences_template.csv";
 
// Create a file pointer 
$f = fopen('php://memory', 'w'); 

// UTF-8 BOM for special character compatibility
fputs($f, "\xEF\xBB\xBF");

// Set column headers 
$fields = array('Employee No.', 'Full Name', 'Line No.', 'Day', 'Shift Group', 'Absent Category', 'Absent Type', 'Reason'); 
fputcsv($f, $fields, $delimiter);

// Set column headers 
$fields = array('Ex: 23-12345', 'Dela Cruz, Juan M.', '5101', '2024-12-01', 'A or B or ADS', 'Absent or No Work', 'VL', 'reason','Note: Please do not modify header, this row and whole Full Name, Line No., and Shift Group Columns. Delete those row below that are not needed to submit or overwrite absences report filing'); 
fputcsv($f, $fields, $delimiter);

$sql = "SELECT 
			emp.emp_no, emp.full_name, emp.line_no, emp.shift_group, 
			absences.day AS absent_day, absences.shift_group AS absent_shift_group, absences.absent_category, absences.absent_type, absences.reason 
		FROM m_employees emp
		LEFT JOIN t_time_in_out tio ON tio.emp_no = emp.emp_no AND tio.day = ? 
		LEFT JOIN t_absences absences ON absences.emp_no = emp.emp_no AND absences.day = ? 
		WHERE emp.shift_group = ?";
$params = [
	$day,
	$day,
	$shift_group
];

if (!empty($attendance_status)) {
	switch ($attendance_status) {
		case 1:
			$sql = $sql . " AND tio.time_in IS NOT NULL";
			break;
		case 2:
			$sql = $sql . " AND tio.time_in IS NULL";
			break;
		case 3:
			$sql = $sql . " AND tio.time_in IS NULL AND absences.day IS NOT NULL";
			break;
		case 4:
			$sql = $sql . " AND tio.time_in IS NULL AND absences.day IS NULL";
			break;
	}
}

if (!empty($dept)) {
	$sql = $sql . " AND emp.dept LIKE ?";
	$dept_param = $dept . "%";
	$params[] = $dept_param;
} else {
	$sql = $sql . " AND emp.dept != ''";
}
if (!empty($section)) {
	$sql = $sql . " AND emp.section LIKE ?";
	$section_param = $section . "%";
	$params[] = $section_param;
}
if (!empty($line_no)) {
	$sql = $sql . " AND emp.line_no LIKE ?";
	$line_no_param = $line_no . "%";
	$params[] = $line_no_param;
}
$sql = $sql . " AND (emp.date_hired <= ?) AND (emp.resigned_date IS NULL OR emp.resigned_date >= ?)";
$params[] = $day;
$params[] = $day;
$sql = $sql . " ORDER BY emp.emp_no ASC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);

$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {

    // Output each row of the data, format line as csv and write to file pointer 
    do {
        $lineData = array($row['emp_no'], $row['full_name'], $row['line_no'], $day, $row['shift_group'], $row['absent_category'], $row['absent_type'], $row['reason']);
        fputcsv($f, $lineData, $delimiter);
    } while ($row = $stmt->fetch(PDO::FETCH_ASSOC));

} else {

	// Output each row of the data, format line as csv and write to file pointer 
    $lineData = array("NO DATA FOUND"); 
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
