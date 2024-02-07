<?php
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

if (!isset($_SESSION['emp_no_hr'])) {
  header('location:/emp_mgt/hr');
  exit;
}

require('../conn.php');

switch (true) {
    case !isset($_GET['date_filed_from']):
    case !isset($_GET['date_filed_to']):
    case !isset($_GET['leave_type']):
    case !isset($_GET['leave_form_status']):
        echo 'Query Parameters Not Set';
        exit;
        break;
}

$date_filed_from = $_GET['date_filed_from'];
if (!empty($date_filed_from)) {
  $date_filed_from = date_create($date_filed_from);
  $date_filed_from = date_format($date_filed_from,"Y-m-d");
}
$date_filed_to = $_GET['date_filed_to'];
if (!empty($date_filed_to)) {
  $date_filed_to = date_create($date_filed_to);
  $date_filed_to = date_format($date_filed_to,"Y-m-d");
}
$leave_type = $_GET['leave_type'];
$leave_form_status = $_GET['leave_form_status'];

$c = 0;

$delimiter = ","; 

$filename = "EmpMgtSys_LeaveFormHistory(".$date_filed_from."_".$date_filed_to.").csv";
 
// Create a file pointer 
$f = fopen('php://memory', 'w'); 

// UTF-8 BOM for special character compatibility
fputs($f, "\xEF\xBB\xBF");

// Set column headers 
$fields = array('#', 'Date Filed', 'Leave Form ID', 'Employee No.', 'Full Name', 'Department', 'Position', 'Employment Status', 'Date Hired', 'Address on Leave', 'Contact No. on Leave', 'Type of Leave', 'Leave Date From', 'Leave Date To', 'Total No. of Days', 'Information Received Through', 'Information Received By', 'Reason', 'Issued By', 'Jr. Staff / Staff', 'Supervisor', 'Managing / Approving Officer', 'Remarks 1', 'Remarks 2', 'Remarks 3', 'Recommendation 1', 'Recommendation 1', 'Recommendation 1', 'Recommendation 1', 'Nurse / Doctor Remarks', 'Nurse / Doctor Name', 'Nurse / Doctor Date'); 
fputcsv($f, $fields, $delimiter); 

$sql = "SELECT 
	lfh.leave_form_id, lfh.emp_no, lfh.date_filed, lfh.address, lfh.contact_no, lfh.leave_type, lfh.leave_date_from, lfh.leave_date_to, lfh.total_leave_days, lfh.irt_phone_call, lfh.irt_letter, lfh.irb, lfh.reason, lfh.issued_by, lfh.js_s, lfh.sv, lfh.approver, lfh.leave_form_status, lfh.sl_r1_1_hrs, lfh.sl_r1_1_date, lfh.sl_r1_1_time_in, lfh.sl_r1_1_time_out, lfh.sl_r1_2_days, lfh.sl_r1_3_date, lfh.sl_rc_1_days, lfh.sl_rc_2_from, lfh.sl_rc_2_to, lfh.sl_rc_3_oc, lfh.sl_rc_4_hm, lfh.sl_rc_mgh, lfh.sl_r2, lfh.sl_dr_name, lfh.sl_dr_date,
	emp.full_name, emp.dept, emp.position, emp.date_hired, emp.emp_status
		FROM t_leave_form_history lfh
		LEFT JOIN m_employees emp
		ON emp.emp_no = lfh.emp_no
		WHERE (lfh.date_filed >= '$date_filed_from' AND lfh.date_filed <= '$date_filed_to')";

if (!empty($leave_type)) {
	$sql = $sql . " AND lfh.leave_type = '$leave_type'";
	if (empty($leave_form_status)) {
		$sql = $sql . " AND (lfh.leave_form_status = 'approved' OR lfh.leave_form_status = 'disapproved')";
	} else if ($leave_form_status == 'approved') {
		$sql = $sql . " AND lfh.leave_form_status = 'approved'";
	} else if ($leave_form_status == 'disapproved') {
		$sql = $sql . " AND lfh.leave_form_status = 'disapproved'";
	}
} else if (empty($leave_form_status)) {
	$sql = $sql . " AND (lfh.leave_form_status = 'approved' OR lfh.leave_form_status = 'disapproved')";
} else if ($leave_form_status == 'approved') {
	$sql = $sql . " AND lfh.leave_form_status = 'approved'";
} else if ($leave_form_status == 'disapproved') {
	$sql = $sql . " AND lfh.leave_form_status = 'disapproved'";
}

$sql = $sql . " ORDER BY lfh.id DESC";

$stmt = $conn->prepare($sql);
$stmt->execute();
if ($stmt -> rowCount() > 0) {

    // Output each row of the data, format line as csv and write to file pointer 
    while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) { 
    	$c++;
    	$irt = '';
    	$remarks1 = '';
    	$remarks2 = '';
    	$remarks3 = '';
    	$recommendation1 = '';
    	$recommendation2 = '';
    	$recommendation3 = '';
    	$recommendation4 = '';

    	if ($row['irt_phone_call'] == 1) {
    		$irt = 'Phone Call';
    	} else if ($row['irt_letter'] == 1) {
    		$irt = 'Letter';
    	}

    	if ($row['sl_r1_1_hrs'] > 0 || $row['sl_r1_1_date'] != '' || $row['sl_r1_1_time_in'] != '' || $row['sl_r1_1_time_out'] != '') {
    		$remarks1 = 'Undertime (No. of Hours) ' . $row['sl_r1_1_hrs'] . ' Date: ' .$row['sl_r1_1_date']. ' Time In: ' .$row['sl_r1_1_time_in']. ' Time Out: ' . $row['sl_r1_1_time_out'];
    	}
    	if ($row['sl_r1_2_days'] > 0) {
    		$remarks2 = 'Sick Leave For: ' .$row['sl_r1_2_days']. ' days';
    	}
    	if ($row['sl_r1_3_date'] != '') {
    		$remarks3 = 'Fit To Work Effective ' . $row['sl_r1_3_date'];
    	}

    	if ($row['sl_rc_1_days'] > 0 || $row['sl_rc_2_from'] != '' || $row['sl_rc_2_to']  != '') {
    		$recommendation1 = 'Unfit for ' .$row['sl_rc_1_days']. ' day(s) From ' .$row['sl_rc_2_from']. ' To' . $row['sl_rc_2_to'];
    	}
    	if ($row['sl_rc_3_oc'] == 1) {
    		$recommendation2 = 'For Observation At The Clinic';
    	}
    	if ($row['sl_rc_4_hm'] == 1) {
    		$recommendation3 = 'For Hospital Management';
    	}
    	if ($row['sl_rc_mgh'] == 1) {
    		$recommendation4 = 'May Go Home';
    	}

        $lineData = array($c, $row['date_filed'], $row['leave_form_id'], $row['emp_no'], $row['full_name'], $row['dept'], $row['position'], $row['emp_status'], $row['date_hired'], $row['address'], $row['contact_no'], $row['leave_type'], $row['leave_date_from'], $row['leave_date_to'], $row['total_leave_days'], $irt, $row['irb'], $row['reason'], $row['issued_by'], $row['js_s'], $row['sv'], $row['approver'], $remarks1, $remarks2, $remarks3, $recommendation1, $recommendation2, $recommendation3, $recommendation4, $row['sl_r2'], $row['sl_dr_name'], $row['sl_dr_date']); 
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