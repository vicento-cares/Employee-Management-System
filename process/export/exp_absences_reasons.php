<?php
require('../conn.php');

$c = 0;

$delimiter = ","; 

$filename = "absences_reasons_list.csv";
 
// Create a file pointer 
$f = fopen('php://memory', 'w'); 

// UTF-8 BOM for special character compatibility
fputs($f, "\xEF\xBB\xBF");

// Set column headers 
$fields = array('#', 'Absent Category', 'Absent Type', 'Reason'); 
fputcsv($f, $fields, $delimiter);

$sql = "SELECT absent_category, absent_type, reason FROM m_absences_reasons";

$stmt = $conn->prepare($sql);
$stmt->execute();

$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {

    // Output each row of the data, format line as csv and write to file pointer 
    do {
        $lineData = array(++$c, $row['absent_category'], $row['absent_type'], $row['reason']);
        fputcsv($f, $lineData, $delimiter);
    } while ($row = $stmt->fetch(PDO::FETCH_ASSOC));

} else {

	// Output each row of the data, format line as csv and write to file pointer 
    $lineData = array("NO DATA FOUND"); 
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
