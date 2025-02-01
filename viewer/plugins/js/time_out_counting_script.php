<script type="text/javascript">
    // AJAX IN PROGRESS GLOBAL VARS
    var get_time_out_counting_ajax_in_process = false;
    var get_attendance_list_ajax_in_process = false

    // DOMContentLoaded function
    document.addEventListener("DOMContentLoaded", () => {
        fetch_dept_dropdown();
        // fetch_group_dropdown();
        fetch_section_dropdown();
        fetch_line_dropdown();
        document.getElementById('attendance_date_search').value = '<?= $server_date_only ?>';
    });

    const fetch_dept_dropdown = () => {
        $.ajax({
            url: '../process/hr/employees/emp-masterlist_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'fetch_dept_dropdown'
            },
            success: function (response) {
                $('#dept_search').html(response);
                // $('#dept_search_multiple').html(response);
            }
        });
    }

    const fetch_section_dropdown = () => {
        $.ajax({
            url: '../process/hr/employees/emp-masterlist_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'fetch_section_dropdown'
            },
            success: function (response) {
                $('#section_search').html(response);
                // $('#section_search_multiple').html(response);
            }
        });
    }

    const fetch_line_dropdown = () => {
        let section = document.getElementById('section_search').value;

        $.ajax({
            url: '../process/hr/employees/emp-masterlist_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'fetch_line_dropdown',
                section: section
            },
            success: function (response) {
                $('#line_no_search').html(response);
                // $('#line_no_search_multiple').html(response);
            }
        });
    }

    document.getElementById("attendance_date_search").addEventListener('change', e => {
        // clear_search_multiple_asr();
        get_time_out_counting(1);
    });

    document.getElementById("shift_group_search").addEventListener('change', e => {
        // clear_search_multiple_asr();
        get_time_out_counting(1);
    });

    document.getElementById("dept_search").addEventListener('change', e => {
        // clear_search_multiple_asr();
        get_time_out_counting(1);
    });

    document.getElementById("section_search").addEventListener('change', e => {
        // clear_search_multiple_asr();
        get_time_out_counting(1);
    });

    document.getElementById("line_no_search").addEventListener('change', e => {
        // clear_search_multiple_asr();
        get_time_out_counting(1);
    });

    document.getElementById("btnSearchTimeOutCounting").addEventListener('click', e => {
        // clear_search_multiple_asr();
        get_time_out_counting(1);
    });

    const get_time_out_counting = current_page => {
        // If an AJAX call is already in progress, return immediately
        if (get_time_out_counting_ajax_in_process) {
            return;
        }

        let day = document.getElementById('attendance_date_search').value;
        let shift_group = document.getElementById('shift_group_search').value;
        let dept = document.getElementById('dept_search').value;
        let section = document.getElementById('section_search').value;
        let line_no = document.getElementById('line_no_search').value;

        var day1 = sessionStorage.getItem('attendance_date_search');
        var shift_group1 = sessionStorage.getItem('shift_group_search');
        var dept1 = sessionStorage.getItem('dept_search');
        var section1 = sessionStorage.getItem('section_search');
        var line_no1 = sessionStorage.getItem('line_no_search');

        if (current_page > 1) {
            switch (true) {
                case day !== day1:
                case shift_group !== shift_group1:
                case dept !== dept1:
                case section !== section1:
                case line_no !== line_no1:
                    day = day1;
                    shift_group = shift_group1;
                    dept = dept1;
                    section = section1;
                    line_no = line_no1;
                    break;
                default:
            }
        } else {
            sessionStorage.setItem('attendance_date_search', day);
            sessionStorage.setItem('shift_group_search', shift_group);
            sessionStorage.setItem('dept_search', dept);
            sessionStorage.setItem('section_search', section);
            sessionStorage.setItem('line_no_search', line_no);
        }

        // Set the flag to true as we're starting an AJAX call
        get_time_out_counting_ajax_in_process = true;

        $.ajax({
            url: '../process/admin/time_in_out/tio_p.php',
            type: 'GET',
            cache: false,
            data: {
                method: 'get_time_out_counting',
                day: day,
                shift_group: shift_group,
                dept: dept,
                section: section,
                line_no: line_no
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