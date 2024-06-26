<script type="text/javascript">
    // DOMContentLoaded function
    document.addEventListener("DOMContentLoaded", () => {
        fetch_line_dropdown();
        document.getElementById('ls_day_search').value = '<?= $server_date_only ?>';

        sessionStorage.setItem("emp_mgt_ls_day_search", '');
        sessionStorage.setItem("emp_mgt_ls_shift_search", '');
        sessionStorage.setItem("emp_mgt_ls_line_no_from_search", '');
        sessionStorage.setItem("emp_mgt_ls_line_no_to_search", '');
    });

    const fetch_line_dropdown = () => {
        $.ajax({
            url: '../process/admin/line_support/ls_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'fetch_line_dropdown'
            },
            success: function (response) {
                $("#ls_line_no_from_search").html(response);
                $("#ls_line_no_to_search").html(response);
            }
        });
    }

    const get_line_support = () => {
        let day = document.getElementById("ls_day_search").value;
        let shift = document.getElementById("ls_shift_search").value;
        let emp_no = document.getElementById("ls_emp_no_search").value;
        let full_name = document.getElementById("ls_full_name_search").value;
        let line_no_from = document.getElementById("ls_line_no_from_search").value;
        let line_no_to = document.getElementById("ls_line_no_to_search").value;
        let status = document.getElementById("ls_status_search").value;

        if (day == '') {
            Swal.fire({
                icon: 'info',
                title: 'Please Fill Out Date Field',
                text: 'Information',
                showConfirmButton: false,
                timer: 1000
            });
        } else {
            $.ajax({
                url: '../process/admin/line_support/ls_p.php',
                type: 'POST',
                cache: false,
                data: {
                    method: 'get_line_support',
                    day: day,
                    shift: shift,
                    emp_no: emp_no,
                    full_name: full_name,
                    line_no_from: line_no_from,
                    line_no_to: line_no_to,
                    status: status
                },
                beforeSend: (jqXHR, settings) => {
                    var loading = `<tr id="loading"><td colspan="16" style="text-align:center;"><div class="spinner-border text-dark" role="status"><span class="sr-only">Loading...</span></div></td></tr>`;
                    document.getElementById("lineSupportData").innerHTML = loading;
                    jqXHR.url = settings.url;
                    jqXHR.type = settings.type;
                },
                success: function (response) {
                    document.getElementById("lineSupportData").innerHTML = response;
                    let table_rows = parseInt(document.getElementById("lineSupportData").childNodes.length);
                    document.getElementById("count_view3").innerHTML = `Count: ${table_rows}`;
                    sessionStorage.setItem("emp_mgt_ls_day_search", day);
                    sessionStorage.setItem("emp_mgt_ls_shift_search", shift);
                    sessionStorage.setItem("emp_mgt_ls_line_no_from_search", line_no_from);
                    sessionStorage.setItem("emp_mgt_ls_line_no_to_search", line_no_to);
                }
            });
        }
    }

    const export_line_support = (table_id, separator = ',') => {
        var day = sessionStorage.getItem("emp_mgt_ls_day_search");
        var shift = sessionStorage.getItem("emp_mgt_ls_shift_search");
        var line_no_from = sessionStorage.getItem("emp_mgt_ls_line_no_from_search");
        var line_no_to = sessionStorage.getItem("emp_mgt_ls_line_no_to_search");

        if (day == '' || day == null) {
            Swal.fire({
                icon: 'info',
                title: 'Please Fill Out Date Field',
                text: 'Information',
                showConfirmButton: false,
                timer: 1000
            });
        } else {
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
            var filename = 'EmpMgtSys_LineSupport_' + day + '_' + shift;
            if (line_no_from) {
                filename += '_' + line_no_from;
            }
            if (line_no_to) {
                filename += '_' + line_no_to;
            }
            filename += '_' + new Date().toLocaleDateString() + '.csv';
            var link = document.createElement('a');
            link.style.display = 'none';
            link.setAttribute('target', '_blank');
            link.setAttribute('href', 'data:text/csv;charset=utf-8,%EF%BB%BF' + encodeURIComponent(csv_string));
            link.setAttribute('download', filename);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }
</script>