<?php
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

include '../../conn.php';

$method = $_POST['method'];

function check_sa_submission_time($server_time)
{
	if ($server_time >= '06:00:00' && $server_time < '13:30:00') {
		return true;
	} else if ($server_time >= '18:00:00' && $server_time <= '23:59:59') {
		return true;
	} else if ($server_time >= '00:00:00' && $server_time < '01:30:00') {
		return true;
	} else {
		return false;
	}
}

// Shuttle Allocation

// Get Shuttle Route Dropdown
if ($method == 'fetch_shuttle_route_dropdown') {
	$sql = "SELECT shuttle_route FROM m_shuttle_routes ORDER BY shuttle_route ASC";

	$stmt = $conn->prepare($sql);
	$stmt->execute();

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($row) {
		echo '<option selected value="">Select Shuttle Route</option>';
		do {
			echo '<option value="' . htmlspecialchars($row['shuttle_route']) . '">' . htmlspecialchars($row['shuttle_route']) . '</option>';
		} while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
	} else {
		echo '<option disabled selected value="">Select Shuttle Route</option>';
	}
}

if ($method == 'get_shuttle_allocation_date_shift') {
	$response_arr = array();
	if ($server_time >= '12:00:00' && $server_time <= '23:59:59') {
		$response_arr = array(
			'date' => $server_date_only,
			'shift' => 'DS'
		);
	} else if ($server_time >= '00:00:00' && $server_time < '12:00:00') {
		$response_arr = array(
			'date' => $server_date_only_yesterday,
			'shift' => 'NS'
		);
	}
	//header('Content-Type: application/json; charset=utf-8');
	echo json_encode($response_arr, JSON_FORCE_OBJECT);
}

if ($method == 'get_shuttle_allocation') {
	$day = $_POST['day'];
	//$day = '2023-07-28';
	$shift_group = $_POST['shift_group'];
	//$shift = 'DS';
	$dept = $_SESSION['dept'];
	$section = $_SESSION['section'];
	$line_no = $_SESSION['line_no'];

	$c = 0;

	$sql = "DECLARE @Day DATE = ?;
			DECLARE @ShiftGroup VARCHAR(50) = ?;
			DECLARE @Dept VARCHAR(50) = ?;

			WITH TimeInOut AS (
				SELECT 
					id, emp_no, time_in, day, shift 
				FROM t_time_in_out 
				WHERE day = @Day
			),
			ShuttleAllocation AS (
				SELECT 
					id, emp_no, out_5, out_6, out_7, out_8, day, shift, shuttle_route 
				FROM t_shuttle_allocation 
				WHERE day = @Day
			)
			SELECT 
				emp.provider, 
				emp.emp_no, 
				emp.full_name, 
				emp.dept, 
				emp.section, 
				emp.line_no, 
				COALESCE(NULLIF(emp.shuttle_route, ''), 'No Shuttle Route') AS emp_shuttle_route, 
				tio.id AS tio_id, 
				tio.time_in, 
				tio.day AS time_in_day, 
				tio.shift AS time_in_shift, 
				sa.id AS sa_id, 
				sa.out_5, 
				sa.out_6, 
				sa.out_7, 
				sa.out_8, 
				sa.day AS sa_day, 
				sa.shift AS sa_shift, 
				COALESCE(NULLIF(sa.shuttle_route, ''), 'No Shuttle Route') AS sa_shuttle_route 
			FROM m_employees emp
			LEFT JOIN TimeInOut tio ON tio.emp_no = emp.emp_no
			LEFT JOIN ShuttleAllocation sa ON sa.emp_no = emp.emp_no
			WHERE emp.shift_group = @ShiftGroup 
			AND emp.dept = @Dept";

	$params = [
		$day,
		$shift_group,
		$dept
	];

	if (!empty($section)) {
		$sql = $sql . " AND emp.section = ?";
		$params[] = $section;
	}
	if (!empty($line_no)) {
		$sql = $sql . " AND emp.line_no = ?";
		$params[] = $line_no;
	}
	$sql = $sql . " AND tio.time_in != '' ORDER BY emp.emp_no ASC";

	$stmt = $conn->prepare($sql);
	$stmt->execute($params);

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$c++;

		echo '<tr>';

		echo '<td><p class="mb-0"><label class="mb-0">
				<input type="checkbox" class="singleCheck" value="' . $row['tio_id'] . '" onclick="get_checked_length_present()" /><span></span>
				</label></p></td>';
		echo '<td>' . $c . '</td>';
		echo '<td>' . $row['provider'] . '</td>';
		echo '<td>' . $row['emp_no'] . '</td>';
		echo '<td>' . $row['full_name'] . '</td>';
		echo '<td>' . $row['dept'] . '</td>';
		echo '<td>' . $row['section'] . '</td>';
		echo '<td>' . $row['line_no'] . '</td>';
		if (empty($row['out_5']) && empty($row['out_6']) && empty($row['out_7']) && empty($row['out_8'])) {
			echo '<td>' . $row['emp_shuttle_route'] . '</td>';
		} else {
			echo '<td style="cursor:pointer;" class="modal-trigger" data-toggle="modal" data-target="#update_shuttle_route" 
						onclick="get_shuttle_allocation_details(&quot;' .
				$row['sa_id'] . '~!~' .
				$row['sa_shuttle_route'] . '&quot;)">' . $row['sa_shuttle_route'] . '</td>';
		}

		echo '<td>' . $row['out_5'] . '</td>';
		echo '<td>' . $row['out_6'] . '</td>';
		echo '<td>' . $row['out_7'] . '</td>';
		echo '<td>' . $row['out_8'] . '</td>';

		echo '</tr>';
	}
}

