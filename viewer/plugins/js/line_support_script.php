<script type="text/javascript">
    // DOMContentLoaded function
    document.addEventListener("DOMContentLoaded", () => {
        fetch_line_dropdown();
        document.getElementById('ls_day_from_search').value = '<?= $server_date_only ?>';
        document.getElementById('ls_day_to_search').value = '<?= $server_date_only ?>';

        sessionStorage.setItem("emp_mgt_ls_day_from_search", '');
        sessionStorage.setItem("emp_mgt_ls_day_to_search", '');
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
        let day_from = document.getElementById("ls_day_from_search").value;
        let day_to = document.getElementById("ls_day_to_search").value;
        let shift = document.getElementById("ls_shift_search").value;
        let emp_no = document.getElementById("ls_emp_no_search").value;
        let full_name = document.getElementById("ls_full_name_search").value;
        let line_no_from = document.getElementById("ls_line_no_from_search").value;
        let line_no_to = document.getElementById("ls_line_no_to_search").value;

        if (day_from != '' && day_to != '') {
            $.ajax({
                url: '../process/admin/line_support/ls_p.php',
                type: 'POST',
                cache: false,
                data: {
                    method: 'get_line_support',
                    day_from: day_from,
                    day_to: day_to,
                    shift: shift,
                    emp_no: emp_no,
                    full_name: full_name,
                    line_no_from: line_no_from,
                    line_no_to: line_no_to
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
                    sessionStorage.setItem("emp_mgt_ls_day_from_search", day_from);
                    sessionStorage.setItem("emp_mgt_ls_day_to_search", day_to);
                    sessionStorage.setItem("emp_mgt_ls_shift_search", shift);
                    sessionStorage.setItem("emp_mgt_ls_line_no_from_search", line_no_from);
                    sessionStorage.setItem("emp_mgt_ls_line_no_to_search", line_no_to);
                }
            });
        } else {
            Swal.fire({
                icon: 'info',
                title: 'Please Fill Out Date Field',
                text: 'Information',
                showConfirmButton: false,
                timer: 1000
            });
        }
    }

    const export_line_support = (table_id, separator = ',') => {
        var day_from = sessionStorage.getItem("emp_mgt_ls_day_from_search");
        var day_to = sessionStorage.getItem("emp_mgt_ls_day_to_search");
        var shift = sessionStorage.getItem("emp_mgt_ls_shift_search");
        var line_no_from = sessionStorage.getItem("emp_mgt_ls_line_no_from_search");
        var line_no_to = sessionStorage.getItem("emp_mgt_ls_line_no_to_search");

        if (day_from != '' && day_to != '') {
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
            var filename = 'EmpMgtSys_LineSupport_' + day_from + '_' + day_to + '_' + shift;
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
        } else {
            Swal.fire({
                icon: 'info',
                title: 'Please Fill Out Date Field',
                text: 'Information',
                showConfirmButton: false,
                timer: 1000
            });
        }
    }

    const get_line_support_details = param => {
        var string = param.split('~!~');
        var emp_no = string[0];
        var full_name = string[1];
        var dept = string[2];
        var section = string[3];
        var line_no_from = string[4];
        var line_process = string[5];

        document.getElementById("emp_no_lsd").innerHTML = emp_no;
        document.getElementById("full_name_lsd").innerHTML = full_name;
        document.getElementById("dept_lsd").innerHTML = dept;
        document.getElementById("section_lsd").innerHTML = section;
        document.getElementById("line_no_lsd").innerHTML = line_no_from;
        document.getElementById("process_lsd").innerHTML = line_process;
        
        $.ajax({
            url: '../process/viewer/certification/cert_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'get_line_support_certification',
                emp_no: emp_no
            },
            beforeSend: (jqXHR, settings) => {
                var loading = `<tr id="loading"><td colspan="16" style="text-align:center;"><div class="spinner-border text-dark" role="status"><span class="sr-only">Loading...</span></div></td></tr>`;
                document.getElementById("certificationData").innerHTML = loading;
                jqXHR.url = settings.url;
                jqXHR.type = settings.type;
            },
            success: function (response) {
                document.getElementById("certificationData").innerHTML = response;
                let table_rows = parseInt(document.getElementById("certificationData").childNodes.length);
                document.getElementById("count_view_lsd").innerHTML = `Count: ${table_rows}`;
            }
        });
    }

    const export_line_support_certification = (table_id, separator = ',') => {
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
        var filename = 'EmpMgtSys_LineSupportCertificationList';
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
</script>