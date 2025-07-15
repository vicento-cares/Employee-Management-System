<?php 
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

include '../../conn.php';

$method = $_POST['method'];

// Employee Masterlist

function count_employee_list($search_arr, $conn) {
	$query = "SELECT COUNT(id) AS total FROM m_employees WHERE";
	$params = [];

	if (!empty($search_arr['search_multiple_employee_arr'])) {
		// Create a placeholder string for the IDs
		$placeholders = implode(',', array_fill(0, count($search_arr['search_multiple_employee_arr']), '?'));
		$query = $query . " emp_no IN ($placeholders)";
		$params = array_merge($params, $search_arr['search_multiple_employee_arr']); // Flatten the array
	} else {
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
		if (!empty($search_arr['provider'])) {
			$query = $query . " AND provider = ?";
			$params[] = $search_arr['provider'];
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
	
			/*$query = $query . " AND dept = '".$_SESSION['dept']."' AND section = '".$_SESSION['section']."' AND line_no = '".$_SESSION['line_no']."'";*/
		} else {
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
		}
	
		if (!empty($search_arr['date_updated_from']) && !empty($search_arr['date_updated_to'])) {
			$query = $query . " AND date_updated BETWEEN ? AND ?";
			$params[] = $search_arr['date_updated_from'];
			$params[] = $search_arr['date_updated_to'];
		}
	
		if ($search_arr['resigned'] != '') {
			$query = $query . " AND resigned = '".$search_arr['resigned']."'";
			$params[] = $search_arr['resigned'];
		}

		// Control Area Only Active Employees
		if (isset($_SESSION['emp_no_control_area'])) {
			$query = $query . " AND resigned = 0";
		}
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

// Get Dept Dropdown
if ($method == 'fetch_dept_dropdown') {
	$sql = "SELECT dept FROM m_dept ORDER BY dept ASC";

	$stmt = $conn -> prepare($sql);
	$stmt -> execute();

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
		echo '<option selected value="">Select Department</option>';
		echo '<option value="">All</option>';
		echo '<option value="PD">All PD</option>';
		do {
			echo '<option value="'.htmlspecialchars($row['dept']).'">'.htmlspecialchars($row['dept']).'</option>';
		} while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
	} else {
		echo '<option disabled selected value="">Select Department</option>';
	}
}

// Get Group Dropdown
if ($method == 'fetch_group_dropdown') {
	$sql = "SELECT falp_group FROM m_falp_groups ORDER BY falp_group ASC";

	$stmt = $conn -> prepare($sql);
	$stmt -> execute();

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
		echo '<option selected value="">Select Group</option>';
		do {
			echo '<option value="'.htmlspecialchars($row['falp_group']).'">'.htmlspecialchars($row['falp_group']).'</option>';
		} while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
	} else {
		echo '<option disabled selected value="">Select Group</option>';
	}
}

// Get Section Dropdown
if ($method == 'fetch_section_dropdown') {
	$sql = "SELECT section FROM m_access_locations GROUP BY section ORDER BY section ASC";

	$stmt = $conn -> prepare($sql);
	$stmt -> execute();

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
		echo '<option selected value="">Select Section</option>';
		echo '<option value="">All</option>';
		do {
			echo '<option value="'.htmlspecialchars($row['section']).'">'.htmlspecialchars($row['section']).'</option>';
		} while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
	} else {
		echo '<option disabled selected value="">Select Section</option>';
	}
}

// Get Sub Section Dropdown
if ($method == 'fetch_sub_section_dropdown') {
	$sql = "SELECT sub_section FROM m_sub_sections ORDER BY sub_section ASC";

	$stmt = $conn -> prepare($sql);
	$stmt -> execute();

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
		echo '<option selected value="">Select Sub Section</option>';
		do {
			echo '<option value="'.htmlspecialchars($row['sub_section']).'">'.htmlspecialchars($row['sub_section']).'</option>';
		} while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
	} else {
		echo '<option disabled selected value="">Select Sub Section</option>';
	}
}

