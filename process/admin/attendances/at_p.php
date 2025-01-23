<?php 
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

include '../../conn.php';

$method = $_POST['method'];

// Attendances

function count_line_support_to($search_arr, $conn) {
	$sql = "SELECT count(lsh.emp_no) AS total 
		FROM t_line_support_history lsh
		LEFT JOIN m_employees emp ON emp.emp_no = lsh.emp_no
		WHERE lsh.day = '".$search_arr['day']."' AND lsh.line_no_to LIKE '".$search_arr['line_no']."%' AND lsh.status = 'accepted'";

	$sql = $sql . " AND emp.shift_group = '".$search_arr['shift_group']."'";

	if (!empty($search_arr['dept'])) {
		$sql = $sql . " AND emp.dept = '".$search_arr['dept']."'";
	} else {
		$sql = $sql . " AND emp.dept != ''";
	}

	if (!empty($search_arr['section'])) {
		// Check line number first char if it is numeric (Final Process)
		// If not (Initial Process)
		$line_number_first_char = substr($search_arr['line_no'], 0, 1);
		if (!is_numeric($line_number_first_char)) {
			$sql = $sql . " AND emp.section LIKE '".$search_arr['section']."%'";
		}
	}

	$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $row){
			$total = intval($row['total']);
		}
	}else{
		$total = 0;
	}
	return $total;
}

function count_line_support_from($search_arr, $conn) {
	$sql = "SELECT count(lsh.emp_no) AS total 
		FROM t_line_support_history lsh
		LEFT JOIN m_employees emp ON emp.emp_no = lsh.emp_no
		WHERE lsh.day = '".$search_arr['day']."' AND lsh.line_no_from LIKE '".$search_arr['line_no']."%' AND lsh.status = 'accepted'";

	$sql = $sql . " AND emp.shift_group = '".$search_arr['shift_group']."'";

	if (!empty($search_arr['dept'])) {
		$sql = $sql . " AND emp.dept = '".$search_arr['dept']."'";
	} else {
		$sql = $sql . " AND emp.dept != ''";
	}

	if (!empty($search_arr['section'])) {
		// Check line number first char if it is numeric (Final Process)
		// If not (Initial Process)
		$line_number_first_char = substr($search_arr['line_no'], 0, 1);
		if (!is_numeric($line_number_first_char)) {
			$sql = $sql . " AND emp.section LIKE '".$search_arr['section']."%'";
		}
	}

	// if ($search_arr['line_no'] == 'No Line') {
	// 	$sql = $sql . " AND emp.line_no IS NULL";
	// } else if (!empty($search_arr['line_no'])) {
	// 	$sql = $sql . " AND emp.line_no LIKE '".$search_arr['line_no']."%'";
	// } else {
	// 	$sql = $sql . " AND (emp.line_no = '' OR emp.line_no IS NULL)";
	// }

	$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $row){
			$total = intval($row['total']);
		}
	}else{
		$total = 0;
	}
	return $total;
}

function count_line_support_from_rejected($search_arr, $conn) {
	$sql = "SELECT count(lsh.emp_no) AS total 
		FROM t_line_support_history lsh
		LEFT JOIN m_employees emp ON emp.emp_no = lsh.emp_no
		WHERE lsh.day = '".$search_arr['day']."' AND lsh.line_no_from LIKE '".$search_arr['line_no']."%' AND lsh.status = 'rejected'";

	$sql = $sql . " AND emp.shift_group = '".$search_arr['shift_group']."'";

	if (!empty($search_arr['dept'])) {
		$sql = $sql . " AND emp.dept = '".$search_arr['dept']."'";
	} else {
		$sql = $sql . " AND emp.dept != ''";
	}
	
	if (!empty($search_arr['section'])) {
		// Check line number first char if it is numeric (Final Process)
		// If not (Initial Process)
		$line_number_first_char = substr($search_arr['line_no'], 0, 1);
		if (!is_numeric($line_number_first_char)) {
			$sql = $sql . " AND emp.section LIKE '".$search_arr['section']."%'";
		}
	}

	$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $row){
			$total = intval($row['total']);
		}
	}else{
		$total = 0;
	}
	return $total;
}

function get_attendance_list_line_support_to($search_arr, $conn) {
	$table_data = "";

	$c = 0;

	$sql = "SELECT 
	emp.provider, emp.emp_no, emp.full_name, emp.dept, emp.section, emp.process, emp.line_no, emp.shift_group, emp.resigned_date,
	tio.time_in, tio.time_out, tio.day AS time_in_day, tio.shift AS time_in_shift
		FROM m_employees emp
		LEFT JOIN t_time_in_out tio ON tio.emp_no = emp.emp_no AND tio.day = '".$search_arr['day']."'
		LEFT JOIN t_line_support_history lsh ON lsh.emp_no = emp.emp_no AND lsh.day = '".$search_arr['day']."'
		WHERE emp.shift_group = '".$search_arr['shift_group']."'";

	if (!empty($search_arr['dept'])) {
		$sql = $sql . " AND emp.dept LIKE '".$search_arr['dept']."%'";
	} else {
		$sql = $sql . " AND emp.dept != ''";
	}

	$sql = $sql . " AND lsh.line_no_to LIKE '".$search_arr['line_no']."%' AND lsh.status = 'accepted'
		AND (emp.resigned_date IS NULL OR emp.resigned_date >= '".$search_arr['day']."')
		ORDER BY emp.full_name ASC";

	$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $row){
			$c++;

			$table_data .= '<tr class="modal-trigger bg-warning">';
			$table_data .= '<td>'.$c.'</td>';
			if (!empty($row['time_in'])) {
				$table_data .= '<td>'.$row['time_in_day'].'</td>';
				$table_data .= '<td>'.$row['time_in_shift'].'</td>';
				$table_data .= '<td>'.$row['shift_group'].'</td>';
			} else {
				$table_data .= '<td>'.$search_arr['day'].'</td>';
				$table_data .= '<td></td>';
				$table_data .= '<td>'.$row['shift_group'].'</td>';
			}
			$table_data .= '<td>'.$row['provider'].'</td>';
			$table_data .= '<td>'.$row['emp_no'].'</td>';
			$table_data .= '<td>'.$row['full_name'].'</td>';
			$table_data .= '<td>'.$row['dept'].'</td>';
			$table_data .= '<td>'.$row['section'].'</td>';
			$table_data .= '<td>'.$row['line_no'].'</td>';
			$table_data .= '<td>'.$row['process'].'</td>';
			$table_data .= '<td>'.$row['time_in'].'</td>';
			$table_data .= '<td>'.$row['time_out'].'</td>';
			$table_data .= '<td></td>';
			$table_data .= '<td></td>';

			$table_data .= '</tr>';
		}
	}

	$response_array = array(
		"table_data" => $table_data,
		"c" => $c
	);

	return $response_array;
}

