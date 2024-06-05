<?php 
session_name("emp_mgt");
session_start();

include '../../conn.php';

$method = $_POST['method'];

// Employee Masterlist

function count_employee_list($search_arr, $conn) {
	$query = "SELECT count(id) AS total FROM m_employees WHERE";
	if (!empty($search_arr['emp_no'])) {
		$query = $query . " emp_no LIKE '".$search_arr['emp_no']."%'";
	} else {
		$query = $query . " emp_no != ''";
	}
	if (!empty($search_arr['full_name'])) {
		$query = $query . " AND full_name LIKE '".$search_arr['full_name']."%'";
	}
	if (!empty($search_arr['provider'])) {
		$query = $query . " AND provider = '".$search_arr['provider']."'";
	}
	if (isset($_SESSION['emp_no'])) {
		/*if (isset($_SESSION['dept']) && !empty($_SESSION['dept'])) {
			$query = $query . " AND dept = '".$_SESSION['dept']."'";
		} else {
			$query = $query . " AND dept IS NULL";
		}
		if (isset($_SESSION['section']) && !empty($_SESSION['section'])) {
			$query = $query . " AND section = '".$_SESSION['section']."'";
		} else {
			$query = $query . " AND section IS NULL";
		}
		if (isset($_SESSION['line_no']) && !empty($_SESSION['line_no'])) {
			$query = $query . " AND line_no = '".$_SESSION['line_no']."'";
		} else {
			$query = $query . " AND line_no IS NULL";
		}*/

		if (!empty($search_arr['dept'])) {
			$query = $query . " AND dept = '".$search_arr['dept']."'";
		}
		if (!empty($search_arr['section'])) {
			$query = $query . " AND section LIKE '".$search_arr['section']."%'";
		}
		if (!empty($search_arr['line_no'])) {
			$query = $query . " AND line_no LIKE '".$search_arr['line_no']."%'";
		}

		/*$query = $query . " AND dept = '".$_SESSION['dept']."' AND section = '".$_SESSION['section']."' AND line_no = '".$_SESSION['line_no']."'";*/
	} else {
		if (!empty($search_arr['dept'])) {
			$query = $query . " AND dept = '".$search_arr['dept']."'";
		}
		if (!empty($search_arr['section'])) {
			$query = $query . " AND section LIKE '".$search_arr['section']."%'";
		}
		if (!empty($search_arr['line_no'])) {
			$query = $query . " AND line_no LIKE '".$search_arr['line_no']."%'";
		}
	}

	if (!empty($search_arr['date_updated_from']) && !empty($search_arr['date_updated_to'])) {
		$query = $query . " AND date_updated BETWEEN '".$search_arr['date_updated_from']."' AND '".$search_arr['date_updated_to']."'";
	}

	if ($search_arr['resigned'] != '') {
		$query = $query . " AND resigned = '".$search_arr['resigned']."'";
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

// Get Dept Dropdown
if ($method == 'fetch_dept_dropdown') {
	$sql = "SELECT `dept` FROM `m_dept` ORDER BY dept ASC";
	$stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt -> execute();
	if ($stmt -> rowCount() > 0) {
		echo '<option selected value="">Select Department</option>';
		echo '<option value="">All</option>';
		foreach($stmt -> fetchAll() as $row) {
			echo '<option value="'.htmlspecialchars($row['dept']).'">'.htmlspecialchars($row['dept']).'</option>';
		}
	} else {
		echo '<option disabled selected value="">Select Department</option>';
	}
}

// Get Group Dropdown
if ($method == 'fetch_group_dropdown') {
	$sql = "SELECT `falp_group` FROM `m_falp_groups` ORDER BY falp_group ASC";
	$stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt -> execute();
	if ($stmt -> rowCount() > 0) {
		echo '<option selected value="">Select Group</option>';
		foreach($stmt -> fetchAll() as $row) {
			echo '<option value="'.htmlspecialchars($row['falp_group']).'">'.htmlspecialchars($row['falp_group']).'</option>';
		}
	} else {
		echo '<option disabled selected value="">Select Group</option>';
	}
}

// Get Section Dropdown
if ($method == 'fetch_section_dropdown') {
	$sql = "SELECT `section` FROM `m_access_locations` GROUP BY section ORDER BY section ASC";
	$stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt -> execute();
	if ($stmt -> rowCount() > 0) {
		echo '<option selected value="">Select Section</option>';
		echo '<option value="">All</option>';
		foreach($stmt -> fetchAll() as $row) {
			echo '<option value="'.htmlspecialchars($row['section']).'">'.htmlspecialchars($row['section']).'</option>';
		}
	} else {
		echo '<option disabled selected value="">Select Section</option>';
	}
}

// Get Sub Section Dropdown
if ($method == 'fetch_sub_section_dropdown') {
	$sql = "SELECT `sub_section` FROM `m_sub_sections` ORDER BY sub_section ASC";
	$stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt -> execute();
	if ($stmt -> rowCount() > 0) {
		echo '<option selected value="">Select Sub Section</option>';
		foreach($stmt -> fetchAll() as $row) {
			echo '<option value="'.htmlspecialchars($row['sub_section']).'">'.htmlspecialchars($row['sub_section']).'</option>';
		}
	} else {
		echo '<option disabled selected value="">Select Sub Section</option>';
	}
}

// Get Line Datalist
if ($method == 'fetch_line_dropdown') {
	$section = addslashes($_POST['section']);
	$sql = "SELECT `line_no` FROM `m_access_locations`";
	if (!empty($section)) {
		if ($section != 'QA') {
			$sql = $sql . " WHERE section = '$section'";
		}
	}
	$sql = $sql . " GROUP BY line_no ORDER BY line_no ASC";
	$stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt -> execute();
	if ($stmt -> rowCount() > 0) {
		echo '<option selected value="">Select Line No.</option>';
		echo '<option value="">All</option>';
		foreach($stmt -> fetchAll() as $row) {
			echo '<option value="'.htmlspecialchars($row['line_no']).'">'.htmlspecialchars($row['line_no']).'</option>';
		}
	} else {
		echo '<option disabled selected value="">Select Line No.</option>';
	}
}

// Get Position Dropdown
if ($method == 'fetch_position_dropdown') {
	$sql = "SELECT `position` FROM `m_positions` ORDER BY position ASC";
	$stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt -> execute();
	if ($stmt -> rowCount() > 0) {
		echo '<option selected value="">Select Position</option>';
		foreach($stmt -> fetchAll() as $row) {
			echo '<option value="'.htmlspecialchars($row['position']).'">'.htmlspecialchars($row['position']).'</option>';
		}
	} else {
		echo '<option disabled selected value="">Select Position</option>';
	}
}

// Get Position Dropdown
if ($method == 'fetch_process_dropdown') {
	$sql = "SELECT `process` FROM `m_process` ORDER BY process ASC";
	$stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt -> execute();
	if ($stmt -> rowCount() > 0) {
		echo '<option selected value="">Select Process</option>';
		foreach($stmt -> fetchAll() as $row) {
			echo '<option value="'.htmlspecialchars($row['process']).'">'.htmlspecialchars($row['process']).'</option>';
		}
	} else {
		echo '<option disabled selected value="">Select Process</option>';
	}
}

// Get Provider Dropdown
if ($method == 'fetch_provider_dropdown') {
	$sql = "SELECT `provider` FROM `m_providers` ORDER BY provider ASC";
	$stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt -> execute();
	if ($stmt -> rowCount() > 0) {
		echo '<option selected value="">Select Provider</option>';
		foreach($stmt -> fetchAll() as $row) {
			echo '<option value="'.htmlspecialchars($row['provider']).'">'.htmlspecialchars($row['provider']).'</option>';
		}
	} else {
		echo '<option disabled selected value="">Select Provider</option>';
	}
}

// Get Employee Name Jr. Staff or Staff Dropdown
if ($method == 'fetch_employee_name_js_s_dropdown') {
	$dept = trim($_POST['dept']);
	$section = trim($_POST['section']);
	$line_no = trim($_POST['line_no']);

	$sql = "SELECT `emp_no`, `full_name` FROM `m_employees` WHERE `position` IN ('Jr. Staff', 'Staff') AND resigned = 0";
	if (!empty($dept)) {
		$sql = $sql . " AND dept = '$dept'";
	}
	if (!empty($section)) {
		$sql = $sql . " AND section LIKE '$section%'";
	}
	if (!empty($line_no)) {
		$sql = $sql . " AND line_no LIKE '$line_no%'";
	}
	$sql = $sql . " ORDER BY full_name ASC";
	$stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt -> execute();
	if ($stmt -> rowCount() > 0) {
		echo '<option selected value="">Select Name</option>';
		foreach($stmt -> fetchAll() as $row) {
			echo '<option value="'.htmlspecialchars($row['emp_no']).'">'.htmlspecialchars($row['full_name']).'</option>';
		}
	} else {
		echo '<option disabled selected value="">Select Name</option>';
	}
}

// Get Employee Name Supervisor Dropdown
if ($method == 'fetch_employee_name_sv_dropdown') {
	$dept = trim($_POST['dept']);
	$section = trim($_POST['section']);
	$line_no = trim($_POST['line_no']);

	$sql = "SELECT `emp_no`, `full_name` FROM `m_employees` WHERE `position` = 'Supervisor' AND resigned = 0";
	if (!empty($dept)) {
		$sql = $sql . " AND dept = '$dept'";
	}
	if (!empty($section)) {
		$sql = $sql . " AND section LIKE '$section%'";
	}
	if (!empty($line_no)) {
		$sql = $sql . " AND line_no LIKE '$line_no%'";
	}
	$sql = $sql . " ORDER BY full_name ASC";
	$stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt -> execute();
	if ($stmt -> rowCount() > 0) {
		echo '<option selected value="">Select Name</option>';
		foreach($stmt -> fetchAll() as $row) {
			echo '<option value="'.htmlspecialchars($row['emp_no']).'">'.htmlspecialchars($row['full_name']).'</option>';
		}
	} else {
		echo '<option disabled selected value="">Select Name</option>';
	}
}

// Get Employee Name Dropdown
if ($method == 'fetch_employee_name_approver_dropdown') {
	$dept = trim($_POST['dept']);
	$section = trim($_POST['section']);
	$line_no = trim($_POST['line_no']);

	$sql = "SELECT `emp_no`, `full_name` FROM `m_employees` WHERE `position` IN ('Assistant Manager', 'Section Manager', 'Manager') AND resigned = 0";
	if (!empty($dept)) {
		$sql = $sql . " AND dept = '$dept'";
	}
	if (!empty($section)) {
		$sql = $sql . " AND section LIKE '$section%'";
	}
	if (!empty($line_no)) {
		$sql = $sql . " AND line_no LIKE '$line_no%'";
	}
	$sql = $sql . " ORDER BY full_name ASC";
	$stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt -> execute();
	if ($stmt -> rowCount() > 0) {
		echo '<option disabled selected value="">Select Name</option>';
		foreach($stmt -> fetchAll() as $row) {
			echo '<option value="'.htmlspecialchars($row['emp_no']).'">'.htmlspecialchars($row['full_name']).'</option>';
		}
	} else {
		echo '<option disabled selected value="">Select Name</option>';
	}
}

if ($method == 'count_employee_list') {
	$emp_no = addslashes($_POST['emp_no']);
	$full_name = addslashes($_POST['full_name']);
	$provider = $_POST['provider'];

	$date_updated_from = '';
	if (isset($_POST['date_updated_from'])) {
		$date_updated_from = $_POST['date_updated_from'];
	}
	if (!empty($date_updated_from)) {
		$date_updated_from = date_create($date_updated_from);
		$date_updated_from = date_format($date_updated_from,"Y-m-d H:i:s");
	}

	$date_updated_to = '';
	if (isset($_POST['date_updated_to'])) {
		$date_updated_to = $_POST['date_updated_to'];
	}
	if (!empty($date_updated_to)) {
		$date_updated_to = date_create($date_updated_to);
		$date_updated_to = date_format($date_updated_to,"Y-m-d H:i:s");
	}

	if (!isset($_POST['dept'])) {
		$dept = '';
		if (isset($_SESSION['emp_no_control_area'])) {
			$dept = $_SESSION['dept'];
		}
	} else {
		$dept = $_POST['dept'];
	}

	if (!isset($_POST['section'])) {
		$section = '';
		if (isset($_SESSION['emp_no_control_area'])) {
			$section = $_SESSION['section'];
		}
	} else {
		$section = addslashes($_POST['section']);
	}

	if (!isset($_POST['line_no'])) {
		$line_no = '';
	} else {
		$line_no = addslashes($_POST['line_no']);
	}

	if (!isset($_POST['resigned'])) {
		$resigned = '';
	} else {
		$resigned = $_POST['resigned'];
	}
	
	$search_arr = array(
		"emp_no" => $emp_no,
		"full_name" => $full_name,
		"provider" => $provider,
		"dept" => $dept,
		"section" => $section,
		"line_no" => $line_no,
		"date_updated_from" => $date_updated_from,
		"date_updated_to" => $date_updated_to,
		"resigned" => $resigned
	);

	echo count_employee_list($search_arr, $conn);
}

if ($method == 'employee_list_last_page') {
	$emp_no = addslashes($_POST['emp_no']);
	$full_name = addslashes($_POST['full_name']);
	$provider = $_POST['provider'];

	$date_updated_from = '';
	if (isset($_POST['date_updated_from'])) {
		$date_updated_from = $_POST['date_updated_from'];
	}
	if (!empty($date_updated_from)) {
		$date_updated_from = date_create($date_updated_from);
		$date_updated_from = date_format($date_updated_from,"Y-m-d H:i:s");
	}

	$date_updated_to = '';
	if (isset($_POST['date_updated_to'])) {
		$date_updated_to = $_POST['date_updated_to'];
	}
	if (!empty($date_updated_to)) {
		$date_updated_to = date_create($date_updated_to);
		$date_updated_to = date_format($date_updated_to,"Y-m-d H:i:s");
	}

	if (!isset($_POST['dept'])) {
		$dept = '';
		if (isset($_SESSION['emp_no_control_area'])) {
			$dept = $_SESSION['dept'];
		}
	} else {
		$dept = $_POST['dept'];
	}

	if (!isset($_POST['section'])) {
		$section = '';
		if (isset($_SESSION['emp_no_control_area'])) {
			$section = $_SESSION['section'];
		}
	} else {
		$section = addslashes($_POST['section']);
	}

	if (!isset($_POST['line_no'])) {
		$line_no = '';
	} else {
		$line_no = addslashes($_POST['line_no']);
	}

	if (!isset($_POST['resigned'])) {
		$resigned = '';
	} else {
		$resigned = $_POST['resigned'];
	}

	$search_arr = array(
		"emp_no" => $emp_no,
		"full_name" => $full_name,
		"provider" => $provider,
		"dept" => $dept,
		"section" => $section,
		"line_no" => $line_no,
		"date_updated_from" => $date_updated_from,
		"date_updated_to" => $date_updated_to,
		"resigned" => $resigned
	);

	$results_per_page = 20;

	$number_of_result = intval(count_employee_list($search_arr, $conn));

	//determine the total number of pages available  
	$number_of_page = ceil($number_of_result / $results_per_page);

	echo $number_of_page;

}

if ($method == 'employee_list') {
	$emp_no = addslashes($_POST['emp_no']);
	$full_name = addslashes($_POST['full_name']);
	$provider = $_POST['provider'];

	$date_updated_from = '';
	if (isset($_POST['date_updated_from'])) {
		$date_updated_from = $_POST['date_updated_from'];
	}
	if (!empty($date_updated_from)) {
		$date_updated_from = date_create($date_updated_from);
		$date_updated_from = date_format($date_updated_from,"Y-m-d H:i:s");
	}

	$date_updated_to = '';
	if (isset($_POST['date_updated_to'])) {
		$date_updated_to = $_POST['date_updated_to'];
	}
	if (!empty($date_updated_to)) {
		$date_updated_to = date_create($date_updated_to);
		$date_updated_to = date_format($date_updated_to,"Y-m-d H:i:s");
	}
	
	if (!isset($_POST['dept'])) {
		$dept = '';
		if (isset($_SESSION['emp_no_control_area'])) {
			$dept = $_SESSION['dept'];
		}
	} else {
		$dept = $_POST['dept'];
	}

	if (!isset($_POST['section'])) {
		$section = '';
		if (isset($_SESSION['emp_no_control_area'])) {
			$section = $_SESSION['section'];
		}
	} else {
		$section = addslashes($_POST['section']);
	}

	if (!isset($_POST['line_no'])) {
		$line_no = '';
	} else {
		$line_no = addslashes($_POST['line_no']);
	}

	if (!isset($_POST['resigned'])) {
		$resigned = '';
	} else {
		$resigned = $_POST['resigned'];
	}

	$current_page = intval($_POST['current_page']);
	$c = 0;

	$results_per_page = 20;

	//determine the sql LIMIT starting number for the results on the displaying page
	$page_first_result = ($current_page-1) * $results_per_page;

	$c = $page_first_result;

	$query = "SELECT id, emp_no, full_name, dept, section, sub_section, line_no, process, position, provider, gender, shift_group, date_hired, address, contact_no, emp_status, shuttle_route, emp_js_s_no, emp_sv_no, emp_approver_no, resigned, resigned_date FROM m_employees WHERE";
	if (!empty($emp_no)) {
		$query = $query . " emp_no LIKE '".$emp_no."%'";
	} else {
		$query = $query . " emp_no != ''";
	}
	if (!empty($full_name)) {
		$query = $query . " AND full_name LIKE '$full_name%'";
	}
	if (!empty($provider)) {
		$query = $query . " AND provider = '$provider'";
	}
	if (isset($_SESSION['emp_no'])) {
		/*if (isset($_SESSION['dept']) && !empty($_SESSION['dept'])) {
			$query = $query . " AND dept = '".$_SESSION['dept']."'";
		} else {
			$query = $query . " AND dept IS NULL";
		}
		if (isset($_SESSION['section']) && !empty($_SESSION['section'])) {
			$query = $query . " AND section = '".$_SESSION['section']."'";
		} else {
			$query = $query . " AND section IS NULL";
		}
		if (isset($_SESSION['line_no']) && !empty($_SESSION['line_no'])) {
			$query = $query . " AND line_no = '".$_SESSION['line_no']."'";
		} else {
			$query = $query . " AND line_no IS NULL";
		}*/

		if (!empty($dept)) {
			$query = $query . " AND dept = '$dept'";
		}
		if (!empty($section)) {
			$query = $query . " AND section LIKE '$section%'";
		}
		if (!empty($line_no)) {
			$query = $query . " AND line_no LIKE '$line_no%'";
		}

		/*$query = $query . " AND dept = '".$_SESSION['dept']."' AND section = '".$_SESSION['section']."' AND line_no = '".$_SESSION['line_no']."'";*/
	} else {
		if (!empty($dept)) {
			$query = $query . " AND dept = '$dept'";
		}
		if (!empty($section)) {
			$query = $query . " AND section LIKE '$section%'";
		}
		if (!empty($line_no)) {
			$query = $query . " AND line_no LIKE '$line_no%'";
		}
	}

	if (!empty($date_updated_from) && !empty($date_updated_to)) {
		$query = $query . " AND date_updated BETWEEN '$date_updated_from' AND '$date_updated_to'";
	}

	if ($resigned != '') {
		$query = $query . " AND resigned = '$resigned'";
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
			
			if (isset($_SESSION['emp_no']) || isset($_SESSION['emp_no_control_area'])) {
				echo '<tr style="cursor:pointer;" class="modal-trigger" data-toggle="modal" data-target="#update_employee" onclick="get_employees_details(&quot;'.$j['id'].'~!~'.$j['emp_no'].'~!~'.$j['full_name'].'~!~'.$j['dept'].'~!~'.$j['section'].'~!~'.$j['line_no'].'~!~'.$j['position'].'~!~'.$j['provider'].'~!~'.$j['date_hired'].'~!~'.$j['address'].'~!~'.$j['contact_no'].'~!~'.$j['emp_status'].'~!~'.$j['shuttle_route'].'~!~'.$j['emp_js_s_no'].'~!~'.$j['emp_sv_no'].'~!~'.$j['emp_approver_no'].'~!~'.$j['resigned'].'~!~'.$j['resigned_date'].'~!~'.$j['gender'].'~!~'.$j['shift_group'].'~!~'.$j['process'].'~!~'.$j['section'].'~!~'.$j['sub_section'].'&quot;)">';

				echo '<td >'.$c.'</td>';
			} else {
				echo '<tr>';

				echo '<td><p class="mb-0"><label class="mb-0"><input type="checkbox" class="singleCheck" value="'.$j['id'].'" onclick="get_checked_length()" /><span></span></label></p></td>';

				echo '<td style="cursor:pointer;" class="modal-trigger" data-toggle="modal" data-target="#update_employee" onclick="get_employees_details(&quot;'.$j['id'].'~!~'.$j['emp_no'].'~!~'.$j['full_name'].'~!~'.$j['dept'].'~!~'.$j['section'].'~!~'.$j['line_no'].'~!~'.$j['position'].'~!~'.$j['provider'].'~!~'.$j['date_hired'].'~!~'.$j['address'].'~!~'.$j['contact_no'].'~!~'.$j['emp_status'].'~!~'.$j['shuttle_route'].'~!~'.$j['emp_js_s_no'].'~!~'.$j['emp_sv_no'].'~!~'.$j['emp_approver_no'].'~!~'.$j['resigned'].'~!~'.$j['resigned_date'].'~!~'.$j['gender'].'~!~'.$j['shift_group'].'~!~'.$j['process'].'~!~'.$j['section'].'~!~'.$j['sub_section'].'&quot;)">'.$c.'</td>';
			}

				echo '<td>'.$j['emp_no'].'</td>';
				echo '<td>'.$j['full_name'].'</td>';
				echo '<td>'.$j['dept'].'</td>';
				echo '<td>'.$j['section'].'</td>';
				echo '<td>'.$j['line_no'].'</td>';
				echo '<td>'.$j['provider'].'</td>';
				echo '<td>'.$j['shuttle_route'].'</td>';
			echo '</tr>';
		}
	}else{
		$colspan = 0;
		if (isset($_SESSION['emp_no']) || isset($_SESSION['emp_no_control_area'])) {
			$colspan = 8;
		} else {
			$colspan = 9;
		} 
		echo '<tr>';
			echo '<td colspan="'.$colspan.'" style="text-align:center; color:red;">No Result !!!</td>';
		echo '</tr>';
	}
}

if ($method == 'get_employee_data') {
	$emp_no = addslashes($_POST['emp_no']);
	$response_arr = array();
	$full_name = '';
	$dept = '';
	$section = '';
	$line_no = '';
	$position = '';
	$shift_group = '';
	$date_hired = '';
	$address = '';
	$contact_no = '';
	$emp_status = '';
	$resigned = 0;
	$role = '';
	$message = '';

	$query = "SELECT emp_no, full_name, dept, section, line_no, position, shift_group, date_hired, address, contact_no, emp_status, resigned FROM m_employees WHERE emp_no = '$emp_no'";
	$stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $j){
			$emp_no = $j['emp_no'];
			$full_name = $j['full_name'];
			$dept = $j['dept'];
			$section = $j['section'];
			$line_no = $j['line_no'];
			$position = $j['position'];
			$shift_group = $j['shift_group'];
			$date_hired = $j['date_hired'];
			$address = $j['address'];
			$contact_no = $j['contact_no'];
			$emp_status = $j['emp_status'];
			$resigned = intval($j['resigned']);
		}
		if ($resigned == 0) {
			$message = 'success';
		} else {
			$message = 'Not Found';
		}
	} else {
		$message = 'Not Found';
	}

	if (empty($position) || $position == 'Associate' || $position == 'Jr. Staff') {
		$role = 'user';
	} else {
		$role = 'admin';
	}

	$response_arr = array(
		'emp_no' => $emp_no,
		'full_name' => $full_name,
		'dept' => $dept,
		'section' => $section,
		'line_no' => $line_no,
		'position' => $position,
		'shift_group' => $shift_group,
		'date_hired' => $date_hired,
		'address' => $address,
		'contact_no' => $contact_no,
		'emp_status' => $emp_status,
		'role' => $role,
		'message' => $message
	);

	//header('Content-Type: application/json; charset=utf-8');
    echo json_encode($response_arr, JSON_FORCE_OBJECT);
}

