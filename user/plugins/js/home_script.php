<script type="text/javascript">
// DOMContentLoaded function
document.addEventListener("DOMContentLoaded", () => {
	get_employee_data();
    get_pending_leave_forms();
    get_recent_leave_forms_history();
    setInterval(get_pending_leave_forms, 10000);
    setInterval(get_recent_leave_forms_history, 30000);
});

const get_employee_data = () => {
	let emp_no = '<?=$_SESSION['emp_no_user']?>';
	$.ajax({
        url:'../process/hr/employees/emp-masterlist_p.php',
        type:'POST',
        cache:false,
        data:{
            method:'get_employee_data',
            emp_no:emp_no
        },
        success:function(response){
            try {
                let response_array = JSON.parse(response);
                document.getElementById('emp_no_leave').innerHTML = response_array.emp_no;
                document.getElementById('full_name_leave').innerHTML = response_array.full_name;
                document.getElementById('date_filed_leave').innerHTML = '<?=$server_date_only?>';
                document.getElementById('dept_leave').innerHTML = response_array.dept;
                document.getElementById('position_leave').innerHTML = response_array.position;
                document.getElementById('emp_status_leave').innerHTML = response_array.emp_status;
                document.getElementById('date_hired_leave').innerHTML = response_array.date_hired;
                document.getElementById('address_leave').value = response_array.address;
                document.getElementById('contact_no_leave').innerHTML = response_array.contact_no;
            } catch(e) {
                console.log(response);
                Swal.fire({
                  icon: 'error',
                  title: 'Error !!!',
                  text: `Error: ${response}`,
                  showConfirmButton: false,
                  timer : 1000
                });
            }
        }
    });
}

$("#leave_form").on('show.bs.modal', e => {
  load_reason_leave_textarea();
});

const load_reason_leave_textarea = () => {
  setTimeout(() => {
    var max_length = document.getElementById("reason_leave").getAttribute("maxlength");
    var reason_leave_length = document.getElementById("reason_leave").value.length;
    var reason_leave_count = `${reason_leave_length} / ${max_length}`;
    document.getElementById("reason_leave_count").innerHTML = reason_leave_count;
  }, 100);
}

const count_reason_leave_char = () => {
  var max_length = document.getElementById("reason_leave").getAttribute("maxlength");
  var reason_leave_length = document.getElementById("reason_leave").value.length;
  var reason_leave_count = `${reason_leave_length} / ${max_length}`;
  document.getElementById("reason_leave_count").innerHTML = reason_leave_count;
}

const gen_total_leave_days = () => {
	let leave_date_from = document.getElementById('leave_date_from_leave').value;
	let leave_date_to = document.getElementById('leave_date_to_leave').value;
	if (leave_date_from != '' && leave_date_to != '') {
		date1 = new Date(leave_date_from);  
		date2 = new Date(leave_date_to);  

		//calculate time difference  
		var time_difference = date2.getTime() - date1.getTime();

		//calculate days difference by dividing total milliseconds in a day
		var days_difference = (time_difference / (1000 * 60 * 60 * 24)) + 1;

		document.getElementById('total_leave_days_leave').innerHTML = days_difference;
	}
}