function count_attendance_list($search_arr, $conn) {
	$sql = "SELECT count(emp_no) AS total 
		FROM m_employees
		WHERE shift_group = '".$search_arr['shift_group']."'";
	if (!empty($search_arr['dept'])) {
		$sql = $sql . " AND dept LIKE '".$search_arr['dept']."%'";
	} else {
		$sql = $sql . " AND dept != ''";
	}
	if (!empty($search_arr['section'])) {
		$sql = $sql . " AND section LIKE '".$search_arr['section']."%'";
	}
	if (!empty($search_arr['line_no'])) {
		$sql = $sql . " AND line_no LIKE '".$search_arr['line_no']."%'";
	}
	$sql = $sql . " AND (resigned_date IS NULL OR resigned_date >= '".$search_arr['day']."')";
	
	$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
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

function count_attendance_list2($search_arr, $conn) {
	$sql = "SELECT count(emp_no) AS total 
		FROM m_employees
		WHERE shift_group = '".$search_arr['shift_group']."'";
	if (!empty($search_arr['dept'])) {
		$sql = $sql . " AND dept LIKE '".$search_arr['dept']."%'";
	} else {
		$sql = $sql . " AND dept != ''";
	}
	if (!empty($search_arr['section'])) {
		$sql = $sql . " AND section LIKE '".$search_arr['section']."%'";
	}
	if ($search_arr['line_no'] == 'No Line') {
		$sql = $sql . " AND line_no IS NULL";
	} else if (!empty($search_arr['line_no'])) {
		$sql = $sql . " AND line_no LIKE '".$search_arr['line_no']."%'";
	} else {
		$sql = $sql . " AND (line_no = '' OR line_no IS NULL)";
	}
	$sql = $sql . " AND (resigned_date IS NULL OR resigned_date >= '".$search_arr['day']."')";
	
	$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $row){
			$total = $row['total'];
		}
	}else{
		$total = 0;
	}

	$total += count_line_support_to($search_arr, $conn);
	// $total += count_line_support_from_rejected($search_arr, $conn);
	$total -= count_line_support_from($search_arr, $conn);

	return $total;
}

function count_emp_tio($search_arr, $conn) {
	$sql = "SELECT count(emp.emp_no) AS total FROM m_employees emp
			LEFT JOIN t_time_in_out tio ON tio.emp_no = emp.emp_no
			WHERE tio.day = '".$search_arr['day']."' AND emp.shift_group = '".$search_arr['shift_group']."'";
	if (!empty($search_arr['dept'])) {
		$sql = $sql . " AND emp.dept LIKE '".$search_arr['dept']."%'";
	} else {
		$sql = $sql . " AND emp.dept != ''";
	}
	if (!empty($search_arr['section'])) {
		$sql = $sql . " AND emp.section LIKE '".$search_arr['section']."%'";
	}
	if (!empty($search_arr['line_no'])) {
		$sql = $sql . " AND emp.line_no LIKE '".$search_arr['line_no']."%'";
	}
	$sql = $sql . " AND (emp.resigned_date IS NULL OR emp.resigned_date >= '".$search_arr['day']."')";
	$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $row){
			$total = intval($row['total']);
		}
	}else{
		$total = 0;
	}
	return $total;
}

if ($method == 'count_attendance_present') {
	$day = $_POST['day'];
	$shift_group = $_POST['shift_group'];

	if (!empty($_SESSION['emp_no_hr'])) {
		if (!empty($_POST['dept'])) {
			$dept = addslashes($_POST['dept']);
		} else {
			$dept = '';
		}
		if (!empty($_POST['section'])) {
			$section = addslashes($_POST['section']);
		} else {
			$section = '';
		}
		if (!empty($_POST['line_no'])) {
			$line_no = addslashes($_POST['line_no']);
		} else {
			$line_no = '';
		}
	} else {
		if (!empty($_POST['dept'])) {
			$dept = addslashes($_POST['dept']);
		} else {
			$dept = '';
		}
		$section = '';
		$line_no = $_SESSION['line_no'];
	}

	$search_arr = array(
		"day" => $day,
		"shift_group" => $shift_group,
		"dept" => $dept,
		"section" => $section,
		"line_no" => $line_no
	);

	echo count_emp_tio($search_arr, $conn);
}

if ($method == 'count_attendance_list') {
	$day = $_POST['day'];
	$shift_group = $_POST['shift_group'];

	if (!empty($_SESSION['emp_no_hr'])) {
		if (!empty($_POST['dept'])) {
			$dept = addslashes($_POST['dept']);
		} else {
			$dept = '';
		}
		if (!empty($_POST['section'])) {
			$section = addslashes($_POST['section']);
		} else {
			$section = '';
		}
		if (!empty($_POST['line_no'])) {
			$line_no = addslashes($_POST['line_no']);
		} else {
			$line_no = '';
		}
	} else {
		if (!empty($_POST['dept'])) {
			$dept = addslashes($_POST['dept']);
		} else {
			$dept = '';
		}
		$section = '';
		$line_no = $_SESSION['line_no'];
	}
	
	$search_arr = array(
		"day" => $day,
		"shift_group" => $shift_group,
		"dept" => $dept,
		"section" => $section,
		"line_no" => $line_no
	);

	echo count_attendance_list($search_arr, $conn);
}