if ($method == 'register_employee') {
	$full_name = addslashes(trim($_POST['full_name']));
	$emp_no = addslashes(trim($_POST['emp_no']));
	$dept = trim($_POST['dept']);
	$group = trim($_POST['group']);
	$section = trim($_POST['section']);
	$sub_section = trim($_POST['sub_section']);
	$process = trim($_POST['line_process']);
	$line_no = trim($_POST['line_no']);
	$position = trim($_POST['position']);
	$date_hired = trim($_POST['date_hired']);
	$provider = trim($_POST['provider']);
	$shift_group = trim($_POST['shift_group']);
	$address = addslashes(trim($_POST['address']));
	$contact_no = addslashes(trim($_POST['contact_no']));
	$emp_status = trim($_POST['emp_status']);
	$shuttle_route = trim($_POST['shuttle_route']);
	$gender = trim($_POST['gender']);
	$emp_js_s_no = trim($_POST['emp_js_s_no']);
	$emp_sv_no = trim($_POST['emp_sv_no']);
	$emp_approver_no = trim($_POST['emp_approver_no']);
	$emp_js_s = trim($_POST['emp_js_s']);
	$emp_sv = trim($_POST['emp_sv']);
	$emp_approver = trim($_POST['emp_approver']);

	$check = "SELECT id FROM m_employees WHERE emp_no = '$emp_no'";
	$stmt = $conn->prepare($check, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		echo 'Already Exist';
	}else{
		$stmt = NULL;

		$query = "INSERT INTO m_employees (`emp_no`, `full_name`, `dept`, `section`, `sub_section`, `process`, `line_no`, `position`, `provider`, `gender`, `shift_group`, `date_hired`, `address`, `contact_no`, `emp_status`, `shuttle_route`, `emp_js_s`, `emp_sv`, `emp_approver`, `emp_js_s_no`, `emp_sv_no`, `emp_approver_no`) VALUES ('$emp_no','$full_name'";

		if (!empty($dept)) {
			$query = $query . ",'$dept'";
		} else {
			$query = $query . ", 'Undefined'";
		}
		if (!empty($section)) {
			$query = $query . ",'$section'";
		} else {
			$query = $query . ", 'Undefined'";
		}
		if (!empty($sub_section)) {
			$query = $query . ",'$sub_section'";
		} else {
			$query = $query . ", 'Undefined'";
		}
		if (!empty($process)) {
			$query = $query . ",'$process'";
		} else {
			$query = $query . ", 'Undefined'";
		}
		if (!empty($line_no)) {
			$query = $query . ",'$line_no'";
		} else {
			$query = $query . ", 'Undefined'";
		}

		$query = $query . ",'$position','$provider','$gender','$shift_group','$date_hired','$address','$contact_no','$emp_status','$shuttle_route','$emp_js_s','$emp_sv','$emp_approver','$emp_js_s_no','$emp_sv_no','$emp_approver_no')";

		$stmt = $conn->prepare($query);
		if ($stmt->execute()) {
			echo 'success';
		}else{
			echo 'error';
		}
	}
}