const save_leave_form = () => {
	let emp_no = document.getElementById('emp_no_leave').innerHTML;
	let date_filed = document.getElementById('date_filed_leave').innerHTML;
	let address = document.getElementById('address_leave').value;
	let contact_no = document.getElementById('contact_no_leave').innerHTML;
	let leave_type = document.getElementById('leave_type_leave').value;
	let leave_date_from = document.getElementById('leave_date_from_leave').value;
	let leave_date_to = document.getElementById('leave_date_to_leave').value;
	let total_leave_days = document.getElementById('total_leave_days_leave').innerHTML;
	let irt_leave = document.getElementsByName('irt_leave');
	let irt = 0;
	for (i = 0; i < irt_leave.length; i++) {
        if (irt_leave[i].checked)
            irt = irt_leave[i].value;
    }
    let irb = document.getElementById('irb_leave').value;
    let reason = document.getElementById('reason_leave').value;

    $.ajax({
        url:'../process/user/leave/laf_p.php',
        type:'POST',
        cache:false,
        data:{
            method:'save_leave_form',
            emp_no:emp_no,
            date_filed:date_filed,
            address:address,
            contact_no:contact_no,
            leave_type:leave_type,
            leave_date_from:leave_date_from,
            leave_date_to:leave_date_to,
            total_leave_days:total_leave_days,
            irt:irt,
            irb:irb,
            reason:reason
        },
        beforeSend: () => {
        	Swal.fire({
                icon: 'info',
                title: 'Save New Leave Form in Progress...',
                text: 'Info',
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false
            });
        },
        success:function(response){
        	if (response == 'success') {
                setTimeout(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'New Leave Form Saved Successfully',
                        text: 'Success',
                        showConfirmButton: false,
                        timer : 1000
                    });
                    document.getElementById('leave_type_leave').value = '';
                    document.getElementById('leave_date_from_leave').value = '';
                    document.getElementById('leave_date_to_leave').value = '';
                    document.getElementById('total_leave_days_leave').innerHTML = '';
                    document.getElementById('irt_phone_call_leave').checked = false;
                    document.getElementById('irt_letter_leave').checked = false;
                    document.getElementById('irb_leave').value = '';
                    document.getElementById('reason_leave').value = '';
                    $('#leave_form').modal('hide');
                }, 500);
            }else if(response == 'Address Empty'){
                Swal.fire({
                  icon: 'info',
                  title: 'Please fill out Address',
                  text: 'Info',
                  showConfirmButton: false,
                  timer : 1000
                });
            }else if(response == 'Leave Type Not Set'){
                Swal.fire({
                  icon: 'info',
                  title: 'Please Set Leave Type',
                  text: 'Info',
                  showConfirmButton: false,
                  timer : 1000
                });
            }else if(response == 'Leave Date Not Set'){
                Swal.fire({
                  icon: 'info',
                  title: 'Please fill out Leave Dates',
                  text: 'Info',
                  showConfirmButton: false,
                  timer : 1000
                });
            }else if(response == 'IRT Not Set'){
                Swal.fire({
                  icon: 'info',
                  title: 'Please set Information Received Through',
                  text: 'Info',
                  showConfirmButton: false,
                  timer : 1000
                });
            }else if(response == 'IRB Empty'){
                Swal.fire({
                  icon: 'info',
                  title: 'Please fill out Information Received By',
                  text: 'Info',
                  showConfirmButton: false,
                  timer : 1000
                });
            }else if(response == 'Reason Empty'){
                Swal.fire({
                  icon: 'info',
                  title: 'Please fill out Reason',
                  text: 'Info',
                  showConfirmButton: false,
                  timer : 1000
                });
            }else{
                Swal.fire({
                  icon: 'error',
                  title: 'Error !!!',
                  text: 'Error',
                  showConfirmButton: false,
                  timer : 1000
                });
            }
        }
    });
}

const get_pending_leave_forms =()=>{
    $.ajax({
        url:'../process/user/leave/laf_p.php',
        type:'POST',
        cache:false,
        data:{
            method:'get_pending_leave_forms'
        },
        success:function(response){
            $('#pendingLeaveFormsData').html(response);
            let table_rows = parseInt(document.getElementById("pendingLeaveFormsData").childNodes.length);
            $('#count_view').html("Count: " + table_rows);
        }
    });
}