if ($method == 'attendance_list_last_page') {
	$day = $_POST['day'];
	$shift_group = $_POST['shift_group'];

	if (!empty($_SESSION['emp_no_hr'])) {
		if (!empty($_POST['dept'])) {
			$dept = addslashes($_POST['dept']);
		} else {
			$dept = '';
		}
		if (!empty($_POST['section'])) {
			$section = addslashes($_POST['section']);
		} else {
			$section = '';
		}
		if (!empty($_POST['line_no'])) {
			$line_no = addslashes($_POST['line_no']);
		} else {
			$line_no = '';
		}
	} else {
		if (!empty($_POST['dept'])) {
			$dept = addslashes($_POST['dept']);
		} else {
			$dept = '';
		}
		$section = '';
		$line_no = $_SESSION['line_no'];
	}
	
	$search_arr = array(
		"day" => $day,
		"shift_group" => $shift_group,
		"dept" => $dept,
		"section" => $section,
		"line_no" => $line_no
	);

	$results_per_page = 20;

	$number_of_result = intval(count_attendance_list($search_arr, $conn));

	//determine the total number of pages available  
	$number_of_page = ceil($number_of_result / $results_per_page);

	echo $number_of_page;
}

if ($method == 'get_attendance_list') {
	$day = $_POST['day'];
	$shift_group = $_POST['shift_group'];

	if (!empty($_SESSION['emp_no_hr'])) {
		if (!empty($_POST['dept'])) {
			$dept = addslashes($_POST['dept']);
		} else {
			$dept = '';
		}
		if (!empty($_POST['section'])) {
			$section = addslashes($_POST['section']);
		} else {
			$section = '';
		}
		if (!empty($_POST['line_no'])) {
			$line_no = addslashes($_POST['line_no']);
		} else {
			$line_no = '';
		}
	} else {
		if (!empty($_POST['dept'])) {
			$dept = addslashes($_POST['dept']);
		} else {
			$dept = '';
		}
		$section = '';
		$line_no = $_SESSION['line_no'];
	}

	$current_page = intval($_POST['current_page']);
	$c = 0;
	$row_class_arr = array('modal-trigger', 'modal-trigger bg-success', 'modal-trigger bg-danger');
	$row_class = $row_class_arr[0];

	$results_per_page = 20;

	//determine the sql LIMIT starting number for the results on the displaying page
	$page_first_result = ($current_page-1) * $results_per_page;

	$c = $page_first_result;

	$sql = "SELECT 
	emp.provider, emp.emp_no, emp.full_name, emp.dept, emp.section, emp.line_no, emp.shift_group, emp.resigned_date,
	tio.time_in, tio.day AS time_in_day, tio.shift AS time_in_shift, 
	absences.id AS absent_id, absences.day AS absent_day, absences.shift_group AS absent_shift_group, absences.absent_type, absences.reason 
		FROM m_employees emp
		LEFT JOIN t_time_in_out tio ON tio.emp_no = emp.emp_no AND tio.day = '$day'
		LEFT JOIN t_absences absences ON absences.emp_no = emp.emp_no AND absences.day = '$day'
		WHERE emp.shift_group = '$shift_group'";
	if (!empty($dept)) {
		$sql = $sql . " AND emp.dept LIKE '$dept%'";
	} else {
		$sql = $sql . " AND emp.dept != ''";
	}
	if (!empty($section)) {
		$sql = $sql . " AND emp.section LIKE '$section%'";
	}
	if (!empty($line_no)) {
		$sql = $sql . " AND emp.line_no LIKE '$line_no%'";
	}
	$sql = $sql . " AND (emp.resigned_date IS NULL OR emp.resigned_date >= '$day')";
	$sql = $sql . " ORDER BY emp.emp_no ASC";

	// MySQL Query
	// $sql = $sql . " LIMIT ".$page_first_result.", ".$results_per_page;

	// MS SQL Server Query
	$sql = $sql . " OFFSET ".$page_first_result." ROWS FETCH NEXT ".$results_per_page." ROWS ONLY";

	$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $row){
			$c++;

			if (!empty($row['time_in'])) {
				$row_class = $row_class_arr[1];
				echo '<tr class="'.$row_class.'">';
			} else {
				$row_class = $row_class_arr[2];
				$row_day = '';
				$row_shift = '';
				if (!empty($row['absent_day']) && !empty($row['absent_shift_group'])) {
					$row_day = $row['absent_day'];
					$row_shift_group = $row['absent_shift_group'];
				} else {
					$row_day = $day;
					$row_shift_group = $shift_group;
				}
				
				echo '<tr style="cursor:pointer;" class="'.$row_class.'" data-toggle="modal" data-target="#absence_details" onclick="get_absence_details(&quot;'.$row['absent_id'].'~!~'.$row['emp_no'].'~!~'.$row['full_name'].'~!~'.$row_day.'~!~'.$row_shift_group.'~!~'.$row['absent_type'].'~!~'.$row['reason'].'&quot;)">';
			}

			echo '<td>'.$c.'</td>';
			if (!empty($row['time_in'])) {
				echo '<td>'.$row['time_in_day'].'</td>';
				echo '<td>'.$row['time_in_shift'].'</td>';
				echo '<td>'.$row['shift_group'].'</td>';
			} else {
				echo '<td>'.$row['absent_day'].'</td>';
				echo '<td></td>';
				echo '<td>'.$row['absent_shift_group'].'</td>';
			}
			echo '<td>'.$row['provider'].'</td>';
			echo '<td>'.$row['emp_no'].'</td>';
			echo '<td>'.$row['full_name'].'</td>';
			echo '<td>'.$row['dept'].'</td>';
			echo '<td>'.$row['section'].'</td>';
			echo '<td>'.$row['line_no'].'</td>';
			echo '<td>'.$row['absent_type'].'</td>';
			$reason = $row['reason'];
			if (strlen($reason) > 12) {
				$reason = substr($reason, 0, 12) . "...";
			}
			echo '<td>'.$reason.'</td>';

			echo '</tr>';
		}
	}else{
		echo '<tr>';
			echo '<td colspan="11" style="text-align:center; color:red;">No Result !!!</td>';
		echo '</tr>';
	}
}

