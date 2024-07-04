<?php 
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

include '../../conn.php';

$method = $_GET['method'];

// Home

if ($method == 'get_attendance_date') {
	// DS
	// if ($server_time >= '03:00:00' && $server_time <= '23:59:59') {
	// 	echo $server_date_only;
	// } else if ($server_time >= '00:00:00' && $server_time < '03:00:00') {
	// 	echo $server_date_only_yesterday;
	// }
	// NS
	// if ($server_time >= '15:00:00' && $server_time <= '23:59:59') {
	// 	echo $server_date_only;
	// } else if ($server_time >= '00:00:00' && $server_time < '15:00:00') {
	// 	echo $server_date_only_yesterday;
	// }
	$day_view_ds = '';
	$day_view_ns = '';
	$day_view_ads = '';

	if ($server_time >= '05:00:00' && $server_time <= '23:59:59') {
		$day_view_ds = $server_date_only;
		$day_view_ns = $server_date_only;
		$day_view_ads = $server_date_only;
	} else if ($server_time >= '00:00:00' && $server_time < '05:00:00') {
		$day_view_ds = $server_date_only_yesterday;
		$day_view_ns = $server_date_only_yesterday;
		$day_view_ads = $server_date_only_yesterday;
	}

	$response_arr = array(
		"day_view_ds" => $day_view_ds,
		"day_view_ns" => $day_view_ds,
		"day_view_ads" => $day_view_ds
	);

	echo json_encode($response_arr, JSON_FORCE_OBJECT);
}

