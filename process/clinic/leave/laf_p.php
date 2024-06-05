<?php 
session_name("emp_mgt");
session_start();

include '../../conn.php';

$method = $_POST['method'];

$date_updated = date('Y-m-d H:i:s');

// Leave Application Form

if ($method == 'save_leave_form_clinic') {
	$leave_form_id = $_POST['leave_form_id'];
	$sl_r1_1_hrs = $_POST['sl_r1_1_hrs'];
	$sl_r1_1_date = $_POST['sl_r1_1_date'];
	$sl_r1_1_time_in = $_POST['sl_r1_1_time_in'];
	$sl_r1_1_time_out = $_POST['sl_r1_1_time_out'];
	$sl_r1_2_days = $_POST['sl_r1_2_days'];
	$sl_r1_3_date = $_POST['sl_r1_3_date'];
	$sl_rc_1_days = $_POST['sl_rc_1_days'];
	$sl_rc_2_from = $_POST['sl_rc_2_from'];
	$sl_rc_2_to = $_POST['sl_rc_2_to'];
	$sl_rc_3_oc = $_POST['sl_rc_3_oc'];
	$sl_rc_4_hm = $_POST['sl_rc_4_hm'];
	$sl_rc_mgh = $_POST['sl_rc_mgh'];
	$sl_r2 = $_POST['sl_r2'];
	$sl_dr_date = $_POST['sl_dr_date'];

	$sql = "UPDATE t_leave_form SET leave_form_status='pending',sl_r1_1_hrs='$sl_r1_1_hrs',sl_r1_1_date='$sl_r1_1_date',sl_r1_1_time_in='$sl_r1_1_time_in',sl_r1_1_time_out='$sl_r1_1_time_out',sl_r1_2_days='$sl_r1_2_days',sl_r1_3_date='$sl_r1_3_date',sl_rc_1_days='$sl_rc_1_days',sl_rc_2_from='$sl_rc_2_from',sl_rc_2_to='$sl_rc_2_to',sl_rc_3_oc='$sl_rc_3_oc',sl_rc_4_hm='$sl_rc_4_hm',sl_rc_mgh='$sl_rc_mgh',sl_r2='$sl_r2',sl_dr_name='".$_SESSION['full_name']."',sl_dr_date='$sl_dr_date',date_updated='$date_updated' WHERE leave_form_id = '$leave_form_id'";

	$sql = "UPDATE t_leave_form SET leave_form_status='pending',sl_r1_1_hrs='$sl_r1_1_hrs',";

	if (empty($sl_r1_1_date)) {
		$sql = $sql . "sl_r1_1_date=NULL,";
	} else {
		$sql = $sql . "sl_r1_1_date='$sl_r1_1_date',";
	}
	if (empty($sl_r1_1_time_in)) {
		$sql = $sql . "sl_r1_1_time_in=NULL,";
	} else {
		$sql = $sql . "sl_r1_1_time_in='$sl_r1_1_time_in',";
	}
	if (empty($sl_r1_1_time_out)) {
		$sql = $sql . "sl_r1_1_time_out=NULL,";
	} else {
		$sql = $sql . "sl_r1_1_time_out='$sl_r1_1_time_out',";
	}

	$sql = $sql . "sl_r1_2_days='$sl_r1_2_days',";

	if (empty($sl_r1_3_date)) {
		$sql = $sql . "sl_r1_3_date=NULL,";
	} else {
		$sql = $sql . "sl_r1_3_date='$sl_r1_3_date',";
	}

	$sql = $sql . "sl_rc_1_days='$sl_rc_1_days',";

	if (empty($sl_rc_2_from)) {
		$sql = $sql . "sl_rc_2_from=NULL,";
	} else {
		$sql = $sql . "sl_rc_2_from='$sl_rc_2_from',";
	}
	if (empty($sl_rc_2_to)) {
		$sql = $sql . "sl_rc_2_to=NULL,";
	} else {
		$sql = $sql . "sl_rc_2_to='$sl_rc_2_to',";
	}

	$sql = $sql . "sl_rc_3_oc='$sl_rc_3_oc',sl_rc_4_hm='$sl_rc_4_hm',sl_rc_mgh='$sl_rc_mgh',sl_r2='$sl_r2',sl_dr_name='".$_SESSION['full_name']."',sl_dr_date='$sl_dr_date',date_updated='$date_updated' WHERE leave_form_id = '$leave_form_id'";

	$stmt = $conn->prepare($sql);
	$stmt->execute();

	echo 'success';
}

