<?php 
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

include '../../conn.php';
include '../../conn_mailer.php';
include '../../lib/approve_email.php';

$method = $_POST['method'];

function add_emp_transfer_history($mail_arr, $conn) {
    $approve_email_opt = intval($mail_arr['approve_email_opt']);

    $params = [];

    $addedQuery = "";

    if ($approve_email_opt == 1) {
        $addedQuery = " ? AS hr_ack, ? AS hr_ack_no, ? AS hr_date_ack, 1 AS is_approved";
        $params[] = $mail_arr['hr_ack'];
        $params[] = $mail_arr['hr_ack_no'];
        $params[] = $mail_arr['hr_date_ack'];
    } else if ($approve_email_opt == 0) {
        $addedQuery = " NULL AS hr_ack, NULL AS hr_ack_no, NULL AS hr_date_ack, 0 AS is_approved";
    }

    $isTransactionActive = false;

    try {
        if (!$isTransactionActive) {
            $conn->beginTransaction();
            $isTransactionActive = true;
        }

        $query = "INSERT INTO t_employee_transfer_history 
                        (emp_transfer_id, emp_transfer_batch_id, approve_key, emp_no, emp_transfer_type, 
                        dept_from, section_from, line_no_from, 
                        dept_to, section_to, line_no_to, 
                        issued_by, issued_by_no, date_issued_by, checked_by, checked_by_no, date_checked_by, approved_by, approved_by_no, date_approved_by, 
                        r_noted_by, r_noted_by_no, r_date_noted_by, r_acknowledged_by, r_acknowledged_by_no, r_date_acknowledged_by, 
                        r_approved_by, r_approved_by_no, r_date_approved_by, date_effectivity, reason, 
                        hr_ack, hr_ack_no, hr_date_ack, is_approved) 
                    SELECT 
                        emp_transfer_id, emp_transfer_batch_id, approve_key, emp_no, emp_transfer_type, 
                        dept_from, section_from, line_no_from, 
                        dept_to, section_to, line_no_to, 
                        issued_by, issued_by_no, date_issued_by, checked_by, checked_by_no, date_checked_by, approved_by, approved_by_no, date_approved_by, 
                        r_noted_by, r_noted_by_no, r_date_noted_by, r_acknowledged_by, r_acknowledged_by_no, r_date_acknowledged_by, 
                        r_approved_by, r_approved_by_no, r_date_approved_by, date_effectivity, reason, 
                        $addedQuery 
                    FROM 
                        t_employee_transfer 
                    WHERE 
                        emp_transfer_batch_id = ? AND approve_key = ?";

        $stmt = $conn->prepare($query);

        $params[] = $mail_arr['emp_transfer_batch_id'];
        $params[] = $mail_arr['approve_key'];

        $stmt->execute($params);

        $query = "DELETE FROM t_employee_transfer WHERE emp_transfer_batch_id = ? AND approve_key = ?";

        $stmt = $conn->prepare($query);

        $stmt->execute([$mail_arr['emp_transfer_batch_id'], $mail_arr['approve_key']]);

        $conn->commit();
        $isTransactionActive = false;
    } catch (Exception $e) {
        if ($isTransactionActive) {
            $conn->rollBack();
            $isTransactionActive = false;
        }

        return 'Failed. Please Try Again or Call IT Personnel Immediately!: ' . $e->getMessage();
    }

    // Collect all for transfer and update employee information by emp_no (n+1q)
    if ($approve_email_opt == 1) {
        $query = "SELECT 
                        emp_no, 
                        dept_to, section_to, line_no_to 
                    FROM 
                        t_employee_transfer_history 
                    WHERE 
                        emp_transfer_batch_id = ? AND approve_key = ?";

        $stmt = $conn->prepare($query);

        $stmt->execute([$mail_arr['emp_transfer_batch_id'], $mail_arr['approve_key']]);

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $query2 = "UPDATE m_employees SET dept = ?, section = ?, line_no = ? WHERE emp_no = ?";
            $stmt2 = $conn->prepare($query2);
            $stmt2->execute([
                $row['dept_to'],
                $row['section_to'],
                $row['line_no_to'],
                $row['emp_no']
            ]);

            $query2 = "UPDATE m_accounts SET dept = ?, section = ?, line_no = ? WHERE emp_no = ?";
            $stmt2 = $conn->prepare($query2);
            $stmt2->execute([
                $row['dept_to'],
                $row['section_to'],
                $row['line_no_to'],
                $row['emp_no']
            ]);
        }
    }

    return 'success';
}

function get_issued_by_email($mail_arr, $conn) {
    $sendto = '';

    // Get Send To Emails
    $query = "SELECT 
                    email 
                FROM 
                    m_control_area_accounts 
                WHERE 
                    emp_no = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$mail_arr['issued_by_no']]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $sendto = $row['email'];
    }

    return $sendto;
}

function get_receiving_mp_email($mail_arr, $conn) {
    $sendto = [];

    // First query to get dept_to and section_to
    $query = "SELECT 
                    dept_to, section_to 
              FROM 
                    t_employee_transfer_history 
              WHERE 
                    emp_transfer_batch_id = ? AND approve_key = ?
              GROUP BY
                    dept_to, section_to";

    $stmt = $conn->prepare($query);
    $stmt->execute([$mail_arr['emp_transfer_batch_id'], $mail_arr['approve_key']]);

    $departments = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Store dept_to and section_to in an array
        $departments[] = ['dept' => $row['dept_to'], 'section' => $row['section_to']];
    }

    // Prepare arrays for the next query's parameters
    $depts = [];
    $sections = [];
    foreach ($departments as $dept_section) {
        $depts[] = $dept_section['dept'];
        $sections[] = $dept_section['section'];
    }

    // Placeholders for the IN clause
    $deptPlaceholders = implode(',', array_fill(0, count($depts), '?'));
    $sectionPlaceholders = implode(',', array_fill(0, count($sections), '?'));

    // Second query to get emails
    $query = "SELECT 
                    email 
              FROM 
                    m_control_area_accounts 
              WHERE 
                    dept IN ($deptPlaceholders) AND section IN ($sectionPlaceholders)";

    $stmt = $conn->prepare($query);
    $stmt->execute(array_merge($depts, $sections));

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Append emails to the $sendto array
        $sendto[] = $row['email'];
    }

    return $sendto;
}

