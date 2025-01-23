<script type="text/javascript">
    // AJAX IN PROGRESS GLOBAL VARS
    var get_attendance_summary_report_ajax_in_process = false;
    var get_attendance_list_ajax_in_process = false;
    var get_multiple_attendance_summary_report_ajax_in_process = false;

    var search_multiple_asr_shift_group_arr = [];
    var search_multiple_asr_dept_arr = [];
    var search_multiple_asr_section_arr = [];
    var search_multiple_asr_line_no_arr = [];

    // DOMContentLoaded function
    document.addEventListener("DOMContentLoaded", () => {
        fetch_dept_dropdown();
        // fetch_group_dropdown();
        fetch_section_dropdown();
        fetch_line_dropdown();
        document.getElementById('attendance_date_search').value = '<?= $server_date_only ?>';
        get_attendance_summary_report(1);
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
                $('#dept_search_multiple').html(response);
            }
        });
    }

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
                $('#section_search_multiple').html(response);
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
                $('#line_no_search_multiple').html(response);
            }
        });
    }

    document.getElementById("attendance_date_search").addEventListener('change', e => {
        clear_search_multiple_asr();
        get_attendance_summary_report(1);
    });

    document.getElementById("shift_group_search").addEventListener('change', e => {
        clear_search_multiple_asr();
        get_attendance_summary_report(1);
    });

    document.getElementById("dept_search").addEventListener('change', e => {
        clear_search_multiple_asr();
        get_attendance_summary_report(1);
    });

    document.getElementById("section_search").addEventListener('change', e => {
        clear_search_multiple_asr();
        get_attendance_summary_report(1);
    });

    document.getElementById("line_no_search").addEventListener('change', e => {
        clear_search_multiple_asr();
        get_attendance_summary_report(1);
    });

    document.getElementById("btnSearchAttendanceSummaryReport").addEventListener('click', e => {
        clear_search_multiple_asr();
        get_attendance_summary_report(1);
    });

    const clear_get_attendance_summary_report = () => {
        document.getElementById('shift_group_search').value = '';
        document.getElementById('dept_search').value = '';
        document.getElementById('section_search').value = '';
        document.getElementById('line_no_search').value = '';
        sessionStorage.setItem('shift_group_search', '');
        sessionStorage.setItem('dept_search', '');
        sessionStorage.setItem('section_search', '');
        sessionStorage.setItem('line_no_search', '');
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
                current_page: current_page,
                search_multiple_asr_shift_group_arr: search_multiple_asr_shift_group_arr,
                search_multiple_asr_dept_arr: search_multiple_asr_dept_arr,
                search_multiple_asr_section_arr: search_multiple_asr_section_arr,
                search_multiple_asr_line_no_arr: search_multiple_asr_line_no_arr
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

                var smasrsg_arr_length = search_multiple_asr_shift_group_arr.length;
                var smasrd_arr_length = search_multiple_asr_dept_arr.length;
                var smasrs_arr_length = search_multiple_asr_section_arr.length;
                var smasrln_arr_length = search_multiple_asr_line_no_arr.length;

                if (smasrsg_arr_length == 0 && smasrd_arr_length == 0 && smasrs_arr_length == 0 && smasrln_arr_length == 0) {
                    $('#multipleDateAttendanceSummaryReportTableRes').html('');
                }

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
    
    $("#search_multiple_asr").on('show.bs.modal', e => {
        var attendance_date = document.getElementById("attendance_date_search").value;
        let day_from = document.getElementById('attendance_date_from_search_multiple').value;
        let day_to = document.getElementById('attendance_date_to_search_multiple').value;

        if (day_from == '' && day_to == '') {
            document.getElementById("attendance_date_from_search_multiple").value = attendance_date;
            document.getElementById("attendance_date_to_search_multiple").value = attendance_date;
        }
    });

    document.getElementById("attendance_date_search_multiple_chkbx").addEventListener('change', function() {
        // Toggle the disabled property of the input field
        document.getElementById("attendance_date_from_search_multiple").disabled = !this.checked;
        document.getElementById("attendance_date_to_search_multiple").disabled = !this.checked;

        if (!this.checked) {
            var day_from = document.getElementById('attendance_date_from_search_multiple').value;
            document.getElementById('attendance_date_to_search_multiple').value = day_from;
        }

        check_search_multiple_asr();
    });

    document.getElementById("shift_group_search_multiple_chkbx").addEventListener('change', function() {
        // Toggle the disabled property of the input field
        document.getElementById("shift_group_search_multiple").disabled = !this.checked;
        document.getElementById("btnAddSearchMultipleAsrShiftGroup").disabled = !this.checked;

        if (!this.checked) {
            search_multiple_asr_shift_group_arr = [];
            document.getElementById('search_multiple_asr_shift_group_container').innerHTML = '';
        }

        check_search_multiple_asr();
    });

    document.getElementById("dept_search_multiple_chkbx").addEventListener('change', function() {
        // Toggle the disabled property of the input field
        document.getElementById("dept_search_multiple").disabled = !this.checked;
        document.getElementById("btnAddSearchMultipleAsrDept").disabled = !this.checked;

        if (!this.checked) {
            search_multiple_asr_dept_arr = [];
            document.getElementById('search_multiple_asr_dept_container').innerHTML = '';
        }

        check_search_multiple_asr();
    });

    document.getElementById("section_search_multiple_chkbx").addEventListener('change', function() {
        // Toggle the disabled property of the input field
        document.getElementById("section_search_multiple").disabled = !this.checked;
        document.getElementById("btnAddSearchMultipleAsrSection").disabled = !this.checked;

        if (!this.checked) {
            search_multiple_asr_section_arr = [];
            document.getElementById('search_multiple_asr_section_container').innerHTML = '';
        }

        check_search_multiple_asr();
    });

    document.getElementById("line_no_search_multiple_chkbx").addEventListener('change', function() {
        // Toggle the disabled property of the input field
        document.getElementById("line_no_search_multiple").disabled = !this.checked;
        document.getElementById("btnAddSearchMultipleAsrLineNo").disabled = !this.checked;

        if (!this.checked) {
            search_multiple_asr_line_no_arr = [];
            document.getElementById('search_multiple_asr_line_no_container').innerHTML = '';
        }

        check_search_multiple_asr();
    });

    document.getElementById("search_multiple_asr_shift_group_form").addEventListener('submit', e => {
        e.preventDefault();

        // Get the value from the input field
        const shift_group = document.getElementById("shift_group_search_multiple").value.trim();

        // Check if the value already exists in the array
        if (!search_multiple_asr_shift_group_arr.includes(shift_group) && shift_group != '') {
            // Add the employee number to the global array
            search_multiple_asr_shift_group_arr.push(shift_group);

            console.log(search_multiple_asr_shift_group_arr);

            // Create a new card element
            const newCard = document.createElement('div');
            newCard.className = 'card bg-success collapsed-card ml-2';
            newCard.innerHTML = `
                <div class="card-header">
                    <h3 class="card-title">${shift_group}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="remove" onclick="remove_search_multiple_asr_shift_group(this)">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
            `;

            // Append the new card to the container
            document.getElementById('search_multiple_asr_shift_group_container').appendChild(newCard);
        }

        // Clear the input field after adding the card
        document.getElementById("shift_group_search_multiple").value = '';

        check_search_multiple_asr();
    });

    const remove_search_multiple_asr_shift_group = button => {
        // Check if button is defined
        if (!button) {
            console.error('Button is undefined');
            return;
        }

        // Find the closest card div to the button that was clicked
        const card = button.closest('.card');

        // Check if card is found
        if (!card) {
            console.error('Card not found');
            return;
        }
        
        // Get the innerHTML of the card-title
        const shift_group = card.querySelector('.card-title').innerHTML;

        // Remove the employee number from the global array
        search_multiple_asr_shift_group_arr = search_multiple_asr_shift_group_arr.filter(sg => sg !== shift_group);

        console.log(search_multiple_asr_shift_group_arr);

        console.log('Removed Search:', shift_group); // You can use this value as needed

        // Remove the card from the DOM
        card.remove();

        check_search_multiple_asr();
    }

    document.getElementById("search_multiple_asr_dept_form").addEventListener('submit', e => {
        e.preventDefault();

        // Get the value from the input field
        const dept = document.getElementById("dept_search_multiple").value.trim();

        // Check if the value already exists in the array
        if (!search_multiple_asr_dept_arr.includes(dept) && dept != '') {
            // Add the employee number to the global array
            search_multiple_asr_dept_arr.push(dept);

            console.log(search_multiple_asr_dept_arr);

            // Create a new card element
            const newCard = document.createElement('div');
            newCard.className = 'card bg-success collapsed-card ml-2';
            newCard.innerHTML = `
                <div class="card-header">
                    <h3 class="card-title">${dept}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="remove" onclick="remove_search_multiple_asr_dept(this)">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
            `;

            // Append the new card to the container
            document.getElementById('search_multiple_asr_dept_container').appendChild(newCard);
        }

        // Clear the input field after adding the card
        document.getElementById("dept_search_multiple").value = '';

        check_search_multiple_asr();
    });

    const remove_search_multiple_asr_dept = button => {
        // Check if button is defined
        if (!button) {
            console.error('Button is undefined');
            return;
        }

        // Find the closest card div to the button that was clicked
        const card = button.closest('.card');

        // Check if card is found
        if (!card) {
            console.error('Card not found');
            return;
        }
        
        // Get the innerHTML of the card-title
        const dept = card.querySelector('.card-title').innerHTML;

        // Remove the employee number from the global array
        search_multiple_asr_dept_arr = search_multiple_asr_dept_arr.filter(d => d !== dept);

        console.log(search_multiple_asr_dept_arr);

        console.log('Removed Search:', dept); // You can use this value as needed

        // Remove the card from the DOM
        card.remove();

        check_search_multiple_asr();
    }

    document.getElementById("search_multiple_asr_section_form").addEventListener('submit', e => {
        e.preventDefault();

        // Get the value from the input field
        const section = document.getElementById("section_search_multiple").value.trim();

        // Check if the value already exists in the array
        if (!search_multiple_asr_section_arr.includes(section) && section != '') {
            // Add the employee number to the global array
            search_multiple_asr_section_arr.push(section);

            console.log(search_multiple_asr_section_arr);

            // Create a new card element
            const newCard = document.createElement('div');
            newCard.className = 'card bg-success collapsed-card ml-2';
            newCard.innerHTML = `
                <div class="card-header">
                    <h3 class="card-title">${section}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="remove" onclick="remove_search_multiple_asr_section(this)">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
            `;

            // Append the new card to the container
            document.getElementById('search_multiple_asr_section_container').appendChild(newCard);
        }

        // Clear the input field after adding the card
        document.getElementById("section_search_multiple").value = '';

        check_search_multiple_asr();
    });

    const remove_search_multiple_asr_section = button => {
        // Check if button is defined
        if (!button) {
            console.error('Button is undefined');
            return;
        }

        // Find the closest card div to the button that was clicked
        const card = button.closest('.card');

        // Check if card is found
        if (!card) {
            console.error('Card not found');
            return;
        }
        
        // Get the innerHTML of the card-title
        const section = card.querySelector('.card-title').innerHTML;

        // Remove the employee number from the global array
        search_multiple_asr_section_arr = search_multiple_asr_section_arr.filter(sec => sec !== section);

        console.log(search_multiple_asr_section_arr);

        console.log('Removed Search:', section); // You can use this value as needed

        // Remove the card from the DOM
        card.remove();

        check_search_multiple_asr();
    }

    document.getElementById("search_multiple_asr_line_no_form").addEventListener('submit', e => {
        e.preventDefault();

        // Get the value from the input field
        const line_no = document.getElementById("line_no_search_multiple").value.trim();

        // Check if the value already exists in the array
        if (!search_multiple_asr_line_no_arr.includes(line_no) && line_no != '') {
            // Add the employee number to the global array
            search_multiple_asr_line_no_arr.push(line_no);

            console.log(search_multiple_asr_line_no_arr);

            // Create a new card element
            const newCard = document.createElement('div');
            newCard.className = 'card bg-success collapsed-card ml-2';
            newCard.innerHTML = `
                <div class="card-header">
                    <h3 class="card-title">${line_no}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="remove" onclick="remove_search_multiple_asr_line_no(this)">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
            `;

            // Append the new card to the container
            document.getElementById('search_multiple_asr_line_no_container').appendChild(newCard);
        }

        // Clear the input field after adding the card
        document.getElementById("line_no_search_multiple").value = '';

        check_search_multiple_asr();
    });

    const remove_search_multiple_asr_line_no = button => {
        // Check if button is defined
        if (!button) {
            console.error('Button is undefined');
            return;
        }

        // Find the closest card div to the button that was clicked
        const card = button.closest('.card');

        // Check if card is found
        if (!card) {
            console.error('Card not found');
            return;
        }
        
        // Get the innerHTML of the card-title
        const line_no = card.querySelector('.card-title').innerHTML;

        // Remove the employee number from the global array
        search_multiple_asr_line_no_arr = search_multiple_asr_line_no_arr.filter(line => line !== line_no);

        console.log(search_multiple_asr_line_no_arr);

        console.log('Removed Search:', line_no); // You can use this value as needed

        // Remove the card from the DOM
        card.remove();

        check_search_multiple_asr();
    }

    const check_search_multiple_asr = () => {
        var smasrsg_arr_length = search_multiple_asr_shift_group_arr.length;
        var smasrd_arr_length = search_multiple_asr_dept_arr.length;
        var smasrs_arr_length = search_multiple_asr_section_arr.length;
        var smasrln_arr_length = search_multiple_asr_line_no_arr.length;

        if (smasrsg_arr_length > 0 || smasrd_arr_length > 0 || smasrs_arr_length > 0 || smasrln_arr_length > 0) {
            document.getElementById("btnSearchMultipleAsr").removeAttribute('disabled');
        } else {
            document.getElementById("btnSearchMultipleAsr").setAttribute('disabled', true);
        }
    }

    const clear_search_multiple_asr = () => {
        search_multiple_asr_shift_group_arr = [];
        search_multiple_asr_dept_arr = [];
        search_multiple_asr_section_arr = [];
        search_multiple_asr_line_no_arr = [];
        document.getElementById('attendance_date_from_search_multiple').value = '';
        document.getElementById('attendance_date_to_search_multiple').value = '';
        document.getElementById('search_multiple_asr_shift_group_container').innerHTML = '';
        document.getElementById('search_multiple_asr_dept_container').innerHTML = '';
        document.getElementById('search_multiple_asr_section_container').innerHTML = '';
        document.getElementById('search_multiple_asr_line_no_container').innerHTML = '';
        document.getElementById("btnSearchMultipleAsr").setAttribute('disabled', true);
    }

    const review_search_multiple_asr = () => {
        // Code for checking date here
        let day_from = document.getElementById('attendance_date_from_search_multiple').value;
        let day_to = document.getElementById('attendance_date_to_search_multiple').value;

        clear_get_attendance_summary_report();

        if (day_from != day_to) {
            get_multiple_attendance_summary_report();
        } else {
            set_attendance_summary_report_date(day_from);
        }
    }

    const get_multiple_attendance_summary_report = () => {
        // If an AJAX call is already in progress, return immediately
        if (get_multiple_attendance_summary_report_ajax_in_process) {
            return;
        }

        let day_from = document.getElementById('attendance_date_from_search_multiple').value;
        let day_to = document.getElementById('attendance_date_to_search_multiple').value;

        // Set the flag to true as we're starting an AJAX call
        get_multiple_attendance_summary_report_ajax_in_process = true;

        $.ajax({
            url: '../process/admin/attendances/at_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'get_multiple_attendance_summary_report',
                day_from: day_from,
                day_to: day_to,
                search_multiple_asr_shift_group_arr: search_multiple_asr_shift_group_arr,
                search_multiple_asr_dept_arr: search_multiple_asr_dept_arr,
                search_multiple_asr_section_arr: search_multiple_asr_section_arr,
                search_multiple_asr_line_no_arr: search_multiple_asr_line_no_arr
            },
            beforeSend: (jqXHR, settings) => {
                var loading = `<div class="d-flex justify-content-center align-items-center" id="loading"><div class="spinner-border text-dark" role="status"><span class="sr-only">Loading...</span></div></div>`;
                document.getElementById("multipleDateAttendanceSummaryReportTableRes").innerHTML = loading;
                jqXHR.url = settings.url;
                jqXHR.type = settings.type;
            },
            success: function (response) {
                $('#loading').remove();
                $('#attendanceSummaryReportData').html('');
                $('#multipleDateAttendanceSummaryReportTableRes').html(response);
                // Set the flag back to false as the AJAX call has completed
                get_multiple_attendance_summary_report_ajax_in_process = false;
            }
        }).fail((jqXHR, textStatus, errorThrown) => {
            console.log(jqXHR);
            console.log(`System Error : Call IT Personnel Immediately!!! They will fix it right away. Error: url: ${jqXHR.url}, method: ${jqXHR.type} ( HTTP ${jqXHR.status} - ${jqXHR.statusText} ) Press F12 to see Console Log for more info.`);
            $('#loading').remove();
            document.getElementById("btnNextPage").removeAttribute('disabled');
            // Set the flag back to false as the AJAX call has completed
            get_multiple_attendance_summary_report_ajax_in_process = false;
        });
    }

    const set_attendance_summary_report_date = day => {
        document.getElementById('attendance_date_search').value = day;
        get_attendance_summary_report(1);
    }

    const export_attendances_all = () => {
        let day = sessionStorage.getItem('attendance_date_search');
        window.open('../process/export/exp_attendances_all.php?day=' + day, '_blank');
    }

    const export_attendance_summary_report = () => {
        let day = sessionStorage.getItem('attendance_date_search');
        let shift_group = sessionStorage.getItem('shift_group_search');
        let dept = sessionStorage.getItem('dept_search');
        let section = sessionStorage.getItem('section_search');
        let line_no = sessionStorage.getItem('line_no_search');

        var smasrsg_arr_length = search_multiple_asr_shift_group_arr.length;
        var search_multiple_asr_shift_group_obj = '';
        if (smasrsg_arr_length > 0) {
            search_multiple_asr_shift_group_obj = Object.values(search_multiple_asr_shift_group_arr);
        }
        console.log(search_multiple_asr_shift_group_obj);

        var smasrd_arr_length = search_multiple_asr_dept_arr.length;
        var search_multiple_asr_dept_obj = '';
        if (smasrd_arr_length > 0) {
            search_multiple_asr_dept_obj = Object.values(search_multiple_asr_dept_arr);
        }
        console.log(search_multiple_asr_dept_obj);

        var smasrs_arr_length = search_multiple_asr_section_arr.length;
        var search_multiple_asr_section_obj = '';
        if (smasrs_arr_length > 0) {
            search_multiple_asr_section_obj = Object.values(search_multiple_asr_section_arr);
        }
        console.log(search_multiple_asr_section_obj);

        var smasrln_arr_length = search_multiple_asr_line_no_arr.length;
        var search_multiple_asr_line_no_obj = '';
        if (smasrln_arr_length > 0) {
            search_multiple_asr_line_no_obj = Object.values(search_multiple_asr_line_no_arr);
        }
        console.log(search_multiple_asr_line_no_obj);

        window.open('../process/export/exp_attendance_summary_report.php?day=' + day 
                    + "&shift_group=" + shift_group 
                    + "&dept=" + dept 
                    + "&section=" + section 
                    + "&line_no=" + line_no 
                    + "&search_multiple_asr_shift_group_arr=" + search_multiple_asr_shift_group_obj 
                    + "&search_multiple_asr_dept_arr=" + search_multiple_asr_dept_obj 
                    + "&search_multiple_asr_section_arr=" + search_multiple_asr_section_obj 
                    + "&search_multiple_asr_line_no_arr=" + search_multiple_asr_line_no_obj, '_blank');
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
                method: 'get_attendance_list_counting',
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
        window.open('../process/export/exp_attendances_counting.php?day=' + day + "&shift_group=" + shift_group + "&dept=" + dept + "&section=" + section + "&line_no=" + line_no, '_blank');
    }
</script>