if ($method == 'count_attendance_list2') {
	$day = $_POST['day'];
	$shift_group = $_POST['shift_group'];

	if (!empty($_SESSION['emp_no_hr'])) {
		if (!empty($_POST['dept'])) {
			$dept = addslashes($_POST['dept']);
		} else {
			$dept = '';
		}
		if (!empty($_POST['section'])) {
			$section = addslashes($_POST['section']);
		} else {
			$section = '';
		}
		if (!empty($_POST['line_no'])) {
			$line_no = addslashes($_POST['line_no']);
		} else {
			$line_no = '';
		}
	} else {
		if (!empty($_POST['dept'])) {
			$dept = addslashes($_POST['dept']);
		} else {
			$dept = '';
		}
		if (isset($_POST['section']) && !empty($_POST['section'])) {
			$section = addslashes($_POST['section']);
		} else {
			$section = '';
		}
		if (isset($_POST['line_no']) && !empty($_POST['line_no'])) {
			$line_no = addslashes($_POST['line_no']);
		} else {
			$line_no = '';
		}
	}
	
	$search_arr = array(
		"day" => $day,
		"shift_group" => $shift_group,
		"dept" => $dept,
		"section" => $section,
		"line_no" => $line_no
	);

	echo count_attendance_list2($search_arr, $conn);
}

if ($method == 'attendance_list_last_page2') {
	$day = $_POST['day'];
	$shift_group = $_POST['shift_group'];

	if (!empty($_SESSION['emp_no_hr'])) {
		if (!empty($_POST['dept'])) {
			$dept = addslashes($_POST['dept']);
		} else {
			$dept = '';
		}
		if (!empty($_POST['section'])) {
			$section = addslashes($_POST['section']);
		} else {
			$section = '';
		}
		if (!empty($_POST['line_no'])) {
			$line_no = addslashes($_POST['line_no']);
		} else {
			$line_no = '';
		}
	} else {
		if (!empty($_POST['dept'])) {
			$dept = addslashes($_POST['dept']);
		} else {
			$dept = '';
		}
		if (isset($_POST['section']) && !empty($_POST['section'])) {
			$section = addslashes($_POST['section']);
		} else {
			$section = '';
		}
		if (isset($_POST['line_no']) && !empty($_POST['line_no'])) {
			$line_no = addslashes($_POST['line_no']);
		} else {
			$line_no = '';
		}
	}
	
	$search_arr = array(
		"day" => $day,
		"shift_group" => $shift_group,
		"dept" => $dept,
		"section" => $section,
		"line_no" => $line_no
	);

	$results_per_page = 20;

	$number_of_result = intval(count_attendance_list2($search_arr, $conn));

	//determine the total number of pages available  
	$number_of_page = ceil($number_of_result / $results_per_page);

	echo $number_of_page;
}

