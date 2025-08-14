<?php
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

include '../../conn.php';

$method = $_POST['method'];

// Line Support

function update_notif_line_support($line_no, $status, $conn) {
	if ($status != 'Added' && $status != 'Saved') {
		// MySQL
		// $sql = "UPDATE t_notif_line_support nls
		// 		LEFT JOIN m_accounts acc
		// 		ON acc.emp_no = nls.emp_no ";
		// if ($status == 'pending') {
		// 	$sql = $sql . " SET nls.pending_ls = nls.pending_ls + 1";
		// } else if ($status == 'accepted') {
		// 	$sql = $sql . " SET nls.accepted_ls = nls.accepted_ls + 1";
		// } else if ($status == 'rejected') {
		// 	$sql = $sql . " SET nls.rejected_ls = nls.rejected_ls + 1";
		// }

		// MS SQL Server
		$sql = "UPDATE nls";
		if ($status == 'pending') {
			$sql = $sql . " SET nls.pending_ls = nls.pending_ls + 1";
		} else if ($status == 'accepted') {
			$sql = $sql . " SET nls.accepted_ls = nls.accepted_ls + 1";
		} else if ($status == 'rejected') {
			$sql = $sql . " SET nls.rejected_ls = nls.rejected_ls + 1";
		}
		$sql = $sql . " FROM t_notif_line_support AS nls 
						LEFT JOIN m_accounts AS acc
						ON acc.emp_no = nls.emp_no";
		$params = [];

		if (!empty($line_no)) {
			$sql = $sql . " WHERE acc.line_no = ?";
			$params[] = $line_no;
		} else {
			$sql = $sql . " WHERE acc.line_no IS NULL OR acc.line_no = ''";
		}

		$stmt = $conn -> prepare($sql);
		$stmt -> execute($params);
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

if ($method == 'get_assigned_process_dropdown') {
	$category = $_POST['category'];
	$emp_no = $_POST['emp_no'];

	$table_name = '';

	if ($category == 'Initial') {
		$table_name = '[qualif].[dbo].[t_i_process]';
	} else if ($category == 'Final') {
		$table_name = '[qualif].[dbo].[t_f_process]';
	}

	$sql = "DECLARE @EmpId NVARCHAR(255) = ?;

			WITH AllProcess AS (
				SELECT process 
				FROM $table_name 
				WHERE (emp_id = @EmpId OR emp_id_old = @EmpId) 
				UNION ALL 
				SELECT process 
				FROM [trs_renewal].[dbo].[trs_renewal_request]
				WHERE (emp_id = @EmpId OR emp_id_old = @EmpId) 
				SELECT process 
				FROM [trs_renewal].[dbo].[trs_renewal_history]
				WHERE (emp_id = @EmpId OR emp_id_old = @EmpId) 
				SELECT process 
				FROM [trs_renewal].[dbo].[trs_renewal_new_mp]
				WHERE (emp_id = @EmpId OR emp_id_old = @EmpId) 
			)
			
			SELECT process 
			FROM AllProcess 
			GROUP BY process";

	$params[] = $emp_no;
	
	$stmt = $conn -> prepare($sql);
	$stmt -> execute($params);
	
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
		echo '<option selected value="">Select Process</option>';
		do {
			echo '<option value="'.htmlspecialchars($row['process']).'">'.htmlspecialchars($row['process']).'</option>';
		} while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
	} else {
		echo '<option disabled selected value="">Select Process</option>';
	}
}

if ($method == 'get_assigned_station_dropdown') {
	$category = $_POST['category'];

	$sql = "SELECT assigned_station FROM m_assigned_stations WHERE category = ? ORDER BY assigned_station ASC";
	$stmt = $conn -> prepare($sql);
	$params = array($category);
	$stmt -> execute($params);

	echo '<option selected value="">Select Assigned Station</option>';
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		echo '<option value="'.htmlspecialchars($row['assigned_station']).'">'.htmlspecialchars($row['assigned_station']).'</option>';
	}
}

