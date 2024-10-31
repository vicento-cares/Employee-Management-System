<?php
require ('../conn.php');

$emp_id = $_GET['emp_id'] ?? '';
$category = $_GET['category'] ?? '';
$pro = $_GET['pro'] ?? '';
$date = $_GET['date'] ?? '';
$date_authorized = $_GET['date_authorized'] ?? '';
$fullname = $_GET['fullname'] ?? '';

if (empty($category)) {
    echo 'Please select a category.';
    exit;
}

$c = 0;
$delimiter = ",";
$datenow = date('Y-m-d');
$filename = "E-Record_Data_" . $datenow . ".csv";

// Create a file pointer
$f = fopen('php://memory', 'w');

// Output the UTF-8 BOM for Excel compatibility
fputs($f, "\xEF\xBB\xBF");

// Set column headers
$fields = array('#', 'Process Name', 'Authorization No.', 'Authorization Year', 'Date Authorized', 'Expire Date', 'Employee Name', 'Employee No.', 'Batch No.', 'Department', 'Remarks', 'Reason of Cancellation', 'Date of Cancellation');
fputcsv($f, $fields, $delimiter);

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
            SELECT emp.line_no, emp.section, 
                    a.batch, a.process, a.auth_no, a.auth_year, a.date_authorized, a.expire_date, 
                    a.r_of_cancellation, a.d_of_cancellation, a.remarks, a.i_status, a.r_status, 
                    b.fullname, b.agency, a.dept, b.emp_id, 
                    ROW_NUMBER() OVER (PARTITION BY a.emp_id, a.auth_no ORDER BY a.auth_year DESC) AS rn 
            FROM $table_name a 
            LEFT JOIN [qualif].[dbo].[t_employee_m] b ON a.emp_id = b.emp_id AND a.batch = b.batch 
            LEFT JOIN m_employees emp ON a.emp_id=emp.emp_no 
            JOIN LatestAuth la ON a.emp_id = la.emp_id AND a.auth_no = la.auth_no AND a.auth_year = la.latest_auth_year 
            WHERE a.i_status = 'Approved'";

if (!empty($emp_id)) {
    $query .= " AND (b.emp_id = '$emp_id' OR b.emp_id_old = '$emp_id')";
}

if (!empty($fullname)) {
    $query .= " AND b.fullname LIKE '$fullname%'";
}

if (!empty($pro)) {
    $query .= " AND a.process LIKE '$pro%'";
}

if (!empty($date)) {
    $query .= " AND a.expire_date = '$date'";
}

if (!empty($date_authorized)) {
    $query .= " AND a.date_authorized = '$date_authorized'";
}

$query .= ") SELECT *
            FROM RankedAuth
            WHERE rn = 1";

$query .= " ORDER BY process ASC, fullname ASC, auth_year DESC";

// Prepare and execute query with batch processing
$stmt = $conn->prepare($query);

// Execute the statement in batches
$stmt->execute();

// Stream output directly to the browser in chunks
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $c++;
    // Sanitize line breaks and spaces in fields
    foreach ($row as $key => $value) {
        $row[$key] = str_replace(["\r", "\n"], " ", $value);
    }

    // Prepare data for CSV
    $lineData = array(
        $c,
        $row['process'],
        $row['auth_no'],
        $row['auth_year'],
        $row['date_authorized'],
        $row['expire_date'],
        $row['fullname'],
        $row['emp_id'],
        $row['batch'],
        $row['dept'],
        $row['remarks'],
        $row['r_of_cancellation'],
        $row['d_of_cancellation']
    );
    fputcsv($f, $lineData, $delimiter);
}

// Move back to the beginning of the file
fseek($f, 0);

// Set headers for download
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $filename . '";');
header('Pragma: no-cache');
header('Expires: 0');

// Output all remaining data on a file pointer
fpassthru($f);

// Close the connection
$conn = null;