if ($method == 'get_ongoing_employee_transfer') {
    $dept_from = '';
    $section_from = '';

    if (isset($_SESSION['emp_no_control_area'])) {
        $dept_from = $_SESSION['dept'];
	    $section_from = $_SESSION['section'];
    } else if (isset($_SESSION['emp_no_hr'])) {
        $dept_from = $_POST['dept_from'];
        $section_from = $_POST['section_from'];
    } else {
        echo 'Session Expired. Please re-login your account.';
		$conn = null;
		exit();
    }

    $emp_no = $_POST['emp_no'];
	$full_name = $_POST['full_name'];
    $provider = $_POST['provider'];
    $position = $_POST['position'];
    $emp_transfer_type = $_POST['emp_transfer_type'];
	$line_no_from = $_POST['line_no_from'];
    $dept_to = $_POST['dept_to'];
    $section_to = $_POST['section_to'];
    $line_no_to = $_POST['line_no_to'];
    $is_checked_by = intval($_POST['is_checked_by']);
    $is_approved_by = intval($_POST['is_approved_by']);
    $is_receiving_noted_by = intval($_POST['is_receiving_noted_by']);
    $is_receiving_acknowledged_by = intval($_POST['is_receiving_acknowledged_by']);
    $is_receiving_approved_by = intval($_POST['is_receiving_approved_by']);

	$c = 0;

	$query = "SELECT 
					et.id, et.emp_no, et.emp_transfer_type, 
                    et.dept_from, et.section_from, et.line_no_from, 
                    et.dept_to, et.section_to, et.line_no_to, 
                    et.issued_by, et.checked_by, et.approved_by, 
                    et.r_noted_by, et.r_acknowledged_by, et.r_approved_by, 
                    et.reason, et.date_effectivity, 
                    emp.full_name, emp.provider, emp.position, 
                    CASE 
                        WHEN et.date_effectivity < GETDATE() THEN 'overdue' 
                        ELSE 'ongoing' 
                    END AS date_effectivity_status 
				FROM t_employee_transfer et 
                LEFT JOIN m_employees emp ON et.emp_no = emp.emp_no 
				WHERE et.dept_from != ''";

	$params = [];

    if (!empty($dept_from)) {
		$query = $query . " AND et.dept_from = ?";
		$params[] = $dept_from;
	}
    
    if (!empty($section_from)) {
		$query = $query . " AND et.section_from = ?";
		$params[] = $section_from;
	}
    
    if (!empty($line_no_from)) {
		$query = $query . " AND et.line_no_from = ?";
		$params[] = $line_no_from;
	}

	if (!empty($emp_no)) {
		$query = $query . " AND et.emp_no LIKE ?";
		$emp_no_search = $emp_no . "%";
		$params[] = $emp_no_search;
	}

    if (!empty($full_name)) {
		$query = $query . " AND emp.full_name LIKE ?";
        $full_name_search = $full_name . "%";
		$params[] = $full_name_search;
	}

    if (!empty($provider)) {
		$query = $query . " AND emp.provider = ?";
		$params[] = $provider;
	}

    if (!empty($position)) {
		$query = $query . " AND emp.position = ?";
		$params[] = $position;
	}

    if (!empty($emp_transfer_type)) {
		$query = $query . " AND et.emp_transfer_type = ?";
		$params[] = $emp_transfer_type;
	}

    if (!empty($emp_transfer_type)) {
		$query = $query . " AND et.emp_transfer_type = ?";
		$params[] = $emp_transfer_type;
	}

    if (!empty($dept_to)) {
		$query = $query . " AND et.dept_to = ?";
		$params[] = $dept_to;
	}

    if (!empty($section_to)) {
		$query = $query . " AND et.section_to = ?";
		$params[] = $section_to;
	}

    if (!empty($line_no_to)) {
		$query = $query . " AND et.line_no_to = ?";
		$params[] = $line_no_to;
	}

    if ($is_checked_by > 0) {
		$query = $query . " AND et.checked_by IS NOT NULL";
	}
    if ($is_approved_by > 0) {
		$query = $query . " AND et.approved_by IS NOT NULL";
	}
    if ($is_receiving_noted_by > 0) {
		$query = $query . " AND et.r_noted_by IS NOT NULL";
	}
    if ($is_receiving_acknowledged_by > 0) {
		$query = $query . " AND et.r_acknowledged_by IS NOT NULL";
	}
    if ($is_receiving_approved_by > 0) {
		$query = $query . " AND et.r_approved_by IS NOT NULL";
	}

	$stmt = $conn->prepare($query);
	$stmt->execute($params);

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $c++;

        $row_class = '';
        $row_edit = '';

        if (($row['emp_transfer_type'] == 'department' && $row['checked_by'] == '') || 
            ($row['emp_transfer_type'] == 'section' && $row['approved_by'] == '')) {
            $row_class = 'bg-secondary';
            $row_edit = 'style="cursor:pointer;" class="modal-trigger" data-toggle="modal" data-target="#update_employee_transfer" 
                        data-id="'.$row['id'].'" 
                        data-emp_no="'.htmlspecialchars($row['emp_no']).'" 
                        data-emp_transfer_type="'.htmlspecialchars($row['emp_transfer_type']).'" 
                        data-dept_to="'.htmlspecialchars($row['dept_to']).'" 
                        data-section_to="'.htmlspecialchars($row['section_to']).'" 
                        data-line_no_to="'.htmlspecialchars($row['line_no_to']).'" 
                        data-date_effectivity="'.htmlspecialchars($row['date_effectivity']).'" 
                        data-reason="'.htmlspecialchars($row['reason']).'" 
                        onclick="get_employee_transfer_details(this)"';
        } else if ($row['date_effectivity_status'] == 'overdue') {
            $row_class = 'bg-danger';
        }

        echo '<tr class="'.$row_class.'" '.$row_edit.'>';

        echo '<td>'.$c.'</td>';
        echo '<td>'.$row['date_effectivity'].'</td>';
        echo '<td>'.$row['emp_no'].'</td>';
        echo '<td>'.$row['full_name'].'</td>';
        echo '<td>'.$row['provider'].'</td>';
        echo '<td>'.$row['position'].'</td>';
        echo '<td>'.$row['emp_transfer_type'].'</td>';
        echo '<td>'.$row['dept_from'].'</td>';
        echo '<td>'.$row['section_from'].'</td>';
        echo '<td>'.$row['line_no_from'].'</td>';
        echo '<td>'.$row['dept_to'].'</td>';
        echo '<td>'.$row['section_to'].'</td>';
        echo '<td>'.$row['line_no_to'].'</td>';
        echo '<td>'.$row['issued_by'].'</td>';
        echo '<td>'.$row['checked_by'].'</td>';
        echo '<td>'.$row['approved_by'].'</td>';
        echo '<td>'.$row['r_noted_by'].'</td>';
        echo '<td>'.$row['r_acknowledged_by'].'</td>';
        echo '<td>'.$row['r_approved_by'].'</td>';
        echo '<td>'.$row['reason'].'</td>';

        echo '</tr>';
    }
}

