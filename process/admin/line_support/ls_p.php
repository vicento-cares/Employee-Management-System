<?php
session_name("emp_mgt");
session_start();

include '../../conn.php';

$method = $_POST['method'];

// Line Support

function update_notif_line_support($line_no, $status, $conn) {
	if ($status != 'Added' && $status != 'Saved') {
		$sql = "UPDATE t_notif_line_support nls
				LEFT JOIN m_accounts acc
				ON acc.emp_no = nls.emp_no ";
		if ($status == 'pending') {
			$sql = $sql . " SET nls.pending_ls = nls.pending_ls + 1";
		} else if ($status == 'accepted') {
			$sql = $sql . " SET nls.accepted_ls = nls.accepted_ls + 1";
		} else if ($status == 'rejected') {
			$sql = $sql . " SET nls.rejected_ls = nls.rejected_ls + 1";
		}
		if (!empty($line_no)) {
			$sql = $sql . " WHERE acc.line_no = '$line_no'";
		} else {
			$sql = $sql . " WHERE acc.line_no IS NULL OR acc.line_no = ''";
		}
		$stmt = $conn -> prepare($sql);
		$stmt -> execute();
	}
}

// Generate Line Support ID
function generate_line_support_id($line_support_id) {
	if ($line_support_id == "") {
		$line_support_id = date("ymdh");
		$rand = substr(md5(microtime()),rand(0,26),5);
		$line_support_id = 'LS:'.$line_support_id;
		$line_support_id = $line_support_id.''.$rand;
	}
	return $line_support_id;
}

function get_shift($server_time) {
	if ($server_time >= '06:00:00' && $server_time < '18:00:00') {
		return 'DS';
	} else if ($server_time >= '18:00:00' && $server_time <= '23:59:59') {
		return 'NS';
	} else if ($server_time >= '00:00:00' && $server_time < '06:00:00') {
		return 'NS';
	}
}

function get_day($server_time, $server_date_only, $server_date_only_yesterday) {
	if ($server_time >= '06:00:00' && $server_time <= '23:59:59') {
		return $server_date_only;
	} else if ($server_time >= '00:00:00' && $server_time < '06:00:00') {
		return $server_date_only_yesterday;
	}
}

// Get Line Datalist
if ($method == 'fetch_line_dropdown') {
	$sql = "SELECT line_no FROM m_access_locations WHERE line_no != '".$_SESSION['line_no']."' GROUP BY line_no ORDER BY line_no ASC";
	$stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt -> execute();
	if ($stmt -> rowCount() > 0) {
		echo '<option selected value="">Select Line No.</option>';
		foreach($stmt -> fetchAll() as $row) {
			echo '<option value="'.htmlspecialchars($row['line_no']).'">'.htmlspecialchars($row['line_no']).'</option>';
		}
	} else {
		echo '<option disabled selected value="">Select Line No.</option>';
	}
}

// Get Line Support Employee Dropdown
if ($method == 'get_line_support_employee') {
	$emp_no = $_POST['emp_no'];
	$line_no = $_SESSION['line_no'];
	$full_name = '';
	$time_out = '';
	$shift = get_shift($server_time);
	$day = get_day($server_time, $server_date_only, $server_date_only_yesterday);

	$sql = "SELECT emp.full_name, tio.time_out 
		FROM m_employees emp
		LEFT JOIN t_time_in_out tio
		ON tio.emp_no = emp.emp_no
		WHERE tio.emp_no = '$emp_no'
		AND tio.day = '$day'
		AND tio.shift = '$shift'";
	if (!empty($line_no)) {
		$sql = $sql . " AND emp.line_no = '$line_no'";
	} else {
		$sql = $sql . " AND (emp.line_no IS NULL OR emp.line_no = '')";
	}
	$stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt -> execute();
	if ($stmt -> rowCount() > 0) {
		foreach($stmt -> fetchAll() as $row) {
			$full_name = $row['full_name'];
			$time_out = $row['time_out'];
		}
		if (empty($time_out)) {
			$response_arr = array(
		        'full_name' => $full_name,
		        'message' => 'success'
		    );
			echo json_encode($response_arr, JSON_FORCE_OBJECT);
		} else {
			echo 'Already Time Out';
		}
	} else {
		echo 'No Time In';
	}
}

