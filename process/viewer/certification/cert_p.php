<?php
include '../../conn.php';

$method = $_POST['method'];

if ($method == 'fetch_pro') {
	$category = $_POST['category'];
	$query = "SELECT process FROM [qualif].[dbo].[m_process] WHERE category = '$category' ORDER BY process ASC";
	$stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		echo '<option value="">Please select a process.....</option>';
		foreach ($stmt->fetchAll() as $row) {
			echo '<option>' . htmlspecialchars($row['process']) . '</option>';
		}
	} else {
		echo '<option>Please select a process.....</option>';
	}
}

function count_category($search_arr, $conn)
{
	$table_name = "";

	if ($search_arr['category'] == 'Final') {
		$table_name = "[qualif].[dbo].[t_f_process]";
	} else if ($search_arr['category'] == 'Initial') {
		$table_name = "[qualif].[dbo].[t_i_process]";
	}

	$query = "WITH LatestAuth AS (
					SELECT emp_id, auth_no, MAX(auth_year) AS latest_auth_year
					FROM $table_name
					WHERE i_status = 'Approved'
					GROUP BY emp_id, auth_no
				),
				
			RankedAuth AS (
				SELECT a.id,
				ROW_NUMBER() OVER (PARTITION BY a.emp_id, a.auth_no ORDER BY a.auth_year DESC) AS rn 
				FROM $table_name a 
				LEFT JOIN [qualif].[dbo].[t_employee_m] b ON a.emp_id = b.emp_id AND a.batch = b.batch 
				JOIN LatestAuth la ON a.emp_id = la.emp_id AND a.auth_no = la.auth_no AND a.auth_year = la.latest_auth_year 
				WHERE a.i_status = 'Approved' ";

	if (!empty($search_arr['emp_id'])) {
		$query = $query . " AND (b.emp_id = '" . $search_arr['emp_id'] . "' OR b.emp_id_old = '" . $search_arr['emp_id'] . "')";
	}
	if (!empty($search_arr['fullname'])) {
		$query = $query . " AND b.fullname LIKE'" . $search_arr['fullname'] . "%'";
	}

	if (!empty($search_arr['pro'])) {
		$query = $query . " AND a.process LIKE '" . $search_arr['pro'] . "'";
	}
	if (!empty($search_arr['date'])) {
		$query = $query . " AND a.expire_date = '" . $search_arr['date'] . "' ";
	}
	if (!empty($search_arr['date_authorized'])) {
		$query = $query . " AND a.date_authorized = '" . $search_arr['date_authorized'] . "' ";
	}

	$query .= ") SELECT COUNT(id) AS total
					FROM RankedAuth
					WHERE rn = 1";

	$stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach ($stmt->fetchALL() as $row) {
			$total = $row['total'];
		}
	} else {
		$total = 0;
	}
	return $total;
}

if ($method == 'count_category') {
	$emp_id = $_POST['emp_id'];
	$pro = $_POST['pro'];
	$category = $_POST['category'];
	$date = $_POST['date'];
	$date_authorized = $_POST['date_authorized'];
	$fullname = $_POST['fullname'];

	$search_arr = array(
		"emp_id" => $emp_id,
		"pro" => $pro,
		"category" => $category,
		"date" => $date,
		"date_authorized" => $date_authorized,
		"fullname" => $fullname
	);

	echo count_category($search_arr, $conn);
}

if ($method == 'fetch_category_pagination') {
	$emp_id = $_POST['emp_id'];
	$pro = $_POST['pro'];
	$category = $_POST['category'];
	$date = $_POST['date'];
	$date_authorized = $_POST['date_authorized'];
	$fullname = $_POST['fullname'];

	$search_arr = array(
		"emp_id" => $emp_id,
		"pro" => $pro,
		"category" => $category,
		"date" => $date,
		"date_authorized" => $date_authorized,
		"fullname" => $fullname
	);

	$results_per_page = 100;

	$number_of_result = intval(count_category($search_arr, $conn));

	//determine the total number of pages available  
	$number_of_page = ceil($number_of_result / $results_per_page);

	for ($page = 1; $page <= $number_of_page; $page++) {
		echo '<option value="' . $page . '">' . $page . '</option>';
	}
}

