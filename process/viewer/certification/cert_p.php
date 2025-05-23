<?php
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

include '../../conn.php';

$method = $_POST['method'];

if ($method == 'fetch_pro') {
	$category = $_POST['category'];

	$query = "SELECT process FROM [qualif].[dbo].[m_process] WHERE category = ? ORDER BY process ASC";
	$params[] = $category;

	$stmt = $conn->prepare($query);
	$stmt->execute($params);

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
		echo '<option value="">Please select a process.....</option>';
		do {
			echo '<option>' . htmlspecialchars($row['process']) . '</option>';
		} while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
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
				LEFT JOIN m_employees emp ON a.emp_id=emp.emp_no 
				JOIN LatestAuth la ON a.emp_id = la.emp_id AND a.auth_no = la.auth_no AND a.auth_year = la.latest_auth_year 
				WHERE a.i_status = 'Approved' ";

	$params = [];

	if (!empty($search_arr['emp_id'])) {
		$query .= " AND (b.emp_id = ? OR b.emp_id_old = ?)";
		$params[] = $search_arr['emp_id'];
		$params[] = $search_arr['emp_id'];
	}
	if (!empty($search_arr['fullname'])) {
		$query .= " AND b.fullname LIKE ?";
		$fullname_search = $search_arr['fullname'] . "%";
		$params[] = $fullname_search;
	}

	if (!empty($search_arr['pro'])) {
		$query .= " AND a.process LIKE ?";
		$pro_search = $search_arr['pro'] . "%";
		$params[] = $pro_search;
	}
	if (!empty($search_arr['date'])) {
		$query .= " AND a.expire_date = ? ";
		$params[] = $search_arr['date'];
	}
	if (!empty($search_arr['date_authorized'])) {
		$query .= " AND a.date_authorized = ? ";
		$params[] = $search_arr['date_authorized'];
	}

	if (!empty($search_arr['dept'])) {
		$query .= " AND emp.dept LIKE ?";
		$dept_search = $search_arr['dept'] . "%";
		$params[] = $dept_search;
	}
	if (!empty($search_arr['section'])) {
		$query .= " AND emp.section LIKE ?";
		$section_search = $search_arr['section'] . "%";
		$params[] = $section_search;
	}
	if (!empty($search_arr['line_no'])) {
		$query .= " AND emp.line_no LIKE ?";
		$line_no_search = $search_arr['line_no'] . "%";
		$params[] = $line_no_search;
	}

	$query .= ") SELECT COUNT(id) AS total
					FROM RankedAuth
					WHERE rn = 1";

	$stmt = $conn->prepare($query);
	$stmt->execute($params);

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
		$total = $row['total'];
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

	$dept = '';
	$section = '';
	$line_no = '';

	if (isset($_SESSION['emp_no_control_area'])) {
		$dept = $_SESSION['dept'];
		$section = $_SESSION['section'];
		if (empty($_SESSION['line_no'])) {
			$line_no = $_POST['line_no'];
		} else {
			$line_no = $_SESSION['line_no'];
		}
	} else {
		$dept = $_POST['dept'];
		$section = $_POST['section'];
		$line_no = $_POST['line_no'];
	}

	$search_arr = array(
		"emp_id" => $emp_id,
		"pro" => $pro,
		"category" => $category,
		"date" => $date,
		"date_authorized" => $date_authorized,
		"fullname" => $fullname,
		"dept" => $dept,
		"section" => $section,
		"line_no" => $line_no
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

	$dept = '';
	$section = '';
	$line_no = '';

	if (isset($_SESSION['emp_no_control_area'])) {
		$dept = $_SESSION['dept'];
		$section = $_SESSION['section'];
		if (empty($_SESSION['line_no'])) {
			$line_no = $_POST['line_no'];
		} else {
			$line_no = $_SESSION['line_no'];
		}
	} else {
		$dept = $_POST['dept'];
		$section = $_POST['section'];
		$line_no = $_POST['line_no'];
	}

	$search_arr = array(
		"emp_id" => $emp_id,
		"pro" => $pro,
		"category" => $category,
		"date" => $date,
		"date_authorized" => $date_authorized,
		"fullname" => $fullname,
		"dept" => $dept,
		"section" => $section,
		"line_no" => $line_no
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

	$dept = '';
	$section = '';
	$line_no = '';

	if (isset($_SESSION['emp_no_control_area'])) {
		$dept = $_SESSION['dept'];
		$section = $_SESSION['section'];
		if (empty($_SESSION['line_no'])) {
			$line_no = $_POST['line_no'];
		} else {
			$line_no = $_SESSION['line_no'];
		}
	} else {
		$dept = $_POST['dept'];
		$section = $_POST['section'];
		$line_no = $_POST['line_no'];
	}

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
					SELECT emp.dept, emp.line_no, emp.section, 
						 a.batch, a.process, a.auth_no, a.auth_year, a.date_authorized, a.expire_date, 
                         a.r_of_cancellation, a.d_of_cancellation, a.remarks, a.i_status, a.r_status, 
                         b.fullname, b.agency, b.emp_id, 
						 sl.id AS skill_level_id, sl.skill_level, 
						 ROW_NUMBER() OVER (PARTITION BY a.emp_id, a.auth_no ORDER BY a.auth_year DESC) AS rn
					FROM $table_name a 
					LEFT JOIN [qualif].[dbo].[t_employee_m] b ON a.emp_id = b.emp_id AND a.batch = b.batch 
					LEFT JOIN m_employees emp ON a.emp_id=emp.emp_no 
					LEFT JOIN m_skill_level sl ON a.emp_id = sl.emp_no AND a.process = sl.process 
					JOIN LatestAuth la ON a.emp_id = la.emp_id AND a.auth_no = la.auth_no AND a.auth_year = la.latest_auth_year 
                    WHERE a.i_status = 'Approved'";

		$params = [];

		if (!empty($emp_id)) {
			$query .= " AND (b.emp_id = ? OR b.emp_id_old = ?)";
			$params[] = $emp_id;
			$params[] = $emp_id;
		}

		if (!empty($fullname)) {
			$query .= " AND b.fullname LIKE ?";
			$fullname_search = $fullname . "%";
			$params[] = $fullname_search;
		}

		if (!empty($pro)) {
			$query .= " AND a.process LIKE ?";
			$pro_search = $pro . "%";
			$params[] = $pro_search;
		}

		if (!empty($date)) {
			$query .= " AND a.expire_date = ?";
			$params[] = $date;
		}

		if (!empty($date_authorized)) {
			$query .= " AND a.date_authorized = ?";
			$params[] = $date_authorized;
		}

		if (!empty($dept)) {
			$query.= " AND emp.dept LIKE ?";
			$dept_search = $dept . "%";
			$params[] = $dept_search;
		}

		if (!empty($section)) {
			$query .= " AND emp.section LIKE ?";
			$section_search = $section . "%";
			$params[] = $section_search;
		}

		if (!empty($line_no)) {
			$query .= " AND emp.line_no LIKE ?";
			$line_no_search = $line_no . "%";
			$params[] = $line_no_search;
		}

		$query .= " ORDER BY a.process ASC, b.fullname ASC, a.auth_year DESC 
                    OFFSET ? ROWS 
                    FETCH NEXT ? ROWS ONLY";

		$query .= ") SELECT *
					FROM RankedAuth
					WHERE rn = 1";

		$stmt = $conn->prepare($query);

		// Bind parameters dynamically
		foreach ($params as $index => $param) {
			$stmt->bindValue($index + 1, $param); // Bind each filtering parameter
		}

		// Bind pagination parameters
		$stmt->bindValue(count($params) + 1, $page_first_result, PDO::PARAM_INT); // Bind OFFSET
		$stmt->bindValue(count($params) + 2, $results_per_page, PDO::PARAM_INT); // Bind FETCH NEXT

		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		// Check if rows are returned
		if ($row) {
			do {
				$c++;

				$row_class = ($row['r_status'] == 'Approved') ? " bg-danger" : "";

				if (isset($_SESSION['emp_no_control_area'])) {
					echo '<tr style="cursor:pointer;" class="modal-trigger" 
							data-toggle="modal" data-target="#update_skill_level" 
							onclick="get_skill_level_details(&quot;'.
							$row['skill_level_id'].'~!~'.
							$row['skill_level'].'~!~'.
							$row['emp_id'].'~!~'.
							$row['process'].'&quot;)">';
				} else {
					echo '<tr>';
				}

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
				if (!empty($row['skill_level'])) {
					echo '<td>Level ' . htmlspecialchars($row['skill_level']) . '</td>';
				} else {
					echo '<td>' . htmlspecialchars($row['skill_level']) . '</td>';
				}
				echo '<td>' . htmlspecialchars($row['remarks']) . '</td>';
				if ($row['r_status'] == 'Approved') {
					echo '<td>' . htmlspecialchars($row['r_of_cancellation']) . '</td>';
					echo '<td>' . htmlspecialchars($row['d_of_cancellation']) . '</td>';
				} else {
					echo '<td></td>';
					echo '<td></td>';
				}
				echo '</tr>';
			} while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
		} else {
			echo '<tr>';
			echo '<td style="text-align:center;" colspan="4">No Result</td>';
			echo '</tr>';
		}
	} else {
		echo '<script>alert("Please select category and process");</script>';
	}
}

if ($method == 'update_skill_level') {
	$id = 0;

	if (isset($_POST['id']) && !empty($_POST['id'])) {
		$id = intval($_POST['id']);
	}

	$skill_level = $_POST['skill_level'];
	$emp_no = $_POST['emp_no'];
	$process = $_POST['process'];

	$isTransactionActive = false;

	try {
		if (!$isTransactionActive) {
			$conn->beginTransaction();
			$isTransactionActive = true;
		}

		$query = "SELECT id FROM m_skill_level WHERE id = ?";
		$stmt = $conn->prepare($query);
		$params = array($id);
		$stmt->execute($params);

		$row = $stmt -> fetch(PDO::FETCH_ASSOC);

		if ($row && $id > 0) {
			$query = "UPDATE m_skill_level SET skill_level = ? WHERE id = ?";
			$stmt = $conn->prepare($query);
			$params = array($skill_level, $id);
			$stmt->execute($params);
		} else {
			$query = "INSERT INTO m_skill_level (emp_no, process, skill_level) 
								VALUES (?, ?, ?)";
			$stmt = $conn -> prepare($query);
			$params = array($emp_no, $process, $skill_level);
			$stmt -> execute($params);
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
		exit();
	}
}

$conn = NULL;