if ($method == 'submit_employee_transfer') {
    if (!isset($_SESSION['emp_no_control_area'])) {
        echo 'Session Expired. Please Re-Login your account!';
        $conn = null;
        exit();
    }

    $emp_transfer_id = '';
    $emp_no = $_POST['emp_no'];
    $emp_transfer_type = $_POST['emp_transfer_type'];
    $dept_to = $_POST['dept'];
    $section_to = $_POST['section'];
    $line_no_to = $_POST['line_no'];
    $date_effectivity = $_POST['date_effectivity'];
    $reason = $_POST['reason'];
    $issued_by = $_SESSION['full_name'];
    $issued_by_no = $_SESSION['emp_no_control_area'];

    if ($emp_transfer_type == 'department') {
        $emp_transfer_id = str_replace('.', '', uniqid('HR-014-', true));
    } else if ($emp_transfer_type == 'section') {
        $emp_transfer_id = str_replace('.', '', uniqid('PRD-032-', true));
    }

    $emp_transfer_batch_id = str_replace('.', '', uniqid('ET-BAT-', true));
    $approve_key = str_replace('.', '', uniqid('emp_mgt_key_', true));

    $dept_from = '';
    $section_from = '';
    $line_no_from = '';

    // get current dept from and section from session of issuer
    $dept_from_issuer = $_SESSION['dept'];
    $section_from_issuer = $_SESSION['section'];

    $query = "SELECT dept, section, line_no FROM m_employees WHERE emp_no = ? AND dept = ? AND section = ? AND resigned = 0";
    $stmt = $conn->prepare($query);
    $stmt->execute([$emp_no, $dept_from_issuer, $section_from_issuer]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $dept_from = $row['dept'];
        $section_from = $row['section'];
        $line_no_from = $row['line_no'];

        if ($emp_transfer_type == 'section') {
            $dept_to = $dept_from;
        }
    } else {
        echo 'Not Manpower of this department/section';
        $conn = null;
        exit();
    }

    $query = "SELECT TOP 1 id FROM m_access_locations WHERE dept = ? AND section = ? -- AND line_no = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$dept_to, $section_to]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo 'Wrong department/section combination';
        $conn = null;
        exit();
    }

    $query = "SELECT emp_no FROM t_employee_transfer WHERE emp_no = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$emp_no]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        echo 'Duplicate / Already for transfer';
        $conn = null;
        exit();
    }

    $params = [
        $emp_transfer_id,
        $emp_transfer_batch_id,
        $approve_key,
        $emp_no,
        $emp_transfer_type,
        $dept_from,
        $section_from,
        $line_no_from,
        $dept_to,
        $section_to,
        $line_no_to,
        $issued_by,
        $issued_by_no,
        $reason,
        $date_effectivity
    ];

    $query = "INSERT INTO t_employee_transfer 
                    (emp_transfer_id, emp_transfer_batch_id, approve_key, emp_no, emp_transfer_type, 
                    dept_from, section_from, line_no_from, 
                    dept_to, section_to, line_no_to, 
                    issued_by, issued_by_no, reason, date_effectivity) 
                VALUES 
                    (?, ?, ?, ?, ?, 
                    ?, ?, ?, 
                    ?, ?, ?, 
                    ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);

    if (!$stmt->execute($params)) {
        echo 'error';
        $conn = null;
        exit();
    }

    $send_to_emails = [];

    // Get Send To Emails
    $query = "SELECT 
                    email 
                FROM 
                    m_control_area_accounts 
                WHERE 
                    dept = ? AND 
                    section = ? AND 
                    position IN ('Assistant Manager', 'Section Manager')";
    $stmt = $conn->prepare($query);
    $stmt->execute([$dept_from, $section_from]);

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $send_to_emails[] = $row['email'];
    }
    
    $sendto = implode(";", $send_to_emails);

    $mail_arr = [
        'approve_email_opt' => 2,
        'emp_transfer_batch_id' => $emp_transfer_batch_id,
        'approve_key' => $approve_key,
        'sendto' => $sendto
    ];

    send_mail($mail_arr, $conn_mailer);

    echo 'success';
}