// Get Line Datalist
if ($method == 'fetch_line_dropdown') {
	$section = $_POST['section'];

	$sql = "SELECT line_no FROM m_access_locations";
	$params = [];

	if (!empty($section)) {
		if ($section != 'QC') {
			$sql = $sql . " WHERE section = ? OR line_no = 'Undefined'";
			$params[] = $section;
		}
	}
	$sql = $sql . " GROUP BY line_no ORDER BY line_no ASC";
	$stmt = $conn -> prepare($sql);
	$stmt -> execute($params);

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
		echo '<option selected value="">Select Line No.</option>';
		echo '<option value="">All</option>';
		do {
			echo '<option value="'.htmlspecialchars($row['line_no']).'">'.htmlspecialchars($row['line_no']).'</option>';
		} while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
	} else {
		echo '<option disabled selected value="">Select Line No.</option>';
	}
}

// Get Position Dropdown
if ($method == 'fetch_position_dropdown') {
	$sql = "SELECT position FROM m_positions ORDER BY position ASC";

	$stmt = $conn -> prepare($sql);
	$stmt -> execute();

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
		echo '<option selected value="">Select Position</option>';
		do {
			echo '<option value="'.htmlspecialchars($row['position']).'">'.htmlspecialchars($row['position']).'</option>';
		} while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
	} else {
		echo '<option disabled selected value="">Select Position</option>';
	}
}

// Get Position Dropdown
if ($method == 'fetch_process_dropdown') {
	$sql = "SELECT process FROM m_process ORDER BY process ASC";
	$stmt = $conn -> prepare($sql);
	$stmt -> execute();

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
		echo '<option selected value="">Select Process</option>';
		do {
			echo '<option value="'.htmlspecialchars($row['process']).'">'.htmlspecialchars($row['process']).'</option>';
		} while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
	} else {
		echo '<option disabled selected value="">Select Process</option>';
	}
}

// Get Provider Dropdown
if ($method == 'fetch_provider_dropdown') {
	$sql = "SELECT provider FROM m_providers ORDER BY provider ASC";

	$stmt = $conn -> prepare($sql);
	$stmt -> execute();

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
		echo '<option selected value="">Select Provider</option>';
		do {
			echo '<option value="'.htmlspecialchars($row['provider']).'">'.htmlspecialchars($row['provider']).'</option>';
		} while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
	} else {
		echo '<option disabled selected value="">Select Provider</option>';
	}
}

// Get Employee Name Jr. Staff or Staff Dropdown
if ($method == 'fetch_employee_name_js_s_dropdown') {
	$dept = trim($_POST['dept']);
	$section = trim($_POST['section']);
	$line_no = trim($_POST['line_no']);

	$sql = "SELECT emp_no, full_name FROM m_employees WHERE position IN ('Jr. Staff', 'Staff') AND resigned = 0";
	$params = [];

	if (!empty($dept)) {
		$sql = $sql . " AND dept = ?";
		$params[] = $dept;
	}
	if (!empty($section)) {
		$sql = $sql . " AND section LIKE ?";
		$section_param = $section . '%';
		$params[] = $section_param;
	}
	if (!empty($line_no)) {
		$sql = $sql . " AND line_no LIKE ?";
		$line_no_param = $line_no . '%';
		$params[] = $line_no_param;
	}
	$sql = $sql . " ORDER BY full_name ASC";

	$stmt = $conn -> prepare($sql);
	$stmt -> execute($params);

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
		echo '<option selected value="">Select Name</option>';
		do {
			echo '<option value="'.htmlspecialchars($row['emp_no']).'">'.htmlspecialchars($row['full_name']).'</option>';
		} while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
	} else {
		echo '<option disabled selected value="">Select Name</option>';
	}
}

// Get Employee Name Supervisor Dropdown
if ($method == 'fetch_employee_name_sv_dropdown') {
	$dept = trim($_POST['dept']);
	$section = trim($_POST['section']);
	$line_no = trim($_POST['line_no']);

	$sql = "SELECT emp_no, full_name FROM m_employees WHERE position = 'Supervisor' AND resigned = 0";
	$params = [];

	if (!empty($dept)) {
		$sql = $sql . " AND dept = ?";
		$params[] = $dept;
	}
	if (!empty($section)) {
		$sql = $sql . " AND section LIKE ?";
		$section_param = $section . '%';
		$params[] = $section_param;
	}
	if (!empty($line_no)) {
		$sql = $sql . " AND line_no LIKE ?";
		$line_no_param = $line_no . '%';
		$params[] = $line_no_param;
	}
	$sql = $sql . " ORDER BY full_name ASC";

	$stmt = $conn -> prepare($sql);
	$stmt -> execute($params);

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
		echo '<option selected value="">Select Name</option>';
		do {
			echo '<option value="'.htmlspecialchars($row['emp_no']).'">'.htmlspecialchars($row['full_name']).'</option>';
		} while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
	} else {
		echo '<option disabled selected value="">Select Name</option>';
	}
}

