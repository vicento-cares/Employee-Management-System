<script type="text/javascript">
    // AJAX IN PROGRESS GLOBAL VARS
    var non_compliance_list_ajax_in_process = false;

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

		if (year && month) {
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