const get_pending_leave_forms_details = param => {
    var string = param.split('~!~');
    var leave_form_id = string[0];
    var emp_no = string[1];
    var date_filed = string[2];
    var address = string[3];
    var contact_no = string[4];
    var leave_type = string[5];
    var leave_date_from = string[6];
    var leave_date_to = string[7];
    var total_leave_days = string[8];
    var irt_phone_call = string[9];
    var irt_letter = string[10];
    var irb = string[11];
    var reason = string[12];

    var issued_by = string[13];
    var js_s = string[14];
    var sv = string[15];
    var approver = string[16];

    var sl_r1_1_hrs = string[17];
    var sl_r1_1_date = string[18];
    var sl_r1_1_time_in = string[19];
    var sl_r1_1_time_out = string[20];
    var sl_r1_2_days = string[21];
    var sl_r1_3_date = string[22];

    var sl_rc_1_days = string[23];
    var sl_rc_2_from = string[24];
    var sl_rc_2_to = string[25];
    var sl_rc_3_oc = string[26];
    var sl_rc_4_hm = string[27];
    var sl_rc_mgh = string[28];

    var sl_r2 = string[29];

    var sl_dr_name = string[30];
    var sl_dr_date = string[31];

    document.getElementById('leave_form_id_leave_pending').innerHTML = leave_form_id;
    document.getElementById('emp_no_leave_pending').innerHTML = emp_no;
    document.getElementById('date_filed_leave_pending').innerHTML = date_filed;
    document.getElementById('address_leave_pending').innerHTML = address;
    document.getElementById('contact_no_leave_pending').innerHTML = contact_no;
    document.getElementById('leave_type_leave_pending').innerHTML = leave_type;
    document.getElementById('leave_date_from_leave_pending').innerHTML = leave_date_from;
    document.getElementById('leave_date_to_leave_pending').innerHTML = leave_date_to;
    document.getElementById('total_leave_days_leave_pending').innerHTML = total_leave_days;
    if (irt_phone_call == 1) {
        document.getElementById('irt_leave_pending').innerHTML = 'Phone Call';
    } else if (irt_letter == 1) {
        document.getElementById('irt_leave_pending').innerHTML = 'Letter';
    }
    document.getElementById('irb_leave_pending').innerHTML = irb;
    document.getElementById('reason_leave_pending').innerHTML = reason;

    document.getElementById('issued_by_leave_pending').innerHTML = issued_by;
    document.getElementById('js_s_leave_pending').innerHTML = js_s;
    document.getElementById('sv_leave_pending').innerHTML = sv;
    document.getElementById('approver_leave_pending').innerHTML = approver;

    if (sl_r1_1_hrs > 0 || sl_r1_1_date != '' || sl_r1_1_time_in != '' || sl_r1_1_time_out != '') {
        document.getElementById('lbl_sl_r1_1_hrs_leave_pending').innerHTML = `<i class="fas fa-check-square mr-2"></i>Undertime (No. of Hours) `;
        document.getElementById('sl_r1_1_hrs_leave_pending').innerHTML = sl_r1_1_hrs;
    }
    document.getElementById('sl_r1_1_date_leave_pending').innerHTML = sl_r1_1_date;
    if (sl_rc_1_days > 0 || sl_rc_2_from != '' || sl_rc_2_to != '') {
        document.getElementById('lbl_sl_rc_1_days_leave_pending').innerHTML = `<i class="fas fa-check-square mr-2"></i>Unfit for `;
        document.getElementById('sl_rc_1_days_leave_pending').innerHTML = sl_rc_1_days;
    }

    document.getElementById('sl_r1_1_time_in_leave_pending').innerHTML = sl_r1_1_time_in;
    document.getElementById('sl_r1_1_time_out_leave_pending').innerHTML = sl_r1_1_time_out;
    
    document.getElementById('sl_rc_2_from_leave_pending').innerHTML = sl_rc_2_from;
    document.getElementById('sl_rc_2_to_leave_pending').innerHTML = sl_rc_2_to;

    if (sl_r1_2_days > 0) {
        document.getElementById('lbl_sl_r1_2_days_leave_pending').innerHTML = `<i class="fas fa-check-square mr-2"></i>Sick Leave For : `;
        document.getElementById('sl_r1_2_days_leave_pending').innerHTML = sl_r1_2_days;
    }

    if (sl_rc_3_oc == 1) {
        document.getElementById('sl_rc_3_oc_leave_pending').innerHTML = `<i class="fas fa-check-square mr-2"></i>For Observation At The Clinic`;
    }

    if (sl_r1_3_date != '') {
        document.getElementById('lbl_sl_r1_3_date_leave_pending').innerHTML = `<i class="fas fa-check-square mr-2"></i>Fit To Work Effective `;
        document.getElementById('sl_r1_3_date_leave_pending').innerHTML = sl_r1_3_date;
    }

    if (sl_rc_4_hm == 1) {
        document.getElementById('lbl_sl_rc_4_hm_leave_pending').innerHTML = `<i class="fas fa-check-square mr-2"></i>For Hospital Management`;
    }

    if (sl_rc_mgh == 1) {
        document.getElementById('lbl_sl_rc_mgh_leave_pending').innerHTML = `<i class="fas fa-check-square mr-2"></i>May Go Home`;
    }

    document.getElementById('sl_r2_leave_pending').innerHTML = sl_r2;

    document.getElementById('sl_dr_name_leave_pending').innerHTML = sl_dr_name;
    document.getElementById('sl_dr_date_leave_pending').innerHTML = sl_dr_date;

    $.ajax({
        url:'../process/hr/employees/emp-masterlist_p.php',
        type:'POST',
        cache:false,
        data:{
            method:'get_employee_data',
            emp_no:emp_no
        },
        success:function(response){
            try {
                let response_array = JSON.parse(response);
                document.getElementById('emp_no_leave_pending').innerHTML = response_array.emp_no;
                document.getElementById('full_name_leave_pending').innerHTML = response_array.full_name;
                document.getElementById('dept_leave_pending').innerHTML = response_array.dept;
                document.getElementById('position_leave_pending').innerHTML = response_array.position;
                document.getElementById('emp_status_leave_pending').innerHTML = response_array.emp_status;
                document.getElementById('date_hired_leave_pending').innerHTML = response_array.date_hired;
                document.getElementById('address_leave_pending').value = response_array.address;
                document.getElementById('contact_no_leave_pending').innerHTML = response_array.contact_no;
            } catch(e) {
                console.log(response);
                Swal.fire({
                  icon: 'error',
                  title: 'Error !!!',
                  text: `Error: ${response}`,
                  showConfirmButton: false,
                  timer : 1000
                });
            }
        }
    });
}