if ($method == 'get_recent_time_in_out') {
	// REMOTE IP ADDRESS
	$ip = $_SERVER['REMOTE_ADDR'];

	$section = $_SESSION['section'];
	$line_no = $_SESSION['line_no'];
	$shift_group = $_GET['shift_group'];
	$c = 0;
	/*$sql = "SELECT tio.emp_no, emp.full_name, tio.time_in, tio.time_out, 
		HOUR(TIMEDIFF(DATE_FORMAT(tio.time_in, '%Y-%m-%d %H:%i'), DATE_FORMAT(tio.time_out, '%Y-%m-%d %H:%i'))) as hr_diff,
		MINUTE(TIMEDIFF(DATE_FORMAT(tio.time_in, '%Y-%m-%d %H:%i'), DATE_FORMAT(tio.time_out, '%Y-%m-%d %H:%i'))) as min_diff,
		HOUR(TIMEDIFF(DATE_FORMAT(tio.time_in, '%Y-%m-%d %H:%i'), DATE_FORMAT(tio.time_out, '%Y-%m-%d %H:%i'))) - 8 as hr_excess
		FROM t_time_in_out tio
		JOIN m_employees emp
		ON tio.emp_no = emp.emp_no
		WHERE emp.section = '$section' AND emp.line_no = '$line_no' AND tio.shift = '$shift'";*/

	// MySQL
	// $sql = "SELECT tio.emp_no, emp.full_name, tio.time_in, tio.time_out, 
	// 	HOUR(TIMEDIFF(DATE_FORMAT(tio.time_in, '%Y-%m-%d %H:%i'), DATE_FORMAT(tio.time_out, '%Y-%m-%d %H:%i'))) as hr_diff,
	// 	MINUTE(TIMEDIFF(DATE_FORMAT(tio.time_in, '%Y-%m-%d %H:%i'), DATE_FORMAT(tio.time_out, '%Y-%m-%d %H:%i'))) as min_diff,
	// 	HOUR(TIMEDIFF(DATE_FORMAT(tio.time_in, '%Y-%m-%d %H:%i'), DATE_FORMAT(tio.time_out, '%Y-%m-%d %H:%i'))) - 8 as hr_excess
	// 	FROM t_time_in_out tio
	// 	JOIN m_employees emp
	// 	ON tio.emp_no = emp.emp_no
	// 	WHERE";

	// MS SQL Server
	$sql = "SELECT tio.emp_no, emp.full_name, tio.time_in, tio.time_out, 
		(DATEDIFF(MINUTE, tio.time_in, tio.time_out) / 60) as hr_diff,
		(DATEDIFF(MINUTE, tio.time_in, tio.time_out) % 60) as min_diff,
		(DATEDIFF(MINUTE, tio.time_in, tio.time_out) / 60) - 8 as hr_excess
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
	$sql = $sql . " AND emp.shift_group = '$shift_group'";

	// Search by IP
	// if ($ip != '172.25.112.131') {
	// 	$sql = $sql . " AND tio.ip = '$ip'";
	// } else {
	// 	$sql = $sql . " AND tio.ip = '172.25.112.131'";
	// }

	// DS
	// if ($server_time >= '03:00:00' && $server_time <= '23:59:59') {
	// 	$sql = $sql . " AND tio.day = '$server_date_only'";
	// } else if ($server_time >= '00:00:00' && $server_time < '03:00:00') {
	// 	$sql = $sql . " AND tio.day = '$server_date_only_yesterday'";
	// }
	// NS
	// if ($server_time >= '15:00:00' && $server_time <= '23:59:59') {
	// 	$sql = $sql . " AND tio.day = '$server_date_only'";
	// } else if ($server_time >= '00:00:00' && $server_time < '15:00:00') {
	// 	$sql = $sql . " AND tio.day = '$server_date_only_yesterday'";
	// }
	if ($server_time >= '05:00:00' && $server_time <= '23:59:59') {
		$sql = $sql . " AND tio.day = '$server_date_only'";
	} else if ($server_time >= '00:00:00' && $server_time < '05:00:00') {
		$sql = $sql . " AND tio.day = '$server_date_only_yesterday'";
	}
	$sql = $sql . " ORDER BY tio.date_updated DESC";

	//Temporary
	//$sql = $sql . " LIMIT 0, 100";

	$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
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

if ($method == 'get_time_out_counting') {
	$day = $_GET['day'];
	$day_tomorrow = date('Y-m-d',(strtotime('+1 day',strtotime($day))));

	$c = 0;

	$results = array();

	$sql = "SELECT dept, section FROM m_employees GROUP BY dept, section";

	$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $row) {
			array_push($results, array('dept' => $row['dept'], 'section' => $row['section'], 'total_0' => 0, 'total_0_5' => 0, 'total_1' => 0, 'total_1_5' => 0, 'total_2' => 0, 'total_3' => 0));
		}
	}

	// Queries for Time Out Count

	// OUT 3
	$sql = "SELECT emp.dept, emp.section, count(tio.id) AS total_0 FROM t_time_in_out tio 
	LEFT JOIN m_employees emp
	ON tio.emp_no = emp.emp_no
	WHERE tio.day = '$day' AND (tio.time_out BETWEEN '$day 15:00:00' AND '$day 15:59:59') OR (tio.time_out BETWEEN '$day_tomorrow 03:00:00' AND '$day_tomorrow 03:59:59')
	OR tio.day = '$day' AND tio.time_out IS NULL
	GROUP BY emp.dept, emp.section";

	$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
			foreach ($results as &$result) {
				if ($result['dept'] == $row['dept'] && $result['section'] == $row['section']) {
					$result['total_0'] = intval($row['total_0']);
					break; // exit the loop once you've found and updated the process
				}
			}
			unset($result); // unset reference to last element
		}
	}

	// OUT 4
	$sql = "SELECT emp.dept, emp.section, count(tio.id) AS total_1 FROM t_time_in_out tio
	LEFT JOIN m_employees emp
	ON tio.emp_no = emp.emp_no
	WHERE tio.day = '$day' AND (tio.time_out BETWEEN '$day 16:00:00' AND '$day 16:59:59') OR (tio.time_out BETWEEN '$day_tomorrow 04:00:00' AND '$day_tomorrow 04:59:59')
	GROUP BY emp.dept, emp.section";

	$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
			foreach ($results as &$result) {
				if ($result['dept'] == $row['dept'] && $result['section'] == $row['section']) {
					$result['total_1'] = intval($row['total_1']);
					break; // exit the loop once you've found and updated the process
				}
			}
			unset($result); // unset reference to last element
		}
	}

	// OUT 5
	$sql = "SELECT emp.dept, emp.section, count(tio.id) AS total_2 FROM t_time_in_out tio 
	LEFT JOIN m_employees emp
	ON tio.emp_no = emp.emp_no
	WHERE tio.day = '$day' AND (tio.time_out BETWEEN '$day 17:00:00' AND '$day 17:59:59') OR (tio.time_out BETWEEN '$day_tomorrow 05:00:00' AND '$day_tomorrow 05:59:59')
	GROUP BY emp.dept, emp.section";

	$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
			foreach ($results as &$result) {
				if ($result['dept'] == $row['dept'] && $result['section'] == $row['section']) {
					$result['total_2'] = intval($row['total_2']);
					break; // exit the loop once you've found and updated the process
				}
			}
			unset($result); // unset reference to last element
		}
	}

	// OUT 6
	$sql = "SELECT emp.dept, emp.section, count(tio.id) AS total_3 FROM t_time_in_out tio 
	LEFT JOIN m_employees emp
	ON tio.emp_no = emp.emp_no
	WHERE tio.day = '$day' AND (tio.time_out BETWEEN '$day 18:00:00' AND '$day 18:59:59') OR (tio.time_out BETWEEN '$day_tomorrow 06:00:00' AND '$day_tomorrow 06:59:59')
	GROUP BY emp.dept, emp.section";

	$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
			foreach ($results as &$result) {
				if ($result['dept'] == $row['dept'] && $result['section'] == $row['section']) {
					$result['total_3'] = intval($row['total_3']);
					break; // exit the loop once you've found and updated the process
				}
			}
			unset($result); // unset reference to last element
		}
	}

	foreach ($results as &$result) {
		$c++;

		$total = $result['total_0'] + $result['total_1'] + $result['total_2'] + $result['total_3'];
	
		$average_ot = 0;
		if ($total > 0) {
			$average_ot = round((($result['total_0'] * 0) + ($result['total_1'] * 1) + ($result['total_2'] * 2) + ($result['total_3'] * 3)) / $total, 2);
		}

		echo '<tr>';

		echo '<td>'.$c.'</td>';
		echo '<td>'.$result['dept'].'</td>';
		echo '<td>'.$result['section'].'</td>';
		echo '<td>Manpower</td>';
		echo '<td>'.$result['total_0'].'</td>';
		echo '<td>0</td>';
		echo '<td>'.$result['total_1'].'</td>';
		echo '<td>0</td>';
		echo '<td>'.$result['total_2'].'</td>';
		echo '<td>'.$result['total_3'].'</td>';
		echo '<td>'.$total.'</td>';
		echo '<td>'.$average_ot.'</td>';
		
		echo '</tr>';
	} 
}

$conn = NULL;
?>