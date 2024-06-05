<?php 
session_name("emp_mgt");
session_start();

include '../../conn.php';

$method = $_POST['method'];

// Dashboard

function get_shift($server_time) {
	if ($server_time >= '06:00:00' && $server_time < '18:00:00') {
		return 'DS';
	} else if ($server_time >= '18:00:00' && $server_time <= '23:59:59') {
		return 'NS';
	} else if ($server_time >= '00:00:00' && $server_time < '06:00:00') {
		return 'NS';
	}
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

if ($method == 'count_emp_dashboard') {
	$day = $_POST['day'];
	$dept = $_POST['dept'];
	$section = addslashes($_POST['section']);
	$line_no = addslashes($_POST['line_no']);
	$shift = get_shift($server_time);

	$total = 0;
	$total_shift_group_a = 0;
	$total_shift_group_b = 0;
	$total_shift_group_ads = 0;

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
	$stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $j){
			$total = intval($j['total']);
		}

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
		$query = $query . " AND shift_group = 'A'";
		$stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			foreach($stmt->fetchALL() as $j){
				$total_shift_group_a = intval($j['total']);
			}
		}else{
			$total_shift_group_a = 0;
		}

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
		$query = $query . " AND shift_group = 'B'";
		$stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			foreach($stmt->fetchALL() as $j){
				$total_shift_group_b = intval($j['total']);
			}
		}else{
			$total_shift_group_b = 0;
		}

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
		$query = $query . " AND shift_group = 'ADS'";
		$stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			foreach($stmt->fetchALL() as $j){
				$total_shift_group_ads = intval($j['total']);
			}
		}else{
			$total_shift_group_ads = 0;
		}
	}else{
		$total = 0;
	}

	// $shift = 'DS';
	// if ($server_time >= '05:00:00' && $server_time <= '23:59:59') {
	// 	$day = $server_date_only;
	// } else if ($server_time >= '00:00:00' && $server_time < '05:00:00') {
	// 	$day = $server_date_only_yesterday;
	// }

	$search_arr1 = array(
	  "day" => $day,
	  "shift" => $shift,
	  "shift_group" => "A",
	  "dept" => $dept,
	  "section" => $section,
	  "line_no" => $line_no
	);

	$total_present_ds = count_emp_tio($search_arr1, $conn);
	$total_absent_ds = $total_shift_group_a - $total_present_ds;
	$total_support_ds = count_emp_lsh($search_arr1, $conn);
	if ($total_shift_group_a != 0) {
		$attendance_percentage_ds = round(($total_present_ds / $total_shift_group_a) * 100, 2);
	} else {
		$attendance_percentage_ds = 0;
	}

	// $shift = 'NS';
	// if ($server_time >= '17:00:00' && $server_time <= '23:59:59') {
	// 	$day = $server_date_only;
	// } else if ($server_time >= '00:00:00' && $server_time < '17:00:00') {
	// 	$day = $server_date_only_yesterday;
	// }

	$search_arr1 = array(
	  "day" => $day,
	  "shift" => $shift,
	  "shift_group" => "B",
	  "dept" => $dept,
	  "section" => $section,
	  "line_no" => $line_no
	);

	$total_present_ns = count_emp_tio($search_arr1, $conn);
	$total_absent_ns = $total_shift_group_b - $total_present_ns;
	$total_support_ns = count_emp_lsh($search_arr1, $conn);
	if ($total_shift_group_b != 0) {
		$attendance_percentage_ns = round(($total_present_ns / $total_shift_group_b) * 100, 2);
	} else {
		$attendance_percentage_ns = 0;
	}

	// $shift = 'DS';
	// if ($server_time >= '05:00:00' && $server_time <= '23:59:59') {
	// 	$day = $server_date_only;
	// } else if ($server_time >= '00:00:00' && $server_time < '05:00:00') {
	// 	$day = $server_date_only_yesterday;
	// }

	$search_arr1 = array(
	  "day" => $day,
	  "shift" => $shift,
	  "shift_group" => "ADS",
	  "dept" => $dept,
	  "section" => $section,
	  "line_no" => $line_no
	);

	$total_present_ads = count_emp_tio($search_arr1, $conn);
	$total_absent_ads = $total_shift_group_ads - $total_present_ads;
	$total_support_ads = count_emp_lsh($search_arr1, $conn);
	if ($total_shift_group_ads != 0) {
		$attendance_percentage_ads = round(($total_present_ads / $total_shift_group_ads) * 100, 2);
	} else {
		$attendance_percentage_ads = 0;
	}

	$total_present = $total_present_ds + $total_present_ns + $total_present_ads;
	// $total_sum = $total_shift_group_a + $total_shift_group_b + $total_shift_group_ads;
	// if ($total_sum != 0) {
	// 	$attendance_percentage_total = round(($total_present / $total_sum) * 100, 2);
	// } else {
	// 	$attendance_percentage_total = 0;
	// }
	if ($total != 0) {
		$attendance_percentage_total = round(($total_present / $total) * 100, 2);
	} else {
		$attendance_percentage_total = 0;
	}

	$response_arr = array(
		'total' => $total,
		'attendance_percentage_total' => $attendance_percentage_total,
		'total_shift_group_a' => $total_shift_group_a,
		'total_shift_group_b' => $total_shift_group_b,
		'total_shift_group_ads' => $total_shift_group_ads,
		'attendance_percentage_ds' => $attendance_percentage_ds,
		'attendance_percentage_ns' => $attendance_percentage_ns,
		'attendance_percentage_ads' => $attendance_percentage_ads,
		'total_present_ds' => $total_present_ds,
		'total_absent_ds' => $total_absent_ds,
		'total_support_ds' => $total_support_ds,
		'total_present_ns' => $total_present_ns,
		'total_absent_ns' => $total_absent_ns,
		'total_support_ns' => $total_support_ns,
		'total_present_ads' => $total_present_ads,
		'total_absent_ads' => $total_absent_ads,
		'total_support_ads' => $total_support_ads
	);

	//header('Content-Type: application/json; charset=utf-8');
    echo json_encode($response_arr, JSON_FORCE_OBJECT);
}