if ($method == 'get_attendance_list2') {
	$day = $_POST['day'];
	$shift_group = $_POST['shift_group'];

	if (!empty($_SESSION['emp_no_hr'])) {
		if (!empty($_POST['dept'])) {
			$dept = addslashes($_POST['dept']);
		} else {
			$dept = '';
		}
		if (!empty($_POST['section'])) {
			$section = addslashes($_POST['section']);
		} else {
			$section = '';
		}
		if (!empty($_POST['line_no'])) {
			$line_no = addslashes($_POST['line_no']);
		} else {
			$line_no = '';
		}
	} else {
		if (!empty($_POST['dept'])) {
			$dept = addslashes($_POST['dept']);
		} else {
			$dept = '';
		}
		if (isset($_POST['section']) && !empty($_POST['section'])) {
			$section = addslashes($_POST['section']);
		} else {
			$section = '';
		}
		if (isset($_POST['line_no']) && !empty($_POST['line_no'])) {
			$line_no = addslashes($_POST['line_no']);
		} else {
			$line_no = '';
		}
	}

	$current_page = intval($_POST['current_page']);
	$c = 0;
	$row_class_arr = array('modal-trigger', 'modal-trigger bg-success', 'modal-trigger bg-danger');
	$row_class = $row_class_arr[0];

	$search_arr = array(
		"day" => $day,
		"shift_group" => $shift_group,
		"dept" => $dept,
		"section" => $section,
		"line_no" => $line_no
	);

	$results_per_page = 20;

	//determine the sql LIMIT starting number for the results on the displaying page
	$page_first_result = ($current_page-1) * $results_per_page;

	// Get Attendances from Line Support History
	if ($page_first_result == 0) {
		$attendance_list_line_support_to_arr = get_attendance_list_line_support_to($search_arr, $conn);
		$c += $attendance_list_line_support_to_arr["c"];
	} else {
		$c += count_line_support_to($search_arr, $conn);
	}

	$c += $page_first_result;

	$sql = "SELECT 
	emp.provider, emp.emp_no, emp.full_name, emp.dept, emp.section, emp.process, emp.skill_level, emp.line_no, emp.shift_group, emp.resigned_date,
	tio.time_in, tio.time_out, tio.day AS time_in_day, tio.shift AS time_in_shift, 
	absences.id AS absent_id, absences.day AS absent_day, absences.shift_group AS absent_shift_group, absences.absent_type, absences.reason 
		FROM m_employees emp
		LEFT JOIN t_time_in_out tio ON tio.emp_no = emp.emp_no AND tio.day = '$day'
		LEFT JOIN t_absences absences ON absences.emp_no = emp.emp_no AND absences.day = '$day'
		LEFT JOIN t_line_support_history lsh ON lsh.emp_no = emp.emp_no AND lsh.day = '$day'
		WHERE ((emp.shift_group = '$shift_group'";
	if (!empty($dept)) {
		$sql = $sql . " AND emp.dept LIKE '$dept%'";
	} else {
		$sql = $sql . " AND emp.dept != ''";
	}
	if (!empty($section)) {
		$sql = $sql . " AND emp.section LIKE '$section%'";
	}
	if ($line_no == 'No Line') {
		$sql = $sql . " AND emp.line_no IS NULL";
	} else if (!empty($line_no)) {
		$sql = $sql . " AND emp.line_no LIKE '$line_no%'";
	} else {
		$sql = $sql . " AND (emp.line_no = '' OR emp.line_no IS NULL)";
	}
	$sql = $sql . ") AND (lsh.line_no_from IS NULL OR lsh.status != 'accepted'))";
	$sql = $sql . " AND (emp.resigned_date IS NULL OR emp.resigned_date >= '$day')";
	$sql = $sql . " ORDER BY emp.full_name ASC";

	// MySQL Query
	// $sql = $sql . " LIMIT ".$page_first_result.", ".$results_per_page;

	// MS SQL Server Query
	$sql = $sql . " OFFSET ".$page_first_result." ROWS FETCH NEXT ".$results_per_page." ROWS ONLY";

	$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		if ($page_first_result == 0){
			echo $attendance_list_line_support_to_arr["table_data"];
		}

		foreach($stmt->fetchALL() as $row){
			$c++;

			if (!empty($row['time_in'])) {
				$row_class = $row_class_arr[1];
				echo '<tr class="'.$row_class.'">';
			} else {
				$row_class = $row_class_arr[2];
				$row_day = '';
				$row_shift = '';
				if (!empty($row['absent_day']) && !empty($row['absent_shift_group'])) {
					$row_day = $row['absent_day'];
					$row_shift_group = $row['absent_shift_group'];
				} else {
					$row_day = $day;
					$row_shift_group = $shift_group;
				}
				
				echo '<tr style="cursor:pointer;" class="'.$row_class.'" data-dismiss="modal" onclick="get_absence_details(&quot;'.$row['absent_id'].'~!~'.$row['emp_no'].'~!~'.$row['full_name'].'~!~'.$row_day.'~!~'.$row_shift_group.'~!~'.$row['absent_type'].'~!~'.$row['reason'].'&quot;)">';
			}

			echo '<td>'.$c.'</td>';
			if (!empty($row['time_in'])) {
				echo '<td>'.$row['time_in_day'].'</td>';
				echo '<td>'.$row['time_in_shift'].'</td>';
				echo '<td>'.$row['shift_group'].'</td>';
			} else {
				echo '<td>'.$row['absent_day'].'</td>';
				echo '<td></td>';
				echo '<td>'.$row['absent_shift_group'].'</td>';
			}
			echo '<td>'.$row['provider'].'</td>';
			echo '<td>'.$row['emp_no'].'</td>';
			echo '<td>'.$row['full_name'].'</td>';
			echo '<td>'.$row['dept'].'</td>';
			echo '<td>'.$row['section'].'</td>';
			echo '<td>'.$row['line_no'].'</td>';
			echo '<td>'.$row['process'].'</td>';
			if (!empty($row['skill_level'])) {
				echo '<td>Level '.$row['skill_level'].'</td>';
			} else {
				echo '<td>'.$row['skill_level'].'</td>';
			}
			echo '<td>'.$row['time_in'].'</td>';
			echo '<td>'.$row['time_out'].'</td>';
			echo '<td>'.$row['absent_type'].'</td>';
			$reason = $row['reason'];
			if (strlen($reason) > 12) {
				$reason = substr($reason, 0, 12) . "...";
			}
			echo '<td>'.$reason.'</td>';

			echo '</tr>';
		}
	}else{
		// echo '<tr>';
		// 	echo '<td colspan="11" style="text-align:center; color:red;">No Result !!!</td>';
		// echo '</tr>';
	}
}

if ($method == 'get_attendance_list_counting') {
	$day = $_POST['day'];
	$shift_group = $_POST['shift_group'];

	if (!empty($_SESSION['emp_no_hr'])) {
		if (!empty($_POST['dept'])) {
			$dept = $_POST['dept'];
		} else {
			$dept = '';
		}
		if (!empty($_POST['section'])) {
			$section = $_POST['section'];
		} else {
			$section = '';
		}
		if (!empty($_POST['line_no'])) {
			$line_no = $_POST['line_no'];
		} else {
			$line_no = '';
		}
	} else {
		if (!empty($_POST['dept'])) {
			$dept = $_POST['dept'];
		} else {
			$dept = '';
		}
		if (!empty($_SESSION['section'])) {
			$section = $_SESSION['section'];
		} else if (isset($_POST['section']) && !empty($_POST['section'])) {
			$section = $_POST['section'];
		} else {
			$section = '';
		}
		if (!empty($_SESSION['line_no'])) {
			$line_no = $_SESSION['line_no'];
		} else if (isset($_POST['line_no']) && !empty($_POST['line_no'])) {
			$line_no = $_POST['line_no'];
		} else {
			$line_no = '';
		}
	}

	$c = 0;
	$row_class_arr = array('modal-trigger', 'modal-trigger bg-success', 'modal-trigger bg-warning', 'modal-trigger bg-danger', 'modal-trigger bg-gray');
	$row_class = $row_class_arr[0];

	//MS SQL Server
	$sql = "SELECT 
				ISNULL(emp.process, 'No Process') AS process, 
				COUNT(emp.emp_no) AS total, 
				COUNT(tio.emp_no) AS total_present, 
				COUNT(emp.emp_no) - COUNT(tio.emp_no) AS total_absent 
			FROM 
				m_employees emp 
			LEFT JOIN 
				t_time_in_out tio ON emp.emp_no = tio.emp_no AND tio.day = ? 
			WHERE 
				emp.dept != ''";
	
	$params = [];

	$params[] = $day;

	if (!empty($shift_group)) {
		$sql = $sql . " AND emp.shift_group = ?";
		$params[] = $shift_group;
	} else {
		$sql = $sql . " AND (emp.shift_group = '' OR emp.shift_group IS NULL)";
	}
	if (!empty($dept)) {
		$sql = $sql . " AND emp.dept LIKE ?";
		$dept_search = $dept . "%";
		$params[] = $dept_search;
	}
	if (!empty($section)) {
		$sql = $sql . " AND emp.section LIKE ?";
		$section_search = $section . "%";
		$params[] = $section_search;
	}
	if (!empty($line_no)) {
		$sql = $sql . " AND emp.line_no LIKE ?";
		$line_no_search = $line_no . "%";
		$params[] = $line_no_search;
	}

	$sql = $sql . " AND 
						(emp.resigned_date IS NULL OR emp.resigned_date >= ?) 
					GROUP BY 
						emp.process";
	
	$params[] = $day;

	$stmt = $conn->prepare($sql);
	$stmt->execute($params);

	while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
		$c++;

		$total = intval($row['total']);
		$total_present = intval($row['total_present']);

		if ($row['process'] == 'No Process') {
			$row_class = $row_class_arr[4];
		} else if ($total_present == $total) {
			$row_class = $row_class_arr[1];
		} else if ($total_present < $total && $total_present > 0) {
			$row_class = $row_class_arr[2];
		} else if ($total_present < 1) {
			$row_class = $row_class_arr[3];
		} else {
			$row_class = $row_class_arr[0];
		}
		
		echo '<tr class="'.$row_class.'">';
		echo '<td>'.$c.'</td>';
		echo '<td>'.$row['process'].'</td>';
		echo '<td>'.$row['total_present'].'</td>';
		echo '<td>'.$row['total_absent'].'</td>';
		echo '<td>'.$row['total'].'</td>';

		echo '</tr>';
	}
}

