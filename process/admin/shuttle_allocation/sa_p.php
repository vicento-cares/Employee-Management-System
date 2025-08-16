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

function get_shift($server_time)
{
	if ($server_time >= '06:00:00' && $server_time < '18:00:00') {
		return 'DS';
	} else if ($server_time >= '18:00:00' && $server_time <= '23:59:59') {
		return 'NS';
	} else if ($server_time >= '00:00:00' && $server_time < '06:00:00') {
		return 'NS';
	}
}

// Shuttle Allocation

if ($method == 'get_shuttle_allocation_per_sr') {
	$day = $server_date_only;
	//$day = '2023-07-28';
	$shift = get_shift($server_time);
	$section = $_POST['section'];

	$sql = "WITH ShuttleAllocationSummary AS (
			SELECT 
				section, 
				shuttle_route, 
				sum(out_5) AS total_out_5, 
				sum(out_6) AS total_out_6, 
				sum(out_7) AS total_out_7, 
				sum(out_8) AS total_out_8,
				0 AS table_order  
			FROM t_shuttle_allocation 
			WHERE day = ? AND 
				shift = ?";
	$params = [
		$day,
		$shift
	];

	if (!empty($section)) {
		$sql = $sql . " AND section = ?";
		$params[] = $section;
	}

	$sql = $sql . " GROUP BY section, shuttle_route 
					)
	
					SELECT * FROM ShuttleAllocationSummary 
					
					UNION ALL 
					
					SELECT 
						'Total MP:' AS section, 
						NULL AS shuttle_route, 
						SUM(total_out_5), 
						SUM(total_out_6), 
						SUM(total_out_7), 
						SUM(total_out_8), 
						1 AS table_order 
					FROM 
						ShuttleAllocationSummary
					ORDER BY 
						table_order ASC, section ASC, shuttle_route ASC";

	$stmt = $conn->prepare($sql);
	$stmt->execute($params);

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$row_class = "";
		$row_style = "";
		$total_class = "";

		if ($row['section'] == 'Total MP:') {
			$row_class = "bg-black";
			$row_style = " style='text-align: center; position: sticky; bottom: 0'";
			$total_class = " class='text-bold'";
		}

		echo '<tr class="'.$row_class.'"'.$row_style.'>';

		echo '<td'.$total_class.'>' . $row['section'] . '</td>';
		echo '<td>' . $row['shuttle_route'] . '</td>';
		echo '<td'.$total_class.'>' . $row['total_out_5'] . '</td>';
		echo '<td'.$total_class.'>' . $row['total_out_6'] . '</td>';
		echo '<td'.$total_class.'>' . $row['total_out_7'] . '</td>';
		echo '<td'.$total_class.'>' . $row['total_out_8'] . '</td>';

		echo '</tr>';
	}
}

