<?php
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

require('../conn.php');

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

function count_emp_tio2($search_arr, $conn) {
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
	if ($search_arr['line_no'] == 'No Line') {
		$sql = $sql . " AND emp.line_no IS NULL";
	} else if (!empty($search_arr['line_no'])) {
		$sql = $sql . " AND emp.line_no LIKE '".$search_arr['line_no']."%'";
	} else {
		$sql = $sql . " AND (emp.line_no = '' OR emp.line_no IS NULL)";
	}
	$sql = $sql . " AND (emp.resigned_date IS NULL OR emp.resigned_date >= '".$search_arr['day']."')";
	$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $j){
			$total = intval($j['total']);
		}
	}else{
		$total = 0;
	}

	$total += count_line_support_to($search_arr, $conn);
	// $total += count_line_support_from_rejected($search_arr, $conn);
	$total -= count_line_support_from($search_arr, $conn);

	return $total;
}

switch (true) {
    case !isset($_GET['day']):
    case !isset($_GET['shift_group']):
    case !isset($_GET['dept']):
    case !isset($_GET['section']):
    case !isset($_GET['line_no']):
        echo 'Query Parameters Not Set';
        exit;
        break;
}

$day = $_GET['day'];
$shift_group = $_GET['shift_group'];
$dept = $_GET['dept'];
$section = $_GET['section'];
$line_no = $_GET['line_no'];

$search_arr = array(
    "day" => $day,
    "shift_group" => $shift_group,
    "dept" => $dept,
    "section" => $section,
    "line_no" => $line_no
);

$total_mp = count_attendance_list2($search_arr, $conn);
$total_present_mp = count_emp_tio2($search_arr, $conn);
$total_absent_mp = $total_mp - $total_present_mp;

$c = 0;

$delimiter = ","; 

$filename = "EmpMgtSys_AttendanceCounting_";
if (!empty($dept)) {
	$filename = $filename . $dept . "-";
}
if (!empty($section)) {
	$filename = $filename . $section . "-";
}
if (!empty($line_no)) {
	$filename = $filename . $line_no . "-";
}
$filename = $filename . $day."-".$shift_group.".csv";
 
// Create a file pointer 
$f = fopen('php://memory', 'w'); 

// UTF-8 BOM for special character compatibility
fputs($f, "\xEF\xBB\xBF");
 
// Set column headers 
$fields = array('#', 'Process', 'Present', 'Absent', 'Total MP'); 
fputcsv($f, $fields, $delimiter); 

$results = array();

// Get list of processes with total mp based on Employee Masterlist

//MySQL
// $sql = "SELECT IFNULL(process, 'No Process') AS process1, 
// 		COUNT(emp_no) AS total 
// 	FROM m_employees 
// 	WHERE shift_group = '$shift_group'";
//MS SQL Server
$sql = "SELECT ISNULL(process, 'No Process') AS process1, 
		COUNT(emp_no) AS total 
	FROM m_employees 
	WHERE shift_group = '$shift_group'";
if (!empty($dept)) {
	$sql = $sql . " AND dept LIKE '$dept%'";
} else {
	$sql = $sql . " AND dept != ''";
}
if (!empty($section)) {
	$sql = $sql . " AND section LIKE '$section%'";
}
if ($line_no == 'No Line') {
    $sql = $sql . " AND line_no IS NULL";
} else if (!empty($line_no)) {
    $sql = $sql . " AND line_no LIKE '$line_no%'";
} else {
    $sql = $sql . " AND (line_no = '' OR line_no IS NULL)";
}
$sql = $sql . " AND (resigned_date IS NULL OR resigned_date >= '$day')";
$sql = $sql . " GROUP BY process";

$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt->execute();
if ($stmt->rowCount() > 0) {
	while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
		array_push($results, array('process' => $row['process1'], 'total_present' => 0, 'total' => intval($row['total'])));
	}
}

// Get list of processes with total mp based on Line Support