if ($method == 'set_line_support') {
	$line_support_id = generate_line_support_id($_POST['line_support_id']);
	$emp_no = $_POST['emp_no'];
	$full_name = $_POST['full_name'];
	$line_no_from = $_SESSION['line_no'];
	$line_no_to = $_POST['line_no'];
	$shift = get_shift($server_time);
	$day = get_day($server_time, $server_date_only, $server_date_only_yesterday);

	$sql = "SELECT id FROM t_line_support WHERE day = '$day' AND shift = '$shift' AND emp_no = '$emp_no' AND status = 'added'";
	$stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt -> execute();
	if ($stmt -> rowCount() < 1) {
		$sql = "SELECT id FROM t_line_support WHERE day = '$day' AND shift = '$shift' AND emp_no = '$emp_no' AND status = 'pending'";
		$stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt -> execute();
		if ($stmt -> rowCount() < 1) {
			$sql = "SELECT id FROM t_line_support_history WHERE day = '$day' AND shift = '$shift' AND emp_no = '$emp_no' AND status = 'accepted'";
			$stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$stmt -> execute();
			if ($stmt -> rowCount() < 1) {
				$sql = "SELECT id FROM t_time_in_out WHERE emp_no = '$emp_no' AND day = '$day' AND shift = '$shift' AND time_out IS NULL";
				$stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
				$stmt -> execute();
				if ($stmt -> rowCount() > 0) {
					$sql = "INSERT INTO t_line_support(line_support_id, emp_no, day, shift, line_no_from, line_no_to, set_by, set_by_no) VALUES ('$line_support_id','$emp_no','$day','$shift','$line_no_from','$line_no_to','".$_SESSION['full_name']."','".$_SESSION['emp_no']."')";
					$stmt = $conn -> prepare($sql);
					$stmt -> execute();

					$response_arr = array(
				        'line_support_id' => $line_support_id,
				        'message' => 'success'
				    );
					echo json_encode($response_arr, JSON_FORCE_OBJECT);
				} else {
					echo 'Already Time Out';
				}
			} else {
				echo 'Already Supported';
			}
		} else {
			echo 'Already Set';
		}
	} else {
		echo 'Duplicate';
	}
}

if ($method == 'get_added_line_support') {
	$line_support_id = $_POST['line_support_id'];
	$c = 0;
	$sql = "SELECT 
		ls.id, ls.line_support_id, ls.emp_no, emp.full_name, emp.dept, emp.process, ls.day, ls.shift, emp.shift_group, ls.line_no_to
		FROM t_line_support ls 
		LEFT JOIN m_employees emp
		ON ls.emp_no = emp.emp_no
		WHERE ls.line_support_id = '$line_support_id' 
		ORDER BY ls.id DESC";
	$stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt -> execute();
	if ($stmt -> rowCount() > 0) {
		foreach($stmt -> fetchAll() as $row) {
			$c++;
			echo '<tr id="added_'.$row['id'].'">';
			echo '<td>'.$c.'</td>';
			echo '<td>'.$row['emp_no'].'</td>';
			echo '<td>'.$row['full_name'].'</td>';
			echo '<td>'.$row['dept'].'</td>';
			echo '<td>'.$row['process'].'</td>';
			echo '<td>'.$row['day'].'</td>';
			echo '<td>'.$row['shift'].'</td>';
			echo '<td>'.$row['shift_group'].'</td>';
			echo '<td>'.$row['line_no_to'].'</td>';
			echo '<td><center><i class="fas fa-trash" style="cursor:pointer;" data-line_support_id="'.$row['line_support_id'].'" data-id="'.$row['id'].'" onclick="delete_single_added_line_support(this);"></i></center></td>';
			echo '</tr>';
		}
	}
}

if ($method == 'delete_single_added_line_support') {
	$id = $_POST['id'];
	$line_support_id = $_POST['line_support_id'];

	$sql = "DELETE FROM t_line_support WHERE line_support_id = '$line_support_id' AND id = '$id'";
	$stmt = $conn -> prepare($sql);
	$stmt -> execute();

	echo 'success';
}

