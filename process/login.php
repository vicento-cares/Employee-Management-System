<?php
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

include 'conn.php';

function get_access_location_by_ip($ip, $conn) {
    $can_access = false;
    $dept = '';
    $section = '';
    $line_no = '';

    $response_arr = array();

    $sql = "SELECT dept, section, line_no FROM m_access_locations WHERE ip = '$ip'";
    $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        foreach($stmt->fetchALL() as $x){
            $dept = $x['dept'];
            $section = $x['section'];
            $line_no = $x['line_no'];
        }
        $can_access = true;
    }

    $response_arr = array(
        'dept' => $dept,
        'section' => $section,
        'line_no' => $line_no,
        'can_access' => $can_access
    );

    return $response_arr;
}

if (isset($_POST['login_btn'])) {
    /*// REMOTE IP ADDRESS
    $ip = $_SERVER['REMOTE_ADDR'];

    $emp_no = addslashes($_POST['emp_no']);

    // CHECK IP
    $response_arr = get_access_location_by_ip($ip, $conn);

    if (empty($emp_no)) {
        echo '<script>alert("Please Scan QR Code or Enter ID Number")</script>';
    } else if ($response_arr['can_access'] == true) {
        // MySQL
        // $check = "SELECT emp_no, full_name, dept, section, line_no, shift_group, role FROM m_accounts WHERE BINARY emp_no = '$emp_no'";
        // MS SQL Server
        $check = "SELECT emp_no, full_name, dept, section, line_no, shift_group, role FROM m_accounts WHERE emp_no = '$emp_no' COLLATE SQL_Latin1_General_CP1_CS_AS";
        $stmt = $conn->prepare($check, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            foreach($stmt->fetchALL() as $x){
                $emp_no = $x['emp_no'];
                $full_name = $x['full_name'];
                $dept = $x['dept'];
                $section = $x['section'];
                $line_no = $x['line_no'];
                $shift_group = $x['shift_group'];
                $role = $x['role'];
            }

            if ($response_arr['dept'] == $dept && $response_arr['section'] == $section && $response_arr['line_no'] == $line_no) {
                $_SESSION['emp_no'] = $emp_no;
                $_SESSION['full_name'] = $full_name;
                $_SESSION['dept'] = $dept;
                $_SESSION['section'] = $section;
                $_SESSION['line_no'] = $line_no;
                $_SESSION['shift_group'] = $shift_group;
                $_SESSION['role'] = $role;
                header('location: home.php');
            } else {
                echo '<script>alert("Sign In Failed. Login in WRONG Dept, Section or Line")</script>';
            }
        } else {
            echo '<script>alert("Sign In Failed. Maybe an incorrect credential or account not found")</script>';
        }
    } else {
        echo '<script>alert("Sign In Failed. WRONG Access Location")</script>';
    }*/

    // REMOTE IP ADDRESS
    $ip = $_SERVER['REMOTE_ADDR'];

    $emp_no = addslashes($_POST['emp_no']);

    // CHECK IP
    $response_arr = get_access_location_by_ip($ip, $conn);

    if (empty($emp_no)) {
        echo '<script>alert("Please Scan QR Code or Enter ID Number")</script>';
    } else if ($response_arr['can_access'] == true) {
        // MySQL
        // $check = "SELECT emp_no, full_name, dept, section, line_no, shift_group, role FROM m_accounts WHERE BINARY emp_no = '$emp_no'";
        // MS SQL Server
        $check = "SELECT emp_no, full_name, dept, section, line_no, shift_group, role FROM m_accounts WHERE emp_no = '$emp_no' COLLATE SQL_Latin1_General_CP1_CS_AS";
        $stmt = $conn->prepare($check, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            foreach($stmt->fetchALL() as $x){
                $emp_no = $x['emp_no'];
                $full_name = $x['full_name'];
                $dept = $x['dept'];
                $section = $x['section'];
                $line_no = $x['line_no'];
                //$shift_group = $x['shift_group'];
                $role = $x['role'];
            }
            if ($role == 'admin') {
                $_SESSION['emp_no'] = $emp_no;
                $_SESSION['full_name'] = $full_name;
                $_SESSION['dept'] = $dept;
                $_SESSION['section'] = $section;
                $_SESSION['line_no'] = $line_no;
                //$_SESSION['shift_group'] = $shift_group;
                $_SESSION['role'] = $role;
                header('location: home.php');
            } else if ($response_arr['line_no'] == $line_no) {
                $_SESSION['emp_no'] = $emp_no;
                $_SESSION['full_name'] = $full_name;
                $_SESSION['dept'] = $dept;
                $_SESSION['section'] = $section;
                $_SESSION['line_no'] = $line_no;
                //$_SESSION['shift_group'] = $shift_group;
                $_SESSION['role'] = $role;
                header('location: home.php');
            } else {
                echo '<script>alert("Sign In Failed. Login in WRONG Line \nNote: If this was your new Line, please submit transfer form to HR")</script>';
            }
        } else {
            echo '<script>alert("Sign In Failed. Maybe an incorrect credential or account not found")</script>';
        }
    } else {
        echo '<script>alert("Sign In Failed. Unregistered IP: '.$ip.'! Call IT Personnel Immediately!")</script>';
    }

    /*$emp_no = addslashes($_POST['emp_no']);

    if (empty($emp_no)) {
        echo '<script>alert("Please Scan QR Code or Enter ID Number")</script>';
    } else {
        $check = "SELECT emp_no, full_name, dept, section, line_no, shift_group, role FROM m_accounts WHERE BINARY emp_no = '$emp_no'";
        $stmt = $conn->prepare($check, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            foreach($stmt->fetchALL() as $x){
                $emp_no = $x['emp_no'];
                $full_name = $x['full_name'];
                $dept = $x['dept'];
                $section = $x['section'];
                $line_no = $x['line_no'];
                $shift_group = $x['shift_group'];
                $role = $x['role'];
                $_SESSION['emp_no'] = $emp_no;
                $_SESSION['full_name'] = $full_name;
                $_SESSION['dept'] = $dept;
                $_SESSION['section'] = $section;
                $_SESSION['line_no'] = $line_no;
                $_SESSION['shift_group'] = $shift_group;
                $_SESSION['role'] = $role;
                header('location: home.php');
            }
        } else {
            echo '<script>alert("Sign In Failed. Maybe an incorrect credential or account not found")</script>';
        }
    }*/
}

if (isset($_POST['Logout'])) {
    session_unset();
    session_destroy();
    header('location:/emp_mgt/admin');
}
?>