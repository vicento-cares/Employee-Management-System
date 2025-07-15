<?php 
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

include '../../conn.php';

$method = $_POST['method'];

// Shifting

if ($method == 'set_line_shifting') {
	if (isset($_SESSION['dept'])) {
		$dept = $_SESSION['dept'];
		$section = $_SESSION['section'];
		$line_no = $_POST['line_no'];
		$shift = $_POST['shift'];
		$shift_group = $_POST['shift_group'];
		$schedule_date = $_POST['schedule_date'];

		if (!empty($line_no)) {
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
		} else {
			echo 'Empty Line No. Please select Line No. Again';
		}
	} else {
		echo 'Session Expired. Please re-login your account.';
	}
}

$conn = NULL;