if ($method == 'update_employee_transfer') {
    $id = $_POST['id'];
    $dept_to = $_POST['dept'];
    $section_to = $_POST['section'];
    $line_no_to = $_POST['line_no'];
    $date_effectivity = $_POST['date_effectivity'];
    $reason = $_POST['reason'];

    $query = "UPDATE 
                t_employee_transfer 
                SET 
                    dept_to = ?, section_to = ?, line_no_to = ?, 
                    reason = ?, date_effectivity = ? 
                WHERE 
                    id = ?";

    $params = [
        $dept_to,
        $section_to,
        $line_no_to,
        $reason,
        $date_effectivity,
        $id
    ];

    $stmt = $conn->prepare($query);

    if ($stmt->execute($params)) {
        echo 'success';
    } else {
        echo 'error';
    }
}

if ($method == 'cancel_employee_transfer') {
    $id = $_POST['id'];

    $query = "DELETE FROM t_employee_transfer WHERE id = ?";

    $stmt = $conn->prepare($query);

    if ($stmt->execute([$id])) {
        echo 'success';
    } else {
        echo 'error';
    }
}

if ($method == 'approve_employee_transfer') {
    $opt = intval($_POST['opt']);
    $emp_transfer_batch_id = $_POST['emp_transfer_batch_id'];
	$approve_key = $_POST['approve_key'];
    $approver_emp_no = $_POST['approver_emp_no'];

    $emp_transfer_type = '';

    $dept_from = '';
    $section_from = '';
    $dept_to = '';
    $section_to = '';

    $checked_by = '';
    $approved_by = '';
    $r_noted_by = '';
    $r_acknowledged_by = '';
    $r_approved_by = '';

    // check and get all data
    $query = "SELECT 
                    emp_transfer_id, 
                    emp_transfer_batch_id, 
                    approve_key, 
                    emp_no, 
                    emp_transfer_type, 
                    dept_from, 
                    section_from, 
                    line_no_from, 
                    dept_to, 
                    section_to, 
                    line_no_to, 
                    date_effectivity, 
                    reason, 
                    issued_by, 
                    issued_by_no, 
                    date_issued_by, 
                    checked_by, 
                    date_checked_by, 
                    approved_by, 
                    date_approved_by, 
                    r_noted_by, 
                    r_date_noted_by, 
                    r_acknowledged_by, 
                    r_date_acknowledged_by, 
                    r_approved_by, 
                    r_date_approved_by 
                FROM 
                    t_employee_transfer 
                WHERE 
                    emp_transfer_batch_id = ? AND 
                    approve_key = ?";

    $stmt = $conn->prepare($query);
    $stmt->execute([$emp_transfer_batch_id, $approve_key]);

    $emp_transfer_rows = $stmt -> fetchAll(PDO::FETCH_ASSOC);

    if (!$emp_transfer_rows) {
        echo 'not found';
        $conn = null;
        exit();
    }

    // get first row only
    $row = $emp_transfer_rows[0];

    $emp_transfer_type = $row['emp_transfer_type'];

    $dept_from = $row['dept_from'];
    $section_from = $row['section_from'];
    $dept_to = $row['dept_to'];
    $section_to = $row['section_to'];

    $issued_by = $row['issued_by'];
    $issued_by_no = $row['issued_by_no'];

    $checked_by = $row['checked_by'];
    $approved_by = $row['approved_by'];
    $r_noted_by = $row['r_noted_by'];
    $r_acknowledged_by = $row['r_acknowledged_by'];
    $r_approved_by = $row['r_approved_by'];

    $approver_name = '';
    $is_hr = false;

    // check hr acknowledger & control approver details
    $query = "SELECT 
                    h.full_name AS hr_full_name, 
                    c.full_name AS control_full_name 
                FROM 
                    m_hr_accounts h
                FULL OUTER JOIN 
                    m_control_area_accounts c ON h.emp_no = c.emp_no
                WHERE 
                    (h.emp_no = ? AND h.role = 'HR') OR c.emp_no = ?";

    $stmt = $conn->prepare($query);
    $stmt->execute([$approver_emp_no, $approver_emp_no]);

    $row = $stmt -> fetch(PDO::FETCH_ASSOC);

    if (!empty($row['hr_full_name'])) {
        $is_hr = true;
        $approver_name = $row['hr_full_name'];
    } else if (!empty($row['control_full_name'])) {
        // check valid control approver details
        $query = "SELECT 
                        full_name AS control_full_name 
                    FROM 
                        m_control_area_accounts 
                    WHERE 
                        emp_no = ?";

        $params[] = $approver_emp_no;

        if ($emp_transfer_type == 'department') {
            if (empty($checked_by)) {
                $query .= " AND position IN ('Assistant Manager', 'Section Manager') AND section = ?";
                $params[] = $section_from;
            } else if (empty($approved_by)) {
                $query .= " AND position IN ('Deputy Department Manager', 'Department Manager')";
            } else {
                echo 'authorized hr acknowledgement only';
                $conn = null;
                exit();
            }
        } else if ($emp_transfer_type == 'section') {
            if (empty($approved_by)) {
                $query .= " AND position IN ('Assistant Manager', 'Section Manager') AND section = ?";
                $params[] = $section_from;
            } else if (empty($r_noted_by)) {
                $query .= " AND position IN ('Staff', 'Supervisor') AND section != ?";
                $params[] = $section_from;
            } else if (empty($r_acknowledged_by)) {
                $query .= " AND position IN ('Assistant Manager', 'Section Manager') AND section != ?";
                $params[] = $section_from;
            } else if (empty($r_approved_by)) {
                $query .= " AND position IN ('Deputy Department Manager', 'Department Manager')";
            } else {
                echo 'authorized hr acknowledgement only';
                $conn = null;
                exit();
            }
        }

        $stmt = $conn->prepare($query);
        $stmt->execute($params);

        $row = $stmt -> fetch(PDO::FETCH_ASSOC);

        if (!empty($row['control_full_name'])) {
            $approver_name = $row['control_full_name'];
        } else {
            echo 'approver strictly not authorized';
            $conn = null;
            exit();
        }
    } else {
        echo 'approver not authorized or registered';
        $conn = null;
        exit();
    }

    // disapprove
    if ($opt < 1) {
        $mail_arr = [
            'approve_email_opt' => 0,
            'emp_transfer_batch_id' => '',
            'approve_key' => '',
            'hr_ack' => '', 
            'hr_ack_no' => '', 
            'hr_date_ack' => '', 
            'issued_by' => $issued_by, 
            'issued_by_no' => $issued_by_no 
        ];

        $check_added = add_emp_transfer_history($mail_arr, $conn);

        if ($check_added != 'success') {
            echo $check_added;
            $conn = null;
            exit();
        }

        $sendto = get_issued_by_email($mail_arr, $conn);

        if (empty($sendto)) {
            echo 'Error finding issued by email';
            $conn = null;
            exit();
        }

        $mail_arr['sendto'] = $sendto;

        send_mail($mail_arr, $conn_mailer);

        echo 'success';
        $conn = null;
        exit();
    }

    // approve
    if ($opt > 0) {
        if ($emp_transfer_type == 'department') {
            if ($is_hr) {
                if (
                    !empty($checked_by) && 
                    !empty($approved_by)
                ) {
                    // history
                    $mail_arr = [
                        'approve_email_opt' => 1,
                        'emp_transfer_batch_id' => $emp_transfer_batch_id,
                        'approve_key' => $approve_key,
                        'hr_ack' => $approver_name, 
                        'hr_ack_no' => $approver_emp_no, 
                        'hr_date_ack' => $server_date_time, 
                        'issued_by' => $issued_by,
                        'issued_by_no' => $issued_by_no 
                    ];

                    $check_added = add_emp_transfer_history($mail_arr, $conn);

                    if ($check_added != 'success') {
                        echo $check_added;
                        $conn = null;
                        exit();
                    }

                    $sendto = get_issued_by_email($mail_arr, $conn);

                    if (empty($sendto)) {
                        echo 'Error finding issued by email';
                        $conn = null;
                        exit();
                    }

                    $mail_arr['sendto'] = $sendto;

                    send_mail($mail_arr, $conn_mailer);

                    // Receiving MP Email
                    $mail_arr['approve_email_opt'] = 3;

                    $sendto = get_receiving_mp_email($mail_arr, $conn);

                    if (!empty($sendto)) {
                        $mail_arr['sendto'] = is_array($sendto) ? implode(';', $sendto) : $sendto;
                        send_mail($mail_arr, $conn_mailer);
                    }

                    echo 'success';
                } else {
                    echo 'hr cannot bypass approval';
                    $conn = null;
                    exit();
                }
            } else {
                $new_approve_key = str_replace('.', '', uniqid('emp_mgt_key_', true));

                if (empty($checked_by)) {
                    $query = "UPDATE 
                                    t_employee_transfer 
                                SET 
                                    approve_key = ?, 
                                    checked_by = ?, 
                                    checked_by_no = ?, 
                                    date_checked_by = ? 
                                WHERE 
                                    emp_transfer_batch_id = ? AND 
                                    approve_key = ?";

                    $params = [
                        $new_approve_key, 
                        $approver_name, 
                        $approver_emp_no, 
                        $server_date_time, 
                        $emp_transfer_batch_id, 
                        $approve_key 
                    ];

                    $stmt = $conn->prepare($query);
                    $stmt->execute($params);

                    $send_to_emails = [];

                    // Get Send To Emails
                    $query = "SELECT
                                    email 
                                FROM 
                                    m_control_area_accounts 
                                WHERE 
                                    dept = ? AND 
                                    position IN ('Deputy Department Manager', 'Department Manager')";

                    $stmt = $conn->prepare($query);
                    $stmt->execute([$dept_from]);

                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $send_to_emails[] = $row['email'];
                    }
                    
                    $sendto = implode(";", $send_to_emails);

                    $mail_arr = [
                        'approve_email_opt' => 2,
                        'emp_transfer_batch_id' => $emp_transfer_batch_id,
                        'approve_key' => $new_approve_key,
                        'sendto' => $sendto
                    ];

                    send_mail($mail_arr, $conn_mailer);

                    echo 'success';
                } else if (empty($approved_by)) {
                    $query = "UPDATE 
                                    t_employee_transfer 
                                SET 
                                    approve_key = ?, 
                                    approved_by = ?, 
                                    approved_by_no = ?, 
                                    date_approved_by = ? 
                                WHERE 
                                    emp_transfer_batch_id = ? AND 
                                    approve_key = ?";

                    $params = [
                        $new_approve_key, 
                        $approver_name, 
                        $approver_emp_no, 
                        $server_date_time, 
                        $emp_transfer_batch_id, 
                        $approve_key 
                    ];

                    $stmt = $conn->prepare($query);
                    $stmt->execute($params);

                    $send_to_emails = [];

                    // Get Send To Emails
                    $query = "SELECT
                                    email 
                                FROM 
                                    m_hr_accounts 
                                WHERE 
                                    role = 'hr' AND 
                                    position IN ('Associate', 'Jr. Staff', 'Staff', 'Supervisor')";

                    $stmt = $conn->prepare($query);
                    $stmt->execute();

                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $send_to_emails[] = $row['email'];
                    }
                    
                    $sendto = implode(";", $send_to_emails);

                    $mail_arr = [
                        'approve_email_opt' => 2,
                        'emp_transfer_batch_id' => $emp_transfer_batch_id,
                        'approve_key' => $new_approve_key,
                        'sendto' => $sendto
                    ];

                    send_mail($mail_arr, $conn_mailer);

                    echo 'success';
                }
            }
        }
        
        if ($emp_transfer_type == 'section') {
            if ($is_hr) {
                if (
                    !empty($approved_by) && 
                    !empty($r_noted_by) && 
                    !empty($r_acknowledged_by) && 
                    !empty($r_approved_by)
                ) {
                    // history
                    $mail_arr = [
                        'approve_email_opt' => 1,
                        'emp_transfer_batch_id' => $emp_transfer_batch_id,
                        'approve_key' => $approve_key,
                        'hr_ack' => $approver_name, 
                        'hr_ack_no' => $approver_emp_no, 
                        'hr_date_ack' => $server_date_time, 
                        'issued_by' => $issued_by, 
                        'issued_by_no' => $issued_by_no 
                    ];

                    $check_added = add_emp_transfer_history($mail_arr, $conn);

                    if ($check_added != 'success') {
                        echo $check_added;
                        $conn = null;
                        exit();
                    }

                    $sendto = get_issued_by_email($mail_arr, $conn);

                    if (empty($sendto)) {
                        echo 'Error finding issued by email';
                        $conn = null;
                        exit();
                    }

                    $mail_arr['sendto'] = $sendto;

                    send_mail($mail_arr, $conn_mailer);

                    // Receiving MP Email
                    $mail_arr['approve_email_opt'] = 3;

                    $sendto = get_receiving_mp_email($mail_arr, $conn);

                    if (!empty($sendto)) {
                        $mail_arr['sendto'] = is_array($sendto) ? implode(';', $sendto) : $sendto;
                        send_mail($mail_arr, $conn_mailer);
                    }

                    echo 'success';
                } else {
                    echo 'hr cannot bypass approval';
                    $conn = null;
                    exit();
                }
            } else {
                if (empty($approved_by)) {
                    $groupedEmpTransferRows = [];

                    foreach ($emp_transfer_rows as $row) {
                        $key = $row['dept_to'] . '|' . $row['section_to']; // Create a unique key for each combination
                        if (!isset($groupedEmpTransferRows[$key])) {
                            $groupedEmpTransferRows[$key] = []; // Initialize an array for this key if it doesn't exist
                        }
                        $groupedEmpTransferRows[$key][] = $row; // Add the row to the corresponding group
                    }

                    // Now, loop through the grouped rows to execute something for duplicates
                    foreach ($groupedEmpTransferRows as $key => $rows) {
                        if (count($rows) > 1) { // Check if there are duplicates
                            // Execute your code for rows with the same dept_to and section_to
                            $new_approve_key = str_replace('.', '', uniqid('emp_mgt_key_', true));

                            $row = $rows[0];

                            $dept_to = $row['dept_to'];
                            $section_to = $row['section_to'];

                            $query = "UPDATE 
                                            t_employee_transfer 
                                        SET 
                                            approve_key = ?, 
                                            approved_by = ?, 
                                            approved_by_no = ?, 
                                            date_approved_by = ? 
                                        WHERE 
                                            emp_transfer_batch_id = ? AND 
                                            approve_key = ? AND 
                                            dept_to = ? AND 
                                            section_to = ?";

                            $params = [
                                $new_approve_key, 
                                $approver_name, 
                                $approver_emp_no, 
                                $server_date_time, 
                                $emp_transfer_batch_id, 
                                $approve_key, 
                                $dept_to, 
                                $section_to 
                            ];

                            $stmt = $conn->prepare($query);
                            $stmt->execute($params);

                            $send_to_emails = [];

                            // Get Send To Emails
                            $query = "SELECT
                                            email 
                                        FROM 
                                            m_control_area_accounts 
                                        WHERE 
                                            dept = ? AND 
                                            section = ? AND 
                                            position IN ('Staff', 'Supervisor')";

                            $stmt = $conn->prepare($query);
                            $stmt->execute([$dept_to, $section_to]);

                            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $send_to_emails[] = $row['email'];
                            }
                            
                            $sendto = implode(";", $send_to_emails);

                            $mail_arr = [
                                'approve_email_opt' => 2,
                                'emp_transfer_batch_id' => $emp_transfer_batch_id,
                                'approve_key' => $new_approve_key,
                                'sendto' => $sendto
                            ];

                            send_mail($mail_arr, $conn_mailer);
                        } else {
                            // Execute your code for rows with no duplicates
                            $new_approve_key = str_replace('.', '', uniqid('emp_mgt_key_', true));

                            $row = $rows[0]; // Since there's only one row, we can access it directly

                            $dept_to = $row['dept_to'];
                            $section_to = $row['section_to'];

                            $query = "UPDATE 
                                            t_employee_transfer 
                                        SET 
                                            approve_key = ?, 
                                            approved_by = ?, 
                                            approved_by_no = ?, 
                                            date_approved_by = ? 
                                        WHERE 
                                            emp_transfer_batch_id = ? AND 
                                            approve_key = ?";

                            $params = [
                                $new_approve_key, 
                                $approver_name, 
                                $approver_emp_no, 
                                $server_date_time, 
                                $emp_transfer_batch_id, 
                                $approve_key, 
                                $dept_to, 
                                $section_to 
                            ];

                            $stmt = $conn->prepare($query);
                            $stmt->execute($params);

                            $send_to_emails = [];

                            // Get Send To Emails
                            $query = "SELECT
                                            email 
                                        FROM 
                                            m_control_area_accounts 
                                        WHERE 
                                            dept = ? AND 
                                            section = ? AND 
                                            position IN ('Staff', 'Supervisor')";

                            $stmt = $conn->prepare($query);
                            $stmt->execute([$dept_to, $section_to]);

                            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $send_to_emails[] = $row['email'];
                            }
                            
                            $sendto = implode(";", $send_to_emails);

                            $mail_arr = [
                                'approve_email_opt' => 2,
                                'emp_transfer_batch_id' => $emp_transfer_batch_id,
                                'approve_key' => $new_approve_key,
                                'sendto' => $sendto
                            ];

                            send_mail($mail_arr, $conn_mailer);
                        }
                    }

                    echo 'success';
                    $conn = null;
                    exit();
                }
                
                $new_approve_key = str_replace('.', '', uniqid('emp_mgt_key_', true));

                if (empty($r_noted_by)) {
                    $query = "UPDATE 
                                    t_employee_transfer 
                                SET 
                                    approve_key = ?, 
                                    r_noted_by = ?, 
                                    r_noted_by_no = ?, 
                                    r_date_noted_by = ? 
                                WHERE 
                                    emp_transfer_batch_id = ? AND 
                                    approve_key = ?";

                    $params = [
                        $new_approve_key, 
                        $approver_name, 
                        $approver_emp_no, 
                        $server_date_time, 
                        $emp_transfer_batch_id, 
                        $approve_key 
                    ];

                    $stmt = $conn->prepare($query);
                    $stmt->execute($params);

                    $send_to_emails = [];

                    // Get Send To Emails
                    $query = "SELECT
                                    email 
                                FROM 
                                    m_control_area_accounts 
                                WHERE 
                                    dept = ? AND 
                                    section = ? AND 
                                    position IN ('Assistant Manager', 'Section Manager')";

                    $stmt = $conn->prepare($query);
                    $stmt->execute([$dept_to, $section_to]);

                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $send_to_emails[] = $row['email'];
                    }
                    
                    $sendto = implode(";", $send_to_emails);

                    $mail_arr = [
                        'approve_email_opt' => 2,
                        'emp_transfer_batch_id' => $emp_transfer_batch_id,
                        'approve_key' => $new_approve_key,
                        'sendto' => $sendto
                    ];

                    send_mail($mail_arr, $conn_mailer);

                    echo 'success';
                } else if (empty($r_acknowledged_by)) {
                    $query = "UPDATE 
                                    t_employee_transfer 
                                SET 
                                    approve_key = ?, 
                                    r_acknowledged_by = ?, 
                                    r_acknowledged_by_no = ?, 
                                    r_date_acknowledged_by = ? 
                                WHERE 
                                    emp_transfer_batch_id = ? AND 
                                    approve_key = ?";

                    $params = [
                        $new_approve_key, 
                        $approver_name, 
                        $approver_emp_no, 
                        $server_date_time, 
                        $emp_transfer_batch_id, 
                        $approve_key 
                    ];

                    $stmt = $conn->prepare($query);
                    $stmt->execute($params);

                    $send_to_emails = [];

                    // Get Send To Emails
                    $query = "SELECT
                                    email 
                                FROM 
                                    m_control_area_accounts 
                                WHERE 
                                    dept = ? AND 
                                    position IN ('Deputy Department Manager', 'Department Manager')";

                    $stmt = $conn->prepare($query);
                    $stmt->execute([$dept_to]);

                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $send_to_emails[] = $row['email'];
                    }
                    
                    $sendto = implode(";", $send_to_emails);

                    $mail_arr = [
                        'approve_email_opt' => 2,
                        'emp_transfer_batch_id' => $emp_transfer_batch_id,
                        'approve_key' => $new_approve_key,
                        'sendto' => $sendto
                    ];

                    send_mail($mail_arr, $conn_mailer);

                    echo 'success';
                } else if (empty($r_approved_by)) {
                    $query = "UPDATE 
                                    t_employee_transfer 
                                SET 
                                    approve_key = ?, 
                                    r_approved_by = ?, 
                                    r_approved_by_no = ?, 
                                    r_date_approved_by = ? 
                                WHERE 
                                    emp_transfer_batch_id = ? AND 
                                    approve_key = ?";

                    $params = [
                        $new_approve_key, 
                        $approver_name, 
                        $approver_emp_no, 
                        $server_date_time, 
                        $emp_transfer_batch_id, 
                        $approve_key 
                    ];

                    $stmt = $conn->prepare($query);
                    $stmt->execute($params);

                    $send_to_emails = [];

                    // Get Send To Emails
                    $query = "SELECT
                                    email 
                                FROM 
                                    m_hr_accounts 
                                WHERE 
                                    role = 'hr' AND 
                                    position IN ('Associate', 'Jr. Staff', 'Staff', 'Supervisor')";

                    $stmt = $conn->prepare($query);
                    $stmt->execute();

                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $send_to_emails[] = $row['email'];
                    }
                    
                    $sendto = implode(";", $send_to_emails);

                    $mail_arr = [
                        'approve_email_opt' => 2,
                        'emp_transfer_batch_id' => $emp_transfer_batch_id,
                        'approve_key' => $new_approve_key,
                        'sendto' => $sendto
                    ];

                    send_mail($mail_arr, $conn_mailer);

                    echo 'success';
                }
            }
        }
    }
}

