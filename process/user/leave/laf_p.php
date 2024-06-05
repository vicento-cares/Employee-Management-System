<?php 
session_name("emp_mgt");
session_start();

include '../../conn.php';

$method = $_POST['method'];

// Leave Application Form

if ($method == 'save_leave_form') {
	$emp_no = $_POST['emp_no'];
	$date_filed = $_POST['date_filed'];
	$address = $_POST['address'];
	$contact_no = $_POST['contact_no'];
	$leave_type = $_POST['leave_type'];
	$leave_date_from = $_POST['leave_date_from'];
	$leave_date_to = $_POST['leave_date_to'];
	$total_leave_days = $_POST['total_leave_days'];
	$irt = $_POST['irt'];
	$irb = $_POST['irb'];
	$reason = $_POST['reason'];

	$clinic_leave_status_arr = array('SL','Paternity','SSS Benefits','Maternity','Sickness','Others');

	$is_valid = false;

	if (!empty($address)) {
		if (!empty($leave_type)) {
			if (!empty($leave_date_from) && !empty($leave_date_to)) {
				if (!empty($irt)) {
					if (!empty($irb)) {
						if (!empty($reason)) {
							$is_valid = true;
						} else echo 'Reason Empty';
					} else echo 'IRB Empty';
				} else echo 'IRT Not Set';
			} else echo 'Leave Date Not Set';
		} else echo 'Leave Type Not Set';
	} else echo 'Address Empty';

	if ($is_valid == true) {
		$irt_phone_call = 0;
		$irt_letter = 0;
		if ($irt == 1) {
			$irt_phone_call = 1;
		} else if ($irt == 2) {
			$irt_letter = 1;
		}

		$leave_form_status = '';
		if (in_array($leave_type, $clinic_leave_status_arr)) {
			$leave_form_status = 'clinic';
		} else {
			$leave_form_status = 'pending';
		}

		$leave_form_id = date("ymdh");
		$rand = substr(md5(microtime()),rand(0,26),5);
		$leave_form_id = 'LAF-'.$leave_form_id;
		$leave_form_id = $leave_form_id.''.$rand;

		$sql = "INSERT INTO t_leave_form(leave_form_id, emp_no, date_filed, address, contact_no, leave_type, leave_date_from, leave_date_to, total_leave_days, irt_phone_call, irt_letter, irb, reason, issued_by, leave_form_status) VALUES ('$leave_form_id','$emp_no','$date_filed','$address','$contact_no','$leave_type','$leave_date_from','$leave_date_to','$total_leave_days','$irt_phone_call','$irt_letter','$irb','$reason','".$_SESSION['full_name']."', '$leave_form_status')";
		$stmt = $conn->prepare($sql);
		$stmt->execute();

		echo 'success';
	}
}

if ($method == 'get_pending_leave_forms') {
	$row_class_arr = array('modal-trigger', 'modal-trigger bg-orange', 'modal-trigger bg-warning');
	$row_class = $row_class_arr[0];
	$c = 0;

	$sql = "SELECT leave_form_id, emp_no, date_filed, address, contact_no, leave_type, leave_date_from, leave_date_to, total_leave_days, irt_phone_call, irt_letter, irb, reason, issued_by, js_s, sv, approver, leave_form_status, sl_r1_1_hrs, sl_r1_1_date, sl_r1_1_time_in, sl_r1_1_time_out, sl_r1_2_days, sl_r1_3_date, sl_rc_1_days, sl_rc_2_from, sl_rc_2_to, sl_rc_3_oc, sl_rc_4_hm, sl_rc_mgh, sl_r2, sl_dr_name, sl_dr_date FROM t_leave_form WHERE emp_no = '".$_SESSION['emp_no_user']."' AND (leave_form_status = 'clinic' OR leave_form_status = 'pending') ORDER BY id DESC";
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

			echo '<tr style="cursor:pointer;" class="'.$row_class.'" data-toggle="modal" data-target="#leave_form_pending" onclick="get_pending_leave_forms_details(&quot;'.$row['leave_form_id'].'~!~'.$row['emp_no'].'~!~'.$row['date_filed'].'~!~'.$row['address'].'~!~'.$row['contact_no'].'~!~'.$row['leave_type'].'~!~'.$row['leave_date_from'].'~!~'.$row['leave_date_to'].'~!~'.$row['total_leave_days'].'~!~'.$row['irt_phone_call'].'~!~'.$row['irt_letter'].'~!~'.$row['irb'].'~!~'.$row['reason'].'~!~'.$row['issued_by'].'~!~'.$row['js_s'].'~!~'.$row['sv'].'~!~'.$row['approver'].'~!~'.$row['sl_r1_1_hrs'].'~!~'.$row['sl_r1_1_date'].'~!~'.$row['sl_r1_1_time_in'].'~!~'.$row['sl_r1_1_time_out'].'~!~'.$row['sl_r1_2_days'].'~!~'.$row['sl_r1_3_date'].'~!~'.$row['sl_rc_1_days'].'~!~'.$row['sl_rc_2_from'].'~!~'.$row['sl_rc_2_to'].'~!~'.$row['sl_rc_3_oc'].'~!~'.$row['sl_rc_4_hm'].'~!~'.$row['sl_rc_mgh'].'~!~'.$row['sl_r2'].'~!~'.$row['sl_dr_name'].'~!~'.$row['sl_dr_date'].'&quot;)">';

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

	$sql = "SELECT leave_form_id, emp_no, date_filed, address, contact_no, leave_type, leave_date_from, leave_date_to, total_leave_days, irt_phone_call, irt_letter, irb, reason, issued_by, js_s, sv, approver, leave_form_status, sl_r1_1_hrs, sl_r1_1_date, sl_r1_1_time_in, sl_r1_1_time_out, sl_r1_2_days, sl_r1_3_date, sl_rc_1_days, sl_rc_2_from, sl_rc_2_to, sl_rc_3_oc, sl_rc_4_hm, sl_rc_mgh, sl_r2, sl_dr_name, sl_dr_date FROM t_leave_form_history WHERE emp_no = '".$_SESSION['emp_no_user']."' AND (leave_form_status = 'approved' OR leave_form_status = 'disapproved') ORDER BY id DESC LIMIT 25";
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

	$sql = "SELECT leave_form_id, emp_no, date_filed, address, contact_no, leave_type, leave_date_from, leave_date_to, total_leave_days, irt_phone_call, irt_letter, irb, reason, issued_by, js_s, sv, approver, leave_form_status, sl_r1_1_hrs, sl_r1_1_date, sl_r1_1_time_in, sl_r1_1_time_out, sl_r1_2_days, sl_r1_3_date, sl_rc_1_days, sl_rc_2_from, sl_rc_2_to, sl_rc_3_oc, sl_rc_4_hm, sl_rc_mgh, sl_r2, sl_dr_name, sl_dr_date FROM t_leave_form_history WHERE emp_no = '".$_SESSION['emp_no_user']."'";

	if (!empty($leave_type)) {
		$sql = $sql . " AND leave_type = '$leave_type'";
	}
	
	if (empty($leave_form_status)) {
		$sql = $sql . " AND (leave_form_status = 'approved' OR leave_form_status = 'disapproved')";
	} else if ($leave_form_status == 'approved') {
		$sql = $sql . " AND leave_form_status = 'approved'";
	} else if ($leave_form_status == 'disapproved') {
		$sql = $sql . " AND leave_form_status = 'disapproved'";
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