// Get Count Employee By Provider Small Boxes
if ($method == 'count_emp_provider_dashboard') {
	$day = $_POST['day'];
	$dept = $_POST['dept'];
	$section = addslashes($_POST['section']);
	$line_no = addslashes($_POST['line_no']);
	$shift = get_shift($server_time);
	$shift_group = $_POST['shift_group'];
	$small_box_colors_arr = array('bg-primary', 'bg-navy', 'bg-info', 'bg-warning', 'bg-lightblue', 'bg-purple', 'bg-olive', 'bg-gray');
	$small_box_color_count = count($small_box_colors_arr);
	$provider_count = 0;
	$sql = "SELECT `provider` FROM `m_providers` ORDER BY id ASC";
	$stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt -> execute();
	if ($stmt -> rowCount() > 0) {
		foreach($stmt -> fetchAll() as $row) {
			if ($small_box_color_count == $provider_count) {
				$provider_count = 0;
			}

			$search_arr = array(
				"shift_group" => $shift_group,
				"dept" => $dept,
				"section" => $section,
				"line_no" => $line_no
			);

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

			echo '<div class="col-xl-3 col-lg-3 col-md-6 col-12">
			<div class="small-box '.$small_box_colors_arr[$provider_count].'">
			<div class="inner mb-3">
			
			<h4><b>'.htmlspecialchars($row['provider']).'</b></h4>
			<h4 class="mb-3">Employees</h4>
			<div class="bg-light p-2"><div class="row"><div class="col-md-6 col-sm-12"><h4 class="ml-2">Total: </h4><h2 class="ml-2"><b>'.$total.'</b></h2></div><div class="col-md-6 col-sm-12"><h4 class="ml-2">Percentage: </h4><h2 class="ml-2"><b>'.$attendance_percentage.'%</b></h2></div></div><h4 class="ml-2">Present: </h4><h2 class="text-success ml-2"><b>'.$total_present.'</b></h2><h4 class="ml-2">Absent: </h4><h2 class="text-danger ml-2"><b>'.$total_absent.'</b></h2></div>
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