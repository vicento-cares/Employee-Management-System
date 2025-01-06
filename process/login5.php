<?php
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

include 'conn.php';

if (isset($_POST['login_btn'])) {

    $emp_no = addslashes($_POST['emp_no']);

    if (empty($emp_no)) {
        echo '<script>alert("Please Scan QR Code or Enter ID Number")</script>';
    } else {
        // MySQL
        // $check = "SELECT emp_no, full_name, dept, section, line_no, shift_group, role FROM m_control_area_accounts WHERE BINARY emp_no = '$emp_no'";
        // MS SQL Server
        $check = "
            SELECT emp_no, full_name, dept, section, line_no, shift_group, role 
            FROM m_control_area_accounts 
            WHERE emp_no = '$emp_no' COLLATE SQL_Latin1_General_CP1_CS_AS
            UNION
            SELECT emp_no, full_name, dept, section, line_no, shift_group, role 
            FROM m_accounts 
            WHERE emp_no = '$emp_no' COLLATE SQL_Latin1_General_CP1_CS_AS
        ";
        $stmt = $conn->prepare($check);
        $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($results) > 0) {
			foreach ($results as $row) {
                $emp_no = $row['emp_no'];
                $full_name = $row['full_name'];
                $dept = $row['dept'];
                $section = $row['section'];
                $line_no = $row['line_no'];
                $shift_group = $row['shift_group'];
                $role = $row['role'];
            }
            $_SESSION['emp_no_control_area'] = $emp_no;
            $_SESSION['full_name'] = $full_name;
            $_SESSION['dept'] = $dept;
            $_SESSION['section'] = $section;
            $_SESSION['line_no'] = $line_no;
            $_SESSION['shift_group'] = $shift_group;
            $_SESSION['role'] = $role;
            if (empty($_SESSION['line_no'])) {
                header('location: employees.php');
            } else {
                header('location: certification.php');
            }
        } else {
            echo '<script>alert("Sign In Failed. Maybe an incorrect credential or account not found")</script>';
        }
    }
}

if (isset($_POST['Logout'])) {
    session_unset();
    session_destroy();
    header('location:/emp_mgt/control_area');
}
