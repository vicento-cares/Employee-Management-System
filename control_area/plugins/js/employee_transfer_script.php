<script type="text/javascript">
    // AJAX IN PROGRESS GLOBAL VARS
    var get_ongoing_employee_transfer_ajax_in_process = false;

    // DOMContentLoaded function
    document.addEventListener("DOMContentLoaded", () => {
        fetch_dept_dropdown();
        fetch_section_dropdown();
        fetch_line_dropdown();

        get_ongoing_employee_transfer(1);
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
                document.getElementById('et_dept').innerHTML = response;
                document.getElementById('et_dept_to_search').innerHTML = response;
                document.getElementById('et_dept_update').innerHTML = response;
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
                document.getElementById('et_section').innerHTML = response;
                document.getElementById('et_section_to_search').innerHTML = response;
                document.getElementById('et_section_update').innerHTML = response;
            }
        });
    }

    const fetch_line_dropdown = opt => {
        let section = '';

        if (opt == 1) {
            section = document.getElementById('et_section').value;
        } else if (opt == 2) {
            section = document.getElementById('et_section_update').value;
        }

        $.ajax({
            url: '../process/hr/employees/emp-masterlist_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'fetch_line_dropdown',
                section: section
            },
            success: function (response) {
                if (opt == 1) {
                    document.getElementById('et_line_no').innerHTML = response;
                } else if (opt == 2) {
                    document.getElementById('et_line_no_update').innerHTML = response;
                } else {
                    document.getElementById('et_line_no').innerHTML = response;
                    document.getElementById('et_line_no_from_search').innerHTML = response;
                    document.getElementById('et_line_no_to_search').innerHTML = response;
                    document.getElementById('et_line_no_update').innerHTML = response;
                }
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

    const get_employee_transfer_details = el => {
        var id = el.dataset.id;
        var emp_no = el.dataset.emp_no;
        var emp_transfer_type = el.dataset.emp_transfer_type;
        var dept_to = el.dataset.dept_to;
        var section_to = el.dataset.section_to;
        var line_no_to = el.dataset.line_no_to;
        var date_effectivity = el.dataset.date_effectivity;
        var reason = el.dataset.reason;

        document.getElementById("et_id_update").value = id;
        document.getElementById("et_emp_no_update").value = emp_no;
        document.getElementById("et_emp_transfer_type_update").value = emp_transfer_type;
        document.getElementById("et_dept_update").value = dept_to;
        document.getElementById("et_section_update").value = section_to;
        document.getElementById("et_line_no_update").value = line_no_to;
        document.getElementById("et_date_effectivity_update").value = date_effectivity;
        document.getElementById("et_reason_update").value = reason;
    }

    $("#update_employee_transfer").on('shown.bs.modal', e => {
        load_et_reason_update_textarea();
    });

    const load_et_reason_update_textarea = () => {
        setTimeout(() => {
            var max_length = document.getElementById("et_reason_update").getAttribute("maxlength");
            var et_reason_length = document.getElementById("et_reason_update").value.length;
            var et_reason_count = `${et_reason_length} / ${max_length}`;
            document.getElementById("et_reason_update_count").innerHTML = et_reason_count;
        }, 100);
    }

    const count_et_reason_update_char = () => {
        var max_length = document.getElementById("et_reason_update").getAttribute("maxlength");
        var et_reason_length = document.getElementById("et_reason_update").value.length;
        var et_reason_count = `${et_reason_length} / ${max_length}`;
        document.getElementById("et_reason_update_count").innerHTML = et_reason_count;
    }

    // Get the form element
    var update_employee_transfer_form = document.getElementById('update_employee_transfer_form');

    // Add a submit event listener to the form
    update_employee_transfer_form.addEventListener('submit', e => {
        e.preventDefault();

        // Get the button that triggered the submit event
        var button = document.activeElement;

        // Check the id or name of the button
        if (button.id === 'btnUpdateEmployeeTransfer') {
            // Call the function for the first submit button
            update_employee_transfer();
        } else if (button.id === 'btnCancelEmployeeTransfer') {
            // Call the function for the first submit button
            cancel_employee_transfer();
        }
    });

    const update_employee_transfer = () => {
        var id = document.getElementById('et_id_emp_no_update').value;
        var dept = document.getElementById('et_dept_update').value;
        var section = document.getElementById('et_section_update').value;
        var line_no = document.getElementById('et_line_no_update').value;
        var date_effectivity = document.getElementById('et_date_effectivity_update').value;
        var reason = document.getElementById('et_reason_update').value;

        $.ajax({
            url: '../process/hr/employee_transfer/et_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'update_employee_transfer',
                id: id,
                dept: dept,
                section: section,
                line_no: line_no,
                date_effectivity: date_effectivity,
                reason: reason
            }, success: function (response) {
                if (response == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Succesfully Updated!!!',
                        text: 'Success',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    document.getElementById('et_id_update').value = '';
                    document.getElementById('et_emp_no_update').value = '';
                    document.getElementById('et_emp_transfer_type_update').value = '';
                    document.getElementById('et_dept_update').value = '';
                    document.getElementById('et_section_update').value = '';
                    document.getElementById('et_line_no_update').value = '';
                    document.getElementById('et_date_effectivity_update').value = '';
                    document.getElementById('et_reason_update').value = '';
                    get_ongoing_employee_transfer();
                    $('#update_employee_transfer').modal('hide');
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

    const cancel_employee_transfer = () => {
        var id = document.getElementById('et_id_emp_no_update').value;

        $.ajax({
            url: '../process/hr/employee_transfer/et_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'cancel_employee_transfer',
                id: id
            }, success: function (response) {
                if (response == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Succesfully Cancelled!!!',
                        text: 'Success',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    document.getElementById('et_id_update').value = '';
                    document.getElementById('et_emp_no_update').value = '';
                    document.getElementById('et_emp_transfer_type_update').value = '';
                    document.getElementById('et_dept_update').value = '';
                    document.getElementById('et_section_update').value = '';
                    document.getElementById('et_line_no_update').value = '';
                    document.getElementById('et_date_effectivity_update').value = '';
                    document.getElementById('et_reason_update').value = '';
                    get_ongoing_employee_transfer();
                    $('#update_employee_transfer').modal('hide');
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

    $("#new_employee_transfer").on('show.bs.modal', e => {
        load_et_reason_textarea();
    });

    const load_et_reason_textarea = () => {
        setTimeout(() => {
            var max_length = document.getElementById("et_reason").getAttribute("maxlength");
            var et_reason_length = document.getElementById("et_reason").value.length;
            var et_reason_count = `${et_reason_length} / ${max_length}`;
            document.getElementById("et_reason_count").innerHTML = et_reason_count;
        }, 100);
    }

    const count_et_reason_char = () => {
        var max_length = document.getElementById("et_reason").getAttribute("maxlength");
        var et_reason_length = document.getElementById("et_reason").value.length;
        var et_reason_count = `${et_reason_length} / ${max_length}`;
        document.getElementById("et_reason_count").innerHTML = et_reason_count;
    }
    
    document.getElementById('new_employee_transfer_form').addEventListener('submit', e => {
        e.preventDefault();
        submit_employee_transfer();
    });

    const submit_employee_transfer = () => {
        var emp_no = document.getElementById('et_emp_no').value;
        var emp_transfer_type = document.getElementById('et_emp_transfer_type').value;
        var dept = document.getElementById('et_dept').value;
        var section = document.getElementById('et_section').value;
        var line_no = document.getElementById('et_line_no').value;
        var date_effectivity = document.getElementById('et_date_effectivity').value;
        var reason = document.getElementById('et_reason').value;

        $.ajax({
            url: '../process/hr/employee_transfer/et_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'submit_employee_transfer',
                emp_no: emp_no,
                emp_transfer_type: emp_transfer_type,
                dept: dept,
                section: section,
                line_no: line_no,
                date_effectivity: date_effectivity,
                reason: reason
            }, success: function (response) {
                if (response == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Succesfully Added!!!',
                        text: 'Success',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    document.getElementById('et_emp_no').value = '';
                    document.getElementById('et_emp_transfer_type').value = '';
                    document.getElementById('et_dept').value = '';
                    document.getElementById('et_section').value = '';
                    document.getElementById('et_line_no').value = '';
                    document.getElementById('et_date_effectivity').value = '';
                    document.getElementById('et_reason').value = '';
                    get_ongoing_employee_transfer();
                    $('#new_employee_transfer').modal('hide');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error !!!',
                        text: `Error: ${response}`,
                        showConfirmButton: false,
                        timer: 1000
                    });
                }
            }
        });
    }

    const upload_csv = () => {
        var file_form = document.getElementById('file_form');
        var form_data = new FormData(file_form);
        $.ajax({
            url: '../process/import/imp_employee_transfer.php',
            type: 'POST',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            beforeSend: (jqXHR, settings) => {
                Swal.fire({
                    icon: 'info',
                    title: 'Uploading Please Wait...',
                    text: 'Info',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false
                });
                jqXHR.url = settings.url;
                jqXHR.type = settings.type;
            },
            success: response => {
                setTimeout(() => {
                    swal.close();
                    if (response != '') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Upload CSV Error',
                            text: `Error: ${response}`,
                            showConfirmButton: false,
                            timer: 2000
                        });
                    } else {
                        Swal.fire({
                            icon: 'info',
                            title: 'Upload CSV',
                            text: 'Uploaded and updated successfully',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        get_ongoing_employee_transfer();
                    }
                    document.getElementById("file").value = '';
                }, 500);
            }
        })
            .fail((jqXHR, textStatus, errorThrown) => {
                console.log(jqXHR);
                swal('System Error', `Call IT Personnel Immediately!!! They will fix it right away. Error: url: ${jqXHR.url}, method: ${jqXHR.type} ( HTTP ${jqXHR.status} - ${jqXHR.statusText} ) Press F12 to see Console Log for more info.`, 'error');
            });
    }
</script>