<?php 
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

include '../server_date_time.php';
include '../conn.php';

switch (true) {
  case !isset($_SESSION['emp_no_control_area']):
    header('location:/emp_mgt/control_area');
    exit();
  case isset($_SESSION['emp_no']):
    header('location:/emp_mgt/admin');
    exit();
  case isset($_SESSION['emp_no_user']):
    header('location:/emp_mgt/user');
    exit();
  case isset($_SESSION['emp_no_clinic']):
    header('location:/emp_mgt/clinic');
    exit();
  case isset($_SESSION['emp_no_hr']):
    header('location:/emp_mgt/hr');
    exit();
  case isset($_SESSION['emp_no_tc']):
    header('location:/emp_mgt/tc');
    exit();
}

switch (true) {
  case !isset($_GET['emp_no']):
  case !isset($_GET['full_name']):
  case !isset($_GET['provider']):
  case !isset($_GET['line_no']):
  case !isset($_GET['shift']):
  case !isset($_GET['shift_group']):
  case !isset($_GET['process']):
  case !isset($_GET['sub_section']):
    echo 'Query Parameters Not Set';
    exit();
}

$emp_no = trim($_GET['emp_no']);
$full_name = trim($_GET['full_name']);
$provider = trim($_GET['provider']);
if (isset($_SESSION['emp_no_control_area'])) {
  $dept = $_SESSION['dept'];
  $section = $_SESSION['section'];
}
$line_no = trim($_GET['line_no']);
$shift = trim($_GET['shift']);
$shift_group = trim($_GET['shift_group']);
$process = trim($_GET['process']);
$sub_section = trim($_GET['sub_section']);

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

$delimiter = ","; 

$filename = "EmpMgtSys_EmployeeMasterlist";

if (!empty($line_no)) {
  $filename = $filename . "_" . $line_no;
}

$filename = $filename . ".csv";
 
// Create a file pointer 
$f = fopen('php://memory', 'w'); 

// UTF-8 BOM for special character compatibility
fputs($f, "\xEF\xBB\xBF");

// Set column headers 
$fields = array('Employee No.', 'Full Name', 'Department', 'Section', 'Car Model', 'Process', 'Line No.', 'Specific Process', 'Position', 'Provider', 'Shift', 'Shift Group'); 
fputcsv($f, $fields, $delimiter); 

$query = "SELECT emp_no, full_name, dept, section, car_model, sub_section, line_no, process, position, provider, shift, shift_group FROM m_employees WHERE";
$params = [];


if (!empty($emp_no)) {
  $query = $query . " emp_no LIKE ?";
  $emp_no_search = $emp_no . "%";
  $params[] = $emp_no_search;
} else {
  $query = $query . " emp_no != ''";
}
if (!empty($full_name)) {
  $query = $query . " AND full_name LIKE ?";
  $full_name_search = $full_name . "%";
  $params[] = $full_name_search;
}
if (!empty($provider)) {
  $query = $query . " AND provider = ?";
  $params[] = $provider;
}
if (!empty($dept)) {
  $query = $query . " AND dept = ?";
  $params[] = $dept;
}
if (!empty($section)) {
  $query = $query . " AND section LIKE ?";
  $section_search = $section . "%";
  $params[] = $section_search;
}
if (!empty($line_no)) {
  $query = $query . " AND line_no LIKE ?";
  $line_no_search = $line_no . "%";
  $params[] = $line_no_search;
}

// Control Area Search
if (!empty($shift)) {
  if ($shift == 'No Shift') {
    $shift = '';
  }
  $query = $query . " AND shift = ?";
  $params[] = $shift;
}
if (!empty($shift_group)) {
  if ($shift == 'No Shift Group') {
    $shift_group = '';
  }
  $query = $query . " AND shift_group = ?";
  $params[] = $shift_group;
}
if (!empty($process)) {
  $query = $query . " AND process = ?";
  $params[] = $process;
}
if (!empty($sub_section)) {
  $query = $query . " AND sub_section = ?";
  $params[] = $sub_section;
}

// Control Area Only Active Employees
if (isset($_SESSION['emp_no_control_area'])) {
  $query = $query . " AND resigned = 0";
}

$stmt = $conn->prepare($query);
$stmt->execute($params);

$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {

    // Output each row of the data, format line as csv and write to file pointer 
    do {

        $lineData = array(
          $row['emp_no'], 
          $row['full_name'], 
          $row['dept'], 
          $row['section'], 
          $row['car_model'], 
          $row['sub_section'], 
          $row['line_no'], 
          $row['process'], 
          $row['position'], 
          $row['provider'], 
          $row['shift'], 
          $row['shift_group']
        ); 
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
