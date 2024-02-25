<script type="text/javascript">
    // Global Variables for Realtime Count
    var realtime_count_emp_dashboard;

    $(document).ready(function () {
        fetch_dept_dropdown();
        count_emp_dashboard();
        realtime_count_emp_dashboard = setInterval(count_emp_dashboard, 30000);
        sessionStorage.setItem('notif_pending_ls', 0);
        sessionStorage.setItem('notif_accepted_ls', 0);
        sessionStorage.setItem('notif_rejected_ls', 0);
        load_notif_line_support();
        realtime_load_notif_line_support = setInterval(load_notif_line_support, 5000);
    });

    document.getElementById("section_master_search").addEventListener("keyup", e => {
        if (e.which === 13) {
            e.preventDefault();
            count_emp_dashboard();
        }
    });

    document.getElementById("line_no_master_search").addEventListener("keyup", e => {
        if (e.which === 13) {
            e.preventDefault();
            count_emp_dashboard();
        }
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

    const count_emp_dashboard = () => {
        let dept = document.getElementById('dept_master_search').value;
        let section = document.getElementById('section_master_search').value;
        let line_no = document.getElementById('line_no_master_search').value;
        $.ajax({
            url: '../process/hr/dashboard/dash_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'count_emp_dashboard',
                dept: dept,
                section: section,
                line_no: line_no
            },
            success: function (response) {
                try {
                    let response_array = JSON.parse(response);
                    $('#count_emp_dashboard_value_total').html(`<b>${response_array.total}</b>`);
                    $('#count_emp_dashboard_value_ds').html(`<b>${response_array.total_shift_group_a}</b>`);
                    $('#count_emp_dashboard_present_value_ds').html(`<b>${response_array.total_present_ds}</b>`);
                    $('#count_emp_dashboard_absent_value_ds').html(`<b>${response_array.total_absent_ds}</b>`);
                    $('#count_emp_dashboard_support_value_ds').html(`<b>${response_array.total_support_ds}</b>`);
                    $('#count_emp_dashboard_value_ns').html(`<b>${response_array.total_shift_group_b}</b>`);
                    $('#count_emp_dashboard_present_value_ns').html(`<b>${response_array.total_present_ns}</b>`);
                    $('#count_emp_dashboard_absent_value_ns').html(`<b>${response_array.total_absent_ns}</b>`);
                    $('#count_emp_dashboard_support_value_ns').html(`<b>${response_array.total_support_ns}</b>`);
                    $('#count_emp_dashboard_value_ads').html(`<b>${response_array.total_shift_group_ads}</b>`);
                    $('#count_emp_dashboard_present_value_ads').html(`<b>${response_array.total_present_ads}</b>`);
                    $('#count_emp_dashboard_absent_value_ads').html(`<b>${response_array.total_absent_ads}</b>`);
                    $('#count_emp_dashboard_support_value_ads').html(`<b>${response_array.total_support_ads}</b>`);
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
        let dept = sessionStorage.getItem('dept_master_search');
        let section = sessionStorage.getItem('section_master_search');
        let line_no = sessionStorage.getItem('line_no_master_search');
        $.ajax({
            url: '../process/hr/dashboard/dash_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'count_emp_provider_dashboard',
                dept: dept,
                section: section,
                line_no: line_no,
                shift: 'DS',
                shift_group: 'A'
            },
            success: function (response) {
                $('#count_emp_provider_dashboard_ds').html(response);
            }
        });
    }

    const count_emp_provider_dashboard_ns = () => {
        let dept = sessionStorage.getItem('dept_master_search');
        let section = sessionStorage.getItem('section_master_search');
        let line_no = sessionStorage.getItem('line_no_master_search');
        $.ajax({
            url: '../process/hr/dashboard/dash_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'count_emp_provider_dashboard',
                dept: dept,
                section: section,
                line_no: line_no,
                shift: 'NS',
                shift_group: 'B'
            },
            success: function (response) {
                $('#count_emp_provider_dashboard_ns').html(response);
            }
        });
    }

    const count_emp_provider_dashboard_ads = () => {
        let dept = sessionStorage.getItem('dept_master_search');
        let section = sessionStorage.getItem('section_master_search');
        let line_no = sessionStorage.getItem('line_no_master_search');
        $.ajax({
            url: '../process/hr/dashboard/dash_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'count_emp_provider_dashboard',
                dept: dept,
                section: section,
                line_no: line_no,
                shift: 'DS',
                shift_group: 'ADS'
            },
            success: function (response) {
                $('#count_emp_provider_dashboard_ads').html(response);
            }
        });
    }

    const export_dashboard = () => {
        var dept = sessionStorage.getItem('dept_master_search');
        var section = sessionStorage.getItem('section_master_search');
        var line_no = sessionStorage.getItem('line_no_master_search');
        window.open('../process/export/exp_dashboard.php?dept=' + dept + '&section=' + section + '&line_no=' + line_no, '_blank');
    }
</script>