<script type="text/javascript">
    // AJAX IN PROGRESS GLOBAL VARS
    var get_attendance_list_ajax_in_process = false;

    // DOMContentLoaded function
    document.addEventListener("DOMContentLoaded", () => {
        document.getElementById('attendance_date_search').value = '<?= $server_date_only ?>';
        fetch_dept_dropdown();
        get_attendance_list(1);
    });

    /*document.getElementById("section_search").addEventListener("keyup", e => {
        get_attendance_list(1);
    });
    
    document.getElementById("line_no_search").addEventListener("keyup", e => {
        get_attendance_list(1);
    });*/

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
            }
        });
    }

    // Table Responsive Scroll Event for Load More
    document.getElementById("attendanceTableRes").addEventListener("scroll", function () {
        var scrollTop = document.getElementById("attendanceTableRes").scrollTop;
        var scrollHeight = document.getElementById("attendanceTableRes").scrollHeight;
        var offsetHeight = document.getElementById("attendanceTableRes").offsetHeight;

        if (get_attendance_list_ajax_in_process == false) {
            //check if the scroll reached the bottom
            if ((offsetHeight + scrollTop + 1) >= scrollHeight) {
                get_next_page();
            }
        }
    });

    const get_next_page = () => {
        var current_page = parseInt(sessionStorage.getItem('attendanceTablePagination'));
        let total = sessionStorage.getItem('count_rows');
        var last_page = parseInt(sessionStorage.getItem('last_page'));
        var next_page = current_page + 1;
        if (next_page <= last_page && total > 0) {
            get_attendance_list(next_page);
        }
    }

    const count_attendance_present = () => {
        var day = sessionStorage.getItem('attendance_date_search');
        var shift_group = sessionStorage.getItem('shift_group_search');
        var dept = sessionStorage.getItem('dept_search');
        var section = sessionStorage.getItem('section_search');
        var line_no = sessionStorage.getItem('line_no_search');
        $.ajax({
            url: '../process/admin/attendances/at_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'count_attendance_present',
                day: day,
                shift_group: shift_group,
                dept: dept,
                section: section,
                line_no: line_no
            },
            success: function (response) {
                let total = parseInt(sessionStorage.getItem('count_rows'));

                let present = parseInt(response);
                let absent = total - present;
                $('#count_view_present').html(present);
                $('#count_view_absent').html(absent);

                /*let present = $('#attendanceTable tbody tr.bg-success').length;
                let absent = $('#attendanceTable tbody tr.bg-danger').length;
                $('#count_view_present').html(present);
                $('#count_view_absent').html(absent);*/
            }
        });
    }

    const count_attendance_list = () => {
        var day = sessionStorage.getItem('attendance_date_search');
        var shift_group = sessionStorage.getItem('shift_group_search');
        var dept = sessionStorage.getItem('dept_search');
        var section = sessionStorage.getItem('section_search');
        var line_no = sessionStorage.getItem('line_no_search');
        $.ajax({
            url: '../process/admin/attendances/at_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'count_attendance_list',
                day: day,
                shift_group: shift_group,
                dept: dept,
                section: section,
                line_no: line_no
            },
            success: function (response) {
                sessionStorage.setItem('count_rows', response);
                var count = `Total: ${response}`;
                $('#attendanceTableInfo').html(count);

                if (response > 0) {
                    count_attendance_present();
                    get_attendances_last_page();
                } else {
                    document.getElementById("btnNextPage").style.display = "none";
                    document.getElementById("btnNextPage").setAttribute('disabled', true);
                }
            }
        });
    }

    const get_attendances_last_page = () => {
        var day = sessionStorage.getItem('attendance_date_search');
        var shift_group = sessionStorage.getItem('shift_group_search');
        var dept = sessionStorage.getItem('dept_search');
        var section = sessionStorage.getItem('section_search');
        var line_no = sessionStorage.getItem('line_no_search');
        var current_page = parseInt(sessionStorage.getItem('attendanceTablePagination'));
        $.ajax({
            url: '../process/admin/attendances/at_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'attendance_list_last_page',
                day: day,
                shift_group: shift_group,
                dept: dept,
                section: section,
                line_no: line_no
            },
            success: function (response) {
                sessionStorage.setItem('last_page', response);
                let total = sessionStorage.getItem('count_rows');
                var next_page = current_page + 1;
                if (next_page > response || total < 1) {
                    document.getElementById("btnNextPage").style.display = "none";
                    document.getElementById("btnNextPage").setAttribute('disabled', true);
                } else {
                    document.getElementById("btnNextPage").style.display = "block";
                    document.getElementById("btnNextPage").removeAttribute('disabled');
                }
            }
        });
    }

    const get_attendance_list = current_page => {
        // If an AJAX call is already in progress, return immediately
        if (get_attendance_list_ajax_in_process) {
            return;
        }

        let day = document.getElementById('attendance_date_search').value;
        let shift_group = document.getElementById('shift_group_search').value;

        var dept = document.getElementById('dept_search').value;
        var section = document.getElementById('section_search').value;
        var line_no = document.getElementById('line_no_search').value;

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
        get_attendance_list_ajax_in_process = true;

        $.ajax({
            url: '../process/admin/attendances/at_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'get_attendance_list',
                day: day,
                shift_group: shift_group,
                dept: dept,
                section: section,
                line_no: line_no,
                current_page: current_page
            },
            beforeSend: (jqXHR, settings) => {
                document.getElementById("btnNextPage").setAttribute('disabled', true);
                var loading = `<tr id="loading"><td colspan="12" style="text-align:center;"><div class="spinner-border text-dark" role="status"><span class="sr-only">Loading...</span></div></td></tr>`;
                if (current_page == 1) {
                    document.getElementById("attendanceData").innerHTML = loading;
                } else {
                    $('#attendanceTable tbody').append(loading);
                }
                jqXHR.url = settings.url;
                jqXHR.type = settings.type;
            },
            success: function (response) {
                $('#loading').remove();
                document.getElementById("btnNextPage").removeAttribute('disabled');
                if (current_page == 1) {
                    $('#attendanceTable tbody').html(response);
                } else {
                    $('#attendanceTable tbody').append(response);
                }
                sessionStorage.setItem('attendanceTablePagination', current_page);
                count_attendance_list();
                // Set the flag back to false as the AJAX call has completed
                get_attendance_list_ajax_in_process = false;
            }
        }).fail((jqXHR, textStatus, errorThrown) => {
            console.log(jqXHR);
            console.log(`System Error : Call IT Personnel Immediately!!! They will fix it right away. Error: url: ${jqXHR.url}, method: ${jqXHR.type} ( HTTP ${jqXHR.status} - ${jqXHR.statusText} ) Press F12 to see Console Log for more info.`);
            $('#loading').remove();
            document.getElementById("btnNextPage").removeAttribute('disabled');
            // Set the flag back to false as the AJAX call has completed
            get_attendance_list_ajax_in_process = false;
        });
    }

    const get_absence_details = (param) => {
        var string = param.split('~!~');
        var absent_id = string[0];
        var emp_no = string[1];
        var full_name = string[2];
        var absent_day = string[3];
        var absent_shift_group = string[4];
        var absent_type = string[5];
        var reason = string[6];

        document.getElementById('id_absence_update').value = absent_id;
        document.getElementById('emp_no_absence_update').innerHTML = emp_no;
        document.getElementById('full_name_absence_update').innerHTML = full_name;
        document.getElementById('absent_day_absence_update').innerHTML = absent_day;
        document.getElementById('absent_shift_group_absence_update').innerHTML = absent_shift_group;
        document.getElementById('absent_type_absence_update').value = absent_type;
        document.getElementById('reason_absence_update').value = reason;
    }

    $("#absence_details").on('show.bs.modal', e => {
        load_reason_absence_update_textarea();
    });

    const load_reason_absence_update_textarea = () => {
        setTimeout(() => {
            var max_length = document.getElementById("reason_absence_update").getAttribute("maxlength");
            var reason_absence_update_length = document.getElementById("reason_absence_update").value.length;
            var reason_absence_update_count = `${reason_absence_update_length} / ${max_length}`;
            document.getElementById("reason_absence_update_count").innerHTML = reason_absence_update_count;
        }, 100);
    }

    const count_reason_absence_update_char = () => {
        var max_length = document.getElementById("reason_absence_update").getAttribute("maxlength");
        var reason_absence_update_length = document.getElementById("reason_absence_update").value.length;
        var reason_absence_update_count = `${reason_absence_update_length} / ${max_length}`;
        document.getElementById("reason_absence_update_count").innerHTML = reason_absence_update_count;
    }

    const save_absence_details = () => {
        var id = document.getElementById('id_absence_update').value;
        var emp_no = document.getElementById('emp_no_absence_update').innerHTML;
        var absent_day = document.getElementById('absent_day_absence_update').innerHTML;
        var absent_shift_group = document.getElementById('absent_shift_group_absence_update').innerHTML;
        var absent_type = document.getElementById('absent_type_absence_update').value;
        var reason = document.getElementById('reason_absence_update').value;

        if (absent_type == '') {
            Swal.fire({
                icon: 'info',
                title: 'Please Select Type of Absent !!!',
                text: 'Information',
                showConfirmButton: false,
                timer: 1000
            });
        } else if (reason == '') {
            Swal.fire({
                icon: 'info',
                title: 'Please Input Reason !!!',
                text: 'Information',
                showConfirmButton: false,
                timer: 1000
            });
        } else {
            $.ajax({
                url: '../process/admin/attendances/at_p.php',
                type: 'POST',
                cache: false,
                data: {
                    method: 'save_absence_details',
                    id: id,
                    emp_no: emp_no,
                    absent_day: absent_day,
                    absent_shift_group: absent_shift_group,
                    absent_type: absent_type,
                    reason: reason
                }, success: function (response) {
                    if (response == 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Absence Details Saved Successfully',
                            text: 'Success',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        $('#id_absence_update').val('');
                        $('#emp_no_absence_update').val('');
                        $('#absent_day_absence_update').val('');
                        $('#absent_shift_group_absence_update').val('');
                        $('#absent_type_absence_update').val('');
                        $('#reason_absence_update').val('');
                        get_attendance_list(1);
                        $('#absence_details').modal('hide');
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
    }

    const export_attendances = () => {
        let day = sessionStorage.getItem('attendance_date_search');
        let shift_group = sessionStorage.getItem('shift_group_search');
        let dept = sessionStorage.getItem('dept_search');
        let section = sessionStorage.getItem('section_search');
        let line_no = sessionStorage.getItem('line_no_search');
        window.open('../process/export/exp_attendances_hr.php?day=' + day + "&shift_group=" + shift_group + "&dept=" + dept + "&section=" + section + "&line_no=" + line_no, '_blank');
    }

    const export_absences = () => {
        let day = sessionStorage.getItem('attendance_date_search');
        let shift_group = sessionStorage.getItem('shift_group_search');
        let dept = sessionStorage.getItem('dept_search');
        let section = sessionStorage.getItem('section_search');
        let line_no = sessionStorage.getItem('line_no_search');
        window.open('../process/export/exp_absences_hr.php?day=' + day + "&shift_group=" + shift_group + "&dept=" + dept + "&section=" + section + "&line_no=" + line_no, '_blank');
    }
</script>