if ($method == 'get_shuttle_allocation_total') {
	$day = $_POST['day'];
	//$day = '2023-07-28';
	$shift_group = $_POST['shift_group'];
	//$shift = 'DS';
	$dept = $_SESSION['dept'];
	$section = $_SESSION['section'];
	$line_no = $_SESSION['line_no'];
	$response_arr = array();

	$sql = "SELECT 
				sum(out_5) AS total_out_5, 
				sum(out_6) AS total_out_6, 
				sum(out_7) AS total_out_7, 
				sum(out_8) AS total_out_8 
			FROM t_shuttle_allocation 
			WHERE day = ? AND 
				shift_group = ? AND 
				dept = ?";
	$params = [
		$day,
		$shift_group,
		$dept
	];

	if (!empty($section)) {
		$sql = $sql . " AND section = ?";
		$params[] = $section;
	}
	if (!empty($line_no)) {
		$sql = $sql . " AND line_no = ?";
		$params[] = $line_no;
	}

	$stmt = $conn->prepare($sql);
	$stmt->execute($params);

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$response_arr = array(
			'total_out_5' => $row['total_out_5'],
			'total_out_6' => $row['total_out_6'],
			'total_out_7' => $row['total_out_7'],
			'total_out_8' => $row['total_out_8']
		);
	}

	echo json_encode($response_arr, JSON_FORCE_OBJECT);
}