if ($method == 'save_line_support') {
	$line_support_id = $_POST['line_support_id'];

	$sql = "UPDATE t_line_support SET status = 'pending' WHERE line_support_id = '$line_support_id'";
	$stmt = $conn -> prepare($sql);
	$stmt -> execute();

	$sql = "SELECT line_no_to FROM t_line_support WHERE line_support_id = '$line_support_id' ORDER BY id DESC";
	$stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt -> execute();
	if ($stmt -> rowCount() > 0) {
		foreach($stmt -> fetchAll() as $row) {
			update_notif_line_support($row['line_no_to'], 'pending', $conn);
		}
	}

	echo 'success';
}

if ($method == 'get_pending_line_support') {
	$line_no_to = $_SESSION['line_no'];
	$line_no_from = $_SESSION['line_no'];
	$pending_status = "";

	$c = 0;
	$row_class_arr = array('modal-trigger', 'modal-trigger bg-warning', 'modal-trigger bg-orange');
	$row_class = $row_class_arr[0];

	$sql = "SELECT 
		ls.id, ls.line_support_id, ls.emp_no, emp.full_name, emp.dept, emp.process, ls.day, ls.shift, emp.shift_group, ls.line_no_from, ls.line_no_to, ls.set_by, ls.set_by_no, ls.date_updated
		FROM t_line_support ls 
		LEFT JOIN m_employees emp
		ON ls.emp_no = emp.emp_no
		WHERE (ls.line_no_from = '$line_no_from' 
		OR ls.line_no_to = '$line_no_to') 
		AND ls.status = 'pending'
		ORDER BY ls.date_updated DESC";
	$stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt -> execute();
	if ($stmt -> rowCount() > 0) {
		foreach($stmt -> fetchAll() as $row) {
			$c++;
			if ($row['line_no_from'] == $line_no_from) {
				$row_class = $row_class_arr[2];
				$pending_status = "pending";
			} else if ($row['line_no_to'] == $line_no_to) {
				$row_class = $row_class_arr[1];
				$pending_status = "needacceptance";
			} else {
				$row_class = $row_class_arr[0];
			}
			echo '<tr style="cursor:pointer;" class="'.$row_class.'" data-toggle="modal" data-target="#pending_line_support" onclick="get_pending_line_support_details(&quot;'.$row['id'].'~!~'.$row['line_support_id'].'~!~'.$row['emp_no'].'~!~'.$row['full_name'].'~!~'.$row['dept'].'~!~'.$row['day'].'~!~'.$row['shift'].'~!~'.$row['line_no_from'].'~!~'.$row['line_no_to'].'~!~'.$row['set_by'].'~!~'.$row['set_by_no'].'~!~'.$pending_status.'~!~'.$row['process'].'~!~'.$row['shift_group'].'&quot;)">';
			echo '<td>'.$c.'</td>';
			echo '<td>'.$row['emp_no'].'</td>';
			echo '<td>'.$row['full_name'].'</td>';
			echo '<td>'.$row['dept'].'</td>';
			echo '<td>'.$row['process'].'</td>';
			echo '<td>'.$row['day'].'</td>';
			echo '<td>'.$row['shift'].'</td>';
			echo '<td>'.$row['shift_group'].'</td>';
			echo '<td>'.$row['line_no_from'].'</td>';
			echo '<td>'.$row['line_no_to'].'</td>';
			echo '<td>'.$row['set_by'].'</td>';
			echo '<td>'.$row['date_updated'].'</td>';
			echo '</tr>';
		}
	}
}

if ($method == 'reject_line_support') {
	$id = $_POST['id'];
	$line_support_id = '';
	$emp_no = '';
	$day = '';
	$shift = '';
	$line_no_from = '';
	$line_no_to = '';
	$set_by = '';
	$set_by_no = '';

	$sql = "SELECT line_support_id, emp_no, day, shift, line_no_from, line_no_to, set_by, set_by_no FROM t_line_support WHERE id = '$id'";
	$stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt -> execute();

	if ($stmt -> rowCount() > 0) {
		foreach($stmt -> fetchAll() as $row) {
			$line_support_id = $row['line_support_id'];
			$emp_no = $row['emp_no'];
			$day = $row['day'];
			$shift = $row['shift'];
			$line_no_from = $row['line_no_from'];
			$line_no_to = $row['line_no_to'];
			$set_by = $row['set_by'];
			$set_by_no = $row['set_by_no'];
		}
	}

	$sql = "INSERT INTO t_line_support_history(line_support_id, emp_no, day, shift, line_no_from, line_no_to, set_by, set_by_no, set_status_by, set_status_by_no, status) VALUES ('$line_support_id','$emp_no','$day','$shift','$line_no_from','$line_no_to','$set_by','$set_by_no','".$_SESSION['full_name']."','".$_SESSION['emp_no']."','rejected')";
	$stmt = $conn -> prepare($sql);
	$stmt -> execute();

	$sql = "DELETE FROM t_line_support WHERE id = '$id'";
	$stmt = $conn -> prepare($sql);
	$stmt -> execute();

	update_notif_line_support($line_no_from, 'rejected', $conn);

	echo 'success';
}

