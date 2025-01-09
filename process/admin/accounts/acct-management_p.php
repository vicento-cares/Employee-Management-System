<?php 
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

include '../../conn.php';

$method = $_POST['method'];

// Account Management

function count_account_list($search_arr, $conn) {
	$query = "SELECT COUNT(id) AS total FROM m_accounts WHERE";
	$params = [];

	if (!empty($search_arr['emp_no'])) {
		$query = $query . " emp_no LIKE ?";
		$emp_no_search = $search_arr['emp_no'] . "%";
		$params[] = $emp_no_search;
	} else {
		$query = $query . " emp_no != ''";
	}

	if (!empty($search_arr['full_name'])) {
		$query = $query . " AND full_name LIKE ?";
		$full_name_search = $search_arr['full_name'] . "%";
		$params[] = $full_name_search;
	}

	if (!empty($search_arr['dept'])) {
		$query = $query . " AND dept = ?";
		$params[] = $search_arr['dept'];
	}
	if (!empty($search_arr['section'])) {
		$query = $query . " AND section LIKE ?";
		$section_search = $search_arr['section'] . "%";
		$params[] = $section_search;
	}
	if (!empty($search_arr['line_no'])) {
		$query = $query . " AND line_no LIKE ?";
		$line_no_search = $search_arr['line_no'] . "%";
		$params[] = $line_no_search;
	}

	if (!empty($search_arr['role'])) {
		$query = $query . " AND role = ?";
		$params[] = $search_arr['role'];
	}
	
	$stmt = $conn->prepare($query);
	$stmt->execute($params);

	$row = $stmt -> fetch(PDO::FETCH_ASSOC);

	if ($row) {
		$total = $row['total'];
	} else {
		$total = 0;
	}

	return $total;
}

if ($method == 'count_account_list') {
	$emp_no = $_POST['emp_no'];
	$full_name = $_POST['full_name'];

	if (!isset($_POST['dept'])) {
		$dept = '';
	} else {
		$dept = $_POST['dept'];
	}

	if (!isset($_POST['section'])) {
		$section = '';
	} else {
		$section = $_POST['section'];
	}

	if (!isset($_POST['line_no'])) {
		$line_no = '';
	} else {
		$line_no = $_POST['line_no'];
	}

	$role = $_POST['role'];
	
	$search_arr = array(
		"emp_no" => $emp_no,
		"full_name" => $full_name,
		"dept" => $dept,
		"section" => $section,
		"line_no" => $line_no,
		"role" => $role
	);

	echo count_account_list($search_arr, $conn);
}

if ($method == 'account_list_last_page') {
	$emp_no = $_POST['emp_no'];
	$full_name = $_POST['full_name'];

	if (!isset($_POST['dept'])) {
		$dept = '';
	} else {
		$dept = $_POST['dept'];
	}

	if (!isset($_POST['section'])) {
		$section = '';
	} else {
		$section = $_POST['section'];
	}

	if (!isset($_POST['line_no'])) {
		$line_no = '';
	} else {
		$line_no = $_POST['line_no'];
	}

	$role = $_POST['role'];

	$search_arr = array(
		"emp_no" => $emp_no,
		"full_name" => $full_name,
		"dept" => $dept,
		"section" => $section,
		"line_no" => $line_no,
		"role" => $role
	);

	$results_per_page = 20;

	$number_of_result = intval(count_account_list($search_arr, $conn));

	//determine the total number of pages available  
	$number_of_page = ceil($number_of_result / $results_per_page);

	echo $number_of_page;
}

