<?php 
session_name("emp_mgt");
session_start();

include '../../conn.php';

$method = $_POST['method'];

// Shuttle Allocation

// Get Shuttle Route Dropdown
if ($method == 'fetch_shuttle_route_dropdown') {
	$sql = "SELECT shuttle_route FROM m_shuttle_routes ORDER BY shuttle_route ASC";
	$stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt -> execute();
	if ($stmt -> rowCount() > 0) {
		echo '<option selected value="">Select Shuttle Route</option>';
		foreach($stmt -> fetchAll() as $row) {
			echo '<option value="'.htmlspecialchars($row['shuttle_route']).'">'.htmlspecialchars($row['shuttle_route']).'</option>';
		}
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

	$sql = "SELECT 
	emp.provider, emp.emp_no, emp.full_name, emp.dept, emp.section, emp.line_no, emp.shuttle_route AS emp_shuttle_route, 
	tio.id AS tio_id, tio.time_in, tio.day AS time_in_day, tio.shift AS time_in_shift, 
	sa.id AS sa_id, sa.out_5, sa.out_6, sa.out_7, sa.out_8, sa.day AS sa_day, sa.shift AS sa_shift, sa.shuttle_route AS sa_shuttle_route
		FROM m_employees emp
		LEFT JOIN 
		(SELECT id, emp_no, time_in, day, shift FROM t_time_in_out WHERE day = '$day') AS tio ON tio.emp_no = emp.emp_no
		LEFT JOIN 
		(SELECT id, emp_no, out_5, out_6, out_7, out_8, day, shift, shuttle_route FROM t_shuttle_allocation WHERE day = '$day') AS sa ON sa.emp_no = emp.emp_no
		WHERE emp.shift_group = '$shift_group' AND emp.dept = '$dept'";
	if (!empty($section)) {
		$sql = $sql . " AND emp.section = '$section'";
	}
	if (!empty($line_no)) {
		$sql = $sql . " AND emp.line_no = '$line_no'";
	}
	$sql = $sql . " AND tio.time_in != '' ORDER BY emp.emp_no ASC";
	$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $j){
			$c++;

			echo '<tr>';

			echo '<td><p class="mb-0"><label class="mb-0">
                  <input type="checkbox" class="singleCheck" value="'.$j['tio_id'].'" onclick="get_checked_length_present()" /><span></span>
                  </label></p></td>';
            echo '<td>'.$c.'</td>';
			echo '<td>'.$j['provider'].'</td>';
			echo '<td>'.$j['emp_no'].'</td>';
			echo '<td>'.$j['full_name'].'</td>';
			echo '<td>'.$j['dept'].'</td>';
			echo '<td>'.$j['section'].'</td>';
			echo '<td>'.$j['line_no'].'</td>';
			if (empty($j['out_5']) && empty($j['out_6']) && empty($j['out_7']) && empty($j['out_8'])) {
            	echo '<td>'.$j['emp_shuttle_route'].'</td>';
            } else {
            	echo '<td style="cursor:pointer;" class="modal-trigger" data-toggle="modal" data-target="#update_shuttle_route" onclick="get_shuttle_allocation_details(&quot;'.$j['sa_id'].'~!~'.$j['sa_shuttle_route'].'&quot;)">'.$j['sa_shuttle_route'].'</td>';
            }

			echo '<td>'.$j['out_5'].'</td>';
			echo '<td>'.$j['out_6'].'</td>';
			echo '<td>'.$j['out_7'].'</td>';
			echo '<td>'.$j['out_8'].'</td>';

			echo '</tr>';
		}
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

	$sql = "SELECT sum(out_5) AS total_out_5, sum(out_6) AS total_out_6, sum(out_7) AS total_out_7, sum(out_8) AS total_out_8 FROM t_shuttle_allocation WHERE day = '$day' AND shift_group = '$shift_group' AND dept = '$dept'";
	if (!empty($section)) {
		$sql = $sql . " AND section = '$section'";
	}
	if (!empty($line_no)) {
		$sql = $sql . " AND line_no = '$line_no'";
	}

	$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $j){
			$response_arr = array(
				'total_out_5' => $j['total_out_5'],
				'total_out_6' => $j['total_out_6'],
				'total_out_7' => $j['total_out_7'],
				'total_out_8' => $j['total_out_8']
			);
		}
	}

	echo json_encode($response_arr, JSON_FORCE_OBJECT);
}

