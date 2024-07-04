<?php 
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

include '../../conn.php';

$method = $_POST['method'];

// Account Management

function count_account_list($search_arr, $conn) {
	$query = "SELECT count(id) AS total FROM m_accounts WHERE";

	if (!empty($search_arr['emp_no'])) {
		$query = $query . " emp_no LIKE '".$search_arr['emp_no']."%'";
	} else {
		$query = $query . " emp_no != ''";
	}

	if (!empty($search_arr['full_name'])) {
		$query = $query . " AND full_name LIKE '".$search_arr['full_name']."%'";
	}

	if (!empty($search_arr['role'])) {
		$query = $query . " AND role = '".$search_arr['role']."'";
	}
	
	$stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $j){
			$total = $j['total'];
		}
	}else{
		$total = 0;
	}
	return $total;
}

if ($method == 'count_account_list') {
	$emp_no = addslashes($_POST['emp_no']);
	$full_name = addslashes($_POST['full_name']);
	$role = addslashes($_POST['role']);
	
	$search_arr = array(
		"emp_no" => $emp_no,
		"full_name" => $full_name,
		"role" => $role
	);

	echo count_account_list($search_arr, $conn);
}

if ($method == 'account_list_last_page') {
	$emp_no = addslashes($_POST['emp_no']);
	$full_name = addslashes($_POST['full_name']);
	$role = addslashes($_POST['role']);

	$search_arr = array(
		"emp_no" => $emp_no,
		"full_name" => $full_name,
		"role" => $role
	);

	$results_per_page = 20;

	$number_of_result = intval(count_account_list($search_arr, $conn));

	//determine the total number of pages available  
	$number_of_page = ceil($number_of_result / $results_per_page);

	echo $number_of_page;
}

if ($method == 'account_list') {
	$emp_no = addslashes($_POST['emp_no']);
	$full_name = addslashes($_POST['full_name']);
	$role = addslashes($_POST['role']);

	$current_page = intval($_POST['current_page']);
	$c = 0;

	$results_per_page = 20;

	//determine the sql LIMIT starting number for the results on the displaying page
	$page_first_result = ($current_page-1) * $results_per_page;

	$c = $page_first_result;

	$query = "SELECT id, emp_no, full_name, dept, section, line_no, shift_group, role FROM m_accounts WHERE";

	if (!empty($emp_no)) {
		$query = $query . " emp_no LIKE '$emp_no%'";
	} else {
		$query = $query . " emp_no != ''";
	}

	if (!empty($full_name)) {
		$query = $query . " AND full_name LIKE '$full_name%'";
	}

	if (!empty($role)) {
		$query = $query . " AND role = '$role'";
	}

	// MySQL Query
	$query = $query . " LIMIT ".$page_first_result.", ".$results_per_page;

	// MS SQL Server Query
	// $query = $query . " ORDER BY id ASC";
	// $query = $query . " OFFSET ".$page_first_result." ROWS FETCH NEXT ".$results_per_page." ROWS ONLY";

	$stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $j){
			$c++;

			if (isset($_SESSION['emp_no_hr'])) {
				echo '<tr>';

				echo '<td><p class="mb-0"><label class="mb-0"><input type="checkbox" class="singleCheck" value="'.$j['id'].'" onclick="get_checked_length()" /><span></span></label></p></td>';

				echo '<td style="cursor:pointer;" class="modal-trigger" data-toggle="modal" data-target="#update_account" onclick="get_accounts_details(&quot;'.$j['id'].'~!~'.$j['emp_no'].'~!~'.$j['full_name'].'~!~'.$j['dept'].'~!~'.$j['section'].'~!~'.$j['line_no'].'~!~'.$j['role'].'~!~'.$j['shift_group'].'&quot;)">'.$c.'</td>';
			} else {
				echo '<tr style="cursor:pointer;" class="modal-trigger" data-toggle="modal" data-target="#update_account" onclick="get_accounts_details(&quot;'.$j['id'].'~!~'.$j['emp_no'].'~!~'.$j['full_name'].'~!~'.$j['dept'].'~!~'.$j['section'].'~!~'.$j['line_no'].'~!~'.$j['role'].'~!~'.$j['shift_group'].'&quot;)">';

				echo '<td>'.$c.'</td>';
			}

				echo '<td>'.$j['emp_no'].'</td>';
				echo '<td>'.$j['full_name'].'</td>';
				echo '<td>'.$j['dept'].'</td>';
				echo '<td>'.$j['section'].'</td>';
				echo '<td>'.$j['line_no'].'</td>';
				echo '<td>'.$j['shift_group'].'</td>';
				echo '<td>'.strtoupper($j['role']).'</td>';
			echo '</tr>';
		}
	}else{
		echo '<tr>';
			echo '<td colspan="7" style="text-align:center; color:red;">No Result !!!</td>';
		echo '</tr>';
	}
}

