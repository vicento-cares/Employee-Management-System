<script type="text/javascript">
    // AJAX IN PROGRESS GLOBAL VARS
    var get_attendance_summary_report_ajax_in_process = false;
    var get_attendance_list_ajax_in_process = false;

    // DOMContentLoaded function
    document.addEventListener("DOMContentLoaded", () => {
        // fetch_group_dropdown();
        fetch_section_dropdown();
        fetch_line_dropdown();
        document.getElementById('attendance_date_search').value = '<?= $server_date_only ?>';
        get_attendance_summary_report(1);
        sessionStorage.setItem('notif_pending_ls', 0);
        sessionStorage.setItem('notif_accepted_ls', 0);
        sessionStorage.setItem('notif_rejected_ls', 0);
        load_notif_line_support();
        realtime_load_notif_line_support = setInterval(load_notif_line_support, 5000);
    });

    const fetch_group_dropdown = () => {
        $.ajax({
            url: '../process/hr/employees/emp-masterlist_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'fetch_group_dropdown'
            },
            success: function (response) {
                $('#group_search').html(response);
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
            }
        });
    }

    // Table Responsive Scroll Event for Load More
    // document.getElementById("attendanceSummaryReportTableRes").addEventListener("scroll", function () {
    //     var scrollTop = document.getElementById("attendanceSummaryReportTableRes").scrollTop;
    //     var scrollHeight = document.getElementById("attendanceSummaryReportTableRes").scrollHeight;
    //     var offsetHeight = document.getElementById("attendanceSummaryReportTableRes").offsetHeight;

    //     //check if the scroll reached the bottom
    //     if ((offsetHeight + scrollTop + 1) >= scrollHeight) {
    //         get_next_page();
    //     }
    // });

    const get_next_page = () => {
        var current_page = parseInt(sessionStorage.getItem('attendanceSummaryReportTablePagination'));
        let total = sessionStorage.getItem('count_rows');
        var last_page = parseInt(sessionStorage.getItem('last_page'));
        var next_page = current_page + 1;
        if (next_page <= last_page && total > 0) {
            get_attendance_summary_report(next_page);
        }
    }

    const count_attendance_summary_report = () => {
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
                method: 'count_attendance_summary_report',
                day: day,
                shift_group: shift_group,
                dept: dept,
                section: section,
                line_no: line_no
            },
            success: function (response) {
                sessionStorage.setItem('count_rows', response);
                var count = `Total: ${response}`;
                document.getElementById("attendanceSummaryReportTableInfo").innerHTML = count;

                if (response > 0) {
                    get_attendance_summary_report_last_page();
                } else {
                    document.getElementById("btnNextPage").style.display = "none";
                    document.getElementById("btnNextPage").setAttribute('disabled', true);
                }
            }
        });
    }

    const get_attendance_summary_report_last_page = () => {
        var day = sessionStorage.getItem('attendance_date_search');
        var shift_group = sessionStorage.getItem('shift_group_search');
        var dept = sessionStorage.getItem('dept_search');
        var section = sessionStorage.getItem('section_search');
        var line_no = sessionStorage.getItem('line_no_search');
        var current_page = parseInt(sessionStorage.getItem('attendanceSummaryReportTablePagination'));
        $.ajax({
            url: '../process/admin/attendances/at_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'attendance_summary_report_last_page',
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

    const get_attendance_summary_report = current_page => {
        // If an AJAX call is already in progress, return immediately
        if (get_attendance_summary_report_ajax_in_process) {
            return;
        }

        let day = document.getElementById('attendance_date_search').value;
        let shift_group = document.getElementById('shift_group_search').value;
        let dept = document.getElementById('dept_search').value;
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
            // Check section to change line no dropdown options
            if (section1 != section) {
                fetch_line_dropdown();
            }
            sessionStorage.setItem('attendance_date_search', day);
            sessionStorage.setItem('shift_group_search', shift_group);
            sessionStorage.setItem('dept_search', dept);
            sessionStorage.setItem('section_search', section);
            sessionStorage.setItem('line_no_search', line_no);
        }

        // Set the flag to true as we're starting an AJAX call
        get_attendance_summary_report_ajax_in_process = true;

        $.ajax({
            url: '../process/admin/attendances/at_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'get_attendance_summary_report',
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
                    document.getElementById("attendanceSummaryReportData").innerHTML = loading;
                } else {
                    $('#attendanceSummaryReportTable tbody').append(loading);
                }
                jqXHR.url = settings.url;
                jqXHR.type = settings.type;
            },
            success: function (response) {
                $('#loading').remove();
                document.getElementById("btnNextPage").removeAttribute('disabled');
                if (current_page == 1) {
                    $('#attendanceSummaryReportTable tbody').html(response);
                } else {
                    $('#attendanceSummaryReportTable tbody').append(response);
                }
                sessionStorage.setItem('attendanceSummaryReportTablePagination', current_page);
                // count_attendance_summary_report();
                // Set the flag back to false as the AJAX call has completed
                get_attendance_summary_report_ajax_in_process = false;
            }
        }).fail((jqXHR, textStatus, errorThrown) => {
            console.log(jqXHR);
            console.log(`System Error : Call IT Personnel Immediately!!! They will fix it right away. Error: url: ${jqXHR.url}, method: ${jqXHR.type} ( HTTP ${jqXHR.status} - ${jqXHR.statusText} ) Press F12 to see Console Log for more info.`);
            $('#loading').remove();
            document.getElementById("btnNextPage").removeAttribute('disabled');
            // Set the flag back to false as the AJAX call has completed
            get_attendance_summary_report_ajax_in_process = false;
        });
    }

    const export_attendance_summary_report = () => {
        let day = sessionStorage.getItem('attendance_date_search');
        let shift_group = sessionStorage.getItem('shift_group_search');
        let dept = sessionStorage.getItem('dept_search');
        let section = sessionStorage.getItem('section_search');
        let line_no = sessionStorage.getItem('line_no_search');
        window.open('../process/export/exp_attendance_summary_report.php?day=' + day + "&shift_group=" + shift_group + "&dept=" + dept + "&section=" + section + "&line_no=" + line_no, '_blank');
    }

    // Table Responsive Scroll Event for Load More
    document.getElementById("attendanceTableRes").addEventListener("scroll", function () {
        var scrollTop = document.getElementById("attendanceTableRes").scrollTop;
        var scrollHeight = document.getElementById("attendanceTableRes").scrollHeight;
        var offsetHeight = document.getElementById("attendanceTableRes").offsetHeight;

        //check if the scroll reached the bottom
        if ((offsetHeight + scrollTop + 1) >= scrollHeight) {
            get_next_page1();
        }
    });

    const get_next_page1 = () => {
        var current_page = parseInt(sessionStorage.getItem('attendanceTablePagination'));
        let total = sessionStorage.getItem('count_rows1');
        var last_page = parseInt(sessionStorage.getItem('last_page1'));
        var next_page = current_page + 1;
        if (next_page <= last_page && total > 0) {
            get_attendance_list(next_page);
        }
    }

    const count_attendance_list = () => {
        var day = sessionStorage.getItem('attendance_date_asrd');
        var shift_group = sessionStorage.getItem('shift_group_asrd');
        var dept = sessionStorage.getItem('dept_asrd');
        var section = sessionStorage.getItem('section_asrd');
        var line_no = sessionStorage.getItem('line_no_asrd');
        var current_page = parseInt(sessionStorage.getItem('attendanceTablePagination'));
        $.ajax({
            url: '../process/admin/attendances/at_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'count_attendance_list2',
                day: day,
                shift_group: shift_group,
                dept: dept,
                section: section,
                line_no: line_no
            },
            success: function (response) {
                sessionStorage.setItem('count_rows1', response);
                var count = `Total: ${response}`;
                document.getElementById("attendanceTableInfo1").innerHTML = count;

                if (response > 0) {
                    get_attendances_last_page();
                } else {
                    document.getElementById("btnNextPage1").style.display = "none";
                    document.getElementById("btnNextPage1").setAttribute('disabled', true);
                }
            }
        });
    }

    const get_attendances_last_page = () => {
        var day = sessionStorage.getItem('attendance_date_asrd');
        var shift_group = sessionStorage.getItem('shift_group_asrd');
        var dept = sessionStorage.getItem('dept_asrd');
        var section = sessionStorage.getItem('section_asrd');
        var line_no = sessionStorage.getItem('line_no_asrd');
        var current_page = parseInt(sessionStorage.getItem('attendanceTablePagination'));
        $.ajax({
            url: '../process/admin/attendances/at_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'attendance_list_last_page2',
                day: day,
                shift_group: shift_group,
                dept: dept,
                section: section,
                line_no: line_no
            },
            success: function (response) {
                sessionStorage.setItem('last_page1', response);
                let total = sessionStorage.getItem('count_rows1');
                var next_page = current_page + 1;
                if (next_page > response || total < 1) {
                    document.getElementById("btnNextPage1").style.display = "none";
                    document.getElementById("btnNextPage1").setAttribute('disabled', true);
                } else {
                    document.getElementById("btnNextPage1").style.display = "block";
                    document.getElementById("btnNextPage1").removeAttribute('disabled');
                }
            }
        });
    }

    const get_attendance_list = current_page => {
        // If an AJAX call is already in progress, return immediately
        if (get_attendance_list_ajax_in_process) {
            return;
        }

        var day = sessionStorage.getItem('attendance_date_asrd');
        var shift_group = sessionStorage.getItem('shift_group_asrd');
        var dept = sessionStorage.getItem('dept_asrd');
        var section = sessionStorage.getItem('section_asrd');
        var line_no = sessionStorage.getItem('line_no_asrd');

        // Set the flag to true as we're starting an AJAX call
        get_attendance_list_ajax_in_process = true;

        $.ajax({
            url: '../process/admin/attendances/at_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'get_attendance_list2',
                day: day,
                shift_group: shift_group,
                dept: dept,
                section: section,
                line_no: line_no,
                current_page: current_page
            },
            beforeSend: (jqXHR, settings) => {
                document.getElementById("btnNextPage1").setAttribute('disabled', true);
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
                document.getElementById("btnNextPage1").removeAttribute('disabled');
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
            document.getElementById("btnNextPage1").removeAttribute('disabled');
            // Set the flag back to false as the AJAX call has completed
            get_attendance_list_ajax_in_process = false;
        });
    }

    const get_attendance_summary_report_details = (param) => {
        var string = param.split('~!~');
        var day = string[0];
        var shift_group = string[1];
        var dept = string[2];
        var section = string[3];
        var line_no = string[4];
        var total = string[5];
        var total_present = string[6];
        var total_absent = string[7];
        var attendance_percentage = string[8];

        document.getElementById('day_asrd').innerHTML = day;
        document.getElementById('shift_group_asrd').innerHTML = shift_group;
        document.getElementById('dept_asrd').innerHTML = dept;
        document.getElementById('section_asrd').innerHTML = section;
        document.getElementById('line_no_asrd').innerHTML = line_no;
        document.getElementById('total_mp_asrd').innerHTML = total;
        document.getElementById('present_asrd').innerHTML = total_present;
        document.getElementById('absent_asrd').innerHTML = total_absent;
        document.getElementById('attendance_percentage_asrd').innerHTML = `${attendance_percentage}%`;

        document.getElementById('counting_view_present').innerHTML = total_present;
        document.getElementById('counting_view_absent').innerHTML = total_absent;
        document.getElementById('attendanceCountTableInfo').innerHTML = total;

        sessionStorage.setItem('attendance_date_asrd', day);
        sessionStorage.setItem('shift_group_asrd', shift_group);
        sessionStorage.setItem('dept_asrd', dept);
        sessionStorage.setItem('section_asrd', section);
        sessionStorage.setItem('line_no_asrd', line_no);

        $.ajax({
            url: '../process/admin/attendances/at_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'get_attendance_list_counting2',
                day: day,
                shift_group: shift_group,
                dept: dept,
                section: section,
                line_no: line_no
            },
            beforeSend: () => {
                var loading = `<tr id="loading_counting"><td colspan="5" style="text-align:center;"><div class="spinner-border text-dark" role="status"><span class="sr-only">Loading...</span></div></td></tr>`;
                document.getElementById("attendanceCountData").innerHTML = loading;
            },
            success: function (response) {
                $('#loading_counting').remove();
                $('#attendanceCountTable tbody').html(response);
            }
        });

        get_attendance_list(1);
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

        document.getElementById('id_absence_update2').value = absent_id;
        document.getElementById('emp_no_absence_update2').innerHTML = emp_no;
        document.getElementById('full_name_absence_update2').innerHTML = full_name;
        document.getElementById('absent_day_absence_update2').innerHTML = absent_day;
        document.getElementById('absent_shift_group_absence_update2').innerHTML = absent_shift_group;
        document.getElementById('absent_type_absence_update2').value = absent_type;
        document.getElementById('reason_absence_update2').value = reason;

        setTimeout(() => {$("#absence_details2").modal("show");}, 400);
    }

    $("#absence_details2").on('show.bs.modal', e => {
        load_reason_absence_update_textarea();
    });

    const load_reason_absence_update_textarea = () => {
        setTimeout(() => {
            var max_length = document.getElementById("reason_absence_update2").getAttribute("maxlength");
            var reason_absence_update_length = document.getElementById("reason_absence_update2").value.length;
            var reason_absence_update_count = `${reason_absence_update_length} / ${max_length}`;
            document.getElementById("reason_absence_update_count2").innerHTML = reason_absence_update_count;
        }, 100);
    }

    const count_reason_absence_update_char = () => {
        var max_length = document.getElementById("reason_absence_update2").getAttribute("maxlength");
        var reason_absence_update_length = document.getElementById("reason_absence_update2").value.length;
        var reason_absence_update_count = `${reason_absence_update_length} / ${max_length}`;
        document.getElementById("reason_absence_update_count2").innerHTML = reason_absence_update_count;
    }

    const export_attendances = () => {
        let day = sessionStorage.getItem('attendance_date_asrd');
        let shift_group = sessionStorage.getItem('shift_group_asrd');
        let dept = sessionStorage.getItem('dept_asrd');
        let section = sessionStorage.getItem('section_asrd');
        let line_no = sessionStorage.getItem('line_no_asrd');
        window.open('../process/export/exp_attendances2.php?day=' + day + "&shift_group=" + shift_group + "&dept=" + dept + "&section=" + section + "&line_no=" + line_no, '_blank');
    }

    const export_absences = () => {
        let day = sessionStorage.getItem('attendance_date_asrd');
        let shift_group = sessionStorage.getItem('shift_group_asrd');
        let dept = sessionStorage.getItem('dept_asrd');
        let section = sessionStorage.getItem('section_asrd');
        let line_no = sessionStorage.getItem('line_no_asrd');
        window.open('../process/export/exp_absences2.php?day=' + day + "&shift_group=" + shift_group + "&dept=" + dept + "&section=" + section + "&line_no=" + line_no, '_blank');
    }

    const export_attendances_counting = () => {
        let day = sessionStorage.getItem('attendance_date_asrd');
        let shift_group = sessionStorage.getItem('shift_group_asrd');
        let dept = sessionStorage.getItem('dept_asrd');
        let section = sessionStorage.getItem('section_asrd');
        let line_no = sessionStorage.getItem('line_no_asrd');
        window.open('../process/export/exp_attendances_counting2.php?day=' + day + "&shift_group=" + shift_group + "&dept=" + dept + "&section=" + section + "&line_no=" + line_no, '_blank');
    }
</script>