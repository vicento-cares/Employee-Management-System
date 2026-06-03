<script type="text/javascript">
    // DOMContentLoaded function
    document.addEventListener("DOMContentLoaded", () => {
        get_recent_backup_logs();
    });

    document.getElementById('emp_mgt_backup_form').addEventListener('submit', e => {
        e.preventDefault();
        backup_empmgtsys_data();
    });

    const backup_empmgtsys_data = () => {
        let bak_date_from = document.getElementById('bak_date_from').value;
        let bak_date_to = document.getElementById('bak_date_to').value;

        Swal.fire({
            icon: 'info',
            title: 'Transferring Data to Backup Database Please Wait...',
            text: 'Info',
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false
        });

        $.ajax({
            url: '../process/backup/bak_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'backup_empmgtsys_data',
                bak_date_from: bak_date_from,
                bak_date_to: bak_date_to
            }, success: function (response) {
                swal.close();
                if (response == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Transfer Employee Management System Data to Backup Database Executed Successfully!!!',
                        text: 'Success',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    get_recent_backup_logs();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error !!!',
                        text: `${response}`,
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            }
        });
    }

    const get_recent_backup_logs = () => {
		$.ajax({
			type: "POST",
			url: "../process/backup/bak_p.php",
			cache: false,
			data: {
				method: "get_recent_backup_logs"
            }, 
			success: (response) => {
                $('#backupLogsData').html(response);
				let table_rows = parseInt(document.getElementById("backupLogsData").childNodes.length);
				$('#count_view').html("Total: " + table_rows);
			}
		});
	}
</script>