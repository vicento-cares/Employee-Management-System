<script type="text/javascript">
    // AJAX IN PROGRESS GLOBAL VARS
    var get_time_out_counting_ajax_in_process = false;
    var get_attendance_list_ajax_in_process = false

    const get_time_out_counting = current_page => {
        // If an AJAX call is already in progress, return immediately
        if (get_time_out_counting_ajax_in_process) {
            return;
        }

        let day = document.getElementById('attendance_date_search').value;

        var day1 = sessionStorage.getItem('attendance_date_search');

        if (current_page > 1) {
            switch (true) {
                case day !== day1:
                    day = day1;
                    break;
                default:
            }
        } else {
            sessionStorage.setItem('attendance_date_search', day);
        }

        // Set the flag to true as we're starting an AJAX call
        get_time_out_counting_ajax_in_process = true;

        $.ajax({
            url: '../process/admin/time_in_out/tio_p.php',
            type: 'GET',
            cache: false,
            data: {
                method: 'get_time_out_counting',
                day: day
            },
            beforeSend: (jqXHR, settings) => {
                document.getElementById("btnNextPage").setAttribute('disabled', true);
                var loading = `<tr id="loading"><td colspan="12" style="text-align:center;"><div class="spinner-border text-dark" role="status"><span class="sr-only">Loading...</span></div></td></tr>`;
                if (current_page == 1) {
                    document.getElementById("timeOutCountingData").innerHTML = loading;
                } else {
                    $('#timeOutCountingTable tbody').append(loading);
                }
                jqXHR.url = settings.url;
                jqXHR.type = settings.type;
            },
            success: function (response) {
                $('#loading').remove();
                document.getElementById("btnNextPage").removeAttribute('disabled');
                if (current_page == 1) {
                    $('#timeOutCountingTable tbody').html(response);
                } else {
                    $('#timeOutCountingTable tbody').append(response);
                }
                sessionStorage.setItem('timeOutCountingTablePagination', current_page);
                // Set the flag back to false as the AJAX call has completed
                get_time_out_counting_ajax_in_process = false;
            }
        }).fail((jqXHR, textStatus, errorThrown) => {
            console.log(jqXHR);
            console.log(`System Error : Call IT Personnel Immediately!!! They will fix it right away. Error: url: ${jqXHR.url}, method: ${jqXHR.type} ( HTTP ${jqXHR.status} - ${jqXHR.statusText} ) Press F12 to see Console Log for more info.`);
            $('#loading').remove();
            document.getElementById("btnNextPage").removeAttribute('disabled');
            // Set the flag back to false as the AJAX call has completed
            get_time_out_counting_ajax_in_process = false;
        });
    }

    // const export_time_out_counting = () => {
    //     let day = sessionStorage.getItem('attendance_date_search');
    //     window.open('../process/export/exp_time_out_counting.php?day=' + day, '_blank');
    // }

    const export_time_out_counting = (table_id, separator = ',') => {
        let day = sessionStorage.getItem('attendance_date_search');

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

        var filename = 'EmpMgtSys_TimeOutCounting_' + day + '.csv';
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