<script type="text/javascript">
	// DOMContentLoaded function
	document.addEventListener("DOMContentLoaded", () => {
		get_attendance_date();
		get_recent_time_in_out_ds();
		get_recent_time_in_out_ns();
		get_recent_time_in_out_ads();
		setInterval(get_attendance_date, 10000);
		setInterval(get_recent_time_in_out_ds, 10000);
		setInterval(get_recent_time_in_out_ns, 10000);
		setInterval(get_recent_time_in_out_ads, 10000);
		sessionStorage.setItem('notif_pending_ls', 0);
		sessionStorage.setItem('notif_accepted_ls', 0);
		sessionStorage.setItem('notif_rejected_ls', 0);
		load_notif_line_support();
		realtime_load_notif_line_support = setInterval(load_notif_line_support, 5000);
	});

	const get_attendance_date = () => {
		$.ajax({
			type: "GET",
			url: "../process/admin/time_in_out/tio_p.php",
			cache: false,
			data: {
				method: "get_attendance_date"
			},
			success: (response) => {
				try {
                    let response_array = JSON.parse(response);
					$('#day_view_ds').html(`Attendance Date: ${response_array.day_view_ds}`);
					$('#day_view_ns').html(`Attendance Date: ${response_array.day_view_ns}`);
					$('#day_view_ads').html(`Attendance Date: ${response_array.day_view_ads}`);
                } catch (e) {
                    console.log(response);
                }
			}
		});
	}

	const get_recent_time_in_out_ds = () => {
		$.ajax({
			type: "GET",
			url: "../process/admin/time_in_out/tio_p.php",
			cache: false,
			data: {
				method: "get_recent_time_in_out",
				shift_group: 'A'
			},
			success: (response) => {
				$('#recentTimeInOutDsData').html(response);
				let table_rows = parseInt(document.getElementById("recentTimeInOutDsData").childNodes.length);
				$('#count_view_ds').html("Total MP: " + table_rows);
			}
		});
	}

	const get_recent_time_in_out_ns = () => {
		$.ajax({
			type: "GET",
			url: "../process/admin/time_in_out/tio_p.php",
			cache: false,
			data: {
				method: "get_recent_time_in_out",
				shift_group: 'B'
			},
			success: (response) => {
				$('#recentTimeInOutNsData').html(response);
				let table_rows = parseInt(document.getElementById("recentTimeInOutNsData").childNodes.length);
				$('#count_view_ns').html("Total MP: " + table_rows);
			}
		});
	}

	const get_recent_time_in_out_ads = () => {
		$.ajax({
			type: "GET",
			url: "../process/admin/time_in_out/tio_p.php",
			cache: false,
			data: {
				method: "get_recent_time_in_out",
				shift_group: 'ADS'
			},
			success: (response) => {
				$('#recentTimeInOutAdsData').html(response);
				let table_rows = parseInt(document.getElementById("recentTimeInOutAdsData").childNodes.length);
				$('#count_view_ads').html("Total MP: " + table_rows);
			}
		});
	}
</script>