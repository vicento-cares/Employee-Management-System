<?php
session_name("emp_mgt");
session_start();

include 'conn.php';

if (isset($_POST['login_btn'])) {
    $emp_no = addslashes($_POST['emp_no']);

    if (empty($emp_no)) {
        echo '<script>alert("Please Scan QR Code or Enter ID Number")</script>';
    } else {
        $check = "SELECT emp_no, full_name, dept, section, line_no, role FROM m_clinic_accounts WHERE BINARY emp_no = '$emp_no'";
        $stmt = $conn->prepare($check, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            foreach($stmt->fetchALL() as $x){
                $emp_no = $x['emp_no'];
                $full_name = $x['full_name'];
                $dept = $x['dept'];
                $section = $x['section'];
                $line_no = $x['line_no'];
                $role = $x['role'];
                $_SESSION['emp_no_clinic'] = $emp_no;
                $_SESSION['full_name'] = $full_name;
                $_SESSION['dept'] = $dept;
                $_SESSION['section'] = $section;
                $_SESSION['line_no'] = $line_no;
                $_SESSION['role'] = $role;
                header('location: home.php');
            }
        } else {
            echo '<script>alert("Sign In Failed. Maybe an incorrect credential or account not found")</script>';
        }
    }
}

if (isset($_POST['Logout'])) {
    session_unset();
    session_destroy();
    header('location:/emp_mgt/clinic');
}
?>