if ($method == 'update_employee') {
	$id = $_POST['id'];
	$emp_no = addslashes(trim($_POST['emp_no']));
	$full_name = addslashes(trim($_POST['full_name']));
	$dept = trim($_POST['dept']);
	$group = trim($_POST['group']);
	$section = trim($_POST['section']);
	$sub_section = trim($_POST['sub_section']);
	$process = trim($_POST['line_process']);
	$line_no = trim($_POST['line_no']);
	$position = trim($_POST['position']);
	$date_hired = trim($_POST['date_hired']);
	$provider = trim($_POST['provider']);
	$shift_group = trim($_POST['shift_group']);
	$address = addslashes(trim($_POST['address']));
	$contact_no = addslashes(trim($_POST['contact_no']));
	$emp_status = trim($_POST['emp_status']);
	$shuttle_route = trim($_POST['shuttle_route']);
	$gender = trim($_POST['gender']);
	$emp_js_s_no = trim($_POST['emp_js_s_no']);
	$emp_sv_no = trim($_POST['emp_sv_no']);
	$emp_approver_no = trim($_POST['emp_approver_no']);
	$emp_js_s = trim($_POST['emp_js_s']);
	$emp_sv = trim($_POST['emp_sv']);
	$emp_approver = trim($_POST['emp_approver']);
	$resigned = intval($_POST['resigned']);
	$resigned_date = trim($_POST['resigned_date']);

	$query = "UPDATE m_employees SET emp_no = '$emp_no', full_name = '$full_name'";
	
	if (!empty($dept)) {
		$query = $query . ", dept = '$dept'";
	} else {
		$query = $query . ", dept = 'Undefined'";
	}
	if (!empty($section)) {
		$query = $query . ", section = '$section'";
	} else {
		$query = $query . ", section = 'Undefined'";
	}
	if (!empty($sub_section)) {
		$query = $query . ", sub_section = '$sub_section'";
	} else {
		$query = $query . ", sub_section = 'Undefined'";
	}
	if (!empty($process)) {
		$query = $query . ", process = '$process'";
	} else {
		$query = $query . ", process = 'Undefined'";
	}
	if (!empty($line_no)) {
		$query = $query . ", line_no = '$line_no'";
	} else {
		$query = $query . ", line_no = 'Undefined'";
	}

	$query = $query . ", position = '$position', provider = '$provider', gender = '$gender', shift_group = '$shift_group', date_hired = '$date_hired', address = '$address', contact_no = '$contact_no', emp_status = '$emp_status', shuttle_route = '$shuttle_route', emp_js_s = '$emp_js_s', emp_sv = '$emp_sv', emp_approver = '$emp_approver', emp_js_s_no = '$emp_js_s_no', emp_sv_no = '$emp_sv_no', emp_approver_no = '$emp_approver_no', resigned = '$resigned', resigned_date = '$resigned_date' WHERE id = '$id'";

	$stmt = $conn->prepare($query);
	if ($stmt->execute()) {
		$query = "UPDATE m_control_area_accounts SET";
	
		if (!empty($dept)) {
			$query = $query . " dept = '$dept'";
		} else {
			$query = $query . " dept = ''";
		}
		if (!empty($section)) {
			$query = $query . ", section = '$section'";
		} else {
			$query = $query . ", section = NULL";
		}
		if (!empty($line_no)) {
			$query = $query . ", line_no = '$line_no'";
		} else {
			$query = $query . ", line_no = NULL";
		}
		if (!empty($shift_group)) {
			$query = $query . ", shift_group = '$shift_group'";
		} else {
			$query = $query . ", shift_group = NULL";
		}

		$query = $query . " WHERE emp_no = '$emp_no'";
		$stmt = $conn->prepare($query);

		if ($stmt->execute()) {
			$query = "UPDATE m_accounts SET";
	
			if (!empty($dept)) {
				$query = $query . " dept = '$dept'";
			} else {
				$query = $query . " dept = ''";
			}
			if (!empty($section)) {
				$query = $query . ", section = '$section'";
			} else {
				$query = $query . ", section = NULL";
			}
			if (!empty($line_no)) {
				$query = $query . ", line_no = '$line_no'";
			} else {
				$query = $query . ", line_no = NULL";
			}
			if (!empty($shift_group)) {
				$query = $query . ", shift_group = '$shift_group'";
			} else {
				$query = $query . ", shift_group = NULL";
			}

			$query = $query . " WHERE emp_no = '$emp_no'";
			$stmt = $conn->prepare($query);

			if ($stmt->execute()) {
				echo 'success';
			} else {
				echo 'error';
			}
		} else {
			echo 'error';
		}
	}else{
		echo 'error';
	}
}

if ($method == 'delete_employee') {
	$id = $_POST['id'];

	$query = "DELETE FROM m_employees WHERE id = '$id'";
	$stmt = $conn->prepare($query);
	if ($stmt->execute()) {
		echo 'success';
	}else{
		echo 'error';
	}
}

$conn = NULL;
?>