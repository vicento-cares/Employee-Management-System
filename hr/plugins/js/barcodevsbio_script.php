<script type="text/javascript">
    // AJAX IN PROGRESS GLOBAL VARS
    var bio_vs_barcode_data_ajax_in_process = false;

    // Charts
    let month_bio_vs_barcode_time_in_chart;
	let month_bio_vs_barcode_time_out_chart;
	let month_compliance_time_in_chart;
	let month_compliance_time_out_chart;

    // DOMContentLoaded function
    document.addEventListener("DOMContentLoaded", () => {
        sessionStorage.setItem('nc_year_recent', '<?=date('Y')?>');
        sessionStorage.setItem('nc_month_recent', '<?=date('n')?>');

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

	const get_month_bio_vs_barcode_time_in_chart = () => {
		return new Promise((resolve, reject) => {
			let year = document.getElementById('nc_year_search').value;
			let month = document.getElementById('nc_month_search').value;

			$.ajax({
				url: '../process/hr/biometric/biod_p.php',
				type: 'GET',
				cache: false,
				dataType: 'json',
				data: {
					method: 'get_month_bio_vs_barcode_time_in_chart',
					year: year,
					month: month
				},
				success: response => {

					const seriesColorMap = response.colorMap;

					const seriesData = response.data.map(item => ({
						name: item.name,
						data: Object.values(item.data)
					}));

					const colors = seriesData.map(
						item => seriesColorMap[item.name] || '#343a40'
					);

					let ctx = document.querySelector("#month_bio_vs_barcode_time_in_chart");

					let activeSeriesIndex = null;
					let activeSeriesName = null;
					let originalSeries = [];

					var options = {
						chart: {
							type: 'line',
							height: 300,
							events: {
								mounted(chartContext) {
									// Save immutable copy of the original series
									originalSeries = JSON.parse(
										JSON.stringify(chartContext.w.config.series)
									);
								},
								legendClick(chartContext, seriesIndex) {
									const seriesName = chartContext.w.globals.seriesNames[seriesIndex];

									if (activeSeriesName !== seriesName) {
										// Hide all except clicked
										chartContext.w.globals.seriesNames.forEach(name => {
											if (name !== seriesName) {
												chartContext.hideSeries(name);
											}
										});
										chartContext.showSeries(seriesName);
										activeSeriesName = seriesName;
									} else {
										// Show all
										chartContext.w.globals.seriesNames.forEach(name => {
											chartContext.showSeries(name);
										});
										activeSeriesName = null;
									}

									return false; // prevent default Apex behavior
								}
							}
						},
						series: seriesData,
						colors: colors,
						xaxis: {
							categories: response.categories
						},
						title: {
							text: 'Barcode vs Biometric Time In Trend',
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
						},
						legend: {
							onItemClick: {
								toggleDataSeries: false // disable default toggle
							}
						}
					};

					// Destroy previous chart before rendering new one
					if (month_bio_vs_barcode_time_in_chart) {
						month_bio_vs_barcode_time_in_chart.destroy();
					}

					month_bio_vs_barcode_time_in_chart =
						new ApexCharts(ctx, options);

					month_bio_vs_barcode_time_in_chart.render();

					resolve({ status: 'success' });
				}
			});
		});
	};

    const get_month_bio_vs_barcode_time_out_chart = () => {
		return new Promise((resolve, reject) => {
			let year = document.getElementById('nc_year_search').value;
			let month = document.getElementById('nc_month_search').value;

			$.ajax({
				url: '../process/hr/biometric/biod_p.php',
				type: 'GET',
				cache: false,
				dataType: 'json',
				data: {
					method: 'get_month_bio_vs_barcode_time_out_chart',
					year: year,
					month: month
				},
				success: response => {

					const seriesColorMap = response.colorMap;

					const seriesData = response.data.map(item => ({
						name: item.name,
						data: Object.values(item.data)
					}));

					const colors = seriesData.map(
						item => seriesColorMap[item.name] || '#343a40'
					);

					let ctx = document.querySelector("#month_bio_vs_barcode_time_out_chart");

					let activeSeriesIndex = null;
					let activeSeriesName = null;
					let originalSeries = [];

					var options = {
						chart: {
							type: 'line',
							height: 300,
							events: {
								mounted(chartContext) {
									// Save immutable copy of the original series
									originalSeries = JSON.parse(
										JSON.stringify(chartContext.w.config.series)
									);
								},
								legendClick(chartContext, seriesIndex) {
									const seriesName = chartContext.w.globals.seriesNames[seriesIndex];

									if (activeSeriesName !== seriesName) {
										// Hide all except clicked
										chartContext.w.globals.seriesNames.forEach(name => {
											if (name !== seriesName) {
												chartContext.hideSeries(name);
											}
										});
										chartContext.showSeries(seriesName);
										activeSeriesName = seriesName;
									} else {
										// Show all
										chartContext.w.globals.seriesNames.forEach(name => {
											chartContext.showSeries(name);
										});
										activeSeriesName = null;
									}

									return false; // prevent default Apex behavior
								}
							}
						},
						series: seriesData,
						colors: colors,
						xaxis: {
							categories: response.categories
						},
						title: {
							text: 'Barcode vs Biometric Time Out Trend',
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
						},
						legend: {
							onItemClick: {
								toggleDataSeries: false // disable default toggle
							}
						}
					};

					// Destroy previous chart before rendering new one
					if (month_bio_vs_barcode_time_out_chart) {
						month_bio_vs_barcode_time_out_chart.destroy();
					}

					month_bio_vs_barcode_time_out_chart =
						new ApexCharts(ctx, options);

					month_bio_vs_barcode_time_out_chart.render();

					resolve({ status: 'success' });
				}
			});
		});
	};

	const get_month_compliance_time_in_chart = () => {
		return new Promise((resolve, reject) => {
			let year = document.getElementById('nc_year_search').value;
			let month = document.getElementById('nc_month_search').value;

			$.ajax({
				url: '../process/hr/biometric/biod_p.php',
				type: 'GET',
				cache: false,
				dataType: 'json',
				data: {
					method: 'get_month_compliance_time_in_chart',
					year: year,
					month: month
				},
				success: response => {

					const seriesColorMap = response.colorMap;

					const seriesData = response.data.map(item => ({
						name: item.name,
						data: Object.values(item.data)
					}));

					const colors = seriesData.map(
						item => seriesColorMap[item.name] || '#343a40'
					);

					let ctx = document.querySelector("#month_compliance_time_in_chart");

					let activeSeriesIndex = null;
					let activeSeriesName = null;
					let originalSeries = [];

					var options = {
						chart: {
							type: 'line',
							height: 300,
							events: {
								mounted(chartContext) {
									// Save immutable copy of the original series
									originalSeries = JSON.parse(
										JSON.stringify(chartContext.w.config.series)
									);
								},
								legendClick(chartContext, seriesIndex) {
									const seriesName = chartContext.w.globals.seriesNames[seriesIndex];

									if (activeSeriesName !== seriesName) {
										// Hide all except clicked
										chartContext.w.globals.seriesNames.forEach(name => {
											if (name !== seriesName) {
												chartContext.hideSeries(name);
											}
										});
										chartContext.showSeries(seriesName);
										activeSeriesName = seriesName;
									} else {
										// Show all
										chartContext.w.globals.seriesNames.forEach(name => {
											chartContext.showSeries(name);
										});
										activeSeriesName = null;
									}

									return false; // prevent default Apex behavior
								}
							}
						},
						series: seriesData,
						colors: colors,
						xaxis: {
							categories: response.categories
						},
						title: {
							text: 'Compliance Percentage Time In Trend',
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
						},
						legend: {
							onItemClick: {
								toggleDataSeries: false // disable default toggle
							}
						}
					};

					// Destroy previous chart before rendering new one
					if (month_compliance_time_in_chart) {
						month_compliance_time_in_chart.destroy();
					}

					month_compliance_time_in_chart =
						new ApexCharts(ctx, options);

					month_compliance_time_in_chart.render();

					resolve({ status: 'success' });
				}
			});
		});
	};

	const get_month_compliance_time_out_chart = () => {
		return new Promise((resolve, reject) => {
			let year = document.getElementById('nc_year_search').value;
			let month = document.getElementById('nc_month_search').value;

			$.ajax({
				url: '../process/hr/biometric/biod_p.php',
				type: 'GET',
				cache: false,
				dataType: 'json',
				data: {
					method: 'get_month_compliance_time_out_chart',
					year: year,
					month: month
				},
				success: response => {

					const seriesColorMap = response.colorMap;

					const seriesData = response.data.map(item => ({
						name: item.name,
						data: Object.values(item.data)
					}));

					const colors = seriesData.map(
						item => seriesColorMap[item.name] || '#343a40'
					);

					let ctx = document.querySelector("#month_compliance_time_out_chart");

					let activeSeriesIndex = null;
					let activeSeriesName = null;
					let originalSeries = [];

					var options = {
						chart: {
							type: 'line',
							height: 300,
							events: {
								mounted(chartContext) {
									// Save immutable copy of the original series
									originalSeries = JSON.parse(
										JSON.stringify(chartContext.w.config.series)
									);
								},
								legendClick(chartContext, seriesIndex) {
									const seriesName = chartContext.w.globals.seriesNames[seriesIndex];

									if (activeSeriesName !== seriesName) {
										// Hide all except clicked
										chartContext.w.globals.seriesNames.forEach(name => {
											if (name !== seriesName) {
												chartContext.hideSeries(name);
											}
										});
										chartContext.showSeries(seriesName);
										activeSeriesName = seriesName;
									} else {
										// Show all
										chartContext.w.globals.seriesNames.forEach(name => {
											chartContext.showSeries(name);
										});
										activeSeriesName = null;
									}

									return false; // prevent default Apex behavior
								}
							}
						},
						series: seriesData,
						colors: colors,
						xaxis: {
							categories: response.categories
						},
						title: {
							text: 'Compliance Percentage Time Out Trend',
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
						},
						legend: {
							onItemClick: {
								toggleDataSeries: false // disable default toggle
							}
						}
					};

					// Destroy previous chart before rendering new one
					if (month_compliance_time_out_chart) {
						month_compliance_time_out_chart.destroy();
					}

					month_compliance_time_out_chart =
						new ApexCharts(ctx, options);

					month_compliance_time_out_chart.render();

					resolve({ status: 'success' });
				}
			});
		});
	};

	const get_bio_vs_barcode_data = () => {
		return new Promise((resolve, reject) => {
			// If an AJAX call is already in progress, return immediately
			if (bio_vs_barcode_data_ajax_in_process) {
				return;
			}

			// let year = document.getElementById('nc_year_search').value;
			// let month = document.getElementById('nc_month_search').value;

			// Set the flag to true as we're starting an AJAX call
			bio_vs_barcode_data_ajax_in_process = true;

			$.ajax({
				url: '../process/hr/biometric/biod_p.php',
				type: 'GET',
				cache: false,
				data: {
					method: 'get_bio_vs_barcode_data'
				},
				beforeSend: (jqXHR, settings) => {
					var loading = `<tr id="loading"><td colspan="10" style="text-align:center;"><div class="spinner-border text-dark" role="status"><span class="sr-only">Loading...</span></div></td></tr>`;

					document.getElementById("bioVsBarcodeData").innerHTML = loading;
					
					jqXHR.url = settings.url;
					jqXHR.type = settings.type;
				},
				success: function (response) {
					$('#loading').remove();

					$('#bioVsBarcodeTable tbody').html(response);
					let table_rows = parseInt(document.getElementById("bioVsBarcodeData").childNodes.length);
					$('#count_view').html("Total: " + table_rows);

					// sessionStorage.setItem('nc_year_search', year);
					// sessionStorage.setItem('nc_month_search', month);

					// setTimeout(() => {
					//     get_month_bio_vs_barcode_time_in_chart();
					// }, 250);

					// Set the flag back to false as the AJAX call has completed
					bio_vs_barcode_data_ajax_in_process = false;

					resolve({ status: 'success' });
				}
			}).fail((jqXHR, textStatus, errorThrown) => {
				console.log(jqXHR);
				console.log(`System Error : Call IT Personnel Immediately!!! They will fix it right away. Error: url: ${jqXHR.url}, method: ${jqXHR.type} ( HTTP ${jqXHR.status} - ${jqXHR.statusText} ) Press F12 to see Console Log for more info.`);
				$('#loading').remove();

				reject({
					error: `HTTP ${jqXHR.status} - ${jqXHR.statusText}`
				});

				// Set the flag back to false as the AJAX call has completed
				bio_vs_barcode_data_ajax_in_process = false;
			});
		});
    }

	const get_requests = [
		get_month_bio_vs_barcode_time_in_chart,
		get_month_bio_vs_barcode_time_out_chart,
		get_month_compliance_time_in_chart,
		get_month_compliance_time_out_chart,
		get_bio_vs_barcode_data
	];

	const load_bvb_dashboard = async () => {
		Swal.fire({
			icon: 'info',
			title: 'Fetching Data...',
			html: `0 / ${get_requests.length}`,
			showConfirmButton: false,
			allowOutsideClick: false
		});

		let completed = 0;

		const wrappedRequests = get_requests.map(fn =>
			Promise.resolve(fn())
				.then(result => {
					completed++;
					Swal.update({
						html: `${completed} / ${get_requests.length}`
					});
					return result;
				})
				.catch(error => {
					completed++;
					Swal.update({
						html: `${completed} / ${get_requests.length}`
					});
					throw error;
				})
		);

		const results = await Promise.allSettled(wrappedRequests);

		const success = results.filter(r => r.status === 'fulfilled');
		const failed  = results.filter(r => r.status === 'rejected');

		Swal.close();

		if (failed.length > 0) {
			Swal.fire({
				icon: 'warning',
				title: 'Dashboard Loaded with Errors',
				html: `
					Success: ${success.length}<br>
					Failed: ${failed.length}
				`
			});

			console.table(failed.map(f => f.reason));
		} else {
			Swal.fire({
				icon: 'success',
				title: 'Dashboard Loaded Successfully',
				timer: 1500,
				showConfirmButton: false
			});
		}
	};

    document.getElementById('bvb_form').addEventListener('submit', e => {
        e.preventDefault();
        load_bvb_dashboard();
    });

    const export_bio_vs_barcode_data = (table_id, separator = ',') => {
        // let year = sessionStorage.getItem('nc_year_search');
        // let month = sessionStorage.getItem('nc_month_search');

		let year = document.getElementById('nc_year_search').value;
		let month = document.getElementById('nc_month_search').value;

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
        var filename = 'EmpMgtSys_BioVsBarcodeData_';

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
</script>