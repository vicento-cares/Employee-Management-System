<?php
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

$method = $_POST['method'];

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';

function findFile($filename, $directory) {
    $foundFiles = [];
    
    // Create a RecursiveDirectoryIterator
    $iterator = new RecursiveDirectoryIterator($directory);
    $recursiveIterator = new RecursiveIteratorIterator($iterator);

    // Iterate through each file
    foreach ($recursiveIterator as $file) {
        // Check if the current file matches the filename exactly (case-sensitive)
        if ($file->getFilename() === $filename) {
            $foundFiles[] = $file->getPathname(); // Store the full path of the found file
        }
    }

    return $foundFiles;
}

if ($method == 'reload_employee_picture') {
	if (isset($_POST['emp_no']) && !empty($_POST['emp_no'])) {
		$employee_picture_filename = $_POST['emp_no'] . ".png"; // Change this to the filename you want to find

		$employee_picture_url = "/uploads/emp_mgt/employee_picture/";
		$target_dir = "D:\\uploads\\emp_mgt\\employee_picture\\"; // Your target directory

		$results = findFile($employee_picture_filename, $target_dir);

		if (!empty($results)) {
		    foreach ($results as $result) {
		        $employee_picture_url .= rawurlencode(basename($employee_picture_filename));
		        echo htmlspecialchars($protocol.$_SERVER['SERVER_ADDR'].":".$_SERVER['SERVER_PORT'].$employee_picture_url);
		    }
		} else {
			echo htmlspecialchars($protocol.$_SERVER['SERVER_ADDR'].":".$_SERVER['SERVER_PORT']).'/emp_mgt/dist/img/user.png';
		}
	} else {
	    echo 'No Employee No Detected. Please re-open Modal';
	}
}
