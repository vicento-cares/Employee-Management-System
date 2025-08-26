<?php
// error_reporting(0);
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

function check_shsa_submission_day($server_date_only, $server_time)
{
    $day_name = date("D"); // Uploading Restriction (Vince)

    if ($day_name == 'Fri') {
		if ($server_time > '00:00:00' && $server_time < '23:59:59') {
			return true;
		} else {
            return false;
        }
	} else if ($day_name == 'Sat') {
		if ($server_time > '00:00:00' && $server_time < '20:59:59') {
			return true;
		} else {
            return false;
        }
	} else {
		// Custom
        $custom_start_date_only = '2025-01-28';
        $custom_end_date_only = '2025-01-29';
        if ($server_date_only >= $custom_start_date_only && $server_date_only <= $custom_end_date_only) {
            if ($server_date_only == $custom_end_date_only) {
                if ($server_time >= '00:00:00' && $server_time <= '01:59:59') {
                    return true;
                } else {
                    return false;
                }
            } else if ($server_time >= '00:00:00' && $server_time <= '23:59:59') {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
	}
}

// Check GA Account Session
if (!isset($_SESSION['emp_no_ga'])) {
    // Check Submission Time Range
    if (!check_shsa_submission_day($server_date_only, $server_time)) {
        echo 'Set Sunday & Holiday Shuttle Allocation Day Range must follow! (Friday and Saturday)';
        $conn = null;
        exit();
    }
}

if (!isset($_POST['upload'])) {
    echo 'Please upload file!';
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
    echo 'Please upload file and check its file format!';
    exit();
}

if (!is_uploaded_file($_FILES['file']['tmp_name'])) {
    echo 'Sorry, there was an error uploading your file.';
    exit();
}

//READ FILE
$csvFile = fopen($_FILES['file']['tmp_name'], 'r');
// SKIP FIRST LINE
$first_line = fgets($csvFile);
// Remove UTF-8 BOM from First Line
$first_line = removeBomUtf8($first_line);
// PARSE
$error = 0;

// CHECK CSV BASED ON HEADER
$first_line = preg_replace('/[\t\n\r]+/', '', $first_line);
$valid_first_line = "Shuttle Route,Total";
$valid_first_line2 = '"Shuttle Route",Total';
if ($first_line != $valid_first_line || $first_line != $valid_first_line2) {
    echo 'Invalid CSV Table Header. Maybe an incorrect CSV file or incorrect CSV header';
    exit();
}

$day = $_POST['sunday_holiday_date'];
$shift_weekly = $_POST['sunday_holiday_shift'];
$sunday_holiday_sched_type = $_POST['sunday_holiday_sched_type'];
$set_by = $_POST['sunday_holiday_set_by'];

if (!isset($_SESSION['dept'])) {
    echo 'Session Timeout! Please re-login your account';
    exit();
}

$dept = $_SESSION['dept'];
$section_weekly = $_SESSION['section'];

require '../conn.php';
require '../lib/validate.php';

while (($line = fgetcsv($csvFile)) !== false) {
    $shuttle_route = $line[0];
    $total_count = $line[1];
    // $qrcode = $line[3];

    // CHECK IF BLANK DATA
    if ($line[0] == '' || $line[1] == '') {
        // IF BLANK DETECTED ERROR += 1
        $error = $error + 1;
    } else {
        // CHECK DATA
        $query = "SELECT 
                        id 
                    FROM 
                        t_shuttle_allocation_sh 
                    WHERE 
                        dept = ? AND 
                        section = ? AND 
                        shuttle_route = ? AND 
                        shift = ? AND 
                        set_by = ? AND 
                        day = ? AND 
                        sched_type = ?";

        $stmt = $conn->prepare($query);

        $stmt->execute([
            $dept, $section_weekly, $line[0], $shift_weekly, $set_by, $day, $sunday_holiday_sched_type 
        ]);
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $id = $row['id'];
            
            $update = "UPDATE 
                            t_shuttle_allocation_sh 
                        SET 
                            shuttle_route = ?, 
                            total_count = ?, 
                            day = ?, 
                            shift = ?, 
                            set_by = ?, 
                            dept = ?, 
                            section = ?, 
                            date_updated = ?, 
                            sched_type = ? 
                        WHERE 
                            id = ?";

            $stmt = $conn->prepare($update);

            $params = [
                $shuttle_route, 
                $total_count, 
                $day, 
                $shift_weekly, 
                $set_by, 
                $dept, 
                $section_weekly, 
                $server_date_time, 
                $sunday_holiday_sched_type, 
                $id 
            ];
            
            if ($stmt->execute($params)) {
                $error = 0;
            } else {
                $error = $error + 1;
            }
        } else {
            $insert = "INSERT INTO 
                            t_shuttle_allocation_sh 
                            (dept, section, shuttle_route, total_count, day, shift, set_by, section, sched_type) 
                        VALUES 
                            (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($insert);

            $params = [
                $dept, 
                $section_weekly, 
                $shuttle_route, 
                $total_count, 
                $day, 
                $shift_weekly, 
                $set_by, 
                $section_weekly, 
                $sunday_holiday_sched_type
            ];
            
            if ($stmt->execute($params)) {
                $error = 0;
            } else {
                $error = $error + 1;
            }
        }
    }
}

fclose($csvFile);

if ($error > 0) {
    echo 'WITH # OF ERRORS ' . $error;
}

// KILL CONNECTION
$conn = null;