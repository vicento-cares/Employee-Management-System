<script type="text/javascript">
    // AJAX IN PROGRESS GLOBAL VARS
    var load_line_shifting_schedules_ajax_in_process = false;

    $(document).ready(function () {
        fetch_line_dropdown_search();
        load_line_shifting_schedules(1);
        setInterval(() => {
            load_line_shifting_schedules(1);
        }, 60000);
    });

    // Table Responsive Scroll Event for Load More
    document.getElementById("list_of_lshiftsched_res").addEventListener("scroll", function () {
        var scrollTop = document.getElementById("list_of_lshiftsched_res").scrollTop;
        var scrollHeight = document.getElementById("list_of_lshiftsched_res").scrollHeight;
        var offsetHeight = document.getElementById("list_of_lshiftsched_res").offsetHeight;

        if (load_line_shifting_schedules_ajax_in_process == false) {
            //check if the scroll reached the bottom
            if ((offsetHeight + scrollTop + 1) >= scrollHeight) {
                get_next_page();
            }
        }
    });

    const get_next_page = () => {
        var current_page = parseInt(sessionStorage.getItem('list_of_lshiftsched_table_pagination'));
        let total = sessionStorage.getItem('count_rows');
        var last_page = parseInt(sessionStorage.getItem('last_page'));
        var next_page = current_page + 1;
        if (next_page <= last_page && total > 0) {
            load_line_shifting_schedules(next_page);
        }
    }

    const fetch_line_dropdown_search = () => {
        let section = '<?=$_SESSION['section']?>';

        $.ajax({
            url: '../process/hr/employees/emp-masterlist_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'fetch_line_dropdown',
                section: section
            },
            success: function (response) {
                $('#line_no_master_search').html(response);
                $('#line_no_lshift').html(response);
            }
        });
    }

    const count_line_shifting_schedule_list = () => {
        var shift = sessionStorage.getItem('shift_master_search');
        var shift_group = sessionStorage.getItem('shift_group_master_search');
        var line_no = sessionStorage.getItem('line_no_master_search');
        $.ajax({
            url: '../process/admin/shifting/shift_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'count_line_shifting_schedule_list',
                shift: shift,
                shift_group: shift_group,
                line_no: line_no
            },
            success: function (response) {
                sessionStorage.setItem('count_rows', response);
                var count = `Total: ${response}`;
                $('#list_of_lshiftsched_info').html(count);

                if (response > 0) {
                    load_line_shifting_schedules_last_page();
                } else {
                    document.getElementById("btnNextPage").style.display = "none";
                    document.getElementById("btnNextPage").setAttribute('disabled', true);
                }
            }
        });
    }

    const load_line_shifting_schedules_last_page = () => {
        var shift = sessionStorage.getItem('shift_master_search');
        var shift_group = sessionStorage.getItem('shift_group_master_search');
        var line_no = sessionStorage.getItem('line_no_master_search');
        var current_page = parseInt(sessionStorage.getItem('list_of_lshiftsched_table_pagination'));
        $.ajax({
            url: '../process/admin/shifting/shift_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'line_shifting_schedule_list_last_page',
                shift: shift,
                shift_group: shift_group,
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

    const load_line_shifting_schedules = current_page => {
        // If an AJAX call is already in progress, return immediately
        if (load_line_shifting_schedules_ajax_in_process) {
            return;
        }

        var shift = document.getElementById('shift_master_search').value;
        var shift_group = document.getElementById('shift_group_master_search').value;
        var line_no = document.getElementById('line_no_master_search').value;

        var shift1 = sessionStorage.getItem('shift_master_search');
        var shift_group1 = sessionStorage.getItem('shift_group_master_search');
        var line_no1 = sessionStorage.getItem('line_no_master_search');

        if (current_page > 1) {
            switch (true) {
                case shift !== shift1:
                case shift_group !== shift_group1:
                case line_no !== line_no1:
                    shift = shift1;
                    shift_group = shift_group1;
                    line_no = line_no1;
                    break;
                default:
            }
        } else {
            sessionStorage.setItem('shift_master_search', shift);
            sessionStorage.setItem('shift_group_master_search', shift_group);
            sessionStorage.setItem('line_no_master_search', line_no);
        }

        // Set the flag to true as we're starting an AJAX call
        load_line_shifting_schedules_ajax_in_process = true;

        $.ajax({
            url: '../process/admin/shifting/shift_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'line_shifting_schedule_list',
                shift: shift,
                shift_group: shift_group,
                line_no: line_no,
                current_page: current_page
            },
            beforeSend: (jqXHR, settings) => {
                document.getElementById("btnNextPage").setAttribute('disabled', true);
                jqXHR.url = settings.url;
                jqXHR.type = settings.type;
            },
            success: function (response) {
                $('#loading').remove();
                document.getElementById("btnNextPage").removeAttribute('disabled');
                if (current_page == 1) {
                    $('#list_of_lshiftsched_table tbody').html(response);
                } else {
                    $('#list_of_lshiftsched_table tbody').append(response);
                }
                sessionStorage.setItem('list_of_lshiftsched_table_pagination', current_page);
                count_line_shifting_schedule_list();
                // Set the flag back to false as the AJAX call has completed
                load_line_shifting_schedules_ajax_in_process = false;
            }
        }).fail((jqXHR, textStatus, errorThrown) => {
            console.log(jqXHR);
            console.log(`System Error : Call IT Personnel Immediately!!! They will fix it right away. Error: url: ${jqXHR.url}, method: ${jqXHR.type} ( HTTP ${jqXHR.status} - ${jqXHR.statusText} ) Press F12 to see Console Log for more info.`);
            $('#loading').remove();
            document.getElementById("btnNextPage").removeAttribute('disabled');
            // Set the flag back to false as the AJAX call has completed
            load_line_shifting_schedules_ajax_in_process = false;
        });
    }

    const delete_line_shifting_schedule = el => {
        var id = el.dataset.id;

        Swal.fire({
            title: 'Cancel Schedule?',
            text: "This will delete schedule record",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes',
        }).then((confirm_delete_line_shifting_schedule_form) => {
            if (confirm_delete_line_shifting_schedule_form.value) {
                $.ajax({
                    url: '../process/admin/shifting/shift_p.php',
                    type: 'POST',
                    cache: false,
                    data: {
                        method: 'delete_line_shifting_schedule',
                        id: id
                    }, success: function (response) {
                        if (response == 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Line Shifting',
                                text: 'Line Shifting Schedule Cancelled',
                                showConfirmButton: false,
                                timer: 1000
                            });
                            load_line_shifting_schedules(1);
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
        });
    }

    const clear_line_shifting_details = () => {
        document.getElementById('line_no_lshift').value = '';
        document.getElementById('shift_group_lshift').value = '';
        document.getElementById('shift_lshift').value = '';
        document.getElementById('schedule_date_lshift').value = '';
    }

	$("#set_line_shifting").on('hidden.bs.modal', e => {
        clear_line_shifting_details();
    });

    document.getElementById('set_line_shifting_form').addEventListener('submit', e => {
        e.preventDefault();
        set_line_shifting();
    });

    const set_line_shifting = () => {
        var line_no = document.getElementById('line_no_lshift').value;

        if (line_no == '') {
            var selectLineNo = document.getElementById('line_no_lshift');
            var selectedLineNo = selectLineNo.options[selectLineNo.selectedIndex];
            line_no = selectedLineNo.innerHTML;
        }
        
        var shift_group = document.getElementById('shift_group_lshift').value;
        var shift = document.getElementById('shift_lshift').value;
        var schedule_date = document.getElementById('schedule_date_lshift').value;

        $.ajax({
            url: '../process/admin/shifting/shift_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'set_line_shifting',
                line_no: line_no,
                shift_group: shift_group,
                shift: shift,
                schedule_date: schedule_date
            }, success: function (response) {
                if (response == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Line Shifting',
                        text: 'Line Shifting Scheduled Successfully!',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    load_line_shifting_schedules(1);
                    $('#set_line_shifting').modal('hide');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error !!!',
                        text: response,
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            }
        });
    }
</script>