const get_recent_leave_forms_history =()=>{
    $.ajax({
        url:'../process/user/leave/laf_p.php',
        type:'POST',
        cache:false,
        data:{
            method:'get_recent_leave_forms_history'
        },
        success:function(response){
            $('#recentLeaveFormsHistoryData').html(response);
            let table_rows = parseInt(document.getElementById("recentLeaveFormsHistoryData").childNodes.length);
            $('#count_view2').html("Count: " + table_rows);
        }
    });
}

const get_leave_forms_history =()=>{
    let date_filed_from = document.getElementById("date_filed_from_search").value;
    let date_filed_to = document.getElementById("date_filed_to_search").value;
    let leave_type = document.getElementById("leave_type_search").value;
    let leave_form_status = document.getElementById("leave_form_status_search").value;
    if (date_filed_from != '' && date_filed_to != '') {
        $.ajax({
            url:'../process/user/leave/laf_p.php',
            type:'POST',
            cache:false,
            data:{
                method:'get_leave_forms_history',
                date_filed_from:date_filed_from,
                date_filed_to:date_filed_to,
                leave_type:leave_type,
                leave_form_status:leave_form_status
            },
            success:function(response){
                $('#leaveFormsHistoryData').html(response);
                let table_rows = parseInt(document.getElementById("leaveFormsHistoryData").childNodes.length);
                $('#count_view3').html("Count: " + table_rows);
            }
        });
    } else {
        Swal.fire({
          icon: 'info',
          title: 'Please fill out date fields',
          text: 'Info',
          showConfirmButton: false,
          timer : 1000
        });
    }
}

