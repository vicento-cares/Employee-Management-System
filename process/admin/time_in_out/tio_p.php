<?php 
session_name("emp_mgt");
session_start();

include '../../conn.php';

$method = $_POST['method'];

// Home

if ($method == 'get_attendance_date_ds') {
	if ($server_time >= '03:00:00' && $server_time <= '23:59:59') {
		echo $server_date_only;
	} else if ($server_time >= '00:00:00' && $server_time < '03:00:00') {
		echo $server_date_only_yesterday;
	}
}

if ($method == 'get_attendance_date_ns') {
	if ($server_time >= '15:00:00' && $server_time <= '23:59:59') {
		echo $server_date_only;
	} else if ($server_time >= '00:00:00' && $server_time < '15:00:00') {
		echo $server_date_only_yesterday;
	}
}

if ($method == 'get_recent_time_in_out_ds') {
	// REMOTE IP ADDRESS
	$ip = $_SERVER['REMOTE_ADDR'];

	$section = $_SESSION['section'];
	$line_no = $_SESSION['line_no'];
	$shift = 'DS';
	$c = 0;
	/*$sql = "SELECT tio.emp_no, emp.full_name, tio.time_in, tio.time_out, 
		HOUR(TIMEDIFF(DATE_FORMAT(tio.time_in, '%Y-%m-%d %H:%i'), DATE_FORMAT(tio.time_out, '%Y-%m-%d %H:%i'))) as hr_diff,
		MINUTE(TIMEDIFF(DATE_FORMAT(tio.time_in, '%Y-%m-%d %H:%i'), DATE_FORMAT(tio.time_out, '%Y-%m-%d %H:%i'))) as min_diff,
		HOUR(TIMEDIFF(DATE_FORMAT(tio.time_in, '%Y-%m-%d %H:%i'), DATE_FORMAT(tio.time_out, '%Y-%m-%d %H:%i'))) - 8 as hr_excess
		FROM t_time_in_out tio
		JOIN m_employees emp
		ON tio.emp_no = emp.emp_no
		WHERE emp.section = '$section' AND emp.line_no = '$line_no' AND tio.shift = '$shift'";*/

	$sql = "SELECT tio.emp_no, emp.full_name, tio.time_in, tio.time_out, 
		HOUR(TIMEDIFF(DATE_FORMAT(tio.time_in, '%Y-%m-%d %H:%i'), DATE_FORMAT(tio.time_out, '%Y-%m-%d %H:%i'))) as hr_diff,
		MINUTE(TIMEDIFF(DATE_FORMAT(tio.time_in, '%Y-%m-%d %H:%i'), DATE_FORMAT(tio.time_out, '%Y-%m-%d %H:%i'))) as min_diff,
		HOUR(TIMEDIFF(DATE_FORMAT(tio.time_in, '%Y-%m-%d %H:%i'), DATE_FORMAT(tio.time_out, '%Y-%m-%d %H:%i'))) - 8 as hr_excess
		FROM t_time_in_out tio
		JOIN m_employees emp
		ON tio.emp_no = emp.emp_no
		WHERE";
	
	if (!empty($section)) {
		$sql = $sql . " emp.section = '$section'";
	} else {
		$sql = $sql . " emp.section IS NULL";
	}
	if (!empty($line_no)) {
		$sql = $sql . " AND emp.line_no = '$line_no'";
	} else {
		$sql = $sql . " AND emp.line_no IS NULL";
	}
	$sql = $sql . " AND tio.shift = '$shift'";

	// Search by IP
	if ($ip != '172.25.112.131') {
		$sql = $sql . " AND tio.ip = '$ip'";
	} else {
		$sql = $sql . " AND tio.ip = '172.25.112.131'";
	}

	if ($server_time >= '03:00:00' && $server_time <= '23:59:59') {
		$sql = $sql . " AND tio.day = '$server_date_only'";
	} else if ($server_time >= '00:00:00' && $server_time < '03:00:00') {
		$sql = $sql . " AND tio.day = '$server_date_only_yesterday'";
	}
	$sql = $sql . " ORDER BY tio.date_updated DESC";

	//Temporary
	//$sql = $sql . " LIMIT 0, 100";

	$stmt = $conn->prepare($sql);
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $j){
			$c++;
			$hr_diff = intval($j['hr_diff']);
			$min_diff = intval($j['min_diff']);
			$hr_excess = intval($j['hr_excess']);
			$diff = "";
			$excess = "";
			echo '<tr>';
				echo '<td>'.$c.'</td>';
				echo '<td>'.$j['emp_no'].'</td>';
				echo '<td>'.$j['full_name'].'</td>';
				echo '<td>'.$j['time_in'].'</td>';
				echo '<td>'.$j['time_out'].'</td>';
				// Time Diff
				if ($hr_diff > 1) {
					$diff = $hr_diff . " hrs";
				} else if ($hr_diff == 1) {
					$diff = $hr_diff . " hr";
				}
				if ($min_diff > 1) {
					$diff = $diff . " " .$min_diff. " mins";
				} else if ($min_diff == 1) {
					$diff = $diff . " " .$min_diff. " min";
				}
				echo '<td>'.$diff.'</td>';
				// Excess
				if ($hr_excess > 1) {
					$excess = $hr_excess . " hrs";
				} else if ($hr_excess == 1) {
					$excess = $hr_excess . " hr";
				}
				if ($hr_excess >= 8) {
					if ($min_diff > 1) {
						$excess = $excess . " " .$min_diff. " mins";
					} else if ($min_diff == 1) {
						$excess = $excess . " " .$min_diff. " min";
					}
				}
				echo '<td>'.$excess.'</td>';
			echo '</tr>';
		}
	}
}

