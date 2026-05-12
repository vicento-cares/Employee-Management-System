<script type="text/javascript">
    // AJAX IN PROGRESS GLOBAL VARS
    var get_absences_list_ajax_in_process = false;

    let absentReasonJsonData = [];

    // DOMContentLoaded function
    document.addEventListener("DOMContentLoaded", () => {
        document.getElementById('attendance_date_search').value = '<?= $server_date_only ?>';
        get_absences_reasons();
        setTimeout(get_absences_list, 1000);
        sessionStorage.setItem('notif_pending_ls', 0);
        sessionStorage.setItem('notif_accepted_ls', 0);
        sessionStorage.setItem('notif_rejected_ls', 0);
        load_notif_line_support();
        realtime_load_notif_line_support = setInterval(load_notif_line_support, 30000);
    });

    const get_absences_reasons = () => {
        $.ajax({
            url: '../process/admin/attendances/at_p.php',
            type: 'POST',
            cache: false,
            dataType: 'json',
            data: {
                method: 'get_absences_reasons',
                page: 'admin'
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

    const get_absences_list = () => {
        // If an AJAX call is already in progress, return immediately
        if (get_absences_list_ajax_in_process) {
            return;
        }

        let day = document.getElementById('attendance_date_search').value;
        let shift_group = document.getElementById('shift_group_search').value;

        // Set the flag to true as we're starting an AJAX call
        get_absences_list_ajax_in_process = true;

        $.ajax({
            url: '../process/admin/attendances/at_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'get_absences_list',
                day: day,
                shift_group: shift_group
            },
            beforeSend: (jqXHR, settings) => {
                var loading = `<tr id="loading"><td colspan="16" style="text-align:center;"><div class="spinner-border text-dark" role="status"><span class="sr-only">Loading...</span></div></td></tr>`;

                document.getElementById("absencesData").innerHTML = loading;

                jqXHR.url = settings.url;
                jqXHR.type = settings.type;
            },
            success: function (response) {
                $('#loading').remove();

                document.getElementById("absencesData").innerHTML = response;

                let table_rows = parseInt(document.getElementById("absencesData").childNodes.length);
                document.getElementById("count_view_absent").innerHTML = table_rows;

                // Parse HTML Response for populate_absences_reasons_dropdown
                let wrappedResponse = `<table>${response}</table>`;
                let parser = new DOMParser();
                let doc = parser.parseFromString(wrappedResponse, 'text/html');

                // Select all <tr> elements in the parsed document
                let rows = doc.querySelectorAll('tr');

                // Process each row
                setTimeout(() => {
                    rows.forEach(row => {
                        // Extract the first <td> value
                        let firstTd = row.querySelector('td');
                        if (firstTd) {
                            let rowId = firstTd.innerText.trim(); // Suppose this is the corresponding row ID
                            populate_absences_reasons_dropdown(`absrd_${rowId}`, absentReasonJsonData);
                        }
                    });
                }, 500);
                
                sessionStorage.setItem('attendance_date_search', day);
                sessionStorage.setItem('shift_group_search', shift_group);
                // Set the flag back to false as the AJAX call has completed
                get_absences_list_ajax_in_process = false;
            }
        }).fail((jqXHR, textStatus, errorThrown) => {
            console.log(jqXHR);
            console.log(`System Error : Call IT Personnel Immediately!!! They will fix it right away. Error: url: ${jqXHR.url}, method: ${jqXHR.type} ( HTTP ${jqXHR.status} - ${jqXHR.statusText} ) Press F12 to see Console Log for more info.`);
            $('#loading').remove();
            document.getElementById("btnNextPage").removeAttribute('disabled');
            // Set the flag back to false as the AJAX call has completed
            get_absences_list_ajax_in_process = false;
        });
    }

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

    const export_absences = () => {
        let day = sessionStorage.getItem('attendance_date_search');
        let shift_group = sessionStorage.getItem('shift_group_search');
        let dept = '';
        window.open('../process/export/exp_absences.php?day=' + day + "&shift_group=" + shift_group + "&dept=" + dept, '_blank');
    }
</script>