if ($method == 'account_list') {
	$emp_no = $_POST['emp_no'];
	$full_name = $_POST['full_name'];

	if (!isset($_POST['dept'])) {
		$dept = '';
	} else {
		$dept = $_POST['dept'];
	}

	if (!isset($_POST['section'])) {
		$section = '';
	} else {
		$section = $_POST['section'];
	}

	if (!isset($_POST['line_no'])) {
		$line_no = '';
	} else {
		$line_no = $_POST['line_no'];
	}

	$role = $_POST['role'];

	$current_page = intval($_POST['current_page']);
	$c = 0;

	$results_per_page = 20;

	//determine the sql LIMIT starting number for the results on the displaying page
	$page_first_result = ($current_page-1) * $results_per_page;

	$c = $page_first_result;

	$query = "SELECT id, emp_no, full_name, dept, section, line_no, shift_group, role FROM m_accounts WHERE";

	$params = [];

	if (!empty($emp_no)) {
		$query = $query . " emp_no LIKE ?";
		$emp_no_search = $emp_no . "%";
		$params[] = $emp_no_search;
	} else {
		$query = $query . " emp_no != ''";
	}

	if (!empty($full_name)) {
		$query = $query . " AND full_name LIKE ?";
		$full_name_search = $full_name . "%";
		$params[] = $full_name_search;
	}

	if (!empty($dept)) {
		$query = $query . " AND dept = ?";
		$params[] = $dept;
	}
	if (!empty($section)) {
		$query = $query . " AND section LIKE ?";
		$section_search = $section . "%";
		$params[] = $section_search;
	}
	if (!empty($line_no)) {
		$query = $query . " AND line_no LIKE ?";
		$line_no_search = $line_no . "%";
		$params[] = $line_no_search;
	}

	if (!empty($role)) {
		$query = $query . " AND role = ?";
		$params[] = $role;
	}

	// MySQL Query
	// $query = $query . " LIMIT ".$page_first_result.", ".$results_per_page;

	// MS SQL Server Query
	$query = $query . " ORDER BY id ASC";
	$query = $query . " OFFSET ".$page_first_result." ROWS FETCH NEXT ".$results_per_page." ROWS ONLY";

	$stmt = $conn->prepare($query);
	$stmt->execute($params);

	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

	if (count($results) > 0) {
		foreach($results as $row){
			$c++;

			if (isset($_SESSION['emp_no_hr'])) {
				echo '<tr>';

				echo '<td><p class="mb-0"><label class="mb-0"><input type="checkbox" class="singleCheck" value="'.$row['id'].'" onclick="get_checked_length()" /><span></span></label></p></td>';

				echo '<td style="cursor:pointer;" class="modal-trigger" data-toggle="modal" data-target="#update_account" onclick="get_accounts_details(&quot;'.$row['id'].'~!~'.$row['emp_no'].'~!~'.$row['full_name'].'~!~'.$row['dept'].'~!~'.$row['section'].'~!~'.$row['line_no'].'~!~'.$row['role'].'~!~'.$row['shift_group'].'&quot;)">'.$c.'</td>';
			} else {
				echo '<tr style="cursor:pointer;" class="modal-trigger" data-toggle="modal" data-target="#update_account" onclick="get_accounts_details(&quot;'.$row['id'].'~!~'.$row['emp_no'].'~!~'.$row['full_name'].'~!~'.$row['dept'].'~!~'.$row['section'].'~!~'.$row['line_no'].'~!~'.$row['role'].'~!~'.$row['shift_group'].'&quot;)">';

				echo '<td>'.$c.'</td>';
			}

				echo '<td>'.$row['emp_no'].'</td>';
				echo '<td>'.$row['full_name'].'</td>';
				echo '<td>'.$row['dept'].'</td>';
				echo '<td>'.$row['section'].'</td>';
				echo '<td>'.$row['line_no'].'</td>';
				echo '<td>'.$row['shift_group'].'</td>';
				echo '<td>'.strtoupper($row['role']).'</td>';
			echo '</tr>';
		}
	}else{
		echo '<tr>';
			echo '<td colspan="9" style="text-align:center; color:red;">No Result !!!</td>';
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

	$query = "SELECT id FROM m_accounts WHERE emp_no = '$emp_no' COLLATE SQL_Latin1_General_CP1_CS_AS";
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