if ($method == 'save_absence_details') {
	$id = $_POST['id'];
	$emp_no = trim($_POST['emp_no']);
	$absent_day = trim($_POST['absent_day']);
	$absent_shift_group = trim($_POST['absent_shift_group']);
	$absent_type = trim($_POST['absent_type']);
	$reason = trim($_POST['reason']);

	if (empty($id)) {
		$sql = "INSERT INTO t_absences (emp_no, day, shift_group, absent_type, reason) VALUES ('$emp_no', '$absent_day', '$absent_shift_group', '$absent_type', '$reason')";
		$stmt = $conn->prepare($sql);
		if ($stmt->execute()) {
			echo 'success';
		}else{
			echo 'error';
		}
	} else {
		$sql = "UPDATE t_absences SET emp_no = '$emp_no', day = '$absent_day', shift_group = '$absent_shift_group', absent_type = '$absent_type', reason = '$reason' WHERE id = '$id'";
		$stmt = $conn->prepare($sql);
		if ($stmt->execute()) {
			echo 'success';
		}else{
			echo 'error';
		}
	}
}

// Attendance Summary Report

if ($method == 'count_attendance_summary_report') {
	$day = $_POST['day'];
	$shift_group = $_POST['shift_group'];

	if (!empty($_POST['dept'])) {
		$dept = addslashes($_POST['dept']);
	} else {
		$dept = '';
	}
	if (!empty($_POST['section'])) {
		$section = addslashes($_POST['section']);
	} else {
		$section = '';
	}
	if (!empty($_POST['line_no'])) {
		$line_no = addslashes($_POST['line_no']);
	} else {
		$line_no = '';
	}
	
	$search_arr = array(
		"day" => $day,
		"shift_group" => $shift_group,
		"dept" => $dept,
		"section" => $section,
		"line_no" => $line_no
	);

	echo count_attendance_list($search_arr, $conn);
}

if ($method == 'attendance_summary_report_last_page') {
	$day = $_POST['day'];
	$shift_group = $_POST['shift_group'];

	if (!empty($_POST['dept'])) {
		$dept = addslashes($_POST['dept']);
	} else {
		$dept = '';
	}
	if (!empty($_POST['section'])) {
		$section = addslashes($_POST['section']);
	} else {
		$section = '';
	}
	if (!empty($_POST['line_no'])) {
		$line_no = addslashes($_POST['line_no']);
	} else {
		$line_no = '';
	}
	
	$search_arr = array(
		"day" => $day,
		"shift_group" => $shift_group,
		"dept" => $dept,
		"section" => $section,
		"line_no" => $line_no
	);

	$results_per_page = 20;

	$number_of_result = intval(count_attendance_list($search_arr, $conn));

	//determine the total number of pages available  
	$number_of_page = ceil($number_of_result / $results_per_page);

	echo $number_of_page;
}