if ($method == 'get_shuttle_allocation_per_section') {
	$day = $server_date_only;
	//$day = '2023-07-28';
	$shift = get_shift($server_time);
	$section = $_POST['section'];

	$sql = "WITH ShuttleAllocationSummary AS (
			SELECT 
				section, 
				sum(out_5) + sum(out_6) + sum(out_7) + sum(out_8) AS total_out, 
				0 AS table_order  
			FROM t_shuttle_allocation 
			WHERE day = ? AND 
				shift = ?";
	$params = [
		$day,
		$shift
	];

	if (!empty($section)) {
		$sql = $sql . " AND section = ?";
		$params[] = $section;
	}

	$sql = $sql . " GROUP BY section 
					)
	
					SELECT * FROM ShuttleAllocationSummary 
					
					UNION ALL 
					
					SELECT 
						'Total MP:' AS section, 
						SUM(total_out), 
						1 AS table_order 
					FROM 
						ShuttleAllocationSummary
					ORDER BY 
						table_order ASC, section ASC";

	$stmt = $conn->prepare($sql);
	$stmt->execute($params);

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$row_class = "";
		$row_style = "";
		$total_class = "";

		if ($row['section'] == 'Total MP:') {
			$row_class = "bg-black";
			$row_style = " style='text-align: center; position: sticky; bottom: 0'";
			$total_class = " class='text-bold'";
		}

		echo '<tr class="'.$row_class.'"'.$row_style.'>';

		echo '<td'.$total_class.'>' . $row['section'] . '</td>';
		echo '<td'.$total_class.'>' . $row['total_out'] . '</td>';

		echo '</tr>';
	}
}

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
	if ($server_time >= '06:00:00' && $server_time < '18:00:00') {
		$response_arr = array(
			'date' => $server_date_only,
			'shift' => 'DS'
		);
	} else if ($server_time >= '18:00:00' && $server_time <= '23:59:59') {
		$response_arr = array(
			'date' => $server_date_only,
			'shift' => 'NS'
		);
	} else if ($server_time >= '00:00:00' && $server_time < '06:00:00') {
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
	$emp_no = "";

	if (isset($_POST['dept'])) {
		$dept = $_POST['dept'];
	}

	if (isset($_POST['section'])) {
		$section = $_POST['section'];
	}

	if (isset($_POST['line_no'])) {
		$line_no = $_POST['line_no'];
	}

	if (isset($_POST['emp_no'])) {
		$emp_no = $_POST['emp_no'];
	}

	$c = 0;
	$row_class_arr = array('', 'bg-success', 'bg-info', 'bg-danger', 'bg-purple');
	$row_class = $row_class_arr[0];

	$sql = "DECLARE @Day DATE = ?;

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
			),
			ShuttleAllocationSummary AS (
				SELECT 
					NULL AS c,
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
					sa.day AS sa_day, 
					sa.shift AS sa_shift, 
					COALESCE(NULLIF(sa.shuttle_route, ''), 'No Shuttle Route') AS sa_shuttle_route, 
					sa.out_5, 
					sa.out_6, 
					sa.out_7, 
					sa.out_8, 
					0 AS table_order 
				FROM m_employees emp
				LEFT JOIN TimeInOut tio ON tio.emp_no = emp.emp_no
				LEFT JOIN ShuttleAllocation sa ON sa.emp_no = emp.emp_no
				WHERE emp.dept != ''";

	$params[] = $day;

	if (!empty($emp_no)) {
		$sql = $sql . " AND emp.emp_no LIKE ?";
		$emp_no_params = $emp_no . '%';
		$params[] = $emp_no_params;
	}

	if (!empty($shift_group)) {
		$sql = $sql . " AND emp.shift_group = ?";
		$params[] = $shift_group;
	}

	if (!empty($dept)) {
		$sql = $sql . " AND emp.dept = ?";
		$params[] = $dept;
	}
	if (!empty($section)) {
		$sql = $sql . " AND emp.section = ?";
		$params[] = $section;
	}
	if (!empty($line_no)) {
		$sql = $sql . " AND emp.line_no = ?";
		$params[] = $line_no;
	}
	$sql = $sql . " AND tio.time_in != '' 
				)
				
				SELECT * FROM ShuttleAllocationSummary
				
				UNION ALL

				SELECT 
					'Total MP:' AS c, 
					NULL AS provider, 
					NULL AS emp_no, 
					NULL AS full_name, 
					NULL AS dept, 
					NULL AS section, 
					NULL AS line_no, 
					NULL AS emp_shuttle_route, 
					NULL AS tio_id, 
					NULL AS time_in, 
					NULL AS time_in_day, 
					NULL AS time_in_shift, 
					NULL AS sa_id, 
					NULL AS sa_day, 
					NULL AS sa_shift, 
					NULL AS sa_shuttle_route, 
					SUM(out_5) AS out_5, 
					SUM(out_6) AS out_6, 
					SUM(out_7) AS out_7, 
					SUM(out_8) AS out_8, 
					1 AS table_order
				FROM 
					ShuttleAllocationSummary
				ORDER BY 
					table_order ASC, emp_no ASC";

	$stmt = $conn->prepare($sql);
	$stmt->execute($params);

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$row_style = "";
		$total_class = "";

		if ($row['c'] != 'Total MP:') {
			$c++;

			if (intval($row['out_5']) == 1) {
				$row_class = $row_class_arr[1];
			} else if (intval($row['out_6']) == 1) {
				$row_class = $row_class_arr[2];
			} else if (intval($row['out_7']) == 1) {
				$row_class = $row_class_arr[3];
			} else if (intval($row['out_8']) == 1) {
				$row_class = $row_class_arr[4];
			} else {
				$row_class = $row_class_arr[0];
			}

			echo '<tr class="'.$row_class.'">';

			echo '<td><p class="mb-0"><label class="mb-0">
				<input type="checkbox" class="singleCheck" value="' . $row['tio_id'] . '" onclick="get_checked_length_present()" /><span></span>
				</label></p></td>';
		} else {
			$c = $row['c'];
			$row_style = " style='background-color: black; color: white; text-align: center; position: sticky; bottom: 0'";
			$total_class = " class='text-bold'";

			echo '<tr'.$row_style.'>';

			echo '<td><p class="mb-0"></p></td>';
		}

		echo '<td'.$total_class.'>' . $c . '</td>';

		echo '<td>' . $row['provider'] . '</td>';
		echo '<td>' . $row['emp_no'] . '</td>';
		echo '<td>' . $row['full_name'] . '</td>';
		echo '<td>' . $row['dept'] . '</td>';
		echo '<td>' . $row['section'] . '</td>';
		echo '<td>' . $row['line_no'] . '</td>';

		if (empty($row['out_5']) && empty($row['out_6']) && empty($row['out_7']) && empty($row['out_8'])) {
			echo '<td>' . $row['emp_shuttle_route'] . '</td>';
		} else if ($row['c'] == 'Total MP:') {
			echo '<td></td>';
		} else {
			echo '<td style="cursor:pointer;" class="modal-trigger" data-toggle="modal" data-target="#update_shuttle_route" 
						onclick="get_shuttle_allocation_details(&quot;' .
				$row['sa_id'] . '~!~' .
				$row['sa_shuttle_route'] . '&quot;)">' . $row['sa_shuttle_route'] . '</td>';
		}

		echo '<td'.$total_class.'>' . $row['out_5'] . '</td>';
		echo '<td'.$total_class.'>' . $row['out_6'] . '</td>';
		echo '<td'.$total_class.'>' . $row['out_7'] . '</td>';
		echo '<td'.$total_class.'>' . $row['out_8'] . '</td>';

		echo '</tr>';
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
	$emp_no = "";

	if (isset($_POST['dept'])) {
		$dept = $_POST['dept'];
	}

	if (isset($_POST['section'])) {
		$section = $_POST['section'];
	}

	if (isset($_POST['line_no'])) {
		$line_no = $_POST['line_no'];
	}

	if (isset($_POST['emp_no'])) {
		$emp_no = $_POST['emp_no'];
	}

	$sql = "WITH ShuttleAllocationSummary AS (
			SELECT 
				shuttle_route, 
				sum(out_5) AS total_out_5, 
				sum(out_6) AS total_out_6, 
				sum(out_7) AS total_out_7, 
				sum(out_8) AS total_out_8,
				0 AS table_order  
			FROM t_shuttle_allocation 
			WHERE day = ?";
	$params[] = $day;

	if (!empty($emp_no)) {
		$sql = $sql . " AND emp_no LIKE ?";
		$emp_no_params = $emp_no . '%';
		$params[] = $emp_no_params;
	}

	if (!empty($shift_group)) {
		$sql = $sql . " AND shift_group = ?";
		$params[] = $shift_group;
	}

	if (!empty($dept)) {
		$sql = $sql . " AND dept = ?";
		$params[] = $dept;
	}
	if (!empty($section)) {
		$sql = $sql . " AND section = ?";
		$params[] = $section;
	}
	if (!empty($line_no)) {
		$sql = $sql . " AND line_no = ?";
		$params[] = $line_no;
	}

	$sql = $sql . " GROUP BY shuttle_route 
					)
	
					SELECT * FROM ShuttleAllocationSummary 
					
					UNION ALL 
					
					SELECT 
						'Total MP:' AS shuttle_route, 
						SUM(total_out_5), 
						SUM(total_out_6), 
						SUM(total_out_7), 
						SUM(total_out_8), 
						1 AS table_order 
					FROM 
						ShuttleAllocationSummary
					ORDER BY 
						table_order ASC, shuttle_route ASC";

	$stmt = $conn->prepare($sql);
	$stmt->execute($params);

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$row_class = "";
		$row_style = "";
		$total_class = "";

		if ($row['shuttle_route'] == 'Total MP:') {
			$row_class = "bg-black";
			$row_style = " style='text-align: center; position: sticky; bottom: 0'";
			$total_class = " class='text-bold'";
		}

		echo '<tr class="'.$row_class.'"'.$row_style.'>';

		echo '<td'.$total_class.'>' . $row['shuttle_route'] . '</td>';
		echo '<td'.$total_class.'>' . $row['total_out_5'] . '</td>';
		echo '<td'.$total_class.'>' . $row['total_out_6'] . '</td>';
		echo '<td'.$total_class.'>' . $row['total_out_7'] . '</td>';
		echo '<td'.$total_class.'>' . $row['total_out_8'] . '</td>';

		echo '</tr>';
	}
}

