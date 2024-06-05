<?php
session_name("emp_mgt");
session_start();

require('../conn.php');

function get_shift($server_time) {
	if ($server_time >= '06:00:00' && $server_time < '18:00:00') {
		return 'DS';
	} else if ($server_time >= '18:00:00' && $server_time <= '23:59:59') {
		return 'NS';
	} else if ($server_time >= '00:00:00' && $server_time < '06:00:00') {
		return 'NS';
	}
}

function count_emp_dashboard($search_arr, $conn) {
	$query = "SELECT count(id) AS total FROM m_employees WHERE resigned = 0";
	if (!empty($search_arr['dept'])) {
		$query = $query . " AND dept = '".$search_arr['dept']."'";
	}
	if (!empty($search_arr['section'])) {
		$query = $query . " AND section LIKE '".$search_arr['section']."%'";
	}
	if (!empty($search_arr['line_no'])) {
		$query = $query . " AND line_no LIKE '".$search_arr['line_no']."%'";
	}
	if (!empty($search_arr['shift_group'])) {
		$query = $query . " AND shift_group = '".$search_arr['shift_group']."'";
	}
	$stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $j){
			$total = intval($j['total']);
		}
	}else{
		$total = 0;
	}
	return $total;
}

function count_emp_by_provider($provider, $search_arr, $conn) {
	$query = "SELECT count(provider) AS total FROM m_employees WHERE provider = '$provider' AND resigned = 0";
	if (!empty($search_arr['dept'])) {
		$query = $query . " AND dept = '".$search_arr['dept']."'";
	}
	if (!empty($search_arr['section'])) {
		$query = $query . " AND section = '".$search_arr['section']."'";
	}
	if (!empty($search_arr['line_no'])) {
		$query = $query . " AND line_no = '".$search_arr['line_no']."'";
	}
	$query = $query . " AND shift_group = '".$search_arr['shift_group']."'";
	$stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $j){
			$total = intval($j['total']);
		}
	}else{
		$total = 0;
	}
	return $total;
}

function count_emp_by_provider_tio($provider, $search_arr, $conn) {
	$query = "SELECT count(emp.emp_no) AS total FROM m_employees emp
			LEFT JOIN t_time_in_out tio ON tio.emp_no = emp.emp_no
			WHERE emp.provider = '$provider' AND emp.resigned = 0 AND tio.day = '".$search_arr['day']."' AND emp.shift_group = '".$search_arr['shift_group']."'";
	if (!empty($search_arr['dept'])) {
		$query = $query . " AND emp.dept = '".$search_arr['dept']."'";
	}
	if (!empty($search_arr['section'])) {
		$query = $query . " AND emp.section = '".$search_arr['section']."'";
	}
	if (!empty($search_arr['line_no'])) {
		$query = $query . " AND emp.line_no = '".$search_arr['line_no']."'";
	}
	$stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $j){
			$total = intval($j['total']);
		}
	}else{
		$total = 0;
	}
	return $total;
}

function count_emp_tio($search_arr, $conn) {
	$query = "SELECT count(emp.emp_no) AS total FROM m_employees emp
			LEFT JOIN t_time_in_out tio ON tio.emp_no = emp.emp_no
			WHERE emp.resigned = 0 AND tio.day = '".$search_arr['day']."' AND emp.shift_group = '".$search_arr['shift_group']."'";
	if (!empty($search_arr['dept'])) {
		$query = $query . " AND emp.dept = '".$search_arr['dept']."'";
	}
	if (!empty($search_arr['section'])) {
		$query = $query . " AND emp.section = '".$search_arr['section']."'";
	}
	if (!empty($search_arr['line_no'])) {
		$query = $query . " AND emp.line_no = '".$search_arr['line_no']."'";
	}
	$stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $j){
			$total = intval($j['total']);
		}
	}else{
		$total = 0;
	}
	return $total;
}

