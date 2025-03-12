<script type="text/javascript">
    // Global Variables for Realtime Count
    var realtime_count_emp_dashboard;

    $(document).ready(function () {
        fetch_dept_dropdown();
        // fetch_group_dropdown();
        fetch_section_dropdown();
        fetch_line_dropdown();

        <?php
            // PCAD GET Params
            if (isset($_GET['pcad_exec_server_date_only'])) {
                $server_date_only = $_GET['pcad_exec_server_date_only'];
            }

            if (isset($_GET['dept'])) {
                $dept = $_GET['dept'];
            }

            if (isset($_GET['section'])) {
                $section = $_GET['section'];
            }

            if (isset($_GET['line_no'])) {
                $line_no = $_GET['line_no'];
            }
        ?>

        document.getElementById('attendance_date_search').value = '<?= $server_date_only ?>';
        <?php if (!empty($dept) && !empty($section) && !empty($line_no)) { ?>
        console.log('Dept:', '<?= $dept ?>');
        console.log('Section:', '<?= $section ?>');
        console.log('Line No:', '<?= $line_no ?>');
        setTimeout(() => {
            document.getElementById('dept_master_search').value = '<?= $dept ?>';
            document.getElementById('section_master_search').value = '<?= $section ?>';
            document.getElementById('line_no_master_search').value = '<?= $line_no ?>';
            count_emp_dashboard();
            realtime_count_emp_dashboard = setInterval(count_emp_dashboard, 30000);
        }, 1000);
        <?php } else { ?>
        count_emp_dashboard();
        realtime_count_emp_dashboard = setInterval(count_emp_dashboard, 30000);
        <?php } ?>
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
                $('#dept_master_search').html(response);
            }
        });
    }

    const fetch_group_dropdown = () => {
        $.ajax({
            url: '../process/hr/employees/emp-masterlist_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'fetch_group_dropdown'
            },
            success: function (response) {
                $('#group_master_search').html(response);
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
                $('#section_master_search').html(response);
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
                $('#line_no_master_search').html(response);
            }
        });
    }

    const count_emp_dashboard = () => {
        let day = document.getElementById('attendance_date_search').value;
        let dept = document.getElementById('dept_master_search').value;
        let section = document.getElementById('section_master_search').value;
        let line_no = document.getElementById('line_no_master_search').value;
        $.ajax({
            url: '../process/hr/dashboard/dash_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'count_emp_dashboard',
                day: day,
                dept: dept,
                section: section,
                line_no: line_no
            },
            success: function (response) {
                try {
                    let response_array = JSON.parse(response);
                    $('#count_emp_dashboard_value_total').html(`<b>${response_array.total}</b>`);
                    $('#count_emp_dashboard_value_total_percentage').html(`<b>${response_array.attendance_percentage_total}%</b>`);
                    $('#count_emp_dashboard_value_ds').html(`<b>${response_array.total_shift_group_a}</b>`);
                    $('#count_emp_dashboard_value_ds_percentage').html(`<b>${response_array.attendance_percentage_ds}%</b>`);
                    $('#count_emp_dashboard_present_value_ds').html(`<b>${response_array.total_present_ds}</b>`);
                    $('#count_emp_dashboard_absent_value_ds').html(`<b>${response_array.total_absent_ds}</b>`);
                    $('#count_emp_dashboard_support_value_ds').html(`<b>${response_array.total_support_ds}</b>`);
                    $('#count_emp_dashboard_value_ns').html(`<b>${response_array.total_shift_group_b}</b>`);
                    $('#count_emp_dashboard_value_ns_percentage').html(`<b>${response_array.attendance_percentage_ns}%</b>`);
                    $('#count_emp_dashboard_present_value_ns').html(`<b>${response_array.total_present_ns}</b>`);
                    $('#count_emp_dashboard_absent_value_ns').html(`<b>${response_array.total_absent_ns}</b>`);
                    $('#count_emp_dashboard_support_value_ns').html(`<b>${response_array.total_support_ns}</b>`);
                    $('#count_emp_dashboard_value_ads').html(`<b>${response_array.total_shift_group_ads}</b>`);
                    $('#count_emp_dashboard_value_ads_percentage').html(`<b>${response_array.attendance_percentage_ads}%</b>`);
                    $('#count_emp_dashboard_present_value_ads').html(`<b>${response_array.total_present_ads}</b>`);
                    $('#count_emp_dashboard_absent_value_ads').html(`<b>${response_array.total_absent_ads}</b>`);
                    $('#count_emp_dashboard_support_value_ads').html(`<b>${response_array.total_support_ads}</b>`);
                    sessionStorage.setItem('attendance_date_search', day);
                    sessionStorage.setItem('dept_master_search', dept);
                    sessionStorage.setItem('section_master_search', section);
                    sessionStorage.setItem('line_no_master_search', line_no);
                    count_emp_provider_dashboard_ds();
                    count_emp_provider_dashboard_ns();
                    count_emp_provider_dashboard_ads();
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

    const count_emp_provider_dashboard_ds = () => {
        let day = sessionStorage.getItem('attendance_date_search');
        let dept = sessionStorage.getItem('dept_master_search');
        let section = sessionStorage.getItem('section_master_search');
        let line_no = sessionStorage.getItem('line_no_master_search');
        $.ajax({
            url: '../process/hr/dashboard/dash_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'count_emp_provider_dashboard',
                day: day,
                dept: dept,
                section: section,
                line_no: line_no,
                shift_group: 'A'
            },
            success: function (response) {
                $('#count_emp_provider_dashboard_ds').html(response);
            }
        });
    }

    const count_emp_provider_dashboard_ns = () => {
        let day = sessionStorage.getItem('attendance_date_search');
        let dept = sessionStorage.getItem('dept_master_search');
        let section = sessionStorage.getItem('section_master_search');
        let line_no = sessionStorage.getItem('line_no_master_search');
        $.ajax({
            url: '../process/hr/dashboard/dash_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'count_emp_provider_dashboard',
                day: day,
                dept: dept,
                section: section,
                line_no: line_no,
                shift_group: 'B'
            },
            success: function (response) {
                $('#count_emp_provider_dashboard_ns').html(response);
            }
        });
    }

    const count_emp_provider_dashboard_ads = () => {
        let day = sessionStorage.getItem('attendance_date_search');
        let dept = sessionStorage.getItem('dept_master_search');
        let section = sessionStorage.getItem('section_master_search');
        let line_no = sessionStorage.getItem('line_no_master_search');
        $.ajax({
            url: '../process/hr/dashboard/dash_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'count_emp_provider_dashboard',
                day: day,
                dept: dept,
                section: section,
                line_no: line_no,
                shift_group: 'ADS'
            },
            success: function (response) {
                $('#count_emp_provider_dashboard_ads').html(response);
            }
        });
    }

    const export_dashboard = () => {
        var day = sessionStorage.getItem('attendance_date_search');
        var dept = sessionStorage.getItem('dept_master_search');
        var section = sessionStorage.getItem('section_master_search');
        var line_no = sessionStorage.getItem('line_no_master_search');
        window.open('../process/export/exp_dashboard.php?day=' + day + '&dept=' + dept + '&section=' + section + '&line_no=' + line_no, '_blank');
    }
</script>