if ($method == 'get_pending_leave_forms') {
	$row_class_arr = array('modal-trigger', 'modal-trigger bg-orange', 'modal-trigger bg-success');
	$row_class = $row_class_arr[0];
	$c = 0;

	$sql = "SELECT leave_form_id, emp_no, date_filed, address, contact_no, leave_type, leave_date_from, leave_date_to, total_leave_days, irt_phone_call, irt_letter, irb, reason, issued_by, js_s, sv, approver, leave_form_status, sl_r1_1_hrs, sl_r1_1_date, sl_r1_1_time_in, sl_r1_1_time_out, sl_r1_2_days, sl_r1_3_date, sl_rc_1_days, sl_rc_2_from, sl_rc_2_to, sl_rc_3_oc, sl_rc_4_hm, sl_rc_mgh, sl_r2, sl_dr_name, sl_dr_date FROM t_leave_form WHERE leave_form_status = 'clinic' OR (leave_form_status = 'pending' AND sl_dr_name != '') ORDER BY id DESC";
	$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $row){
			$c++;

			if ($row['leave_form_status'] == 'clinic') {
				$row_class = $row_class_arr[1];
			} else if ($row['leave_form_status'] == 'pending') {
				$row_class = $row_class_arr[2];
			} else {
				$row_class = $row_class_arr[0];
			}

			echo '<tr style="cursor:pointer;" class="'.$row_class.'" data-toggle="modal" data-target="#leave_form_clinic" onclick="get_pending_leave_forms_details(&quot;'.$row['leave_form_id'].'~!~'.$row['emp_no'].'~!~'.$row['date_filed'].'~!~'.$row['address'].'~!~'.$row['contact_no'].'~!~'.$row['leave_type'].'~!~'.$row['leave_date_from'].'~!~'.$row['leave_date_to'].'~!~'.$row['total_leave_days'].'~!~'.$row['irt_phone_call'].'~!~'.$row['irt_letter'].'~!~'.$row['irb'].'~!~'.$row['reason'].'~!~'.$row['issued_by'].'~!~'.$row['js_s'].'~!~'.$row['sv'].'~!~'.$row['approver'].'~!~'.$row['sl_r1_1_hrs'].'~!~'.$row['sl_r1_1_date'].'~!~'.$row['sl_r1_1_time_in'].'~!~'.$row['sl_r1_1_time_out'].'~!~'.$row['sl_r1_2_days'].'~!~'.$row['sl_r1_3_date'].'~!~'.$row['sl_rc_1_days'].'~!~'.$row['sl_rc_2_from'].'~!~'.$row['sl_rc_2_to'].'~!~'.$row['sl_rc_3_oc'].'~!~'.$row['sl_rc_4_hm'].'~!~'.$row['sl_rc_mgh'].'~!~'.$row['sl_r2'].'~!~'.$row['sl_dr_name'].'~!~'.$row['sl_dr_date'].'&quot;)">';

            echo '<td>'.$c.'</td>';
			echo '<td>'.$row['date_filed'].'</td>';
			echo '<td>'.$row['leave_form_id'].'</td>';
			echo '<td>'.$row['leave_type'].'</td>';
			echo '<td>'.$row['leave_date_from'].'</td>';
			echo '<td>'.$row['leave_date_to'].'</td>';
			
			echo '</tr>';
		}
	}
}