if ($method == 'accept_line_support') {
	$id = $_POST['id'];
	$line_support_id = '';
	$emp_no = '';
	$day = '';
	$shift = '';
	$line_no_from = '';
	$line_no_to = '';
	$set_by = '';
	$set_by_no = '';

	$sql = "SELECT line_support_id, emp_no, day, shift, line_no_from, line_no_to, set_by, set_by_no FROM t_line_support WHERE id = '$id'";
	$stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt -> execute();

	if ($stmt -> rowCount() > 0) {
		foreach($stmt -> fetchAll() as $row) {
			$line_support_id = $row['line_support_id'];
			$emp_no = $row['emp_no'];
			$day = $row['day'];
			$shift = $row['shift'];
			$line_no_from = $row['line_no_from'];
			$line_no_to = $row['line_no_to'];
			$set_by = $row['set_by'];
			$set_by_no = $row['set_by_no'];
		}
	}

	$latest_shift = get_shift($server_time);
	$latest_day = get_day($server_time, $server_date_only, $server_date_only_yesterday);

	if ($latest_day == $day && $latest_shift == $shift) {
		// MySQL
		$sql = "SELECT id FROM t_time_in_out WHERE emp_no = '$emp_no' AND day = '$day' AND shift = '$shift' AND time_out IS NULL ORDER BY date_updated DESC LIMIT 1";
		// MS SQL Server
		// $sql = "SELECT TOP 1 id FROM t_time_in_out WHERE emp_no = '$emp_no' AND day = '$day' AND shift = '$shift' AND time_out IS NULL ORDER BY date_updated DESC";
		$stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt -> execute();
		if ($stmt -> rowCount() > 0) {
			$sql = "INSERT INTO t_line_support_history(line_support_id, emp_no, day, shift, line_no_from, line_no_to, set_by, set_by_no, set_status_by, set_status_by_no, status) VALUES ('$line_support_id','$emp_no','$day','$shift','$line_no_from','$line_no_to','$set_by','$set_by_no','".$_SESSION['full_name']."','".$_SESSION['emp_no']."','accepted')";
			$stmt = $conn -> prepare($sql);
			$stmt -> execute();

			$sql = "DELETE FROM t_line_support WHERE id = '$id'";
			$stmt = $conn -> prepare($sql);
			$stmt -> execute();

			update_notif_line_support($line_no_from, 'accepted', $conn);

			echo 'success';
		} else {
			echo 'Already Time Out';
		}
	} else {
		echo 'Current Day or Shift Unmatched';
	}
}

