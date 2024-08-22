<?php 
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

include '../../conn.php';

$method = $_POST['method'];

// Access Locations

function count_access_location_list($search_arr, $conn) {
	$query = "SELECT count(id) AS total FROM m_access_locations WHERE";

	if (!empty($search_arr['line_no'])) {
		$query = $query . " line_no LIKE '".$search_arr['line_no']."%'";
	} else {
		$query = $query . " line_no != ''";
	}

	if (!empty($search_arr['section'])) {
		$query = $query . " AND section LIKE '".$search_arr['section']."%'";
	}
	
	if (!empty($search_arr['ip'])) {
		$query = $query . " AND ip LIKE '".$search_arr['ip']."%'";
	}

	$stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $row){
			$total = $row['total'];
		}
	}else{
		$total = 0;
	}
	return $total;
}

if ($method == 'count_access_location_list') {
	$line_no = addslashes($_POST['line_no']);
	$section = addslashes($_POST['section']);
	$ip = addslashes($_POST['ip']);

	$search_arr = array(
		"line_no" => $line_no,
		"section" => $section,
		"ip" => $ip
	);

	echo count_access_location_list($search_arr, $conn);
}

if ($method == 'access_location_list_last_page') {
	$line_no = addslashes($_POST['line_no']);
	$section = addslashes($_POST['section']);
	$ip = addslashes($_POST['ip']);

	$search_arr = array(
		"line_no" => $line_no,
		"section" => $section,
		"ip" => $ip
	);

	$results_per_page = 20;

	$number_of_result = intval(count_access_location_list($search_arr, $conn));

	//determine the total number of pages available  
	$number_of_page = ceil($number_of_result / $results_per_page);

	echo $number_of_page;
}

if ($method == 'access_location_list') {
	$line_no = addslashes($_POST['line_no']);
	$section = addslashes($_POST['section']);
	$ip = addslashes($_POST['ip']);

	$current_page = intval($_POST['current_page']);
	$c = 0;

	$results_per_page = 20;

	//determine the sql LIMIT starting number for the results on the displaying page
	$page_first_result = ($current_page-1) * $results_per_page;

	$c = $page_first_result;

	$query = "SELECT id, dept, section, line_no, ip, date_updated FROM m_access_locations WHERE";

	if (!empty($line_no)) {
		$query = $query . " line_no LIKE '$line_no%'";
	} else {
		$query = $query . " line_no != ''";
	}

	if (!empty($section)) {
		$query = $query . " AND section LIKE '$section%'";
	}

	if (!empty($ip)) {
		$query = $query . " AND ip LIKE '$ip%'";
	}

	// MySQL Query
	// $query = $query . " LIMIT ".$page_first_result.", ".$results_per_page;

	// MS SQL Server Query
	$query = $query . " ORDER BY id ASC";
	$query = $query . " OFFSET ".$page_first_result." ROWS FETCH NEXT ".$results_per_page." ROWS ONLY";

	$stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $row){
			$c++;

			echo '<tr style="cursor:pointer;" class="modal-trigger" data-toggle="modal" data-target="#update_access_location" onclick="get_access_location_details(&quot;'.$row['id'].'~!~'.$row['dept'].'~!~'.$row['section'].'~!~'.$row['line_no'].'~!~'.$row['ip'].'&quot;)">';
			echo '<td>'.$c.'</td>';
			echo '<td>'.$row['dept'].'</td>';
			echo '<td>'.$row['section'].'</td>';
			echo '<td>'.$row['line_no'].'</td>';
			echo '<td>'.$row['ip'].'</td>';
			echo '<td>'.$row['date_updated'].'</td>';
			echo '</tr>';
		}
	}else{
		echo '<tr>';
		echo '<td colspan="6" style="text-align:center; color:red;">No Result !!!</td>';
		echo '</tr>';
	}
}

if ($method == 'add_access_location') {
	$dept = trim($_POST['dept']);
	$section = trim($_POST['section']);
	$line_no = trim($_POST['line_no']);
	$ip = trim($_POST['ip']);

	$check = "SELECT id FROM m_access_locations WHERE line_no = '$line_no'";
	$stmt = $conn->prepare($check, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		echo 'Already Exist';
	} else {
		$stmt = NULL;
		$query = "INSERT INTO m_access_locations (dept, section, line_no, ip) VALUES ('$dept','$section','$line_no','$ip')";
		$stmt = $conn->prepare($query);
		if ($stmt->execute()) {
			echo 'success';
		} else {
			echo 'error';
		}
	}
}

if ($method == 'update_access_location') {
	$id = $_POST['id'];
	$dept = trim($_POST['dept']);
	$section = trim($_POST['section']);
	$line_no = trim($_POST['line_no']);
	$ip = trim($_POST['ip']);

	$query = "SELECT id FROM m_access_locations WHERE dept = '$dept' AND section = '$section' AND line_no = '$line_no' AND ip = '$ip'";
	$stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		echo 'duplicate';
	} else {
		$stmt = NULL;
		$query = "UPDATE m_access_locations SET dept = '$dept', section = '$section', line_no = '$line_no', ip = '$ip' WHERE id = '$id'";
		$stmt = $conn->prepare($query);
		if ($stmt->execute()) {
			echo 'success';
		} else {
			echo 'error';
		}
	}
}

if ($method == 'delete_access_location') {
	$id = $_POST['id'];

	$query = "DELETE FROM m_access_locations WHERE id = '$id'";
	$stmt = $conn->prepare($query);
	if ($stmt->execute()) {
		echo 'success';
	} else {
		echo 'error';
	}
}

$conn = NULL;
?>