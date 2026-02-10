<?php
// error_reporting(0);
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit("Invalid request.");
}

if (!isset($_POST['emp_no']) || empty($_POST['emp_no'])) {
    exit("No Employee No Detected. Please re-open Modal");
}

if (preg_match('/[^a-zA-Z0-9_-]/', $_POST['emp_no'])) {
    // Unwanted characters exist
    exit("Invalid employee number. Only alphanumeric characters, underscores, and hyphens are allowed.");
}

if (empty($_FILES['file']['name'])) {
    exit("Please upload Employee Picture file");
}

if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    exit("File upload failed. Error code: " . $_FILES['file']['error']);
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

    $mimes = ['image/png'];

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

    $sql = "MERGE INTO m_employee_pictures AS target 
            USING (SELECT ? AS emp_no, ? AS file_url) AS source 
            ON target.emp_no = source.emp_no 
            WHEN MATCHED THEN 
                UPDATE SET file_url = source.file_url 
            WHEN NOT MATCHED THEN 
                INSERT (emp_no, file_url) 
                VALUES (source.emp_no, source.file_url);";

    $stmt = $conn->prepare($sql);
    $params = array($emp_no, $employee_picture_url);
    $stmt->execute($params);
}

$emp_no = $_POST['emp_no'];

// $employee_picture_filename = $_FILES['file']['name'];
$employee_picture_filename = $emp_no . ".png";

$employee_picture_file = $_FILES['file']['tmp_name'];

// $employee_picture_filetype = $_FILES['file']['type'];
$finfo = new finfo(FILEINFO_MIME_TYPE);
$employee_picture_filetype = $finfo->file($employee_picture_file);

$employee_picture_size = $_FILES['file']['size'];

$imageType = exif_imagetype($employee_picture_file);

if ($imageType === false) {
    exit("Uploaded file is not a valid image.");
}

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

if ($chkEmployeePictureFileMsg != '') {
    exit($chkEmployeePictureFileMsg);
}

// Check for supported image types
if (!in_array($imageType, [IMAGETYPE_PNG])) {
    exit("Unsupported image type.");
}

// Add Folder If Not Exists
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

$employee_picture_file_exists = false;

if (file_exists($target_file)) {
    $employee_picture_file_exists = true;

    // Generate a unique ID (timestamp)
    $unique_id = str_replace('.', '', microtime(true)); // You can also use uniqid() for a more unique value

    // Specify the new name for the old file with a unique ID
    $new_file_name = $target_dir . 'old_' . $unique_id . '_' . basename($employee_picture_filename);

    // Rename the old file
    if (!rename($target_file, $new_file_name)) {
        exit("Error renaming the old file.");
    }
}

// Upload File and Check if successfully uploaded
// Note: Can overwrite existing file
if (!move_uploaded_file($employee_picture_file, $target_file)) {
    exit("Sorry, there was an error uploading your file. Try Again or Contact IT Personnel if it fails again");
}

// Get image dimensions
list($originalWidth, $originalHeight) = getimagesize($target_file);

// Set new width and calculate new height to maintain aspect ratio
$newWidth = 326;
$newHeight = (int)(($newWidth / $originalWidth) * $originalHeight);

// Load the image based on its type
if ($imageType === IMAGETYPE_PNG) {
    $foo = imagecreatefrompng($target_file);
} else {
    exit("Unsupported image type (2).");
}

// Only resize if the image is larger than the target width
if ($originalWidth > $newWidth) {
    // Create a new true color image
    $newImage = imagecreatetruecolor($newWidth, $newHeight);

    imagealphablending($newImage, false);
    imagesavealpha($newImage, true);
    $transparent = imagecolorallocatealpha($newImage, 0, 0, 0, 127);
    imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);

    imagecopyresampled($newImage, $foo, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

    // Save the resized image, overwriting the original uploaded file
    if ($imageType === IMAGETYPE_PNG) {
        imagepng($newImage, $target_file);
    }

    // Free up memory
    imagedestroy($foo);
    imagedestroy($newImage);
}

if (isset($foo)) {
    imagedestroy($foo);
}

include '../conn.php';
save_employee_picture_info($employee_picture_file_info, $conn);
$conn = null;