if ($method == 'get_recent_line_support_history') {
	$line_no_to = $_SESSION['line_no'];
	$line_no_from = $_SESSION['line_no'];

	$c = 0;
	$row_class_arr = array('modal-trigger', 'modal-trigger bg-success', 'modal-trigger bg-teal', 'modal-trigger bg-danger', 'modal-trigger bg-purple');
	$row_class = $row_class_arr[0];

	// MySQL
	$sql = "SELECT 
		ls.id, ls.line_support_id, ls.emp_no, emp.full_name, emp.dept, emp.process, ls.day, ls.shift, emp.shift_group, ls.line_no_from, ls.line_no_to, ls.set_by, ls.set_by_no, ls.set_status_by, ls.set_status_by_no, ls.status, ls.date_updated
		FROM t_line_support_history ls 
		LEFT JOIN m_employees emp
		ON ls.emp_no = emp.emp_no
		WHERE (ls.line_no_from = '$line_no_from' 
		OR ls.line_no_to = '$line_no_to') 
		AND ls.status IN ('rejected','accepted')
		ORDER BY ls.date_updated DESC
		LIMIT 50";
	// MS SQL Server
	// $sql = "SELECT TOP 50 
	// 	ls.id, ls.line_support_id, ls.emp_no, emp.full_name, emp.dept, emp.process, ls.day, ls.shift, emp.shift_group, ls.line_no_from, ls.line_no_to, ls.set_by, ls.set_by_no, ls.set_status_by, ls.set_status_by_no, ls.status, ls.date_updated
	// 	FROM t_line_support_history ls 
	// 	LEFT JOIN m_employees emp
	// 	ON ls.emp_no = emp.emp_no
	// 	WHERE (ls.line_no_from = '$line_no_from' 
	// 	OR ls.line_no_to = '$line_no_to') 
	// 	AND ls.status IN ('rejected','accepted')
	// 	ORDER BY ls.date_updated DESC";
	$stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt -> execute();
	if ($stmt -> rowCount() > 0) {
		foreach($stmt -> fetchAll() as $row) {
			$c++;
			if ($row['line_no_from'] == $line_no_from) {
				if ($row['status'] == 'accepted') {
					$row_class = $row_class_arr[2];
				} else if ($row['status'] == 'rejected') {
					$row_class = $row_class_arr[4];
				}
			} else if ($row['line_no_to'] == $line_no_to) {
				if ($row['status'] == 'accepted') {
					$row_class = $row_class_arr[1];
				} else if ($row['status'] == 'rejected') {
					$row_class = $row_class_arr[3];
				}
			} else {
				$row_class = $row_class_arr[0];
			}
			echo '<tr style="cursor:pointer;" class="'.$row_class.'" data-toggle="modal" data-target="#line_support_history" onclick="get_recent_line_support_history_details(&quot;'.$row['id'].'~!~'.$row['line_support_id'].'~!~'.$row['emp_no'].'~!~'.$row['full_name'].'~!~'.$row['dept'].'~!~'.$row['day'].'~!~'.$row['shift'].'~!~'.$row['line_no_from'].'~!~'.$row['line_no_to'].'~!~'.$row['set_by'].'~!~'.$row['set_by_no'].'~!~'.$row['set_status_by'].'~!~'.$row['set_status_by_no'].'~!~'.$row['status'].'~!~'.$row['process'].'~!~'.$row['shift_group'].'&quot;)">';
			echo '<td>'.$c.'</td>';
			echo '<td>'.$row['emp_no'].'</td>';
			echo '<td>'.$row['full_name'].'</td>';
			echo '<td>'.$row['dept'].'</td>';
			echo '<td>'.$row['process'].'</td>';
			echo '<td>'.$row['day'].'</td>';
			echo '<td>'.$row['shift'].'</td>';
			echo '<td>'.$row['shift_group'].'</td>';
			echo '<td>'.$row['line_no_from'].'</td>';
			echo '<td>'.$row['line_no_to'].'</td>';
			echo '<td>'.$row['set_by'].'</td>';
			echo '<td>'.$row['date_updated'].'</td>';
			echo '</tr>';
		}
	}
}