if ($method == 'set_line_support_details') {
	$id = $_POST['id'];
	$assigned_process = $_POST['assigned_process'];
	$assigned_station = $_POST['assigned_station'];
	$assigned_station_no = $_POST['assigned_station_no'];
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];

	$shift = get_shift($server_time);

	$valid_dates = false;

	if ($start_date >= '06:00' && $start_date < '18:00' && $shift == 'DS') {
		if ($end_date >= '07:00' && $end_date <= '18:00') {
			if ($start_date < $end_date) {
				$valid_dates = true;
			}
		}
	} else if ($start_date >= '18:00' && $start_date <= '23:59' && $shift == 'NS') {
    	if ($end_date >= '19:00' && $end_date <= '23:59') {
			if ($start_date < $end_date) {
				$valid_dates = true;
			}
		} else if ($end_date >= '00:00' && $end_date <= '06:00') {
			$valid_dates = true;	
		}
	} else if ($start_date >= '00:00' && $start_date < '06:00' && $shift == 'NS') {
		if ($end_date >= '01:00' && $end_date <= '06:00') {
			if ($start_date < $end_date) {
				$valid_dates = true;
			}
		}
	}

	if ($valid_dates) {
		// Combine date and time (Temporary)
		if ($shift == 'DS') {
			$start_date = $server_date_only . ' ' . $start_date . ':00';
			$end_date = $server_date_only . ' ' . $end_date . ':00';
		} else if ($shift == 'NS') {
			if ($start_date >= '18:00' && $start_date <= '23:59') {
				if ($server_time >= '18:00:00' && $server_time <= '23:59:59') {
					$start_date = $server_date_only . ' ' . $start_date . ':00';
				} else if ($server_time >= '00:00:00' && $server_time < '06:00:00') {
					$start_date = $server_date_only_yesterday . ' ' . $start_date . ':00';
				}
			} else if ($start_date >= '00:00' && $start_date < '06:00') {
				if ($server_time >= '18:00:00' && $server_time <= '23:59:59') {
					$start_date = $server_date_only_tomorrow . ' ' . $start_date . ':00';
				} else if ($server_time >= '00:00:00' && $server_time < '06:00:00') {
					$start_date = $server_date_only . ' ' . $start_date . ':00';
				}
			}
			if ($end_date >= '19:00' && $end_date <= '23:59') {
				if ($server_time >= '18:00:00' && $server_time <= '23:59:59') {
					$end_date = $server_date_only_tomorrow . ' ' . $end_date . ':00';
				} else if ($server_time >= '00:00:00' && $server_time < '06:00:00') {
					$end_date = $server_date_only_yesterday . ' ' . $end_date . ':00';
				}
			} else if ($end_date >= '00:00' && $end_date <= '06:00') {
				if ($server_time >= '18:00:00' && $server_time <= '23:59:59') {
					$end_date = $server_date_only_tomorrow . ' ' . $end_date . ':00';
				} else if ($server_time >= '00:00:00' && $server_time < '06:00:00') {
					$end_date = $server_date_only . ' ' . $end_date . ':00';
				}
			}
		}

		$skill_level = 0;

		$sql = "SELECT sl.skill_level 
				FROM t_line_support ls 
				LEFT JOIN m_skill_level sl ON ls.emp_no = sl.emp_no  -- Only join on emp_no
				WHERE ls.id = ? AND sl.process = ?";
		$stmt = $conn -> prepare($sql);
		$params = array($id, $assigned_process);
		$stmt->execute($params);

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($row) {
			$skill_level = intval($row['skill_level']);
		}

		// $sql = "UPDATE ls 
		// 		SET 
		// 			ls.skill_level = COALESCE(sl.skill_level, '0'), 
		// 			ls.assigned_process = COALESCE(sl.process, ?), 
		// 			ls.assigned_station = ?, 
		// 			ls.assigned_station_no = ?, 
		// 			ls.start_date = ?, 
		// 			ls.end_date = ? 
		// 		FROM 
		// 			t_line_support ls 
		// 		LEFT JOIN 
		// 			m_skill_level sl ON ls.emp_no = sl.emp_no  -- Only join on emp_no
		// 		WHERE 
		// 			ls.id = ?  -- Replace with the appropriate ID value
		// 			AND (sl.process = ? OR sl.process IS NULL);  -- Replace with the appropriate process value
		// 		";

		$sql = "UPDATE t_line_support 
				SET skill_level = ?, assigned_process = ?, 
					assigned_station = ?, assigned_station_no = ?, 
					start_date = ?, end_date = ? 
				WHERE id = ?";
		$stmt = $conn -> prepare($sql);
		$params = array(
			$skill_level, $assigned_process, $assigned_station, $assigned_station_no, 
			$start_date, $end_date, $id 
		);
		if ($stmt->execute($params)) {
			echo 'success';
		} else {
			echo 'error';
		}
	} else {
		echo 'Please set start and end date correctly.';
	}
}

