<?php
// error_reporting(0);
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

require '../conn.php';
require '../lib/validate.php';

if (!isset($_SESSION['emp_no_hr']) || !isset($_SESSION['emp_no_tc'])) {
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
    }
} else {
    switch (true) {
        case !isset($_SESSION['emp_no_hr']):
            header('location:/emp_mgt/hr');
            exit();
        case !isset($_SESSION['emp_no_tc']):
            header('location:/emp_mgt/tc');
            exit();
    }
}

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

function get_falp_groups($conn)
{
    $data = array();

    $sql = "SELECT falp_group FROM m_falp_groups ORDER BY falp_group ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($data, $row['falp_group']);
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

function get_sub_sections($conn)
{
    $data = array();

    $sql = "SELECT sub_section FROM m_sub_sections ORDER BY sub_section ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($data, $row['sub_section']);
    }

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

function get_process($conn)
{
    $data = array();

    $sql = "SELECT process FROM m_process ORDER BY process ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($data, $row['process']);
    }

    return $data;
}

function get_positions($conn)
{
    $data = array();

    $sql = "SELECT position FROM m_positions ORDER BY position ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($data, $row['position']);
    }

    return $data;
}

function get_providers($conn)
{
    $data = array();

    $sql = "SELECT provider FROM m_providers ORDER BY provider ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($data, $row['provider']);
    }

    return $data;
}

function get_shuttle_routes($conn)
{
    $data = array();

    $sql = "SELECT shuttle_route FROM m_shuttle_routes ORDER BY shuttle_route ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($data, $row['shuttle_route']);
    }

    return $data;
}

function get_employee_name_js_s($conn)
{
    $data = array();

    $sql = "SELECT emp_no, full_name 
            FROM m_employees 
            WHERE position IN ('Jr. Staff', 'Staff') 
            AND resigned = 0 ORDER BY full_name ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($data, $row['full_name']);
        array_push($data, $row['emp_no']);
    }

    return $data;
}

function get_employee_name_sv($conn)
{
    $data = array();

    $sql = "SELECT emp_no, full_name FROM m_employees 
            WHERE position = 'Supervisor' 
            AND resigned = 0 ORDER BY full_name ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($data, $row['full_name']);
        array_push($data, $row['emp_no']);
    }

    return $data;
}