function count_emp_lsh($search_arr, $conn) {
	$query = "SELECT count(emp.id) AS total FROM m_employees emp
			LEFT JOIN t_line_support_history ls
			ON emp.emp_no = ls.emp_no";

	$query = $query . " WHERE ls.day = '".$search_arr['day']."' AND ls.shift = '".$search_arr['shift']."'";

	if (!empty($search_arr['line_no'])) {
		$query = $query . " AND ls.line_no_to LIKE '".$search_arr['line_no']."%'";
	}

	$query = $query . " AND ls.status = 'accepted' AND emp.shift_group = '".$search_arr['shift_group']."'";

	if (!empty($search_arr['dept'])) {
		$query = $query . " AND emp.dept = '".$search_arr['dept']."'";
	}

	if (!empty($search_arr['section'])) {
		$query = $query . " AND emp.section = '".$search_arr['section']."'";
	}

	$stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $row){
			$total = intval($row['total']);
		}
	}else{
		$total = 0;
	}
	return $total;
}

switch (true) {
	case !isset($_GET['day']):
    case !isset($_GET['dept']):
    case !isset($_GET['section']):
    case !isset($_GET['line_no']):
        echo 'Query Parameters Not Set';
        exit;
        break;
}

$day = $_GET['day'];
$shift = get_shift($server_time);
$dept = $_GET['dept'];
$section_label = $_GET['section'];
$section = addslashes($section_label);
$line_no_label = $_GET['line_no'];
$line_no = addslashes($line_no_label);

$search_arr = array(
  "dept" => $dept,
  "section" => $section,
  "line_no" => $line_no
);

$total_emp = count_emp_dashboard($search_arr, $conn);

// $shift = 'DS';
$shift_group = 'A';
// if ($server_time >= '05:00:00' && $server_time <= '23:59:59') {
// 	$day = $server_date_only;
// } else if ($server_time >= '00:00:00' && $server_time < '05:00:00') {
// 	$day = $server_date_only_yesterday;
// }

$search_arr = array(
	"dept" => $dept,
	"section" => $section,
	"line_no" => $line_no,
	"shift_group" => $shift_group
);

$total_emp_shift_group_a = count_emp_dashboard($search_arr, $conn);

$search_arr1 = array(
  "day" => $day,
  "shift" => $shift,
  "shift_group" => $shift_group,
  "dept" => $dept,
  "section" => $section,
  "line_no" => $line_no
);

$total_present_ds = count_emp_tio($search_arr1, $conn);
$total_absent_ds = $total_emp_shift_group_a - $total_present_ds;
$total_support_ds = count_emp_lsh($search_arr1, $conn);

if ($total_emp_shift_group_a != 0) {
	$attendance_percentage_ds = round(($total_present_ds / $total_emp_shift_group_a) * 100, 2);
} else {
	$attendance_percentage_ds = 0;
}

$delimiter = ","; 

$filename = "EmpMgtSys_ExpDash-";

if (!empty($dept)) {
	$filename = $filename . $dept . "-";
}
if (!empty($section)) {
	$filename = $filename . $section_label . "-";
}
if (!empty($line_no)) {
	$filename = $filename . $line_no_label . "-";
}
$filename = $filename . $day.".csv";

// Create a file pointer 
$f = fopen('php://memory', 'w'); 

// UTF-8 BOM for special character compatibility
fputs($f, "\xEF\xBB\xBF");

$fields = array('Shift Group A'); 
fputcsv($f, $fields, $delimiter);

$fields = array(''); 
fputcsv($f, $fields, $delimiter);

$fields = array('Label', 'Total'); 
fputcsv($f, $fields, $delimiter);

$fields = array('Present MP', $total_present_ds); 
fputcsv($f, $fields, $delimiter);

$fields = array('Absent MP', $total_absent_ds); 
fputcsv($f, $fields, $delimiter);

$fields = array('Total Shift A MP', $total_emp_shift_group_a); 
fputcsv($f, $fields, $delimiter);