// Get Employee Name Dropdown
if ($method == 'fetch_employee_name_approver_dropdown') {
	$dept = trim($_POST['dept']);
	$section = trim($_POST['section']);
	$line_no = trim($_POST['line_no']);

	$sql = "SELECT emp_no, full_name FROM m_employees 
			WHERE position IN ('Assistant Manager', 'Section Manager', 'Manager') AND resigned = 0";
	$params = [];

	if (!empty($dept)) {
		$sql = $sql . " AND dept = ?";
		$params[] = $dept;
	}
	if (!empty($section)) {
		$sql = $sql . " AND section LIKE ?";
		$section_param = $section . '%';
		$params[] = $section_param;
	}
	if (!empty($line_no)) {
		$sql = $sql . " AND line_no LIKE ?";
		$line_no_param = $line_no . '%';
		$params[] = $line_no_param;
	}
	$sql = $sql . " ORDER BY full_name ASC";

	$stmt = $conn -> prepare($sql);
	$stmt -> execute($params);

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
		echo '<option disabled selected value="">Select Name</option>';
		do {
			echo '<option value="'.htmlspecialchars($row['emp_no']).'">'.htmlspecialchars($row['full_name']).'</option>';
		} while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
	} else {
		echo '<option disabled selected value="">Select Name</option>';
	}
}

if ($method == 'count_employee_list') {
	$emp_no = $_POST['emp_no'];
	$full_name = $_POST['full_name'];
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
		$section = $_POST['section'];
	}

	if (!isset($_POST['line_no'])) {
		$line_no = '';
	} else {
		$line_no = $_POST['line_no'];
	}

	if (!isset($_POST['resigned'])) {
		$resigned = '';
	} else {
		$resigned = $_POST['resigned'];
	}

	$search_multiple_employee_arr = [];
	if (isset($_POST['search_multiple_employee_arr'])) {
		$search_multiple_employee_arr = $_POST['search_multiple_employee_arr'];
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
		"resigned" => $resigned,
		"search_multiple_employee_arr" => $search_multiple_employee_arr
	);

	echo count_employee_list($search_arr, $conn);
}

if ($method == 'employee_list_last_page') {
	$emp_no = $_POST['emp_no'];
	$full_name = $_POST['full_name'];
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
		$section = $_POST['section'];
	}

	if (!isset($_POST['line_no'])) {
		$line_no = '';
	} else {
		$line_no = $_POST['line_no'];
	}

	if (!isset($_POST['resigned'])) {
		$resigned = '';
	} else {
		$resigned = $_POST['resigned'];
	}

	$search_multiple_employee_arr = [];
	if (isset($_POST['search_multiple_employee_arr'])) {
		$search_multiple_employee_arr = $_POST['search_multiple_employee_arr'];
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
		"resigned" => $resigned,
		"search_multiple_employee_arr" => $search_multiple_employee_arr
	);

	$results_per_page = 20;

	$number_of_result = intval(count_employee_list($search_arr, $conn));

	//determine the total number of pages available  
	$number_of_page = ceil($number_of_result / $results_per_page);

	echo $number_of_page;

}