function get_employee_name_approver($conn)
{
    $data = array();

    $sql = "SELECT emp_no, full_name FROM m_employees 
            WHERE position IN ('Assistant Manager', 'Section Manager', 'Manager') 
            AND resigned = 0 ORDER BY full_name ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($data, $row['full_name']);
        array_push($data, $row['emp_no']);
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
    $sub_section_arr = get_sub_sections($conn);
    $line_arr = get_lines($conn);
    $process_arr = get_process($conn);
    $position_arr = get_positions($conn);
    $provider_arr = get_providers($conn);
    $shift_group_arr = array('A', 'B', 'ADS');
    $shuttle_route_arr = get_shuttle_routes($conn);
    $gender_arr = array('M', 'F');
    $emp_status_arr = array('Probationary', 'Regular');
    $emp_js_s_no_arr = get_employee_name_js_s($conn);
    $emp_sv_no_arr = get_employee_name_sv($conn);
    $emp_approver_no_arr = get_employee_name_approver($conn);

    $hasError = 0;
    $hasBlankError = 0;
    $isDuplicateOnCsv = 0;
    $hasBlankErrorArr = array();
    $isDuplicateOnCsvArr = array();
    $dup_temp_arr = array();

    $row_valid_arr = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

    $notExistsDeptArr = array();
    $notExistsSectionArr = array();
    $notExistsSubSectionArr = array();
    $notExistsLineNoArr = array();
    $notExistsProcessArr = array();
    $notExistsPositionArr = array();
    $notExistsProviderArr = array();
    $notExistsShiftGroupArr = array();
    $notExistsShuttleRouteArr = array();
    $notExistsGenderArr = array();
    $notExistsEmpStatusArr = array();
    $notValidDateHiredArr = array();
    $notValidResignedDateArr = array();
    $notExistsEmpJsSNoArr = array();
    $notExistsEmpSvNoArr = array();
    $notExistsEmpAppNoArr = array();

    $message = "";
    $check_csv_row = 0;

    // CHECK CSV BASED ON HEADER
    $first_line = preg_replace('/[\t\n\r]+/', '', $first_line);
    $valid_first_line = "Employee No.,Full Name,Department,Section,Sub Section,Line No.,Process,Position,Provider,Shift Group,Gender,Date Hired,Address,Contact No.,Employment Status,Shuttle Route,Jr. Staff or Staff,Supervisor,Approver,Date Resigned";
    $valid_first_line2 = '"Employee No.","Full Name",Department,Section,"Sub Section","Line No.",Process,Position,Provider,"Shift Group",Gender,"Date Hired",Address,"Contact No.","Employment Status","Shuttle Route","Jr. Staff or Staff",Supervisor,Approver,"Date Resigned"';
    if ($first_line == $valid_first_line || $first_line == $valid_first_line2) {
        while (($line = fgetcsv($csvFile)) !== false) {
            // Check if the row is blank or consists only of whitespace
            if (empty(implode('', $line))) {
                continue; // Skip blank lines
            }

            $check_csv_row++;

            $emp_no = custom_trim($line[0]);
            $full_name = custom_trim($line[1]);
            $dept = custom_trim($line[2]);
            $section = custom_trim($line[3]);
            $sub_section = custom_trim($line[4]);
            $line_no = custom_trim($line[5]);
            $process = custom_trim($line[6]);
            $position = custom_trim($line[7]);
            $provider = custom_trim($line[8]);
            $shift_group = custom_trim($line[9]);
            $gender = custom_trim($line[10]);
            $date_hired = custom_trim($line[11]);
            $address = custom_trim($line[12]);
            $contact_no = custom_trim($line[13]);
            $emp_status = custom_trim($line[14]);
            $shuttle_route = custom_trim($line[15]);
            $emp_js_s_no = custom_trim($line[16]);
            $emp_sv_no = custom_trim($line[17]);
            $emp_approver_no = custom_trim($line[18]);
            $resigned_date = custom_trim($line[19]);

            $date_hired_valid = str_replace('/', '-', $date_hired);
            $is_valid_date_hired = validate_date($date_hired_valid);

            $resigned_date_valid = str_replace('/', '-', $resigned_date);
            $is_valid_resigned_date = validate_date($resigned_date_valid);

            /*if ($emp_no == '' || $full_name == '' || $dept == '' || $position == '' || $provider == '' || $date_hired == '') {
                // IF BLANK DETECTED ERROR += 1
                $hasBlankError++;
                $hasError = 1;
                array_push($hasBlankErrorArr, $check_csv_row);
            }*/

            if ($emp_no == '' || $full_name == '' || $dept == '' || $provider == '') {
                // IF BLANK DETECTED ERROR += 1
                $hasBlankError++;
                $hasError = 1;
                array_push($hasBlankErrorArr, $check_csv_row);
            }

            // CHECK ROW VALIDATION
            if (!empty($dept)) {
                if (!in_array($dept, $dept_arr)) {
                    $hasError = 1;
                    $row_valid_arr[0] = 1;
                    array_push($notExistsDeptArr, $check_csv_row);
                }
            }
            if (!empty($section)) {
                if (!in_array($section, $section_arr)) {
                    $hasError = 1;
                    $row_valid_arr[1] = 1;
                    array_push($notExistsSectionArr, $check_csv_row);
                }
            }
            if (!empty($sub_section)) {
                if (!in_array($sub_section, $sub_section_arr)) {
                    $hasError = 1;
                    $row_valid_arr[2] = 1;
                    array_push($notExistsSubSectionArr, $check_csv_row);
                }
            }
            if (!empty($line_no)) {
                if (!in_array($line_no, $line_arr)) {
                    $hasError = 1;
                    $row_valid_arr[3] = 1;
                    array_push($notExistsLineNoArr, $check_csv_row);
                }
            }
            if (!empty($process)) {
                if (!in_array($process, $process_arr)) {
                    $hasError = 1;
                    $row_valid_arr[4] = 1;
                    array_push($notExistsProcessArr, $check_csv_row);
                }
            }
            /*if (!empty($position)) {
                if (!in_array($position, $position_arr)) {
                    $hasError = 1;
                    $row_valid_arr[5] = 1;
                    array_push($notExistsPositionArr, $check_csv_row);
                }
            }*/
            if (!empty($provider)) {
                if (!in_array($provider, $provider_arr)) {
                    $hasError = 1;
                    $row_valid_arr[6] = 1;
                    array_push($notExistsProviderArr, $check_csv_row);
                }
            }
            if (!empty($shift_group)) {
                if (!in_array($shift_group, $shift_group_arr)) {
                    $hasError = 1;
                    $row_valid_arr[7] = 1;
                    array_push($notExistsShiftGroupArr, $check_csv_row);
                }
            }
            /*if (!in_array($shuttle_route, $shuttle_route_arr)) {
                $hasError = 1;
                $row_valid_arr[8] = 1;
                array_push($notExistsShuttleRouteArr, $check_csv_row);
            }
            if (!in_array($gender, $gender_arr)) {
                $hasError = 1;
                $row_valid_arr[9] = 1;
                array_push($notExistsGenderArr, $check_csv_row);
            }
            if (!in_array($emp_status, $emp_status_arr)) {
                $hasError = 1;
                $row_valid_arr[10] = 1;
                array_push($notExistsEmpStatusArr, $check_csv_row);
            }
            if (!empty($date_hired)) {
                if ($is_valid_date_hired == false) {
                    $hasError = 1;
                    $row_valid_arr[11] = 1;
                    array_push($notValidDateHiredArr, $check_csv_row);
                }
            }
            if (!empty($resigned_date)) {
                if ($is_valid_resigned_date == false) {
                    $hasError = 1;
                    $row_valid_arr[12] = 1;
                    array_push($notValidResignedDateArr, $check_csv_row);
                }
            }
            if (!empty($emp_js_s_no)) {
                if (!in_array($emp_js_s_no, $emp_js_s_no_arr)) {
                    $hasError = 1;
                    $row_valid_arr[13] = 1;
                    array_push($notExistsEmpJsSNoArr, $check_csv_row);
                }
            }
            if (!empty($emp_sv_no)) {
                if (!in_array($emp_sv_no, $emp_sv_no_arr)) {
                    $hasError = 1;
                    $row_valid_arr[14] = 1;
                    array_push($notExistsEmpSvNoArr, $check_csv_row);
                }
            }
            if (!empty($emp_approver_no)) {
                if (!in_array($emp_approver_no, $emp_approver_no_arr)) {
                    $hasError = 1;
                    $row_valid_arr[15] = 1;
                    array_push($notExistsEmpAppNoArr, $check_csv_row);
                }
            }*/

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
            $message = $message . 'Department doesn\'t exists on row/s ' . implode(", ", $notExistsDeptArr) . '. ';
        }
        if ($row_valid_arr[1] == 1) {
            $message = $message . 'Section doesn\'t exists on row/s ' . implode(", ", $notExistsSectionArr) . '. ';
        }
        if ($row_valid_arr[2] == 1) {
            $message = $message . 'Sub Section doesn\'t exists row/s ' . implode(", ", $notExistsSubSectionArr) . '. ';
        }
        if ($row_valid_arr[3] == 1) {
            $message = $message . 'Line No. doesn\'t exists row/s ' . implode(", ", $notExistsLineNoArr) . '. ';
        }
        if ($row_valid_arr[4] == 1) {
            $message = $message . 'Process doesn\'t exists row/s ' . implode(", ", $notExistsProcessArr) . '. ';
        }
        if ($row_valid_arr[5] == 1) {
            $message = $message . 'Position doesn\'t exists on row/s ' . implode(", ", $notExistsPositionArr) . '. ';
        }
        if ($row_valid_arr[6] == 1) {
            $message = $message . 'Provider doesn\'t exists on row/s ' . implode(", ", $notExistsProviderArr) . '. ';
        }
        if ($row_valid_arr[7] == 1) {
            $message = $message . 'Shift Group doesn\'t exists on row/s ' . implode(", ", $notExistsShiftGroupArr) . '. ';
        }
        if ($row_valid_arr[8] == 1) {
            $message = $message . 'Shuttle Route doesn\'t exists on row/s ' . implode(", ", $notExistsShuttleRouteArr) . '. ';
        }
        if ($row_valid_arr[9] == 1) {
            $message = $message . 'Gender doesn\'t exists on row/s ' . implode(", ", $notExistsGenderArr) . '. ';
        }
        if ($row_valid_arr[10] == 1) {
            $message = $message . 'Employment Status doesn\'t exists on row/s ' . implode(", ", $notExistsEmpStatusArr) . '. ';
        }
        if ($row_valid_arr[11] == 1) {
            $message = $message . 'Invalid Date Hired on row/s ' . implode(", ", $notValidDateHiredArr) . '. ';
        }
        if ($row_valid_arr[12] == 1) {
            $message = $message . 'Invalid Resigned Date on row/s ' . implode(", ", $notValidResignedDateArr) . '. ';
        }
        if ($row_valid_arr[13] == 1) {
            $message = $message . 'Jr. Staff or Staff Employee No. doesn\'t exists on row/s ' . implode(", ", $notExistsEmpJsSNoArr) . '. ';
        }
        if ($row_valid_arr[14] == 1) {
            $message = $message . 'Supervisor Employee No. doesn\'t exists on row/s ' . implode(", ", $notExistsEmpSvNoArr) . '. ';
        }
        if ($row_valid_arr[15] == 1) {
            $message = $message . 'Approver Employee No. doesn\'t exists on row/s ' . implode(", ", $notExistsEmpAppNoArr) . '. ';
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

            // PARSE
            $error = 0;

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
                    $full_name = custom_trim($line[1]);
                    $dept = custom_trim($line[2]);
                    $section = custom_trim($line[3]);
                    $sub_section = custom_trim($line[4]);
                    $line_no = custom_trim($line[5]);
                    $process = custom_trim($line[6]);
                    $position = custom_trim($line[7]);
                    $provider = custom_trim($line[8]);
                    $shift_group = custom_trim($line[9]);
                    $gender = strtoupper(custom_trim($line[10]));
                    $date_hired = custom_trim($line[11]);
                    $address = custom_trim($line[12]);
                    $contact_no = custom_trim($line[13]);
                    $emp_status = custom_trim($line[14]);
                    $shuttle_route = custom_trim($line[15]);
                    $emp_js_s_no = custom_trim($line[16]);
                    $emp_sv_no = custom_trim($line[17]);
                    $emp_approver_no = custom_trim($line[18]);
                    $resigned_date = custom_trim($line[19]);
                    $resigned = '';

                    if (!empty($date_hired)) {
                        $result = parseDate($date_hired);

                        // Check if the result is a DateTime object or an error message
                        if ($result instanceof DateTime) {
                            $date_hired = $result->format('Y-m-d'); // Outputs: 2025-05-28
                        } else {
                            echo "Parse Date Error on Emp No. (".$emp_no.")" . $result; // Outputs the error message
                            exit();
                        }
                    }

                    if (!empty($resigned_date)) {
                        $result = parseDate($resigned_date);

                        // Check if the result is a DateTime object or an error message
                        if ($result instanceof DateTime) {
                            $resigned_date = $result->format('Y-m-d'); // Outputs: 2025-05-28
                            $resigned = 1;
                        } else {
                            echo "Parse Date Error on Emp No. (".$emp_no.")" . $result; // Outputs the error message
                            exit();
                        }
                    } else {
                        $resigned = 0;
                    }

                    // CHECK DATA
                    $sql = "SELECT id FROM m_employees WHERE emp_no = ?";
                    $stmt = $conn->prepare($sql);
                    $params = array($emp_no);
                    $stmt->execute($params);

                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($row) {
                        $id = $row['id'];

                        $sql = "UPDATE m_employees SET emp_no = ?, full_name = ?";

                        $params1 = [
                            $emp_no,
                            $full_name
                        ];

                        if (!empty($dept)) {
                            $sql = $sql . ", dept = ?";
                            $params1[] = $dept;
                        } else {
                            $sql = $sql . ", dept = 'Undefined'";
                        }
                        if (!empty($section)) {
                            $sql = $sql . ", section = ?";
                            $params1[] = $section;
                        } else {
                            $sql = $sql . ", section = 'Undefined'";
                        }
                        if (!empty($sub_section)) {
                            $sql = $sql . ", sub_section = ?";
                            $params1[] = $sub_section;
                        } else {
                            $sql = $sql . ", sub_section = 'Undefined'";
                        }
                        if (!empty($line_no)) {
                            $sql = $sql . ", line_no = ?";
                            $params1[] = $line_no;
                        } else {
                            $sql = $sql . ", line_no = 'Undefined'";
                        }
                        if (!empty($process)) {
                            $sql = $sql . ", process = ?";
                            $params1[] = $process;
                        } else {
                            $sql = $sql . ", process = 'Undefined'";
                        }
                        if (!empty($shift_group)) {
                            $sql = $sql . ", shift_group = ?";
                            $params1[] = $shift_group;
                        } else {
                            $sql = $sql . ", shift_group = 'ADS'";
                        }

                        if (!empty($date_hired)) {
                            $sql = $sql . ", date_hired = ?";
                            $params1[] = $date_hired;
                        } else {
                            $sql = $sql . ", date_hired = NULL";
                        }

                        if (!empty($resigned_date)) {
                            $sql = $sql . ", resigned_date = ?";
                            $params1[] = $resigned_date;
                        } else {
                            $sql = $sql . ", resigned_date = NULL";
                        }

                        $sql = $sql . ",position = ?, provider = ?, gender = ?, 
                                address = ?, contact_no = ?, emp_status = ?,  
                                shuttle_route = ?, emp_js_s = ?, emp_js_s_no = ?, emp_sv = ?, emp_sv_no = ?, 
                                emp_approver = ?,emp_approver_no = ?,
                                resigned = ? WHERE id = ?";
                        $params2 = [
                            $position,
                            $provider,
                            $gender,
                            $address,
                            $contact_no,
                            $emp_status,
                            $shuttle_route,
                            '',
                            $emp_js_s_no,
                            '',
                            $emp_sv_no,
                            '',
                            $emp_approver_no,
                            $resigned,
                            $id
                        ];

                        $params = array_merge($params1, $params2);

                        $stmt = $conn->prepare($sql);

                        if ($stmt->execute($params)) {
                            $query = "UPDATE m_control_area_accounts SET";
                            $params = [];

                            if (!empty($dept)) {
                                $query = $query . " dept = ?";
                                $params[] = $dept;
                            } else {
                                $query = $query . " dept = ''";
                            }
                            if (!empty($section)) {
                                $query = $query . ", section = ?";
                                $params[] = $section;
                            } else {
                                $query = $query . ", section = NULL";
                            }
                            if (!empty($line_no)) {
                                $query = $query . ", line_no = ?";
                                $params[] = $line_no;
                            } else {
                                $query = $query . ", line_no = NULL";
                            }
                            if (!empty($shift_group)) {
                                $query = $query . ", shift_group = ?";
                                $params[] = $shift_group;
                            } else {
                                $query = $query . ", shift_group = 'ADS'";
                            }

                            $query = $query . " WHERE emp_no = ?";
                            $params[] = $emp_no;

                            $stmt = $conn->prepare($query);

                            if ($stmt->execute($params)) {
                                $query = "UPDATE m_accounts SET";
                                $params = [];

                                if (!empty($dept)) {
                                    $query = $query . " dept = ?";
                                    $params[] = $dept;
                                } else {
                                    $query = $query . " dept = ''";
                                }
                                if (!empty($section)) {
                                    $query = $query . ", section = ?";
                                    $params[] = $section;
                                } else {
                                    $query = $query . ", section = NULL";
                                }
                                if (!empty($line_no)) {
                                    $query = $query . ", line_no = ?";
                                    $params[] = $line_no;
                                } else {
                                    $query = $query . ", line_no = NULL";
                                }
                                if (!empty($shift_group)) {
                                    $query = $query . ", shift_group = ?";
                                    $params[] = $shift_group;
                                } else {
                                    $query = $query . ", shift_group = 'ADS'";
                                }

                                $query = $query . " WHERE emp_no = ?";
                                $params[] = $emp_no;

                                $stmt = $conn->prepare($query);

                                $stmt->execute($params);
                            }
                        }
                    } else {
                        $sql = "INSERT INTO m_employees
                                (emp_no, full_name, dept, section, sub_section, line_no, process, shift_group, 
                                date_hired, resigned_date, 
                                position, provider, gender, address, contact_no, emp_status, shuttle_route, 
                                emp_js_s, emp_js_s_no, emp_sv, emp_sv_no, emp_approver, emp_approver_no, resigned) 
                                VALUES (?, ?";
                        $params1 = [
                            $emp_no,
                            $full_name
                        ];

                        if (!empty($dept)) {
                            $sql = $sql . ", ?";
                            $params1[] = $dept;
                        } else {
                            $sql = $sql . ", 'Undefined'";
                        }
                        
                        if (!empty($section)) {
                            $sql = $sql . ", ?";
                            $params1[] = $section;
                        } else {
                            $sql = $sql . ", 'Undefined'";
                        }

                        if (!empty($sub_section)) {
                            $sql = $sql . ", ?";
                            $params1[] = $sub_section;
                        } else {
                            $sql = $sql . ", 'Undefined'";
                        }

                        if (!empty($line_no)) {
                            $sql = $sql . ", ?";
                            $params1[] = $line_no;
                        } else {
                            $sql = $sql . ", 'Undefined'";
                        }

                        if (!empty($process)) {
                            $sql = $sql . ", ?";
                            $params1[] = $process;
                        } else {
                            $sql = $sql . ", 'Undefined'";
                        }

                        if (!empty($shift_group)) {
                            $sql = $sql . ", ?";
                            $params1[] = $shift_group;
                        } else {
                            $sql = $sql . ", 'ADS'";
                        }


                        if (!empty($date_hired)) {
                            $sql = $sql . ", ?";
                            $params1[] = $date_hired;
                        } else {
                            $sql = $sql . ", NULL";
                        }

                        if (!empty($resigned_date)) {
                            $sql = $sql . ", ?";
                            $params1[] = $resigned_date;
                        } else {
                            $sql = $sql . ", NULL";
                        }

                        $sql = $sql . ", ?, ?, ?, ?, ?, ?, ?, 
                                        ?, ?, ?, ?, ?, ?, ?)";
                        $params2 = [
                            $position,
                            $provider,
                            $gender,
                            $address,
                            $contact_no,
                            $emp_status,
                            $shuttle_route,
                            '',
                            $emp_js_s_no,
                            '',
                            $emp_sv_no,
                            '',
                            $emp_approver_no,
                            $resigned
                        ];

                        $params = array_merge($params1, $params2);

                        $stmt = $conn->prepare($sql);

                        $stmt->execute($params);
                    }
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