$fields = array('Total MP', $total_emp); 
fputcsv($f, $fields, $delimiter);

$fields = array('Support MP', $total_support_ds); 
fputcsv($f, $fields, $delimiter);

$fields = array('Percentage', $attendance_percentage_ds); 
fputcsv($f, $fields, $delimiter);

$fields = array(''); 
fputcsv($f, $fields, $delimiter);


// Set column headers 
$fields = array('#', 'Provider', 'Total', 'Present', 'Absent', 'Percentage'); 
fputcsv($f, $fields, $delimiter);

$c = 0;

$sql = "SELECT `provider` FROM `m_providers` ORDER BY id ASC";
$stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt -> execute();
if ($stmt -> rowCount() > 0) {
	foreach($stmt -> fetchAll() as $row) {
		$c++;
		
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
		  "shift" => $shift,
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

		$lineData = array($c, $row['provider'], $total, $total_present, $total_absent, $attendance_percentage); 
        fputcsv($f, $lineData, $delimiter);
	}
}


$fields = array(''); 
fputcsv($f, $fields, $delimiter);


// $shift = 'NS';
$shift_group = 'B';
// if ($server_time >= '17:00:00' && $server_time <= '23:59:59') {
// 	$day = $server_date_only;
// } else if ($server_time >= '00:00:00' && $server_time < '17:00:00') {
// 	$day = $server_date_only_yesterday;
// }

$search_arr = array(
	"dept" => $dept,
	"section" => $section,
	"line_no" => $line_no,
	"shift_group" => $shift_group
);

$total_emp_shift_group_b = count_emp_dashboard($search_arr, $conn);

$search_arr1 = array(
  "day" => $day,
  "shift" => $shift,
  "shift_group" => $shift_group,
  "dept" => $dept,
  "section" => $section,
  "line_no" => $line_no
);

$total_present_ns = count_emp_tio($search_arr1, $conn);
$total_absent_ns = $total_emp_shift_group_b - $total_present_ns;
$total_support_ns = count_emp_lsh($search_arr1, $conn);

if ($total_emp_shift_group_b != 0) {
	$attendance_percentage_ns = round(($total_present_ns / $total_emp_shift_group_b) * 100, 2);
} else {
	$attendance_percentage_ns = 0;
}

$fields = array('Shift Group B'); 
fputcsv($f, $fields, $delimiter);

$fields = array(''); 
fputcsv($f, $fields, $delimiter);

$fields = array('Label', 'Total'); 
fputcsv($f, $fields, $delimiter);

$fields = array('Present MP', $total_present_ns); 
fputcsv($f, $fields, $delimiter);

$fields = array('Absent MP', $total_absent_ns); 
fputcsv($f, $fields, $delimiter);

$fields = array('Total Shift B MP', $total_emp_shift_group_b); 
fputcsv($f, $fields, $delimiter);

$fields = array('Total MP', $total_emp); 
fputcsv($f, $fields, $delimiter);

$fields = array('Support MP', $total_support_ns); 
fputcsv($f, $fields, $delimiter);

$fields = array('Percentage', $attendance_percentage_ns); 
fputcsv($f, $fields, $delimiter);

$fields = array(''); 
fputcsv($f, $fields, $delimiter);


// Set column headers 
$fields = array('#', 'Provider', 'Total', 'Present', 'Absent', 'Percentage'); 
fputcsv($f, $fields, $delimiter);

$c = 0;

$sql = "SELECT `provider` FROM `m_providers` ORDER BY id ASC";
$stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt -> execute();
if ($stmt -> rowCount() > 0) {
	foreach($stmt -> fetchAll() as $row) {
		$c++;
		
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
		  "shift" => $shift,
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

		$lineData = array($c, $row['provider'], $total, $total_present, $total_absent, $attendance_percentage); 
        fputcsv($f, $lineData, $delimiter);
	}
}


