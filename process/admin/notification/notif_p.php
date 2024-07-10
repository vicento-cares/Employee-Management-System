<?php 
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

include '../../conn.php';

if (!isset($_POST['method'])) {
	echo 'method not set';
	exit;
}
$method = $_POST['method'];

if ($method == 'count_notif_line_support') {
	$pending_ls = 0;
	$accepted_ls = 0;
	$rejected_ls = 0;
	$total = 0;
	$sql = "SELECT nls.pending_ls, nls.accepted_ls, nls.rejected_ls FROM t_notif_line_support nls 
			LEFT JOIN m_accounts acc
			ON acc.emp_no = nls.emp_no
			WHERE ";
	if (!empty($_SESSION['line_no'])) {
		$line_no = $_SESSION['line_no'];
		$sql = $sql . " acc.line_no = '$line_no'";
	} else {
		$sql = $sql . " (acc.line_no IS NULL OR acc.line_no = '')";
	}
	$sql = $sql . " AND (nls.pending_ls > 0 OR nls.accepted_ls > 0 OR nls.rejected_ls > 0)";
	$stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt -> execute();
	if ($stmt -> rowCount() > 0) {
		while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
			$pending_ls = intval($row['pending_ls']);
			$accepted_ls = intval($row['accepted_ls']);
			$rejected_ls = intval($row['rejected_ls']);
		}
	}
	$total = $pending_ls + $accepted_ls + $rejected_ls;

	$response_arr = array(
		'pending_ls' => $pending_ls,
        'accepted_ls' => $accepted_ls,
        'rejected_ls' => $rejected_ls,
        'total' => $total
    );

    echo json_encode($response_arr, JSON_FORCE_OBJECT);
}

/*if ($method == 'update_notif_pending') {
	$sql = "UPDATE t_notif_line_support nls
			LEFT JOIN m_accounts acc
			ON acc.emp_no = nls.emp_no 
			SET nls.pending_ls = 0 WHERE acc.line_no = '".$_SESSION['line_no']."'";
	$stmt = $conn -> prepare($sql);
	$stmt -> execute();
}

if ($method == 'update_notif_accepted_rejected') {
	$sql = "UPDATE t_notif_line_support nls
			LEFT JOIN m_accounts acc
			ON acc.emp_no = nls.emp_no 
			SET nls.accepted_ls = 0, nls.rejected_ls = 0 WHERE acc.line_no = '".$_SESSION['line_no']."'";
	$stmt = $conn -> prepare($sql);
	$stmt -> execute();
}*/

if ($method == 'update_notif_line_support') {
	// MySQL
	// $sql = "UPDATE t_notif_line_support nls
	// 		LEFT JOIN m_accounts acc
	// 		ON acc.emp_no = nls.emp_no 
	// 		SET nls.pending_ls = 0, nls.accepted_ls = 0, nls.rejected_ls = 0 WHERE ";
	// MS SQL Server
	$sql = "UPDATE nls
			SET nls.pending_ls = 0, nls.accepted_ls = 0, nls.rejected_ls = 0
			FROM t_notif_line_support AS nls
			LEFT JOIN m_accounts AS acc
			ON acc.emp_no = nls.emp_no 
			WHERE ";
	if (!empty($_SESSION['line_no'])) {
		$line_no = $_SESSION['line_no'];
		$sql = $sql . " acc.line_no = '$line_no'";
	} else {
		$sql = $sql . " acc.line_no IS NULL OR acc.line_no = ''";
	}
	$stmt = $conn -> prepare($sql);
	$stmt -> execute();
}

$conn = null;
?>