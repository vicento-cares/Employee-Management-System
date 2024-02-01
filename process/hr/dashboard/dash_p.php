<?php 
session_name("emp_mgt");
session_start();

include '../../conn.php';

$method = $_POST['method'];

// Dashboard

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
	$stmt = $conn->prepare($query);
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
			WHERE emp.provider = '$provider' AND emp.resigned = 0 AND tio.day = '".$search_arr['day']."' AND tio.shift = '".$search_arr['shift']."'";
	if (!empty($search_arr['dept'])) {
		$query = $query . " AND emp.dept = '".$search_arr['dept']."'";
	}
	if (!empty($search_arr['section'])) {
		$query = $query . " AND emp.section = '".$search_arr['section']."'";
	}
	if (!empty($search_arr['line_no'])) {
		$query = $query . " AND emp.line_no = '".$search_arr['line_no']."'";
	}
	$stmt = $conn->prepare($query);
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
			WHERE emp.resigned = 0 AND tio.day = '".$search_arr['day']."' AND tio.shift = '".$search_arr['shift']."'";
	if (!empty($search_arr['dept'])) {
		$query = $query . " AND emp.dept = '".$search_arr['dept']."'";
	}
	if (!empty($search_arr['section'])) {
		$query = $query . " AND emp.section = '".$search_arr['section']."'";
	}
	if (!empty($search_arr['line_no'])) {
		$query = $query . " AND emp.line_no = '".$search_arr['line_no']."'";
	}
	$stmt = $conn->prepare($query);
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

	$query = $query . " AND ls.status = 'accepted'";

	if (!empty($search_arr['dept'])) {
		$query = $query . " AND emp.dept = '".$search_arr['dept']."'";
	}

	if (!empty($search_arr['section'])) {
		$query = $query . " AND emp.section = '".$search_arr['section']."'";
	}

	$stmt = $conn->prepare($query);
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

if ($method == 'count_emp_dashboard') {
	$dept = $_POST['dept'];
	$section = addslashes($_POST['section']);
	$line_no = addslashes($_POST['line_no']);
	$shift = '';

	$total = 0;

	$query = "SELECT count(id) AS total FROM m_employees WHERE resigned = 0";
	if (!empty($dept)) {
		$query = $query . " AND dept = '$dept'";
	}
	if (!empty($section)) {
		$query = $query . " AND section LIKE '$section%'";
	}
	if (!empty($line_no)) {
		$query = $query . " AND line_no LIKE '$line_no%'";
	}
	$stmt = $conn->prepare($query);
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $j){
			$total = intval($j['total']);
		}
	}else{
		$total = 0;
	}

	$shift = 'DS';
	if ($server_time >= '05:00:00' && $server_time <= '23:59:59') {
		$day = $server_date_only;
	} else if ($server_time >= '00:00:00' && $server_time < '05:00:00') {
		$day = $server_date_only_yesterday;
	}

	$search_arr1 = array(
	  "day" => $day,
	  "shift" => $shift,
	  "dept" => $dept,
	  "section" => $section,
	  "line_no" => $line_no
	);

	$total_present_ds = count_emp_tio($search_arr1, $conn);
	$total_absent_ds = $total - $total_present_ds;
	$total_support_ds = count_emp_lsh($search_arr1, $conn);

	$shift = 'NS';
	if ($server_time >= '17:00:00' && $server_time <= '23:59:59') {
		$day = $server_date_only;
	} else if ($server_time >= '00:00:00' && $server_time < '17:00:00') {
		$day = $server_date_only_yesterday;
	}

	$search_arr1 = array(
	  "day" => $day,
	  "shift" => $shift,
	  "dept" => $dept,
	  "section" => $section,
	  "line_no" => $line_no
	);

	$total_present_ns = count_emp_tio($search_arr1, $conn);
	$total_absent_ns = $total - $total_present_ns;
	$total_support_ns = count_emp_lsh($search_arr1, $conn);

	$response_arr = array(
		'total' => $total,
		'total_present_ds' => $total_present_ds,
		'total_absent_ds' => $total_absent_ds,
		'total_support_ds' => $total_support_ds,
		'total_present_ns' => $total_present_ns,
		'total_absent_ns' => $total_absent_ns,
		'total_support_ns' => $total_support_ns
	);

	//header('Content-Type: application/json; charset=utf-8');
    echo json_encode($response_arr, JSON_FORCE_OBJECT);
}

// Get Count Employee By Provider Small Boxes
if ($method == 'count_emp_provider_dashboard') {
	$dept = $_POST['dept'];
	$section = addslashes($_POST['section']);
	$line_no = addslashes($_POST['line_no']);
	$shift = $_POST['shift'];
	$small_box_colors_arr = array('bg-primary', 'bg-navy', 'bg-info', 'bg-warning', 'bg-lightblue', 'bg-purple', 'bg-olive', 'bg-gray');
	$small_box_color_count = count($small_box_colors_arr);
	$provider_count = 0;
	$sql = "SELECT `provider` FROM `m_providers` ORDER BY id ASC";
	$stmt = $conn -> prepare($sql);
	$stmt -> execute();
	if ($stmt -> rowCount() > 0) {
		foreach($stmt -> fetchAll() as $row) {
			if ($small_box_color_count == $provider_count) {
				$provider_count = 0;
			}

			$day = '';

			$search_arr = array(
			  "dept" => $dept,
			  "section" => $section,
			  "line_no" => $line_no
			);

			$total = count_emp_by_provider($row['provider'], $search_arr, $conn);

			if ($shift == 'DS') {
				if ($server_time >= '05:00:00' && $server_time <= '23:59:59') {
					$day = $server_date_only;
				} else if ($server_time >= '00:00:00' && $server_time < '05:00:00') {
					$day = $server_date_only_yesterday;
				}
			} else if ($shift == 'NS') {
				if ($server_time >= '17:00:00' && $server_time <= '23:59:59') {
					$day = $server_date_only;
				} else if ($server_time >= '00:00:00' && $server_time < '17:00:00') {
					$day = $server_date_only_yesterday;
				}
			}

			$search_arr1 = array(
			  "day" => $day,
			  "shift" => $shift,
			  "dept" => $dept,
			  "section" => $section,
			  "line_no" => $line_no
			);

			$total_present = count_emp_by_provider_tio($row['provider'], $search_arr1, $conn);
			$total_absent = $total - $total_present;

			echo '<div class="col-xl-3 col-lg-3 col-md-6 col-12">
			<div class="small-box '.$small_box_colors_arr[$provider_count].'">
			<div class="inner mb-3">
			
			<h4><b>'.htmlspecialchars($row['provider']).'</b></h4>
			<h4 class="mb-3">Employees</h4>
			<div class="bg-light p-2"><h4 class="ml-2">Total: </h4><h2 class="ml-2"><b>'.$total.'</b></h2><h4 class="ml-2">Present: </h4><h2 class="text-success ml-2"><b>'.$total_present.'</b></h2><h4 class="ml-2">Absent: </h4><h2 class="text-danger ml-2"><b>'.$total_absent.'</b></h2></div>
			</div>
			<div class="icon">
			<i class="ion ion-person"></i>
			</div>
			<div class="small-box-footer"></div>
			</div>
			</div>';
			$provider_count++;
		}
	}
}

$conn = NULL;
?>