if ($method == 'register_account') {
	$full_name = trim($_POST['full_name']);
	$emp_no = addslashes(trim($_POST['emp_no']));
	$dept = trim($_POST['dept']);
	$section = trim($_POST['section']);
	$line_no = trim($_POST['line_no']);
	$shift_group = trim($_POST['shift_group']);
	$role = trim($_POST['role']);

	$check = "SELECT id FROM m_accounts WHERE emp_no = '$emp_no'";
	$stmt = $conn->prepare($check, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		echo 'Already Exist';
	}else{
		$stmt = NULL;
		$query = "INSERT INTO m_accounts (emp_no, full_name, dept, section, line_no, shift_group, role) VALUES ('$emp_no','$full_name','$dept','$section','$line_no','$shift_group','$role')";
		$stmt = $conn->prepare($query);
		if ($stmt->execute()) {
			$stmt = NULL;
			$query = "INSERT INTO t_notif_line_support (emp_no) VALUES ('$emp_no')";
			$stmt = $conn->prepare($query);
			if ($stmt->execute()) {
				echo 'success';
			} else {
				echo 'error';
			}
		}else{
			echo 'error';
		}
	}
}

if ($method == 'update_account') {
	$id = $_POST['id'];
	$emp_no = addslashes(trim($_POST['emp_no']));
	$full_name = trim($_POST['full_name']);
	$dept = trim($_POST['dept']);
	$section = trim($_POST['section']);
	$line_no = trim($_POST['line_no']);
	$shift_group = trim($_POST['shift_group']);
	$role = trim($_POST['role']);

	$query = "SELECT id FROM m_accounts WHERE emp_no = '$emp_no' AND full_name = '$full_name' AND dept = '$dept' AND section = '$section' AND line_no = '$line_no'";
	$stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		echo 'duplicate';
	}else{
		$stmt = NULL;
		$query = "UPDATE m_accounts SET emp_no = '$emp_no', full_name = '$full_name', dept = '$dept', section = '$section', line_no = '$line_no', shift_group = '$shift_group', role = '$role' WHERE id = '$id'";
		$stmt = $conn->prepare($query);
		if ($stmt->execute()) {
			echo 'success';
		}else{
			echo 'error';
		}
	}
}

if ($method == 'delete_account') {
	$id = $_POST['id'];
	$emp_no = '';

	$query = "SELECT emp_no FROM m_accounts WHERE id = '$id'";
	$stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $row){
			$emp_no = $row['emp_no'];
		}

		$query = "DELETE FROM m_accounts WHERE id = '$id'";
		$stmt = $conn->prepare($query);
		if ($stmt->execute()) {
			$query = "DELETE FROM t_notif_line_support WHERE emp_no = '$emp_no'";
			$stmt = $conn->prepare($query);
			if ($stmt->execute()) {
				echo 'success';
			}else{
				echo 'error';
			}
		}else{
			echo 'error';
		}
	} else {
		echo 'not found';
	}
}

if ($method == 'admin_verification') {
	$emp_no = addslashes(trim($_POST['emp_no']));

	$query = "SELECT id FROM m_accounts WHERE BINARY emp_no = '$emp_no'";
	$stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		if ($_SESSION['emp_no'] == $emp_no) {
			echo 'success';
		} else {
			echo 'unmatched';
		}
	} else {
		echo 'failed';
	}
}

$conn = NULL;
?>