// Update Total based on Line Support To
//MySQL
// $sql = "SELECT IFNULL(emp.process, 'No Process') AS process1, 
// 		COUNT(emp.emp_no) AS total 
// 	FROM m_employees emp 
// 	LEFT JOIN t_line_support_history lsh ON lsh.emp_no = emp.emp_no  AND lsh.day = '$day'
// 	WHERE emp.shift_group = '$shift_group'";
//MS SQL Server
$sql = "SELECT ISNULL(emp.process, 'No Process') AS process1, 
		COUNT(emp.emp_no) AS total 
	FROM m_employees emp 
	LEFT JOIN t_line_support_history lsh ON lsh.emp_no = emp.emp_no  AND lsh.day = '$day'
	WHERE emp.shift_group = '$shift_group'";
if (!empty($dept)) {
	$sql = $sql . " AND emp.dept LIKE '$dept%'";
} else {
	$sql = $sql . " AND emp.dept != ''";
}
if (!empty($line_no)) {
	$sql = $sql . " AND lsh.line_no_to LIKE '$line_no%'";
}
$sql = $sql . " AND lsh.status = 'accepted'";
$sql = $sql . " GROUP BY emp.process";

$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt->execute();
if ($stmt->rowCount() > 0) {
	while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
		$additional_process = true;
		foreach ($results as &$result) {
			if ($result['process'] == $row['process1']) {
				$result['total'] += intval($row['total']);
				$additional_process = false;
				break; // exit the loop once you've found and updated the process
			}
		}
		
		unset($result); // unset reference to last element

		if ($additional_process == true) {
			array_push($results, array('process' => $row['process1'], 'total_present' => 0, 'total' => intval($row['total'])));
		}
	}
}

// Update Total based on Line Support From Rejected
//MySQL
// $sql = "SELECT IFNULL(emp.process, 'No Process') AS process1, 
// 		COUNT(emp.emp_no) AS total 
// 	FROM m_employees emp 
// 	LEFT JOIN t_line_support_history lsh ON lsh.emp_no = emp.emp_no  AND lsh.day = '$day'
// 	WHERE emp.shift_group = '$shift_group'";
//MS SQL Server
$sql = "SELECT ISNULL(emp.process, 'No Process') AS process1, 
		COUNT(emp.emp_no) AS total 
	FROM m_employees emp 
	LEFT JOIN t_line_support_history lsh ON lsh.emp_no = emp.emp_no  AND lsh.day = '$day'
	WHERE emp.shift_group = '$shift_group'";
if (!empty($dept)) {
	$sql = $sql . " AND emp.dept LIKE '$dept%'";
} else {
	$sql = $sql . " AND emp.dept != ''";
}
if (!empty($line_no)) {
	$sql = $sql . " AND lsh.line_no_from LIKE '$line_no%'";
}
$sql = $sql . " AND lsh.status = 'rejected'";
$sql = $sql . " GROUP BY emp.process";

$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt->execute();
if ($stmt->rowCount() > 0) {
	while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
		$additional_process = true;
		foreach ($results as &$result) {
			if ($result['process'] == $row['process1']) {
				// $result['total'] += intval($row['total']);
				$additional_process = false;
				break; // exit the loop once you've found and updated the process
			}
		}
		
		unset($result); // unset reference to last element

		// if ($additional_process == true) {
		// 	array_push($results, array('process' => $row['process1'], 'total_present' => 0, 'total' => intval($row['total'])));
		// }
	}
}

// Update Total based on Line Support From
//MySQL
// $sql = "SELECT IFNULL(emp.process, 'No Process') AS process1, 
// 		COUNT(emp.emp_no) AS total 
// 	FROM m_employees emp 
// 	LEFT JOIN t_line_support_history lsh ON lsh.emp_no = emp.emp_no  AND lsh.day = '$day'
// 	WHERE emp.shift_group = '$shift_group'";
//MS SQL Server
$sql = "SELECT ISNULL(emp.process, 'No Process') AS process1, 
		COUNT(emp.emp_no) AS total 
	FROM m_employees emp 
	LEFT JOIN t_line_support_history lsh ON lsh.emp_no = emp.emp_no  AND lsh.day = '$day'
	WHERE emp.shift_group = '$shift_group'";
if (!empty($dept)) {
	$sql = $sql . " AND emp.dept LIKE '$dept%'";
} else {
	$sql = $sql . " AND emp.dept != ''";
}
if (!empty($line_no)) {
	$sql = $sql . " AND lsh.line_no_from LIKE '$line_no%'";
}
$sql = $sql . " AND lsh.status = 'accepted'";
$sql = $sql . " GROUP BY emp.process";