if ($method == 'get_employee_transfer_history') {
	$dept_from = '';
    $section_from = '';

    if (isset($_SESSION['emp_no_control_area'])) {
        $dept_from = $_SESSION['dept'];
	    $section_from = $_SESSION['section'];
    } else if (isset($_SESSION['emp_no_hr'])) {
        $dept_from = $_POST['dept_from'];
        $section_from = $_POST['section_from'];
    } else {
        echo 'Session Expired. Please re-login your account.';
		$conn = null;
		exit();
    }

    $date_issued_by_from = $_POST['date_issued_by_from'];
    $date_issued_by_to = $_POST['date_issued_by_to'];

    $emp_no = $_POST['emp_no'];
	$full_name = $_POST['full_name'];
    $provider = $_POST['provider'];
    $position = $_POST['position'];
    $emp_transfer_type = $_POST['emp_transfer_type'];
	$line_no_from = $_POST['line_no_from'];
    $dept_to = $_POST['dept_to'];
    $section_to = $_POST['section_to'];
    $line_no_to = $_POST['line_no_to'];
    $is_checked_by = intval($_POST['is_checked_by']);
    $is_approved_by = intval($_POST['is_approved_by']);
    $is_receiving_noted_by = intval($_POST['is_receiving_noted_by']);
    $is_receiving_acknowledged_by = intval($_POST['is_receiving_acknowledged_by']);
    $is_receiving_approved_by = intval($_POST['is_receiving_approved_by']);

	$c = 0;

	$query = "SELECT 
					eth.id, eth.emp_no, eth.emp_transfer_type, 
                    eth.dept_from, eth.section_from, eth.line_no_from, 
                    eth.dept_to, eth.section_to, eth.line_no_to, 
                    eth.issued_by, eth.checked_by, eth.approved_by, 
                    eth.r_noted_by, eth.r_acknowledged_by, eth.r_approved_by, 
                    eth.hr_ack, eth.hr_ack_no, eth.hr_date_ack, 
                    CASE 
                        WHEN eth.is_approved = 0 THEN 'Disapproved' 
                        ELSE 'Approved' 
                    END AS is_approved, 
                    eth.reason, eth.date_effectivity, 
                    emp.full_name, emp.provider, emp.position 
				FROM t_employee_transfer_history eth 
                LEFT JOIN m_employees emp ON eth.emp_no = emp.emp_no 
				WHERE eth.dept_from != ''";

	$params = [];

    if (!empty($date_issued_by_from) && !empty($date_issued_by_to)) {
        $date_issued_by_from = date('Y-m-d H:i:s',(strtotime($date_issued_by_from)));
        $date_issued_by_to = date('Y-m-d H:i:s',(strtotime($date_issued_by_to)));
        $query = $query . " AND (eth.date_issued_by >= ? AND eth.date_issued_by <= ?)";
        $params[] = $date_issued_by_from;
        $params[] = $date_issued_by_to;
    }

    if (!empty($dept_from)) {
		$query = $query . " AND eth.dept_from = ?";
		$params[] = $dept_from;
	}

    if (!empty($section_from)) {
		$query = $query . " AND eth.section_from = ?";
		$params[] = $section_from;
	}

    if (!empty($line_no_from)) {
		$query = $query . " AND eth.line_no_from = ?";
		$params[] = $line_no_from;
	}

	if (!empty($emp_no)) {
		$query = $query . " AND eth.emp_no LIKE ?";
		$emp_no_search = $emp_no . "%";
		$params[] = $emp_no_search;
	}

    if (!empty($full_name)) {
		$query = $query . " AND emp.full_name LIKE ?";
        $full_name_search = $full_name . "%";
		$params[] = $full_name_search;
	}

    if (!empty($provider)) {
		$query = $query . " AND emp.provider = ?";
		$params[] = $provider;
	}

    if (!empty($position)) {
		$query = $query . " AND emp.position = ?";
		$params[] = $position;
	}

    if (!empty($emp_transfer_type)) {
		$query = $query . " AND eth.emp_transfer_type = ?";
		$params[] = $emp_transfer_type;
	}

    if (!empty($emp_transfer_type)) {
		$query = $query . " AND eth.emp_transfer_type = ?";
		$params[] = $emp_transfer_type;
	}

    if (!empty($dept_to)) {
		$query = $query . " AND eth.dept_to = ?";
		$params[] = $dept_to;
	}

    if (!empty($section_to)) {
		$query = $query . " AND eth.section_to = ?";
		$params[] = $section_to;
	}

    if (!empty($line_no_to)) {
		$query = $query . " AND eth.line_no_to = ?";
		$params[] = $line_no_to;
	}

    if ($is_checked_by > 0) {
		$query = $query . " AND eth.checked_by IS NOT NULL";
	}
    if ($is_approved_by > 0) {
		$query = $query . " AND eth.approved_by IS NOT NULL";
	}
    if ($is_receiving_noted_by > 0) {
		$query = $query . " AND eth.r_noted_by IS NOT NULL";
	}
    if ($is_receiving_acknowledged_by > 0) {
		$query = $query . " AND eth.r_acknowledged_by IS NOT NULL";
	}
    if ($is_receiving_approved_by > 0) {
		$query = $query . " AND eth.r_approved_by IS NOT NULL";
	}

	$stmt = $conn->prepare($query);
	$stmt->execute($params);

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $c++;

        $row_class = '';

        if ($row['is_approved'] == 'Approved') {
            $row_class = 'bg-success';
        } else if ($row['is_approved'] == 'Disapproved') {
            $row_class = 'bg-danger';
        }

        echo '<tr class="'.$row_class.'">';

        echo '<td>'.$c.'</td>';
        echo '<td>'.$row['date_effectivity'].'</td>';
        echo '<td>'.$row['emp_no'].'</td>';
        echo '<td>'.$row['full_name'].'</td>';
        echo '<td>'.$row['provider'].'</td>';
        echo '<td>'.$row['position'].'</td>';
        echo '<td>'.$row['emp_transfer_type'].'</td>';
        echo '<td>'.$row['dept_from'].'</td>';
        echo '<td>'.$row['section_from'].'</td>';
        echo '<td>'.$row['line_no_from'].'</td>';
        echo '<td>'.$row['dept_to'].'</td>';
        echo '<td>'.$row['section_to'].'</td>';
        echo '<td>'.$row['line_no_to'].'</td>';
        echo '<td>'.$row['issued_by'].'</td>';
        echo '<td>'.$row['checked_by'].'</td>';
        echo '<td>'.$row['approved_by'].'</td>';
        echo '<td>'.$row['r_noted_by'].'</td>';
        echo '<td>'.$row['r_acknowledged_by'].'</td>';
        echo '<td>'.$row['r_approved_by'].'</td>';
        echo '<td>'.$row['hr_ack'].'</td>';
        echo '<td>'.$row['is_approved'].'</td>';
        echo '<td>'.$row['reason'].'</td>';

        echo '</tr>';
    }
}

$conn = null;
