<?php
// http://172.25.112.131/emp_mgt/process/export/exp_time_out_counting.php?day=2024-04-01&shift=DS&shift_group=A&dept=&section=&line_no=
require('../conn.php');

switch (true) {
    case !isset($_GET['day']):
    case !isset($_GET['shift']):
    case !isset($_GET['shift_group']):
	case !isset($_GET['dept']):
    case !isset($_GET['section']):
    case !isset($_GET['line_no']):
        echo 'Query Parameters Not Set';
        exit;
}

$day = $_GET['day'];
$shift = $_GET['shift'];
$shift_group = $_GET['shift_group'];
$dept = $_GET['dept'];
$section = $_GET['section'];
$line_no = $_GET['line_no'];

$c = 0;

$delimiter = ","; 

$filename = "EmpMgtSys_TimeOutCounting_";
if (!empty($dept)) {
	$filename = $filename . $dept . "-";
}
if (!empty($section)) {
	$filename = $filename . $section . "-";
}
if (!empty($line_no)) {
	$filename = $filename . $line_no . "-";
}
$filename = $filename . $day."-".$shift."-".$shift_group.".csv";
 
// Create a file pointer 
$f = fopen('php://memory', 'w'); 

// UTF-8 BOM for special character compatibility
fputs($f, "\xEF\xBB\xBF");

// Set column headers 
$fields = array('WT', '0', '0.5', '1', '1.5', '2', '3');
fputcsv($f, $fields, $delimiter);

$total_mp_3 = 0;
$total_mp_4 = 0;
$total_mp_5 = 0;
$total_mp_6 = 0;

// Queries for Time Out Count
if ($shift == 'DS') {
    // OUT 3
    $sql = "SELECT count(tio.id) AS total FROM t_time_in_out tio
        LEFT JOIN m_employees emp
        ON tio.emp_no = emp.emp_no
        WHERE tio.day = '$day' AND emp.shift_group IN ('$shift_group', 'ADS') AND tio.time_out BETWEEN '$day 15:00:00' AND '$day 15:29:59'";
    if (!empty($line_no)) {
        $sql = $sql . " AND emp.line_no = '$line_no'";
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        foreach($stmt->fetchALL() as $row){
            $total_mp_3 = intval($row['total']);
        }
    }

    // OUT 4
    $sql = "SELECT count(tio.id) AS total FROM t_time_in_out tio
        LEFT JOIN m_employees emp
        ON tio.emp_no = emp.emp_no
        WHERE tio.day = '$day' AND emp.shift_group IN ('$shift_group', 'ADS') AND tio.time_out BETWEEN '$day 15:30:00' AND '$day 16:29:59'";
    if (!empty($line_no)) {
        $sql = $sql . " AND emp.line_no = '$line_no'";
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        foreach($stmt->fetchALL() as $row){
            $total_mp_4 = intval($row['total']);
        }
    }

    // OUT 5
    $sql = "SELECT count(tio.id) AS total FROM t_time_in_out tio
        LEFT JOIN m_employees emp
        ON tio.emp_no = emp.emp_no
        WHERE tio.day = '$day' AND emp.shift_group IN ('$shift_group', 'ADS') AND tio.time_out BETWEEN '$day 16:30:00' AND '$day 17:29:59'";
    if (!empty($line_no)) {
        $sql = $sql . " AND emp.line_no = '$line_no'";
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        foreach($stmt->fetchALL() as $row){
            $total_mp_5 = intval($row['total']);
        }
    }

    // OUT 6
    $sql = "SELECT count(tio.id) AS total FROM t_time_in_out tio
        LEFT JOIN m_employees emp
        ON tio.emp_no = emp.emp_no
        WHERE tio.day = '$day' AND emp.shift_group IN ('$shift_group', 'ADS') AND tio.time_out BETWEEN '$day 17:30:00' AND '$day 18:29:59'";
    if (!empty($line_no)) {
        $sql = $sql . " AND emp.line_no = '$line_no'";
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        foreach($stmt->fetchALL() as $row){
            $total_mp_6 = intval($row['total']);
        }
    }
} else if ($shift == 'NS') {
    // OUT 3
    $sql = "SELECT count(tio.id) AS total FROM t_time_in_out tio
        LEFT JOIN m_employees emp
        ON tio.emp_no = emp.emp_no
        WHERE tio.day = '$day' AND emp.shift_group = '$shift_group' AND tio.time_out BETWEEN '$day 03:00:00' AND '$day 03:29:59'";
    if (!empty($line_no)) {
        $sql = $sql . " AND emp.line_no = '$line_no'";
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        foreach($stmt->fetchALL() as $row){
            $total_mp_3 = intval($row['total']);
        }
    }

    // OUT 4
    $sql = "SELECT count(tio.id) AS total FROM t_time_in_out tio
        LEFT JOIN m_employees emp
        ON tio.emp_no = emp.emp_no
        WHERE tio.day = '$day' AND emp.shift_group = '$shift_group' AND tio.time_out BETWEEN '$day 03:30:00' AND '$day 04:29:59'";
    if (!empty($line_no)) {
        $sql = $sql . " AND emp.line_no = '$line_no'";
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        foreach($stmt->fetchALL() as $row){
            $total_mp_4 = intval($row['total']);
        }
    }

    // OUT 5
    $sql = "SELECT count(tio.id) AS total FROM t_time_in_out tio
        LEFT JOIN m_employees emp
        ON tio.emp_no = emp.emp_no
        WHERE tio.day = '$day' AND emp.shift_group = '$shift_group' AND tio.time_out BETWEEN '$day 04:30:00' AND '$day 05:29:59'";
    if (!empty($line_no)) {
        $sql = $sql . " AND emp.line_no = '$line_no'";
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        foreach($stmt->fetchALL() as $row){
            $total_mp_5 = intval($row['total']);
        }
    }

    // OUT 6
    $sql = "SELECT count(tio.id) AS total FROM t_time_in_out tio
        LEFT JOIN m_employees emp
        ON tio.emp_no = emp.emp_no
        WHERE tio.day = '$day' AND emp.shift_group = '$shift_group' AND tio.time_out BETWEEN '$day 05:30:00' AND '$day 06:29:59'";
    if (!empty($line_no)) {
        $sql = $sql . " AND emp.line_no = '$line_no'";
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        foreach($stmt->fetchALL() as $row){
            $total_mp_6 = intval($row['total']);
        }
    }
}

// Results
$fields = array('Manpower', $total_mp_3, '0', $total_mp_4, '0', $total_mp_5, $total_mp_6); 
fputcsv($f, $fields, $delimiter); 

// Move back to beginning of file 
fseek($f, 0); 
 
// Set headers to download file rather than displayed 
header('Content-Type: text/csv'); 
header('Content-Disposition: attachment; filename="' . $filename . '";'); 
 
//output all remaining data on a file pointer 
fpassthru($f); 

$conn = null;

?>