<script type="text/javascript">
    // DOMContentLoaded function
    document.addEventListener("DOMContentLoaded", () => {
        fetch_shuttle_route_dropdown();
        get_shuttle_allocation_date_shift();
        sessionStorage.setItem('notif_pending_ls', 0);
        sessionStorage.setItem('notif_accepted_ls', 0);
        sessionStorage.setItem('notif_rejected_ls', 0);
        load_notif_line_support();
        realtime_load_notif_line_support = setInterval(load_notif_line_support, 5000);
    });

    const fetch_shuttle_route_dropdown = () => {
        $.ajax({
            url: '../process/admin/shuttle_allocation/sa_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'fetch_shuttle_route_dropdown'
            },
            success: function (response) {
                $('#shuttle_route_sa_update').html(response);
            }
        });
    }

    const get_shuttle_allocation_date_shift = () => {
        $.ajax({
            type: "POST",
            url: "../process/admin/shuttle_allocation/sa_p.php",
            cache: false,
            data: {
                method: "get_shuttle_allocation_date_shift"
            },
            success: (response) => {
                try {
                    let response_array = JSON.parse(response);
                    document.getElementById('shuttle_allocation_date').value = response_array.date;
                    document.getElementById('shuttle_allocation_shift').innerHTML = response_array.shift;
                    get_shuttle_allocation();
                } catch (e) {
                    console.log(response);
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

    const get_shuttle_allocation = () => {
        let day = document.getElementById('shuttle_allocation_date').value;
        let shift_group = document.getElementById('shuttle_allocation_shift_group').value;
        $.ajax({
            url: '../process/admin/shuttle_allocation/sa_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'get_shuttle_allocation',
                day: day,
                shift_group: shift_group
            },
            beforeSend: () => {
                var loading = `<tr><td colspan="13" style="text-align:center;"><div class="spinner-border text-dark" role="status"><span class="sr-only">Loading...</span></div></td></tr>`;
                document.getElementById("shuttleAllocationData").innerHTML = loading;
            },
            success: function (response) {
                $('#shuttleAllocationData').html(response);
                let present = parseInt(document.getElementById("shuttleAllocationData").childNodes.length);
                $('#count_view_present').html(present);
                document.getElementById("btnOut5").setAttribute('disabled', true);
                document.getElementById("btnOut6").setAttribute('disabled', true);
                document.getElementById("btnOut7").setAttribute('disabled', true);
                document.getElementById("btnOut8").setAttribute('disabled', true);
                get_shuttle_allocation_per_route();
                get_shuttle_allocation_total();
            }
        });
    }

    const get_shuttle_allocation_total = () => {
        let day = document.getElementById('shuttle_allocation_date').value;
        let shift_group = document.getElementById('shuttle_allocation_shift_group').value;
        $.ajax({
            url: '../process/admin/shuttle_allocation/sa_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'get_shuttle_allocation_total',
                day: day,
                shift_group: shift_group
            },
            success: function (response) {
                try {
                    let response_array = JSON.parse(response);
                    document.getElementById('total_out_5').innerHTML = response_array.total_out_5;
                    document.getElementById('total_out_6').innerHTML = response_array.total_out_6;
                    document.getElementById('total_out_7').innerHTML = response_array.total_out_7;
                    document.getElementById('total_out_8').innerHTML = response_array.total_out_8;
                    document.getElementById('sr_total_out_5').innerHTML = response_array.total_out_5;
                    document.getElementById('sr_total_out_6').innerHTML = response_array.total_out_6;
                    document.getElementById('sr_total_out_7').innerHTML = response_array.total_out_7;
                    document.getElementById('sr_total_out_8').innerHTML = response_array.total_out_8;
                } catch (e) {
                    console.log(response);
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

    // uncheck all
    const uncheck_all_present = () => {
        var select_all = document.getElementById('check_all_present');
        select_all.checked = false;
        document.querySelectorAll(".singleCheck").forEach((el, i) => {
            el.checked = false;
        });
        get_checked_length_present();
    }
    // check all
    const select_all_func_present = () => {
        var select_all = document.getElementById('check_all_present');
        if (select_all.checked == true) {
            console.log('check');
            document.querySelectorAll(".singleCheck").forEach((el, i) => {
                el.checked = true;
            });
        } else {
            console.log('uncheck');
            document.querySelectorAll(".singleCheck").forEach((el, i) => {
                el.checked = false;
            });
        }
        get_checked_length_present();
    }
    // GET THE LENGTH OF CHECKED CHECKBOXES
    const get_checked_length_present = () => {
        var arr = [];
        document.querySelectorAll("input.singleCheck[type='checkbox']:checked").forEach((el, i) => {
            arr.push(el.value);
        });
        console.log(arr);
        var numberOfChecked = arr.length;
        console.log(numberOfChecked);
        if (numberOfChecked > 0) {
            document.getElementById("btnOut5").removeAttribute('disabled');
            document.getElementById("btnOut6").removeAttribute('disabled');
            document.getElementById("btnOut7").removeAttribute('disabled');
            document.getElementById("btnOut8").removeAttribute('disabled');
        } else {
            document.getElementById("btnOut5").setAttribute('disabled', true);
            document.getElementById("btnOut6").setAttribute('disabled', true);
            document.getElementById("btnOut7").setAttribute('disabled', true);
            document.getElementById("btnOut8").setAttribute('disabled', true);
        }
    }

    const verify_set_out = time => {
        sessionStorage.setItem('set_out', time);
        $('#admin_verification').modal('show');
    }

    document.getElementById("emp_no_verify").addEventListener("keyup", e => {
        if (e.which === 13) {
            e.preventDefault();
            var emp_no = document.getElementById('emp_no_verify').value;

            admin_verification((message) => {
                if (message == "success") {
                    $('#admin_verification').modal('hide');
                    set_out();
                    sessionStorage.setItem('set_out', "");
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

    const set_out = time => {
        var time = sessionStorage.getItem('set_out');
        var arr = [];
        document.querySelectorAll("input.singleCheck[type='checkbox']:checked").forEach((el, i) => {
            arr.push(el.value);
        });
        console.log(arr);

        var numberOfChecked = arr.length;
        if (numberOfChecked > 0) {
            $.ajax({
                url: '../process/admin/shuttle_allocation/sa_p.php',
                type: 'POST',
                cache: false,
                data: {
                    method: 'set_out',
                    arr: arr,
                    time: time
                },
                beforeSend: () => {
                    Swal.fire({
                        icon: 'info',
                        title: 'Set Shuttle Allocation in Progress...',
                        text: 'Info',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        allowEnterKey: false
                    });
                },
                success: function (response) {
                    if (response == 'success') {
                        setTimeout(() => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Shuttle Route Updated Successfully',
                                text: 'Success',
                                showConfirmButton: false,
                                timer: 1000
                            });
                            get_shuttle_allocation();
                        }, 500);
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
    }


    const get_shuttle_allocation_details = (param) => {
        var string = param.split('~!~');
        var sa_id = string[0];
        var sa_shuttle_route = string[1];

        document.getElementById('id_sa_update').value = sa_id;
        document.getElementById('shuttle_route_sa_update').value = sa_shuttle_route;
    }

    const update_shuttle_route = () => {
        var id = document.getElementById('id_sa_update').value;
        var shuttle_route = document.getElementById('shuttle_route_sa_update').value;

        if (shuttle_route == '') {
            Swal.fire({
                icon: 'info',
                title: 'Please Select Shuttle Route !!!',
                text: 'Information',
                showConfirmButton: false,
                timer: 1000
            });
        } else {
            $.ajax({
                url: '../process/admin/shuttle_allocation/sa_p.php',
                type: 'POST',
                cache: false,
                data: {
                    method: 'update_shuttle_route',
                    id: id,
                    shuttle_route: shuttle_route
                }, success: function (response) {
                    if (response == 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Shuttle Route Updated Successfully',
                            text: 'Success',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        $('#id_sa_update').val('');
                        $('#shuttle_route_sa_update').val('').trigger('change');
                        get_shuttle_allocation();
                        $('#update_shuttle_route').modal('hide');
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
    }

    const get_shuttle_allocation_per_route = () => {
        let day = document.getElementById('shuttle_allocation_date').value;
        let shift_group = document.getElementById('shuttle_allocation_shift_group').value;
        $.ajax({
            url: '../process/admin/shuttle_allocation/sa_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'get_shuttle_allocation_per_route',
                day: day,
                shift_group: shift_group
            },
            beforeSend: () => {
                var loading = `<tr><td colspan="5" style="text-align:center;"><div class="spinner-border text-dark" role="status"><span class="sr-only">Loading...</span></div></td></tr>`;
                document.getElementById("shuttleAllocationPerRouteData").innerHTML = loading;
            },
            success: function (response) {
                $('#shuttleAllocationPerRouteData').html(response);
            }
        });
    }
</script>