<?php 
function get_day($server_time, $server_date_only, $server_date_only_yesterday) {
  if ($server_time >= '06:00:00' && $server_time <= '23:59:59') {
    return $server_date_only;
  } else if ($server_time >= '00:00:00' && $server_time < '06:00:00') {
    return $server_date_only_yesterday;
  }
}
?>
<script type="text/javascript">
    // AJAX IN PROGRESS GLOBAL VARS
    var get_attendance_list_ajax_in_process = false;

    let absentReasonJsonData = [];

    // DOMContentLoaded function
    document.addEventListener("DOMContentLoaded", () => {
        document.getElementById('attendance_date_search').value = '<?= get_day($server_time, $server_date_only, $server_date_only_yesterday) ?>';
        fetch_line_dropdown_search();
        get_absences_reasons();
        get_attendance_list(1);
    });

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
                $('#line_no_search').html(response);
            }
        });
    }

    const get_absences_reasons = () => {
        $.ajax({
            url: '../process/admin/attendances/at_p.php',
            type: 'POST',
            cache: false,
            dataType: 'json',
            data: {
                method: 'get_absences_reasons'
            }, 
            success: function (response) {
                absentReasonJsonData = response;
                console.log(response);
            }
        });
    }

    const populate_absences_reasons_dropdown = (dropdownId, reasonsData) => {
        const reasonDropdown = document.getElementById(dropdownId);

        if (!reasonDropdown) {
            return; // Exit the function if the dropdown does not exist
        }
        
        reasonDropdown.innerHTML = '<option disabled selected value="">Select Reason</option>'; // Clear previous options

        reasonsData.forEach(item => {
            const option = document.createElement('option');
            option.value = item.reason;
            option.textContent = item.reason;
            reasonDropdown.appendChild(option);
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

    const get_attendance_list_counting = () => {
        var day = sessionStorage.getItem('attendance_date_search');
        var shift_group = sessionStorage.getItem('shift_group_search');
        var line_no = sessionStorage.getItem('line_no_search');
        var attendance_status = sessionStorage.getItem('attendance_status_search');

        $.ajax({
            url: '../process/admin/attendances/at_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'get_attendance_list_counting',
                day: day,
                shift_group: shift_group,
                line_no: line_no,
                attendance_status: attendance_status
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
    }

    const count_attendance_present = () => {
        var day = sessionStorage.getItem('attendance_date_search');
        var shift_group = sessionStorage.getItem('shift_group_search');
        var line_no = sessionStorage.getItem('line_no_search');
        var attendance_status = parseInt(sessionStorage.getItem('attendance_status_search'));

        $.ajax({
            url: '../process/admin/attendances/at_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'count_attendance_present',
                day: day,
                shift_group: shift_group,
                line_no: line_no
            },
            success: function (response) {
                let total = parseInt(sessionStorage.getItem('count_rows'));

                let present = 0;

                if (attendance_status != 2) {
                    present = parseInt(response);
                }

                let absent = total - present;
                let attendance_percentage = (present / total) * 100;
                document.getElementById("count_view_present").innerHTML = present;
                document.getElementById("counting_view_present").innerHTML = present;
                document.getElementById("count_view_absent").innerHTML = absent;
                document.getElementById("counting_view_absent").innerHTML = absent;
                document.getElementById("count_view_attendance_percentage").innerHTML = `${attendance_percentage.toFixed(2)}%`;

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
        var line_no = sessionStorage.getItem('line_no_search');
        var current_page = parseInt(sessionStorage.getItem('attendanceTablePagination'));
        var attendance_status = sessionStorage.getItem('attendance_status_search');
        $.ajax({
            url: '../process/admin/attendances/at_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'count_attendance_list',
                day: day,
                shift_group: shift_group,
                line_no: line_no,
                attendance_status: attendance_status
            },
            success: function (response) {
                sessionStorage.setItem('count_rows', response);
                var count = `Total: ${response}`;
                document.getElementById("attendanceTableInfo").innerHTML = count;
                document.getElementById("attendanceCountTableInfo").innerHTML = response;

                if (response > 0) {
                    count_attendance_present();
                    get_attendances_last_page();
                } else {
                    document.getElementById("btnNextPage").style.display = "none";
                    document.getElementById("btnNextPage").setAttribute('disabled', true);
                    document.getElementById("count_view_present").innerHTML = 0;
                    document.getElementById("counting_view_present").innerHTML = 0;
                    document.getElementById("count_view_absent").innerHTML = 0;
                    document.getElementById("counting_view_absent").innerHTML = 0;
                    document.getElementById("count_view_attendance_percentage").innerHTML = 0;
                }

                if (current_page < 2) {
                    get_attendance_list_counting();
                }
            }
        });
    }

    const get_attendances_last_page = () => {
        var day = sessionStorage.getItem('attendance_date_search');
        var shift_group = sessionStorage.getItem('shift_group_search');
        var line_no = sessionStorage.getItem('line_no_search');
        var attendance_status = sessionStorage.getItem('attendance_status_search');
        var current_page = parseInt(sessionStorage.getItem('attendanceTablePagination'));
        $.ajax({
            url: '../process/admin/attendances/at_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'attendance_list_last_page',
                day: day,
                shift_group: shift_group,
                line_no: line_no,
                attendance_status: attendance_status
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
        let line_no = document.getElementById('line_no_search').value;
        let attendance_status = document.getElementById('attendance_status_search').value;

        var day1 = sessionStorage.getItem('attendance_date_search');
        var shift_group1 = sessionStorage.getItem('shift_group_search');
        var line_no1 = sessionStorage.getItem('line_no_search');
        var attendance_status1 = sessionStorage.getItem('attendance_status_search');

        if (current_page > 1) {
            switch (true) {
                case day !== day1:
                case shift_group !== shift_group1:
                case line_no !== line_no1:
                case attendance_status !== attendance_status1:
                    day = day1;
                    shift_group = shift_group1;
                    line_no = line_no1;
                    attendance_status = attendance_status1;
                    break;
                default:
            }
        } else {
            sessionStorage.setItem('attendance_date_search', day);
            sessionStorage.setItem('shift_group_search', shift_group);
            sessionStorage.setItem('line_no_search', line_no);
            sessionStorage.setItem('attendance_status_search', attendance_status);
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
                line_no: line_no,
                attendance_status: attendance_status,
                current_page: current_page
            },
            beforeSend: (jqXHR, settings) => {
                document.getElementById("btnNextPage").setAttribute('disabled', true);
                var loading = `<tr id="loading"><td colspan="15" style="text-align:center;"><div class="spinner-border text-dark" role="status"><span class="sr-only">Loading...</span></div></td></tr>`;
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

                // Parse HTML Response for populate_absences_reasons_dropdown
                let wrappedResponse = `<table>${response}</table>`;
                let parser = new DOMParser();
                let doc = parser.parseFromString(wrappedResponse, 'text/html');

                // Select all <tr> elements in the parsed document
                let rows = doc.querySelectorAll('tr');

                // Process each row
                rows.forEach(row => {
                    // Extract the first <td> value
                    let firstTd = row.querySelector('td');
                    if (firstTd) {
                        let rowId = firstTd.innerText.trim(); // Suppose this is the corresponding row ID
                        populate_absences_reasons_dropdown(`absrd_${rowId}`, absentReasonJsonData);
                    }
                });

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

    // const get_absence_details = (param) => {
    //     var string = param.split('~!~');
    //     var absent_id = string[0];
    //     var emp_no = string[1];
    //     var full_name = string[2];
    //     var absent_day = string[3];
    //     var absent_shift_group = string[4];
    //     var absent_type = string[5];
    //     var reason = string[6];

    //     document.getElementById('id_absence_update').value = absent_id;
    //     document.getElementById('emp_no_absence_update').innerHTML = emp_no;
    //     document.getElementById('full_name_absence_update').innerHTML = full_name;
    //     document.getElementById('absent_day_absence_update').innerHTML = absent_day;
    //     document.getElementById('absent_shift_group_absence_update').innerHTML = absent_shift_group;
    //     document.getElementById('absent_type_absence_update').value = absent_type;
    //     document.getElementById('reason_absence_update').value = reason;
    // }

    // $("#absence_details").on('show.bs.modal', e => {
    //     load_reason_absence_update_textarea();
    // });

    // const load_reason_absence_update_textarea = () => {
    //     setTimeout(() => {
    //         var max_length = document.getElementById("reason_absence_update").getAttribute("maxlength");
    //         var reason_absence_update_length = document.getElementById("reason_absence_update").value.length;
    //         var reason_absence_update_count = `${reason_absence_update_length} / ${max_length}`;
    //         document.getElementById("reason_absence_update_count").innerHTML = reason_absence_update_count;
    //     }, 100);
    // }

    // const count_reason_absence_update_char = () => {
    //     var max_length = document.getElementById("reason_absence_update").getAttribute("maxlength");
    //     var reason_absence_update_length = document.getElementById("reason_absence_update").value.length;
    //     var reason_absence_update_count = `${reason_absence_update_length} / ${max_length}`;
    //     document.getElementById("reason_absence_update_count").innerHTML = reason_absence_update_count;
    // }

    // const save_absence_details = () => {
    //     var id = document.getElementById('id_absence_update').value;
    //     var emp_no = document.getElementById('emp_no_absence_update').innerHTML;
    //     var absent_day = document.getElementById('absent_day_absence_update').innerHTML;
    //     var absent_shift_group = document.getElementById('absent_shift_group_absence_update').innerHTML;
    //     var absent_type = document.getElementById('absent_type_absence_update').value;
    //     var reason = document.getElementById('reason_absence_update').value;

    //     if (absent_type == '') {
    //         Swal.fire({
    //             icon: 'info',
    //             title: 'Please Select Type of Absent !!!',
    //             text: 'Information',
    //             showConfirmButton: false,
    //             timer: 1000
    //         });
    //     } else if (reason == '') {
    //         Swal.fire({
    //             icon: 'info',
    //             title: 'Please Input Reason !!!',
    //             text: 'Information',
    //             showConfirmButton: false,
    //             timer: 1000
    //         });
    //     } else {
    //         $.ajax({
    //             url: '../process/admin/attendances/at_p.php',
    //             type: 'POST',
    //             cache: false,
    //             data: {
    //                 method: 'save_absence_details',
    //                 id: id,
    //                 emp_no: emp_no,
    //                 absent_day: absent_day,
    //                 absent_shift_group: absent_shift_group,
    //                 absent_type: absent_type,
    //                 reason: reason
    //             }, success: function (response) {
    //                 if (response == 'success') {
    //                     Swal.fire({
    //                         icon: 'success',
    //                         title: 'Absence Details Saved Successfully',
    //                         text: 'Success',
    //                         showConfirmButton: false,
    //                         timer: 1000
    //                     });
    //                     document.getElementById("id_absence_update").value = '';
    //                     document.getElementById("emp_no_absence_update").value = '';
    //                     document.getElementById("absent_day_absence_update").value = '';
    //                     document.getElementById("absent_shift_group_absence_update").value = '';
    //                     document.getElementById("absent_type_absence_update").value = '';
    //                     document.getElementById("reason_absence_update").value = '';
    //                     get_attendance_list(1);
    //                     $('#absence_details').modal('hide');
    //                 } else {
    //                     Swal.fire({
    //                         icon: 'error',
    //                         title: 'Error !!!',
    //                         text: 'Error',
    //                         showConfirmButton: false,
    //                         timer: 1000
    //                     });
    //                 }
    //             }
    //         });
    //     }
    // }

    const delete_single_absences_report = (row, selectElement) => {
        const id = selectElement.dataset.absent_id;

        const absentReasonDropdown = document.getElementById(`absrd_${row}`);
        const absentTypeDropdown = document.getElementById(`abstd_${row}`);

        $.ajax({
            url: '../process/admin/attendances/at_p.php',
            type: 'POST',
            cache: false,
            dataType: 'json',
            data: {
                method: 'delete_single_absences_report',
                id: id
            }, 
            success: function (response) {
                if (response.message == 'success') {
                    document.getElementById(`abst_${row}`).innerText = '';
                    document.getElementById(`absr_${row}`).innerText = '';
                    absentReasonDropdown.value = '';
                    absentTypeDropdown.value = '';
                    selectElement.disabled = true;
                    absentTypeDropdown.disabled = true;
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error !!!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1000
                    });
                }
            }
        });
    }

    const update_type_of_absent = (row, selectElement) => {
        const id = selectElement.dataset.absent_id;
        const absent_type = selectElement.value;

        $.ajax({
            url: '../process/admin/attendances/at_p.php',
            type: 'POST',
            cache: false,
            dataType: 'json',
            data: {
                method: 'update_type_of_absent',
                id: id,
                absent_type: absent_type
            }, 
            success: function (response) {
                if (response.message == 'success') {
                    document.getElementById(`abst_${row}`).innerText = absent_type;
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error !!!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1000
                    });
                }
            }
        });
    }

    const update_reason = (row, selectElement) => {
        const id = selectElement.dataset.absent_id;
        const emp_no = selectElement.dataset.emp_no;
        const absent_day = selectElement.dataset.absent_day;
        const absent_shift_group = selectElement.dataset.absent_shift_group;
        let absent_type = '';
        const reason = selectElement.value;
        const selectedItem = absentReasonJsonData.filter(item => item.reason === reason);

        let data = {};
        
        const absentDelBtn = document.getElementById(`absdelbtn_${row}`);

        const absentTypeDropdown = document.getElementById(`abstd_${row}`);
        absentTypeDropdown.innerHTML = '<option disabled selected value="">Select Type of Absent</option>'; // Clear previous absent types

        if (selectedItem.length > 1) {
            const absentTypes = [...new Set(selectedItem.map(item => item.absent_type))]; // Get unique absent types
            absentTypes.forEach(absentType => {
                const option = document.createElement('option');
                option.value = absentType;
                option.textContent = absentType;
                absentTypeDropdown.append(option);
            });
            document.getElementById(`abst_${row}`).innerText = '';

            data = {
                method: 'update_reason',
                id: id,
                emp_no: emp_no,
                absent_day: absent_day,
                absent_shift_group: absent_shift_group,
                reason: reason
            };
        } else if (selectedItem.length === 1) {
            // If only one item is found, select it in the dropdown
            absent_type = selectedItem[0].absent_type; // Get the absent type
            const option = document.createElement('option');
            option.value = absent_type;
            option.textContent = absent_type;
            absentTypeDropdown.append(option);
            absentTypeDropdown.value = absent_type; // Set the dropdown value
            document.getElementById(`abst_${row}`).innerText = absent_type; // Update the text for the single absent type

            data = {
                method: 'update_reason',
                id: id,
                emp_no: emp_no,
                absent_day: absent_day,
                absent_shift_group: absent_shift_group,
                absent_type: absent_type,
                reason: reason
            };
        } else {
            document.getElementById(`abst_${row}`).innerText = '';
        }

        $.ajax({
            url: '../process/admin/attendances/at_p.php',
            type: 'POST',
            cache: false,
            dataType: 'json',
            data: data,
            success: function (response) {
                if (response.message == 'success') {
                    if (response.id && id == '') {
                        selectElement.dataset.absent_id = response.id;
                        absentDelBtn.dataset.absent_id = response.id;
                        document.getElementById(`abstd_${row}`).dataset.absent_id = response.id;
                    }

                    absentTypeDropdown.disabled = false;
                    absentDelBtn.disabled = false;
                    document.getElementById(`absr_${row}`).innerText = reason;
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error !!!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1000
                    });
                }
            }
        });
    }

    const export_attendances = () => {
        let day = sessionStorage.getItem('attendance_date_search');
        let shift_group = sessionStorage.getItem('shift_group_search');
        let line_no = sessionStorage.getItem('line_no_search');
        window.open('../process/export/exp_attendances_control.php?day=' + day + "&shift_group=" + shift_group + "&line_no=" + line_no, '_blank');
    }

    const export_absences = () => {
        let day = sessionStorage.getItem('attendance_date_search');
        let shift_group = sessionStorage.getItem('shift_group_search');
        let line_no = sessionStorage.getItem('line_no_search');
        window.open('../process/export/exp_absences_control.php?day=' + day + "&shift_group=" + shift_group + "&line_no=" + line_no, '_blank');
    }

    const export_attendances_counting = () => {
        let day = sessionStorage.getItem('attendance_date_search');
        let shift_group = sessionStorage.getItem('shift_group_search');
        let line_no = sessionStorage.getItem('line_no_search');
        window.open('../process/export/exp_attendances_counting_control.php?day=' + day + "&shift_group=" + shift_group + "&line_no=" + line_no, '_blank');
    }

    const export_absences_template = () => {
        let day = sessionStorage.getItem('attendance_date_search');
        let shift_group = sessionStorage.getItem('shift_group_search');
        let line_no = sessionStorage.getItem('line_no_search');
        let attendance_status = sessionStorage.getItem('attendance_status_search');
        window.open('../process/export/exp_absences_template.php?day=' + day + "&shift_group=" + shift_group + "&line_no=" + line_no + "&attendance_status=" + attendance_status, '_blank');
    }

    const export_absences_reasons = () => {
        window.open('../process/export/exp_absences_reasons.php', '_blank');
    }

    const upload_csv = () => {
        var file_form = document.getElementById('file_form');
        var form_data = new FormData(file_form);
        $.ajax({
            url: '../process/import/imp_absences_report.php',
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
                            timer : 2000
                        });
                    } else {
                        Swal.fire({
                            icon: 'info',
                            title: 'Upload CSV',
                            text: 'Uploaded and updated successfully',
                            showConfirmButton: false,
                            timer : 1000
                        });
                        get_attendance_list(1);
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