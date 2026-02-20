<?php
// error_reporting(0);
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

if (!isset($_SESSION['emp_no_hr']) || !isset($_SESSION['emp_no_control_area'])) {
    switch (true) {
        case isset($_SESSION['emp_no']):
            header('location:/emp_mgt/admin');
            exit();
        case isset($_SESSION['emp_no_user']):
            header('location:/emp_mgt/user');
            exit();
        case isset($_SESSION['emp_no_clinic']):
            header('location:/emp_mgt/clinic');
            exit();
        case isset($_SESSION['emp_no_tc']):
            header('location:/emp_mgt/tc');
            exit();
    }
} else {
    switch (true) {
        case !isset($_SESSION['emp_no_hr']):
            header('location:/emp_mgt/hr');
            exit();
        case !isset($_SESSION['emp_no_control_area']):
            header('location:/emp_mgt/control_area');
            exit();
    }
}


require '../lib/validate.php';

function get_absences_reasons($conn)
{
    $sql = "SELECT reason, absent_type, absent_category FROM m_absences_reasons ORDER BY reason ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $data;
}

function process_absences_data($conn)
{
    $absences_reasons_data = get_absences_reasons($conn);

    $absent_reason_arr = [];
    $absent_type_arr = [];
    $absent_category_arr = [];

    foreach ($absences_reasons_data as $row) {
        if (!in_array($row['reason'], $absent_reason_arr)) {
            $absent_reason_arr[] = $row['reason'];
        }
        if (!in_array($row['absent_type'], $absent_type_arr)) {
            $absent_type_arr[] = $row['absent_type'];
        }
        if (!in_array($row['absent_category'], $absent_category_arr)) {
            $absent_category_arr[] = $row['absent_category'];
        }
    }
    
    return [
        'absences_reasons_data' => $absences_reasons_data,
        'absent_reason_arr' => $absent_reason_arr,
        'absent_type_arr' => $absent_type_arr,
        'absent_category_arr' => $absent_category_arr
    ];
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

    $shift_group_arr = array('DS', 'NS');

    $absences_matrix = process_absences_data($conn);
    
    $absent_reason_arr = $absences_matrix['absent_reason_arr'];
    $absent_type_arr = $absences_matrix['absent_type_arr'];
    $absent_category_arr = $absences_matrix['absent_category_arr'];

    $absences_reasons_data = $absences_matrix['absences_reasons_data'];

    $hasError = 0;
    $hasBlankError = 0;
    $isDuplicateOnCsv = 0;
    $hasBlankErrorArr = array();
    $isDuplicateOnCsvArr = array();
    $dup_temp_arr = array();

    $row_valid_arr = array(0, 0, 0, 0, 0);

    $notExistsShiftGroupArr = array();
    $notExistsAbsentCategoryArr = array();
    $notExistsAbsentReasonArr = array();
    $notExistsAbsentTypeArr = array();
    $unmatchedAbsencesReasonsDataArr = array();

    $message = "";
    $check_csv_row = 0;

    // CHECK CSV BASED ON HEADER
    $first_line = preg_replace('/[\t\n\r]+/', '', $first_line);
    $valid_first_line = "Employee No.,Day,Shift Group,Absent Category,Absent Type,Reason";
    $valid_first_line2 = '"Employee No.",Day,"Shift Group","Absent Category","Absent Type",Reason';
    if ($first_line == $valid_first_line || $first_line == $valid_first_line2) {
        while (($line = fgetcsv($csvFile)) !== false) {
            // Check if the row is blank or consists only of whitespace
            if (empty(implode('', $line))) {
                continue; // Skip blank lines
            }

            $check_csv_row++;

            $emp_no = custom_trim($line[0]);
            $day = custom_trim($line[1]);
            $shift_group = custom_trim($line[2]);
            $absent_category = custom_trim($line[3]);
            $absent_reason = custom_trim($line[4]);
            $absent_type = custom_trim($line[5]);

            /*if ($emp_no == '' || $full_name == '' || $dept == '' || $position == '' || $provider == '' || $date_hired == '') {
                // IF BLANK DETECTED ERROR += 1
                $hasBlankError++;
                $hasError = 1;
                array_push($hasBlankErrorArr, $check_csv_row);
            }*/

            if ($emp_no == '' || $day == '' || $shift_group == '' || $absent_category == '' || $absent_reason == '' || $absent_type == '') {
                // IF BLANK DETECTED ERROR += 1
                $hasBlankError++;
                $hasError = 1;
                array_push($hasBlankErrorArr, $check_csv_row);
            }

            // CHECK ROW VALIDATION
            if (!empty($shift_group)) {
                if (!in_array($shift_group, $shift_group_arr)) {
                    $hasError = 1;
                    $row_valid_arr[0] = 1;
                    array_push($notExistsShiftGroupArr, $check_csv_row);
                }
            }
            if (!empty($absent_category)) {
                if (!in_array($absent_category, $absent_category_arr)) {
                    $hasError = 1;
                    $row_valid_arr[1] = 1;
                    array_push($notExistsAbsentCategoryArr, $check_csv_row);
                }
            }
            if (!empty($absent_reason)) {
                if (!in_array($absent_reason, $absent_reason_arr)) {
                    $hasError = 1;
                    $row_valid_arr[2] = 1;
                    array_push($notExistsAbsentReasonArr, $check_csv_row);
                }
            }
            if (!empty($absent_type)) {
                if (!in_array($absent_type, $absent_type_arr)) {
                    $hasError = 1;
                    $row_valid_arr[3] = 1;
                    array_push($notExistsAbsentTypeArr, $check_csv_row);
                }
            }

            // Adjust keys based on your CSV structure
            // Create a csvRow only with the required columns
            $csvRow = [
                'reason' => $absent_reason,
                'absent_type' => $absent_type,
                'absent_category' => $absent_category,
                // Include other necessary fields as needed
            ];

            $matched = false;

            foreach ($absences_reasons_data as $dbRow) {
                if ($csvRow['reason'] == $dbRow['reason'] && 
                    $csvRow['absent_type'] == $dbRow['absent_type'] && 
                    $csvRow['absent_category'] == $dbRow['absent_category']) { // Change to match your criteria
                    $matched = true;
                    break;
                }
            }

            if (!$matched) {
                $hasError = 1;
                $row_valid_arr[4] = 1;
                array_push($unmatchedAbsencesReasonsDataArr, $check_csv_row);
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
    } else {
        //$message = $first_line;
        $message = $message . 'Invalid CSV Table Header. Maybe an incorrect CSV file or incorrect CSV header ';
    }

    fclose($csvFile);

    if ($hasError == 1) {
        if ($row_valid_arr[0] == 1) {
            $message = $message . 'Shift Group doesn\'t exists on row/s ' . implode(", ", $notExistsShiftGroupArr) . '. ';
        }
        if ($row_valid_arr[1] == 1) {
            $message = $message . 'Absent Category doesn\'t exists on row/s ' . implode(", ", $notExistsAbsentCategoryArr) . '. ';
        }
        if ($row_valid_arr[2] == 1) {
            $message = $message . 'Absent Reason doesn\'t exists on row/s ' . implode(", ", $notExistsAbsentReasonArr) . '. ';
        }
        if ($row_valid_arr[3] == 1) {
            $message = $message . 'Absent Type doesn\'t exists on row/s ' . implode(", ", $notExistsAbsentTypeArr) . '. ';
        }
        if ($row_valid_arr[4] == 1) {
            $message = $message . 'Unmatched Absences Reasons Data on row/s ' . implode(", ", $unmatchedAbsencesReasonsDataArr) . '. ';
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

$csvMimes = [
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
            ];

if (empty($_FILES['file']['name'])) {
    exit("Please upload csv file");
}

if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    exit("File upload failed. Error code: " . $_FILES['file']['error']);
}

if (is_uploaded_file($_FILES['file']['tmp_name'])) {
    exit("CSV FILE NOT UPLOADED!");
}

if (!in_array($_FILES['file']['type'], $csvMimes)) {
    exit("INVALID FILE FORMAT!");
}

require '../conn.php';

$chkCsvMsg = check_csv($_FILES['file']['tmp_name'], $conn);

if ($chkCsvMsg != '') {
    $conn = null;
    exit($chkCsvMsg);
}

//READ FILE
$csvFile = fopen($_FILES['file']['tmp_name'], 'r');

// SKIP FIRST LINE (HEADER)
fgets($csvFile);

// SKIP SECOND LINE (EXAMPLE ROW)
fgets($csvFile);

// PARSE
$error = 0;

$submitted_by_no = '';

if (isset($_SESSION['emp_no_hr'])) {
    $submitted_by_no = $_SESSION['emp_no_hr'];
} else if (isset($_SESSION['emp_no_control_area'])) {
    $submitted_by_no = $_SESSION['emp_no_control_area'];
}

$isTransactionActive = false;

try {
    if (!$isTransactionActive) {
        $conn->beginTransaction();
        $isTransactionActive = true;
    }

    while (($line = fgetcsv($csvFile)) !== false) {
        // Check if the row is blank or consists only of whitespace
        if (empty(implode('', $line))) {
            continue; // Skip blank lines
        }

        $emp_no = custom_trim($line[0]);
        $day = custom_trim($line[1]);
        $shift_group = custom_trim($line[2]);
        $absent_category = custom_trim($line[3]);
        $absent_reason = custom_trim($line[4]);
        $absent_type = custom_trim($line[5]);

        if (!empty($day)) {
            $result = parseDate($day);

            // Check if the result is a DateTime object or an error message
            if ($result instanceof DateTime) {
                $day = $result->format('Y-m-d'); // Outputs: 2025-05-28
            } else {
                echo "Parse Date Error on Emp No. (".$emp_no.")" . $result; // Outputs the error message
                exit();
            }
        }

        // MERGE UPSERT DATA
        $sql = "MERGE INTO t_absences AS target 
                USING (SELECT ? AS emp_no, ? AS day, ? AS shift_group, ? AS absent_category, ? AS absent_type, ? AS reason, ? AS submitted_by_no, ? AS date_updated) AS source 
                ON target.emp_no = source.emp_no AND target.day = source.day AND target.shift_group = source.shift_group 
                WHEN MATCHED THEN 
                    UPDATE SET 
                        absent_category = source.absent_category, absent_type = source.absent_type, reason = source.reason, 
                        updated_by_no = source.submitted_by_no, date_updated = source.date_updated 
                WHEN NOT MATCHED THEN 
                    INSERT (emp_no, day, shift_group, absent_category, absent_type, reason, submitted_by_no) 
                    VALUES (source.emp_no, source.day, source.shift_group, source.absent_category, source.absent_type, source.reason, source.submitted_by_no);";
        $stmt = $conn->prepare($sql);
        $params = array($emp_no, $day, $shift_group, $absent_category, $absent_type, $reason, $submitted_by_no, $server_date_time);
        $stmt->execute($params);
    }

    $conn->commit();
    $isTransactionActive = false;
} catch (Exception $e) {
    if ($isTransactionActive) {
        $conn->rollBack();
        $isTransactionActive = false;
    }

    $conn = null;
    exit("Failed. Please Try Again or Call IT Personnel Immediately!: " . $e->getMessage());
}

fclose($csvFile);

// KILL CONNECTION
$conn = null;