if ($method == 'set_out') {
	if (!check_sa_submission_time($server_time)) {
		echo 'Set Shuttle Allocation Time Range must follow! (6 AM/PM to 1:29:59 AM/PM)';
		$conn = null;
		exit();
	}

	$arr = [];
	$arr = $_POST['arr'];
	$time = $_POST['time'];

	$count = count($arr);
	foreach ($arr as $id) {
		$sql = "SELECT 
					tio.day, tio.shift, 
					emp.emp_no, emp.dept, emp.section, emp.line_no, emp.shift_group, 
					COALESCE(NULLIF(emp.shuttle_route, ''), 'No Shuttle Route') AS shuttle_route 
				FROM t_time_in_out tio
				LEFT JOIN m_employees emp
				ON emp.emp_no = tio.emp_no
				WHERE tio.id = ?";
		$stmt = $conn->prepare($sql);
		$params = array($id);
		$stmt->execute($params);

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($row) {
			$emp_no = $row['emp_no'];
			$dept = $row['dept'];
			$section = $row['section'];
			$line_no = $row['line_no'];
			$shift_group = $row['shift_group'];
			$day = $row['day'];
			$shift = $row['shift'];
			$shuttle_route = $row['shuttle_route'];
		}

		$sql = "SELECT id FROM t_shuttle_allocation 
				WHERE emp_no = ? AND 
					day = ? AND 
					shift_group = ? AND 
					(out_5 != 0 OR out_6 != 0 OR out_7 != 0 OR out_8 != 0)";

		$stmt = $conn->prepare($sql);
		$params = array($emp_no, $day, $shift_group);
		$stmt->execute($params);

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($row) {
			$sa_id = $row['id'];

			$sql = "UPDATE t_shuttle_allocation SET out_" . $time . " = 1";
			switch ($time) {
				case 5:
					$sql = $sql . ", out_6 = 0, out_7 = 0, out_8 = 0";
					break;
				case 6:
					$sql = $sql . ", out_5 = 0, out_7 = 0, out_8 = 0";
					break;
				case 7:
					$sql = $sql . ", out_5 = 0, out_6 = 0, out_8 = 0";
					break;
				case 8:
					$sql = $sql . ", out_5 = 0, out_6 = 0, out_7 = 0";
					break;
				default:
			}
			$sql = $sql . " WHERE id = ?";
			$stmt = $conn->prepare($sql);
			$params = array($sa_id);
			$stmt->execute($params);
		} else {
			$set_by = $_SESSION['full_name'];
			$sql = "INSERT INTO t_shuttle_allocation 
						(emp_no, dept, section, line_no, day, shift, shift_group, shuttle_route, out_" . $time . ", set_by) 
					VALUES 
						(?, ?, ?, ?, ?, ?, ?, ?, 1, ?)";
			$stmt = $conn->prepare($sql);
			$params = array($emp_no, $dept, $section, $line_no, $day, $shift, $shift_group, $shuttle_route, $set_by);
			$stmt->execute($params);
		}

		$count--;
	}

	if ($count == 0) {
		echo 'success';
	} else {
		echo 'error';
	}
}

if ($method == 'update_shuttle_route') {
	$id = $_POST['id'];
	$shuttle_route = trim($_POST['shuttle_route']);

	$sql = "UPDATE t_shuttle_allocation SET shuttle_route = ? WHERE id = ?";
	$params = array($shuttle_route, $id);
	$stmt = $conn->prepare($sql);

	if ($stmt->execute($params)) {
		echo 'success';
	} else {
		echo 'error';
	}
}

if ($method == 'get_shuttle_allocation_per_route') {
	$day = $_POST['day'];
	//$day = '2023-07-28';
	$shift_group = $_POST['shift_group'];
	//$shift = 'DS';
	$dept = $_SESSION['dept'];
	$section = $_SESSION['section'];
	$line_no = $_SESSION['line_no'];

	$sql = "SELECT 
				shuttle_route, 
				sum(out_5) as total_out_5, 
				sum(out_6) as total_out_6, 
				sum(out_7) as total_out_7, 
				sum(out_8) as total_out_8 
			FROM t_shuttle_allocation 
			WHERE day = ? AND 
				shift_group = ? AND 
				dept = ?";
	$params = [
		$day,
		$shift_group,
		$dept
	];

	if (!empty($section)) {
		$sql = $sql . " AND section = ?";
		$params[] = $section;
	}
	if (!empty($line_no)) {
		$sql = $sql . " AND line_no = ?";
		$params[] = $line_no;
	}
	$sql = $sql . " GROUP BY shuttle_route ORDER BY shuttle_route ASC";

	$stmt = $conn->prepare($sql);
	$stmt->execute($params);

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		echo '<tr>';

		echo '<td>' . $row['shuttle_route'] . '</td>';
		echo '<td>' . $row['total_out_5'] . '</td>';
		echo '<td>' . $row['total_out_6'] . '</td>';
		echo '<td>' . $row['total_out_7'] . '</td>';
		echo '<td>' . $row['total_out_8'] . '</td>';

		echo '</tr>';
	}
}

$conn = NULL;
