<script type="text/javascript">
    // AJAX IN PROGRESS GLOBAL VARS
    var get_attendance_monitoring_ajax_in_process = false;

    // DOMContentLoaded function
    document.addEventListener("DOMContentLoaded", () => {
        get_attendance_monitoring_year_dropdown_search();

        setTimeout(() => {
            document.getElementById('am_year_search').value = '<?=date('Y')?>';
            document.getElementById('am_month_search').value = '<?=date('n')?>';

            get_attendance_monitoring();
        }, 750);
    });

    const get_attendance_monitoring_year_dropdown_search = () => {
        $.ajax({
            url: '../process/hr/attendances/nc_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'get_non_compliance_year_dropdown_search'
            },
            success: function (response) {
                document.getElementById("am_year_search").innerHTML = response;
            }
        });
    }

    const populateTable = (tableId, dataRows) => {
        const table = document.querySelector(tableId);
        let absentRatePercent = '';
        if (tableId == '#absentRateMonTable') {
            absentRatePercent = '%';
        }

        // Clear existing content
        table.innerHTML = "";

        if (!dataRows || dataRows.length === 0) {
            table.innerHTML = "<tr><td colspan='100%' style='text-align:center;'>No data available</td></tr>";
            return 0; // no rows
        }

        // Generate header
        const columns = Object.keys(dataRows[0]);
        let thead = "<thead><tr>";
        columns.forEach(col => {
            thead += `<th>${col}</th>`;
        });
        thead += "</tr></thead>";

        // Generate body
        let tbody = "<tbody>";
        dataRows.forEach((row, index) => {
            // Check if this is the last row
            const isLastRow = index === dataRows.length - 1;
            // Set the row color based on whether it's the last row
            const rowColor = isLastRow ? 'bg-dark' : '';
            
            tbody += `<tr class="${rowColor}">`;
            columns.forEach((col, colIndex) => {
                if (colIndex === 0) {
                    tbody += `<td class="text-bold">${row[col]}</td>`;
                } else {
                    tbody += `<td>${row[col]}${absentRatePercent}</td>`;
                }
            });
            tbody += "</tr>";
        });
        tbody += "</tbody>";

        // Append to table
        table.innerHTML = thead + tbody;

        return dataRows.length - 1; // return number of rows
    }

    document.getElementById('attendance_monitoring_form').addEventListener('submit', e => {
        e.preventDefault();
        get_attendance_monitoring();
    });

    const get_attendance_monitoring = () => {
        // If an AJAX call is already in progress, return immediately
        if (get_attendance_monitoring_ajax_in_process) {
            return;
        }

        let year = document.getElementById('am_year_search').value;
        let month = document.getElementById('am_month_search').value;

        // Set the flag to true as we're starting an AJAX call
        get_attendance_monitoring_ajax_in_process = true;

        $.ajax({
            url: '../process/admin/attendances/am_p.php',
            type: 'GET',
            cache: false,
            data: {
                method: 'month_attendance_mon',
                year: year,
                month: month
            },
            beforeSend: (jqXHR, settings) => {
                var loading = `<tr class="loading"><td colspan="32" style="text-align:center;"><div class="spinner-border text-dark" role="status"><span class="sr-only">Loading...</span></div></td></tr>`;

                document.getElementById("absentMonTable").innerHTML = loading;
                document.getElementById("presentMonTable").innerHTML = loading;
                document.getElementById("absentRateMonTable").innerHTML = loading;
                
                jqXHR.url = settings.url;
                jqXHR.type = settings.type;
            },
            success: function (response) {
                $('.loading').remove();

                const absentCount = populateTable("#absentMonTable", response.absent_mon_rows);
                const presentCount = populateTable("#presentMonTable", response.present_mon_rows);
                const absentRateCount = populateTable("#absentRateMonTable", response.absent_rate_mon_rows);

                $('#count_view_absent_mon').html("Total: " + absentCount);
                $('#count_view_present_mon').html("Total: " + presentCount);
                $('#count_view_absent_rate_mon').html("Total: " + absentRateCount);

                sessionStorage.setItem('am_year_search', year);
                sessionStorage.setItem('am_month_search', month);
                
                // Set the flag back to false as the AJAX call has completed
                get_attendance_monitoring_ajax_in_process = false;
            }
        }).fail((jqXHR, textStatus, errorThrown) => {
            console.log(jqXHR);
            console.log(`System Error : Call IT Personnel Immediately!!! They will fix it right away. Error: url: ${jqXHR.url}, method: ${jqXHR.type} ( HTTP ${jqXHR.status} - ${jqXHR.statusText} ) Press F12 to see Console Log for more info.`);
            $('.loading').remove();

            // Set the flag back to false as the AJAX call has completed
            get_attendance_monitoring_ajax_in_process = false;
        });
    }

    const export_attendance_monitoring = (table_id, separator = ',') => {
        let year = sessionStorage.getItem('am_year_search');
        let month = sessionStorage.getItem('am_month_search');

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
        var filename = 'EmpMgtSys_AttendanceMonitoring_';

        if (table_id == 'absentMonTable') {
            filename = 'EmpMgtSys_AbsentMonitoring_';
        } else if (table_id == 'presentMonTable') {
            filename = 'EmpMgtSys_PresentMonitoring';
        } else if (table_id == 'absentRateMonTable') {
            filename = 'EmpMgtSys_AbsentRateMonitoring';
        }
        
        if (year && month) {
			filename += year + '_' + month;
		}

		filename += '.csv';
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