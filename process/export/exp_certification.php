<?php
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

require ('../conn.php');

$emp_id = $_GET['emp_id'] ?? '';
$category = $_GET['category'] ?? '';
$pro = $_GET['pro'] ?? '';
$date = $_GET['date'] ?? '';
$date_authorized = $_GET['date_authorized'] ?? '';
$fullname = $_GET['fullname'] ?? '';

$dept = '';
$section = '';

if (isset($_SESSION['emp_no_control_area'])) {
    $dept = $_SESSION['dept'];
    $section = $_SESSION['section'];
} else {
    $dept = $_GET['dept'] ?? '';
    $section = $_GET['section'] ?? '';
}

$line_no = $_GET['line_no'] ?? '';

if (empty($category)) {
    echo 'Please select a category.';
    exit;
}

$c = 0;
$delimiter = ",";
$datenow = date('Y-m-d');
$filename = "EmpMgtSys_Certification_" . $datenow . ".csv";

// Create a file pointer
$f = fopen('php://memory', 'w');

// Output the UTF-8 BOM for Excel compatibility
fputs($f, "\xEF\xBB\xBF");

// Set column headers
$fields = array('#', 'Process Name', 'Authorization No.', 'Authorization Year', 'Date Authorized', 'Expire Date', 'Employee Name', 'Employee No.', 'Batch No.', 'Department', 'Section', 'Line No.', 'Skill Level',  'Remarks', 'Reason of Cancellation', 'Date of Cancellation');
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
            SELECT emp.dept, emp.line_no, emp.section, 
                    a.batch, a.process, a.auth_no, a.auth_year, a.date_authorized, a.expire_date, 
                    a.r_of_cancellation, a.d_of_cancellation, a.remarks, a.i_status, a.r_status, 
                    b.fullname, b.agency, b.emp_id, 
                    sl.id AS skill_level_id, sl.skill_level, 
                    ROW_NUMBER() OVER (PARTITION BY a.emp_id, a.auth_no ORDER BY a.auth_year DESC) AS rn 
            FROM $table_name a 
            LEFT JOIN [qualif].[dbo].[t_employee_m] b ON a.emp_id = b.emp_id AND a.batch = b.batch 
            LEFT JOIN m_employees emp ON a.emp_id=emp.emp_no 
            LEFT JOIN m_skill_level sl ON a.emp_id = sl.emp_no AND a.process = sl.process 
            JOIN LatestAuth la ON a.emp_id = la.emp_id AND a.auth_no = la.auth_no AND a.auth_year = la.latest_auth_year 
            WHERE a.i_status = 'Approved'";

$params = [];

if (!empty($emp_id)) {
    $query .= " AND (b.emp_id = ? OR b.emp_id_old = ?)";
    $params[] = $emp_id;
    $params[] = $emp_id;
}

if (!empty($fullname)) {
    $query .= " AND b.fullname LIKE ?";
    $fullname_search = $fullname . "%";
    $params[] = $fullname_search;
}

if (!empty($pro)) {
    $query .= " AND a.process LIKE ?";
    $pro_search = $pro . "%";
    $params[] = $pro_search;
}

if (!empty($date)) {
    $query .= " AND a.expire_date = ?";
    $params[] = $date;
}

if (!empty($date_authorized)) {
    $query .= " AND a.date_authorized = ?";
    $params[] = $date_authorized;
}

if (!empty($dept)) {
    $query.= " AND emp.dept LIKE ?";
    $dept_search = $dept . "%";
    $params[] = $dept_search;
}

if (!empty($section)) {
    $query .= " AND emp.section LIKE ?";
    $section_search = $section . "%";
    $params[] = $section_search;
}

if (!empty($line_no)) {
    $query .= " AND emp.line_no LIKE ?";
    $line_no_search = $line_no . "%";
    $params[] = $line_no_search;
}

$query .= ") SELECT *
            FROM RankedAuth
            WHERE rn = 1";

$query .= " ORDER BY process ASC, fullname ASC, auth_year DESC";

// Prepare and execute query with batch processing
$stmt = $conn->prepare($query);

// Execute the statement in batches
$stmt->execute($params);

// Stream output directly to the browser in chunks
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $c++;
    // Sanitize line breaks and spaces in fields
    foreach ($row as $key => $value) {
        $row[$key] = str_replace(["\r", "\n"], " ", $value);
    }

    $row_skill_level = '';

    if (!empty($row['skill_level'])) {
        $row_skill_level = 'Level ' . $row['skill_level'];
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
        $row['section'],
        $row['line_no'],
        $row_skill_level,
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