if ($method == 'get_recent_time_in_out_ns') {
	// REMOTE IP ADDRESS
	$ip = $_SERVER['REMOTE_ADDR'];

	$section = $_SESSION['section'];
	$line_no = $_SESSION['line_no'];
	$shift = 'NS';
	$c = 0;
	/*$sql = "SELECT tio.emp_no, emp.full_name, tio.time_in, tio.time_out, 
		HOUR(TIMEDIFF(DATE_FORMAT(tio.time_in, '%Y-%m-%d %H:%i'), DATE_FORMAT(tio.time_out, '%Y-%m-%d %H:%i'))) as hr_diff,
		MINUTE(TIMEDIFF(DATE_FORMAT(tio.time_in, '%Y-%m-%d %H:%i'), DATE_FORMAT(tio.time_out, '%Y-%m-%d %H:%i'))) as min_diff,
		HOUR(TIMEDIFF(DATE_FORMAT(tio.time_in, '%Y-%m-%d %H:%i'), DATE_FORMAT(tio.time_out, '%Y-%m-%d %H:%i'))) - 8 as hr_excess
		FROM t_time_in_out tio
		JOIN m_employees emp
		ON tio.emp_no = emp.emp_no
		WHERE emp.section = '$section' AND emp.line_no = '$line_no' AND tio.shift = '$shift'";*/

	$sql = "SELECT tio.emp_no, emp.full_name, tio.time_in, tio.time_out, 
		HOUR(TIMEDIFF(DATE_FORMAT(tio.time_in, '%Y-%m-%d %H:%i'), DATE_FORMAT(tio.time_out, '%Y-%m-%d %H:%i'))) as hr_diff,
		MINUTE(TIMEDIFF(DATE_FORMAT(tio.time_in, '%Y-%m-%d %H:%i'), DATE_FORMAT(tio.time_out, '%Y-%m-%d %H:%i'))) as min_diff,
		HOUR(TIMEDIFF(DATE_FORMAT(tio.time_in, '%Y-%m-%d %H:%i'), DATE_FORMAT(tio.time_out, '%Y-%m-%d %H:%i'))) - 8 as hr_excess
		FROM t_time_in_out tio
		JOIN m_employees emp
		ON tio.emp_no = emp.emp_no
		WHERE";

	if (!empty($section)) {
		$sql = $sql . " emp.section = '$section'";
	} else {
		$sql = $sql . " emp.section IS NULL";
	}
	if (!empty($line_no)) {
		$sql = $sql . " AND emp.line_no = '$line_no'";
	} else {
		$sql = $sql . " AND emp.line_no IS NULL";
	}
	$sql = $sql . " AND tio.shift = '$shift'";

	// Search by IP
	if ($ip != '172.25.112.131') {
		$sql = $sql . " AND tio.ip = '$ip'";
	} else {
		$sql = $sql . " AND tio.ip = '172.25.112.131'";
	}

	if ($server_time >= '15:00:00' && $server_time <= '23:59:59') {
		$sql = $sql . " AND tio.day = '$server_date_only'";
	} else if ($server_time >= '00:00:00' && $server_time < '15:00:00') {
		$sql = $sql . " AND tio.day = '$server_date_only_yesterday'";
	}
	$sql = $sql . " ORDER BY tio.date_updated DESC";

	//Temporary
	//$sql = $sql . " LIMIT 0, 100";

	$stmt = $conn->prepare($sql);
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $j){
			$c++;
			$hr_diff = intval($j['hr_diff']);
			$min_diff = intval($j['min_diff']);
			$hr_excess = intval($j['hr_excess']);
			$diff = "";
			$excess = "";
			echo '<tr>';
				echo '<td>'.$c.'</td>';
				echo '<td>'.$j['emp_no'].'</td>';
				echo '<td>'.$j['full_name'].'</td>';
				echo '<td>'.$j['time_in'].'</td>';
				echo '<td>'.$j['time_out'].'</td>';
				// Time Diff
				if ($hr_diff > 1) {
					$diff = $hr_diff . " hrs";
				} else if ($hr_diff == 1) {
					$diff = $hr_diff . " hr";
				}
				if ($min_diff > 1) {
					$diff = $diff . " " .$min_diff. " mins";
				} else if ($min_diff == 1) {
					$diff = $diff . " " .$min_diff. " min";
				}
				echo '<td>'.$diff.'</td>';
				// Excess
				if ($hr_excess > 1) {
					$excess = $hr_excess . " hrs";
				} else if ($hr_excess == 1) {
					$excess = $hr_excess . " hr";
				}
				if ($hr_excess >= 8) {
					if ($min_diff > 1) {
						$excess = $excess . " " .$min_diff. " mins";
					} else if ($min_diff == 1) {
						$excess = $excess . " " .$min_diff. " min";
					}
				}
				echo '<td>'.$excess.'</td>';
			echo '</tr>';
		}
	}
}

$conn = NULL;
?>