if ($method == 'get_attendance_summary_report') {
	$day = $_POST['day'];
	$shift_group = $_POST['shift_group'];

	if (!empty($_POST['dept'])) {
		$dept = $_POST['dept'];
	} else {
		$dept = '';
	}
	if (!empty($_POST['section'])) {
		$section = $_POST['section'];
	} else {
		$section = '';
	}
	if (!empty($_POST['line_no'])) {
		$line_no = $_POST['line_no'];
	} else {
		$line_no = '';
	}

	$search_multiple_asr_shift_group_arr = [];
	if (isset($_POST['search_multiple_asr_shift_group_arr'])) {
		$search_multiple_asr_shift_group_arr = $_POST['search_multiple_asr_shift_group_arr'];
	}

	$search_multiple_asr_dept_arr = [];
	if (isset($_POST['search_multiple_asr_dept_arr'])) {
		$search_multiple_asr_dept_arr = $_POST['search_multiple_asr_dept_arr'];
	}

	$search_multiple_asr_section_arr = [];
	if (isset($_POST['search_multiple_asr_section_arr'])) {
		$search_multiple_asr_section_arr = $_POST['search_multiple_asr_section_arr'];
	}

	$search_multiple_asr_line_no_arr = [];
	if (isset($_POST['search_multiple_asr_line_no_arr'])) {
		$search_multiple_asr_line_no_arr = $_POST['search_multiple_asr_line_no_arr'];
	}

	$c = 0;
	$row_class_arr = array('modal-trigger', 'modal-trigger bg-success', 'modal-trigger bg-warning', 'modal-trigger bg-danger', 'modal-trigger bg-gray');
	$row_class = $row_class_arr[0];

	//MS SQL Server
	$sql = "WITH AttendanceData AS (
				SELECT 
					emp.shift_group, 
					emp.dept, 
					emp.section, 
					ISNULL(emp.line_no, 'No Line') AS line_no, 
					COUNT(emp.emp_no) AS total, 
					COUNT(tio.emp_no) AS total_present, 
					COUNT(emp.emp_no) - COUNT(tio.emp_no) AS total_absent, 
					FORMAT(CASE 
						WHEN COUNT(emp.emp_no) > 0 THEN (COUNT(tio.emp_no) * 100.0 / COUNT(emp.emp_no)) 
						ELSE 0 
					END, 'N2') AS attendance_percentage,
					0 AS table_order
				FROM 
					m_employees emp 
				LEFT JOIN 
					t_time_in_out tio ON emp.emp_no = tio.emp_no AND tio.day = ? 
				WHERE 
					dept != ''";
	
	$params = [];

	$params[] = $day;

	if (!empty($search_multiple_asr_shift_group_arr) || 
		!empty($search_multiple_asr_dept_arr) || 
		!empty($search_multiple_asr_section_arr) || 
		!empty($search_multiple_asr_line_no_arr)) {
			
		if (!empty($search_multiple_asr_shift_group_arr)) {
			// Create a placeholder string for the IDs
			$placeholders = implode(',', array_fill(0, count($search_multiple_asr_shift_group_arr), '?'));
			$sql = $sql . " AND emp.shift_group IN ($placeholders)";
			$params = array_merge($params, $search_multiple_asr_shift_group_arr); // Flatten the array
		}
		if (!empty($search_multiple_asr_dept_arr)) {
			// Create a placeholder string for the IDs
			$placeholders = implode(',', array_fill(0, count($search_multiple_asr_dept_arr), '?'));
			$sql = $sql . " AND emp.dept IN ($placeholders)";
			$params = array_merge($params, $search_multiple_asr_dept_arr); // Flatten the array
		}
		if (!empty($search_multiple_asr_section_arr)) {
			// Create a placeholder string for the IDs
			$placeholders = implode(',', array_fill(0, count($search_multiple_asr_section_arr), '?'));
			$sql = $sql . " AND emp.section IN ($placeholders)";
			$params = array_merge($params, $search_multiple_asr_section_arr); // Flatten the array
		}
		if (!empty($search_multiple_asr_line_no_arr)) {
			// Create a placeholder string for the IDs
			$placeholders = implode(',', array_fill(0, count($search_multiple_asr_line_no_arr), '?'));
			$sql = $sql . " AND emp.line_no IN ($placeholders)";
			$params = array_merge($params, $search_multiple_asr_line_no_arr); // Flatten the array
		}
	} else {
		if (!empty($shift_group)) {
			$sql = $sql . " AND emp.shift_group = ?";
			$params[] = $shift_group;
		}
		if (!empty($dept)) {
			$sql = $sql . " AND emp.dept LIKE ?";
			$dept_search = $dept . "%";
			$params[] = $dept_search;
		}
		if (!empty($section)) {
			$sql = $sql . " AND emp.section LIKE ?";
			$section_search = $section . "%";
			$params[] = $section_search;
		}
		if (!empty($line_no)) {
			$sql = $sql . " AND emp.line_no LIKE ?";
			$line_no_search = $line_no . "%";
			$params[] = $line_no_search;
		}
	}

	$sql = $sql . " AND 
						(emp.resigned_date IS NULL OR emp.resigned_date >= ?) 
					GROUP BY 
						emp.dept, emp.section, emp.line_no, emp.shift_group 
				)

				SELECT * FROM AttendanceData

				UNION ALL

				SELECT 
					'Total' AS shift_group, 
					NULL AS dept, 
					NULL AS section, 
					NULL AS line_no, 
					SUM(total) AS total, 
					SUM(total_present) AS total_present, 
					SUM(total_absent) AS total_absent, 
					FORMAT(CASE 
						WHEN SUM(total) > 0 THEN (SUM(total_present) * 100.0 / SUM(total)) 
						ELSE 0 
					END, 'N2') AS attendance_percentage,
					1 AS table_order
				FROM 
					AttendanceData
				ORDER BY 
					table_order ASC, shift_group ASC";
	
	$params[] = $day;

	$stmt = $conn->prepare($sql);
	$stmt->execute($params);

	while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
		$c_label = "";
		$total_class = "";
		if ($row['shift_group'] != 'Total') {
			$c++;
			$c_label = $c;

			$total = intval($row['total']);
			$total_present = intval($row['total_present']);
			
			if ($row['line_no'] == 'No Line') {
				$row_class = $row_class_arr[4];
			} else if ($total_present == $total) {
				$row_class = $row_class_arr[1];
			} else if ($total_present < $total && $total_present > 0) {
				$row_class = $row_class_arr[2];
			} else if ($total_present < 1) {
				$row_class = $row_class_arr[3];
			} else {
				$row_class = $row_class_arr[0];
			}
		} else {
			$row_class = "bg-black";
			$total_class = " class='text-bold'";
		}
		
		echo '<tr style="cursor:pointer;" class="'.$row_class.'" data-toggle="modal" data-target="#attendance_summary_report_details" 
				onclick="get_attendance_summary_report_details(&quot;'.$day
				.'~!~'.$row['shift_group']
				.'~!~'.$row['dept']
				.'~!~'.$row['section']
				.'~!~'.$row['line_no']
				.'~!~'.$row['total']
				.'~!~'.$row['total_present']
				.'~!~'.$row['total_absent']
				.'~!~'.$row['attendance_percentage'].'&quot;)">';
			
		echo '<td>'.$c_label.'</td>';
		echo '<td'.$total_class.'>'.$row['shift_group'].'</td>';
		echo '<td>'.$row['dept'].'</td>';
		echo '<td>'.$row['section'].'</td>';
		echo '<td>'.$row['line_no'].'</td>';
		echo '<td'.$total_class.'>'.$row['total'].'</td>';
		echo '<td'.$total_class.'>'.$row['total_present'].'</td>';
		echo '<td'.$total_class.'>'.$row['total_absent'].'</td>';
		echo '<td'.$total_class.'>'.$row['attendance_percentage'].'%</td>';

		echo '</tr>';
	}
}