if ($method == 'employee_list') {
	$emp_no = $_POST['emp_no'];
	$full_name = $_POST['full_name'];
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
		$section = $_POST['section'];
	}

	if (!isset($_POST['line_no'])) {
		$line_no = '';
	} else {
		$line_no = $_POST['line_no'];
	}

	if (!isset($_POST['resigned'])) {
		$resigned = '';
	} else {
		$resigned = $_POST['resigned'];
	}

	$search_multiple_employee_arr = [];
	if (isset($_POST['search_multiple_employee_arr'])) {
		$search_multiple_employee_arr = $_POST['search_multiple_employee_arr'];
	}

	$current_page = intval($_POST['current_page']);
	$c = 0;

	$results_per_page = 20;

	//determine the sql LIMIT starting number for the results on the displaying page
	$page_first_result = ($current_page-1) * $results_per_page;

	$c = $page_first_result;

	$query = "SELECT 
				id, emp_no, full_name, dept, section, sub_section, line_no, process, skill_level, 
				position, provider, gender, shift, shift_group, date_hired, address, contact_no, emp_status, 
				shuttle_route, emp_js_s_no, emp_sv_no, emp_approver_no, resigned, resigned_date 
			FROM m_employees WHERE";
	
	$params = [];

	if (!empty($search_multiple_employee_arr)) {
		// Create a placeholder string for the IDs
		$placeholders = implode(',', array_fill(0, count($search_multiple_employee_arr), '?'));
		$query = $query . " emp_no IN ($placeholders)";
		$params = array_merge($params, $search_multiple_employee_arr); // Flatten the array
	} else {
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
		if (!empty($provider)) {
			$query = $query . " AND provider = ?";
			$params[] = $provider;
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
	
			/*$query = $query . " AND dept = '".$_SESSION['dept']."' AND section = '".$_SESSION['section']."' AND line_no = '".$_SESSION['line_no']."'";*/
		} else {
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
		}
	
		if (!empty($date_updated_from) && !empty($date_updated_to)) {
			$query = $query . " AND date_updated BETWEEN ? AND ?";
			$params[] = $date_updated_from;
			$params[] = $date_updated_to;
		}
	
		if ($resigned != '') {
			$query = $query . " AND resigned = ?";
			$params[] = $resigned;
		}

		// Control Area Only Active Employees
		if (isset($_SESSION['emp_no_control_area'])) {
			$query = $query . " AND resigned = 0";
		}
	}

	// MySQL Query
	// $query = $query . " LIMIT ".$page_first_result.", ".$results_per_page;

	// MS SQL Server Query
	$query = $query . " ORDER BY id ASC";
	$query = $query . " OFFSET ".$page_first_result." ROWS FETCH NEXT ".$results_per_page." ROWS ONLY";
	
	$stmt = $conn->prepare($query);
	$stmt->execute($params);

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
		do {
			$c++;
			
			if (isset($_SESSION['emp_no']) || isset($_SESSION['emp_no_control_area']) || isset($_SESSION['emp_no_tc'])) {
				echo '<tr style="cursor:pointer;" class="modal-trigger" data-toggle="modal" data-target="#update_employee" 
						onclick="get_employees_details(&quot;'.
						$row['id'].'~!~'.
						$row['emp_no'].'~!~'.
						$row['full_name'].'~!~'.
						$row['dept'].'~!~'.
						$row['section'].'~!~'.
						$row['line_no'].'~!~'.
						$row['position'].'~!~'.
						$row['provider'].'~!~'.
						$row['date_hired'].'~!~'.
						$row['address'].'~!~'.
						$row['contact_no'].'~!~'.
						$row['emp_status'].'~!~'.
						$row['shuttle_route'].'~!~'.
						$row['emp_js_s_no'].'~!~'.
						$row['emp_sv_no'].'~!~'.
						$row['emp_approver_no'].'~!~'.
						$row['resigned'].'~!~'.
						$row['resigned_date'].'~!~'.
						$row['gender'].'~!~'.
						$row['shift_group'].'~!~'.
						$row['process'].'~!~'.
						$row['section'].'~!~'.
						$row['sub_section'].'~!~'.
						$row['skill_level'].'~!~'.
						$row['shift'].'&quot;)">';

				echo '<td >'.$c.'</td>';
			} else {
				echo '<tr>';

				echo '<td><p class="mb-0"><label class="mb-0"><input type="checkbox" class="singleCheck" 
							value="'.$row['id'].'" onclick="get_checked_length()" /><span></span></label></p></td>';

				echo '<td style="cursor:pointer;" class="modal-trigger" data-toggle="modal" data-target="#update_employee" 
						onclick="get_employees_details(&quot;'.
						$row['id'].'~!~'.
						$row['emp_no'].'~!~'.
						$row['full_name'].'~!~'.
						$row['dept'].'~!~'.
						$row['section'].'~!~'.
						$row['line_no'].'~!~'.
						$row['position'].'~!~'.
						$row['provider'].'~!~'.
						$row['date_hired'].'~!~'.
						$row['address'].'~!~'.
						$row['contact_no'].'~!~'.
						$row['emp_status'].'~!~'.
						$row['shuttle_route'].'~!~'.
						$row['emp_js_s_no'].'~!~'.
						$row['emp_sv_no'].'~!~'.
						$row['emp_approver_no'].'~!~'.
						$row['resigned'].'~!~'.
						$row['resigned_date'].'~!~'.
						$row['gender'].'~!~'.
						$row['shift_group'].'~!~'.
						$row['process'].'~!~'.
						$row['section'].'~!~'.
						$row['sub_section'].'&quot;)">'.$c.'</td>';
			}

				echo '<td>'.$row['emp_no'].'</td>';
				echo '<td>'.$row['full_name'].'</td>';
				echo '<td>'.$row['dept'].'</td>';
				echo '<td>'.$row['section'].'</td>';
				echo '<td>'.$row['line_no'].'</td>';
				echo '<td>'.$row['provider'].'</td>';
				echo '<td>'.$row['shuttle_route'].'</td>';
			echo '</tr>';
		} while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
	} else {
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
	$emp_no = $_POST['emp_no'];
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

	$query = "SELECT 
				emp_no, full_name, dept, section, line_no, position, shift_group, 
				date_hired, address, contact_no, emp_status, resigned 
			FROM m_employees WHERE emp_no = ?";
	$stmt = $conn->prepare($query);
	$params = array($emp_no);
	$stmt->execute($params);
	
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
		$emp_no = $row['emp_no'];
		$full_name = $row['full_name'];
		$dept = $row['dept'];
		$section = $row['section'];
		$line_no = $row['line_no'];
		$position = $row['position'];
		$shift_group = $row['shift_group'];
		$date_hired = $row['date_hired'];
		$address = $row['address'];
		$contact_no = $row['contact_no'];
		$emp_status = $row['emp_status'];
		$resigned = intval($row['resigned']);
		
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
	$full_name = trim($_POST['full_name']);
	$emp_no = trim($_POST['emp_no']);
	$dept = trim($_POST['dept']);
	$section = trim($_POST['section']);
	$line_no = trim($_POST['line_no']);
	$position = trim($_POST['position']);
	$date_hired = trim($_POST['date_hired']);
	$provider = trim($_POST['provider']);
	$address = trim($_POST['address']);
	$contact_no = trim($_POST['contact_no']);
	$emp_status = trim($_POST['emp_status']);
	$shuttle_route = trim($_POST['shuttle_route']);
	$gender = trim($_POST['gender']);
	$emp_js_s_no = trim($_POST['emp_js_s_no']);
	$emp_sv_no = trim($_POST['emp_sv_no']);
	$emp_approver_no = trim($_POST['emp_approver_no']);
	$emp_js_s = trim($_POST['emp_js_s']);
	$emp_sv = trim($_POST['emp_sv']);
	$emp_approver = trim($_POST['emp_approver']);

	$check = "SELECT id FROM m_employees WHERE emp_no = ?";
	
	$stmt = $conn->prepare($check);
	$params = array($emp_no);
	$stmt->execute($params);

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
		echo 'Already Exist';
	} else {
		$stmt = NULL;

		$query = "INSERT INTO m_employees
				(emp_no, full_name, dept, section, line_no, date_hired, position, provider, gender, 
				address, contact_no, emp_status, shuttle_route, emp_js_s, emp_sv, 
				emp_approver, emp_js_s_no, emp_sv_no, emp_approver_no) VALUES (?, ?";
		
		$params1 = [
			$emp_no,
			$full_name
		];

		if (!empty($dept)) {
			$query = $query . ", ?";
			$params1[] = $dept;
		} else {
			$query = $query . ", NULL";
		}
		if (!empty($section)) {
			$query = $query . ", ?";
			$params1[] = $section;
		} else {
			$query = $query . ", NULL";
		}
		if (!empty($line_no)) {
			$query = $query . ", ?";
			$params1[] = $line_no;
		} else {
			$query = $query . ", NULL";
		}

		if (!empty($date_hired)) {
			$query = $query . ", ?";
			$params1[] = $date_hired;
		} else {
			$query = $query . ", NULL";
		}

		$query = $query . ", ?, ?, ?, ?, ?, ?, 
						?, ?, ?, ?, ?, ?, ?)";

		$params2 = [
			$position,
			$provider,
			$gender,
			$address,
			$contact_no,
			$emp_status,
			$shuttle_route,
			$emp_js_s,
			$emp_sv,
			$emp_approver,
			$emp_js_s_no,
			$emp_sv_no,
			$emp_approver_no
		];

		$params = array_merge($params1, $params2);

		$stmt = $conn->prepare($query);

		if ($stmt->execute($params)) {
			echo 'success';
		}else{
			echo 'error';
		}
	}
}