// Get Line Datalist
if ($method == 'fetch_line_dropdown') {
	$line_no = $_SESSION['line_no'];

	$sql = "SELECT line_no FROM m_access_locations";
	$params = [];

	if (isset($_SESSION['line_no'])) {
		$sql .= " WHERE line_no != ?";
		$params[] = $line_no;
	}
	$sql .= " GROUP BY line_no ORDER BY line_no ASC";
	
	$stmt = $conn -> prepare($sql);
	$stmt -> execute($params);
	
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
		echo '<option selected value="">Select Line No.</option>';
		do {
			echo '<option value="'.htmlspecialchars($row['line_no']).'">'.htmlspecialchars($row['line_no']).'</option>';
		} while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
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
			WHERE tio.emp_no = ? 
			AND tio.day = ? 
			AND tio.shift = ?";

	$params = [
		$emp_no,
		$day,
		$shift
	];

	if (!empty($line_no)) {
		$sql = $sql . " AND emp.line_no = ?";
		$params[] = $line_no;
	} else {
		$sql = $sql . " AND (emp.line_no IS NULL OR emp.line_no = '')";
	}

	$stmt = $conn -> prepare($sql);
	$stmt -> execute($params);

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
		$full_name = $row['full_name'];
		$time_out = $row['time_out'];

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

	// Check Duplicates
	$sql = "SELECT id FROM t_line_support 
			WHERE day = ? AND shift = ? AND emp_no = ? AND status = 'added'";
	$stmt = $conn -> prepare($sql);
	$params = array($day, $shift, $emp_no);
	$stmt -> execute($params);

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($row) {
		echo 'Duplicate';
		$conn = null;
		exit();
	}

	// Check Already Set
	$sql = "SELECT id FROM t_line_support 
			WHERE day = ? AND shift = ? AND emp_no = ? AND status = 'pending'";
	$stmt = $conn -> prepare($sql);
	$params = array($day, $shift, $emp_no);
	$stmt -> execute($params);

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($row) {
		echo 'Already Set';
		$conn = null;
		exit();
	}

	// Check Already Supported
	$sql = "SELECT id FROM t_line_support_history 
			WHERE day = ? AND shift = ? AND emp_no = ? AND status = 'accepted'";
	$stmt = $conn -> prepare($sql);
	$params = array($day, $shift, $emp_no);
	$stmt -> execute($params);

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($row) {
		echo 'Already Supported';
		$conn = null;
		exit();
	}

	// Check Already Time Out
	$sql = "SELECT id FROM t_time_in_out 
			WHERE day = ? AND shift = ? AND emp_no = ? AND time_out IS NULL";
	$stmt = $conn -> prepare($sql);
	$params = array($day, $shift, $emp_no);
	$stmt -> execute($params);

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if (!$row) {
		echo 'Already Time Out';
		$conn = null;
		exit();
	}

	// Check No Certification
	$check_line_no_to = intval($line_no_to);

	$table_name = '';

	if ($check_line_no_to > 0) {
		$table_name = '[qualif].[dbo].[t_f_process]';
	} else {
		$table_name = '[qualif].[dbo].[t_i_process]';
	}

	$sql = "DECLARE @EmpId NVARCHAR(255) = ?;

			WITH AllProcess AS (
				SELECT process 
				FROM $table_name 
				WHERE (emp_id = @EmpId OR emp_id_old = @EmpId) 
				UNION ALL 
				SELECT process 
				FROM [trs_renewal].[dbo].[trs_renewal_request]
				WHERE (emp_id = @EmpId OR emp_id_old = @EmpId) 
				SELECT process 
				FROM [trs_renewal].[dbo].[trs_renewal_history]
				WHERE (emp_id = @EmpId OR emp_id_old = @EmpId) 
				SELECT process 
				FROM [trs_renewal].[dbo].[trs_renewal_new_mp]
				WHERE (emp_id = @EmpId OR emp_id_old = @EmpId) 
			)
			
			SELECT process 
			FROM AllProcess 
			GROUP BY process";

	$params[] = $emp_no;
	
	$stmt = $conn -> prepare($sql);
	$stmt -> execute($params);
	
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if (!$row) {
		echo 'No Certification';
		$conn = null;
		exit();
	}

	// Proceed to add line support
	$set_by = $_SESSION['full_name'];
	$set_by_no = $_SESSION['emp_no'];

	$sql = "INSERT INTO t_line_support 
				(line_support_id, emp_no, day, shift, line_no_from, line_no_to, set_by, set_by_no) 
			VALUES 
				(?, ?, ?, ?, ?, ?, ?, ?)";
	$stmt = $conn -> prepare($sql);
	$params = array($line_support_id, $emp_no, $day, $shift, $line_no_from, $line_no_to, $set_by, $set_by_no);
	$stmt -> execute($params);

	$response_arr = array(
		'line_support_id' => $line_support_id,
		'message' => 'success'
	);

	echo json_encode($response_arr, JSON_FORCE_OBJECT);
}