if ($method == 'get_recent_leave_forms_history') {
	$row_class_arr = array('modal-trigger', 'modal-trigger bg-success', 'modal-trigger bg-danger');
	$row_class = $row_class_arr[0];
	$c = 0;

	$sql = "SELECT leave_form_id, emp_no, date_filed, address, contact_no, leave_type, leave_date_from, leave_date_to, total_leave_days, irt_phone_call, irt_letter, irb, reason, issued_by, js_s, sv, approver, leave_form_status, sl_r1_1_hrs, sl_r1_1_date, sl_r1_1_time_in, sl_r1_1_time_out, sl_r1_2_days, sl_r1_3_date, sl_rc_1_days, sl_rc_2_from, sl_rc_2_to, sl_rc_3_oc, sl_rc_4_hm, sl_rc_mgh, sl_r2, sl_dr_name, sl_dr_date FROM t_leave_form_history WHERE (leave_form_status = 'approved' OR leave_form_status = 'disapproved') AND sl_dr_name != '' ORDER BY id DESC LIMIT 25";
	$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $row){
			$c++;

			if ($row['leave_form_status'] == 'approved') {
				$row_class = $row_class_arr[1];
			} else if ($row['leave_form_status'] == 'disapproved') {
				$row_class = $row_class_arr[2];
			} else {
				$row_class = $row_class_arr[0];
			}

			echo '<tr style="cursor:pointer;" class="'.$row_class.'" data-toggle="modal" data-target="#leave_form_history" onclick="get_leave_forms_history_details(&quot;'.$row['leave_form_id'].'~!~'.$row['emp_no'].'~!~'.$row['date_filed'].'~!~'.$row['address'].'~!~'.$row['contact_no'].'~!~'.$row['leave_type'].'~!~'.$row['leave_date_from'].'~!~'.$row['leave_date_to'].'~!~'.$row['total_leave_days'].'~!~'.$row['irt_phone_call'].'~!~'.$row['irt_letter'].'~!~'.$row['irb'].'~!~'.$row['reason'].'~!~'.$row['issued_by'].'~!~'.$row['js_s'].'~!~'.$row['sv'].'~!~'.$row['approver'].'~!~'.$row['sl_r1_1_hrs'].'~!~'.$row['sl_r1_1_date'].'~!~'.$row['sl_r1_1_time_in'].'~!~'.$row['sl_r1_1_time_out'].'~!~'.$row['sl_r1_2_days'].'~!~'.$row['sl_r1_3_date'].'~!~'.$row['sl_rc_1_days'].'~!~'.$row['sl_rc_2_from'].'~!~'.$row['sl_rc_2_to'].'~!~'.$row['sl_rc_3_oc'].'~!~'.$row['sl_rc_4_hm'].'~!~'.$row['sl_rc_mgh'].'~!~'.$row['sl_r2'].'~!~'.$row['sl_dr_name'].'~!~'.$row['sl_dr_date'].'~!~'.$row['leave_form_status'].'&quot;)">';

            echo '<td>'.$c.'</td>';
			echo '<td>'.$row['date_filed'].'</td>';
			echo '<td>'.$row['leave_form_id'].'</td>';
			echo '<td>'.$row['leave_type'].'</td>';
			echo '<td>'.$row['leave_date_from'].'</td>';
			echo '<td>'.$row['leave_date_to'].'</td>';
			
			echo '</tr>';
		}
	}
}

