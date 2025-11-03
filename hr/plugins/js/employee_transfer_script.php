<script type="text/javascript">
    // AJAX IN PROGRESS GLOBAL VARS
    var get_ongoing_employee_transfer_ajax_in_process = false;
    var get_employee_transfer_history_ajax_in_process = false;

    // DOMContentLoaded function
    document.addEventListener("DOMContentLoaded", () => {
        fetch_dept_dropdown();
        fetch_section_dropdown();
        fetch_line_dropdown();

        get_ongoing_employee_transfer();
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
                document.getElementById('et_dept_from_search').innerHTML = response;
                document.getElementById('eth_dept_from_search').innerHTML = response;
                document.getElementById('et_dept_to_search').innerHTML = response;
                document.getElementById('eth_dept_to_search').innerHTML = response;
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
                document.getElementById('et_section_from_search').innerHTML = response;
                document.getElementById('eth_section_from_search').innerHTML = response;
                document.getElementById('et_section_to_search').innerHTML = response;
                document.getElementById('eth_section_to_search').innerHTML = response;
            }
        });
    }

    const fetch_line_dropdown = () => {
        let section = '';

        $.ajax({
            url: '../process/hr/employees/emp-masterlist_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'fetch_line_dropdown',
                section: section
            },
            success: function (response) {
                document.getElementById('et_line_no_from_search').innerHTML = response;
                document.getElementById('eth_line_no_from_search').innerHTML = response;
                document.getElementById('et_line_no_to_search').innerHTML = response;
                document.getElementById('eth_line_no_to_search').innerHTML = response;
            }
        });
    }

    var typingTimerEtEmpNoSearch; // Timer identifier EmpNo Search
    var typingTimerEtFullNameSearch; // Timer identifier FullName Search
    var doneTypingInterval = 250; // Time in ms

    // On keyup, start the countdown
    document.getElementById("et_emp_no_search").addEventListener('keyup', e => {
        clearTimeout(typingTimerEtEmpNoSearch);
        typingTimerEtEmpNoSearch = setTimeout(doneTypingGetOngoingEmployeeTransfer, doneTypingInterval);
    });

    // On keydown, clear the countdown
    document.getElementById("et_emp_no_search").addEventListener('keydown', e => {
        clearTimeout(typingTimerEtEmpNoSearch);
    });

    // On keyup, start the countdown
    document.getElementById("et_full_name_search").addEventListener('keyup', e => {
        clearTimeout(typingTimerEtFullNameSearch);
        typingTimerEtFullNameSearch = setTimeout(doneTypingGetOngoingEmployeeTransfer, doneTypingInterval);
    });

    // On keydown, clear the countdown
    document.getElementById("et_full_name_search").addEventListener('keydown', e => {
        clearTimeout(typingTimerEtFullNameSearch);
    });

    // User is "finished typing," do something
    const doneTypingGetOngoingEmployeeTransfer = () => {
        get_ongoing_employee_transfer();
    }

    const get_ongoing_employee_transfer = () => {
        // If an AJAX call is already in progress, return immediately
        if (get_ongoing_employee_transfer_ajax_in_process) {
            return;
        }

        var full_name = document.getElementById('et_full_name_search').value;
        var emp_transfer_type = document.getElementById('et_emp_transfer_type_search').value;
        var provider = document.getElementById('et_provider_search').value;
        var dept_from = document.getElementById('et_dept_from_search').value;
        var section_from = document.getElementById('et_section_from_search').value;
        var line_no_from = document.getElementById('et_line_no_from_search').value;
        var emp_no = document.getElementById('et_emp_no_search').value;
        var position = document.getElementById('et_position_search').value;
        var dept_to = document.getElementById('et_dept_to_search').value;
        var section_to = document.getElementById('et_section_to_search').value;
        var line_no_to = document.getElementById('et_line_no_to_search').value;

        let is_checked_by = 0; // Default value for Option 1
        let is_approved_by = 0; // Default value for Option 2
        let is_receiving_noted_by = 0; // Default value for Option 3
        let is_receiving_acknowledged_by = 0; // Default value for Option 4
        let is_receiving_approved_by = 0; // Default value for Option 5

        if (document.getElementById('et_checked_by_search').checked) {
            is_checked_by = 1;
        }
        if (document.getElementById('et_approved_by_search').checked) {
            is_approved_by = 1;
        }
        if (document.getElementById('et_receiving_noted_by_search').checked) {
            is_receiving_noted_by = 1;
        }
        if (document.getElementById('et_receiving_acknowledged_by_search').checked) {
            is_receiving_acknowledged_by = 1;
        }
        if (document.getElementById('et_receiving_approved_by_search').checked) {
            is_receiving_approved_by = 1;
        }
        
        // Set the flag to true as we're starting an AJAX call
        get_ongoing_employee_transfer_ajax_in_process = true;

        $.ajax({
            url: '../process/hr/employee_transfer/et_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'get_ongoing_employee_transfer',
                full_name: full_name,
                emp_transfer_type: emp_transfer_type,
                provider: provider,
                dept_from: dept_from,
                section_from: section_from, 
                line_no_from: line_no_from,
                emp_no: emp_no,
                position: position,
                dept_to: dept_to,
                section_to: section_to, 
                line_no_to: line_no_to,
                is_checked_by: is_checked_by,
                is_approved_by: is_approved_by,
                is_receiving_noted_by: is_receiving_noted_by,
                is_receiving_acknowledged_by: is_receiving_acknowledged_by,
                is_receiving_approved_by: is_receiving_approved_by
            },
            beforeSend: (jqXHR, settings) => {
                jqXHR.url = settings.url;
                jqXHR.type = settings.type;
            },
            success: function (response) {
                $('#et_table tbody').html(response);
                let table_rows = parseInt(document.getElementById("et_data").childNodes.length);
				$('#count_view').html("Total: " + table_rows);

                sessionStorage.setItem('et_full_name_search', full_name);
                sessionStorage.setItem('et_emp_transfer_type_search', emp_transfer_type);
                sessionStorage.setItem('et_provider_search', provider);
                sessionStorage.setItem('et_line_no_from_search', line_no_from);
                sessionStorage.setItem('et_emp_no_search', emp_no);
                sessionStorage.setItem('et_position_search', position);
                sessionStorage.setItem('et_dept_to_search', dept_to);
                sessionStorage.setItem('et_section_to_search', section_to);
                sessionStorage.setItem('et_line_no_to_search', line_no_to);
                sessionStorage.setItem('et_checked_by_search', is_checked_by);
                sessionStorage.setItem('et_approved_by_search', is_approved_by);
                sessionStorage.setItem('et_receiving_noted_by_search', is_receiving_noted_by);
                sessionStorage.setItem('et_receiving_acknowledged_by_search', is_receiving_acknowledged_by);
                sessionStorage.setItem('et_receiving_approved_by_search', is_receiving_approved_by);

                // Set the flag back to false as the AJAX call has completed
                get_ongoing_employee_transfer_ajax_in_process = false;
            }
        }).fail((jqXHR, textStatus, errorThrown) => {
            console.log(jqXHR);
            console.log(`System Error : Call IT Personnel Immediately!!! They will fix it right away. Error: url: ${jqXHR.url}, method: ${jqXHR.type} ( HTTP ${jqXHR.status} - ${jqXHR.statusText} ) Press F12 to see Console Log for more info.`);
            // Set the flag back to false as the AJAX call has completed
            get_ongoing_employee_transfer_ajax_in_process = false;
        });
    }

    const get_employee_transfer_history = () => {
        // If an AJAX call is already in progress, return immediately
        if (get_employee_transfer_history_ajax_in_process) {
            return;
        }

        var full_name = document.getElementById('et_full_name_search').value;
        var emp_transfer_type = document.getElementById('et_emp_transfer_type_search').value;
        var provider = document.getElementById('et_provider_search').value;
        var dept_from = document.getElementById('et_dept_from_search').value;
        var section_from = document.getElementById('et_section_from_search').value;
        var line_no_from = document.getElementById('et_line_no_from_search').value;
        var emp_no = document.getElementById('et_emp_no_search').value;
        var position = document.getElementById('et_position_search').value;
        var dept_to = document.getElementById('et_dept_to_search').value;
        var section_to = document.getElementById('et_section_to_search').value;
        var line_no_to = document.getElementById('et_line_no_to_search').value;

        let is_checked_by = 0; // Default value for Option 1
        let is_approved_by = 0; // Default value for Option 2
        let is_receiving_noted_by = 0; // Default value for Option 3
        let is_receiving_acknowledged_by = 0; // Default value for Option 4
        let is_receiving_approved_by = 0; // Default value for Option 5

        if (document.getElementById('et_checked_by_search').checked) {
            is_checked_by = 1;
        }
        if (document.getElementById('et_approved_by_search').checked) {
            is_approved_by = 1;
        }
        if (document.getElementById('et_receiving_noted_by_search').checked) {
            is_receiving_noted_by = 1;
        }
        if (document.getElementById('et_receiving_acknowledged_by_search').checked) {
            is_receiving_acknowledged_by = 1;
        }
        if (document.getElementById('et_receiving_approved_by_search').checked) {
            is_receiving_approved_by = 1;
        }
        
        // Set the flag to true as we're starting an AJAX call
        get_employee_transfer_history_ajax_in_process = true;

        $.ajax({
            url: '../process/hr/employee_transfer/et_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'get_employee_transfer_history',
                full_name: full_name,
                emp_transfer_type: emp_transfer_type,
                provider: provider,
                dept_from: dept_from,
                section_from: section_from, 
                line_no_from: line_no_from,
                emp_no: emp_no,
                position: position,
                dept_to: dept_to,
                section_to: section_to, 
                line_no_to: line_no_to,
                is_checked_by: is_checked_by,
                is_approved_by: is_approved_by,
                is_receiving_noted_by: is_receiving_noted_by,
                is_receiving_acknowledged_by: is_receiving_acknowledged_by,
                is_receiving_approved_by: is_receiving_approved_by
            },
            beforeSend: (jqXHR, settings) => {
                jqXHR.url = settings.url;
                jqXHR.type = settings.type;
            },
            success: function (response) {
                $('#eth_table tbody').html(response);
                let table_rows = parseInt(document.getElementById("eth_data").childNodes.length);
				$('#count_view2').html("Total: " + table_rows);

                sessionStorage.setItem('eth_full_name_search', full_name);
                sessionStorage.setItem('eth_emp_transfer_type_search', emp_transfer_type);
                sessionStorage.setItem('eth_provider_search', provider);
                sessionStorage.setItem('eth_line_no_from_search', line_no_from);
                sessionStorage.setItem('eth_emp_no_search', emp_no);
                sessionStorage.setItem('eth_position_search', position);
                sessionStorage.setItem('eth_dept_to_search', dept_to);
                sessionStorage.setItem('eth_section_to_search', section_to);
                sessionStorage.setItem('eth_line_no_to_search', line_no_to);
                sessionStorage.setItem('eth_checked_by_search', is_checked_by);
                sessionStorage.setItem('eth_approved_by_search', is_approved_by);
                sessionStorage.setItem('eth_receiving_noted_by_search', is_receiving_noted_by);
                sessionStorage.setItem('eth_receiving_acknowledged_by_search', is_receiving_acknowledged_by);
                sessionStorage.setItem('eth_receiving_approved_by_search', is_receiving_approved_by);

                // Set the flag back to false as the AJAX call has completed
                get_employee_transfer_history_ajax_in_process = false;
            }
        }).fail((jqXHR, textStatus, errorThrown) => {
            console.log(jqXHR);
            console.log(`System Error : Call IT Personnel Immediately!!! They will fix it right away. Error: url: ${jqXHR.url}, method: ${jqXHR.type} ( HTTP ${jqXHR.status} - ${jqXHR.statusText} ) Press F12 to see Console Log for more info.`);
            // Set the flag back to false as the AJAX call has completed
            get_employee_transfer_history_ajax_in_process = false;
        });
    }
</script>