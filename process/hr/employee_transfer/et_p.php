<?php 
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

include '../../conn.php';

$method = $_POST['method'];

if ($method == 'submit_employee_transfer') {
    if (!isset($_SESSION['emp_no_control_area'])) {
        echo 'Session Expired. Please Re-Login your account!';
        $conn = null;
        exit();
    }

    $emp_transfer_id_prefix = '';
    $emp_no = $_POST['emp_no'];
    $emp_transfer_type = $_POST['emp_transfer_type'];
    $dept_to = $_POST['dept'];
    $section_to = $_POST['section'];
    $line_no_to = $_POST['line_no'];
    $date_effectivity = $_POST['date_effectivity'];
    $reason = $_POST['reason'];
    $emp_js_s = $_SESSION['full_name'];
    $emp_js_s_no = $_SESSION['emp_js_s_no'];

    if ($emp_transfer_type == 'department') {
        $emp_transfer_id_prefix = 'HR-014-';
    } else if ($emp_transfer_type == 'section') {
        $emp_transfer_id_prefix = 'PRD-032-';
    }

    $emp_transfer_id = str_replace('.', '', uniqid($emp_transfer_id_prefix, true));

    $query = "INSERT INTO t_employee_transfer 
                    (emp_transfer_id, emp_no, emp_transfer_type, dept_to, section_to, line_no_to, emp_js_s, emp_js_s_no, reason, date_effectivity) 
                VALUES 
                    (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);

    $params = [
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

    if ($stmt->execute($params)) {
        echo 'success';
    } else {
        echo 'error';
    }
}

$conn = null;
