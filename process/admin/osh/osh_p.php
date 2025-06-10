<?php
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

include '../../conn.php';

$method = $_POST['method'];

function get_shift($server_time) {
	if ($server_time >= '06:00:00' && $server_time < '18:00:00') {
		return 'DS';
	} else if ($server_time >= '18:00:00' && $server_time <= '23:59:59') {
		return 'NS';
	} else if ($server_time >= '00:00:00' && $server_time < '06:00:00') {
		return 'NS';
	}
}

function get_day($server_time, $server_date_only, $server_date_only_yesterday) {
	if ($server_time >= '06:00:00' && $server_time <= '23:59:59') {
		return $server_date_only;
	} else if ($server_time >= '00:00:00' && $server_time < '06:00:00') {
		return $server_date_only_yesterday;
	}
}

if ($method == 'check_emp_no_osh') {
	$emp_no = $_POST['emp_no'];
	$line_no = $_SESSION['line_no'];
	$full_name = '';
	$time_out = '';
	$shift = get_shift($server_time);
	$day = get_day($server_time, $server_date_only, $server_date_only_yesterday);

	$section = $_SESSION['section'];

	$sql = "SELECT 
                emp.full_name, tio.time_out, COALESCE(osh.emp_no, NULL) AS emp_no_osh
			FROM m_employees emp
			LEFT JOIN t_time_in_out tio 
                ON tio.emp_no = emp.emp_no 
            LEFT JOIN osh_voting osh 
                ON osh.emp_no = emp.emp_no 
			WHERE tio.emp_no = ? 
			AND tio.day = ? 
			AND tio.shift = ?
			AND provider = 'FAS'";

	$params = [
		$emp_no,
		$day,
		$shift
	];

	if (!empty($line_no)) {
		$sql = $sql . " AND emp.line_no = ?";
		$params[] = $line_no;
	} else {
		$sql = $sql . " AND emp.section = ?";
		$params[] = $section;
	}

	$stmt = $conn -> prepare($sql);
	$stmt -> execute($params);

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
		$full_name = $row['full_name'];
		$time_out = $row['time_out'];
        $emp_no_osh_check = $row['emp_no_osh'];

		if (empty($time_out)) {
            if (empty($emp_no_osh_check)) {
                $response_arr = array(
                    'full_name' => $full_name,
                    'message' => 'success'
                );
                echo json_encode($response_arr, JSON_FORCE_OBJECT);
            } else {
                echo 'Already Voted';
            }
		} else {
			echo 'Already Time Out';
		}
	} else {
		echo 'No Time In';
	}
}

if ($method == 'load_osh_candidates') {
    $data = []; // Initialize an array to hold the results

    // $sql = "SELECT * FROM osh_candidates";
	$sql = "
		DECLARE @emp_no NVARCHAR(20);
		SET @emp_no = :emp_no;
		SELECT * 
		FROM osh_candidates 
		WHERE voting_category = 
    		CASE 
        		WHEN (SELECT dept FROM m_employees WHERE emp_no = @emp_no) IN ('PD1', 'PD2', 'PD3') 
        		THEN 'PD' 
        		WHEN (SELECT dept FROM m_employees WHERE emp_no = @emp_no) IN ('QA')
				THEN 'QA'
				ELSE 'NONE'
    		END;
	";
    $stmt = $conn -> prepare($sql);
	$stmt -> bindParam(":emp_no", $_POST['emp_no']);
	$stmt -> execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row; // Add each row to the data array
    }

    echo json_encode($data); // Encode the array as JSON and output it
}

if ($method == 'set_vote') {
	$sql = "INSERT INTO osh_voting (emp_no, cand_emp_no) VALUES (:emp_no, :cand_emp_no)";
	$stmt = $conn -> prepare($sql);
	$stmt -> bindParam(':emp_no', $_POST['voter']);
	$stmt -> bindParam(':cand_emp_no', $_POST['vote']);
	if (!$stmt -> execute()) {
		$errorInfo = $stmt -> errorInfo();
		echo json_encode(['success' => false, 'message' => $errorInfo[2]]);
		exit;
	}
	echo json_encode(['success' => true]);
}

$conn = null;
