<script type="text/javascript">
// DOMContentLoaded function
document.addEventListener("DOMContentLoaded", () => {
	get_attendance_date_ds();
	get_attendance_date_ns();
	get_recent_time_in_out_ds();
	get_recent_time_in_out_ns();
	setInterval(get_attendance_date_ds, 10000);
	setInterval(get_attendance_date_ns, 10000);
	setInterval(get_recent_time_in_out_ds, 10000);
	setInterval(get_recent_time_in_out_ns, 10000);
	sessionStorage.setItem('notif_pending_ls', 0);
	sessionStorage.setItem('notif_accepted_ls', 0);
	sessionStorage.setItem('notif_rejected_ls', 0);
	load_notif_line_support();
    realtime_load_notif_line_support = setInterval(load_notif_line_support, 5000);
});

const get_attendance_date_ds =()=>{
	$.ajax({
		type: "POST",
		url: "../process/admin/time_in_out/tio_p.php",
		cache:false,
		data: {
			method:"get_attendance_date_ds"
		},
		success: (response)=> {
			$('#day_view_ds').html(`Attendance Date: ${response}`);
		}
	});
}

const get_attendance_date_ns =()=>{
	$.ajax({
		type: "POST",
		url: "../process/admin/time_in_out/tio_p.php",
		cache:false,
		data: {
			method:"get_attendance_date_ns"
		},
		success: (response)=> {
			$('#day_view_ns').html(`Attendance Date: ${response}`);
		}
	});
}

const get_recent_time_in_out_ds =()=>{
	$.ajax({
		type: "POST",
		url: "../process/admin/time_in_out/tio_p.php",
		cache:false,
		data: {
			method:"get_recent_time_in_out_ds"
		},
		success: (response)=>{
			$('#recentTimeInOutDsData').html(response);
			let table_rows = parseInt(document.getElementById("recentTimeInOutDsData").childNodes.length);
			$('#count_view_ds').html("Total MP: " + table_rows);
		}
	});
}

const get_recent_time_in_out_ns =()=>{
	$.ajax({
		type: "POST",
		url: "../process/admin/time_in_out/tio_p.php",
		cache:false,
		data: {
			method:"get_recent_time_in_out_ns"
		},
		success: (response)=>{
			$('#recentTimeInOutNsData').html(response);
			let table_rows = parseInt(document.getElementById("recentTimeInOutNsData").childNodes.length);
			$('#count_view_ns').html("Total MP: " + table_rows);
		}
	});
}
</script>