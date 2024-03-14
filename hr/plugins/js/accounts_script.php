<script type="text/javascript">
    // AJAX IN PROGRESS GLOBAL VARS
    var load_accounts_ajax_in_process = false;

    // DOMContentLoaded function
    document.addEventListener("DOMContentLoaded", () => {
        fetch_dept_dropdown();
        fetch_section_dropdown();
        fetch_line_dropdown();
        load_accounts(1);
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
                document.getElementById("dept").innerHTML = response;
                document.getElementById("dept_update").innerHTML = response;
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
                document.getElementById("section").innerHTML = response;
                document.getElementById("section_update").innerHTML = response;
            }
        });
    }

    const fetch_line_dropdown = () => {
        $.ajax({
            url: '../process/hr/employees/emp-masterlist_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'fetch_line_dropdown'
            },
            success: function (response) {
                document.getElementById("line_no").innerHTML = response;
                document.getElementById("line_no_update").innerHTML = response;
            }
        });
    }

    var typingTimerEmpNoSearch; // Timer identifier EmpNo Search
    var typingTimerFullNameSearch; // Timer identifier FullName Search
    var doneTypingInterval = 250; // Time in ms

    // On keyup, start the countdown
    document.getElementById("emp_no_search").addEventListener('keyup', e => {
        clearTimeout(typingTimerEmpNoSearch);
        typingTimerEmpNoSearch = setTimeout(doneTypingLoadAccounts, doneTypingInterval);
    });

    // On keydown, clear the countdown
    document.getElementById("emp_no_search").addEventListener('keydown', e => {
        clearTimeout(typingTimerEmpNoSearch);
    });

    // On keyup, start the countdown
    document.getElementById("full_name_search").addEventListener('keyup', e => {
        clearTimeout(typingTimerFullNameSearch);
        typingTimerFullNameSearch = setTimeout(doneTypingLoadAccounts, doneTypingInterval);
    });

    // On keydown, clear the countdown
    document.getElementById("full_name_search").addEventListener('keydown', e => {
        clearTimeout(typingTimerFullNameSearch);
    });

    // User is "finished typing," do something
    const doneTypingLoadAccounts = () => {
        load_accounts(1);
    }

    // Table Responsive Scroll Event for Load More
    document.getElementById("list_of_accounts_res").addEventListener("scroll", () => {
        var scrollTop = document.getElementById("list_of_accounts_res").scrollTop;
        var scrollHeight = document.getElementById("list_of_accounts_res").scrollHeight;
        var offsetHeight = document.getElementById("list_of_accounts_res").offsetHeight;

        if (load_accounts_ajax_in_process == false) {
            //check if the scroll reached the bottom
            if ((offsetHeight + scrollTop + 1) >= scrollHeight) {
                get_next_page();
            }
        }
    });

    const get_next_page = () => {
        var current_page = parseInt(sessionStorage.getItem('list_of_accounts_table_pagination'));
        let total = sessionStorage.getItem('count_rows');
        var last_page = parseInt(sessionStorage.getItem('last_page'));
        var next_page = current_page + 1;
        if (next_page <= last_page && total > 0) {
            load_accounts(next_page);
        }
    }

    const count_account_list = () => {
        var emp_no = sessionStorage.getItem('emp_no_search');
        var full_name = sessionStorage.getItem('full_name_search');
        var role = sessionStorage.getItem('role_search');
        $.ajax({
            url: '../process/admin/accounts/acct-management_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'count_account_list',
                emp_no: emp_no,
                full_name: full_name,
                role: role
            },
            success: function (response) {
                sessionStorage.setItem('count_rows', response);
                var count = `Total: ${response}`;
                document.getElementById("list_of_accounts_info").innerHTML = count;

                if (response > 0) {
                    load_accounts_last_page();
                } else {
                    document.getElementById("btnNextPage").style.display = "none";
                    document.getElementById("btnNextPage").setAttribute('disabled', true);
                }
            }
        });
    }

    const load_accounts_last_page = () => {
        var emp_no = sessionStorage.getItem('emp_no_search');
        var full_name = sessionStorage.getItem('full_name_search');
        var role = sessionStorage.getItem('role_search');
        var current_page = parseInt(sessionStorage.getItem('list_of_accounts_table_pagination'));
        $.ajax({
            url: '../process/admin/accounts/acct-management_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'account_list_last_page',
                emp_no: emp_no,
                full_name: full_name,
                role: role
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

    const load_accounts = current_page => {
        // If an AJAX call is already in progress, return immediately
        if (load_accounts_ajax_in_process) {
            return;
        }

        var emp_no = document.getElementById('emp_no_search').value;
        var full_name = document.getElementById('full_name_search').value;
        var role = document.getElementById('role_search').value;

        var emp_no1 = sessionStorage.getItem('emp_no_search');
        var full_name1 = sessionStorage.getItem('full_name_search');
        var role1 = sessionStorage.getItem('role_search');

        if (current_page > 1) {
            switch (true) {
                case emp_no !== emp_no1:
                case full_name !== full_name1:
                case role !== role1:
                    emp_no = emp_no1;
                    full_name = full_name1;
                    role = role1;
                    break;
                default:
            }
        } else {
            sessionStorage.setItem('emp_no_search', emp_no);
            sessionStorage.setItem('full_name_search', full_name);
            sessionStorage.setItem('role_search', role);
        }

        // Set the flag to true as we're starting an AJAX call
        load_accounts_ajax_in_process = true;

        $.ajax({
            url: '../process/admin/accounts/acct-management_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'account_list',
                emp_no: emp_no,
                full_name: full_name,
                role: role,
                current_page: current_page
            },
            beforeSend: (jqXHR, settings) => {
                document.getElementById("btnNextPage").setAttribute('disabled', true);
                var loading = `<tr id="loading"><td colspan="7" style="text-align:center;"><div class="spinner-border text-dark" role="status"><span class="sr-only">Loading...</span></div></td></tr>`;
                if (current_page == 1) {
                    document.getElementById("list_of_accounts").innerHTML = loading;
                } else {
                    $('#list_of_accounts_table tbody').append(loading);
                }
                jqXHR.url = settings.url;
                jqXHR.type = settings.type;
            },
            success: function (response) {
                $('#loading').remove();
                document.getElementById("btnNextPage").removeAttribute('disabled');
                if (current_page == 1) {
                    $('#list_of_accounts_table tbody').html(response);
                } else {
                    $('#list_of_accounts_table tbody').append(response);
                }
                sessionStorage.setItem('list_of_accounts_table_pagination', current_page);
                count_account_list();
                // Set the flag back to false as the AJAX call has completed
                load_accounts_ajax_in_process = false;
            }
        }).fail((jqXHR, textStatus, errorThrown) => {
            console.log(jqXHR);
            console.log(`System Error : Call IT Personnel Immediately!!! They will fix it right away. Error: url: ${jqXHR.url}, method: ${jqXHR.type} ( HTTP ${jqXHR.status} - ${jqXHR.statusText} ) Press F12 to see Console Log for more info.`);
            $('#loading').remove();
            document.getElementById("btnNextPage").removeAttribute('disabled');
            // Set the flag back to false as the AJAX call has completed
            load_accounts_ajax_in_process = false;
        });
    }

    document.getElementById("emp_no").addEventListener("keyup", e => {
        if (e.which === 13) {
            e.preventDefault();
            get_employee_data('insert');
        }
    });

    document.getElementById("emp_no_update").addEventListener("keyup", e => {
        if (e.which === 13) {
            e.preventDefault();
            get_employee_data('update');
        }
    });

    const get_employee_data = opt => {
        var emp_no = '';

        if (opt == 'insert') {
            var emp_no = document.getElementById('emp_no').value;
        } else if (opt == 'update') {
            var emp_no = document.getElementById('emp_no_update').value;
        }

        if (emp_no != '') {
            $.ajax({
                url: '../process/hr/employees/emp-masterlist_p.php',
                type: 'POST',
                cache: false,
                data: {
                    method: 'get_employee_data',
                    emp_no: emp_no
                },
                success: function (response) {
                    try {
                        let response_array = JSON.parse(response);
                        if (response_array.message == 'success') {
                            if (opt == 'insert') {
                                document.getElementById('full_name').value = response_array.full_name;
                                document.getElementById('dept').value = response_array.dept;
                                document.getElementById('section').value = response_array.section;
                                document.getElementById('line_no').value = response_array.line_no;
                                document.getElementById('shift_group').value = response_array.shift_group;
                                document.getElementById('role').value = response_array.role;
                            } else if (opt == 'update') {
                                document.getElementById('full_name_update').value = response_array.full_name;
                                document.getElementById('dept_update').value = response_array.dept;
                                document.getElementById('section_update').value = response_array.section;
                                document.getElementById('line_no_update').value = response_array.line_no;
                                document.getElementById('shift_group_update').value = response_array.shift_group;
                                document.getElementById('role_update').value = response_array.role;
                            }
                        } else if (response_array.message == 'Not Found') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error !!!',
                                text: "Error: Employee Unregistered or Resigned",
                                showConfirmButton: false,
                                timer: 2000
                            });
                        }
                    } catch (e) {
                        console.log(response);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error !!!',
                            text: `Error: ${response}`,
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                }
            });
        } else {
            Swal.fire({
                icon: 'info',
                title: 'Information !!!',
                text: "Please type Employee No. Before Pressing Enter Key",
                showConfirmButton: false,
                timer: 2000
            });
        }
    }

    const register_accounts = () => {
        var emp_no = document.getElementById('emp_no').value;
        var full_name = document.getElementById('full_name').value;
        var dept = document.getElementById('dept').value;
        var section = document.getElementById('section').value;
        var line_no = document.getElementById('line_no').value;
        var shift_group = document.getElementById('shift_group').value;
        var role = document.getElementById('role').value;

        if (emp_no == '') {
            Swal.fire({
                icon: 'info',
                title: 'Please Input Employee No !!!',
                text: 'Information',
                showConfirmButton: false,
                timer: 1000
            });
        } else if (full_name == '') {
            Swal.fire({
                icon: 'info',
                title: 'Please Input Full Name !!!',
                text: 'Information',
                showConfirmButton: false,
                timer: 1000
            });
        } else if (dept == '') {
            Swal.fire({
                icon: 'info',
                title: 'Please Set Department !!!',
                text: 'Information',
                showConfirmButton: false,
                timer: 1000
            });
        } else if (role == '') {
            Swal.fire({
                icon: 'info',
                title: 'Please Select User Type !!!',
                text: 'Information',
                showConfirmButton: false,
                timer: 1000
            });
        } else {
            $.ajax({
                url: '../process/admin/accounts/acct-management_p.php',
                type: 'POST',
                cache: false,
                data: {
                    method: 'register_account',
                    emp_no: emp_no,
                    full_name: full_name,
                    dept: dept,
                    section: section,
                    line_no: line_no,
                    shift_group: shift_group,
                    role: role
                }, success: function (response) {
                    if (response == 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Succesfully Recorded!!!',
                            text: 'Success',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        document.getElementById("emp_no").value = '';
                        document.getElementById("full_name").value = '';
                        document.getElementById("dept").value = '';
                        document.getElementById("section").value = '';
                        document.getElementById("line_no").value = '';
                        document.getElementById("shift_group").value = '';
                        document.getElementById("role").value = '';
                        load_accounts(1);
                        $('#new_account').modal('hide');
                    } else if (response == 'Already Exist') {
                        Swal.fire({
                            icon: 'info',
                            title: 'Duplicate Data !!!',
                            text: 'Information',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error !!!',
                            text: 'Error',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                }
            });
        }
    }

    const get_accounts_details = (param) => {
        var string = param.split('~!~');
        var id = string[0];
        var emp_no = string[1];
        var full_name = string[2];
        var dept = string[3];
        var section = string[4];
        var line_no = string[5];
        var role = string[6];
        var shift_group = string[7];

        document.getElementById('id_account_update').value = id;
        document.getElementById('emp_no_update').value = emp_no;
        document.getElementById('full_name_update').value = full_name;
        document.getElementById('dept_update').value = dept;
        document.getElementById('section_update').value = section;
        document.getElementById('line_no_update').value = line_no;
        document.getElementById('shift_group_update').value = shift_group;
        document.getElementById('role_update').value = role;
    }

    const update_account = () => {
        var id = document.getElementById('id_account_update').value;
        var emp_no = document.getElementById('emp_no_update').value;
        var full_name = document.getElementById('full_name_update').value;
        var dept = document.getElementById('dept_update').value;
        var section = document.getElementById('section_update').value;
        var line_no = document.getElementById('line_no_update').value;
        var shift_group = document.getElementById('shift_group_update').value;
        var role = document.getElementById('role_update').value;

        if (emp_no == '') {
            Swal.fire({
                icon: 'info',
                title: 'Please Input Employee No !!!',
                text: 'Information',
                showConfirmButton: false,
                timer: 1000
            });
        } else if (full_name == '') {
            Swal.fire({
                icon: 'info',
                title: 'Please Input Full Name !!!',
                text: 'Information',
                showConfirmButton: false,
                timer: 1000
            });
        } else if (dept == '') {
            Swal.fire({
                icon: 'info',
                title: 'Please Set Department !!!',
                text: 'Information',
                showConfirmButton: false,
                timer: 1000
            });
        } else if (role == '') {
            Swal.fire({
                icon: 'info',
                title: 'Please Select User Type !!!',
                text: 'Information',
                showConfirmButton: false,
                timer: 1000
            });
        } else {
            $.ajax({
                url: '../process/admin/accounts/acct-management_p.php',
                type: 'POST',
                cache: false,
                data: {
                    method: 'update_account',
                    id: id,
                    emp_no: emp_no,
                    full_name: full_name,
                    dept: dept,
                    section: section,
                    line_no: line_no,
                    shift_group: shift_group,
                    role: role
                }, success: function (response) {
                    if (response == 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Succesfully Recorded!!!',
                            text: 'Success',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        document.getElementById('id_account_update').value = '';
                        document.getElementById('emp_no_update').value = '';
                        document.getElementById('full_name_update').value = '';
                        document.getElementById('dept_update').value = '';
                        document.getElementById('section_update').value = '';
                        document.getElementById('line_no_update').value = '';
                        document.getElementById('shift_group_update').value = '';
                        document.getElementById('role_update').value = '';
                        document.getElementById('resigned_master_update').checked = false;
                        load_accounts(1);
                        $('#update_account').modal('hide');
                    } else if (response == 'duplicate') {
                        Swal.fire({
                            icon: 'info',
                            title: 'Duplicate Data !!!',
                            text: 'Information',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error !!!',
                            text: 'Error',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                }
            });
        }
    }

    const delete_account = () => {
        var id = document.getElementById('id_account_update').value;
        $.ajax({
            url: '../process/admin/accounts/acct-management_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'delete_account',
                id: id
            }, success: function (response) {
                if (response == 'success') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Succesfully Deleted !!!',
                        text: 'Information',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    document.getElementById('id_account_update').value = '';
                    document.getElementById('emp_no_update').value = '';
                    document.getElementById('full_name_update').value = '';
                    document.getElementById('dept_update').value = '';
                    document.getElementById('section_update').value = '';
                    document.getElementById('line_no_update').value = '';
                    document.getElementById('shift_group_update').value = '';
                    document.getElementById('role_update').value = '';
                    document.getElementById('resigned_master_update').checked = false;
                    load_accounts(1);
                    $('#update_account').modal('hide');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error !!!',
                        text: 'Error',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            }
        });
    }
</script>