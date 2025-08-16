<script type="text/javascript">
    // DOMContentLoaded function
    document.addEventListener("DOMContentLoaded", () => {
        get_shuttle_allocation_per_sr();

        setInterval(get_shuttle_allocation_per_sr, 60000);

        fetch_dept_dropdown();
        fetch_section_dropdown();
        fetch_line_dropdown_search();
        fetch_shuttle_route_dropdown();
        get_shuttle_allocation_date_shift();
    });

    const get_shuttle_allocation_per_sr = () => {
        let section = document.getElementById('sa_summary_section').value;
        $.ajax({
            url: '../process/admin/shuttle_allocation/sa_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'get_shuttle_allocation_per_sr',
                section: section
            },
            success: function (response) {
                $('#shuttleAllocationPerSectionRouteData').html(response);
                get_shuttle_allocation_per_section();
            }
        });
    }

    const get_shuttle_allocation_per_section = () => {
        let section = document.getElementById('sa_summary_section').value;
        $.ajax({
            url: '../process/admin/shuttle_allocation/sa_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'get_shuttle_allocation_per_section',
                section: section
            },
            success: function (response) {
                $('#shuttleAllocationPerSectionData').html(response);
            }
        });
    }

    const export_shuttle_allocation_summary = () => {
        let separator = ',';
        let day = document.getElementById('shuttle_allocation_date').value;
        let table_id_list = ["shuttleAllocationPerSectionRoute", "shuttleAllocationPerSection"];

        // Construct csv
        var csv = [];

        // Loop through each table ID
        for (let table_id of table_id_list) {
            var rows = document.querySelectorAll('table#' + table_id + ' tr');

            for (var i = 0; i < rows.length; i++) {
                var row = [], cols = rows[i].querySelectorAll('td, th');
                // get td th that has no and one child element
                var filteredCols = Array.from(cols).filter(col => 
                    (col.childNodes.length === 1 && col.childNodes[0].nodeType === Node.TEXT_NODE) || 
                    (col.childNodes.length === 0)
                );
                for (var j = 0; j < filteredCols.length; j++) {
                    var data = filteredCols[j].innerText.replace(/(\r\n|\n|\r)/gm, '').replace(/(\s\s)/gm, ' ')
                    data = data.replace(/"/g, '""');
                    // Push escaped string
                    row.push('"' + data + '"');
                }
                csv.push(row.join(separator));
            }

            // Add an empty row after each table
            csv.push(''); // This adds an empty row
        }

        // Join all rows into a single CSV string
        var csv_string = csv.join('\n');

        // Download it
        var filename = 'EmpMgtSys_ShuttleAllocationSummary_' + day + '.csv';
        var link = document.createElement('a');
        link.style.display = 'none';
        link.setAttribute('target', '_blank');
        link.setAttribute('href', 'data:text/csv;charset=utf-8,%EF%BB%BF' + encodeURIComponent(csv_string));
        link.setAttribute('download', filename);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    const fetch_dept_dropdown = () => {
        $.ajax({
            url: '../process/hr/employees/emp-masterlist_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'fetch_dept_dropdown'
            },
            success: function (response) {
                $('#shuttle_allocation_dept').html(response);
                $('#sa_dept_search').html(response);
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
                $('#sa_summary_section').html(response);
                $('#shuttle_allocation_section').html(response);
                $('#sa_section_search').html(response);
            }
        });
    }

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
                $('#shuttle_allocation_line_no').html(response);
                $('#sa_line_no_search').html(response);
            }
        });
    }

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
        let dept = document.getElementById('shuttle_allocation_dept').value;
        let section = document.getElementById('shuttle_allocation_section').value;
        let line_no = document.getElementById('shuttle_allocation_line_no').value;
        let emp_no = document.getElementById('shuttle_allocation_emp_no').value;
        $.ajax({
            url: '../process/admin/shuttle_allocation/sa_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'get_shuttle_allocation',
                day: day,
                shift_group: shift_group,
                dept: dept,
                section: section,
                line_no: line_no,
                emp_no: emp_no
            },
            beforeSend: () => {
                var loading = `<tr><td colspan="13" style="text-align:center;"><div class="spinner-border text-dark" role="status"><span class="sr-only">Loading...</span></div></td></tr>`;
                document.getElementById("shuttleAllocationData").innerHTML = loading;
            },
            success: function (response) {
                $('#shuttleAllocationData').html(response);
                let present = parseInt(document.getElementById("shuttleAllocationData").childNodes.length) - 1;
                $('#count_view_present').html(present);
                document.getElementById("btnOut5").setAttribute('disabled', true);
                document.getElementById("btnOut6").setAttribute('disabled', true);
                document.getElementById("btnOut7").setAttribute('disabled', true);
                document.getElementById("btnOut8").setAttribute('disabled', true);
                get_shuttle_allocation_per_route();
            }
        });
    }

    const get_shuttle_allocation_per_route = () => {
        let day = document.getElementById('shuttle_allocation_date').value;
        let shift_group = document.getElementById('shuttle_allocation_shift_group').value;
        let dept = document.getElementById('shuttle_allocation_dept').value;
        let section = document.getElementById('shuttle_allocation_section').value;
        let line_no = document.getElementById('shuttle_allocation_line_no').value;
        let emp_no = document.getElementById('shuttle_allocation_emp_no').value;
        $.ajax({
            url: '../process/admin/shuttle_allocation/sa_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'get_shuttle_allocation_per_route',
                day: day,
                shift_group: shift_group,
                dept: dept,
                section: section,
                line_no: line_no,
                emp_no: emp_no
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

    const export_shuttle_allocation = (table_id, separator = ',') => {
        let day = document.getElementById('shuttle_allocation_date').value;

        // Select rows from table_id

        var rows = document.querySelectorAll('table#' + table_id + ' tr');
        // Construct csv
        var csv = [];
        for (var i = 0; i < rows.length; i++) {
            var row = [], cols = rows[i].querySelectorAll('td, th');
            // get td th that has no and one child element
            var filteredCols = Array.from(cols).filter(col => 
                (col.childNodes.length === 1 && col.childNodes[0].nodeType === Node.TEXT_NODE) || 
                (col.childNodes.length === 0)
            );
            for (var j = 0; j < filteredCols.length; j++) {
                var data = filteredCols[j].innerText.replace(/(\r\n|\n|\r)/gm, '').replace(/(\s\s)/gm, ' ')
                data = data.replace(/"/g, '""');
                // Push escaped string
                row.push('"' + data + '"');
            }
            csv.push(row.join(separator));
        }

        var csv_string = csv.join('\n');
        // Download it

        var filename = 'EmpMgtSys_ShuttleAllocation_' + day + '.csv';
        var link = document.createElement('a');
        link.style.display = 'none';
        link.setAttribute('target', '_blank');
        link.setAttribute('href', 'data:text/csv;charset=utf-8,%EF%BB%BF' + encodeURIComponent(csv_string));
        link.setAttribute('download', filename);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
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
        set_out();
        sessionStorage.setItem('set_out', "");
    }

    const set_out = () => {
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
                            title: 'Shuttle Route Update Failed',
                            text: 'Error: ' + response,
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

    const get_shuttle_allocation_history = () => {
        let day = document.getElementById('sa_date_search').value;
        let shift_group = document.getElementById('sa_shift_group_search').value;
        let shift = document.getElementById('sa_shift_search').value;
        let dept = document.getElementById('sa_dept_search').value;
        let section = document.getElementById('sa_section_search').value;
        let line_no = document.getElementById('sa_line_no_search').value;
        let emp_no = document.getElementById('sa_emp_no_search').value;

        $.ajax({
            url: '../process/admin/shuttle_allocation/sa_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'get_shuttle_allocation_history',
                day: day,
                shift_group: shift_group,
                shift: shift,
                dept: dept,
                section: section,
                line_no: line_no,
                emp_no: emp_no
            },
            beforeSend: () => {
                var loading = `<tr><td colspan="12" style="text-align:center;"><div class="spinner-border text-dark" role="status"><span class="sr-only">Loading...</span></div></td></tr>`;
                document.getElementById("shuttleAllocationHistoryData").innerHTML = loading;
            },
            success: function (response) {
                $('#shuttleAllocationHistoryData').html(response);
                get_shuttle_allocation_history_per_route();
            }
        });
    }

    const get_shuttle_allocation_history_per_route = () => {
        let day = document.getElementById('sa_date_search').value;
        let shift_group = document.getElementById('sa_shift_group_search').value;
        let shift = document.getElementById('sa_shift_search').value;
        let dept = document.getElementById('sa_dept_search').value;
        let section = document.getElementById('sa_section_search').value;
        let line_no = document.getElementById('sa_line_no_search').value;
        let emp_no = document.getElementById('sa_emp_no_search').value;

        $.ajax({
            url: '../process/admin/shuttle_allocation/sa_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'get_shuttle_allocation_history_per_route',
                day: day,
                shift_group: shift_group,
                shift: shift,
                dept: dept,
                section: section,
                line_no: line_no,
                emp_no: emp_no
            },
            beforeSend: () => {
                var loading = `<tr><td colspan="5" style="text-align:center;"><div class="spinner-border text-dark" role="status"><span class="sr-only">Loading...</span></div></td></tr>`;
                document.getElementById("shuttleAllocationHistoryPerRouteData").innerHTML = loading;
            },
            success: function (response) {
                $('#shuttleAllocationHistoryPerRouteData').html(response);
            }
        });
    }
</script>