if ($method == 'get_added_line_support') {
	$line_support_id = $_POST['line_support_id'];
	$c = 0;
	$sql = "SELECT 
				ls.id, ls.line_support_id, ls.emp_no, emp.full_name, emp.dept, emp.process, 
				ls.day, ls.shift, emp.shift_group, ls.line_no_to, ls.assigned_process, ls.skill_level, 
				ls.assigned_station, ls.assigned_station_no, ls.start_date, ls.end_date 
			FROM t_line_support ls 
			LEFT JOIN m_employees emp
			ON ls.emp_no = emp.emp_no
			WHERE ls.line_support_id = ? 
			ORDER BY ls.id DESC";

	$stmt = $conn -> prepare($sql);
	$params = array($line_support_id);
	$stmt -> execute($params);

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$c++;

		$assigned_station = $row['assigned_station'] . ' ' . $row['assigned_station_no'];

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
		echo '<td>'.$row['assigned_process'].'</td>';
		echo '<td>'.$row['skill_level'].'</td>';
		echo '<td>'.$assigned_station.'</td>';
		echo '<td>'.$row['start_date'].'</td>';
		echo '<td>'.$row['end_date'].'</td>';
		echo '<td><center><i class="fas fa-pencil-alt" style="cursor:pointer;" data-emp_no="'.$row['emp_no'].'" data-id="'.$row['id'].'" onclick="edit_single_added_line_support(this);"></i></center></td>';
		echo '<td><center><i class="fas fa-trash" style="cursor:pointer;" data-line_support_id="'.$row['line_support_id'].'" data-id="'.$row['id'].'" onclick="delete_single_added_line_support(this);"></i></center></td>';
		echo '</tr>';
	}
}

if ($method == 'delete_single_added_line_support') {
	$id = $_POST['id'];
	$line_support_id = $_POST['line_support_id'];

	$sql = "DELETE FROM t_line_support WHERE line_support_id = ? AND id = ?";
	$stmt = $conn -> prepare($sql);
	$params = array($line_support_id, $id);
	$stmt -> execute($params);

	echo 'success';
}

// Direct Accepted Status when save all set line support on line_no_from
if ($method == 'save_line_support') {
	$line_support_id = $_POST['line_support_id'];

	$isTransactionActive = false;

	try {
		if (!$isTransactionActive) {
			$conn->beginTransaction();
			$isTransactionActive = true;
		}

		// $sql = "UPDATE t_line_support SET status = 'pending' WHERE line_support_id = ?";
		$sql = "UPDATE t_line_support SET status = 'accepted' WHERE line_support_id = ?";

		$stmt = $conn -> prepare($sql);
		$params = array($line_support_id);
		$stmt -> execute($params);

		$sql = "INSERT INTO t_line_support_history 
					(line_support_id, emp_no, day, shift, line_no_from, line_no_to, 
					assigned_process, skill_level, assigned_station, assigned_station_no, 
					set_by, set_by_no, set_status_by, set_status_by_no, 
					status, start_date, end_date) 
				SELECT 
					line_support_id, emp_no, day, shift, line_no_from, line_no_to, 
					assigned_process, skill_level, assigned_station, assigned_station_no, 
					set_by, set_by_no, set_by AS set_status_by, set_by_no AS set_status_by_no, 
					status, start_date, end_date 
				FROM t_line_support 
				WHERE line_support_id = ?";

		$stmt = $conn -> prepare($sql);
		$params = array($line_support_id);
		$stmt -> execute($params);

		$sql = "DELETE FROM t_line_support WHERE line_support_id = ?";

		$stmt = $conn -> prepare($sql);
		$params = array($line_support_id);
		$stmt -> execute($params);

		$sql = "SELECT line_no_to FROM t_line_support_history WHERE line_support_id = ? ORDER BY id DESC";

		$stmt = $conn -> prepare($sql);
		$params = array($line_support_id);
		$stmt -> execute($params);

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			update_notif_line_support($row['line_no_to'], 'accepted', $conn);
		}

		$conn->commit();
		$isTransactionActive = false;

		echo 'success';
	} catch (Exception $e) {
		if ($isTransactionActive) {
			$conn->rollBack();
			$isTransactionActive = false;
		}

		echo 'Failed. Please Try Again or Call IT Personnel Immediately!: ' . $e->getMessage();

		$conn = null;
		exit();
	}
}