if ($method == 'fetch_category') {
	$emp_id = $_POST['emp_id'];
	$pro = $_POST['pro'];
	$category = $_POST['category'];
	$date = $_POST['date'];
	$date_authorized = $_POST['date_authorized'];
	$fullname = $_POST['fullname'];
	$current_page = intval($_POST['current_page']);
	$c = 0;

	if (!empty($category)) {

		$results_per_page = 100;
		$page_first_result = ($current_page - 1) * $results_per_page;

		$c = $page_first_result;

		$table_name = "";

		if ($category == 'Final') {
			$table_name = "[qualif].[dbo].[t_f_process]";
		} else if ($category == 'Initial') {
			$table_name = "[qualif].[dbo].[t_i_process]";
		}

		$query = "WITH LatestAuth AS (
						SELECT emp_id, auth_no, MAX(auth_year) AS latest_auth_year
						FROM $table_name
						WHERE i_status = 'Approved'
						GROUP BY emp_id, auth_no
					),
		
				RankedAuth AS (
					SELECT DISTINCT emp.line_no, emp.section, 
						 a.batch, a.process, a.auth_no, a.auth_year, a.date_authorized, a.expire_date, 
                         a.r_of_cancellation, a.d_of_cancellation, a.remarks, a.i_status, a.r_status, 
                         b.fullname, b.agency, a.dept, b.emp_id, 
						 ROW_NUMBER() OVER (PARTITION BY a.emp_id, a.auth_no ORDER BY a.auth_year DESC) AS rn
					FROM $table_name a 
					LEFT JOIN [qualif].[dbo].[t_employee_m] b ON a.emp_id = b.emp_id AND a.batch = b.batch 
					LEFT JOIN m_employees emp ON a.emp_id=emp.emp_no 
					JOIN LatestAuth la ON a.emp_id = la.emp_id AND a.auth_no = la.auth_no AND a.auth_year = la.latest_auth_year 
                    WHERE a.i_status = 'Approved'";

		$params = [];

		if (!empty($emp_id)) {
			$query .= " AND (b.emp_id = '$emp_id' OR b.emp_id_old = '$emp_id')";
		}

		if (!empty($fullname)) {
			$query .= " AND b.fullname LIKE '$fullname%'";
		}

		if (!empty($pro)) {
			$query .= " AND a.process LIKE '$pro%'";
		}

		if (!empty($date)) {
			$query .= " AND a.expire_date = '$date'";
		}

		if (!empty($date_authorized)) {
			$query .= " AND a.date_authorized = '$date_authorized'";
		}

		$query .= " ORDER BY a.process ASC, b.fullname ASC, a.auth_year DESC 
                    OFFSET :page_first_result ROWS 
                    FETCH NEXT :results_per_page ROWS ONLY";

		$query .= ") SELECT *
					FROM RankedAuth
					WHERE rn = 1";

		$stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));

		$stmt->bindValue(':page_first_result', $page_first_result, PDO::PARAM_INT);
		$stmt->bindValue(':results_per_page', $results_per_page, PDO::PARAM_INT);

		$stmt->execute();

		// Check if rows are returned
		if ($stmt->rowCount() > 0) {
			foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
				$c++;
				$row_class = ($row['r_status'] == 'Approved') ? " bg-danger" : "";

				echo '<tr>';
				echo '<td>' . $c . '</td>';
				echo '<td>' . htmlspecialchars($row['process']) . '</td>';
				echo '<td>' . htmlspecialchars($row['auth_no']) . '</td>';
				echo '<td>' . htmlspecialchars($row['auth_year']) . '</td>';
				echo '<td>' . htmlspecialchars($row['date_authorized']) . '</td>';
				echo '<td>' . htmlspecialchars($row['expire_date']) . '</td>';
				echo '<td>' . htmlspecialchars($row['fullname']) . '</td>';
				echo '<td>' . htmlspecialchars($row['emp_id']) . '</td>';
				echo '<td>' . htmlspecialchars($row['batch']) . '</td>';
				echo '<td>' . htmlspecialchars($row['dept']) . '</td>';
				echo '<td>' . htmlspecialchars($row['section']) . '</td>';
				echo '<td>' . htmlspecialchars($row['line_no']) . '</td>';
				echo '<td>' . htmlspecialchars($row['remarks']) . '</td>';
				if ($row['r_status'] == 'Approved') {
					echo '<td>' . htmlspecialchars($row['r_of_cancellation']) . '</td>';
					echo '<td>' . htmlspecialchars($row['d_of_cancellation']) . '</td>';
				} else {
					echo '<td></td>';
					echo '<td></td>';
				}
				echo '</tr>';
			}
		} else {
			echo '<tr>';
			echo '<td style="text-align:center;" colspan="4">No Result</td>';
			echo '</tr>';
		}
	} else {
		echo '<script>alert("Please select category and process");</script>';
	}
}