if ($method == 'set_out') {
	// Check GA Account Session
	if (!isset($_SESSION['emp_no_ga'])) {
		// Check Submission Time Range
		if (!check_sa_submission_time($server_time)) {
			echo 'Set Shuttle Allocation Time Range must follow! (6 AM/PM to 1:29:59 AM/PM)';
			$conn = null;
			exit();
		}
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


if ($method == 'get_shuttle_allocation_history') {
	$day = $_POST['day'];
	//$day = '2023-07-28';
	$shift_group = $_POST['shift_group'];
	$shift = $_POST['shift'];
	//$shift = 'DS';
	$dept = $_SESSION['dept'];
	$section = $_SESSION['section'];
	$line_no = $_SESSION['line_no'];
	$emp_no = "";

	if (isset($_POST['dept'])) {
		$dept = $_POST['dept'];
	}

	if (isset($_POST['section'])) {
		$section = $_POST['section'];
	}

	if (isset($_POST['line_no'])) {
		$line_no = $_POST['line_no'];
	}

	if (isset($_POST['emp_no'])) {
		$emp_no = $_POST['emp_no'];
	}

	$row_class_arr = array('', 'bg-success', 'bg-info', 'bg-danger', 'bg-purple');
	$row_class = $row_class_arr[0];

	$sql = "DECLARE @Day DATE = ?;

			WITH TimeInOut AS (
				SELECT 
					id, emp_no, time_in, day, shift 
				FROM t_time_in_out 
				WHERE day = @Day
			),
			ShuttleAllocation AS (
				SELECT 
					id, emp_no, out_5, out_6, out_7, out_8, day, shift, shift_group, shuttle_route 
				FROM t_shuttle_allocation 
				WHERE day = @Day
			),
			ShuttleAllocationSummary AS (
				SELECT 
					CAST(sa.day AS nvarchar) AS sa_day, 
					emp.provider, 
					emp.emp_no, 
					emp.full_name, 
					emp.dept, 
					emp.section, 
					emp.line_no, 
					COALESCE(NULLIF(sa.shuttle_route, ''), 'No Shuttle Route') AS sa_shuttle_route, 
					sa.out_5, 
					sa.out_6, 
					sa.out_7, 
					sa.out_8, 
					0 AS table_order 
				FROM m_employees emp
				LEFT JOIN TimeInOut tio ON tio.emp_no = emp.emp_no
				LEFT JOIN ShuttleAllocation sa ON sa.emp_no = emp.emp_no
				WHERE emp.dept != ''";

	
	$params[] = $day;

	if (!empty($emp_no)) {
		$sql = $sql . " AND sa.emp_no LIKE ?";
		$emp_no_params = $emp_no . '%';
		$params[] = $emp_no_params;
	}

	if (!empty($shift_group)) {
		$sql = $sql . " AND sa.shift_group = ?";
		$params[] = $shift_group;
	}
	if (!empty($shift)) {
		$sql = $sql . " AND sa.shift = ?";
		$params[] = $shift;
	}

	if (!empty($dept)) {
		$sql = $sql . " AND emp.dept = ?";
		$params[] = $dept;
	}
	if (!empty($section)) {
		$sql = $sql . " AND emp.section = ?";
		$params[] = $section;
	}
	if (!empty($line_no)) {
		$sql = $sql . " AND emp.line_no = ?";
		$params[] = $line_no;
	}
	$sql = $sql . " AND tio.time_in != '' 
				)
				
				SELECT * FROM ShuttleAllocationSummary
				
				UNION ALL

				SELECT 
					'Total MP:' AS sa_day, 
					NULL AS provider, 
					NULL AS emp_no, 
					NULL AS full_name, 
					NULL AS dept, 
					NULL AS section, 
					NULL AS line_no, 
					NULL AS sa_shuttle_route, 
					SUM(out_5) AS out_5, 
					SUM(out_6) AS out_6, 
					SUM(out_7) AS out_7, 
					SUM(out_8) AS out_8, 
					1 AS table_order
				FROM 
					ShuttleAllocationSummary
				ORDER BY 
					table_order ASC, emp_no ASC";

	$stmt = $conn->prepare($sql);
	$stmt->execute($params);

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$row_style = "";
		$total_class = "";

		if ($row['sa_day'] == 'Total MP:') {
			$row_class = "bg-black";
			$row_style = " style='text-align: center; position: sticky; bottom: 0'";
			$total_class = " class='text-bold'";
		} else {
			if (intval($row['out_5']) == 1) {
				$row_class = $row_class_arr[1];
			} else if (intval($row['out_6']) == 1) {
				$row_class = $row_class_arr[2];
			} else if (intval($row['out_7']) == 1) {
				$row_class = $row_class_arr[3];
			} else if (intval($row['out_8']) == 1) {
				$row_class = $row_class_arr[4];
			} else {
				$row_class = $row_class_arr[0];
			}
		}

		echo '<tr class="'.$row_class.'"'.$row_style.'>';

		echo '<td'.$total_class.'>' . $row['sa_day'] . '</td>';
		echo '<td>' . $row['provider'] . '</td>';
		echo '<td>' . $row['emp_no'] . '</td>';
		echo '<td>' . $row['full_name'] . '</td>';
		echo '<td>' . $row['dept'] . '</td>';
		echo '<td>' . $row['section'] . '</td>';
		echo '<td>' . $row['line_no'] . '</td>';

		echo '<td>' . $row['sa_shuttle_route'] . '</td>';
		
		echo '<td'.$total_class.'>' . $row['out_5'] . '</td>';
		echo '<td'.$total_class.'>' . $row['out_6'] . '</td>';
		echo '<td'.$total_class.'>' . $row['out_7'] . '</td>';
		echo '<td'.$total_class.'>' . $row['out_8'] . '</td>';

		echo '</tr>';
	}
}

if ($method == 'get_shuttle_allocation_history_per_route') {
	$day = $_POST['day'];
	//$day = '2023-07-28';
	$shift_group = $_POST['shift_group'];
	$shift = $_POST['shift'];
	//$shift = 'DS';
	$dept = $_SESSION['dept'];
	$section = $_SESSION['section'];
	$line_no = $_SESSION['line_no'];
	$emp_no = "";

	if (isset($_POST['dept'])) {
		$dept = $_POST['dept'];
	}

	if (isset($_POST['section'])) {
		$section = $_POST['section'];
	}

	if (isset($_POST['line_no'])) {
		$line_no = $_POST['line_no'];
	}

	if (isset($_POST['emp_no'])) {
		$emp_no = $_POST['emp_no'];
	}

	$sql = "WITH ShuttleAllocationSummary AS (
			SELECT 
				shuttle_route, 
				sum(out_5) AS total_out_5, 
				sum(out_6) AS total_out_6, 
				sum(out_7) AS total_out_7, 
				sum(out_8) AS total_out_8,
				0 AS table_order  
			FROM t_shuttle_allocation 
			WHERE day = ?";
	$params[] = $day;

	if (!empty($emp_no)) {
		$sql = $sql . " AND emp_no LIKE ?";
		$emp_no_params = $emp_no . '%';
		$params[] = $emp_no_params;
	}

	if (!empty($shift_group)) {
		$sql = $sql . " AND shift_group = ?";
		$params[] = $shift_group;
	}
	if (!empty($shift)) {
		$sql = $sql . " AND shift = ?";
		$params[] = $shift;
	}

	if (!empty($dept)) {
		$sql = $sql . " AND dept = ?";
		$params[] = $dept;
	}
	if (!empty($section)) {
		$sql = $sql . " AND section = ?";
		$params[] = $section;
	}
	if (!empty($line_no)) {
		$sql = $sql . " AND line_no = ?";
		$params[] = $line_no;
	}

	$sql = $sql . " GROUP BY shuttle_route 
					)
	
					SELECT * FROM ShuttleAllocationSummary 
					
					UNION ALL 
					
					SELECT 
						'Total MP:' AS shuttle_route, 
						SUM(total_out_5), 
						SUM(total_out_6), 
						SUM(total_out_7), 
						SUM(total_out_8), 
						1 AS table_order 
					FROM 
						ShuttleAllocationSummary
					ORDER BY 
						table_order ASC, shuttle_route ASC";

	$stmt = $conn->prepare($sql);
	$stmt->execute($params);

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$row_class = "";
		$row_style = "";
		$total_class = "";

		if ($row['shuttle_route'] == 'Total MP:') {
			$row_class = "bg-black";
			$row_style = " style='text-align: center; position: sticky; bottom: 0'";
			$total_class = " class='text-bold'";
		}

		echo '<tr class="'.$row_class.'"'.$row_style.'>';

		echo '<td'.$total_class.'>' . $row['shuttle_route'] . '</td>';
		echo '<td'.$total_class.'>' . $row['total_out_5'] . '</td>';
		echo '<td'.$total_class.'>' . $row['total_out_6'] . '</td>';
		echo '<td'.$total_class.'>' . $row['total_out_7'] . '</td>';
		echo '<td'.$total_class.'>' . $row['total_out_8'] . '</td>';

		echo '</tr>';
	}
}

$conn = NULL;
