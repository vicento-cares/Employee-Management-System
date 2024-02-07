<?php 
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

include '../conn.php';

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
  case !isset($_GET['emp_no']):
  case !isset($_GET['full_name']):
  case !isset($_GET['provider']):
  case !isset($_GET['dept']):
  case !isset($_GET['section']):
  case !isset($_GET['line_no']):
  case !isset($_GET['date_updated_from']):
  case !isset($_GET['date_updated_to']):
  case !isset($_GET['resigned']):
    echo 'Query Parameters Not Set';
    exit;
    break;
}

$emp_no = addslashes(trim($_GET['emp_no']));
$full_name = addslashes(trim($_GET['full_name']));
$provider = trim($_GET['provider']);
$dept = trim($_GET['dept']);
$section = addslashes(trim($_GET['section']));
$line_no = addslashes(trim($_GET['line_no']));

$date_updated_from = '';
if (isset($_GET['date_updated_from'])) {
  $date_updated_from = $_GET['date_updated_from'];
}
if (!empty($date_updated_from)) {
  $date_updated_from = date_create($date_updated_from);
  $date_updated_from = date_format($date_updated_from,"Y-m-d H:i:s");
}

$date_updated_to = '';
if (isset($_GET['date_updated_to'])) {
  $date_updated_to = $_GET['date_updated_to'];
}
if (!empty($date_updated_to)) {
  $date_updated_to = date_create($date_updated_to);
  $date_updated_to = date_format($date_updated_to,"Y-m-d H:i:s");
}

$resigned = trim($_GET['resigned']);

$delimiter = ","; 

$filename = "EmpMgtSys_EmployeeMasterlist_";
if (!empty($dept)) {
	$filename = $filename . $dept . "-";
}
if (!empty($section)) {
	$filename = $filename . $section . "-";
}
if (!empty($line_no)) {
	$filename = $filename . $line_no;
}
$filename = $filename . ".csv";
 
// Create a file pointer 
$f = fopen('php://memory', 'w'); 

// UTF-8 BOM for special character compatibility
fputs($f, "\xEF\xBB\xBF");

// Set column headers 
$fields = array('Employee No.', 'Full Name', 'Department', 'Section', 'Line No.', 'Process', 'Position', 'Provider', 'Gender', 'Shift Group', 'Date Hired', 'Address', 'Contact No.', 'Employment Status', 'Shuttle Route', 'Jr. Staff or Staff', 'Supervisor', 'Approver', 'Date Resigned'); 
fputcsv($f, $fields, $delimiter); 

$query = "SELECT emp_no, full_name, dept, section, line_no, process, position, provider, gender, shift_group, date_hired, address, contact_no, emp_status, shuttle_route, emp_js_s_no, emp_sv_no, emp_approver_no, resigned_date FROM m_employees WHERE";
if (!empty($emp_no)) {
  $query = $query . " emp_no LIKE '".$emp_no."%'";
} else {
  $query = $query . " emp_no != ''";
}
if (!empty($full_name)) {
  $query = $query . " AND full_name LIKE '$full_name%'";
}
if (!empty($provider)) {
  $query = $query . " AND provider = '$provider'";
}
if (!empty($dept)) {
  $query = $query . " AND dept = '$dept'";
}
if (!empty($section)) {
  $query = $query . " AND section LIKE '$section%'";
}
if (!empty($line_no)) {
  $query = $query . " AND line_no LIKE '$line_no%'";
}

if (!empty($date_updated_from) && !empty($date_updated_to)) {
  $query = $query . " AND date_updated BETWEEN '$date_updated_from' AND '$date_updated_to'";
}

if ($resigned != '') {
  if ($resigned == 1 || $resigned == 0) {
    $query = $query . " AND resigned = '$resigned'";
  }
}

$stmt = $conn->prepare($query);
$stmt->execute();


if ($stmt -> rowCount() > 0) {

    // Output each row of the data, format line as csv and write to file pointer 
    while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) { 

        $lineData = array($row['emp_no'], $row['full_name'], $row['dept'], $row['section'], $row['line_no'], $row['process'], $row['position'], $row['provider'], $row['gender'], $row['shift_group'], $row['date_hired'], $row['address'], $row['contact_no'], $row['emp_status'], $row['shuttle_route'], $row['emp_js_s_no'], $row['emp_sv_no'], $row['emp_approver_no'], $row['resigned_date']); 
        fputcsv($f, $lineData, $delimiter); 
	    
    }

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
?>