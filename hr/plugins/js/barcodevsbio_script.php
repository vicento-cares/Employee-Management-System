<script type="text/javascript">
    // AJAX IN PROGRESS GLOBAL VARS
    var bio_vs_barcode_data_ajax_in_process = false;

    // Charts
    let month_bio_vs_barcode_time_in_chart;

	let month_section_remarks_time_in_chart;

	let month_section_late_time_in_chart;
	let month_section_no_bio_time_in_chart;
	let month_section_no_barcode_time_in_chart;
	let month_section_no_entries_time_in_chart;
	let month_section_early_barcode_time_in_chart;
	let month_section_late_barcode_time_in_chart;

	let month_section_top_remarks_time_in_chart;

	let month_section_top_late_time_in_chart;
	let month_section_top_no_bio_time_in_chart;
	let month_section_top_no_barcode_time_in_chart;
	let month_section_top_no_entries_time_in_chart;
	let month_section_top_early_barcode_time_in_chart;
	let month_section_top_late_barcode_time_in_chart;

	let month_bio_vs_barcode_time_out_chart;

	let month_section_remarks_time_out_chart;

	let month_section_no_bio_time_out_chart;
	let month_section_no_barcode_time_out_chart;
	let month_section_no_entries_time_out_chart;
	let month_section_early_bio_time_out_chart;
	let month_section_late_bio_time_out_chart;

	let month_section_top_remarks_time_out_chart;

	let month_section_top_no_bio_time_out_chart;
	let month_section_top_no_barcode_time_out_chart;
	let month_section_top_no_entries_time_out_chart;
	let month_section_top_early_bio_time_out_chart;
	let month_section_top_late_bio_time_out_chart;

	let month_compliance_time_in_chart;

	let month_section_compliance_percent_time_in_chart;

	let month_section_compliance_time_in_chart;
	let month_section_non_compliance_time_in_chart;

	let month_section_top_compliance_percent_time_in_chart;

	let month_section_top_compliance_time_in_chart;
	let month_section_top_non_compliance_time_in_chart;

	let month_compliance_time_out_chart;

	let month_section_compliance_percent_time_out_chart;
	
	let month_section_compliance_time_out_chart;
	let month_section_non_compliance_time_out_chart;

	let month_section_top_compliance_percent_time_out_chart;

	let month_section_top_compliance_time_out_chart;
	let month_section_top_non_compliance_time_out_chart;

    // DOMContentLoaded function
    document.addEventListener("DOMContentLoaded", () => {
        sessionStorage.setItem('bvb_year_recent', '<?=date('Y')?>');
        sessionStorage.setItem('bvb_month_recent', '<?=date('n')?>');

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
                document.getElementById("bvb_year_search").innerHTML = response;
            }
        });
    }

	// Time In Analysis

	const get_month_bio_vs_barcode_time_in_chart = () => {
		return new Promise((resolve, reject) => {
			let year = document.getElementById('bvb_year_search').value;
			let month = document.getElementById('bvb_month_search').value;

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

					// Update previous chart before rendering new one
					if (month_bio_vs_barcode_time_in_chart) {
						month_bio_vs_barcode_time_in_chart.updateOptions({
                            series: options.series,
                            xaxis: options.xaxis
                        });
					} else {
                        month_bio_vs_barcode_time_in_chart = new ApexCharts(ctx, options);
                        month_bio_vs_barcode_time_in_chart.render();
                    }

					resolve({ status: 'success' });
				}
			});
		});
	};

	const get_month_section_remarks_time_in_chart = () => {
		return new Promise((resolve, reject) => {
			let year = document.getElementById('bvb_year_search').value;
			let month = document.getElementById('bvb_month_search').value;
			let time_in_remarks = document.getElementById('bvb_time_in_remarks_ctx_search').value;

			$.ajax({
				url: '../process/hr/biometric/biod_p.php',
				type: 'GET',
				cache: false,
				dataType: 'json',
				data: {
					method: 'get_month_section_remarks_time_in_chart',
					year: year,
					month: month,
					time_in_remarks: time_in_remarks
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

					let ctx = document.querySelector("#month_section_remarks_time_in_chart");

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
							text: `${time_in_remarks} Time In Trend`,
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

					// Update previous chart before rendering new one
					if (month_section_remarks_time_in_chart) {
						month_section_remarks_time_in_chart.updateOptions({
                            series: options.series,
                            xaxis: options.xaxis,
							title: options.title
                        });
					} else {
                        month_section_remarks_time_in_chart = new ApexCharts(ctx, options);
                        month_section_remarks_time_in_chart.render();
                    }

					resolve({ status: 'success' });
				}
			});
		});
	};

	const get_month_section_top_remarks_time_in_chart = () => {
		return new Promise((resolve, reject) => {
			let year = document.getElementById('bvb_year_search').value;
			let month = document.getElementById('bvb_month_search').value;
			let time_in_remarks = document.getElementById('bvb_time_in_remarks_ctx_search').value;

			$.ajax({
				url: '../process/hr/biometric/biod_p.php',
				type: 'GET',
				cache: false,
				dataType: 'json',
				data: {
					method: 'get_month_section_top_remarks_time_in_chart',
					year: year,
					month: month,
					time_in_remarks: time_in_remarks
				},
				success: response => {

					// Define Bootstrap 4 colors
					const bootstrapColors = ['#dc3545']; // Added a color for the line chart

					// Convert the data object to an array
					const seriesData = response.data.map(item => {
						return {
							name: item.name,
							data: Object.values(item.data)
						};
					});

					let ctx = document.querySelector("#month_section_top_remarks_time_in_chart");

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
							categories: response.categories,
							labels: {
								rotate: -60 // Adjust the rotation angle as needed
							}
						},
						title: {
							text: `Top 10 Section with ${time_in_remarks} Time In Employee Count`,
							align: 'left'
						}
					};

					// Update previous chart before rendering new one
					if (month_section_top_remarks_time_in_chart) {
						month_section_top_remarks_time_in_chart.updateOptions({
                            series: options.series,
                            xaxis: options.xaxis,
							title: options.title
                        });
					} else {
                        month_section_top_remarks_time_in_chart = new ApexCharts(ctx, options);
                        month_section_top_remarks_time_in_chart.render();
                    }

					resolve({ status: 'success' });
				}
			});
		});
	};

	// Time Out Analysis

    const get_month_bio_vs_barcode_time_out_chart = () => {
		return new Promise((resolve, reject) => {
			let year = document.getElementById('bvb_year_search').value;
			let month = document.getElementById('bvb_month_search').value;

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

					// Update previous chart before rendering new one
					if (month_bio_vs_barcode_time_out_chart) {
						month_bio_vs_barcode_time_out_chart.updateOptions({
                            series: options.series,
                            xaxis: options.xaxis
                        });
					} else {
                        month_bio_vs_barcode_time_out_chart = new ApexCharts(ctx, options);
                        month_bio_vs_barcode_time_out_chart.render();
                    }

					resolve({ status: 'success' });
				}
			});
		});
	};

	const get_month_section_remarks_time_out_chart = () => {
		return new Promise((resolve, reject) => {
			let year = document.getElementById('bvb_year_search').value;
			let month = document.getElementById('bvb_month_search').value;
			let time_out_remarks = document.getElementById('bvb_time_out_remarks_ctx_search').value;

			$.ajax({
				url: '../process/hr/biometric/biod_p.php',
				type: 'GET',
				cache: false,
				dataType: 'json',
				data: {
					method: 'get_month_section_remarks_time_out_chart',
					year: year,
					month: month,
					time_out_remarks: time_out_remarks
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

					let ctx = document.querySelector("#month_section_remarks_time_out_chart");

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
							text: `${time_out_remarks} Time Out Trend`,
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

					// Update previous chart before rendering new one
					if (month_section_remarks_time_out_chart) {
						month_section_remarks_time_out_chart.updateOptions({
                            series: options.series,
                            xaxis: options.xaxis,
							title: options.title
                        });
					} else {
                        month_section_remarks_time_out_chart = new ApexCharts(ctx, options);
                        month_section_remarks_time_out_chart.render();
                    }

					resolve({ status: 'success' });
				}
			});
		});
	};

	const get_month_section_top_remarks_time_out_chart = () => {
		return new Promise((resolve, reject) => {
			let year = document.getElementById('bvb_year_search').value;
			let month = document.getElementById('bvb_month_search').value;
			let time_out_remarks = document.getElementById('bvb_time_out_remarks_ctx_search').value;

			$.ajax({
				url: '../process/hr/biometric/biod_p.php',
				type: 'GET',
				cache: false,
				dataType: 'json',
				data: {
					method: 'get_month_section_top_remarks_time_out_chart',
					year: year,
					month: month,
					time_out_remarks: time_out_remarks
				},
				success: response => {

					// Define Bootstrap 4 colors
					const bootstrapColors = ['#dc3545']; // Added a color for the line chart

					// Convert the data object to an array
					const seriesData = response.data.map(item => {
						return {
							name: item.name,
							data: Object.values(item.data)
						};
					});

					let ctx = document.querySelector("#month_section_top_remarks_time_out_chart");

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
							categories: response.categories,
							labels: {
								rotate: -60 // Adjust the rotation angle as needed
							}
						},
						title: {
							text: `Top 10 Section with ${time_out_remarks} Time Out Employee Count`,
							align: 'left'
						}
					};

					// Update previous chart before rendering new one
					if (month_section_top_remarks_time_out_chart) {
						month_section_top_remarks_time_out_chart.updateOptions({
                            series: options.series,
                            xaxis: options.xaxis,
							title: options.title
                        });
					} else {
                        month_section_top_remarks_time_out_chart = new ApexCharts(ctx, options);
                        month_section_top_remarks_time_out_chart.render();
                    }

					resolve({ status: 'success' });
				}
			});
		});
	};

	// Compliance Analysis

	const get_month_compliance_time_in_chart = () => {
		return new Promise((resolve, reject) => {
			let year = document.getElementById('bvb_year_search').value;
			let month = document.getElementById('bvb_month_search').value;

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

					// Update previous chart before rendering new one
					if (month_compliance_time_in_chart) {
						month_compliance_time_in_chart.updateOptions({
                            series: options.series,
                            xaxis: options.xaxis
                        });
					} else {
                        month_compliance_time_in_chart = new ApexCharts(ctx, options);
                        month_compliance_time_in_chart.render();
                    }

					resolve({ status: 'success' });
				}
			});
		});
	};

	const get_month_section_compliance_percent_time_in_chart = () => {
		return new Promise((resolve, reject) => {
			let year = document.getElementById('bvb_year_search').value;
			let month = document.getElementById('bvb_month_search').value;
			let percent_type = document.getElementById('bvb_percent_type_ctx_search').value;

			$.ajax({
				url: '../process/hr/biometric/biod_p.php',
				type: 'GET',
				cache: false,
				dataType: 'json',
				data: {
					method: 'get_month_section_compliance_percent_time_in_chart',
					year: year,
					month: month,
					percent_type: percent_type
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

					let ctx = document.querySelector("#month_section_compliance_percent_time_in_chart");

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
							text: `${percent_type} Percentage Time In Per Section Trend`,
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

					// Update previous chart before rendering new one
					if (month_section_compliance_percent_time_in_chart) {
						month_section_compliance_percent_time_in_chart.updateOptions({
                            series: options.series,
                            xaxis: options.xaxis,
							title: options.title
                        });
					} else {
                        month_section_compliance_percent_time_in_chart = new ApexCharts(ctx, options);
                        month_section_compliance_percent_time_in_chart.render();
                    }

					resolve({ status: 'success' });
				}
			});
		});
	};

	const get_month_section_top_compliance_percent_time_in_chart = () => {
		return new Promise((resolve, reject) => {
			let year = document.getElementById('bvb_year_search').value;
			let month = document.getElementById('bvb_month_search').value;
			let percent_type = document.getElementById('bvb_percent_type_ctx_search').value;

			$.ajax({
				url: '../process/hr/biometric/biod_p.php',
				type: 'GET',
				cache: false,
				dataType: 'json',
				data: {
					method: 'get_month_section_top_compliance_percent_time_in_chart',
					year: year,
					month: month,
					percent_type: percent_type
				},
				success: response => {

					// Define Bootstrap 4 colors
					const bootstrapColors = ['#dc3545']; // Added a color for the line chart

					// Convert the data object to an array
					const seriesData = response.data.map(item => {
						return {
							name: item.name,
							data: Object.values(item.data)
						};
					});

					let ctx = document.querySelector("#month_section_top_compliance_percent_time_in_chart");

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
							categories: response.categories,
							labels: {
								rotate: -60 // Adjust the rotation angle as needed
							}
						},
						title: {
							text: `Top 10 Section for ${percent_type} Time In Percentage`,
							align: 'left'
						}
					};

					// Update previous chart before rendering new one
					if (month_section_top_compliance_percent_time_in_chart) {
						month_section_top_compliance_percent_time_in_chart.updateOptions({
                            series: options.series,
                            xaxis: options.xaxis,
							title: options.title
                        });
					} else {
                        month_section_top_compliance_percent_time_in_chart = new ApexCharts(ctx, options);
                        month_section_top_compliance_percent_time_in_chart.render();
                    }

					resolve({ status: 'success' });
				}
			});
		});
	};

	const get_month_compliance_time_out_chart = () => {
		return new Promise((resolve, reject) => {
			let year = document.getElementById('bvb_year_search').value;
			let month = document.getElementById('bvb_month_search').value;

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

					// Update previous chart before rendering new one
					if (month_compliance_time_out_chart) {
						month_compliance_time_out_chart.updateOptions({
                            series: options.series,
                            xaxis: options.xaxis
                        });
					} else {
                        month_compliance_time_out_chart = new ApexCharts(ctx, options);
                        month_compliance_time_out_chart.render();
                    }

					resolve({ status: 'success' });
				}
			});
		});
	};

	const get_month_section_compliance_percent_time_out_chart = () => {
		return new Promise((resolve, reject) => {
			let year = document.getElementById('bvb_year_search').value;
			let month = document.getElementById('bvb_month_search').value;
			let percent_type = document.getElementById('bvb_percent_type_ctx_search').value;

			$.ajax({
				url: '../process/hr/biometric/biod_p.php',
				type: 'GET',
				cache: false,
				dataType: 'json',
				data: {
					method: 'get_month_section_compliance_percent_time_out_chart',
					year: year,
					month: month,
					percent_type: percent_type
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

					let ctx = document.querySelector("#month_section_compliance_percent_time_out_chart");

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
							text: `${percent_type} Percentage Time Out Per Section Trend`,
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

					// Update previous chart before rendering new one
					if (month_section_compliance_percent_time_out_chart) {
						month_section_compliance_percent_time_out_chart.updateOptions({
                            series: options.series,
                            xaxis: options.xaxis,
							title: options.title
                        });
					} else {
                        month_section_compliance_percent_time_out_chart = new ApexCharts(ctx, options);
                        month_section_compliance_percent_time_out_chart.render();
                    }

					resolve({ status: 'success' });
				}
			});
		});
	};

	const get_month_section_top_compliance_percent_time_out_chart = () => {
		return new Promise((resolve, reject) => {
			let year = document.getElementById('bvb_year_search').value;
			let month = document.getElementById('bvb_month_search').value;
			let percent_type = document.getElementById('bvb_percent_type_ctx_search').value;

			$.ajax({
				url: '../process/hr/biometric/biod_p.php',
				type: 'GET',
				cache: false,
				dataType: 'json',
				data: {
					method: 'get_month_section_top_compliance_percent_time_out_chart',
					year: year,
					month: month,
					percent_type: percent_type
				},
				success: response => {

					// Define Bootstrap 4 colors
					const bootstrapColors = ['#dc3545']; // Added a color for the line chart

					// Convert the data object to an array
					const seriesData = response.data.map(item => {
						return {
							name: item.name,
							data: Object.values(item.data)
						};
					});

					let ctx = document.querySelector("#month_section_top_compliance_percent_time_out_chart");

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
							categories: response.categories,
							labels: {
								rotate: -60 // Adjust the rotation angle as needed
							}
						},
						title: {
							text: `Top 10 Section for ${percent_type} Time Out Percentage`,
							align: 'left'
						}
					};

					// Update previous chart before rendering new one
					if (month_section_top_compliance_percent_time_out_chart) {
						month_section_top_compliance_percent_time_out_chart.updateOptions({
                            series: options.series,
                            xaxis: options.xaxis,
							title: options.title
                        });
					} else {
                        month_section_top_compliance_percent_time_out_chart = new ApexCharts(ctx, options);
                        month_section_top_compliance_percent_time_out_chart.render();
                    }

					resolve({ status: 'success' });
				}
			});
		});
	};

	// All Function Names List
	const get_requests = [
		get_month_bio_vs_barcode_time_in_chart,
		get_month_section_remarks_time_in_chart,
		get_month_section_top_remarks_time_in_chart,
		get_month_bio_vs_barcode_time_out_chart,
		get_month_section_remarks_time_out_chart,
		get_month_section_top_remarks_time_out_chart,
		get_month_compliance_time_in_chart,
		get_month_section_compliance_percent_time_in_chart,
		get_month_section_top_compliance_percent_time_in_chart,
		get_month_compliance_time_out_chart,
		get_month_section_compliance_percent_time_out_chart,
		get_month_section_top_compliance_percent_time_out_chart
	];

	const load_bvb_dashboard = async () => {
		Swal.fire({
			icon: 'info',
			title: 'Fetching Chart Data...',
			html: `0 Chart/s / ${get_requests.length} Chart/s`,
			showConfirmButton: false,
			allowOutsideClick: false
		});

		let completed = 0;

		const wrappedRequests = get_requests.map(fn =>
			Promise.resolve(fn())
				.then(result => {
					completed++;
					Swal.update({
						html: `${completed} Chart/s / ${get_requests.length} Chart/s`
					});
					return result;
				})
				.catch(error => {
					completed++;
					Swal.update({
						html: `${completed} Chart/s / ${get_requests.length} Chart/s`
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

	// Biometric Vs Barcode Table Data

	const get_bio_vs_barcode_data = () => {
		// If an AJAX call is already in progress, return immediately
		if (bio_vs_barcode_data_ajax_in_process) {
			return;
		}

		let day = document.getElementById('bvb_day_search').value;
		let time_in_remarks = document.getElementById('bvb_time_in_remarks_search').value;
		let time_out_remarks = document.getElementById('bvb_time_out_remarks_search').value;

		// Set the flag to true as we're starting an AJAX call
		bio_vs_barcode_data_ajax_in_process = true;

		$.ajax({
			url: '../process/hr/biometric/biod_p.php',
			type: 'GET',
			cache: false,
			dataType: "json",
			data: {
				method: 'get_bio_vs_barcode_data',
				day: day,
				time_in_remarks: time_in_remarks,
				time_out_remarks: time_out_remarks
			},
			beforeSend: (jqXHR, settings) => {
				var loading = `<tr id="loading"><td colspan="10" style="text-align:center;"><div class="spinner-border text-dark" role="status"><span class="sr-only">Loading...</span></div></td></tr>`;

				document.getElementById("bioVsBarcodeData").innerHTML = loading;
				
				jqXHR.url = settings.url;
				jqXHR.type = settings.type;
			},
			success: function (response) {
				$('#loading').remove();

				if (response.status === 'success') {
					const rows = response.message || [];

					rows.forEach((row, index) => {
						const tr = `
								<tr>
									<td>${index + 1}</td>
									<td>${row.day}</td>
									<td>${row.emp_no}</td>
									<td>${row.full_name}</td>
									<td>${row.dept}</td>
									<td>${row.section}</td>
									<td>${row.line_no}</td>
									<td>${row.process}</td>
									<td>${row.time_in_remarks}</td>
									<td>${row.time_out_remarks}</td>
								</tr>
							`;
						document.getElementById('bioVsBarcodeData').insertAdjacentHTML('beforeend', tr);
					});

					$('#count_view').html("Total: " + rows.length);
				}

				// sessionStorage.setItem('bvb_year_search', year);
				// sessionStorage.setItem('bvb_month_search', month);

				// setTimeout(() => {
				//     get_month_bio_vs_barcode_time_in_chart();
				// }, 250);

				// Set the flag back to false as the AJAX call has completed
				bio_vs_barcode_data_ajax_in_process = false;
			}
		}).fail((jqXHR, textStatus, errorThrown) => {
			console.log(jqXHR);
			console.log(`System Error : Call IT Personnel Immediately!!! They will fix it right away. Error: url: ${jqXHR.url}, method: ${jqXHR.type} ( HTTP ${jqXHR.status} - ${jqXHR.statusText} ) Press F12 to see Console Log for more info.`);
			$('#loading').remove();

			// Set the flag back to false as the AJAX call has completed
			bio_vs_barcode_data_ajax_in_process = false;
		});
    }

	document.getElementById('bvb_table_form').addEventListener('submit', e => {
        e.preventDefault();
        get_bio_vs_barcode_data();
    });

    const export_bio_vs_barcode_data = (table_id, separator = ',') => {
        // let year = sessionStorage.getItem('bvb_year_search');
        // let month = sessionStorage.getItem('bvb_month_search');

		let year = document.getElementById('bvb_year_search').value;
		let month = document.getElementById('bvb_month_search').value;

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