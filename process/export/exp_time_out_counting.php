<?php
// http://172.25.112.131/emp_mgt/process/export/exp_time_out_counting.php?day=2024-04-01
require('../conn.php');

switch (true) {
    case !isset($_GET['day']):
        echo 'Query Parameters Not Set';
        exit;
}

$day = $_GET['day'];
$day_tomorrow = date('Y-m-d',(strtotime('+1 day',strtotime($day))));

$c = 0;

$delimiter = ","; 

$filename = "EmpMgtSys_TimeOutCounting_";
$filename = $filename . $day.".csv";
 
// Create a file pointer 
$f = fopen('php://memory', 'w'); 

// UTF-8 BOM for special character compatibility
fputs($f, "\xEF\xBB\xBF");

// Set column headers 
$fields = array('Department', 'Section', 'WT', '0', '0.5', '1', '1.5', '2', '3', 'Total', 'Average OT');
fputcsv($f, $fields, $delimiter);
// echo var_dump($fields);
// echo "<br>";

$results = array();

$sql = "SELECT dept, section FROM m_employees GROUP BY dept, section";

$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt->execute();
if ($stmt->rowCount() > 0) {
    foreach($stmt->fetchALL() as $row) {
        array_push($results, array('dept' => $row['dept'], 'section' => $row['section'], 'total_0' => 0, 'total_0_5' => 0, 'total_1' => 0, 'total_1_5' => 0, 'total_2' => 0, 'total_3' => 0));
    }
}

// echo var_dump($results);
// echo "<br>";

// Queries for Time Out Count

// OUT 3
$sql = "SELECT emp.dept, emp.section, count(tio.id) AS total_0 FROM t_time_in_out tio 
    LEFT JOIN m_employees emp
    ON tio.emp_no = emp.emp_no
    WHERE tio.day = '$day' AND (tio.time_out BETWEEN '$day 15:00:00' AND '$day 15:59:59') OR (tio.time_out BETWEEN '$day_tomorrow 03:00:00' AND '$day_tomorrow 03:59:59')
    OR tio.day = '$day' AND tio.time_out IS NULL
    GROUP BY emp.dept, emp.section";

$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt->execute();
if ($stmt->rowCount() > 0) {
    while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
        foreach ($results as &$result) {
            if ($result['dept'] == $row['dept'] && $result['section'] == $row['section']) {
                $result['total_0'] = intval($row['total_0']);
                break; // exit the loop once you've found and updated the process
            }
        }
        unset($result); // unset reference to last element
    }
}

// echo var_dump($results);
// echo "<br>";

// OUT 4
$sql = "SELECT emp.dept, emp.section, count(tio.id) AS total_1 FROM t_time_in_out tio
    LEFT JOIN m_employees emp
    ON tio.emp_no = emp.emp_no
    WHERE tio.day = '$day' AND (tio.time_out BETWEEN '$day 16:00:00' AND '$day 16:59:59') OR (tio.time_out BETWEEN '$day_tomorrow 04:00:00' AND '$day_tomorrow 04:59:59')
    GROUP BY emp.dept, emp.section";

$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt->execute();
if ($stmt->rowCount() > 0) {
    while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
        foreach ($results as &$result) {
            if ($result['dept'] == $row['dept'] && $result['section'] == $row['section']) {
                $result['total_1'] = intval($row['total_1']);
                break; // exit the loop once you've found and updated the process
            }
        }
        unset($result); // unset reference to last element
    }
}

// echo var_dump($results);
// echo "<br>";

// OUT 5
$sql = "SELECT emp.dept, emp.section, count(tio.id) AS total_2 FROM t_time_in_out tio 
    LEFT JOIN m_employees emp
    ON tio.emp_no = emp.emp_no
    WHERE tio.day = '$day' AND (tio.time_out BETWEEN '$day 17:00:00' AND '$day 17:59:59') OR (tio.time_out BETWEEN '$day_tomorrow 05:00:00' AND '$day_tomorrow 05:59:59')
    GROUP BY emp.dept, emp.section";

$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt->execute();
if ($stmt->rowCount() > 0) {
    while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
        foreach ($results as &$result) {
            if ($result['dept'] == $row['dept'] && $result['section'] == $row['section']) {
                $result['total_2'] = intval($row['total_2']);
                break; // exit the loop once you've found and updated the process
            }
        }
        unset($result); // unset reference to last element
    }
}

// echo var_dump($results);
// echo "<br>";

// OUT 6
$sql = "SELECT emp.dept, emp.section, count(tio.id) AS total_3 FROM t_time_in_out tio 
    LEFT JOIN m_employees emp
    ON tio.emp_no = emp.emp_no
    WHERE tio.day = '$day' AND (tio.time_out BETWEEN '$day 18:00:00' AND '$day 18:59:59') OR (tio.time_out BETWEEN '$day_tomorrow 06:00:00' AND '$day_tomorrow 06:59:59')
    GROUP BY emp.dept, emp.section";

$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt->execute();
if ($stmt->rowCount() > 0) {
    while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
        foreach ($results as &$result) {
            if ($result['dept'] == $row['dept'] && $result['section'] == $row['section']) {
                $result['total_3'] = intval($row['total_3']);
                break; // exit the loop once you've found and updated the process
            }
        }
        unset($result); // unset reference to last element
    }
}

// echo var_dump($results);
// echo "<br>";

// Output each row of the data, format line as csv and write to file pointer 
foreach ($results as &$result) {
    $total = $result['total_0'] + $result['total_1'] + $result['total_2'] + $result['total_3'];

    $average_ot = 0;
    if ($total > 0) {
        $average_ot = round((($result['total_0'] * 0) + ($result['total_1'] * 1) + ($result['total_2'] * 2) + ($result['total_3'] * 3)) / $total, 2);
    }
    
	$lineData = array($result['dept'], $result['section'], 'Manpower', $result['total_0'], '0', $result['total_1'], '0', $result['total_2'], $result['total_3'], $total, $average_ot); 
	fputcsv($f, $lineData, $delimiter);
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