<?php 
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

include '../../conn.php';

$method = $_POST['method'];

// Shifting

if ($method == 'set_line_shifting') {
	if (!isset($_SESSION['dept'])) {
		echo 'Session Expired. Please re-login your account.';
		$conn = null;
		exit();
	}

	if (empty($_POST['line_no'])) {
		echo 'Empty Line No. Please select Line No. Again';
		$conn = null;
		exit();
	}

	$dept = $_SESSION['dept'];
	$section = $_SESSION['section'];
	$line_no = $_POST['line_no'];
	$shift = $_POST['shift'];
	$shift_group = $_POST['shift_group'];
	$schedule_date_draft = $_POST['schedule_date'];

	$schedule_date = date('Y-m-d', strtotime($schedule_date_draft)) . " 06:00:00";

	// $query = "UPDATE m_employees SET shift = ? WHERE shift_group = ?, dept = ? AND section = ?";

	// $params = [
	// 	$shift, 
	// 	$shift_group, 
	// 	$dept, 
	// 	$section
	// ];

	// if ($line_no != 'All') {
	// 	$query .= " AND line_no = ?";
	// 	$params[] = $line_no;
	// }

	// $stmt = $conn->prepare($query);

	// if ($stmt->execute($params)) {
	// 	echo 'success';
	// } else {
	// 	echo 'error';
	// }

	$query = "INSERT INTO t_line_shifting (dept, section, line_no, shift, shift_group, schedule_date) 
				VALUES (?, ?, ?, ?, ?, ?)";

	$params = [
		$dept, 
		$section,
		$line_no,
		$shift, 
		$shift_group, 
		$schedule_date
	];

	$stmt = $conn->prepare($query);

	if ($stmt->execute($params)) {
		echo 'success';
	} else {
		echo 'error';
	}
}

function count_line_shifting_schedule_list($search_arr, $conn) {
	$query = "SELECT count(id) AS total 
				FROM t_line_shifting 
				WHERE dept = ? AND section = ?";
	$params = [
		$search_arr['dept'],
		$search_arr['section']
	];

	if (!empty($search_arr['shift'])) {
		$query = $query . " AND shift = ?";
		$params[] = $search_arr['shift'];
	}

	if (!empty($search_arr['shift_group'])) {
		$query = $query . " AND shift_group = ?";
		$params[] = $search_arr['shift_group'];
	}

	if (!empty($search_arr['line_no'])) {
		$query = $query . " AND line_no LIKE ?";
		$line_no_search = $search_arr['line_no'] . "%";
		$params[] = $line_no_search;
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

if ($method == 'count_line_shifting_schedule_list') {
	if (!isset($_SESSION['dept'])) {
		echo 'Session Expired. Please re-login your account.';
		$conn = null;
		exit();
	}

	$dept = $_SESSION['dept'];
	$section = $_SESSION['section'];
	$line_no = $_POST['line_no'];
	$shift_group = $_POST['shift_group'];
	$shift = $_POST['shift'];

	$search_arr = array(
		"dept" => $dept,
		"section" => $section,
		"line_no" => $line_no,
		"shift_group" => $shift_group,
		"shift" => $shift,
	);

	echo count_line_shifting_schedule_list($search_arr, $conn);
}

if ($method == 'line_shifting_schedule_list_last_page') {
	if (!isset($_SESSION['dept'])) {
		echo 'Session Expired. Please re-login your account.';
		$conn = null;
		exit();
	}

	$dept = $_SESSION['dept'];
	$section = $_SESSION['section'];
	$line_no = $_POST['line_no'];
	$shift_group = $_POST['shift_group'];
	$shift = $_POST['shift'];

	$search_arr = array(
		"dept" => $dept,
		"section" => $section,
		"line_no" => $line_no,
		"shift_group" => $shift_group,
		"shift" => $shift,
	);

	$results_per_page = 20;

	$number_of_result = intval(count_line_shifting_schedule_list($search_arr, $conn));

	//determine the total number of pages available  
	$number_of_page = ceil($number_of_result / $results_per_page);

	echo $number_of_page;
}

if ($method == 'line_shifting_schedule_list') {
	if (!isset($_SESSION['dept'])) {
		echo 'Session Expired. Please re-login your account.';
		$conn = null;
		exit();
	}

	$dept = $_SESSION['dept'];
	$section = $_SESSION['section'];
	$line_no = $_POST['line_no'];
	$shift_group = $_POST['shift_group'];
	$shift = $_POST['shift'];

	$current_page = intval($_POST['current_page']);
	$c = 0;

	$results_per_page = 20;

	//determine the sql LIMIT starting number for the results on the displaying page
	$page_first_result = ($current_page-1) * $results_per_page;

	$c = $page_first_result;

	$query = "SELECT 
					id, dept, section, line_no, shift_group, shift, schedule_date, is_reflected, 
					CASE 
						WHEN GETDATE() > schedule_date THEN 'reflected'
						WHEN ABS(DATEDIFF(SECOND, schedule_date, GETDATE())) <= 600 THEN 'yes' 
						ELSE 'no' 
					END AS is_near_10_mins
				FROM t_line_shifting 
				WHERE dept = ? AND section = ?";

	$params = [
		$dept, 
		$section 
	];

	if (!empty($shift_group)) {
		$query = $query . " AND shift_group = ?";
		$params[] = $shift_group;
	}

	if (!empty($shift)) {
		$query = $query . " AND shift = ?";
		$params[] = $shift;
	}

	if (!empty($line_no)) {
		$query = $query . " AND line_no LIKE ?";
		$line_no_search = $line_no . "%";
		$params[] = $line_no_search;
	}

	$query = $query . " ORDER BY id ASC";
	$query = $query . " OFFSET ".$page_first_result." ROWS FETCH NEXT ".$results_per_page." ROWS ONLY";

	$stmt = $conn->prepare($query);
	$stmt->execute($params);

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($row) {
		do {
			$c++;

			$row_class = "";

			if ($row['is_reflected'] == 1) {
				$row_class = "bg-success";
			}

			echo '<tr class="'.$row_class.'">';

			echo '<td>'.$c.'</td>';
			echo '<td>'.$row['schedule_date'].'</td>';
			echo '<td>'.$row['dept'].'</td>';
			echo '<td>'.$row['section'].'</td>';
			echo '<td>'.$row['line_no'].'</td>';
			echo '<td>'.$row['shift_group'].'</td>';
			echo '<td>'.$row['shift'].'</td>';

			if ($row['is_reflected'] == 1) {
				// Success Reflection
				echo '<td><center><i class="fas fa-check"></i></center></td>';
			} else if ($row['is_near_10_mins'] == 'reflected') {
				// Fail Reflection
				echo '<td><center><i class="fas fa-times"></i></center></td>';
			} else if ($row['is_near_10_mins'] == 'yes') {
				echo '<td><center><i class="fas fa-sync"></i></center></td>';
			} else {
				echo '<td><center><i class="fas fa-trash" style="cursor:pointer;" data-id="'.$row['id'].'" onclick="delete_line_shifting_schedule(this);"></i></center></td>';
			}

			echo '</tr>';
		} while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
	} else {
		echo '<tr>';
			echo '<td colspan="8" style="text-align:center; color:red;">No Result !!!</td>';
		echo '</tr>';
	}
}

if ($method == 'delete_line_shifting_schedule') {
	$id = $_POST['id'];

	$query = "DELETE FROM t_line_shifting WHERE id = ?";

	$stmt = $conn->prepare($query);
	$params = array($id);

	if ($stmt->execute($params)) {
		echo 'success';
	} else {
		echo 'error';
	}
}

$conn = NULL;
