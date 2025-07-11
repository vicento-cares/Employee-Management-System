<script type="text/javascript">
    // Global Variables for Realtime Count
    var realtime_count_emp_dashboard;

    // Charts
    let daily_absent_rate_chart;

    const count_od = () => {
        let day = document.getElementById('od_date_search').value;
        let dept = document.getElementById('od_dept_search').value;
        let section = document.getElementById('od_section_search').value;
        let line_no = document.getElementById('od_line_no_search').value;

        $.ajax({
            url: '../process/hr/dashboard/dash_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'count_od',
                day: day,
                dept: dept,
                section: section,
                line_no: line_no
            },
            success: function (response) {
                try {
                    let response_array = JSON.parse(response);
                    $('#od_present_ds').html(`<b>${response_array.od_present_ds}</b>`);
                    $('#od_present_ns').html(`<b>${response_array.od_present_ns}</b>`);
                    $('#od_present_total').html(`<b>${response_array.od_present_total}</b>`);
                    $('#od_registered_ds').html(`<b>${response_array.od_registered_ds}</b>`);
                    $('#od_registered_ns').html(`<b>${response_array.od_registered_ns}</b>`);
                    $('#od_registered_total').html(`<b>${response_array.od_registered_total}</b>`);
                    $('#od_absent_rate_ds').html(`<b>${response_array.od_absent_rate_ds}</b>`);
                    $('#od_absent_rate_ns').html(`<b>${response_array.od_absent_rate_ns}</b>`);
                    $('#od_absent_rate').html(`<b>${response_array.od_absent_rate}</b>`);

                    sessionStorage.setItem('emp_mgt_od_day_search', day);
                    sessionStorage.setItem('emp_mgt_od_dept_search', dept);
                    sessionStorage.setItem('emp_mgt_od_section_search', section);
                    sessionStorage.setItem('emp_mgt_od_line_no_search', line_no);

                    get_daily_absent_rate_chart();
                    get_daily_absent_rate_provider_chart();
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

    const get_daily_absent_rate_chart = () => {
        let day = sessionStorage.getItem('emp_mgt_od_day_search');
        let dept = sessionStorage.getItem('emp_mgt_od_dept_search');
        let section = sessionStorage.getItem('emp_mgt_od_section_search');
        let line_no = sessionStorage.getItem('emp_mgt_od_line_no_search');

		$.ajax({
			url: '../process/hr/dashboard/dash_p.php',
			type: 'POST',
			cache: false,
			dataType: 'json', 
            data: {
                method: 'get_daily_absent_rate_chart',
                day: day,
                dept: dept,
                section: section,
                line_no: line_no
			},
			success: response => {
				console.log(response.categories);
				console.log(response.data);
				console.log(response.colorMap);

                document.getElementById('daily_absent_rate_chart').innerHTML = '';

				// Define Bootstrap 4 colors
				const bootstrapColors = ['#dc3545']; // Added a color for the line chart

				// Convert the data object to an array
				const seriesData = response.data.map(item => {
					return {
						name: item.name,
						data: Object.values(item.data)
					};
				});

				let ctx = document.querySelector("#daily_absent_rate_chart");

				var options = {
					chart: {
						type: 'line',
						height: 300
					},
					series: seriesData,
					colors: bootstrapColors,
					xaxis: {
						categories: response.categories
					},
                    yaxis: {
                        max: 50
                    },
					title: {
						text: `Daily Absent Rate Trend`,
						align: 'left'
					},
					stroke: {
						curve: 'smooth'
					},
					markers: {
						size: 5
					},
					tooltip: {
						shared: true,
						intersect: false
					}
				};

				// Destroy previous chart instance before creating a new one
				if (daily_absent_rate_chart) {
					daily_absent_rate_chart.destroy();
				}

				daily_absent_rate_chart = new ApexCharts(ctx, options);
				daily_absent_rate_chart.render();
			}
		});
	}

    const get_daily_absent_rate_provider_chart = () => {
        let day = sessionStorage.getItem('emp_mgt_od_day_search');
        let dept = sessionStorage.getItem('emp_mgt_od_dept_search');
        let section = sessionStorage.getItem('emp_mgt_od_section_search');
        let line_no = sessionStorage.getItem('emp_mgt_od_line_no_search');

        $.ajax({
            url: '../process/hr/dashboard/dash_p.php',
            type: 'POST',
            cache: false,
            dataType: 'json', 
            data: {
                method: 'get_daily_absent_rate_provider_chart',
                day: day,
                dept: dept,
                section: section,
                line_no: line_no
            },
            success: response => {
                console.log(response.categories);
                console.log(response.data);
                console.log(response.colorMap);

                // Clear previous charts
                const chartContainer = document.querySelector("#daily_absent_rate_provider_chart");
                chartContainer.innerHTML = ''; // Clear existing charts

                // Loop through each provider and create a chart
                response.data.forEach((item, index) => {
                    // Create a new column div for each chart
                    const colDiv = document.createElement('div');
                    colDiv.className = 'col-6'; // Adjust this class to control the number of charts per row
                    colDiv.style.marginBottom = '20px'; // Add some spacing

                    // Create a new canvas element for the chart
                    const canvas = document.createElement('div');
                    canvas.id = `chart-${index}`; // Unique ID for each chart
                    colDiv.appendChild(canvas); // Append the canvas to the column div

                    // Append the column div to the chart container
                    chartContainer.appendChild(colDiv);

                    // Prepare the chart options
                    const options = {
                        chart: {
                            type: 'line',
                            height: 300
                        },
                        series: [{
                            name: item.name,
                            data: Object.values(item.data)
                        }],
                        colors: [response.colorMap[item.name] || '#dc3545'], // Use color from colorMap or default
                        xaxis: {
                            categories: response.categories
                        },
                        yaxis: {
                            max: 50
                        },
                        title: {
                            text: `${item.name} Daily Absent Rate Trend`,
                            align: 'left'
                        },
                        stroke: {
                            curve: 'smooth'
                        },
                        markers: {
                            size: 5
                        },
                        tooltip: {
                            shared: true,
                            intersect: false
                        }
                    };

                    // Create and render the chart
                    const chart = new ApexCharts(canvas, options);
                    chart.render();
                });
            }
        });
    }

    $(document).ready(function () {
        setTimeout(() => {
            document.getElementById('od_date_search').value = '<?= $server_date_only ?>';
            count_od();
        }, 1000);

        fetch_dept_dropdown();
        fetch_section_dropdown();
        fetch_line_dropdown();

        document.getElementById('attendance_date_search').value = '<?= $server_date_only ?>';
        count_emp_dashboard();
        realtime_count_emp_dashboard = setInterval(count_emp_dashboard, 30000);
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
        window.open('../process/export/exp_dashboard_hr.php?day=' + day + '&dept=' + dept + '&section=' + section + '&line_no=' + line_no, '_blank');
    }
</script>