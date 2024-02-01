<?php
session_name("emp_mgt");
session_start();

require('../conn.php');

switch (true) {
  case !isset($_SESSION['emp_no_hr']):
    header('location:/emp_mgt/hr');
    exit;
    break;
  case isset($_SESSION['emp_no']):
    header('location:/emp_mgt/admin');
    exit;
    break;
  case isset($_SESSION['emp_no_user']):
    header('location:/emp_mgt/user');
    exit;
    break;
  case isset($_SESSION['emp_no_clinic']):
    header('location:/emp_mgt/clinic');
    exit;
    break;
}

switch (true) {
    case !isset($_GET['day']):
    case !isset($_GET['shift']):
    case !isset($_GET['dept']):
    case !isset($_GET['section']):
    case !isset($_GET['line_no']):
        echo 'Query Parameters Not Set';
        exit;
        break;
}

$day = $_GET['day'];
$shift = $_GET['shift'];

if (!empty($_GET['dept'])) {
	$dept_label = $_GET['dept'];
	$dept = addslashes($dept_label);
} else {
	$dept = '';
}
if (!empty($_GET['section'])) {
	$section_label = $_GET['section'];
	$section = addslashes($section_label);
} else {
	$section = '';
}
if (!empty($_GET['line_no'])) {
	$line_no_label = $_GET['line_no'];
	$line_no = addslashes($line_no_label);
} else {
	$line_no = '';
}

$c = 0;

$delimiter = ","; 

$filename = "EmpMgtSys_AttendanceList_";
if (!empty($dept)) {
	$filename = $filename . $dept_label . "-";
}
if (!empty($section)) {
	$filename = $filename . $section_label . "-";
}
if (!empty($line_no)) {
	$filename = $filename . $line_no_label . "-";
}
$filename = $filename . $day."-".$shift.".csv";
 
// Create a file pointer 
$f = fopen('php://memory', 'w'); 

// UTF-8 BOM for special character compatibility
fputs($f, "\xEF\xBB\xBF");
 
// Set column headers 
$fields = array('#', 'Provider', 'ID No.', 'Name', 'Department', 'Section', 'Line No.', 'Shift', 'Time In', 'Time Out', 'IP', 'Status'); 
fputcsv($f, $fields, $delimiter); 

/*$sql = "SELECT 
	emp.provider, emp.emp_no, emp.full_name, emp.dept, emp.section, emp.line_no, emp.resigned_date, 
	tio.time_in
	FROM m_employees emp
	LEFT JOIN t_time_in_out AS tio 
		ON emp.emp_no = tio.emp_no 
		AND tio.day = '$day' 
		AND tio.shift = '$shift'
	WHERE";*/
$sql = "SELECT 
	emp.provider, emp.emp_no, emp.full_name, emp.dept, emp.section, emp.line_no, emp.resigned_date, 
	tio.shift, tio.time_in, tio.time_out, tio.ip
	FROM m_employees emp
	LEFT JOIN t_time_in_out AS tio 
		ON emp.emp_no = tio.emp_no
	WHERE tio.day = '$day' 
	AND tio.shift = '$shift'";
if (!empty($dept)) {
	$sql = $sql . " AND emp.dept = '$dept'";
} else {
	$sql = $sql . " AND emp.dept != ''";
}
/*if (!empty($dept)) {
	$sql = $sql . " emp.dept = '$dept'";
} else {
	$sql = $sql . " emp.dept != ''";
}*/
if (!empty($section)) {
	$sql = $sql . " AND emp.section = '$section'";
}
if (!empty($line_no)) {
	$sql = $sql . " AND emp.line_no = '$line_no'";
}
$sql = $sql . " AND (emp.resigned_date IS NULL OR emp.resigned_date = '0000-00-00' OR emp.resigned_date >= '$day')";
$sql = $sql . " ORDER BY emp.emp_no ASC";

$stmt = $conn->prepare($sql);
$stmt->execute();
if ($stmt -> rowCount() > 0) {
     
    // Output each row of the data, format line as csv and write to file pointer 
    while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) { 
    	$c++;
    	$row_section = '';
    	$row_line_no = '';
    	$row_status = '';
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
		if (!empty($row['time_in'])) {
			$row_status = 'Present';
		} else {
			$row_status = 'Absent';
		}

        $lineData = array($c, $row['provider'], $row['emp_no'], $row['full_name'], $row['dept'], $row_section, $row_line_no, $row['shift'], $row['time_in'], $row['time_out'], $row['ip'], $row_status); 
        fputcsv($f, $lineData, $delimiter); 
    }
}

// Move back to beginning of file 
fseek($f, 0); 
 
// Set headers to download file rather than displayed 
header('Content-Type: text/csv'); 
header('Content-Disposition: attachment; filename="' . $filename . '";'); 
 
//output all remaining data on a file pointer 
fpassthru($f); 

$conn = null;

?>