<?php
session_name("emp_mgt");
session_start();

require('../conn.php');

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
		foreach($stmt->fetchALL() as $j){
			$total = intval($j['total']);
		}
	}else{
		$total = 0;
	}
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

// $search_arr = array(
//     "day" => $day,
//     "shift_group" => $shift_group,
//     "dept" => $dept,
//     "section" => $section,
//     "line_no" => $line_no
// );

// $total_mp = count_attendance_list($search_arr, $conn);
// $total_present_mp = count_emp_tio($search_arr, $conn);
// $total_absent_mp = $total_mp - $total_present_mp;
// if ($total_mp != 0) {
//     $total_attendance_percentage = round(($total_present_mp / $total_mp) * 100, 2);
// } else {
//     $total_attendance_percentage = 0;
// }

$c = 0;

$delimiter = ","; 

$filename = "EmpMgtSys_AttendanceSummaryReport_";
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
$fields = array('#', 'Shift Group', 'Department', 'Section', 'Line No.', 'Total MP', 'Present', 'Absent', 'Percentage'); 
fputcsv($f, $fields, $delimiter); 

$results = array();

//MySQL
$sql = "SELECT shift_group, dept, section, IFNULL(line_no, 'No Line') AS line_no1, 
        COUNT(emp_no) AS total 
    FROM m_employees 
    WHERE shift_group = '$shift_group'";
//MS SQL Server
// $sql = "SELECT shift_group, dept, section, ISNULL(line_no, 'No Line') AS line_no1, 
// 	COUNT(emp_no) AS total 
// 	FROM m_employees 
// 	WHERE shift_group = '$shift_group'";
if (!empty($dept)) {
    $sql = $sql . " AND dept LIKE '$dept%'";
} else {
    $sql = $sql . " AND dept != ''";
}
if (!empty($section)) {
    $sql = $sql . " AND section LIKE '$section%'";
}
if (!empty($line_no)) {
    $sql = $sql . " AND line_no LIKE '$line_no%'";
}
$sql = $sql . " AND (resigned_date IS NULL OR resigned_date >= '$day')";
$sql = $sql . " GROUP BY dept, section, line_no, shift_group";

$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt->execute();
if ($stmt->rowCount() > 0) {
    while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
        array_push($results, array('shift_group' => $row['shift_group'], 'dept' => $row['dept'], 'section' => $row['section'], 'line_no' => $row['line_no1'], 'total_present' => 0, 'total' => $row['total']));
    }
}

//MySQL
$sql = "SELECT IFNULL(emp.line_no, 'No Line') AS line_no1, section, dept,
        COUNT(tio.emp_no) AS total_present 
    FROM t_time_in_out tio 
    LEFT JOIN m_employees emp 
    ON tio.emp_no = emp.emp_no 
    WHERE tio.day = '$day' AND emp.shift_group = '$shift_group'";
//MS SQL Server
// $sql = "SELECT ISNULL(emp.line_no, 'No Line') AS line_no1, section, dept,
// 	COUNT(tio.emp_no) AS total_present 
// 	FROM t_time_in_out tio 
// 	LEFT JOIN m_employees emp 
// 	ON tio.emp_no = emp.emp_no 
// 	WHERE tio.day = '$day' AND emp.shift_group = '$shift_group'";
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
$sql = $sql . " GROUP BY emp.dept, emp.section, emp.line_no";

$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt->execute();
if ($stmt->rowCount() > 0) {
    while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
        foreach ($results as &$result) {
            if ($result['line_no'] == $row['line_no1'] && $result['section'] == $row['section'] && $result['dept'] == $row['dept']) {
                $result['total_present'] = $row['total_present'];
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
    if ($total != 0) {
        $attendance_percentage = round(($total_present / $total) * 100, 2);
    } else {
        $attendance_percentage = 0;
    }
        
	$lineData = array($c, $result['shift_group'], $result['dept'], $result['section'], $result['line_no'], $result['total'], $result['total_present'], $total_absent, $attendance_percentage); 
	fputcsv($f, $lineData, $delimiter);
}

// $lineData = array("Total MP :", "", "", "", "", $total_mp, $total_present_mp, $total_absent_mp, $total_attendance_percentage); 
// fputcsv($f, $lineData, $delimiter);

// Move back to beginning of file 
fseek($f, 0); 
 
// Set headers to download file rather than displayed 
header('Content-Type: text/csv'); 
header('Content-Disposition: attachment; filename="' . $filename . '";'); 
 
//output all remaining data on a file pointer 
fpassthru($f); 

$conn = null;

?>