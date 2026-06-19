<?php 
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

include '../../conn.php';

$method = $_POST['method'];

// Attendances

function count_attendance_list($search_arr, $conn) {
	$sql = "SELECT count(emp.emp_no) AS total 
		FROM m_employees emp 
		LEFT JOIN t_time_in_out tio ON tio.emp_no = emp.emp_no AND tio.day = ? 
		LEFT JOIN t_absences absences ON absences.emp_no = emp.emp_no AND absences.day = ? 
		WHERE (? IS NULL OR emp.shift_group = ?)";
	$params = [
		$search_arr['day'],
		$search_arr['day'],
		$search_arr['shift_group'],
		$search_arr['shift_group']
	];

	if (!empty($search_arr['attendance_status'])) {
		switch ($search_arr['attendance_status']) {
			case 1:
				$sql = $sql . " AND tio.time_in IS NOT NULL";
				break;
			case 2:
				$sql = $sql . " AND tio.time_in IS NULL";
				break;
			case 3:
				$sql = $sql . " AND tio.time_in IS NULL AND absences.day IS NOT NULL";
				break;
			case 4:
				$sql = $sql . " AND tio.time_in IS NULL AND absences.day IS NULL";
				break;
		}
	}

	if (!empty($search_arr['dept'])) {
		$sql = $sql . " AND emp.dept LIKE ?";
		$dept_param = $search_arr['dept'] . "%";
		$params[] = $dept_param;
	} else {
		$sql = $sql . " AND emp.dept != ''";
	}
	if (!empty($search_arr['section'])) {
		$sql = $sql . " AND emp.section LIKE ?";
		$section_param = $search_arr['section'] . "%";
		$params[] = $section_param;
	}
	if (!empty($search_arr['line_no'])) {
		$sql = $sql . " AND emp.line_no LIKE ?";
		$line_no_param = $search_arr['line_no'] . "%";
		$params[] = $line_no_param;
	}
	$sql = $sql . " AND (emp.date_hired <= ?) AND (emp.resigned_date IS NULL OR emp.resigned_date >= ?)";
	$params[] = $search_arr['day'];
	$params[] = $search_arr['day'];
	
	$stmt = $conn->prepare($sql);
	$stmt->execute($params);

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($row) {
		$total = intval($row['total']);
	} else {
		$total = 0;
	}

	return $total;
}

function count_attendance_list2($search_arr, $conn) {
	$sql = "SELECT count(emp_no) AS total 
		FROM m_employees
		WHERE shift_group = ?";
	$params = [];
	$params[] = $search_arr['shift_group'];

	if (!empty($search_arr['dept'])) {
		$sql = $sql . " AND dept LIKE ?";
		$dept_param = $search_arr['dept'] . "%";
		$params[] = $dept_param;
	} else {
		$sql = $sql . " AND dept != ''";
	}
	if (!empty($search_arr['section'])) {
		$sql = $sql . " AND section LIKE ?";
		$section_param = $search_arr['section'] . "%";
		$params[] = $section_param;
	}
	if ($search_arr['line_no'] == 'No Line') {
		$sql = $sql . " AND line_no IS NULL";
	} else if (!empty($search_arr['line_no'])) {
		$sql = $sql . " AND line_no LIKE ?";
		$line_no_param = $search_arr['line_no'] . "%";
		$params[] = $line_no_param;
	} else {
		$sql = $sql . " AND (line_no = '' OR line_no IS NULL)";
	}
	$sql = $sql . " AND (date_hired <= ?) AND (resigned_date IS NULL OR resigned_date >= ?)";
	$params[] = $search_arr['day'];
	$params[] = $search_arr['day'];
	
	$stmt = $conn->prepare($sql);
	$stmt->execute($params);

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($row) {
		$total = intval($row['total']);
	} else {
		$total = 0;
	}

	return $total;
}

function count_emp_tio($search_arr, $conn) {
	$sql = "SELECT count(emp.emp_no) AS total FROM m_employees emp
			LEFT JOIN t_time_in_out tio ON tio.emp_no = emp.emp_no
			WHERE tio.day = ? AND emp.shift_group = ?";
	$params = [
		$search_arr['day'],
		$search_arr['shift_group']
	];

	if (!empty($search_arr['dept'])) {
		$sql = $sql . " AND emp.dept LIKE ?";
		$dept_param = $search_arr['dept'] . "%";
		$params[] = $dept_param;
	} else {
		$sql = $sql . " AND emp.dept != ''";
	}
	if (!empty($search_arr['section'])) {
		$sql = $sql . " AND emp.section LIKE ?";
		$section_param = $search_arr['section'] . "%";
		$params[] = $section_param;
	}
	if (!empty($search_arr['line_no'])) {
		$sql = $sql . " AND emp.line_no LIKE ?";
		$line_no_param = $search_arr['line_no'] . "%";
		$params[] = $line_no_param;
	}
	$sql = $sql . " AND (emp.date_hired <= ?) AND (emp.resigned_date IS NULL OR emp.resigned_date >= ?)";
	$params[] = $search_arr['day'];
	$params[] = $search_arr['day'];

	$stmt = $conn->prepare($sql);
	$stmt->execute($params);

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($row) {
		$total = intval($row['total']);
	} else {
		$total = 0;
	}

	return $total;
}

