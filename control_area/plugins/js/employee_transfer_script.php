<script type="text/javascript">
    // DOMContentLoaded function
    document.addEventListener("DOMContentLoaded", () => {
        fetch_dept_dropdown();
        fetch_section_dropdown();
        fetch_line_dropdown();
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
            }
        });
    }

    const fetch_line_dropdown = opt => {
        let section = '';

        if (opt == 1) {
            section = document.getElementById('section_master').value;
        } else if (opt == 2) {
            section = document.getElementById('section_master_update').value;
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
                document.getElementById('et_line_no').innerHTML = response;
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
                        title: 'Succesfully Recorded!!!',
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
                    load_employee_transfer();
                    $('#new_employee_transfer').modal('hide');
                } else if (response == 'duplicate') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Duplicate Data !!!',
                        text: 'Information',
                        showConfirmButton: false,
                        timer: 1000
                    });
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
                        load_employees(1);
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