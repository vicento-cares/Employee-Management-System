<?php
// error_reporting(0);
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

function get_dept($conn)
{
    $data = array();

    $sql = "SELECT dept FROM m_dept ORDER BY dept ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($data, $row['dept']);
    }

    return $data;
}

function get_sections($conn)
{
    $data = array();

    $sql = "SELECT section FROM m_access_locations GROUP BY section ORDER BY section ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($data, $row['section']);
    }

    //QA QC section
    array_push($data, "QC");

    return $data;
}

function get_lines($conn)
{
    $data = array();

    $sql = "SELECT line_no FROM m_access_locations GROUP BY line_no ORDER BY line_no ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($data, $row['line_no']);
    }

    return $data;
}

// Remove UTF-8 BOM
function removeBomUtf8($s)
{
    if (substr($s, 0, 3) == chr(hexdec('EF')) . chr(hexdec('BB')) . chr(hexdec('BF'))) {
        return substr($s, 3);
    } else {
        return $s;
    }
}

// parse Date
function parseDate($date_sample) {
    // Define an array of possible date formats
    $formats = [
        'm/d/Y', // MM/DD/YYYY
        'd/m/Y', // DD/MM/YYYY
        'Y-m-d', // YYYY-MM-DD
        'm-d-Y', // MM-DD-YYYY
        'd-m-Y', // DD-MM-YYYY
        'Y/m/d', // YYYY/MM/DD
        'd/m/y', // DD/MM/YY
        'm/d/y', // MM/DD/YY
        // Add more formats as needed
    ];

    foreach ($formats as $format) {
        $dateTime = DateTime::createFromFormat($format, $date_sample);
        if ($dateTime) {
            return $dateTime; // Return the DateTime object
        }
    }

    // If no format matched, return an error or handle it as needed
    return "Invalid date format: " . htmlspecialchars($date_sample);
}

