<script type="text/javascript">
    // AJAX IN PROGRESS GLOBAL VARS
    var individual_attendance_list_ajax_in_process = false;

    document.getElementById('individual_attendances_form').addEventListener('submit', e => {
        e.preventDefault();
        individual_attendance_list();
    });

    const individual_attendance_list = () => {
        // If an AJAX call is already in progress, return immediately
        if (individual_attendance_list_ajax_in_process) {
            return;
        }

        let day_from = document.getElementById('attendance_day_from_search').value;
        let day_to = document.getElementById('attendance_day_to_search').value;
        var emp_no = document.getElementById('emp_no_search').value;

        // Set the flag to true as we're starting an AJAX call
        individual_attendance_list_ajax_in_process = true;

        $.ajax({
            url: '../process/hr/attendances/iat_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'individual_attendance_list',
                day_from: day_from,
                day_to: day_to,
                emp_no: emp_no,
                page: 'hr'
            },
            beforeSend: (jqXHR, settings) => {
                var loading = `<tr id="loading"><td colspan="9" style="text-align:center;"><div class="spinner-border text-dark" role="status"><span class="sr-only">Loading...</span></div></td></tr>`;

                document.getElementById("attendanceData").innerHTML = loading;
                
                jqXHR.url = settings.url;
                jqXHR.type = settings.type;
            },
            success: function (response) {
                $('#loading').remove();

                $('#attendanceTable tbody').html(response);
                let table_rows = parseInt(document.getElementById("attendanceData").childNodes.length);
				$('#count_view').html("Total: " + table_rows);

                sessionStorage.setItem('attendance_day_from_search', day_from);
                sessionStorage.setItem('attendance_day_to_search', day_to);
                sessionStorage.setItem('emp_no_search', emp_no);

                // Set the flag back to false as the AJAX call has completed
                individual_attendance_list_ajax_in_process = false;
            }
        }).fail((jqXHR, textStatus, errorThrown) => {
            console.log(jqXHR);
            console.log(`System Error : Call IT Personnel Immediately!!! They will fix it right away. Error: url: ${jqXHR.url}, method: ${jqXHR.type} ( HTTP ${jqXHR.status} - ${jqXHR.statusText} ) Press F12 to see Console Log for more info.`);
            $('#loading').remove();

            // Set the flag back to false as the AJAX call has completed
            individual_attendance_list_ajax_in_process = false;
        });
    }

    const export_individual_attendances = (table_id, separator = ',') => {
		let day_from = sessionStorage.getItem('attendance_day_from_search');
		let day_to = sessionStorage.getItem('attendance_day_to_search');
		let emp_no = sessionStorage.getItem('emp_no_search');

        // Select rows from table_id
        var rows = document.querySelectorAll('table#' + table_id + ' tr');

        // Construct csv
        var csv = [];
        for (var i = 0; i < rows.length; i++) {
            var row = [], cols = rows[i].querySelectorAll('td, th');
            for (var j = 0; j < cols.length; j++) {
                var data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, '').replace(/(\s\s)/gm, ' ')
                data = data.replace(/"/g, '""');
                // Push escaped string
                row.push('"' + data + '"');
            }
            csv.push(row.join(separator));
        }

        var csv_string = csv.join('\n');

        // Download it
        var filename = 'EmpMgtSys_IndividualAttendanceList_';

		if (emp_no) {
			filename += emp_no;
		}

		day_from = new Date(day_from);
		var date = day_from.toISOString().split('T')[0];
		day_from = `${date}`;

		day_to = new Date(day_to);
		var date = day_to.toISOString().split('T')[0];
		day_to = `${date}`;

		filename += day_from + '_' + day_to + '.csv';
        var link = document.createElement('a');
        link.style.display = 'none';
        link.setAttribute('target', '_blank');
        link.setAttribute('href', 'data:text/csv;charset=utf-8,%EF%BB%BF' + encodeURIComponent(csv_string));
        link.setAttribute('download', filename);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
</script>