const get_leave_forms_history_details = param => {
    var string = param.split('~!~');
    var leave_form_id = string[0];
    var emp_no = string[1];
    var date_filed = string[2];
    var address = string[3];
    var contact_no = string[4];
    var leave_type = string[5];
    var leave_date_from = string[6];
    var leave_date_to = string[7];
    var total_leave_days = string[8];
    var irt_phone_call = string[9];
    var irt_letter = string[10];
    var irb = string[11];
    var reason = string[12];

    var issued_by = string[13];
    var js_s = string[14];
    var sv = string[15];
    var approver = string[16];

    var sl_r1_1_hrs = string[17];
    var sl_r1_1_date = string[18];
    var sl_r1_1_time_in = string[19];
    var sl_r1_1_time_out = string[20];
    var sl_r1_2_days = string[21];
    var sl_r1_3_date = string[22];

    var sl_rc_1_days = string[23];
    var sl_rc_2_from = string[24];
    var sl_rc_2_to = string[25];
    var sl_rc_3_oc = string[26];
    var sl_rc_4_hm = string[27];
    var sl_rc_mgh = string[28];

    var sl_r2 = string[29];

    var sl_dr_name = string[30];
    var sl_dr_date = string[31];

    var leave_form_status = string[32];

    document.getElementById('leave_form_id_leave_history').innerHTML = leave_form_id;
    document.getElementById('emp_no_leave_history').innerHTML = emp_no;
    document.getElementById('date_filed_leave_history').innerHTML = date_filed;
    document.getElementById('address_leave_history').innerHTML = address;
    document.getElementById('contact_no_leave_history').innerHTML = contact_no;
    document.getElementById('leave_type_leave_history').innerHTML = leave_type;
    document.getElementById('leave_date_from_leave_history').innerHTML = leave_date_from;
    document.getElementById('leave_date_to_leave_history').innerHTML = leave_date_to;
    document.getElementById('total_leave_days_leave_history').innerHTML = total_leave_days;
    if (irt_phone_call == 1) {
        document.getElementById('irt_leave_history').innerHTML = 'Phone Call';
    } else if (irt_letter == 1) {
        document.getElementById('irt_leave_history').innerHTML = 'Letter';
    }
    document.getElementById('irb_leave_history').innerHTML = irb;
    document.getElementById('reason_leave_history').innerHTML = reason;

    document.getElementById('issued_by_leave_history').innerHTML = issued_by;
    document.getElementById('js_s_leave_history').innerHTML = js_s;
    document.getElementById('sv_leave_history').innerHTML = sv;
    document.getElementById('approver_leave_history').innerHTML = approver;

    if (sl_r1_1_hrs > 0 || sl_r1_1_date != '' || sl_r1_1_time_in != '' || sl_r1_1_time_out != '') {
        document.getElementById('lbl_sl_r1_1_hrs_leave_history').innerHTML = `<i class="fas fa-check-square mr-2"></i>Undertime (No. of Hours) `;
        document.getElementById('sl_r1_1_hrs_leave_history').innerHTML = sl_r1_1_hrs;
        document.getElementById('sl_r1_1_date_leave_history').innerHTML = sl_r1_1_date;
        document.getElementById('sl_r1_1_time_in_leave_history').innerHTML = sl_r1_1_time_in;
        document.getElementById('sl_r1_1_time_out_leave_history').innerHTML = sl_r1_1_time_out;
    } else {
        document.getElementById('lbl_sl_r1_1_hrs_leave_history').innerHTML = `<i class="fas fa-square mr-2"></i>Undertime (No. of Hours) `;
        document.getElementById('sl_r1_1_hrs_leave_history').innerHTML = '';
        document.getElementById('sl_r1_1_date_leave_history').innerHTML = '';
        document.getElementById('sl_r1_1_time_in_leave_history').innerHTML = '';
        document.getElementById('sl_r1_1_time_out_leave_history').innerHTML = '';
    }
    
    if (sl_rc_1_days > 0 || sl_rc_2_from != '' || sl_rc_2_to != '') {
        document.getElementById('lbl_sl_rc_1_days_leave_history').innerHTML = `<i class="fas fa-check-square mr-2"></i>Unfit for `;
        document.getElementById('sl_rc_1_days_leave_history').innerHTML = sl_rc_1_days;
        document.getElementById('sl_rc_2_from_leave_history').innerHTML = sl_rc_2_from;
        document.getElementById('sl_rc_2_to_leave_history').innerHTML = sl_rc_2_to;
    } else {
        document.getElementById('lbl_sl_rc_1_days_leave_history').innerHTML = `<i class="fas fa-square mr-2"></i>Unfit for `;
        document.getElementById('sl_rc_1_days_leave_history').innerHTML = '';
        document.getElementById('sl_rc_2_from_leave_history').innerHTML = '';
        document.getElementById('sl_rc_2_to_leave_history').innerHTML = '';
    }

    if (sl_r1_2_days > 0) {
        document.getElementById('lbl_sl_r1_2_days_leave_history').innerHTML = `<i class="fas fa-check-square mr-2"></i>Sick Leave For : `;
        document.getElementById('sl_r1_2_days_leave_history').innerHTML = sl_r1_2_days;
    } else {
        document.getElementById('lbl_sl_r1_2_days_leave_history').innerHTML = `<i class="fas fa-square mr-2"></i>Sick Leave For : `;
        document.getElementById('sl_r1_2_days_leave_history').innerHTML = '';
    }

    if (sl_rc_3_oc == 1) {
        document.getElementById('lbl_sl_rc_3_oc_leave_history').innerHTML = `<i class="fas fa-check-square mr-2"></i>For Observation At The Clinic`;
    } else {
        document.getElementById('lbl_sl_rc_3_oc_leave_history').innerHTML = `<i class="fas fa-square mr-2"></i>For Observation At The Clinic`;
    }

    if (sl_r1_3_date != '') {
        document.getElementById('lbl_sl_r1_3_date_leave_history').innerHTML = `<i class="fas fa-check-square mr-2"></i>Fit To Work Effective `;
        document.getElementById('sl_r1_3_date_leave_history').innerHTML = sl_r1_3_date;
    } else {
        document.getElementById('lbl_sl_r1_3_date_leave_history').innerHTML = `<i class="fas fa-square mr-2"></i>Fit To Work Effective `;
        document.getElementById('sl_r1_3_date_leave_history').innerHTML = '';
    }

    if (sl_rc_4_hm == 1) {
        document.getElementById('lbl_sl_rc_4_hm_leave_history').innerHTML = `<i class="fas fa-check-square mr-2"></i>For Hospital Management`;
    } else {
        document.getElementById('lbl_sl_rc_4_hm_leave_history').innerHTML = `<i class="fas fa-square mr-2"></i>For Hospital Management`;
    }

    if (sl_rc_mgh == 1) {
        document.getElementById('lbl_sl_rc_mgh_leave_history').innerHTML = `<i class="fas fa-check-square mr-2"></i>May Go Home`;
    } else {
        document.getElementById('lbl_sl_rc_mgh_leave_history').innerHTML = `<i class="fas fa-square mr-2"></i>May Go Home`;
    }

    document.getElementById('sl_r2_leave_history').innerHTML = sl_r2;

    document.getElementById('sl_dr_name_leave_history').innerHTML = sl_dr_name;
    document.getElementById('sl_dr_date_leave_history').innerHTML = sl_dr_date;

    $.ajax({
        url:'../process/hr/employees/emp-masterlist_p.php',
        type:'POST',
        cache:false,
        data:{
            method:'get_employee_data',
            emp_no:emp_no
        },
        success:function(response){
            try {
                let response_array = JSON.parse(response);
                document.getElementById('emp_no_leave_history').innerHTML = response_array.emp_no;
                document.getElementById('full_name_leave_history').innerHTML = response_array.full_name;
                document.getElementById('dept_leave_history').innerHTML = response_array.dept;
                document.getElementById('position_leave_history').innerHTML = response_array.position;
                document.getElementById('emp_status_leave_history').innerHTML = response_array.emp_status;
                document.getElementById('date_hired_leave_history').innerHTML = response_array.date_hired;
                document.getElementById('address_leave_history').value = response_array.address;
                document.getElementById('contact_no_leave_history').innerHTML = response_array.contact_no;
            } catch(e) {
                console.log(response);
                Swal.fire({
                  icon: 'error',
                  title: 'Error !!!',
                  text: `Error: ${response}`,
                  showConfirmButton: false,
                  timer : 1000
                });
            }
        }
    });
}
</script>