<?php 
session_name("emp_mgt");
session_start();

include '../../conn.php';

$method = $_POST['method'];

// Attendances

function count_attendance_list($search_arr, $conn) {
	$sql = "SELECT count(emp_no) AS total 
		FROM m_employees
		WHERE shift_group = '".$search_arr['shift_group']."'";
	if (!empty($search_arr['dept'])) {
		$sql = $sql . " AND dept = '".$search_arr['dept']."'";
	} else {
		$sql = $sql . " AND dept != ''";
	}
	if (!empty($search_arr['section'])) {
		$sql = $sql . " AND section LIKE '".$search_arr['section']."%'";
	}
	if (!empty($search_arr['line_no'])) {
		$sql = $sql . " AND line_no LIKE '".$search_arr['line_no']."%'";
	}
	$sql = $sql . " AND (resigned_date IS NULL OR resigned_date = '0000-00-00' OR resigned_date >= '".$search_arr['day']."')";
	
	$stmt = $conn->prepare($sql);
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

function count_emp_tio($search_arr, $conn) {
	$sql = "SELECT count(emp.emp_no) AS total FROM m_employees emp
			LEFT JOIN t_time_in_out tio ON tio.emp_no = emp.emp_no
			WHERE tio.day = '".$search_arr['day']."' AND emp.shift_group = '".$search_arr['shift_group']."'";
	if (!empty($search_arr['dept'])) {
		$sql = $sql . " AND emp.dept = '".$search_arr['dept']."'";
	} else {
		$sql = $sql . " AND emp.dept != ''";
	}
	if (!empty($search_arr['section'])) {
		$sql = $sql . " AND emp.section LIKE '".$search_arr['section']."%'";
	}
	if (!empty($search_arr['line_no'])) {
		$sql = $sql . " AND emp.line_no LIKE '".$search_arr['line_no']."%'";
	}
	$sql = $sql . " AND (emp.resigned_date IS NULL OR emp.resigned_date = '0000-00-00' OR emp.resigned_date >= '".$search_arr['day']."')";
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $j){
			$total = intval($j['total']);
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
		$dept = '';
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
		$dept = '';
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
		$dept = '';
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
		$dept = '';
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
		$sql = $sql . " AND emp.dept = '$dept'";
	} else {
		$sql = $sql . " AND emp.dept != ''";
	}
	if (!empty($section)) {
		$sql = $sql . " AND emp.section LIKE '$section%'";
	}
	if (!empty($line_no)) {
		$sql = $sql . " AND emp.line_no LIKE '$line_no%'";
	}
	$sql = $sql . " AND (emp.resigned_date IS NULL OR emp.resigned_date = '0000-00-00' OR emp.resigned_date >= '$day')";
	$sql = $sql . " ORDER BY emp.emp_no ASC";

	$sql = $sql . " LIMIT ".$page_first_result.", ".$results_per_page;

	$stmt = $conn->prepare($sql);
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $j){
			$c++;

			if (!empty($j['time_in'])) {
				$row_class = $row_class_arr[1];
				echo '<tr class="'.$row_class.'">';
			} else {
				$row_class = $row_class_arr[2];
				$row_day = '';
				$row_shift = '';
				if (!empty($j['absent_day']) && !empty($j['absent_shift_group'])) {
					$row_day = $j['absent_day'];
					$row_shift_group = $j['absent_shift_group'];
				} else {
					$row_day = $day;
					$row_shift_group = $shift_group;
				}
				
				echo '<tr style="cursor:pointer;" class="'.$row_class.'" data-toggle="modal" data-target="#absence_details" onclick="get_absence_details(&quot;'.$j['absent_id'].'~!~'.$j['emp_no'].'~!~'.$j['full_name'].'~!~'.$row_day.'~!~'.$row_shift_group.'~!~'.$j['absent_type'].'~!~'.$j['reason'].'&quot;)">';
			}

			echo '<td>'.$c.'</td>';
			if (!empty($j['time_in'])) {
				echo '<td>'.$j['time_in_day'].'</td>';
				echo '<td>'.$j['time_in_shift'].'</td>';
				echo '<td>'.$j['shift_group'].'</td>';
			} else {
				echo '<td>'.$j['absent_day'].'</td>';
				echo '<td></td>';
				echo '<td>'.$j['absent_shift_group'].'</td>';
			}
			echo '<td>'.$j['provider'].'</td>';
			echo '<td>'.$j['emp_no'].'</td>';
			echo '<td>'.$j['full_name'].'</td>';
			echo '<td>'.$j['dept'].'</td>';
			echo '<td>'.$j['section'].'</td>';
			echo '<td>'.$j['line_no'].'</td>';
			echo '<td>'.$j['absent_type'].'</td>';
			$reason = $j['reason'];
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

if ($method == 'save_absence_details') {
	$id = $_POST['id'];
	$emp_no = trim($_POST['emp_no']);
	$absent_day = trim($_POST['absent_day']);
	$absent_shift_group = trim($_POST['absent_shift_group']);
	$absent_type = trim($_POST['absent_type']);
	$reason = trim($_POST['reason']);

	if (empty($id)) {
		$sql = "INSERT INTO t_absences (`emp_no`, `day`, `shift_group`, `absent_type`, `reason`) VALUES ('$emp_no', '$absent_day', '$absent_shift_group', '$absent_type', '$reason')";
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

$conn = NULL;
?>