if ($method == 'get_line_support_history') {
	$day = $_POST['day'];
	$shift = $_POST['shift'];
	$emp_no = $_POST['emp_no'];
	$full_name = $_POST['full_name'];
	$line_no_from_search = $_POST['line_no_from'];
	$line_no_to_search = $_POST['line_no_to'];
	$history_status = $_POST['history_status'];
	$line_no_to = $_SESSION['line_no'];
	$line_no_from = $_SESSION['line_no'];

	$c = 0;
	$row_class_arr = array('modal-trigger', 'modal-trigger bg-success', 'modal-trigger bg-teal', 'modal-trigger bg-danger', 'modal-trigger bg-purple');
	$row_class = $row_class_arr[0];

	$sql = "SELECT 
		ls.id, ls.line_support_id, ls.emp_no, emp.full_name, emp.dept, emp.process, ls.day, ls.shift, emp.shift_group, ls.line_no_from, ls.line_no_to, ls.set_by, ls.set_by_no, ls.set_status_by, ls.set_status_by_no, ls.status, ls.date_updated
		FROM t_line_support_history ls 
		LEFT JOIN m_employees emp
		ON ls.emp_no = emp.emp_no
		WHERE ls.day = '$day' AND ls.shift = '$shift'";

	if (!empty($emp_no)) {
		$sql = $sql . " AND ls.emp_no LIKE '$emp_no%'";
	}

	if (!empty($full_name)) {
		$sql = $sql . " AND ls.full_name LIKE '$full_name%'";
	}

	if ($history_status == "2" || $history_status == "4") {
		if (!empty($line_no_to_search)) {
			$sql = $sql . " AND (ls.line_no_from = '$line_no_from' 
		AND ls.line_no_to = '$line_no_to_search')";
		} else {
			$sql = $sql . " AND ls.line_no_from = '$line_no_from'";
		}
	} else if ($history_status == "1" || $history_status == "3") {
		if (!empty($line_no_from_search)) {
			$sql = $sql . " AND (ls.line_no_from = '$line_no_from_search' 
		AND ls.line_no_to = '$line_no_to')";
		} else {
			$sql = $sql . " AND ls.line_no_to = '$line_no_to'";
		}
	} else {
		if (!empty($line_no_from_search) && !empty($line_no_to_search)) {
			$sql = $sql . " AND (ls.line_no_from = '$line_no_to_search' 
		OR ls.line_no_to = '$line_no_to_search')";
		} else if (!empty($line_no_from_search)) {
			$sql = $sql . " AND (ls.line_no_from = '$line_no_from_search' 
		AND ls.line_no_to = '$line_no_to')";
		} else if (!empty($line_no_to_search)) {
			$sql = $sql . " AND (ls.line_no_from = '$line_no_from' 
		AND ls.line_no_to = '$line_no_to_search')";
		} else {
			$sql = $sql . " AND (ls.line_no_from = '$line_no_from' 
		OR ls.line_no_to = '$line_no_to')";
		}
	}

	if ($history_status == "1" || $history_status == "2") {
		$sql = $sql . " AND ls.status = 'accepted'";
	} else if ($history_status == "3" || $history_status == "4") {
		$sql = $sql . " AND ls.status = 'rejected'";
	}

	$sql = $sql . " ORDER BY ls.date_updated DESC";

	$stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt -> execute();
	if ($stmt -> rowCount() > 0) {
		foreach($stmt -> fetchAll() as $row) {
			$c++;
			if ($row['line_no_from'] == $line_no_from) {
				if ($row['status'] == 'accepted') {
					$row_class = $row_class_arr[2];
				} else if ($row['status'] == 'rejected') {
					$row_class = $row_class_arr[4];
				}
			} else if ($row['line_no_to'] == $line_no_to) {
				if ($row['status'] == 'accepted') {
					$row_class = $row_class_arr[1];
				} else if ($row['status'] == 'rejected') {
					$row_class = $row_class_arr[3];
				}
			} else {
				$row_class = $row_class_arr[0];
			}
			echo '<tr style="cursor:pointer;" class="'.$row_class.'" data-toggle="modal" data-target="#line_support_history" onclick="get_recent_line_support_history_details(&quot;'.$row['id'].'~!~'.$row['line_support_id'].'~!~'.$row['emp_no'].'~!~'.$row['full_name'].'~!~'.$row['dept'].'~!~'.$row['day'].'~!~'.$row['shift'].'~!~'.$row['line_no_from'].'~!~'.$row['line_no_to'].'~!~'.$row['set_by'].'~!~'.$row['set_by_no'].'~!~'.$row['set_status_by'].'~!~'.$row['set_status_by_no'].'~!~'.$row['status'].'~!~'.$row['process'].'~!~'.$row['shift_group'].'&quot;)">';
			echo '<td>'.$c.'</td>';
			echo '<td>'.$row['emp_no'].'</td>';
			echo '<td>'.$row['full_name'].'</td>';
			echo '<td>'.$row['dept'].'</td>';
			echo '<td>'.$row['process'].'</td>';
			echo '<td>'.$row['day'].'</td>';
			echo '<td>'.$row['shift'].'</td>';
			echo '<td>'.$row['shift_group'].'</td>';
			echo '<td>'.$row['line_no_from'].'</td>';
			echo '<td>'.$row['line_no_to'].'</td>';
			echo '<td>'.$row['set_by'].'</td>';
			echo '<td>'.$row['status'].'</td>';
			echo '<td>'.$row['date_updated'].'</td>';
			echo '</tr>';
		}
	}
}

$conn = NULL;
?>