if ($method == 'get_pending_line_support') {
	$line_no_to = $_SESSION['line_no'];
	$line_no_from = $_SESSION['line_no'];
	$pending_status = "";

	$c = 0;
	$row_class_arr = array('modal-trigger', 'modal-trigger bg-warning', 'modal-trigger bg-orange');
	$row_class = $row_class_arr[0];

	$sql = "SELECT 
				ls.id, ls.line_support_id, ls.emp_no, emp.full_name, emp.dept, emp.process, 
				ls.day, ls.shift, emp.shift_group, ls.line_no_from, ls.line_no_to, 
				ls.set_by, ls.set_by_no, ls.date_updated
			FROM t_line_support ls 
			LEFT JOIN m_employees emp
			ON ls.emp_no = emp.emp_no
			WHERE (ls.line_no_from = ? 
			OR ls.line_no_to = ?) 
			AND ls.status = 'pending'
			ORDER BY ls.date_updated DESC";
	$params = [
		$line_no_from,
		$line_no_to
	];

	$stmt = $conn -> prepare($sql);
	$stmt -> execute($params);

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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

// Depreciated
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
	$set_status_by = $_SESSION['full_name'];
	$set_status_by_no = $_SESSION['emp_no'];

	$sql = "SELECT 
				line_support_id, emp_no, day, shift, line_no_from, line_no_to, 
				set_by, set_by_no 
			FROM t_line_support WHERE id = ?";
	$stmt = $conn -> prepare($sql);
	$params = array($id);
	$stmt -> execute($params);

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

	$sql = "INSERT INTO t_line_support_history 
				(line_support_id, emp_no, day, shift, line_no_from, line_no_to, set_by, set_by_no, set_status_by, set_status_by_no, status) 
			VALUES 
				(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'rejected')";
	$stmt = $conn -> prepare($sql);
	$params = array($line_support_id, $emp_no,$day, $shift, $line_no_from, $line_no_to, $set_by, $set_by_no, $set_status_by, $set_status_by_no);
	$stmt -> execute($params);

	$sql = "DELETE FROM t_line_support WHERE id = ?";
	$stmt = $conn -> prepare($sql);
	$params = array($id);
	$stmt -> execute($params);

	update_notif_line_support($line_no_from, 'rejected', $conn);

	echo 'success';
}

