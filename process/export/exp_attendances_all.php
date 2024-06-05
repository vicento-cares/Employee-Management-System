<?php
require('../conn.php');

switch (true) {
    case !isset($_GET['day']):
        echo 'Query Parameters Not Set';
        exit;
}

$day = $_GET['day'];

$c = 0;

$delimiter = ","; 

$filename = "EmpMgtSys_AttendanceList_".$day.".csv";
 
// Create a file pointer 
$f = fopen('php://memory', 'w'); 

// UTF-8 BOM for special character compatibility
fputs($f, "\xEF\xBB\xBF");
 
// Set column headers 
$fields = array('#', 'Provider', 'ID No.', 'Name', 'Department', 'Section', 'Line No.', 'Process', 'Shift Group', 'Shift', 'Time In', 'Time Out', 'OT', 'IP', 'Status'); 
fputcsv($f, $fields, $delimiter); 

$sql = "SELECT 
	emp.provider, emp.emp_no, emp.full_name, emp.dept, emp.section, emp.process, emp.line_no, emp.shift_group, emp.resigned_date, 
	tio.shift, tio.time_in, tio.time_out, tio.ip
	FROM m_employees emp
	LEFT JOIN t_time_in_out AS tio 
		ON emp.emp_no = tio.emp_no
	WHERE tio.day = '$day'";
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
        $ot = 0;

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

        // Count Number of OT Hours
        if (!empty($row['time_out'])) {
            $time_out_hr = date('H', strtotime($row['time_out']));

            switch ($time_out_hr) {
                case 4:
                case 16:
                    $ot = 1;
                    break;
                case 5:
                case 17:
                    $ot = 2;
                    break;
                case 6:
                case 18:
                    $ot = 3;
                    break;
                default:
                    $ot = 0;
            }
        }

        $lineData = array($c, $row['provider'], $row['emp_no'], $row['full_name'], $row['dept'], $row_section, $row_line_no, $row['process'], $row['shift_group'], $row['shift'], $row['time_in'], $row['time_out'], $ot, $row['ip'], $row_status); 
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