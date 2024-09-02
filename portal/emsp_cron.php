<?php
require '../process/conn.php';
require '../process/conn_portal.php';

$method = $_POST['method'];

// Login

// Check User Login Req Waiting
if ($method == 'check_user_login_req_waiting') {
    $request_json = $_POST['request_json'];
    $request_arr = json_decode($request_json, true);
    $response_arr = array();
    $req_count = 0;

    foreach ($request_arr as &$request) {
        // MySQL
        // $check = "SELECT emp_no, full_name, dept, position, date_hired, address, contact_no, emp_status FROM m_employees WHERE BINARY emp_no = ? AND resigned = 0";
        // MS SQL Server
        $check = "SELECT emp_no, full_name, dept, position, date_hired, address, contact_no, emp_status FROM m_employees WHERE emp_no = ? COLLATE SQL_Latin1_General_CP1_CS_AS AND resigned = 0";
        $stmt = $conn->prepare($check, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        $params = array($request['emp_no']);
        $stmt->execute($params);

        $row = $stmt -> fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $sql = "UPDATE t_user_login_req 
                SET full_name = ?, dept = ?, position = ?, date_hired = ?, 
                address = ?, contact_no = ?, emp_status = ?, req_status = ? 
                WHERE emp_no = ?";
            $stmt = $conn_portal->prepare($sql);
            $params = array($row['full_name'], $row['dept'], $row['position'], $row['date_hired'], $row['address'], $row['contact_no'], $row['emp_status'], 1, $request['emp_no']);
            $stmt->execute($params);
        } else {
            $sql = "UPDATE t_user_login_req 
                SET req_status = ? 
                WHERE emp_no = ?";
            $stmt = $conn_portal->prepare($sql);
            $params = array(2, $request['emp_no']);
            $stmt->execute($params);
        }

        $req_count++;

        // if ($row) {
        //     $response_arr = array(
        //         "emp_no" => $row['emp_no'],
        //         "full_name" => $row['full_name'],
        //         "dept" => $row['dept'],
        //         "position" => $row['position'],
        //         "date_hired" => $row['date_hired'],
        //         "address" => $row['address'],
        //         "contact_no" => $row['contact_no'],
        //         "emp_status" => $row['emp_status'],
        //         "req_status" => 1,
        //         "message" => 'success'
        //     );
        // } else {
        //     $response_arr = array(
        //         "req_status" => 2,
        //         "message" => 'success'
        //     );
        // }
    }

    $response_arr = [
        "req_count" => $req_count,
        "message" => 'success'
    ];

    // header('Content-Type: application/json; charset=utf-8');
	// echo json_encode($response_arr, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    echo json_encode($response_arr);
}

// Leave Form

// Check Leave Form Req Waiting
if ($method == 'check_leave_form_req_waiting') {
    $request_json = $_POST['request_json'];
    $request_arr = json_decode($request_json, true);
    $response_arr = array();
    $req_count = 0;

    foreach ($request_arr as &$request) {
        $sql = "SELECT id, emp_no, date_filed, address, contact_no, leave_type, 
            leave_date_from, leave_date_to, total_leave_days, irt_phone_call, irt_letter, irb, 
            reason, issued_by FROM t_leave_form_req WHERE id = ?";
        $stmt = $conn_portal->prepare($sql);
        $params = array($request['id']);
        $stmt->execute($params);

        $row = $stmt -> fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $clinic_leave_status_arr = array('SL','Paternity','SSS Benefits','Maternity','Sickness','Others');
            $leave_form_status = '';
            if (in_array($row['leave_type'], $clinic_leave_status_arr)) {
                $leave_form_status = 'clinic';
            } else {
                $leave_form_status = 'pending';
            }

            $leave_form_id = date("ymdh");
            $rand = substr(md5(microtime()),rand(0,26),5);
            $leave_form_id = 'LAF-'.$leave_form_id;
            $leave_form_id = $leave_form_id.''.$rand;

            $sql = "INSERT INTO t_leave_form 
                    (leave_form_id, emp_no, date_filed, address, contact_no, 
                    leave_type, leave_date_from, leave_date_to, total_leave_days, 
                    irt_phone_call, irt_letter, irb, reason, issued_by, leave_form_status) 
                    VALUES 
                    (?, ?, ?, ?, ?, 
                    ?, ?, ?, ?, 
                    ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $params = array($leave_form_id, $row['emp_no'], $row['date_filed'], $row['address'], $row['contact_no'], 
                    $row['leave_type'], $row['leave_date_from'], $row['leave_date_to'], $row['total_leave_days'], 
                    $row['irt_phone_call'], $row['irt_letter'], $row['irb'], $row['reason'], $row['issued_by'], $leave_form_status);
            $stmt->execute($params);

            $sql = "DELETE FROM t_leave_form_req WHERE id = ?";
            $stmt = $conn_portal->prepare($sql);
            $params = array($row['id']);
            $stmt->execute($params);
        }

        $req_count++;
    }

    $response_arr = [
        "req_count" => $req_count,
        "message" => 'success'
    ];

    // header('Content-Type: application/json; charset=utf-8');
	// echo json_encode($response_arr, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    echo json_encode($response_arr);
}