// Depreciated
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
	$set_status_by = $_SESSION['full_name'];
	$set_status_by_no = $_SESSION['emp_no'];

	$sql = "SELECT 
				line_support_id, emp_no, day, shift, line_no_from, line_no_to, 
				set_by, set_by_no 
			FROM t_line_support WHERE id = ?";
	$stmt = $conn -> prepare($sql);
	$params = array($id);
	$stmt -> execute($params);

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$line_support_id = $row['line_support_id'];
		$emp_no = $row['emp_no'];
		$day = $row['day'];
		$shift = $row['shift'];
		$line_no_from = $row['line_no_from'];
		$line_no_to = $row['line_no_to'];
		$set_by = $row['set_by'];
		$set_by_no = $row['set_by_no'];
	}

	$latest_shift = get_shift($server_time);
	$latest_day = get_day($server_time, $server_date_only, $server_date_only_yesterday);

	if ($latest_day == $day && $latest_shift == $shift) {
		// MySQL
		// $sql = "SELECT id FROM t_time_in_out WHERE emp_no = '$emp_no' AND day = '$day' AND shift = '$shift' AND time_out IS NULL ORDER BY date_updated DESC LIMIT 1";
		// MS SQL Server
		$sql = "SELECT TOP 1 id 
				FROM t_time_in_out 
				WHERE emp_no = ? AND 
				day = ? AND 
				shift = ? AND 
				time_out IS NULL 
				ORDER BY date_updated DESC";
		$stmt = $conn -> prepare($sql);
		$params = array($emp_no, $day, $shift);
		$stmt -> execute($params);

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

    	if ($row) {
			$sql = "INSERT INTO t_line_support_history 
						(line_support_id, emp_no, day, shift, line_no_from, line_no_to, set_by, set_by_no, set_status_by, set_status_by_no, status) 
					VALUES 
						(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'accepted')";
			$stmt = $conn -> prepare($sql);
			$params = array($line_support_id, $emp_no, $day, $shift, $line_no_from, $line_no_to, $set_by, $set_by_no, $set_status_by, $set_status_by_no);
			$stmt -> execute($params);

			$sql = "DELETE FROM t_line_support WHERE id = ?";
			$stmt = $conn -> prepare($sql);
			$params = array($id);
			$stmt -> execute($params);

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
	// $sql = "SELECT 
	// 	ls.id, ls.line_support_id, ls.emp_no, emp.full_name, emp.dept, emp.process, ls.day, ls.shift, emp.shift_group, ls.line_no_from, ls.line_no_to, ls.set_by, ls.set_by_no, ls.set_status_by, ls.set_status_by_no, ls.status, ls.date_updated
	// 	FROM t_line_support_history ls 
	// 	LEFT JOIN m_employees emp
	// 	ON ls.emp_no = emp.emp_no
	// 	WHERE (ls.line_no_from = '$line_no_from' 
	// 	OR ls.line_no_to = '$line_no_to') 
	// 	AND ls.status IN ('rejected','accepted')
	// 	ORDER BY ls.date_updated DESC
	// 	LIMIT 50";
	// MS SQL Server
	$sql = "SELECT TOP 50 
				ls.id, ls.line_support_id, ls.emp_no, emp.full_name, emp.dept, emp.process, 
				ls.day, ls.shift, emp.shift_group, ls.line_no_from, ls.line_no_to, 
				ls.set_by, ls.set_by_no, ls.set_status_by, ls.set_status_by_no, ls.status, ls.date_updated
			FROM t_line_support_history ls 
			LEFT JOIN m_employees emp
			ON ls.emp_no = emp.emp_no
			WHERE (ls.line_no_from = ? 
			OR ls.line_no_to = ?) 
			AND ls.status IN ('rejected','accepted')
			ORDER BY ls.date_updated DESC";
	$params = [
		$line_no_from,
		$line_no_to
	];

	$stmt = $conn -> prepare($sql);
	$stmt -> execute($params);

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
				ls.id, ls.line_support_id, ls.emp_no, emp.full_name, emp.dept, emp.process, 
				ls.day, ls.shift, emp.shift_group, ls.line_no_from, ls.line_no_to, 
				ls.set_by, ls.set_by_no, ls.set_status_by, ls.set_status_by_no, ls.status, ls.date_updated
			FROM t_line_support_history ls 
			LEFT JOIN m_employees emp
			ON ls.emp_no = emp.emp_no
			WHERE ls.day = ? AND ls.shift = ?";
	$params = [
		$day,
		$shift
	];

	if (!empty($emp_no)) {
		$sql = $sql . " AND ls.emp_no LIKE ?";
		$emp_no_param = $emp_no . "%";
		$params[] = $emp_no_param;
	}

	if (!empty($full_name)) {
		$sql = $sql . " AND ls.full_name LIKE ?";
		$full_name_param = $full_name . "%";
		$params[] = $full_name_param;
	}

	if ($history_status == "2" || $history_status == "4") {
		if (!empty($line_no_to_search)) {
			$sql = $sql . " AND (ls.line_no_from = ? 
							AND ls.line_no_to = ?)";
			$params[] = $line_no_from;
			$params[] = $line_no_to_search;
		} else {
			$sql = $sql . " AND ls.line_no_from = ?";
			$params[] = $line_no_from;
		}
	} else if ($history_status == "1" || $history_status == "3") {
		if (!empty($line_no_from_search)) {
			$sql = $sql . " AND (ls.line_no_from = ? 
							AND ls.line_no_to = ?)";
			$params[] = $line_no_from_search;
			$params[] = $line_no_to;
		} else {
			$sql = $sql . " AND ls.line_no_to = ?";
			$params[] = $line_no_to;
		}
	} else {
		if (!empty($line_no_from_search) && !empty($line_no_to_search)) {
			$sql = $sql . " AND (ls.line_no_from = ? 
							OR ls.line_no_to = ?)";
			$params[] = $line_no_to_search;
			$params[] = $line_no_to_search;
		} else if (!empty($line_no_from_search)) {
			$sql = $sql . " AND (ls.line_no_from = ? 
							AND ls.line_no_to = ?)";
			$params[] = $line_no_from_search;
			$params[] = $line_no_to;
		} else if (!empty($line_no_to_search)) {
			$sql = $sql . " AND (ls.line_no_from = ? 
							AND ls.line_no_to = ?)";
			$params[] = $line_no_from;
			$params[] = $line_no_to_search;
		} else {
			$sql = $sql . " AND (ls.line_no_from = ? 
							OR ls.line_no_to = ?)";
			$params[] = $line_no_from;
			$params[] = $line_no_to;
		}
	}

	if ($history_status == "1" || $history_status == "2") {
		$sql = $sql . " AND ls.status = 'accepted'";
	} else if ($history_status == "3" || $history_status == "4") {
		$sql = $sql . " AND ls.status = 'rejected'";
	}

	$sql = $sql . " ORDER BY ls.date_updated DESC";

	$stmt = $conn -> prepare($sql);
	$stmt -> execute($params);

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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

