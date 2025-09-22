<?php 
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

include '../../conn.php';
include '../../conn_mailer.php';
include '../../lib/approve_email.php';

$method = $_POST['method'];

if ($method == 'get_ongoing_employee_transfer') {
	if (!isset($_SESSION['dept'])) {
		echo 'Session Expired. Please re-login your account.';
		$conn = null;
		exit();
	}

    $emp_no = $_POST['emp_no'];
	$full_name = $_POST['full_name'];
    $provider = $_POST['provider'];
    $position = $_POST['position'];
    $emp_transfer_type = $_POST['emp_transfer_type'];
	$dept_from = $_SESSION['dept'];
	$section_from = $_SESSION['section'];
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
					et.id, et.emp_no, et.emp_transfer_type, et.dept_to, et.section_to, et.line_no_to, 
                    et.issued_by, et.checked_by, et.approved_by, 
                    et.r_noted_by, et.r_acknowledged_by, et.r_approved_by, 
                    et.reason, et.date_effectivity, 
                    emp.full_name, emp.provider, emp.position, 
                    CASE 
                        WHEN et.date_effectivity > GETDATE() THEN 'overdue' 
                        ELSE 'ongoing' 
                    END AS date_effectivity_status 
				FROM t_employee_transfer et 
                LEFT JOIN m_employees emp ON et.emp_no = emp.emp_no 
				WHERE et.dept_from = ? AND et.section_from = ?";

	$params = [
		$dept_from, 
		$section_from 
	];

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

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($row) {
		do {
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
		} while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
	} else {
		echo '<tr>';
			echo '<td colspan="20" style="text-align:center; color:red;">No Result !!!</td>';
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
    $appprove_key = str_replace('.', '', uniqid('emp_mgt_key_', true));

    $dept_to = '';
    $section_to = '';
    $line_no_to = '';

    $query = "SELECT dept, section, line_no FROM m_employees WHERE emp_no = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$emp_no]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $dept_from = $row['dept'];
        $section_from = $row['section'];
        $line_no_from = $row['line_no'];
    }

    $params = [
        $emp_transfer_id,
        $emp_transfer_batch_id,
        $appprove_key,
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
    $email_body = approve_email($emp_transfer_batch_id, $appprove_key);

    $data = [
        "system_name" => $email_code,
        "send_to" => $sendto,
        "cc" => "vince.dale.alcantara@furukawaelectric.com",
        "subject" => $email_subject . " : " . "Employee Transfer Approval",
        "body" => $email_body
    ];
    $stmt = $conn_mailer -> prepare("EXEC mail_send_mail_basic
        :system_name,
        :send_to,
        :cc,
        :subject,
        :body
    ");
    $stmt -> execute($data);

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

$conn = null;