if ($method == 'get_multiple_attendance_summary_report') {
	$day_from = $_POST['day_from'];
	$day_to = $_POST['day_to'];

	$search_multiple_asr_shift_group_arr = [];
	if (isset($_POST['search_multiple_asr_shift_group_arr'])) {
		$search_multiple_asr_shift_group_arr = $_POST['search_multiple_asr_shift_group_arr'];
	}

	$search_multiple_asr_dept_arr = [];
	if (isset($_POST['search_multiple_asr_dept_arr'])) {
		$search_multiple_asr_dept_arr = $_POST['search_multiple_asr_dept_arr'];
	}

	$search_multiple_asr_section_arr = [];
	if (isset($_POST['search_multiple_asr_section_arr'])) {
		$search_multiple_asr_section_arr = $_POST['search_multiple_asr_section_arr'];
	}

	$search_multiple_asr_line_no_arr = [];
	if (isset($_POST['search_multiple_asr_line_no_arr'])) {
		$search_multiple_asr_line_no_arr = $_POST['search_multiple_asr_line_no_arr'];
	}

	$c = 0;

	//MS SQL Server
	$sql = "-- Define the start and end dates
			DECLARE @StartDate DATE = ?;
			DECLARE @EndDate DATE = ?;

			-- CTE to generate a list of dates
			WITH DateRange AS (
				SELECT @StartDate AS ReportDate
				UNION ALL
				SELECT DATEADD(DAY, 1, ReportDate)
				FROM DateRange
				WHERE ReportDate < @EndDate
			)

			SELECT 
				COUNT(emp.emp_no) AS total, 
				COUNT(tio.emp_no) AS total_present, 
				COUNT(emp.emp_no) - COUNT(tio.emp_no) AS total_absent, 
				FORMAT(CASE 
					WHEN COUNT(emp.emp_no) > 0 THEN (COUNT(tio.emp_no) * 100.0 / COUNT(emp.emp_no)) 
					ELSE 0 
				END, 'N2') AS attendance_percentage,
				dr.ReportDate AS day
			FROM 
				DateRange dr
			LEFT JOIN 
				m_employees emp ON (emp.resigned_date IS NULL OR emp.resigned_date >= dr.ReportDate)
			LEFT JOIN 
				t_time_in_out tio ON emp.emp_no = tio.emp_no AND tio.day = dr.ReportDate
			WHERE 
				emp.dept != ''";
	$params = [];

	$params[] = $day_from;
	$params[] = $day_to;

	if (!empty($search_multiple_asr_shift_group_arr) || 
		!empty($search_multiple_asr_dept_arr) || 
		!empty($search_multiple_asr_section_arr) || 
		!empty($search_multiple_asr_line_no_arr)) {
			
		if (!empty($search_multiple_asr_shift_group_arr)) {
			// Create a placeholder string for the IDs
			$placeholders = implode(',', array_fill(0, count($search_multiple_asr_shift_group_arr), '?'));
			$sql = $sql . " AND emp.shift_group IN ($placeholders)";
			$params = array_merge($params, $search_multiple_asr_shift_group_arr); // Flatten the array
		}
		if (!empty($search_multiple_asr_dept_arr)) {
			// Create a placeholder string for the IDs
			$placeholders = implode(',', array_fill(0, count($search_multiple_asr_dept_arr), '?'));
			$sql = $sql . " AND emp.dept IN ($placeholders)";
			$params = array_merge($params, $search_multiple_asr_dept_arr); // Flatten the array
		}
		if (!empty($search_multiple_asr_section_arr)) {
			// Create a placeholder string for the IDs
			$placeholders = implode(',', array_fill(0, count($search_multiple_asr_section_arr), '?'));
			$sql = $sql . " AND emp.section IN ($placeholders)";
			$params = array_merge($params, $search_multiple_asr_section_arr); // Flatten the array
		}
		if (!empty($search_multiple_asr_line_no_arr)) {
			// Create a placeholder string for the IDs
			$placeholders = implode(',', array_fill(0, count($search_multiple_asr_line_no_arr), '?'));
			$sql = $sql . " AND emp.line_no IN ($placeholders)";
			$params = array_merge($params, $search_multiple_asr_line_no_arr); // Flatten the array
		}
	}

	$sql .= "GROUP BY 
				dr.ReportDate
			OPTION (MAXRECURSION 0);  -- Allow recursion to go beyond the default limit if needed";
	
	$stmt = $conn->prepare($sql);
	$stmt->execute($params);

	echo '<table id="multipleAttendanceSummaryReportTable" class="table table-sm table-head-fixed table-foot-fixed text-nowrap">
			<thead style="text-align: center;">
				<tr>
				<th>#</th>
				<th>Day</th>
				<th>Total MP</th>
				<th>Present</th>
				<th>Absent</th>
				<th>Percentage</th>
				</tr>
			</thead>
			<tbody id="multipleAttendanceSummaryReportData" style="text-align: center;">';

	while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
		$c++;
		
		echo '<tr style="cursor:pointer;"  
				onclick="set_attendance_summary_report_date(&quot;'.$row['day'].'&quot;)">';
			
		echo '<td>'.$c.'</td>';
		echo '<td>'.$row['day'].'</td>';
		echo '<td>'.$row['total'].'</td>';
		echo '<td>'.$row['total_present'].'</td>';
		echo '<td>'.$row['total_absent'].'</td>';
		echo '<td>'.$row['attendance_percentage'].'%</td>';

		echo '</tr>';
	}

	echo '</tbody></table>';
}

$conn = NULL;