if ($method == 'get_line_support') {
	$day_from = $_POST['day_from'];
	$day_to = $_POST['day_to'];
	$shift = $_POST['shift'];
	$emp_no = $_POST['emp_no'];
	$full_name = $_POST['full_name'];
	$line_no_from_search = $_POST['line_no_from'];
	$line_no_to_search = $_POST['line_no_to'];

	$c = 0;
	$row_class_arr = array('modal-trigger', 'modal-trigger bg-success', 'modal-trigger bg-teal', 'modal-trigger bg-danger', 'modal-trigger bg-purple', 'modal-trigger bg-orange');
	$row_class = $row_class_arr[0];

	$sql = "SELECT TOP (1000) 
				ls.id, ls.line_support_id, ls.emp_no, emp.full_name, emp.dept, emp.section, emp.process, 
				ls.day, ls.shift, emp.shift_group, ls.line_no_from, ls.line_no_to, 
				ls.set_by, ls.set_by_no, ls.set_status_by, ls.set_status_by_no, ls.status, ls.date_updated, 
				pic.file_url 
			FROM t_line_support_history ls 
			LEFT JOIN m_employees emp ON ls.emp_no = emp.emp_no 
			LEFT JOIN m_employee_pictures pic ON ls.emp_no = pic.emp_no 
			WHERE (ls.day >= ? AND ls.day <= ?)";
	
	$params = [
		$day_from,
		$day_to
	];

	if (!empty($shift)) {
		$sql = $sql . " AND ls.shift = ?";
		$params[] = $shift;
	}

	if (!empty($emp_no)) {
		$sql = $sql . " AND ls.emp_no LIKE ?";
		$emp_no_param = $emp_no . "%";
		$params[] = $emp_no_param;
	}

	if (!empty($full_name)) {
		$sql = $sql . " AND emp.full_name LIKE ?";
		$full_name_param = $full_name . "%";
		$params[] = $full_name_param;
	}

	if (!empty($line_no_from_search) && !empty($line_no_to_search)) {
		$sql = $sql . " AND (ls.line_no_from = ? OR ls.line_no_to = ?)";
		$params[] = $line_no_to_search;
		$params[] = $line_no_to_search;
	} else if (!empty($line_no_from_search)) {
		$sql = $sql . " AND ls.line_no_from = ?";
		$params[] = $line_no_from_search;
	} else if (!empty($line_no_to_search)) {
		$sql = $sql . " AND ls.line_no_to = ?";
		$params[] = $line_no_to_search;
	}

	$sql = $sql . " AND ls.status = 'accepted'";

	$sql = $sql . " ORDER BY ls.date_updated DESC";

	$stmt = $conn -> prepare($sql);
	$stmt -> execute($params);

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$c++;
		
		if ($row['status'] == 'accepted') {
			$row_class = $row_class_arr[1];
		} else {
			$row_class = $row_class_arr[0];
		}
		echo '<tr style="cursor:pointer;" class="'.$row_class.'" 
				data-toggle="modal" data-target="#line_support_details" 
				onclick="get_line_support_details(&quot;'.
				$row['emp_no'].'~!~'.
				$row['full_name'].'~!~'.
				$row['dept'].'~!~'.
				$row['section'].'~!~'.
				$row['line_no_from'].'~!~'.
				$row['process'].'&quot;)">';
		echo '<td style="vertical-align: middle;">'.$c.'</td>';
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
		if (!empty($row['file_url'])) {
			echo '<td style="vertical-align: middle;"><img class="attendances_employee_picture_img_tag" src="'.htmlspecialchars($protocol."172.25.116.188:3000".$row['file_url']).'" alt="'.htmlspecialchars($row['emp_no']).'" height="75" width="75"></td>';
		} else {
			echo '<td style="vertical-align: middle;"><img class="attendances_employee_picture_img_tag" src="'.htmlspecialchars($protocol.$_SERVER['SERVER_ADDR'].":".$_SERVER['SERVER_PORT']).'/emp_mgt/dist/img/user.png" alt="'.htmlspecialchars($row['emp_no']).'" height="75" width="75"></td>';
		}
		echo '<td style="vertical-align: middle;">'.$row['day'].'</td>';
		echo '<td style="vertical-align: middle;">'.$row['shift'].'</td>';
		echo '<td style="vertical-align: middle;">'.$row['shift_group'].'</td>';
		echo '<td style="vertical-align: middle;">'.$row['emp_no'].'</td>';
		echo '<td style="vertical-align: middle;">'.$row['full_name'].'</td>';
		echo '<td style="vertical-align: middle;">'.$row['dept'].'</td>';
		echo '<td style="vertical-align: middle;">'.$row['section'].'</td>';
		echo '<td style="vertical-align: middle;">'.$row['process'].'</td>';
		echo '<td style="vertical-align: middle;">'.$row['line_no_from'].'</td>';
		echo '<td style="vertical-align: middle;">'.$row['line_no_to'].'</td>';
		echo '<td style="vertical-align: middle;">'.$row['set_by'].'</td>';
		echo '<td style="vertical-align: middle;">'.$row['set_by_no'].'</td>';
		echo '<td style="vertical-align: middle;">'.$row['set_status_by'].'</td>';
		echo '<td style="vertical-align: middle;">'.$row['set_status_by_no'].'</td>';
		echo '<td style="vertical-align: middle;">'.$row['status'].'</td>';
		echo '<td style="vertical-align: middle;">'.$row['date_updated'].'</td>';
		echo '</tr>';
	}
}