$fields = array(''); 
fputcsv($f, $fields, $delimiter);


// $shift = 'DS';
$shift_group = 'ADS';
// if ($server_time >= '05:00:00' && $server_time <= '23:59:59') {
// 	$day = $server_date_only;
// } else if ($server_time >= '00:00:00' && $server_time < '05:00:00') {
// 	$day = $server_date_only_yesterday;
// }

$search_arr = array(
	"dept" => $dept,
	"section" => $section,
	"line_no" => $line_no,
	"shift_group" => $shift_group
);

$total_emp_shift_group_ads = count_emp_dashboard($search_arr, $conn);

$search_arr1 = array(
  "day" => $day,
  "shift" => $shift,
  "shift_group" => $shift_group,
  "dept" => $dept,
  "section" => $section,
  "line_no" => $line_no
);

$total_present_ads = count_emp_tio($search_arr1, $conn);
$total_absent_ads = $total_emp_shift_group_ads - $total_present_ads;
$total_support_ads = count_emp_lsh($search_arr1, $conn);

if ($total_emp_shift_group_ads != 0) {
	$attendance_percentage_ads = round(($total_present_ads / $total_emp_shift_group_ads) * 100, 2);
} else {
	$attendance_percentage_ads = 0;
}

$fields = array('Shift Group ADS'); 
fputcsv($f, $fields, $delimiter);

$fields = array(''); 
fputcsv($f, $fields, $delimiter);

$fields = array('Label', 'Total'); 
fputcsv($f, $fields, $delimiter);

$fields = array('Present MP', $total_present_ads); 
fputcsv($f, $fields, $delimiter);

$fields = array('Absent MP', $total_absent_ads); 
fputcsv($f, $fields, $delimiter);

$fields = array('Total Shift ADS MP', $total_emp_shift_group_ads); 
fputcsv($f, $fields, $delimiter);

$fields = array('Total MP', $total_emp); 
fputcsv($f, $fields, $delimiter);

$fields = array('Support MP', $total_support_ads); 
fputcsv($f, $fields, $delimiter);

$fields = array('Percentage', $attendance_percentage_ads); 
fputcsv($f, $fields, $delimiter);

$fields = array(''); 
fputcsv($f, $fields, $delimiter);


// Set column headers 
$fields = array('#', 'Provider', 'Total', 'Present', 'Absent', 'Percentage'); 
fputcsv($f, $fields, $delimiter);

$c = 0;

$sql = "SELECT `provider` FROM `m_providers` ORDER BY id ASC";
$stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt -> execute();
if ($stmt -> rowCount() > 0) {
	foreach($stmt -> fetchAll() as $row) {
		$c++;
		
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
		  "shift" => $shift,
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

		$lineData = array($c, $row['provider'], $total, $total_present, $total_absent, $attendance_percentage); 
        fputcsv($f, $lineData, $delimiter);
	}
}


$total_mp_present = $total_present_ds + $total_present_ns + $total_present_ads;
// $total_sum = $total_emp_shift_group_a + $total_emp_shift_group_b + $total_emp_shift_group_ads;
// if ($total_sum != 0) {
// 	$attendance_percentage_total = round(($total_mp_present / $total_sum) * 100, 2);
// } else {
// 	$attendance_percentage_total = 0;
// }
if ($total_emp != 0) {
	$attendance_percentage_total = round(($total_mp_present / $total_emp) * 100, 2);
} else {
	$attendance_percentage_total = 0;
}

$fields = array(''); 
fputcsv($f, $fields, $delimiter);

$fields = array('Overall Percentage', $attendance_percentage_total); 
fputcsv($f, $fields, $delimiter);


// Move back to beginning of file 
fseek($f, 0); 

// Set headers to download file rather than displayed 
header('Content-Type: text/csv'); 
header('Content-Disposition: attachment; filename="' . $filename . '";'); 

//output all remaining data on a file pointer 
fpassthru($f); 

$conn = null;
?>