if ($method == 'get_leave_forms_history') {
	$date_filed_from = $_POST['date_filed_from'];
	if (!empty($date_filed_from)) {
		$date_filed_from = date_create($date_filed_from);
		$date_filed_from = date_format($date_filed_from,"Y-m-d");
	}
	$date_filed_to = $_POST['date_filed_to'];
	if (!empty($date_filed_to)) {
		$date_filed_to = date_create($date_filed_to);
		$date_filed_to = date_format($date_filed_to,"Y-m-d");
	}
	$leave_type = trim($_POST['leave_type']);
	$leave_form_status = trim($_POST['leave_form_status']);

	$row_class_arr = array('modal-trigger', 'modal-trigger bg-success', 'modal-trigger bg-danger');
	$row_class = $row_class_arr[0];
	$c = 0;

	$sql = "SELECT leave_form_id, emp_no, date_filed, address, contact_no, leave_type, leave_date_from, leave_date_to, total_leave_days, irt_phone_call, irt_letter, irb, reason, issued_by, js_s, sv, approver, leave_form_status, sl_r1_1_hrs, sl_r1_1_date, sl_r1_1_time_in, sl_r1_1_time_out, sl_r1_2_days, sl_r1_3_date, sl_rc_1_days, sl_rc_2_from, sl_rc_2_to, sl_rc_3_oc, sl_rc_4_hm, sl_rc_mgh, sl_r2, sl_dr_name, sl_dr_date FROM t_leave_form_history";

	if (!empty($leave_type)) {
		$sql = $sql . " WHERE leave_type = '$leave_type'";
		if (empty($leave_form_status)) {
			$sql = $sql . " AND (leave_form_status = 'approved' OR leave_form_status = 'disapproved')";
		} else if ($leave_form_status == 'approved') {
			$sql = $sql . " AND leave_form_status = 'approved'";
		} else if ($leave_form_status == 'disapproved') {
			$sql = $sql . " AND leave_form_status = 'disapproved'";
		}
		$sql = $sql . " AND sl_dr_name != ''";
	} else if (empty($leave_form_status)) {
		$sql = $sql . " WHERE leave_form_status = 'approved' OR leave_form_status = 'disapproved'";
		$sql = $sql . " AND sl_dr_name != ''";
	} else if ($leave_form_status == 'approved') {
		$sql = $sql . " WHERE leave_form_status = 'approved'";
		$sql = $sql . " AND sl_dr_name != ''";
	} else if ($leave_form_status == 'disapproved') {
		$sql = $sql . " WHERE leave_form_status = 'disapproved'";
		$sql = $sql . " AND sl_dr_name != ''";
	} else {
		$sql = $sql . "WHERE sl_dr_name != ''";
	}

	$sql = $sql . " ORDER BY id DESC";

	$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $row){
			$c++;

			if ($row['leave_form_status'] == 'approved') {
				$row_class = $row_class_arr[1];
			} else if ($row['leave_form_status'] == 'disapproved') {
				$row_class = $row_class_arr[2];
			} else {
				$row_class = $row_class_arr[0];
			}

			echo '<tr style="cursor:pointer;" class="'.$row_class.'" data-toggle="modal" data-target="#leave_form_history" onclick="get_leave_forms_history_details(&quot;'.$row['leave_form_id'].'~!~'.$row['emp_no'].'~!~'.$row['date_filed'].'~!~'.$row['address'].'~!~'.$row['contact_no'].'~!~'.$row['leave_type'].'~!~'.$row['leave_date_from'].'~!~'.$row['leave_date_to'].'~!~'.$row['total_leave_days'].'~!~'.$row['irt_phone_call'].'~!~'.$row['irt_letter'].'~!~'.$row['irb'].'~!~'.$row['reason'].'~!~'.$row['issued_by'].'~!~'.$row['js_s'].'~!~'.$row['sv'].'~!~'.$row['approver'].'~!~'.$row['sl_r1_1_hrs'].'~!~'.$row['sl_r1_1_date'].'~!~'.$row['sl_r1_1_time_in'].'~!~'.$row['sl_r1_1_time_out'].'~!~'.$row['sl_r1_2_days'].'~!~'.$row['sl_r1_3_date'].'~!~'.$row['sl_rc_1_days'].'~!~'.$row['sl_rc_2_from'].'~!~'.$row['sl_rc_2_to'].'~!~'.$row['sl_rc_3_oc'].'~!~'.$row['sl_rc_4_hm'].'~!~'.$row['sl_rc_mgh'].'~!~'.$row['sl_r2'].'~!~'.$row['sl_dr_name'].'~!~'.$row['sl_dr_date'].'~!~'.$row['leave_form_status'].'&quot;)">';

            echo '<td>'.$c.'</td>';
			echo '<td>'.$row['date_filed'].'</td>';
			echo '<td>'.$row['leave_form_id'].'</td>';
			echo '<td>'.$row['leave_type'].'</td>';
			echo '<td>'.$row['leave_date_from'].'</td>';
			echo '<td>'.$row['leave_date_to'].'</td>';
			
			echo '</tr>';
		}
	}
}

$conn = NULL;
?>