if ($method == 'set_out') {
	$arr = [];
	$arr = $_POST['arr'];
	$time = $_POST['time'];

	$count = count($arr);
	foreach ($arr as $id) {
		$sql = "SELECT 
		tio.day, tio.shift, 
		emp.emp_no, emp.dept, emp.section, emp.line_no, emp.shift_group, emp.shuttle_route
			FROM t_time_in_out tio
			LEFT JOIN m_employees emp
			ON emp.emp_no = tio.emp_no
			WHERE tio.id = '$id'";
		$stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt -> execute();
		if ($stmt->rowCount() > 0) {
			foreach($stmt->fetchALL() as $j){
				$emp_no = $j['emp_no'];
				$dept = $j['dept'];
				$section = $j['section'];
				$line_no = $j['line_no'];
				$shift_group = $j['shift_group'];
				$day = $j['day'];
				$shift = $j['shift'];
				$shuttle_route = $j['shuttle_route'];
			}
		}

		$sql = "SELECT id FROM t_shuttle_allocation WHERE emp_no = '$emp_no' AND day = '$day' AND shift_group = '$shift_group' AND (out_5 != 0 OR out_6 != 0 OR out_7 != 0 OR out_8 != 0)";
		$stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt -> execute();
		if ($stmt->rowCount() > 0) {
			foreach($stmt->fetchALL() as $j){
				$sa_id = $j['id'];
			}

			$sql = "UPDATE t_shuttle_allocation SET out_".$time." = 1";
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
			$sql = $sql . " WHERE id = '$sa_id'";
			$stmt = $conn->prepare($sql);
			$stmt->execute();
		} else {
			$set_by = $_SESSION['full_name'];
			$sql = "INSERT INTO t_shuttle_allocation (emp_no, dept, section, line_no, day, shift, shift_group, shuttle_route, out_".$time.", set_by) VALUES ('$emp_no', '$dept', '$section', '$line_no', '$day', '$shift', '$shift_group', '$shuttle_route', 1, '$set_by')";
			$stmt = $conn->prepare($sql);
			$stmt->execute();
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

	$sql = "UPDATE t_shuttle_allocation SET shuttle_route = '$shuttle_route' WHERE id = '$id'";
	$stmt = $conn->prepare($sql);
	if ($stmt->execute()) {
		echo 'success';
	}else{
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

	$sql = "SELECT shuttle_route, sum(out_5) as total_out_5, sum(out_6) as total_out_6, sum(out_7) as total_out_7, sum(out_8) as total_out_8 FROM t_shuttle_allocation WHERE day = '$day' AND shift_group = '$shift_group' AND dept = '$dept'";
	if (!empty($section)) {
		$sql = $sql . " AND section = '$section'";
	}
	if (!empty($line_no)) {
		$sql = $sql . " AND line_no = '$line_no'";
	}
	$sql = $sql . " GROUP BY shuttle_route ORDER BY shuttle_route ASC";
	$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $j){

			echo '<tr>';

            echo '<td>'.$j['shuttle_route'].'</td>';
			echo '<td>'.$j['total_out_5'].'</td>';
			echo '<td>'.$j['total_out_6'].'</td>';
			echo '<td>'.$j['total_out_7'].'</td>';
			echo '<td>'.$j['total_out_8'].'</td>';

			echo '</tr>';
		}
	}
}

$conn = NULL;
?>