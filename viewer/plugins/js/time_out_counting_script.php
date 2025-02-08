<script type="text/javascript">
    // AJAX IN PROGRESS GLOBAL VARS
    var get_time_out_counting_ajax_in_process = false;
    var get_attendance_list_ajax_in_process = false
    var get_multiple_time_out_counting_ajax_in_process = false;

    var search_multiple_toc_shift_group_arr = [];
    var search_multiple_toc_dept_arr = [];
    var search_multiple_toc_section_arr = [];
    var search_multiple_toc_line_no_arr = [];

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
                $('#dept_search_multiple').html(response);
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
        clear_search_multiple_toc();
        get_time_out_counting(1);
    });

    document.getElementById("shift_group_search").addEventListener('change', e => {
        clear_search_multiple_toc();
        get_time_out_counting(1);
    });

    document.getElementById("dept_search").addEventListener('change', e => {
        clear_search_multiple_toc();
        get_time_out_counting(1);
    });

    document.getElementById("section_search").addEventListener('change', e => {
        clear_search_multiple_toc();
        get_time_out_counting(1);
    });

    document.getElementById("line_no_search").addEventListener('change', e => {
        clear_search_multiple_toc();
        get_time_out_counting(1);
    });

    document.getElementById("btnSearchTimeOutCounting").addEventListener('click', e => {
        clear_search_multiple_toc();
        get_time_out_counting(1);
    });

    const clear_get_time_out_counting = () => {
        document.getElementById('shift_group_search').value = '';
        document.getElementById('dept_search').value = '';
        document.getElementById('section_search').value = '';
        document.getElementById('line_no_search').value = '';
        sessionStorage.setItem('shift_group_search', '');
        sessionStorage.setItem('dept_search', '');
        sessionStorage.setItem('section_search', '');
        sessionStorage.setItem('line_no_search', '');
    }

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
                line_no: line_no,
                search_multiple_toc_shift_group_arr: search_multiple_toc_shift_group_arr,
                search_multiple_toc_dept_arr: search_multiple_toc_dept_arr,
                search_multiple_toc_section_arr: search_multiple_toc_section_arr,
                search_multiple_toc_line_no_arr: search_multiple_toc_line_no_arr
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

                var smtocsg_arr_length = search_multiple_toc_shift_group_arr.length;
                var smtocd_arr_length = search_multiple_toc_dept_arr.length;
                var smtocs_arr_length = search_multiple_toc_section_arr.length;
                var smtocln_arr_length = search_multiple_toc_line_no_arr.length;

                if (smtocsg_arr_length == 0 && smtocd_arr_length == 0 && smtocs_arr_length == 0 && smtocln_arr_length == 0) {
                    $('#multipleDateTimeOutCountingTableRes').html('');
                }

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

    $("#search_multiple_toc").on('show.bs.modal', e => {
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

        check_search_multiple_toc();
    });

    document.getElementById("shift_group_search_multiple_chkbx").addEventListener('change', function() {
        // Toggle the disabled property of the input field
        document.getElementById("shift_group_search_multiple").disabled = !this.checked;
        document.getElementById("btnAddSearchMultipleTocShiftGroup").disabled = !this.checked;

        if (!this.checked) {
            search_multiple_toc_shift_group_arr = [];
            document.getElementById('search_multiple_toc_shift_group_container').innerHTML = '';
        }

        check_search_multiple_toc();
    });

    document.getElementById("dept_search_multiple_chkbx").addEventListener('change', function() {
        // Toggle the disabled property of the input field
        document.getElementById("dept_search_multiple").disabled = !this.checked;
        document.getElementById("btnAddSearchMultipleTocDept").disabled = !this.checked;

        if (!this.checked) {
            search_multiple_toc_dept_arr = [];
            document.getElementById('search_multiple_toc_dept_container').innerHTML = '';
        }

        check_search_multiple_toc();
    });

    document.getElementById("section_search_multiple_chkbx").addEventListener('change', function() {
        // Toggle the disabled property of the input field
        document.getElementById("section_search_multiple").disabled = !this.checked;
        document.getElementById("btnAddSearchMultipleTocSection").disabled = !this.checked;

        if (!this.checked) {
            search_multiple_toc_section_arr = [];
            document.getElementById('search_multiple_toc_section_container').innerHTML = '';
        }

        check_search_multiple_toc();
    });

    document.getElementById("line_no_search_multiple_chkbx").addEventListener('change', function() {
        // Toggle the disabled property of the input field
        document.getElementById("line_no_search_multiple").disabled = !this.checked;
        document.getElementById("btnAddSearchMultipleTocLineNo").disabled = !this.checked;

        if (!this.checked) {
            search_multiple_toc_line_no_arr = [];
            document.getElementById('search_multiple_toc_line_no_container').innerHTML = '';
        }

        check_search_multiple_toc();
    });

    document.getElementById("search_multiple_toc_shift_group_form").addEventListener('submit', e => {
        e.preventDefault();

        // Get the value from the input field
        const shift_group = document.getElementById("shift_group_search_multiple").value.trim();

        // Check if the value already exists in the array
        if (!search_multiple_toc_shift_group_arr.includes(shift_group) && shift_group != '') {
            // Add the employee number to the global array
            search_multiple_toc_shift_group_arr.push(shift_group);

            console.log(search_multiple_toc_shift_group_arr);

            // Create a new card element
            const newCard = document.createElement('div');
            newCard.className = 'card bg-success collapsed-card ml-2';
            newCard.innerHTML = `
                <div class="card-header">
                    <h3 class="card-title">${shift_group}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="remove" onclick="remove_search_multiple_toc_shift_group(this)">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
            `;

            // Append the new card to the container
            document.getElementById('search_multiple_toc_shift_group_container').appendChild(newCard);
        }

        // Clear the input field after adding the card
        document.getElementById("shift_group_search_multiple").value = '';

        check_search_multiple_toc();
    });

    const remove_search_multiple_toc_shift_group = button => {
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
        search_multiple_toc_shift_group_arr = search_multiple_toc_shift_group_arr.filter(sg => sg !== shift_group);

        console.log(search_multiple_toc_shift_group_arr);

        console.log('Removed Search:', shift_group); // You can use this value as needed

        // Remove the card from the DOM
        card.remove();

        check_search_multiple_toc();
    }

    document.getElementById("search_multiple_toc_dept_form").addEventListener('submit', e => {
        e.preventDefault();

        // Get the value from the input field
        const dept = document.getElementById("dept_search_multiple").value.trim();

        // Check if the value already exists in the array
        if (!search_multiple_toc_dept_arr.includes(dept) && dept != '') {
            // Add the employee number to the global array
            search_multiple_toc_dept_arr.push(dept);

            console.log(search_multiple_toc_dept_arr);

            // Create a new card element
            const newCard = document.createElement('div');
            newCard.className = 'card bg-success collapsed-card ml-2';
            newCard.innerHTML = `
                <div class="card-header">
                    <h3 class="card-title">${dept}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="remove" onclick="remove_search_multiple_toc_dept(this)">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
            `;

            // Append the new card to the container
            document.getElementById('search_multiple_toc_dept_container').appendChild(newCard);
        }

        // Clear the input field after adding the card
        document.getElementById("dept_search_multiple").value = '';

        check_search_multiple_toc();
    });

    const remove_search_multiple_toc_dept = button => {
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
        search_multiple_toc_dept_arr = search_multiple_toc_dept_arr.filter(d => d !== dept);

        console.log(search_multiple_toc_dept_arr);

        console.log('Removed Search:', dept); // You can use this value as needed

        // Remove the card from the DOM
        card.remove();

        check_search_multiple_toc();
    }

    document.getElementById("search_multiple_toc_section_form").addEventListener('submit', e => {
        e.preventDefault();

        // Get the value from the input field
        const section = document.getElementById("section_search_multiple").value.trim();

        // Check if the value already exists in the array
        if (!search_multiple_toc_section_arr.includes(section) && section != '') {
            // Add the employee number to the global array
            search_multiple_toc_section_arr.push(section);

            console.log(search_multiple_toc_section_arr);

            // Create a new card element
            const newCard = document.createElement('div');
            newCard.className = 'card bg-success collapsed-card ml-2';
            newCard.innerHTML = `
                <div class="card-header">
                    <h3 class="card-title">${section}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="remove" onclick="remove_search_multiple_toc_section(this)">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
            `;

            // Append the new card to the container
            document.getElementById('search_multiple_toc_section_container').appendChild(newCard);
        }

        // Clear the input field after adding the card
        document.getElementById("section_search_multiple").value = '';

        check_search_multiple_toc();
    });

    const remove_search_multiple_toc_section = button => {
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
        search_multiple_toc_section_arr = search_multiple_toc_section_arr.filter(sec => sec !== section);

        console.log(search_multiple_toc_section_arr);

        console.log('Removed Search:', section); // You can use this value as needed

        // Remove the card from the DOM
        card.remove();

        check_search_multiple_toc();
    }

    document.getElementById("search_multiple_toc_line_no_form").addEventListener('submit', e => {
        e.preventDefault();

        // Get the value from the input field
        const line_no = document.getElementById("line_no_search_multiple").value.trim();

        // Check if the value already exists in the array
        if (!search_multiple_toc_line_no_arr.includes(line_no) && line_no != '') {
            // Add the employee number to the global array
            search_multiple_toc_line_no_arr.push(line_no);

            console.log(search_multiple_toc_line_no_arr);

            // Create a new card element
            const newCard = document.createElement('div');
            newCard.className = 'card bg-success collapsed-card ml-2';
            newCard.innerHTML = `
                <div class="card-header">
                    <h3 class="card-title">${line_no}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="remove" onclick="remove_search_multiple_toc_line_no(this)">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
            `;

            // Append the new card to the container
            document.getElementById('search_multiple_toc_line_no_container').appendChild(newCard);
        }

        // Clear the input field after adding the card
        document.getElementById("line_no_search_multiple").value = '';

        check_search_multiple_toc();
    });

    const remove_search_multiple_toc_line_no = button => {
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
        search_multiple_toc_line_no_arr = search_multiple_toc_line_no_arr.filter(line => line !== line_no);

        console.log(search_multiple_toc_line_no_arr);

        console.log('Removed Search:', line_no); // You can use this value as needed

        // Remove the card from the DOM
        card.remove();

        check_search_multiple_toc();
    }

    const check_search_multiple_toc = () => {
        var smtocsg_arr_length = search_multiple_toc_shift_group_arr.length;
        var smtocd_arr_length = search_multiple_toc_dept_arr.length;
        var smtocs_arr_length = search_multiple_toc_section_arr.length;
        var smtocln_arr_length = search_multiple_toc_line_no_arr.length;

        if (smtocsg_arr_length > 0 || smtocd_arr_length > 0 || smtocs_arr_length > 0 || smtocln_arr_length > 0) {
            document.getElementById("btnSearchMultipleToc").removeAttribute('disabled');
        } else {
            document.getElementById("btnSearchMultipleToc").setAttribute('disabled', true);
        }
    }

    const clear_search_multiple_toc = () => {
        search_multiple_toc_shift_group_arr = [];
        search_multiple_toc_dept_arr = [];
        search_multiple_toc_section_arr = [];
        search_multiple_toc_line_no_arr = [];
        document.getElementById('attendance_date_from_search_multiple').value = '';
        document.getElementById('attendance_date_to_search_multiple').value = '';
        document.getElementById('search_multiple_toc_shift_group_container').innerHTML = '';
        document.getElementById('search_multiple_toc_dept_container').innerHTML = '';
        document.getElementById('search_multiple_toc_section_container').innerHTML = '';
        document.getElementById('search_multiple_toc_line_no_container').innerHTML = '';
        document.getElementById("btnSearchMultipleToc").setAttribute('disabled', true);
    }

    const review_search_multiple_toc = () => {
        // Get the input elements
        const dayFromInput = document.getElementById('attendance_date_from_search_multiple');
        const dayToInput = document.getElementById('attendance_date_to_search_multiple');

        // Get the values from the input elements
        let day_from = dayFromInput.value;
        let day_to = dayToInput.value;

        // Convert the date strings to Date objects for comparison
        const fromDate = new Date(day_from);
        const toDate = new Date(day_to);

        // Check if day_from is greater than day_to
        if (fromDate > toDate) {
            // Swap values
            [day_from, day_to] = [day_to, day_from];

            // Update the DOM elements with the swapped values
            dayFromInput.value = day_from;
            dayToInput.value = day_to;
        }

        clear_get_time_out_counting();

        if (day_from !== day_to) {
            get_multiple_time_out_counting();
        } else {
            $('#multipleDateAttendanceSummaryReportTableRes').html('');
            set_time_out_counting_date(day_from);
        }
    }

    const get_multiple_time_out_counting = () => {
        // If an AJAX call is already in progress, return immediately
        if (get_multiple_time_out_counting_ajax_in_process) {
            return;
        }

        let day_from = document.getElementById('attendance_date_from_search_multiple').value;
        let day_to = document.getElementById('attendance_date_to_search_multiple').value;

        // Set the flag to true as we're starting an AJAX call
        get_multiple_time_out_counting_ajax_in_process = true;

        $.ajax({
            url: '../process/admin/time_in_out/tio_p.php',
            type: 'GET',
            cache: false,
            data: {
                method: 'get_multiple_time_out_counting',
                day_from: day_from,
                day_to: day_to,
                search_multiple_toc_shift_group_arr: search_multiple_toc_shift_group_arr,
                search_multiple_toc_dept_arr: search_multiple_toc_dept_arr,
                search_multiple_toc_section_arr: search_multiple_toc_section_arr,
                search_multiple_toc_line_no_arr: search_multiple_toc_line_no_arr
            },
            beforeSend: (jqXHR, settings) => {
                var loading = `<div class="d-flex justify-content-center align-items-center" id="loading"><div class="spinner-border text-dark" role="status"><span class="sr-only">Loading...</span></div></div>`;
                document.getElementById("multipleDateTimeOutCountingTableRes").innerHTML = loading;
                jqXHR.url = settings.url;
                jqXHR.type = settings.type;
            },
            success: function (response) {
                $('#loading').remove();
                $('#timeOutCountingData').html('');
                $('#multipleDateTimeOutCountingTableRes').html(response);
                // Set the flag back to false as the AJAX call has completed
                get_multiple_time_out_counting_ajax_in_process = false;
            }
        }).fail((jqXHR, textStatus, errorThrown) => {
            console.log(jqXHR);
            console.log(`System Error : Call IT Personnel Immediately!!! They will fix it right away. Error: url: ${jqXHR.url}, method: ${jqXHR.type} ( HTTP ${jqXHR.status} - ${jqXHR.statusText} ) Press F12 to see Console Log for more info.`);
            $('#loading').remove();
            document.getElementById("btnNextPage").removeAttribute('disabled');
            // Set the flag back to false as the AJAX call has completed
            get_multiple_time_out_counting_ajax_in_process = false;
        });
    }

    const set_time_out_counting_date = day => {
        document.getElementById('attendance_date_search').value = day;
        get_time_out_counting(1);
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