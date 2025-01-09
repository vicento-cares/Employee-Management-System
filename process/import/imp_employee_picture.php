<?php
// error_reporting(0);
session_set_cookie_params(0, "/emp_mgt_test");
session_name("emp_mgt_test");
session_start();

include '../conn.php';

switch (true) {
  case !isset($_SESSION['emp_no_hr']):
    header('location:/emp_mgt/hr');
    exit();
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

// Check Employee Picture File
function check_employee_picture_file($employee_picture_file_info)
{
    $message = "";
    $hasError = 0;
    $file_valid_arr = array(0, 0, 0, 0);

    // $mimes = array(
    //     'application/vnd.ms-excel', 
    //     'application/excel', 
    //     'application/msexcel', 
    //     'application/vnd.msexcel', 
    //     'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 
    //     'application/vnd.ms-word', 
    //     'application/word', 
    //     'application/vnd.msword', 
    //     'application/msword', 
    //     'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 
    //     'application/vnd.oasis.opendocument.spreadsheet', 
    //     'application/vnd.oasis.opendocument.text'
    // );

    // $mimes = array(
    //     'text/x-comma-separated-values', 
    //     'text/comma-separated-values', 
    //     'application/octet-stream', 
    //     'application/vnd.ms-excel', 
    //     'application/x-csv', 
    //     'text/x-csv', 
    //     'text/csv', 
    //     'application/csv', 
    //     'application/excel', 
    //     'application/vnd.msexcel', 
    //     'text/plain'
    // );

    // $mimes = array(
    //     'application/pdf',
    //     'application/x-pdf',
    //     'application/x-bzpdf',
    //     'application/x-gzpdf',
    //     'applications/vnd.pdf',
    //     'application/acrobat',
    //     'application/x-google-chrome-pdf',
    //     'text/pdf',
    //     'text/x-pdf'
    // );

    $mimes = array(
        'image/png',
        'image/x-png',
        'application/png',
        'application/x-png',
        'application/octet-stream' // Sometimes used for PNG files
    );

    // Check File Mimes
    if (!in_array($employee_picture_file_info['employee_picture_filetype'], $mimes)) {
        $hasError = 1;
        $file_valid_arr[0] = 1;
    }
    // Check File Size
    if ($employee_picture_file_info['employee_picture_size'] > 25000000) {
        $hasError = 1;
        $file_valid_arr[1] = 1;
    }
    
    // Error Collection and Output
    if ($hasError == 1) {
        if ($file_valid_arr[0] == 1) {
            $message = $message . 'Employee Picture file format not accepted! ';
        }
        if ($file_valid_arr[1] == 1) {
            $message = $message . 'Employee Picture file is too large. ';
        }
    }

    return $message;
}

// Insert File Information
function save_employee_picture_info($employee_picture_file_info, $conn)
{
    $emp_no = $employee_picture_file_info['emp_no'];
    $employee_picture_filename = basename($employee_picture_file_info['employee_picture_filename']);
    $employee_picture_filetype = $employee_picture_file_info['employee_picture_filetype'];
    $employee_picture_url = $employee_picture_file_info['employee_picture_url'];

    $sql = "INSERT INTO m_employee_pictures 
            (emp_no, file_url) 
            VALUES (?, ?)";

    $stmt = $conn->prepare($sql);
    $params = array($emp_no, $employee_picture_url);
    $stmt->execute($params);
}

if (isset($_POST['emp_no']) && !empty($_POST['emp_no'])) {
    $emp_no = $_POST['emp_no'];
    $employee_picture_filename = $emp_no . ".png";

    // Upload File
    if (!empty($_FILES['file']['name'])) {
        $employee_picture_file = $_FILES['file']['tmp_name'];
        // $employee_picture_filename = $_FILES['file']['name'];
        $employee_picture_filetype = $_FILES['file']['type'];
        $employee_picture_size = $_FILES['file']['size'];

        $employee_picture_url = "/uploads/emp_mgt/employee_picture/";
        $target_dir = "D:\\uploads\\emp_mgt\\employee_picture\\";

        $target_file = $target_dir . basename($employee_picture_filename);
        $employee_picture_url .= rawurlencode(basename($employee_picture_filename));

        $employee_picture_file_info = array(
            'emp_no' => $emp_no,
            'employee_picture_file' => $employee_picture_file,
            'employee_picture_filename' => $employee_picture_filename,
            'employee_picture_filetype' => $employee_picture_filetype,
            'employee_picture_size' => $employee_picture_size,
            'target_file' => $target_file,
            'employee_picture_url' => $employee_picture_url
        );

        // Check Employee Picture File
        $chkEmployeePictureFileMsg = check_employee_picture_file($employee_picture_file_info);

        if ($chkEmployeePictureFileMsg == '') {

            // Add Folder If Not Exists
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $employee_picture_file_exists = false;

            if (file_exists($target_file)) {
                $employee_picture_file_exists = true;

                // Generate a unique ID (timestamp)
                $unique_id = time(); // You can also use uniqid() for a more unique value

                // Specify the new name for the old file with a unique ID
                $new_file_name = $target_dir . 'old_' . $unique_id . '_' . basename($employee_picture_filename);

                // Rename the old file
                if (!rename($target_file, $new_file_name)) {
                    echo "Error renaming the old file.";
                }
            }

            // Upload File and Check if successfully uploaded
            // Note: Can overwrite existing file
            if (move_uploaded_file($employee_picture_file, $target_file)) {
                if (!$employee_picture_file_exists) {
                    save_employee_picture_info($employee_picture_file_info, $conn);
                }
            } else {
                echo "Sorry, there was an error uploading your file. Try Again or Contact IT Personnel if it fails again";
            }

        } else {
            echo $chkEmployeePictureFileMsg;
        }

    } else {
        echo 'Please upload Employee Picture file';
    }
} else {
    echo 'No Employee No Detected. Please re-open Modal';
}

// KILL CONNECTION
$conn = null;