if ($method == 'get_absences_reasons') {
	$sql = "SELECT id, reason, absent_type FROM m_absences_reasons";

	if (isset($_POST['page']) && $_POST['page'] == 'admin') {
		$sql .= " WHERE absent_type != 'NW'";
	}

	$sql .= " ORDER BY reason ASC";

	$stmt = $conn->prepare($sql);
	$stmt->execute();

	$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

	echo json_encode($data);
}

if ($method == 'count_attendance_present') {
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
	} else if (!empty($_SESSION['emp_no_control_area'])) {
		$dept = $_SESSION['dept'];
		$section = $_SESSION['section'];
		if (isset($_POST['line_no'])) {
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
	$shift_group = null;
	
	if (!empty($_POST['shift_group'])) {
		$shift_group = $_POST['shift_group'];
	}

	$attendance_status = 0;

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
	} else if (!empty($_SESSION['emp_no_control_area'])) {
		$dept = $_SESSION['dept'];
		$section = $_SESSION['section'];
		if (isset($_POST['line_no'])) {
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
		$section = '';
		if (!isset($_SESSION['line_no'])) {
			echo 'session timeout. please relogin account';
			$conn = null;
			exit();
		}
		$line_no = $_SESSION['line_no'];
	}

	if (isset($_POST['attendance_status'])) {
		$attendance_status = intval($_POST['attendance_status']);
	}
	
	$search_arr = array(
		"day" => $day,
		"shift_group" => $shift_group,
		"dept" => $dept,
		"section" => $section,
		"line_no" => $line_no,
		"attendance_status" => $attendance_status
	);

	echo count_attendance_list($search_arr, $conn);
}

if ($method == 'attendance_list_last_page') {
	$day = $_POST['day'];
	$shift_group = null;
	
	if (!empty($_POST['shift_group'])) {
		$shift_group = $_POST['shift_group'];
	}

	$attendance_status = 0;

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
	} else if (!empty($_SESSION['emp_no_control_area'])) {
		$dept = $_SESSION['dept'];
		$section = $_SESSION['section'];
		if (isset($_POST['line_no'])) {
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
		$section = '';
		$line_no = $_SESSION['line_no'];
	}

	if (isset($_POST['attendance_status'])) {
		$attendance_status = intval($_POST['attendance_status']);
	}
	
	$search_arr = array(
		"day" => $day,
		"shift_group" => $shift_group,
		"dept" => $dept,
		"section" => $section,
		"line_no" => $line_no,
		"attendance_status" => $attendance_status
	);

	$results_per_page = 20;

	$number_of_result = intval(count_attendance_list($search_arr, $conn));

	//determine the total number of pages available  
	$number_of_page = ceil($number_of_result / $results_per_page);

	echo $number_of_page;
}

if ($method == 'get_attendance_list') {
	$day = $_POST['day'];
	$shift_group = null;

	if (!empty($_POST['shift_group'])) {
		$shift_group = $_POST['shift_group'];
	}
	
	$attendance_status = 0;

	$server_date_only_2days_ago = date('Y-m-d',(strtotime('-1 day',strtotime($server_date_only_yesterday))));

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
	} else if (!empty($_SESSION['emp_no_control_area'])) {
		$dept = $_SESSION['dept'];
		$section = $_SESSION['section'];
		if (isset($_POST['line_no'])) {
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
		$section = '';
		if (!isset($_SESSION['line_no'])) {
			echo 'session timeout. please relogin account';
			$conn = null;
			exit();
		}
		$line_no = $_SESSION['line_no'];
	}

	if (isset($_POST['attendance_status'])) {
		$attendance_status = intval($_POST['attendance_status']);
	}

	$current_page = intval($_POST['current_page']);
	$c = 0;
	$row_class_arr = array('modal-trigger', 'modal-trigger bg-success', 'modal-trigger bg-danger', 'modal-trigger bg-lightpink');
	$row_class = $row_class_arr[0];

	$results_per_page = 20;

	//determine the sql LIMIT starting number for the results on the displaying page
	$page_first_result = ($current_page-1) * $results_per_page;

	$c = $page_first_result;

	$sql = "SELECT 
				emp.provider, emp.emp_no, emp.full_name, emp.dept, emp.section, emp.line_no, emp.shift, emp.shift_group, emp.resigned_date,
				tio.time_in, tio.day AS time_in_day, tio.shift AS time_in_shift, 
				absences.id AS absent_id, absences.day AS absent_day, absences.shift_group AS absent_shift_group, absences.absent_type, absences.reason,
				pic.file_url 
			FROM m_employees emp
			LEFT JOIN t_time_in_out tio ON tio.emp_no = emp.emp_no AND tio.day = ? 
			LEFT JOIN t_absences absences ON absences.emp_no = emp.emp_no AND absences.day = ? 
			LEFT JOIN m_employee_pictures pic ON pic.emp_no = emp.emp_no
			WHERE (? IS NULL OR emp.shift_group = ?)";
	$params = [
		$day,
		$day,
		$shift_group,
		$shift_group
	];

	if (!empty($attendance_status)) {
		switch ($attendance_status) {
			case 1:
				$sql = $sql . " AND tio.time_in IS NOT NULL";
				break;
			case 2:
				$sql = $sql . " AND tio.time_in IS NULL";
				break;
			case 3:
				$sql = $sql . " AND tio.time_in IS NULL AND absences.day IS NOT NULL";
				break;
			case 4:
				$sql = $sql . " AND tio.time_in IS NULL AND absences.day IS NULL";
				break;
		}
	}

	if (!empty($dept)) {
		$sql = $sql . " AND emp.dept LIKE ?";
		$dept_param = $dept . "%";
		$params[] = $dept_param;
	} else {
		$sql = $sql . " AND emp.dept != ''";
	}
	if (!empty($section)) {
		$sql = $sql . " AND emp.section LIKE ?";
		$section_param = $section . "%";
		$params[] = $section_param;
	}
	if (!empty($line_no)) {
		$sql = $sql . " AND emp.line_no LIKE ?";
		$line_no_param = $line_no . "%";
		$params[] = $line_no_param;
	}
	$sql = $sql . " AND (emp.date_hired <= ?) AND (emp.resigned_date IS NULL OR emp.resigned_date >= ?)";
	$params[] = $day;
	$params[] = $day;
	$sql = $sql . " ORDER BY emp.emp_no ASC";

	// MySQL Query
	// $sql = $sql . " LIMIT ".$page_first_result.", ".$results_per_page;

	// MS SQL Server Query
	$sql = $sql . " OFFSET ".$page_first_result." ROWS FETCH NEXT ".$results_per_page." ROWS ONLY";

	$stmt = $conn->prepare($sql);
	$stmt->execute($params);

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($row) {
		do {
			$c++;

			if (!empty($row['time_in'])) {
				$row_class = $row_class_arr[1];
				echo '<tr class="'.$row_class.'">';
			} else {
				$row_class = $row_class_arr[2];

				if (isset($_SESSION['emp_no_hr'])) {
					$row_class = $row_class_arr[3];
				}
				
				$row_day = '';
				$row_shift = '';
				if (!empty($row['absent_day']) && !empty($row['absent_shift_group'])) {
					$row_day = $row['absent_day'];
					$row_shift_group = $row['absent_shift_group'];
				} else {
					$row_day = $day;
					$row_shift_group = $shift_group;
				}
				
				echo '<tr class="'.$row_class.'">';
				// echo '<tr style="cursor:pointer;" class="'.$row_class.'" data-toggle="modal" data-target="#absence_details" onclick="get_absence_details(&quot;'.$row['absent_id'].'~!~'.$row['emp_no'].'~!~'.$row['full_name'].'~!~'.$row_day.'~!~'.$row_shift_group.'~!~'.$row['absent_type'].'~!~'.$row['reason'].'&quot;)">';
			}

			echo '<td style="vertical-align: middle;">'.$c.'</td>';

			if (!empty($row['time_in'])) {
				echo '<td style="vertical-align: middle;"></td>';
				echo '<td style="vertical-align: middle;"></td>';
				echo '<td style="vertical-align: middle;"></td>';
			} else if (isset($_SESSION['emp_no_hr'])) {
				$disable_del_btn = "";
				if (!$row['absent_id']) {
					$disable_del_btn = "disabled";
				}
				echo '<td style="vertical-align: middle;">
						<button class="btn btn-danger btn-sm" id="absdelbtn_'.$c.'" data-absent_id="'.$row['absent_id'].'" onclick="delete_single_absences_report('.$c.',this)" '.$disable_del_btn.'><span class="fa fa-trash"></span></button>
					</td>';
				echo '<td style="vertical-align: middle;">
						<select class="form-control" id="absrd_'.$c.'" data-absent_id="'.$row['absent_id'].'" data-emp_no="'.$row['emp_no'].'" data-full_name="'.$row['full_name'].'" data-absent_day="'.$row_day.'" data-absent_shift_group="'.$row_shift_group.'" data-absent_type="'.$row['absent_type'].'" data-absent_reason="'.$row['reason'].'" onchange="update_reason('.$c.', this)">
							<option disabled selected value="">Select Reason</option>
							<option value="reason1">reason1</option>
							<option value="reason2">reason2</option>
							<option value="reason3">reason3</option>
							<option value="reason4">reason4</option>
						</select>
					</td>';
				echo '<td style="vertical-align: middle;">
						<select class="form-control" id="abstd_'.$c.'" data-absent_id="'.$row['absent_id'].'" data-emp_no="'.$row['emp_no'].'" data-full_name="'.$row['full_name'].'" data-absent_day="'.$row_day.'" data-absent_shift_group="'.$row_shift_group.'" data-absent_type="'.$row['absent_type'].'" data-absent_reason="'.$row['reason'].'" onchange="update_type_of_absent('.$c.', this)" disabled>
							<option disabled selected value="">Select Type of Absent</option>
						</select>
					</td>';
			} else if (($server_time < '06:00:00' && $day >= $server_date_only_2days_ago) || ($server_time >= '06:00:00' && $day >= $server_date_only_yesterday)) {
				$disable_del_btn = "";
				if (!$row['absent_id']) {
					$disable_del_btn = "disabled";
				}
				echo '<td style="vertical-align: middle;">
						<button class="btn btn-secondary btn-sm" id="absdelbtn_'.$c.'" data-absent_id="'.$row['absent_id'].'" onclick="delete_single_absences_report('.$c.',this)" '.$disable_del_btn.'><span class="fa fa-trash"></span></button>
					</td>';
				echo '<td style="vertical-align: middle;">
						<select class="form-control" id="absrd_'.$c.'" data-absent_id="'.$row['absent_id'].'" data-emp_no="'.$row['emp_no'].'" data-full_name="'.$row['full_name'].'" data-absent_day="'.$row_day.'" data-absent_shift_group="'.$row_shift_group.'" data-absent_type="'.$row['absent_type'].'" data-absent_reason="'.$row['reason'].'" onchange="update_reason('.$c.', this)">
							<option disabled selected value="">Select Reason</option>
							<option value="reason1">reason1</option>
							<option value="reason2">reason2</option>
							<option value="reason3">reason3</option>
							<option value="reason4">reason4</option>
						</select>
					</td>';
				echo '<td style="vertical-align: middle;">
						<select class="form-control" id="abstd_'.$c.'" data-absent_id="'.$row['absent_id'].'" data-emp_no="'.$row['emp_no'].'" data-full_name="'.$row['full_name'].'" data-absent_day="'.$row_day.'" data-absent_shift_group="'.$row_shift_group.'" data-absent_type="'.$row['absent_type'].'" data-absent_reason="'.$row['reason'].'" onchange="update_type_of_absent('.$c.', this)" disabled>
							<option disabled selected value="">Select Type of Absent</option>
						</select>
					</td>';
			} else {
				echo '<td style="vertical-align: middle;"></td>';
				echo '<td style="vertical-align: middle;"></td>';
				echo '<td style="vertical-align: middle;"></td>';
			}
			
			echo '<td style="vertical-align: middle;" id="abst_'.$c.'">'.$row['absent_type'].'</td>';
			$reason = $row['reason'];
			// if (strlen($reason) > 12) {
			// 	$reason = substr($reason, 0, 12) . "...";
			// }
			echo '<td style="vertical-align: middle;" id="absr_'.$c.'">'.$reason.'</td>';

			$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
			if (!empty($row['file_url'])) {
				echo '<td style="vertical-align: middle;"><img class="attendances_employee_picture_img_tag" src="'.htmlspecialchars($protocol.$_SERVER['SERVER_ADDR'].":".$_SERVER['SERVER_PORT'].$row['file_url']).'" alt="'.htmlspecialchars($row['emp_no']).'" height="75" width="75"></td>';
			} else {
				echo '<td style="vertical-align: middle;"><img class="attendances_employee_picture_img_tag" src="'.htmlspecialchars($protocol.$_SERVER['SERVER_ADDR'].":".$_SERVER['SERVER_PORT']).'/emp_mgt/dist/img/user.png" alt="'.htmlspecialchars($row['emp_no']).'" height="75" width="75"></td>';
			}

			echo '<td style="vertical-align: middle;">'.$row['emp_no'].'</td>';
			echo '<td style="vertical-align: middle;">'.$row['full_name'].'</td>';

			if (!empty($row['time_in'])) {
				echo '<td style="vertical-align: middle;">'.$row['time_in_day'].'</td>';
				echo '<td style="vertical-align: middle;">'.$row['time_in_shift'].'</td>';
				echo '<td style="vertical-align: middle;">'.$row['shift_group'].'</td>';
			} else {
				echo '<td style="vertical-align: middle;">'.$row_day.'</td>';
				echo '<td style="vertical-align: middle;">'.$row['shift'].'</td>';
				echo '<td style="vertical-align: middle;">'.$row['shift_group'].'</td>';
			}
			echo '<td style="vertical-align: middle;">'.$row['provider'].'</td>';
			
			echo '<td style="vertical-align: middle;">'.$row['dept'].'</td>';
			echo '<td style="vertical-align: middle;">'.$row['section'].'</td>';
			echo '<td style="vertical-align: middle;">'.$row['line_no'].'</td>';

			echo '</tr>';
		} while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
	} else {
		echo '<tr>';
			echo '<td colspan="15" style="text-align:center; color:red;">No Result !!!</td>';
		echo '</tr>';
	}
}

if ($method == 'count_attendance_list2') {
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
		if (isset($_POST['section']) && !empty($_POST['section'])) {
			$section = $_POST['section'];
		} else {
			$section = '';
		}
		if (isset($_POST['line_no']) && !empty($_POST['line_no'])) {
			$line_no = $_POST['line_no'];
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
		if (isset($_POST['section']) && !empty($_POST['section'])) {
			$section = $_POST['section'];
		} else {
			$section = '';
		}
		if (isset($_POST['line_no']) && !empty($_POST['line_no'])) {
			$line_no = $_POST['line_no'];
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
		if (isset($_POST['section']) && !empty($_POST['section'])) {
			$section = $_POST['section'];
		} else {
			$section = '';
		}
		if (isset($_POST['line_no']) && !empty($_POST['line_no'])) {
			$line_no = $_POST['line_no'];
		} else {
			$line_no = '';
		}
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
				emp.provider, emp.emp_no, emp.full_name, emp.dept, emp.section, emp.process, emp.skill_level, emp.line_no, emp.shift_group, emp.resigned_date,
				tio.time_in, tio.time_out, tio.day AS time_in_day, tio.shift AS time_in_shift, 
				absences.id AS absent_id, absences.day AS absent_day, absences.shift_group AS absent_shift_group, absences.absent_type, absences.reason, 
				pic.file_url 
			FROM m_employees emp
			LEFT JOIN t_time_in_out tio ON tio.emp_no = emp.emp_no AND tio.day = ? 
			LEFT JOIN t_absences absences ON absences.emp_no = emp.emp_no AND absences.day = ? 
			LEFT JOIN m_employee_pictures pic ON pic.emp_no = emp.emp_no
			WHERE emp.shift_group = ?";

	$params = [
		$day,
		$day,
		$shift_group
	];

	if (!empty($dept)) {
		$sql = $sql . " AND emp.dept LIKE ?";
		$dept_param = $dept . "%";
		$params[] = $dept_param;
	} else {
		$sql = $sql . " AND emp.dept != ''";
	}
	if (!empty($section)) {
		$sql = $sql . " AND emp.section LIKE ?";
		$section_param = $section . "%";
		$params[] = $section_param;
	}
	if ($line_no == 'No Line') {
		$sql = $sql . " AND emp.line_no IS NULL";
	} else if (!empty($line_no)) {
		$sql = $sql . " AND emp.line_no LIKE ?";
		$line_no_param = $line_no . "%";
		$params[] = $line_no_param;
	} else {
		$sql = $sql . " AND (emp.line_no = '' OR emp.line_no IS NULL)";
	}
	$sql = $sql . " AND (emp.date_hired <= ?) AND (emp.resigned_date IS NULL OR emp.resigned_date >= ?)";
	$params[] = $day;
	$params[] = $day;
	$sql = $sql . " ORDER BY emp.full_name ASC";

	// MySQL Query
	// $sql = $sql . " LIMIT ".$page_first_result.", ".$results_per_page;

	// MS SQL Server Query
	$sql = $sql . " OFFSET ".$page_first_result." ROWS FETCH NEXT ".$results_per_page." ROWS ONLY";

	$stmt = $conn->prepare($sql);
	$stmt->execute($params);

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($row) {
		do {
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

			echo '<td style="vertical-align: middle;">'.$c.'</td>';

			$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
			if (!empty($row['file_url'])) {
				echo '<td style="vertical-align: middle;"><img class="attendances_employee_picture_img_tag" src="'.htmlspecialchars($protocol.$_SERVER['SERVER_ADDR'].":".$_SERVER['SERVER_PORT'].$row['file_url']).'" alt="'.htmlspecialchars($row['emp_no']).'" height="75" width="75"></td>';
			} else {
				echo '<td style="vertical-align: middle;"><img class="attendances_employee_picture_img_tag" src="'.htmlspecialchars($protocol.$_SERVER['SERVER_ADDR'].":".$_SERVER['SERVER_PORT']).'/emp_mgt/dist/img/user.png" alt="'.htmlspecialchars($row['emp_no']).'" height="75" width="75"></td>';
			}

			if (!empty($row['time_in'])) {
				echo '<td style="vertical-align: middle;">'.$row['time_in_day'].'</td>';
				echo '<td style="vertical-align: middle;">'.$row['time_in_shift'].'</td>';
				echo '<td style="vertical-align: middle;">'.$row['shift_group'].'</td>';
			} else {
				echo '<td style="vertical-align: middle;">'.$row['absent_day'].'</td>';
				echo '<td style="vertical-align: middle;"></td>';
				echo '<td style="vertical-align: middle;">'.$row['absent_shift_group'].'</td>';
			}
			echo '<td style="vertical-align: middle;">'.$row['provider'].'</td>';
			echo '<td style="vertical-align: middle;">'.$row['emp_no'].'</td>';
			echo '<td style="vertical-align: middle;">'.$row['full_name'].'</td>';
			echo '<td style="vertical-align: middle;">'.$row['dept'].'</td>';
			echo '<td style="vertical-align: middle;">'.$row['section'].'</td>';
			echo '<td style="vertical-align: middle;">'.$row['line_no'].'</td>';
			echo '<td style="vertical-align: middle;">'.$row['process'].'</td>';
			if (!empty($row['skill_level'])) {
				echo '<td style="vertical-align: middle;">Level '.$row['skill_level'].'</td>';
			} else {
				echo '<td style="vertical-align: middle;">'.$row['skill_level'].'</td>';
			}
			echo '<td style="vertical-align: middle;">'.$row['time_in'].'</td>';
			echo '<td style="vertical-align: middle;">'.$row['time_out'].'</td>';
			echo '<td style="vertical-align: middle;">'.$row['absent_type'].'</td>';
			$reason = $row['reason'];
			// if (strlen($reason) > 12) {
			// 	$reason = substr($reason, 0, 12) . "...";
			// }
			echo '<td style="vertical-align: middle;">'.$reason.'</td>';

			echo '</tr>';
		} while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
	}else{
		echo '<tr>';
			echo '<td colspan="11" style="text-align:center; color:red;">No Result !!!</td>';
		echo '</tr>';
	}
}

if ($method == 'get_attendance_list_counting') {
	$day = $_POST['day'];
	$shift_group = $_POST['shift_group'];
	$attendance_status = 0;

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
		if (isset($_POST['attendance_status'])) {
			$attendance_status = intval($_POST['attendance_status']);
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

	if (!empty($attendance_status)) {
		switch ($attendance_status) {
			case 1:
				$sql = $sql . " AND tio.time_in IS NOT NULL";
				break;
			case 2:
				$sql = $sql . " AND tio.time_in IS NULL";
				break;
		}
	}

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
						(emp.date_hired <= ?) AND (emp.resigned_date IS NULL OR emp.resigned_date >= ?) 
					GROUP BY 
						emp.process";
	
	$params[] = $day;
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
		$sql = "INSERT INTO t_absences 
					(emp_no, day, shift_group, absent_type, reason) 
				VALUES 
					(?, ?, ?, ?, ?)";
		$stmt = $conn->prepare($sql);
		$params = array($emp_no, $absent_day, $absent_shift_group, $absent_type, $reason);
		if ($stmt->execute($params)) {
			echo 'success';
		}else{
			echo 'error';
		}
	} else {
		$sql = "UPDATE t_absences 
				SET emp_no = ?, day = ?, 
				shift_group = ?, absent_type = ?, 
				reason = ? 
				WHERE id = ?";
		$stmt = $conn->prepare($sql);
		$params = array($emp_no, $absent_day, $absent_shift_group, $absent_type, $reason, $id);
		if ($stmt->execute($params)) {
			echo 'success';
		}else{
			echo 'error';
		}
	}
}

if ($method == 'update_reason') {
	$id = $_POST['id'];
	$emp_no = trim($_POST['emp_no']);
	$absent_day = trim($_POST['absent_day']);
	$absent_shift_group = trim($_POST['absent_shift_group']);
	$reason = trim($_POST['reason']);
	$absent_type = '';
	$absent_category = '';

	$emp_no_session = '';

	if (isset($_SESSION['emp_no'])) {
		$emp_no_session = $_SESSION['emp_no'];
	} else if (isset($_SESSION['emp_no_control_area'])) {
		$emp_no_session = $_SESSION['emp_no_control_area'];
	} else if (isset($_SESSION['emp_no_hr'])) {
		$emp_no_session = $_SESSION['full_name']; // Temporary Since No HR Accounts
	} else {
		echo json_encode(['message' => 'session timeout. please relogin account']);
		$conn = null;
		exit();
	}

	$submitted_by_no = $emp_no_session;
	$updated_by_no = $emp_no_session;

	$insertedId = '';
	$message = '';

	$sql = "SELECT absent_category FROM m_absences_reasons WHERE reason = ?";

	$stmt = $conn->prepare($sql);
	$stmt->execute([$reason]);

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($row) {
		$absent_category = $row['absent_category'];
	}

	$params = [];

	if (empty($id)) {
		$sql = "INSERT INTO t_absences (emp_no, day, shift_group, reason, absent_category";

		$columns = [];
		$params = [$emp_no, $absent_day, $absent_shift_group, $reason, $absent_category];

		if (isset($_POST['absent_type'])) {
			$absent_type = trim($_POST['absent_type']);
			
			if (!empty($absent_type)) {
				$columns[] = 'absent_type'; // Add absent_type to columns
				$params[] = $absent_type; // Add the value to params
			} else {
				$response_arr = [
					'message' => 'error'
				];

				echo json_encode($response_arr);
				$conn = NULL;
				exit();
			}
		}

		$columns[] = 'submitted_by_no';
		$params[] = $submitted_by_no;

		// Append the additional columns to the SQL query
		if (count($columns) > 0) {
			$sql .= ", " . implode(", ", $columns) . ") OUTPUT INSERTED.id"; // Append column names
		} else {
			$sql .= ") OUTPUT INSERTED.id"; // Just close the insert columns section
		}

		// Complete the query with the VALUES part
		$sql .= " VALUES (?, ?, ?, ?, ?" . str_repeat(", ?", count($columns)) . ")";

		$stmt = $conn->prepare($sql);
		
		if ($stmt->execute($params)) {
			$insertedId = $stmt->fetchColumn();  // Fetch the inserted ID
			$message = 'success';
		} else {
			$message = 'error';
		}
	} else {
		$sql = "UPDATE t_absences SET reason = ?, absent_category = ?";

		$params = [$reason, $absent_category];

		if (isset($_POST['absent_type'])) {
			$absent_type = trim($_POST['absent_type']);
			$sql .= ", absent_type = ?";
			$params[] = $absent_type; // Add the value to params
		}

		$sql .= ", updated_by_no = ?";
		$params[] = $updated_by_no;

		$sql .= ", date_updated = ?";
		$params[] = $server_date_time;

		$sql .= " WHERE id = ?";
		$params[] = $id;

		$stmt = $conn->prepare($sql);

		if ($stmt->execute($params)) {
			$message = 'success';
		} else {
			$message = 'error';
		}
	}

	if (!empty($insertedId)) {
		$response_arr = [
			'id' => $insertedId,
			'message' => $message
		];
	} else {
		$response_arr = [
			'message' => $message
		];
	}

	echo json_encode($response_arr);
}

if ($method == 'update_type_of_absent') {
	$id = $_POST['id'];
	$absent_type = trim($_POST['absent_type']);
	$updated_by_no = '';

	if (isset($_SESSION['emp_no'])) {
		$updated_by_no = $_SESSION['emp_no'];
	} else if (isset($_SESSION['emp_no_control_area'])) {
		$updated_by_no = $_SESSION['emp_no_control_area'];
	} else if (isset($_SESSION['emp_no_hr'])) {
		$updated_by_no = $_SESSION['full_name']; // Temporary Since No HR Accounts
	} else {
		echo json_encode(['message' => 'session timeout. please relogin account']);
		$conn = null;
		exit();
	}

	$sql = "UPDATE t_absences 
			SET absent_type = ?, updated_by_no = ?, date_updated = ? 
			WHERE id = ?";
	$stmt = $conn->prepare($sql);
	$params = array($absent_type, $updated_by_no, $server_date_time, $id);
	if ($stmt->execute($params)) {
		$message = 'success';
	} else {
		$message = 'error';
	}

	$response_arr = [
		'message' => $message
	];
	
	echo json_encode($response_arr);
}

if ($method == 'delete_single_absences_report') {
	$id = $_POST['id'];

	$sql = "DELETE FROM t_absences WHERE id = ?";
	$stmt = $conn->prepare($sql);
	$params = array($id);
	if ($stmt->execute($params)) {
		$message = 'success';
	} else {
		$message = 'error';
	}

	$response_arr = [
		'message' => $message
	];
	
	echo json_encode($response_arr);
}

// Absences

if ($method == 'get_absences_list') {
	$day = $_POST['day'];
	$shift_group = $_POST['shift_group'];
	
	$server_date_only_2days_ago = date('Y-m-d',(strtotime('-1 day',strtotime($server_date_only_yesterday))));

	if (!isset($_SESSION['emp_no'])) {
		echo 'session timeout. please relogin account';
		$conn = null;
		exit();
	}

	$dept = $_SESSION['dept'];
	$section = $_SESSION['section'];
	$line_no = $_SESSION['line_no'];
	if (isset($_POST['attendance_status'])) {
		$attendance_status = intval($_POST['attendance_status']);
	}
	
	$c = 0;
	$row_class_arr = array('modal-trigger', 'modal-trigger bg-success', 'modal-trigger bg-danger');
	$row_class = $row_class_arr[0];

	$sql = "SELECT 
				emp.provider, emp.emp_no, emp.full_name, emp.dept, emp.section, emp.line_no, emp.shift, emp.shift_group, emp.resigned_date,
				tio.time_in, tio.day AS time_in_day, tio.shift AS time_in_shift, 
				absences.id AS absent_id, absences.day AS absent_day, absences.shift_group AS absent_shift_group, absences.absent_type, absences.reason,
				pic.file_url 
			FROM m_employees emp
			LEFT JOIN t_time_in_out tio ON tio.emp_no = emp.emp_no AND tio.day = ? 
			LEFT JOIN t_absences absences ON absences.emp_no = emp.emp_no AND absences.day = ? 
			LEFT JOIN m_employee_pictures pic ON pic.emp_no = emp.emp_no
			WHERE emp.shift_group = ? AND tio.time_in IS NULL";
			
	$params = [
		$day,
		$day,
		$shift_group
	];

	if (!empty($dept)) {
		$sql = $sql . " AND emp.dept LIKE ?";
		$dept_param = $dept . "%";
		$params[] = $dept_param;
	} else {
		$sql = $sql . " AND emp.dept != ''";
	}
	if (!empty($section)) {
		$sql = $sql . " AND emp.section LIKE ?";
		$section_param = $section . "%";
		$params[] = $section_param;
	}
	if (!empty($line_no)) {
		$sql = $sql . " AND emp.line_no LIKE ?";
		$line_no_param = $line_no . "%";
		$params[] = $line_no_param;
	}
	$sql = $sql . " AND (emp.date_hired <= ?) AND (emp.resigned_date IS NULL OR emp.resigned_date >= ?)";
	$params[] = $day;
	$params[] = $day;
	$sql = $sql . " ORDER BY emp.emp_no ASC";

	$stmt = $conn->prepare($sql);
	$stmt->execute($params);

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($row) {
		do {
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
				
				echo '<tr class="'.$row_class.'">';
				// echo '<tr style="cursor:pointer;" class="'.$row_class.'" data-toggle="modal" data-target="#absence_details" onclick="get_absence_details(&quot;'.$row['absent_id'].'~!~'.$row['emp_no'].'~!~'.$row['full_name'].'~!~'.$row_day.'~!~'.$row_shift_group.'~!~'.$row['absent_type'].'~!~'.$row['reason'].'&quot;)">';
			}

			echo '<td style="vertical-align: middle;">'.$c.'</td>';

			if (!empty($row['time_in'])) {
				echo '<td style="vertical-align: middle;"></td>';
				echo '<td style="vertical-align: middle;"></td>';
				echo '<td style="vertical-align: middle;"></td>';
			} if (($server_time < '06:00:00' && $day >= $server_date_only_2days_ago) || ($server_time >= '06:00:00' && $day >= $server_date_only_yesterday)) {
				$disable_del_btn = "";
				if (!$row['absent_id']) {
					$disable_del_btn = "disabled";
				}
				echo '<td style="vertical-align: middle;">
						<button class="btn btn-secondary btn-sm" id="absdelbtn_'.$c.'" data-absent_id="'.$row['absent_id'].'" onclick="delete_single_absences_report('.$c.',this)" '.$disable_del_btn.'><span class="fa fa-trash"></span></button>
					</td>';
				echo '<td style="vertical-align: middle;">
						<select class="form-control" id="absrd_'.$c.'" data-absent_id="'.$row['absent_id'].'" data-emp_no="'.$row['emp_no'].'" data-full_name="'.$row['full_name'].'" data-absent_day="'.$row_day.'" data-absent_shift_group="'.$row_shift_group.'" data-absent_type="'.$row['absent_type'].'" data-absent_reason="'.$row['reason'].'" onchange="update_reason('.$c.', this)">
							<option disabled selected value="">Select Reason</option>
							<option value="reason1">reason1</option>
							<option value="reason2">reason2</option>
							<option value="reason3">reason3</option>
							<option value="reason4">reason4</option>
						</select>
					</td>';
				echo '<td style="vertical-align: middle;">
						<select class="form-control" id="abstd_'.$c.'" data-absent_id="'.$row['absent_id'].'" data-emp_no="'.$row['emp_no'].'" data-full_name="'.$row['full_name'].'" data-absent_day="'.$row_day.'" data-absent_shift_group="'.$row_shift_group.'" data-absent_type="'.$row['absent_type'].'" data-absent_reason="'.$row['reason'].'" onchange="update_type_of_absent('.$c.', this)" disabled>
							<option disabled selected value="">Select Type of Absent</option>
						</select>
					</td>';
			} else {
				echo '<td style="vertical-align: middle;"></td>';
				echo '<td style="vertical-align: middle;"></td>';
				echo '<td style="vertical-align: middle;"></td>';
			}
			
			echo '<td style="vertical-align: middle;" id="abst_'.$c.'">'.$row['absent_type'].'</td>';
			$reason = $row['reason'];
			// if (strlen($reason) > 12) {
			// 	$reason = substr($reason, 0, 12) . "...";
			// }
			echo '<td style="vertical-align: middle;" id="absr_'.$c.'">'.$reason.'</td>';

			$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
			if (!empty($row['file_url'])) {
				echo '<td style="vertical-align: middle;"><img class="attendances_employee_picture_img_tag" src="'.htmlspecialchars($protocol.$_SERVER['SERVER_ADDR'].":".$_SERVER['SERVER_PORT'].$row['file_url']).'" alt="'.htmlspecialchars($row['emp_no']).'" height="75" width="75"></td>';
			} else {
				echo '<td style="vertical-align: middle;"><img class="attendances_employee_picture_img_tag" src="'.htmlspecialchars($protocol.$_SERVER['SERVER_ADDR'].":".$_SERVER['SERVER_PORT']).'/emp_mgt/dist/img/user.png" alt="'.htmlspecialchars($row['emp_no']).'" height="75" width="75"></td>';
			}

			echo '<td style="vertical-align: middle;">'.$row['emp_no'].'</td>';
			echo '<td style="vertical-align: middle;">'.$row['full_name'].'</td>';

			if (!empty($row['time_in'])) {
				echo '<td style="vertical-align: middle;">'.$row['time_in_day'].'</td>';
				echo '<td style="vertical-align: middle;">'.$row['time_in_shift'].'</td>';
				echo '<td style="vertical-align: middle;">'.$row['shift_group'].'</td>';
			} else {
				echo '<td style="vertical-align: middle;">'.$row['absent_day'].'</td>';
				echo '<td style="vertical-align: middle;">'.$row['shift'].'</td>';
				echo '<td style="vertical-align: middle;">'.$row['absent_shift_group'].'</td>';
			}
			echo '<td style="vertical-align: middle;">'.$row['provider'].'</td>';
			
			echo '<td style="vertical-align: middle;">'.$row['dept'].'</td>';
			echo '<td style="vertical-align: middle;">'.$row['section'].'</td>';
			echo '<td style="vertical-align: middle;">'.$row['line_no'].'</td>';

			echo '</tr>';
		} while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
	}
}

// Attendance Summary Report

if ($method == 'count_attendance_summary_report') {
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
					FORMAT(
							((NULLIF(COUNT(emp.emp_no), 0) - CAST(COUNT(tio.emp_no) AS FLOAT)) * 100) / NULLIF(COUNT(emp.emp_no), 0)
							, 'N2') AS absent_rate, 
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
						(emp.date_hired <= ?) AND (emp.resigned_date IS NULL OR emp.resigned_date >= ?) 
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
					FORMAT(
							((NULLIF(SUM(total), 0) - CAST(SUM(total_present) AS FLOAT)) * 100) / NULLIF(SUM(total), 0)
							, 'N2') AS absent_rate, 
					1 AS table_order
				FROM 
					AttendanceData
				ORDER BY 
					table_order ASC, shift_group ASC";
	
	$params[] = $day;
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
				.'~!~'.$row['attendance_percentage']
				.'~!~'.$row['absent_rate'].'&quot;)">';
			
		echo '<td>'.$c_label.'</td>';
		echo '<td'.$total_class.'>'.$row['shift_group'].'</td>';
		echo '<td>'.$row['dept'].'</td>';
		echo '<td>'.$row['section'].'</td>';
		echo '<td>'.$row['line_no'].'</td>';
		echo '<td'.$total_class.'>'.$row['total'].'</td>';
		echo '<td'.$total_class.'>'.$row['total_present'].'</td>';
		echo '<td'.$total_class.'>'.$row['total_absent'].'</td>';
		echo '<td'.$total_class.'>'.$row['attendance_percentage'].'%</td>';
		echo '<td'.$total_class.'>'.$row['absent_rate'].'%</td>';

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
				FORMAT(
					((NULLIF(COUNT(emp.emp_no), 0) - CAST(COUNT(tio.emp_no) AS FLOAT)) * 100) / NULLIF(COUNT(emp.emp_no), 0)
					, 'N2') AS absent_rate, 
				dr.ReportDate AS day
			FROM 
				DateRange dr
			LEFT JOIN 
				m_employees emp ON (emp.date_hired <= dr.ReportDate) AND (emp.resigned_date IS NULL OR emp.resigned_date >= dr.ReportDate)
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
				<th>Absent Rate</th>
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
		echo '<td>'.$row['absent_rate'].'%</td>';

		echo '</tr>';
	}

	echo '</tbody></table>';
}

$conn = NULL;
