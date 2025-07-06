<script type="text/javascript">
    // Global Variables for Realtime
	var realtime_get_pending_line_support;
	var realtime_get_recent_line_support_history;

    // DOMContentLoaded function
    document.addEventListener("DOMContentLoaded", () => {
        fetch_line_dropdown();
        get_pending_line_support();
        realtime_get_pending_line_support = setInterval(get_pending_line_support, 15000);
        get_recent_line_support_history();
        realtime_get_recent_line_support_history = setInterval(get_recent_line_support_history, 30000);
        sessionStorage.setItem('notif_pending_ls', 0);
        sessionStorage.setItem('notif_accepted_ls', 0);
        sessionStorage.setItem('notif_rejected_ls', 0);
        load_notif_line_support_req();
        realtime_load_notif_line_support_req = setInterval(load_notif_line_support_req, 30000);
        update_notif_line_support();

        sessionStorage.setItem("emp_mgt_history_day_search", '');
        sessionStorage.setItem("emp_mgt_history_shift_search", '');
        sessionStorage.setItem("emp_mgt_history_line_no_from_search", '');
        sessionStorage.setItem("emp_mgt_history_line_no_to_search", '');
    });

    var delay = (function(){
        var timer = 0;
        return function(callback, ms){
            clearTimeout (timer);
            timer = setTimeout(callback, ms);
        };
    })();

    $("#emp_no_ls").on("input", function() {
        delay(function(){
        if ($("#emp_no_ls").val().length < 7) {
            $("#emp_no_ls").val("");
        }
        }, 100);
    });

    document.getElementById("emp_no_ls").addEventListener("keyup", e => {
        if (e.which === 13) {
            e.preventDefault();
            get_line_support_employee();
        }
    });

    const fetch_line_dropdown = () => {
        $.ajax({
            url: '../process/admin/line_support/ls_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'fetch_line_dropdown'
            },
            success: function (response) {
                $("#line_no_ls").html(response);
                $("#history_line_no_from_search").html(response);
                $("#history_line_no_to_search").html(response);
            }
        });
    }

    const get_line_support_employee = () => {
        let emp_no = document.getElementById("emp_no_ls").value;
        if (emp_no == '') {
            Swal.fire({
                icon: 'info',
                title: 'Please Scan ID Number !!!',
                text: 'Information',
                showConfirmButton: false,
                timer: 1000
            });
        } else {
            $.ajax({
                url: '../process/admin/line_support/ls_p.php',
                type: 'POST',
                cache: false,
                data: {
                    method: 'get_line_support_employee',
                    emp_no: emp_no
                },
                success: function (response) {
                    if (response == 'No Time In') {
                        document.getElementById("emp_no_ls").value = '';
                        document.getElementById("full_name_ls").innerHTML = '';
                        Swal.fire({
                            icon: 'info',
                            title: 'No Time In',
                            text: 'Information',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    } else if (response == 'Already Time Out') {
                        document.getElementById("emp_no_ls").value = '';
                        document.getElementById("full_name_ls").innerHTML = '';
                        Swal.fire({
                            icon: 'error',
                            title: 'Already Time Out',
                            text: 'Error !!!',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    } else {
                        try {
                            let response_array = JSON.parse(response);
                            if (response_array.message == 'success') {
                                document.getElementById("full_name_ls").innerHTML = response_array.full_name;
                                sessionStorage.setItem('emp_no_ls', emp_no);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error !!!',
                                    text: `${response_array.message}`,
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            }
                        } catch (e) {
                            console.log(response);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error !!!',
                                text: `${response}`,
                                showConfirmButton: false,
                                timer: 2000
                            });
                        }
                    }
                }
            });
        }
    }

    const set_line_support = () => {
        let line_support_id = document.getElementById("line_support_id_ls").value;
        let emp_no = sessionStorage.getItem('emp_no_ls');
        let full_name = document.getElementById("full_name_ls").innerHTML;
        let line_no = document.getElementById("line_no_ls").value;

        if (emp_no == '' || full_name == '') {
            Swal.fire({
                icon: 'info',
                title: 'Please Scan ID Number !!!',
                text: 'Information',
                showConfirmButton: false,
                timer: 1000
            });
        } else if (line_no == '') {
            Swal.fire({
                icon: 'info',
                title: 'Please Set Supported Line !!!',
                text: 'Information',
                showConfirmButton: false,
                timer: 1000
            });
        } else {
            $.ajax({
                url: '../process/admin/line_support/ls_p.php',
                type: 'POST',
                cache: false,
                data: {
                    method: 'set_line_support',
                    line_support_id: line_support_id,
                    emp_no: emp_no,
                    full_name: full_name,
                    line_no: line_no
                }, success: function (response) {
                    if (response == 'Already Time Out') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Already Time Out',
                            text: 'Error !!!',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    } else if (response == 'Already Supported') {
                        Swal.fire({
                            icon: 'info',
                            title: 'Already Supported',
                            text: 'Information',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    } else if (response == 'Already Set') {
                        Swal.fire({
                            icon: 'info',
                            title: 'Already Set',
                            text: 'Information',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    } else if (response == 'Duplicate') {
                        Swal.fire({
                            icon: 'info',
                            title: 'Duplicate Support',
                            text: 'Information',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    } else if (response == 'No Certification') {
                        Swal.fire({
                            icon: 'info',
                            title: 'No Certification on Process to Support',
                            text: 'Information',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    } else {
                        try {
                            let response_array = JSON.parse(response);
                            if (response_array.message == 'success') {
                                document.getElementById("emp_no_ls").value = '';
                                document.getElementById("full_name_ls").innerHTML = '';
                                document.getElementById("line_no_ls").value = '';
                                document.getElementById("line_support_id_ls").value = response_array.line_support_id;
                                get_added_line_support();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error !!!',
                                    text: `${response_array.message}`,
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            }
                        } catch (e) {
                            console.log(response);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error !!!',
                                text: `${response}`,
                                showConfirmButton: false,
                                timer: 2000
                            });
                        }
                    }
                }
            });
        }
    }

    const get_added_line_support = () => {
        let line_support_id = document.getElementById("line_support_id_ls").value;

        $.ajax({
            url: '../process/admin/line_support/ls_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'get_added_line_support',
                line_support_id: line_support_id
            },
            success: function (response) {
                document.getElementById("setLineSupportData").innerHTML = response;
                let table_rows = parseInt(document.getElementById("setLineSupportData").childNodes.length);
                document.getElementById("count_view_set_line_support").innerHTML = `Count: ${table_rows}`;
                if (table_rows > 0) {
                    document.getElementById("btnSaveLineSupport").removeAttribute('disabled');
                } else {
                    document.getElementById("btnSaveLineSupport").setAttribute('disabled', true);
                }
            }
        });
    }

    const edit_single_added_line_support = el => {
        var id = el.dataset.id;
        var emp_no = el.dataset.emp_no;

        document.getElementById('lsd_id').value = id;
        document.getElementById('lsd_emp_no').value = emp_no;

        const startTimeInput = document.getElementById('lsd_start_date');
        const endTimeInput = document.getElementById('lsd_end_date');

        // Set default time to 00 minutes
        const currentTime = new Date();
        const hours = String(currentTime.getHours()).padStart(2, '0'); // Get current hours
        const defaultTime = `${hours}:00`; // Set minutes to 00

        startTimeInput.value = defaultTime; // Set default value for start time
        endTimeInput.value = defaultTime; // Set default value for end time

        $('#set_line_support_details').modal('show');
    }

    document.getElementById('lsd_category').addEventListener('change', e => {
        e.preventDefault();

        const emp_no = document.getElementById('lsd_emp_no').value;

        // Get the current element value
        const category = e.target.value;

        // You can now use category as needed
        console.log(category);

        $.ajax({
            url: '../process/admin/line_support/ls_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'get_assigned_process_dropdown',
                category: category,
                emp_no: emp_no
            },
            success: function (response) {
                $("#lsd_assigned_process").html(response);
            }
        });

        $.ajax({
            url: '../process/admin/line_support/ls_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'get_assigned_station_dropdown',
                category: category
            },
            success: function (response) {
                $("#lsd_assigned_station").html(response);
            }
        });
    });

    document.getElementById('lsd_assigned_station').addEventListener('change', e => {
        e.preventDefault();

        // Get the current element value
        const assigned_station = e.target.value;

        // You can now use assigned_station as needed
        console.log(assigned_station);

        if (assigned_station == 'N/A') {
            document.getElementById('lsd_assigned_station_no').disabled = true;
            document.getElementById('lsd_assigned_station_no').required = false;
        } else {
            document.getElementById('lsd_assigned_station_no').disabled = false;
            document.getElementById('lsd_assigned_station_no').required = true;
        }
    });

    const clear_line_support_details = () => {
        document.getElementById('lsd_id').value = '';
        document.getElementById('lsd_assigned_process').value = '';
        document.getElementById('lsd_category').value = '';
        document.getElementById('lsd_assigned_station').value = '';
        document.getElementById('lsd_assigned_station_no').disabled = false;
        document.getElementById('lsd_assigned_station_no').value = '';
        document.getElementById('lsd_start_date').value = '';
        document.getElementById('lsd_end_date').value = '';
    }

	$("#set_line_support_details").on('hidden.bs.modal', e => {
        clear_line_support_details();
    });

    document.getElementById('set_line_support_details_form').addEventListener('submit', e => {
        e.preventDefault();
        set_line_support_details();
    });

    const set_line_support_details = () => {
        var id = document.getElementById('lsd_id').value;
        var assigned_process = document.getElementById('lsd_assigned_process').value;
        var assigned_station = document.getElementById('lsd_assigned_station').value;
        var assigned_station_no = document.getElementById('lsd_assigned_station_no').value;
        var start_date = document.getElementById('lsd_start_date').value;
        var end_date = document.getElementById('lsd_end_date').value;

        document.getElementById('btnSaveLineSupportDetails').disabled = true;

        $.ajax({
            url: '../process/admin/line_support/ls_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'set_line_support_details',
                id: id,
                assigned_process: assigned_process,
                assigned_station: assigned_station,
                assigned_station_no: assigned_station_no,
                start_date: start_date,
                end_date: end_date
            }, success: function (response) {
                if (response == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Saved Succesfully!!!',
                        text: 'Success',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    get_added_line_support();
                    $('#set_line_support_details').modal('hide');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error !!!',
                        text: response,
                        showConfirmButton: false,
                        timer: 2000
                    });
                }

                document.getElementById('btnSaveLineSupportDetails').disabled = false;
            }
        });
    }

    const delete_single_added_line_support = el => {
        var line_support_id = el.dataset.line_support_id;
        var id = el.dataset.id;

        $.ajax({
            url: '../process/admin/line_support/ls_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'delete_single_added_line_support',
                line_support_id: line_support_id,
                id: id
            },
            success: function (response) {
                if (response == 'success') {
                    get_added_line_support();
                } else {
                    console.log(response);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error !!!',
                        text: `${response}`,
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            }
        });
    }

    const verify_save_line_support = () => {
        $('#admin_verification').modal('show');
    }

    document.getElementById("emp_no_verify").addEventListener("keyup", e => {
        if (e.which === 13) {
            e.preventDefault();
            var emp_no = document.getElementById('emp_no_verify').value;

            admin_verification((message) => {
                if (message == "success") {
                    $('#admin_verification').modal('hide');
                    save_line_support();
                } else if (message == "failed") {
                    Swal.fire({
                        icon: 'error',
                        title: 'Admin Verification Error',
                        text: 'Failed to verify! Maybe incorrect credential or account not found...',
                        showConfirmButton: false,
                        timer: 2000
                    });
                } else if (message == "unmatched") {
                    Swal.fire({
                        icon: 'error',
                        title: 'Admin Verification Error',
                        text: 'Cannot proceed using different account! Only account that was currently logged in is allowed',
                        showConfirmButton: false,
                        timer: 2000
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Admin Verification Error',
                        text: `Error : ${message}`,
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            });
        }
    });

    const save_line_support = () => {
        let line_support_id = document.getElementById("line_support_id_ls").value;
        $.ajax({
            url: '../process/admin/line_support/ls_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'save_line_support',
                line_support_id: line_support_id
            }, success: function (response) {
                if (response == 'success') {
                    document.getElementById("emp_no_ls").value = '';
                    document.getElementById("full_name_ls").innerHTML = '';
                    document.getElementById("line_no_ls").value = '';
                    document.getElementById("line_support_id_ls").value = '';
                    document.getElementById("setLineSupportData").innerHTML = '';
                    document.getElementById("count_view_set_line_support").innerHTML = '';
                    document.getElementById("btnSaveLineSupport").setAttribute('disabled', true);
                    get_pending_line_support();
                    Swal.fire({
                        icon: 'success',
                        title: 'Set Line Support Saved Succesfully',
                        text: 'Success',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    $("#set_line_support").modal('hide');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error !!!',
                        text: `${response}`,
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            }
        });
    }

    const get_pending_line_support = () => {
        $.ajax({
            url: '../process/admin/line_support/ls_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'get_pending_line_support'
            },
            success: function (response) {
                document.getElementById("pendingLineSupportData").innerHTML = response;
                let table_rows = parseInt(document.getElementById("pendingLineSupportData").childNodes.length);
                document.getElementById("count_view").innerHTML = `Count: ${table_rows}`;
            }
        });
    }

    const get_pending_line_support_details = param => {
        var string = param.split('~!~');
        var id = string[0];
        var line_support_id = string[1];
        var emp_no = string[2];
        var full_name = string[3];
        var dept = string[4];
        var day = string[5];
        var shift = string[6];
        var line_no_from = string[7];
        var line_no_to = string[8];
        var set_by = string[9];
        var set_by_no = string[10];
        var pending_status = string[11];
        var line_process = string[12];
        var shift_group = string[13];

        document.getElementById("pending_id_ls").value = id;
        document.getElementById("pending_line_support_id_ls").innerHTML = line_support_id;
        document.getElementById("pending_emp_no_ls").innerHTML = emp_no;
        document.getElementById("pending_full_name_ls").innerHTML = full_name;
        document.getElementById("pending_dept_ls").innerHTML = dept;
        document.getElementById("pending_process_ls").innerHTML = line_process;
        document.getElementById("pending_shift_group_ls").innerHTML = shift_group;

        document.getElementById("pending_day_ls").innerHTML = day;
        document.getElementById("pending_shift_ls").innerHTML = shift;
        document.getElementById("pending_line_no_from_ls").innerHTML = line_no_from;
        document.getElementById("pending_line_no_to_ls").innerHTML = line_no_to;
        document.getElementById("pending_set_by_no_ls").innerHTML = set_by_no;
        document.getElementById("pending_set_by_ls").innerHTML = set_by;

        if (pending_status == "pending") {
            document.getElementById("divPending").classList.remove("d-none");
            document.getElementById("divNeedConfirmation").classList.add("d-none");
        } else if (pending_status == "needacceptance") {
            document.getElementById("divPending").classList.add("d-none");
            document.getElementById("divNeedConfirmation").classList.remove("d-none");
        }
    }

    const reject_line_support = () => {
        let id = document.getElementById("pending_id_ls").value;
        $.ajax({
            url: '../process/admin/line_support/ls_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'reject_line_support',
                id: id
            }, success: function (response) {
                if (response == 'success') {
                    get_pending_line_support();
                    get_recent_line_support_history();
                    Swal.fire({
                        icon: 'success',
                        title: 'Line Support Rejected Successfully',
                        text: 'Success',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    $("#pending_line_support").modal('hide');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error !!!',
                        text: `${response}`,
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            }
        });
    }

    const accept_line_support = () => {
        let id = document.getElementById("pending_id_ls").value;
        $.ajax({
            url: '../process/admin/line_support/ls_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'accept_line_support',
                id: id
            }, success: function (response) {
                if (response == 'success') {
                    get_pending_line_support();
                    get_recent_line_support_history();
                    Swal.fire({
                        icon: 'success',
                        title: 'Line Support Accepted Successfully',
                        text: 'Success',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    $("#pending_line_support").modal('hide');
                } else if (response == 'Already Time Out') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Already Time Out! Please Reject Instead',
                        text: 'Error !!!',
                        showConfirmButton: false,
                        timer: 2000
                    });
                } else if (response == 'Current Day or Shift Unmatched') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Current Day or Shift Unmatched! Please Reject Instead',
                        text: 'Error !!!',
                        showConfirmButton: false,
                        timer: 2000
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error !!!',
                        text: `${response}`,
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            }
        });
    }

    const get_recent_line_support_history = () => {
        $.ajax({
            url: '../process/admin/line_support/ls_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'get_recent_line_support_history'
            },
            success: function (response) {
                document.getElementById("recentLineSupportHistoryData").innerHTML = response;
                let table_rows = parseInt(document.getElementById("recentLineSupportHistoryData").childNodes.length);
                document.getElementById("count_view2").innerHTML = `Count: ${table_rows}`;
            }
        });
    }

    const get_recent_line_support_history_details = param => {
        var string = param.split('~!~');
        var id = string[0];
        var line_support_id = string[1];
        var emp_no = string[2];
        var full_name = string[3];
        var dept = string[4];
        var day = string[5];
        var shift = string[6];
        var line_no_from = string[7];
        var line_no_to = string[8];
        var set_by = string[9];
        var set_by_no = string[10];
        var set_by = string[11];
        var set_by_no = string[12];
        var status = string[13];
        var line_process = string[14];
        var shift_group = string[15];

        document.getElementById("history_id_ls").value = id;
        document.getElementById("history_line_support_id_ls").innerHTML = line_support_id;
        document.getElementById("history_emp_no_ls").innerHTML = emp_no;
        document.getElementById("history_full_name_ls").innerHTML = full_name;
        document.getElementById("history_dept_ls").innerHTML = dept;
        document.getElementById("history_process_ls").innerHTML = line_process;
        document.getElementById("history_shift_group_ls").innerHTML = shift_group;

        document.getElementById("history_day_ls").innerHTML = day;
        document.getElementById("history_shift_ls").innerHTML = shift;
        document.getElementById("history_line_no_from_ls").innerHTML = line_no_from;
        document.getElementById("history_line_no_to_ls").innerHTML = line_no_to;
        document.getElementById("history_set_by_no_ls").innerHTML = set_by_no;
        document.getElementById("history_set_by_ls").innerHTML = set_by;
        document.getElementById("history_set_status_by_no_ls").innerHTML = set_by_no;
        document.getElementById("history_set_status_by_ls").innerHTML = set_by;
        document.getElementById("history_status_ls").innerHTML = status;
    }

    const get_line_support_history = () => {
        let day = document.getElementById("history_day_search").value;
        let shift = document.getElementById("history_shift_search").value;
        let emp_no = document.getElementById("history_emp_no_search").value;
        let full_name = document.getElementById("history_full_name_search").value;
        let line_no_from = document.getElementById("history_line_no_from_search").value;
        let line_no_to = document.getElementById("history_line_no_to_search").value;
        let history_status = document.getElementById("history_status_search").value;

        if (day == '') {
            Swal.fire({
                icon: 'info',
                title: 'Please Fill Out Date Field',
                text: 'Information',
                showConfirmButton: false,
                timer: 1000
            });
        } else {
            $.ajax({
                url: '../process/admin/line_support/ls_p.php',
                type: 'POST',
                cache: false,
                data: {
                    method: 'get_line_support_history',
                    day: day,
                    shift: shift,
                    emp_no: emp_no,
                    full_name: full_name,
                    line_no_from: line_no_from,
                    line_no_to: line_no_to,
                    history_status: history_status
                },
                success: function (response) {
                    document.getElementById("lineSupportHistoryData").innerHTML = response;
                    let table_rows = parseInt(document.getElementById("lineSupportHistoryData").childNodes.length);
                    document.getElementById("count_view3").innerHTML = `Count: ${table_rows}`;
                    sessionStorage.setItem("emp_mgt_history_day_search", day);
                    sessionStorage.setItem("emp_mgt_history_shift_search", shift);
                    sessionStorage.setItem("emp_mgt_history_line_no_from_search", line_no_from);
                    sessionStorage.setItem("emp_mgt_history_line_no_to_search", line_no_to);
                }
            });
        }
    }

    const export_line_support_history = (table_id, separator = ',') => {
        var day = sessionStorage.getItem("emp_mgt_history_day_search");
        var shift = sessionStorage.getItem("emp_mgt_history_shift_search");
        var line_no_from = sessionStorage.getItem("emp_mgt_history_line_no_from_search");
        var line_no_to = sessionStorage.getItem("emp_mgt_history_line_no_to_search");

        if (day == '' || day == null) {
            Swal.fire({
                icon: 'info',
                title: 'Please Fill Out Date Field',
                text: 'Information',
                showConfirmButton: false,
                timer: 1000
            });
        } else {
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
            var filename = 'EmpMgtSys_LineSupportHistory_' + day + '_' + shift;
            if (line_no_from) {
                filename += '_' + line_no_from;
            }
            if (line_no_to) {
                filename += '_' + line_no_to;
            }
            filename += '_' + new Date().toLocaleDateString() + '.csv';
            var link = document.createElement('a');
            link.style.display = 'none';
            link.setAttribute('target', '_blank');
            link.setAttribute('href', 'data:text/csv;charset=utf-8,%EF%BB%BF' + encodeURIComponent(csv_string));
            link.setAttribute('download', filename);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }
</script>