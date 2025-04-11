<script type="text/javascript">
    // AJAX IN PROGRESS GLOBAL VARS
    var non_compliance_list_ajax_in_process = false;

    // Charts
    let emp_monthly_no_time_out_chart;

    // DOMContentLoaded function
    document.addEventListener("DOMContentLoaded", () => {
        get_non_compliance_year_dropdown_search();
    });

    const get_non_compliance_year_dropdown_search = () => {
        $.ajax({
            url: '../process/hr/attendances/nc_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'get_non_compliance_year_dropdown_search'
            },
            success: function (response) {
                document.getElementById("nc_year_search").innerHTML = response;
            }
        });
    }

    document.getElementById('non_compliance_form').addEventListener('submit', e => {
        e.preventDefault();
        non_compliance_list();
    });

    const non_compliance_list = () => {
        // If an AJAX call is already in progress, return immediately
        if (non_compliance_list_ajax_in_process) {
            return;
        }

        let year = document.getElementById('nc_year_search').value;
        let month = document.getElementById('nc_month_search').value;

        // Set the flag to true as we're starting an AJAX call
        non_compliance_list_ajax_in_process = true;

        $.ajax({
            url: '../process/hr/attendances/nc_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'non_compliance_list',
                year: year,
                month: month
            },
            beforeSend: (jqXHR, settings) => {
                var loading = `<tr id="loading"><td colspan="8" style="text-align:center;"><div class="spinner-border text-dark" role="status"><span class="sr-only">Loading...</span></div></td></tr>`;

                document.getElementById("nonComplianceData").innerHTML = loading;
                
                jqXHR.url = settings.url;
                jqXHR.type = settings.type;
            },
            success: function (response) {
                $('#loading').remove();

                $('#nonComplianceTable tbody').html(response);
                let table_rows = parseInt(document.getElementById("nonComplianceData").childNodes.length);
				$('#count_view').html("Total: " + table_rows);

                sessionStorage.setItem('nc_year_search', year);
                sessionStorage.setItem('nc_month_search', month);

                // Set the flag back to false as the AJAX call has completed
                non_compliance_list_ajax_in_process = false;
            }
        }).fail((jqXHR, textStatus, errorThrown) => {
            console.log(jqXHR);
            console.log(`System Error : Call IT Personnel Immediately!!! They will fix it right away. Error: url: ${jqXHR.url}, method: ${jqXHR.type} ( HTTP ${jqXHR.status} - ${jqXHR.statusText} ) Press F12 to see Console Log for more info.`);
            $('#loading').remove();

            // Set the flag back to false as the AJAX call has completed
            non_compliance_list_ajax_in_process = false;
        });
    }

    const export_non_compliance = (table_id, separator = ',') => {
        let year = sessionStorage.getItem('nc_year_search');
        let month = sessionStorage.getItem('nc_month_search');

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
        var filename = 'EmpMgtSys_NonComplianceList_';

        if (table_id == 'nonComplianceDetailsTable') {
            filename = 'EmpMgtSys_NonComplianceDetails_';
            if (year && month) {
                filename += year + '_' + month;
            }
        } else if (table_id == 'pastNoTimeOutRecordTable') {
            filename = 'EmpMgtSys_PastNoTimeInRecords';
        } else if (year && month) {
			filename += year + '_' + month;
		}

		filename += '.csv';
        var link = document.createElement('a');
        link.style.display = 'none';
        link.setAttribute('target', '_blank');
        link.setAttribute('href', 'data:text/csv;charset=utf-8,%EF%BB%BF' + encodeURIComponent(csv_string));
        link.setAttribute('download', filename);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    const get_emp_monthly_no_time_out_chart = () => {
		let year = sessionStorage.getItem('nc_year_search');
        let emp_no = document.getElementById('emp_no_ncd').innerHTML;
		
		$.ajax({
			url: '../process/hr/attendances/nc_p.php',
			type: 'POST',
			cache: false,
			dataType: 'json',
			data: {
                method: 'get_emp_monthly_no_time_out_chart',
				year: year,
				emp_no: emp_no
			},
			success: response => {
				console.log(response.categories);
				console.log(response.data);

				// Define Bootstrap 4 colors
				const bootstrapColors = ['#dc3545']; // Added a color for the line chart

				// Convert the data object to an array
				const seriesData = response.data.map(item => {
					return {
						name: item.name,
						data: Object.values(item.data)
					};
				});

				let ctx = document.querySelector("#emp_monthly_no_time_out_chart");

				var options = {
					chart: {
						type: 'bar',
						height: 300
					},
					plotOptions: {
						bar: {
							horizontal: false, // Set this to true for horizontal bars
							columnWidth: '50%',
							endingShape: 'flat',
						},
					},
					dataLabels: {
						enabled: false
					},
					series: seriesData,
					colors: bootstrapColors,
					xaxis: {
						categories: response.categories
					},
					title: {
						text: `Monthly No Time In Count at Year ${year}`,
						align: 'left'
					}
				};

				// Destroy previous chart instance before creating a new one
				if (emp_monthly_no_time_out_chart) {
					emp_monthly_no_time_out_chart.destroy();
				}

				emp_monthly_no_time_out_chart = new ApexCharts(ctx, options);
				emp_monthly_no_time_out_chart.render();
			}
		});
	}

    const non_compliance_details_list = () => {
        let year = sessionStorage.getItem('nc_year_search');
        let month = sessionStorage.getItem('nc_month_search');
        let emp_no = document.getElementById('emp_no_ncd').innerHTML;

        $.ajax({
            url: '../process/hr/attendances/nc_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'non_compliance_details_list',
                year: year,
                month: month,
                emp_no: emp_no
            },
            beforeSend: (jqXHR, settings) => {
                var loading = `<tr id="loading_ncd"><td colspan="7" style="text-align:center;"><div class="spinner-border text-dark" role="status"><span class="sr-only">Loading...</span></div></td></tr>`;

                document.getElementById("nonComplianceDetailsData").innerHTML = loading;
                
                jqXHR.url = settings.url;
                jqXHR.type = settings.type;
            },
            success: function (response) {
                $('#loading_ncd').remove();

                $('#nonComplianceDetailsTable tbody').html(response);
                let table_rows = parseInt(document.getElementById("nonComplianceDetailsData").childNodes.length);
				$('#count_view_ncd').html("Total: " + table_rows);
            }
        }).fail((jqXHR, textStatus, errorThrown) => {
            console.log(jqXHR);
            console.log(`System Error : Call IT Personnel Immediately!!! They will fix it right away. Error: url: ${jqXHR.url}, method: ${jqXHR.type} ( HTTP ${jqXHR.status} - ${jqXHR.statusText} ) Press F12 to see Console Log for more info.`);
            $('#loading_ncd').remove();
        });
    }

    const past_no_time_out_record_list = () => {
        let month = sessionStorage.getItem('nc_month_search');
        let emp_no = document.getElementById('emp_no_ncd').innerHTML;

        $.ajax({
            url: '../process/hr/attendances/nc_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'past_no_time_out_record_list',
                month: month,
                emp_no: emp_no
            },
            beforeSend: (jqXHR, settings) => {
                var loading = `<tr id="loading_pntr"><td colspan="7" style="text-align:center;"><div class="spinner-border text-dark" role="status"><span class="sr-only">Loading...</span></div></td></tr>`;

                document.getElementById("pastNoTimeOutRecordData").innerHTML = loading;
                
                jqXHR.url = settings.url;
                jqXHR.type = settings.type;
            },
            success: function (response) {
                $('#loading_pntr').remove();

                $('#pastNoTimeOutRecordTable tbody').html(response);
                let table_rows = parseInt(document.getElementById("pastNoTimeOutRecordData").childNodes.length);
				$('#count_view_pntr').html("Total: " + table_rows);
            }
        }).fail((jqXHR, textStatus, errorThrown) => {
            console.log(jqXHR);
            console.log(`System Error : Call IT Personnel Immediately!!! They will fix it right away. Error: url: ${jqXHR.url}, method: ${jqXHR.type} ( HTTP ${jqXHR.status} - ${jqXHR.statusText} ) Press F12 to see Console Log for more info.`);
            $('#loading_pntr').remove();
        });
    }

    const get_non_compliance_details = param => {
        var string = param.split('~!~');
        var emp_no = string[0];
        var full_name = string[1];
        var dept = string[2];
        var section = string[3];
        var line_no = string[4];
        var process = string[5];
        var null_time_out_count = string[6];

        document.getElementById('emp_no_ncd').innerHTML = emp_no;
        document.getElementById('full_name_ncd').innerHTML = full_name;
        document.getElementById('dept_ncd').innerHTML = dept;
        document.getElementById('section_ncd').innerHTML = section;
        document.getElementById('line_no_ncd').innerHTML = line_no;
        document.getElementById('process_ncd').innerHTML = process;
        document.getElementById('null_time_out_count_ncd').innerHTML = null_time_out_count;

        setTimeout(() => {
            get_emp_monthly_no_time_out_chart();
        }, 250);
        non_compliance_details_list();
        past_no_time_out_record_list();
    }
</script>