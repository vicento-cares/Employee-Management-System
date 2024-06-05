<?php
session_name("emp_mgt");
session_start();

require('../conn.php');

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

function get_attendance_list_line_support_to($search_arr, $conn) {
	$table_data = array();

	$c = 0;

	$sql = "SELECT 
	emp.provider, emp.emp_no, emp.full_name, emp.dept, emp.section, emp.process, emp.line_no, emp.shift_group, emp.resigned_date, 
	tio.shift, tio.time_in, tio.time_out, tio.ip
		FROM m_employees emp
		LEFT JOIN t_time_in_out tio ON tio.emp_no = emp.emp_no
		LEFT JOIN t_line_support_history lsh ON lsh.emp_no = emp.emp_no
		WHERE tio.day = '".$search_arr['day']."' AND lsh.day = '".$search_arr['day']."'
		AND emp.shift_group = '".$search_arr['shift_group']."'";

	if (!empty($search_arr['dept'])) {
		$sql = $sql . " AND emp.dept LIKE '".$search_arr['dept']."%'";
	} else {
		$sql = $sql . " AND emp.dept != ''";
	}

	$sql = $sql . " AND lsh.line_no_to LIKE '".$search_arr['line_no']."%' AND lsh.status = 'accepted'
		AND (emp.resigned_date IS NULL OR emp.resigned_date = '0000-00-00' OR emp.resigned_date >= '".$search_arr['day']."')
		ORDER BY emp.full_name ASC";

	$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $row){
			$c++;
			$row_section = '';
			$row_line_no = '';
			$row_status = '';

			if (!empty($row['section'])) {
				$row_section = $row['section'];
			} else {
				$row_section = 'N/A';
			}
			if (!empty($row['line_no'])) {
				$row_line_no = $row['line_no'];
			} else {
				$row_line_no = 'N/A';
			}
			if (!empty($row['time_in'])) {
				$row_status = 'Present';
			} else {
				$row_status = 'Absent';
			}

			$table_data[] = array($c, $row['provider'], $row['emp_no'], $row['full_name'], $row['dept'], $row_section, $row_line_no, $row['process'], $row['shift_group'], $row['shift'], $row['time_in'], $row['time_out'], $row['ip'], $row_status);
		}
	}

	$response_array = array(
		"table_data" => $table_data,
		"c" => $c
	);

	return $response_array;
}

$day = $_GET['day'];
$shift_group = $_GET['shift_group'];
$dept = $_GET['dept'];
$section = $_GET['section'];
$line_no = $_GET['line_no'];

$c = 0;

$delimiter = ","; 

$filename = "EmpMgtSys_AttendanceList_";
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

$search_arr = array(
	"day" => $day,
	"shift_group" => $shift_group,
	"dept" => $dept,
	"section" => $section,
	"line_no" => $line_no
);
 
// Create a file pointer 
$f = fopen('php://memory', 'w'); 

// UTF-8 BOM for special character compatibility
fputs($f, "\xEF\xBB\xBF");
 
// Set column headers 
$fields = array('#', 'Provider', 'ID No.', 'Name', 'Department', 'Section', 'Line No.', 'Process', 'Shift Group', 'Shift', 'Time In', 'Time Out', 'IP', 'Status'); 
fputcsv($f, $fields, $delimiter); 

$table_data = array();

$attendance_list_line_support_to_arr = get_attendance_list_line_support_to($search_arr, $conn);
$table_data = $attendance_list_line_support_to_arr["table_data"];
$c += $attendance_list_line_support_to_arr["c"];

foreach ($table_data as $table_row) {
	$lineData = $table_row; 
    fputcsv($f, $lineData, $delimiter);
}

/*$sql = "SELECT 
	emp.provider, emp.emp_no, emp.full_name, emp.dept, emp.section, emp.line_no, emp.resigned_date, 
	tio.time_in
	FROM m_employees emp
	LEFT JOIN t_time_in_out AS tio 
		ON emp.emp_no = tio.emp_no 
		AND tio.day = '$day' 
		AND tio.shift = '$shift'
	WHERE emp.dept = '$dept'";*/
$sql = "SELECT 
	emp.provider, emp.emp_no, emp.full_name, emp.dept, emp.section, emp.process, emp.line_no, emp.shift_group, emp.resigned_date, 
	tio.shift, tio.time_in, tio.time_out, tio.ip
	FROM m_employees emp
	LEFT JOIN t_time_in_out AS tio ON emp.emp_no = tio.emp_no 
	LEFT JOIN t_line_support_history lsh ON lsh.emp_no = emp.emp_no AND lsh.day = '$day'
	WHERE tio.day = '$day' AND ((emp.shift_group = '$shift_group'";
if (!empty($dept)) {
	$sql = $sql . " AND emp.dept LIKE '$dept%'";
} else {
	$sql = $sql . " AND emp.dept != ''";
}
if (!empty($section)) {
	$sql = $sql . " AND emp.section = '$section'";
}
if ($line_no == 'No Line') {
    $sql = $sql . " AND emp.line_no IS NULL";
} else if (!empty($line_no)) {
    $sql = $sql . " AND emp.line_no LIKE '$line_no%'";
} else {
    $sql = $sql . " AND (emp.line_no = '' OR emp.line_no IS NULL)";
}
$sql = $sql . ") AND (lsh.line_no_from IS NULL OR lsh.status != 'accepted'))";
$sql = $sql . " AND (emp.resigned_date IS NULL OR emp.resigned_date = '0000-00-00' OR emp.resigned_date >= '$day')";
$sql = $sql . " ORDER BY emp.emp_no ASC";

$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt->execute();
if ($stmt -> rowCount() > 0) {
     
    // Output each row of the data, format line as csv and write to file pointer 
    while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) { 
    	$c++;
    	$row_section = '';
    	$row_line_no = '';
    	$row_status = '';
        if (!empty($row['section'])) {
			$row_section = $row['section'];
		} else {
			$row_section = 'N/A';
		}
		if (!empty($row['line_no'])) {
			$row_line_no = $row['line_no'];
		} else {
			$row_line_no = 'N/A';
		}
		if (!empty($row['time_in'])) {
			$row_status = 'Present';
		} else {
			$row_status = 'Absent';
		}

        $lineData = array($c, $row['provider'], $row['emp_no'], $row['full_name'], $row['dept'], $row_section, $row_line_no, $row['process'], $row['shift_group'], $row['shift'], $row['time_in'], $row['time_out'], $row['ip'], $row_status); 
        fputcsv($f, $lineData, $delimiter);
    } 
}

// Move back to beginning of file 
fseek($f, 0); 
 
// Set headers to download file rather than displayed 
header('Content-Type: text/csv'); 
header('Content-Disposition: attachment; filename="' . $filename . '";'); 
 
//output all remaining data on a file pointer 
fpassthru($f); 

$conn = null;

?>