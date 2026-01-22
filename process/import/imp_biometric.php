<?php
// error_reporting(0);
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

require '../conn.php';
require '../lib/validate.php';

if (!isset($_SESSION['emp_no_hr'])) {
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
} else if (!isset($_SESSION['emp_no_hr'])) {
    header('location:/emp_mgt/hr');
    exit();
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

    $shift_arr = array('DS', 'NS');
    $shift_group_arr = array('A', 'B', 'ADS');

    $hasError = 0;
    $hasBlankError = 0;
    $isDuplicateOnCsv = 0;
    $hasBlankErrorArr = array();
    $isDuplicateOnCsvArr = array();
    $dup_temp_arr = array();

    $row_valid_arr = array(0, 0);

    $notExistsShiftArr = array();
    $notValidDayArr = array();

    $message = "";
    $check_csv_row = 0;

    // CHECK CSV BASED ON HEADER
    $first_line = preg_replace('/[\t\n\r]+/', '', $first_line);
    $valid_first_line = "Employee No.,Day,Shift,Time In,Time Out";
    $valid_first_line2 = '"Employee No.",Day,Shift,"Time In","Time Out"';
    if ($first_line == $valid_first_line || $first_line == $valid_first_line2) {
        while (($line = fgetcsv($csvFile)) !== false) {
            // Check if the row is blank or consists only of whitespace
            if (empty(implode('', $line))) {
                continue; // Skip blank lines
            }

            $check_csv_row++;

            $emp_no = custom_trim($line[0]);
            $day = custom_trim($line[1]);
            $shift = custom_trim($line[2]);
            $time_in = custom_trim($line[3]);
            $time_out = custom_trim($line[4]);

            $day_valid = str_replace('/', '-', $day);
            $is_valid_day = validate_date($day_valid);

            if ($emp_no == '' || $day == '' || $shift == '') {
                // IF BLANK DETECTED ERROR += 1
                $hasBlankError++;
                $hasError = 1;
                array_push($hasBlankErrorArr, $check_csv_row);
            }

            // CHECK ROW VALIDATION
            if (!empty($day)) {
                if ($is_valid_day == false) {
                    $hasError = 1;
                    $row_valid_arr[0] = 1;
                    array_push($notValidDayArr, $check_csv_row);
                }
            }

            if (!empty($shift)) {
                if (!in_array($shift, $shift_arr)) {
                    $hasError = 1;
                    $row_valid_arr[1] = 1;
                    array_push($notExistsShiftArr, $check_csv_row);
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
    } else {
        //$message = $first_line;
        $message = $message . 'Invalid CSV Table Header. Maybe an incorrect CSV file or incorrect CSV header ';
    }

    fclose($csvFile);

    if ($hasError == 1) {
        if ($row_valid_arr[0] == 1) {
            $message = $message . 'Invalid Day on row/s ' . implode(", ", $notValidDayArr) . '. ';
        }
        if ($row_valid_arr[1] == 1) {
            $message = $message . 'Shift doesn\'t exists on row/s ' . implode(", ", $notExistsShiftArr) . '. ';
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

$csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');

if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)) {

    if (is_uploaded_file($_FILES['file']['tmp_name'])) {

        $chkCsvMsg = check_csv($_FILES['file']['tmp_name'], $conn);

        if ($chkCsvMsg == '') {
            //READ FILE
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');

            // SKIP FIRST LINE (HEADER)
            fgets($csvFile);

            // SKIP SECOND LINE (EXAMPLE ROW)
            fgets($csvFile);

            $isTransactionActive = false;
            $chunkSize = 250; // Set your desired chunk size

            try {
                if (!$isTransactionActive) {
                    $conn->beginTransaction();
                    $isTransactionActive = true;
                }

                $sql_insert = "INSERT INTO t_biometric_time_in_out (emp_no, day, shift, time_in, time_out) VALUES ";
                $values = [];
                $placeholders = [];

                while (($line = fgetcsv($csvFile)) !== false) {
                    // Check if the row is blank or consists only of whitespace
                    if (empty(implode('', $line))) {
                        continue; // Skip blank lines
                    }

                    $emp_no = custom_trim($line[0]);
                    $day = custom_trim($line[1]);
                    $shift = custom_trim($line[2]);
                    $time_in = custom_trim($line[3]);
                    $time_out = custom_trim($line[4]);

                    if (!empty($day)) {
                        $result = parseDate($day);

                        // Check if the result is a DateTime object or an error message
                        if ($result instanceof DateTime) {
                            $day = $result->format('Y-m-d'); // Outputs: 2025-05-28
                        } else {
                            echo "Parse Date Error on Emp No. (".$emp_no.")" . $result; // Outputs the error message
                            $conn = null;
                            exit();
                        }
                    }

                    // Create a temporary array for the current row
                    $currentValues = [
                        $emp_no,
                        $day,
                        $shift,
                        $time_in,
                        $time_out
                    ];

                    // Create placeholders for each row
                    $generated_placeholders = implode(',', array_fill(0, count($currentValues), '?'));
                    $placeholders[] = "($generated_placeholders)";

                    // Add current values to the main values array
                    $values = array_merge($values, $currentValues);

                    // Check if we reached the chunk size
                    if (count($placeholders) === $chunkSize) {
                        // Combine the SQL statement with the placeholders
                        $sql_insert .= implode(', ', $placeholders);
                        
                        // Prepare the statement
                        $stmt = $conn->prepare($sql_insert);
                        
                        // Execute the statement with the values
                        $stmt->execute($values);

                        // Reset for the next chunk
                        $placeholders = [];
                        $values = [];
                        $sql_insert = "INSERT INTO t_biometric_time_in_out (emp_no, day, shift, time_in, time_out) VALUES ";
                    }
                }

                // Insert any remaining rows that didn't fill a complete chunk
                if (!empty($placeholders)) {
                    $sql_insert .= implode(', ', $placeholders);
                    $stmt = $conn->prepare($sql_insert);
                    $stmt->execute($values);
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
        } else {
            echo $chkCsvMsg;
        }
    } else {
        echo 'CSV FILE NOT UPLOADED!';
    }
} else {
    echo 'INVALID FILE FORMAT!';
}

// KILL CONNECTION
$conn = null;