function check_csv($file, $conn)
{
    // READ FILE
    $csvFile = fopen($file, 'r');

    // SKIP FIRST LINE (HEADER)
    $first_line = fgets($csvFile);
    // Remove UTF-8 BOM from First Line
    $first_line = removeBomUtf8($first_line);

    // SKIP SECOND LINE (EXAMPLE ROW)
    fgets($csvFile);

    $dept_arr = get_dept($conn);
    $section_arr = get_sections($conn);
    $line_arr = get_lines($conn);

    $hasError = 0;
    $hasBlankError = 0;
    $isDuplicateOnCsv = 0;
    $hasBlankErrorArr = array();
    $isDuplicateOnCsvArr = array();
    $dup_temp_arr = array();

    $row_valid_arr = array(0, 0, 0, 0, 0);

    $notExistsDeptArr = array();
    $notExistsSectionArr = array();
    $notExistsLineNoArr = array();
    $notValidDateEffectivityArr = array();
    $notAllowedDateEffectivityArr = array();

    $message = "";
    $check_csv_row = 0;

    // CHECK CSV BASED ON HEADER
    $first_line = preg_replace('/[\t\n\r]+/', '', $first_line);
    $valid_first_line = "Employee No.,Employee Transfer Type,Department To,Section To,Line No. To,Date Effectivity,Reason";
    $valid_first_line2 = '"Employee No.","Employee Transfer Type","Department To","Section To","Line No. To","Date Effectivity",Reason';
    if ($first_line != $valid_first_line || $first_line != $valid_first_line2) {
        $message = $message . 'Invalid CSV Table Header. Maybe an incorrect CSV file or incorrect CSV header ';
        return $message;
    }

    while (($line = fgetcsv($csvFile)) !== false) {
        // Check if the row is blank or consists only of whitespace
        if (empty(implode('', $line))) {
            continue; // Skip blank lines
        }

        $check_csv_row++;

        $emp_no = trim($line[0]);
        $emp_transfer_type = trim($line[1]);
        $dept_to = trim($line[2]);
        $section_to = trim($line[3]);
        $line_no_to = trim($line[4]);
        $date_effectivity = trim($line[5]);
        $reason = trim($line[6]);

        $date_effectivity_valid = str_replace('/', '-', $date_effectivity);
        $is_valid_date_effectivity = validate_date($date_effectivity_valid);

        if ($emp_no == '' || $emp_transfer_type == '' || 
            $dept_to == '' || $section_to == '' || 
            $section_to == '' || $line_no_to = '' || 
            $date_effectivity == '') {
            // IF BLANK DETECTED ERROR += 1
            $hasBlankError++;
            $hasError = 1;
            array_push($hasBlankErrorArr, $check_csv_row);
        }

        // CHECK ROW VALIDATION
        if (!empty($dept_to)) {
            if (!in_array($dept_to, $dept_arr)) {
                $hasError = 1;
                $row_valid_arr[0] = 1;
                array_push($notExistsDeptArr, $check_csv_row);
            }
        }
        if (!empty($section_to)) {
            if (!in_array($section_to, $section_arr)) {
                $hasError = 1;
                $row_valid_arr[1] = 1;
                array_push($notExistsSectionArr, $check_csv_row);
            }
        }
        if (!empty($line_no_to)) {
            if (!in_array($line_no_to, $line_arr)) {
                $hasError = 1;
                $row_valid_arr[2] = 1;
                array_push($notExistsLineNoArr, $check_csv_row);
            }
        }
        if (!empty($date_effectivity)) {
            if ($is_valid_date_effectivity == false) {
                $hasError = 1;
                $row_valid_arr[3] = 1;
                array_push($notValidDateEffectivityArr, $check_csv_row);
            } else {
                $result = parseDate($date_effectivity);

                // Check if the result is a DateTime object or an error message
                if ($result instanceof DateTime) {
                    $date_effectivity = $result->format('Y-m-d'); // Outputs: 2025-05-28
                    $server_date_only = date('Y-m-d');
                    if ($date_effectivity < $server_date_only) {
                        $hasError = 1;
                        $row_valid_arr[4] = 1;
                        array_push($notAllowedDateEffectivityArr, $check_csv_row);
                    }
                } else {
                    $hasError = 1;
                    $row_valid_arr[3] = 1;
                    array_push($notValidDateEffectivityArr, $check_csv_row);
                }
            }
        }

        // Joining all row values for checking duplicated rows
        $whole_line = join(',', $line);

        // CHECK ROWS IF IT HAS DUPLICATE ON CSV
        if (isset($dup_temp_arr[$whole_line])) {
            $isDuplicateOnCsv = 1;
            $hasError = 1;
            array_push($isDuplicateOnCsvArr, $check_csv_row);
        } else {
            $dup_temp_arr[$whole_line] = 1;
        }
    }

    fclose($csvFile);

    if ($hasError == 1) {
        if ($row_valid_arr[0] == 1) {
            $message = $message . 'Department doesn\'t exists on row/s ' . implode(", ", $notExistsDeptArr) . '. ';
        }
        if ($row_valid_arr[1] == 1) {
            $message = $message . 'Section doesn\'t exists on row/s ' . implode(", ", $notExistsSectionArr) . '. ';
        }
        if ($row_valid_arr[2] == 1) {
            $message = $message . 'Line No. doesn\'t exists row/s ' . implode(", ", $notExistsLineNoArr) . '. ';
        }
        if ($row_valid_arr[3] == 1) {
            $message = $message . 'Invalid Date Effectivity on row/s ' . implode(", ", $notValidDateEffectivityArr) . '. ';
        }
        if ($row_valid_arr[4] == 1) {
            $message = $message . 'Late Date Effectivity is not allowed on row/s ' . implode(", ", $notAllowedDateEffectivityArr) . '. ';
        }

        if ($hasBlankError >= 1) {
            $message = $message . 'Blank Cell Exists on row/s ' . implode(", ", $hasBlankErrorArr) . '. ';
        }
        if ($isDuplicateOnCsv == 1) {
            $message = $message . 'Duplicated Record/s on row/s ' . implode(", ", $isDuplicateOnCsvArr) . '. ';
        }
    }
    return $message;
}

if (!isset($_SESSION['emp_no_control_area'])) {
    echo 'Session Expired. Please Re-Login your account!';
    exit();
}

$csvMimes = array(
    'text/x-comma-separated-values',
    'text/comma-separated-values',
    'application/octet-stream',
    'application/vnd.ms-excel',
    'application/x-csv',
    'text/x-csv',
    'text/csv',
    'application/csv',
    'application/excel',
    'application/vnd.msexcel',
    'text/plain'
);

