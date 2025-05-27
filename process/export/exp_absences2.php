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
        exit;
        break;
}

$day = $_GET['day'];
$shift_group = $_GET['shift_group'];
$dept = $_GET['dept'];
$section = $_GET['section'];
$line_no = $_GET['line_no'];

$c = 0;

$delimiter = ","; 

$filename = "EmpMgtSys_AbsencesReport_";
if (!empty($dept)) {
	$filename = $filename . $dept . "-";
}
if (!empty($section)) {
	$filename = $filename . $section . "-";
}
if (!empty($line_no)) {
	$filename = $filename . $line_no . "-";
}
$filename = $filename . $day."-".$shift_group.".csv";
 
// Create a file pointer 
$f = fopen('php://memory', 'w'); 

// UTF-8 BOM for special character compatibility
fputs($f, "\xEF\xBB\xBF");

// Set column headers 
$fields = array('#', 'Provider', 'ID No.', 'Name', 'Department', 'Section', 'Line No.', 'No. of days', 'Absent Type', 'Reason'); 
fputcsv($f, $fields, $delimiter); 

/*$sql = "SELECT 
	emp.provider, emp.emp_no, emp.full_name, emp.dept, emp.section, emp.line_no, emp.resigned_date, 
	absences.absent_type, absences.reason 
	FROM m_employees emp
	LEFT JOIN 
	(SELECT t_absences.emp_no, t_absences.absent_type, t_absences.reason FROM t_absences WHERE t_absences.day = '$day' AND t_absences.shift = '$shift' AND t_absences.absent_type != '' AND t_absences.reason != '') AS absences ON absences.emp_no = emp.emp_no
	WHERE emp.dept = '$dept'";*/
$sql = "SELECT 
	emp.provider, emp.emp_no, emp.full_name, emp.dept, emp.section, emp.line_no, emp.resigned_date, 
	absences.absent_type, absences.reason 
	FROM m_employees emp
	LEFT JOIN 
	t_absences absences ON absences.emp_no = emp.emp_no
	WHERE absences.day = ? AND absences.shift_group = ? AND absences.absent_type != '' AND absences.reason != ''";
$params = [];
$params[] = $day;
$params[] = $shift_group;

if (!empty($dept)) {
	$sql = $sql . " AND emp.dept LIKE ?";
	$dept_param = $dept . "%";
	$params[] = $dept_param;
} else {
	$sql = $sql . " AND emp.dept != ''";
}
if (!empty($section)) {
	$sql = $sql . " AND emp.section = ?";
	$params[] = $section;
}
if ($line_no == 'No Line') {
    $sql = $sql . " AND emp.line_no IS NULL";
} else if (!empty($line_no)) {
    $sql = $sql . " AND emp.line_no = ?";
	$params[] = $line_no;
} else {
    $sql = $sql . " AND (emp.line_no = '' OR emp.line_no IS NULL)";
}
$sql = $sql . " AND (emp.resigned_date IS NULL OR emp.resigned_date >= ?)";
$params[] = $day;
$sql = $sql . " ORDER BY emp.emp_no ASC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);

$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {

    // Output each row of the data, format line as csv and write to file pointer 
    do {
    	$c++;
    	$row_section = '';
    	$row_line_no = '';
    	$row_no_of_absent = '1';
        if (!empty($row['section'])) {
			$row_section = $row['section'];
		} else {
			$row_section = 'N/A';
		}
		if (!empty($row['line_no'])) {
			$row_line_no = $row['line_no'];
		} else {
			$row_line_no = 'N/A';
		}

        $lineData = array($c, $row['provider'], $row['emp_no'], $row['full_name'], $row['dept'], $row_section, $row_line_no, $row_no_of_absent, $row['absent_type'], $row['reason']); 
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