if ($method == 'get_employee_line_support_history') {
	$emp_no = $_POST['emp_no'];

	$c = 0;

	$sql = "SELECT TOP (1000) 
				emp.full_name, emp.shift_group, 
				ls.day, ls.shift, ls.line_no_to, 
				ls.assigned_process, ls.skill_level, ls.assigned_station, ls.assigned_station_no, 
				ls.start_date, ls.end_date, 
				ls.set_by, ls.set_by_no, ls.date_updated, 
				CASE 
					WHEN DATEDIFF(MINUTE, ls.start_date, ls.end_date) < 1 THEN '< 1 min' 
					ELSE 
						-- Build the elapsed time string conditionally
						LTRIM(
							CASE 
								WHEN DATEDIFF(MINUTE, ls.start_date, ls.end_date) / 1440 > 0 THEN 
									CAST(DATEDIFF(MINUTE, ls.start_date, ls.end_date) / 1440 AS VARCHAR(10)) + ' day' + 
									CASE WHEN DATEDIFF(MINUTE, ls.start_date, ls.end_date) / 1440 <> 1 THEN 's' ELSE '' END + 
									CASE WHEN (DATEDIFF(MINUTE, ls.start_date, ls.end_date) % 1440) / 60 > 0 OR DATEDIFF(MINUTE, ls.start_date, ls.end_date) % 60 > 0 THEN ', ' ELSE '' END
								ELSE '' 
							END +
							CASE 
								WHEN (DATEDIFF(MINUTE, ls.start_date, ls.end_date) % 1440) / 60 > 0 THEN 
									CAST((DATEDIFF(MINUTE, ls.start_date, ls.end_date) % 1440) / 60 AS VARCHAR(10)) + ' hour' + 
									CASE WHEN (DATEDIFF(MINUTE, ls.start_date, ls.end_date) % 1440) / 60 <> 1 THEN 's' ELSE '' END + 
									CASE WHEN DATEDIFF(MINUTE, ls.start_date, ls.end_date) % 60 > 0 THEN ', ' ELSE '' END
								ELSE '' 
							END +
							CASE 
								WHEN DATEDIFF(MINUTE, ls.start_date, ls.end_date) % 60 > 0 THEN 
									CAST(DATEDIFF(MINUTE, ls.start_date, ls.end_date) % 60 AS VARCHAR(10)) + ' min' + 
									CASE WHEN DATEDIFF(MINUTE, ls.start_date, ls.end_date) % 60 <> 1 THEN 's' ELSE '' END 
								ELSE '' 
							END
						) 
				END AS elapsed_time 
			FROM t_line_support_history ls 
			LEFT JOIN m_employees emp ON ls.emp_no = emp.emp_no 
			WHERE ls.emp_no LIKE ?";
	$params = [];

	$emp_no_param = $emp_no . "%";
	$params[] = $emp_no_param;

	$sql = $sql . " AND ls.status = 'accepted'";

	$sql = $sql . " ORDER BY ls.date_updated DESC";

	$stmt = $conn -> prepare($sql);
	$stmt -> execute($params);

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$c++;

		$assigned_station = $row['assigned_station'] . ' ' . $row['assigned_station_no'];

		echo '<tr>';
		echo '<td style="vertical-align: middle;">'.$c.'</td>';
		echo '<td style="vertical-align: middle;">'.$row['day'].'</td>';
		echo '<td style="vertical-align: middle;">'.$row['shift_group'].'</td>';
		echo '<td style="vertical-align: middle;">'.$row['shift'].'</td>';
		echo '<td style="vertical-align: middle;">'.$row['line_no_to'].'</td>';
		echo '<td style="vertical-align: middle;">'.$row['assigned_process'].'</td>';
		echo '<td style="vertical-align: middle;">'.$row['skill_level'].'</td>';
		echo '<td style="vertical-align: middle;">'.$assigned_station.'</td>';
		echo '<td style="vertical-align: middle;">'.$row['set_by'].'</td>';
		echo '<td style="vertical-align: middle;">'.$row['set_by_no'].'</td>';
		echo '<td style="vertical-align: middle;">'.$row['elapsed_time'].'</td>';
		echo '<td style="vertical-align: middle;">'.$row['start_date'].'</td>';
		echo '<td style="vertical-align: middle;">'.$row['end_date'].'</td>';
		echo '<td style="vertical-align: middle;">'.$row['date_updated'].'</td>';
		echo '</tr>';
	}
}

$conn = NULL;