$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt->execute();
if ($stmt->rowCount() > 0) {
	while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
		foreach ($results as &$result) {
			if ($result['process'] == $row['process1']) {
				$result['total'] -= intval($row['total']);
				break; // exit the loop once you've found and updated the process
			}
		}

		unset($result); // unset reference to last element
	}

	// Filter the results array to remove processes with total less than 1
	// $results = array_filter($results, function($result) {
	// 	return $result['total'] >= 1;
	// });
}

// Update total_present from list of processes based on t_time_in_out

//MySQL
// $sql = "SELECT IFNULL(emp.process, 'No Process') AS process, 
// 		COUNT(tio.emp_no) AS total_present 
// 	FROM t_time_in_out tio 
// 	LEFT JOIN m_employees emp 
// 	ON tio.emp_no = emp.emp_no 
// 	WHERE tio.day = '$day' AND shift_group = '$shift_group'";
//MS SQL Server
$sql = "SELECT ISNULL(emp.process, 'No Process') AS process, 
		COUNT(tio.emp_no) AS total_present 
	FROM t_time_in_out tio 
	LEFT JOIN m_employees emp 
	ON tio.emp_no = emp.emp_no 
	WHERE tio.day = '$day' AND shift_group = '$shift_group'";
if (!empty($dept)) {
	$sql = $sql . " AND emp.dept LIKE '$dept%'";
} else {
	$sql = $sql . " AND emp.dept != ''";
}
if (!empty($section)) {
	$sql = $sql . " AND emp.section LIKE '$section%'";
}
if ($line_no == 'No Line') {
    $sql = $sql . " AND line_no IS NULL";
} else if (!empty($line_no)) {
    $sql = $sql . " AND line_no LIKE '$line_no%'";
} else {
    $sql = $sql . " AND (line_no = '' OR line_no IS NULL)";
}
$sql = $sql . " AND (emp.resigned_date IS NULL OR emp.resigned_date >= '$day')";
$sql = $sql . " GROUP BY emp.process";

$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt->execute();
if ($stmt->rowCount() > 0) {
	while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
		foreach ($results as &$result) {
			if ($result['process'] == $row['process']) {
				$result['total_present'] = intval($row['total_present']);
				break; // exit the loop once you've found and updated the process
			}
		}
		unset($result); // unset reference to last element
	}
}

// Update total_present from list of processes based on t_time_in_out and Line Support

// Update Total Present based on Line Support To
//MySQL
// $sql = "SELECT IFNULL(emp.process, 'No Process') AS process1, 
// 		COUNT(tio.emp_no) AS total_present 
// 	FROM t_time_in_out tio 
// 	LEFT JOIN m_employees emp ON emp.emp_no = tio.emp_no AND tio.day = '$day' 
// 	LEFT JOIN t_line_support_history lsh ON lsh.emp_no = emp.emp_no  AND lsh.day = '$day'
// 	WHERE emp.shift_group = '$shift_group'";
//MS SQL Server
$sql = "SELECT ISNULL(emp.process, 'No Process') AS process1, 
		COUNT(tio.emp_no) AS total_present 
	FROM t_time_in_out tio 
	LEFT JOIN m_employees emp ON emp.emp_no = tio.emp_no AND tio.day = '$day' 
	LEFT JOIN t_line_support_history lsh ON lsh.emp_no = emp.emp_no  AND lsh.day = '$day'
	WHERE emp.shift_group = '$shift_group'";
if (!empty($dept)) {
	$sql = $sql . " AND emp.dept LIKE '$dept%'";
} else {
	$sql = $sql . " AND emp.dept != ''";
}
if (!empty($line_no)) {
	$sql = $sql . " AND lsh.line_no_to LIKE '$line_no%'";
}
$sql = $sql . " AND lsh.status = 'accepted'";
$sql = $sql . " GROUP BY emp.process";

$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt->execute();
if ($stmt->rowCount() > 0) {
	while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
		foreach ($results as &$result) {
			if ($result['process'] == $row['process1']) {
				$result['total_present'] += intval($row['total_present']);
				break; // exit the loop once you've found and updated the process
			}
		}
		
		unset($result); // unset reference to last element
	}
}

