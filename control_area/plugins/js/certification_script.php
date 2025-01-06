<script type="text/javascript">
    $(document).ready(function () {
        <?php if (empty($_SESSION['line_no'])) {?>
        fetch_line_dropdown();
        <?php }?>

        $("#category").change(function () {
            var category = document.getElementById("category").value;
            $.ajax({
                url: '../process/viewer/certification/cert_p.php',
                type: 'POST',
                cache: false,
                data: {
                    method: 'fetch_pro',
                    category: category
                }, success: function (response) {
                    $('#pro').html(response);
                }
            });
        });
    });

    const fetch_line_dropdown = () => {
        var section = '<?= $_SESSION['section'] ?>';

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
            }
        });
    }

    document.querySelectorAll('#emp_id_search, #fullname_search').forEach(input => {
        input.addEventListener("keyup", e => {
            if (e.which === 13) {
                search_data(1);
            }
        });
    });

    document.getElementById("process_details_pagination").addEventListener("keyup", e => {
        var current_page = document.getElementById("process_details_pagination").value.trim();
        let total = sessionStorage.getItem('count_rows');
        var last_page = parseInt(sessionStorage.getItem('last_page'));
        if (e.which === 13) {
            e.preventDefault();
            console.log(total);
            if (current_page != 0 && current_page <= last_page && total > 0) {
                search_data(current_page);
            }
        }
    });

    const get_prev_page = () => {
        var current_page = parseInt(sessionStorage.getItem('process_details_pagination'));
        let total = sessionStorage.getItem('count_rows');
        var prev_page = current_page - 1;
        if (prev_page > 0 && total > 0) {
            search_data(prev_page);
        }
    }

    const get_next_page = () => {
        var current_page = parseInt(sessionStorage.getItem('process_details_pagination'));
        let total = sessionStorage.getItem('count_rows');
        var last_page = parseInt(sessionStorage.getItem('last_page'));
        var next_page = current_page + 1;
        if (next_page <= last_page && total > 0) {
            search_data(next_page);
        }
    }

    const search_data = current_page => {
        var emp_id = document.getElementById('emp_id_search').value;
        var category = document.getElementById('category').value;
        var pro = document.getElementById('pro').value;
        var date = document.getElementById('expire_date_search').value;
        var date_authorized = document.getElementById('date_authorized_search').value;
        var fullname = document.getElementById('fullname_search').value;
        var dept = '<?= $_SESSION['dept'] ?>';
        var section = '<?= $_SESSION['section'] ?>';
        var line_no = document.getElementById('line_no_search').value;

        if (pro == 'Please select a process.....') {
            pro = '';
        }
        if (category == 'Category') {
            category = '';
        }

        $.ajax({
            url: '../process/viewer/certification/cert_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'fetch_category',
                emp_id: emp_id,
                category: category,
                pro: pro,
                date: date,
                date_authorized: date_authorized,
                fullname: fullname,
                dept: dept,
                section: section,
                line_no: line_no,
                current_page: current_page

            }, success: function (response) {
                $('#process_details').html(response);
                sessionStorage.setItem('process_details_pagination', current_page);
                count_data();
            }
        });
    }

    const count_data = () => {
        var emp_id = document.getElementById('emp_id_search').value;
        var fullname = document.getElementById('fullname_search').value;
        var category = document.getElementById('category').value;
        var pro = document.getElementById('pro').value;
        var date = document.getElementById('expire_date_search').value;
        var date_authorized = document.getElementById('date_authorized_search').value;
        var dept = '<?= $_SESSION['dept'] ?>';
        var section = '<?= $_SESSION['section'] ?>';
        var line_no = document.getElementById('line_no_search').value;

        if (pro == 'Please select a process.....') {
            pro = '';
        }
        if (category == 'Category') {
            category = '';
        }

        $.ajax({
            url: '../process/viewer/certification/cert_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'count_category',
                emp_id: emp_id,
                category: category,
                pro: pro,
                date: date,
                date_authorized: date_authorized,
                fullname: fullname,
                dept: dept,
                section: section,
                line_no: line_no

            }, success: function (response) {
                sessionStorage.setItem('count_rows', response);
                var count = `Total: ${response}`;
                $('#count_rows_display').html(count);

                if (response > 0) {
                    search_data_pagination();
                    document.getElementById('btnPrevPage').disabled = false;
                    document.getElementById('btnNextPage').disabled = false;
                    document.getElementById('process_details_pagination').disabled = false;
                } else {
                    document.getElementById('btnPrevPage').disabled = true;
                    document.getElementById('btnNextPage').disabled = true;
                    document.getElementById('process_details_pagination').disabled = true;
                    $('#count_rows_display').html('');
                }
            }
        });
    }

    const search_data_pagination = () => {
        var emp_id = document.getElementById('emp_id_search').value;
        var category = document.getElementById('category').value;
        var pro = document.getElementById('pro').value;
        var date = document.getElementById('expire_date_search').value;
        var date_authorized = document.getElementById('date_authorized_search').value;
        var fullname = document.getElementById('fullname_search').value;
        var dept = '<?= $_SESSION['dept'] ?>';
        var section = '<?= $_SESSION['section'] ?>';
        var line_no = document.getElementById('line_no_search').value;
        var current_page = sessionStorage.getItem('process_details_pagination');

        if (pro == 'Please select a process.....') {
            pro = '';
        }
        if (category == 'Category') {
            category = '';
        }

        $.ajax({
            url: '../process/viewer/certification/cert_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'fetch_category_pagination',
                emp_id: emp_id,
                category: category,
                pro: pro,
                date: date,
                date_authorized: date_authorized,
                fullname: fullname,
                dept: dept,
                section: section,
                line_no: line_no
            }, success: function (response) {
                $('#process_details_paginations').html(response);
                $('#process_details_pagination').val(current_page);
                sessionStorage.setItem('process_details_pagination', current_page);
                let last_page_check = document.getElementById("process_details_paginations").innerHTML;
                if (last_page_check != '') {
                    let last_page = document.getElementById("process_details_paginations").lastChild.text;
                    sessionStorage.setItem('last_page', last_page);
                }
            }
        });
    }

    //     function export_data(table_id, separator = ',') {
    //     // Select rows from table_id
    //     var rows = document.querySelectorAll('table#' + table_id + ' tr');
    //     // Construct csv
    //     var csv = [];
    //     for (var i = 0; i < rows.length; i++) {
    //         var row = [], cols = rows[i].querySelectorAll('td, th');
    //         for (var j = 0; j < cols.length; j++) {
    //             var data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, '').replace(/(\s\s)/gm, ' ')
    //             data = data.replace(/"/g, '""');
    //             // Push escaped string
    //             row.push('"' + data + '"');
    //         }
    //         csv.push(row.join(separator));
    //     }
    //     var csv_string = csv.join('\n');
    //     // Download it
    //     var filename = 'erecord_data'+ '_' + new Date().toLocaleDateString() + '.csv';
    //     var link = document.createElement('a');
    //     link.style.display = 'none';
    //     link.setAttribute('target', '_blank');
    //     link.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv_string));
    //     link.setAttribute('download', filename);
    //     document.body.appendChild(link);
    //     link.click();
    //     document.body.removeChild(link);
    // }

    const export_data = () => {
        var emp_id = document.getElementById('emp_id_search').value;
        var category = document.getElementById('category').value;
        var pro = document.getElementById('pro').value;
        var date = document.getElementById('expire_date_search').value;
        var date_authorized = document.getElementById('date_authorized_search').value;
        var fullname = document.getElementById('fullname_search').value;
        var dept = '<?= $_SESSION['dept'] ?>';
        var section = '<?= $_SESSION['section'] ?>';
        var line_no = document.getElementById('line_no_search').value;

        if (category) {
            var encodedPro = encodeURIComponent(pro);
            window.open('../process/export/exp_certification.php?emp_id=' + emp_id
                + "&category=" + category
                + "&pro=" + encodedPro
                + "&date=" + date
                + "&date_authorized=" + date_authorized
                + "&fullname=" + fullname
                + "&dept=" + dept
                + "&section=" + section
                + "&line_no=" + line_no, '_blank');
        } else {
            alert('Please, select category.');
        }
    }

    const get_skill_level_details = (param) => {
        var string = param.split('~!~');
        var id = string[0];
        var skill_level = string[1];
        var emp_no = string[2];
        var process = string[3];

        document.getElementById('id_skill_level_update').value = id;
        document.getElementById('skill_level_c_update').value = skill_level;
        document.getElementById('emp_no_c_update').value = emp_no;
        document.getElementById('process_c_update').value = process;
    }

    document.getElementById("update_skill_level_form").addEventListener("submit", e => {
        e.preventDefault();
        update_skill_level();
    });

    const update_skill_level = () => {
        var id = document.getElementById('id_skill_level_update').value;
        var skill_level = document.getElementById('skill_level_c_update').value;
        var emp_no = document.getElementById('emp_no_c_update').value;
        var process = document.getElementById('process_c_update').value;

        $.ajax({
            url: '../process/viewer/certification/cert_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'update_skill_level',
                id: id,
                skill_level: skill_level,
                emp_no: emp_no,
                process: process
            }, success: function (response) {
                if (response == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Succesfully Recorded!!!',
                        text: 'Success',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    $('#id_skill_level_update').val('');
                    $('#skill_level_c_update').val('');
                    $('#emp_no_c_update').val('');
                    $('#process_c_update').val('');
                    search_data(1);
                    $('#update_skill_level').modal('hide');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error !!!',
                        text: 'Error',
                        showConfirmButton: false,
                        timer: 1000
                    });
                }
            }
        });
    }
</script>