if ($method == 'update_employee') {
	$id = $_POST['id'];
	$emp_no = trim($_POST['emp_no']);
	$full_name = trim($_POST['full_name']);
	$dept = trim($_POST['dept']);
	$section = trim($_POST['section']);
	$line_no = trim($_POST['line_no']);
	$position = trim($_POST['position']);
	$date_hired = trim($_POST['date_hired']);
	$provider = trim($_POST['provider']);
	$address = trim($_POST['address']);
	$contact_no = trim($_POST['contact_no']);
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

	$query = "UPDATE m_employees SET emp_no = ?, full_name = ?";

	$params1 = [
		$emp_no,
		$full_name
	];
	
	if (!empty($dept)) {
		$query = $query . ", dept = ?";
		$params1[] = $dept;
	}
	if (!empty($section)) {
		$query = $query . ", section = ?";
		$params1[] = $section;
	}
	if (!empty($line_no)) {
		$query = $query . ", line_no = ?";
		$params1[] = $line_no;
	}

	if (!empty($date_hired)) {
		$query = $query . ", date_hired = ?";
		$params1[] = $date_hired;
	} else {
		$query = $query . ", date_hired = NULL";
	}

	if (!empty($resigned_date)) {
		$query = $query . ", resigned_date = ?";
		$params1[] = $resigned_date;
	} else {
		$query = $query . ", resigned_date = NULL";
	}

	$query = $query . ", position = ?, provider = ?, gender = ?, 
						address = ?, contact_no = ?, emp_status = ?, 
						shuttle_route = ?, emp_js_s = ?, emp_sv = ?, emp_approver = ?, 
						emp_js_s_no = ?, emp_sv_no = ?, emp_approver_no = ?, 
						resigned = ? WHERE id = ?";
	
	$params2 = [
		$position,
		$provider,
		$gender,
		$address,
		$contact_no,
		$emp_status,
		$shuttle_route,
		$emp_js_s,
		$emp_sv,
		$emp_approver,
		$emp_js_s_no,
		$emp_sv_no,
		$emp_approver_no,
		$resigned,
		$id
	];

	$params = array_merge($params1, $params2);

	$stmt = $conn->prepare($query);

	if ($stmt->execute($params)) {
		$query = "UPDATE m_control_area_accounts SET";
		$params = [];
	
		if (!empty($dept)) {
			$query = $query . " dept = ?";
			$params[] = $dept;
		}
		if (!empty($section)) {
			$query = $query . ", section = ?";
			$params[] = $section;
		}
		if (!empty($line_no)) {
			$query = $query . ", line_no = ?";
			$params[] = $line_no;
		} else {
			$query = $query . ", line_no = NULL";
		}

		$query = $query . " WHERE emp_no = ?";
		$params[] = $emp_no;
		$stmt = $conn->prepare($query);

		if ($stmt->execute($params)) {
			$query = "UPDATE m_accounts SET";
			$params = [];
	
			if (!empty($dept)) {
				$query = $query . " dept = ?";
				$params[] = $dept;
			}
			if (!empty($section)) {
				$query = $query . ", section = ?";
				$params[] = $section;
			}
			if (!empty($line_no)) {
				$query = $query . ", line_no = ?";
				$params[] = $line_no;
			}

			$query = $query . " WHERE emp_no = ?";
			$params[] = $emp_no;
			$stmt = $conn->prepare($query);

			if ($stmt->execute($params)) {
				echo 'success';
			} else {
				echo 'error';
			}
		} else {
			echo 'error';
		}
	} else {
		echo 'error';
	}
}