if (empty($_FILES['file']['name']) && !in_array($_FILES['file']['type'], $csvMimes)) {
    echo 'INVALID FILE FORMAT!';
    exit();
}

if (!is_uploaded_file($_FILES['file']['tmp_name'])) {
    echo 'CSV FILE NOT UPLOADED!';
    exit();
}

require '../conn.php';

$chkCsvMsg = check_csv($_FILES['file']['tmp_name'], $conn);

if ($chkCsvMsg != '') {
    echo $chkCsvMsg;
    $conn = null;
    exit();
}

//READ FILE
$csvFile = fopen($_FILES['file']['tmp_name'], 'r');

// SKIP FIRST LINE (HEADER)
fgets($csvFile);

// SKIP SECOND LINE (EXAMPLE ROW)
fgets($csvFile);

// PARSE
$error = 0;

$isTransactionActive = false;
$chunkSize = 250; // Set your desired chunk size

try {
    if (!$isTransactionActive) {
        $conn->beginTransaction();
        $isTransactionActive = true;
    }

    $sql = "INSERT INTO t_employee_transfer 
                (emp_transfer_id, emp_no, emp_transfer_type, dept_to, section_to, line_no_to, emp_js_s, emp_js_s_no, reason, date_effectivity) 
            VALUES ";
    $values = [];
    $placeholders = [];

    while (($line = fgetcsv($csvFile)) !== false) {
        // Check if the row is blank or consists only of whitespace
        if (empty(implode('', $line))) {
            continue; // Skip blank lines
        }

        $emp_no = $line[0];
        $emp_transfer_type = $line[1];
        $dept_to = $line[2];
        $section_to = $line[3];
        $line_no_to = $line[4];
        $date_effectivity = $line[5];
        $reason = $line[6];
        $emp_js_s = $_SESSION['full_name'];
        $emp_js_s_no = $_SESSION['emp_js_s_no'];

        $emp_transfer_id = str_replace('.', '', uniqid($emp_transfer_id_prefix, true));

        // Create a temporary array for the current row
        $currentValues = [
            $emp_transfer_id,
            $emp_no,
            $emp_transfer_type,
            $dept_to,
            $section_to,
            $line_no_to,
            $emp_js_s,
            $emp_js_s_no,
            $reason,
            $date_effectivity
        ];

        // Create placeholders for each row
        $generated_placeholders = implode(',', array_fill(0, count($currentValues), '?'));
        $placeholders[] = "($generated_placeholders)";

        // Add current values to the main values array
        $values = array_merge($values, $currentValues);

        // Check if we reached the chunk size
        if (count($placeholders) === $chunkSize) {
            // Combine the SQL statement with the placeholders
            $sql .= implode(', ', $placeholders);
            
            // Prepare the statement
            $stmt = $conn->prepare($sql);
            
            // Execute the statement with the values
            if (!$stmt->execute($values)) {
                $error++;
            }

            // Reset for the next chunk
            $placeholders = [];
            $values = [];
            $sql = "INSERT INTO t_employee_transfer 
                        (emp_transfer_id, emp_no, emp_transfer_type, dept_to, section_to, line_no_to, emp_js_s, emp_js_s_no, reason, date_effectivity) 
                    VALUES ";
        }
    }

    // Insert any remaining rows that didn't fill a complete chunk
    if (!empty($placeholders)) {
        $sql .= implode(', ', $placeholders);
        $stmt = $conn->prepare($sql);
        if (!$stmt->execute($values)) {
            $error++;
        }
    }

    if ($error > 0) {
        if ($isTransactionActive) {
            $conn->rollBack();
            $isTransactionActive = false;
        }
        echo 'Failed. Please Try Again or Call IT Personnel Immediately!';
        exit();
    }

    $conn->commit();
    $isTransactionActive = false;
} catch (Exception $e) {
    if ($isTransactionActive) {
        $conn->rollBack();
        $isTransactionActive = false;
    }

    echo 'Failed. Please Try Again or Call IT Personnel Immediately!: ' . $e->getMessage();

    $conn = null;
    exit();
}

fclose($csvFile);

$conn = null;