// Update Total Present based on Line Support From Rejected
//MySQL
// $sql = "SELECT IFNULL(emp.process, 'No Process') AS process1, 
// 		COUNT(tio.emp_no) AS total_present 
// 	FROM t_time_in_out tio 
// 	LEFT JOIN m_employees emp ON emp.emp_no = tio.emp_no AND tio.day = '$day' 
// 	LEFT JOIN t_line_support_history lsh ON lsh.emp_no = emp.emp_no  AND lsh.day = '$day'
// 	WHERE emp.shift_group = '$shift_group'";
//MS SQL Server
$sql = "SELECT ISNULL(emp.process, 'No Process') AS process1, 
		COUNT(tio.emp_no) AS total_present 
	FROM t_time_in_out tio 
	LEFT JOIN m_employees emp ON emp.emp_no = tio.emp_no AND tio.day = '$day' 
	LEFT JOIN t_line_support_history lsh ON lsh.emp_no = emp.emp_no  AND lsh.day = '$day'
	WHERE emp.shift_group = '$shift_group'";
if (!empty($dept)) {
	$sql = $sql . " AND emp.dept LIKE '$dept%'";
} else {
	$sql = $sql . " AND emp.dept != ''";
}
if (!empty($line_no)) {
	$sql = $sql . " AND lsh.line_no_from LIKE '$line_no%'";
}
$sql = $sql . " AND lsh.status = 'rejected'";
$sql = $sql . " GROUP BY emp.process";

$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt->execute();
if ($stmt->rowCount() > 0) {
	while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
		foreach ($results as &$result) {
			if ($result['process'] == $row['process1']) {
				// $result['total_present'] += intval($row['total_present']);
				break; // exit the loop once you've found and updated the process
			}
		}
		
		unset($result); // unset reference to last element
	}
}

// Update Total Present based on Line Support From
//MySQL
// $sql = "SELECT IFNULL(emp.process, 'No Process') AS process1, 
// 		COUNT(tio.emp_no) AS total_present 
// 	FROM t_time_in_out tio 
// 	LEFT JOIN m_employees emp ON emp.emp_no = tio.emp_no AND tio.day = '$day' 
// 	LEFT JOIN t_line_support_history lsh ON lsh.emp_no = emp.emp_no  AND lsh.day = '$day'
// 	WHERE emp.shift_group = '$shift_group'";
//MS SQL Server
$sql = "SELECT ISNULL(emp.process, 'No Process') AS process1, 
		COUNT(tio.emp_no) AS total_present 
	FROM t_time_in_out tio 
	LEFT JOIN m_employees emp ON emp.emp_no = tio.emp_no AND tio.day = '$day' 
	LEFT JOIN t_line_support_history lsh ON lsh.emp_no = emp.emp_no  AND lsh.day = '$day'
	WHERE emp.shift_group = '$shift_group'";
if (!empty($dept)) {
	$sql = $sql . " AND emp.dept LIKE '$dept%'";
} else {
	$sql = $sql . " AND emp.dept != ''";
}
if (!empty($line_no)) {
	$sql = $sql . " AND lsh.line_no_from LIKE '$line_no%'";
}
$sql = $sql . " AND lsh.status = 'accepted'";
$sql = $sql . " GROUP BY emp.process";

$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt->execute();
if ($stmt->rowCount() > 0) {
	while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
		foreach ($results as &$result) {
			if ($result['process'] == $row['process1']) {
				$result['total_present'] -= intval($row['total_present']);
				break; // exit the loop once you've found and updated the process
			}
		}

		unset($result); // unset reference to last element
	}
}

// Output each row of the data, format line as csv and write to file pointer 
foreach ($results as &$result) {
	$c++;

	$total = intval($result['total']);
	$total_present = intval($result['total_present']);
	$total_absent = $total - $total_present;
        
	$lineData = array($c, $result['process'], $result['total_present'], $total_absent, $result['total']); 
	fputcsv($f, $lineData, $delimiter);
}

$lineData = array("Total MP :", "", $total_present_mp, $total_absent_mp, $total_mp); 
fputcsv($f, $lineData, $delimiter);

// Move back to beginning of file 
fseek($f, 0); 
 
// Set headers to download file rather than displayed 
header('Content-Type: text/csv'); 
header('Content-Disposition: attachment; filename="' . $filename . '";'); 
 
//output all remaining data on a file pointer 
fpassthru($f); 

$conn = null;

?>