if ($method == 'update_employee_advanced') {
	$id = $_POST['id'];
	$emp_no = trim($_POST['emp_no']);
	$full_name = trim($_POST['full_name']);
	$dept = trim($_POST['dept']);
	$section = trim($_POST['section']);
	$sub_section = trim($_POST['sub_section']);
	$process = trim($_POST['line_process']);
	$skill_level = trim($_POST['skill_level']);
	$line_no = trim($_POST['line_no']);
	$position = trim($_POST['position']);
	$date_hired = trim($_POST['date_hired']);
	$provider = trim($_POST['provider']);
	$shift_group = trim($_POST['shift_group']);
	$address = trim($_POST['address']);
	$contact_no = trim($_POST['contact_no']);
	$emp_status = trim($_POST['emp_status']);
	$shuttle_route = trim($_POST['shuttle_route']);
	$gender = trim($_POST['gender']);
	$emp_js_s_no = trim($_POST['emp_js_s_no']);
	$emp_sv_no = trim($_POST['emp_sv_no']);
	$emp_approver_no = trim($_POST['emp_approver_no']);
	$emp_js_s = trim($_POST['emp_js_s']);
	$emp_sv = trim($_POST['emp_sv']);
	$emp_approver = trim($_POST['emp_approver']);
	// $resigned = intval($_POST['resigned']);
	// $resigned_date = trim($_POST['resigned_date']);

	// Shift Update
	$shift = '';
	if (isset($_POST['shift'])) {
		$shift = trim($_POST['shift']);
	}

	$query = "UPDATE m_employees SET emp_no = ?, full_name = ?";

	$params1 = [
		$emp_no,
		$full_name
	];
	
	if (!empty($dept)) {
		$query = $query . ", dept = ?";
		$params1[] = $dept;
	}
	if (!empty($section)) {
		$query = $query . ", section = ?";
		$params1[] = $section;
	}
	if (!empty($sub_section)) {
		$query = $query . ", sub_section = ?";
		$params1[] = $sub_section;
	} else {
		$query = $query . ", sub_section = 'Undefined'";
	}
	if (!empty($process)) {
		$query = $query . ", process = ?";
		$params1[] = $process;
	} else {
		$query = $query . ", process = 'Undefined'";
	}
	if (!empty($line_no)) {
		$query = $query . ", line_no = ?";
		$params1[] = $line_no;
	} else {
		$query = $query . ", line_no = 'Undefined'";
	}

	if (!empty($shift)) {
		$query = $query . ", shift = ?";
		$params1[] = $shift;
	}

	if (!empty($skill_level)) {
		$query = $query . ", skill_level = ?";
		$params1[] = $skill_level;
	}

	if (!empty($date_hired)) {
		$query = $query . ", date_hired = ?";
		$params1[] = $date_hired;
	}

	$query = $query . ", position = ?, provider = ?, gender = ?, 
						shift_group = ?, address = ?, contact_no = ?, 
						emp_status = ?, shuttle_route = ?, 
						emp_js_s = ?, emp_sv = ?, emp_approver = ?, emp_js_s_no = ?, 
						emp_sv_no = ?, emp_approver_no = ? 
						WHERE id = ?";

	$stmt = $conn->prepare($query);

	$params2 = [
		$position,
		$provider,
		$gender,
		$shift_group,
		$address,
		$contact_no,
		$emp_status,
		$shuttle_route,
		$emp_js_s,
		$emp_sv,
		$emp_approver,
		$emp_js_s_no,
		$emp_sv_no,
		$emp_approver_no,
		$id
	];

	$params = array_merge($params1, $params2);

	if ($stmt->execute($params)) {
		$query = "UPDATE m_control_area_accounts SET";
		$params = [];
	
		if (!empty($dept)) {
			$query = $query . " dept = ?";
			$params[] = $dept;
		}
		if (!empty($section)) {
			$query = $query . ", section = ?";
			$params[] = $section;
		}
		if (!empty($line_no)) {
			$query = $query . ", line_no = ?";
			$params[] = $line_no;
		} else {
			$query = $query . ", line_no = NULL";
		}
		if (!empty($shift_group)) {
			$query = $query . ", shift_group = ?";
			$params[] = $shift_group;
		}

		$query = $query . " WHERE emp_no = ?";
		$params[] = $emp_no;
		$stmt = $conn->prepare($query);

		if ($stmt->execute($params)) {
			$query = "UPDATE m_accounts SET";
			$params = [];
	
			if (!empty($dept)) {
				$query = $query . " dept = ?";
				$params[] = $dept;
			}
			if (!empty($section)) {
				$query = $query . ", section = ?";
				$params[] = $section;
			}
			if (!empty($line_no)) {
				$query = $query . ", line_no = ?";
				$params[] = $line_no;
			}
			if (!empty($shift_group)) {
				$query = $query . ", shift_group = ?";
				$params[] = $shift_group;
			}

			$query = $query . " WHERE emp_no = ?";
			$params[] = $emp_no;

			$stmt = $conn->prepare($query);

			if ($stmt->execute($params)) {
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

	$query = "DELETE FROM m_employees WHERE id = ?";
	$params = array($id);
	$stmt = $conn->prepare($query);
	if ($stmt->execute($params)) {
		echo 'success';
	}else{
		echo 'error';
	}
}

$conn = NULL;
