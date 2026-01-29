<?php 
function get_day($server_time, $server_date_only, $server_date_only_yesterday) {
  if ($server_time >= '06:00:00' && $server_time <= '23:59:59') {
    return $server_date_only;
  } else if ($server_time >= '00:00:00' && $server_time < '06:00:00') {
    return $server_date_only_yesterday;
  }
}
?>
<script type="text/javascript">
// AJAX IN PROGRESS GLOBAL VARS
var biometric_data_list_ajax_in_process = false;

// DOMContentLoaded function
document.addEventListener("DOMContentLoaded", () => {
    document.getElementById('attendance_date_search').value = '<?= get_day($server_time, $server_date_only, $server_date_only_yesterday) ?>';
    biometric_data_list();
});

const biometric_data_list = () => {
    // If an AJAX call is already in progress, return immediately
    if (biometric_data_list_ajax_in_process) {
        return;
    }

    let day = document.getElementById('attendance_date_search').value;

    // Set the flag to true as we're starting an AJAX call
    biometric_data_list_ajax_in_process = true;

    $.ajax({
        url: '../process/hr/biometric/bio_p.php',
        type: 'POST',
        cache: false,
        data: {
            method: 'biometric_data_list',
            day: day
        },
        beforeSend: (jqXHR, settings) => {
            var loading = `<tr id="loading"><td colspan="11" style="text-align:center;"><div class="spinner-border text-dark" role="status"><span class="sr-only">Loading...</span></div></td></tr>`;

            document.getElementById("biometric_data").innerHTML = loading;
            
            jqXHR.url = settings.url;
            jqXHR.type = settings.type;
        },
        success: function (response) {
            $('#loading').remove();

            $('#biometric_table tbody').html(response);
            let table_rows = parseInt(document.getElementById("biometric_data").childNodes.length);
            $('#count_view').html("Total: " + table_rows);

            // Set the flag back to false as the AJAX call has completed
            biometric_data_list_ajax_in_process = false;
        }
    }).fail((jqXHR, textStatus, errorThrown) => {
        console.log(jqXHR);
        console.log(`System Error : Call IT Personnel Immediately!!! They will fix it right away. Error: url: ${jqXHR.url}, method: ${jqXHR.type} ( HTTP ${jqXHR.status} - ${jqXHR.statusText} ) Press F12 to see Console Log for more info.`);
        $('#loading').remove();

        // Set the flag back to false as the AJAX call has completed
        biometric_data_list_ajax_in_process = false;
    });
}

const upload_csv = () => {
    var file_form = document.getElementById('file_form');
    var form_data = new FormData(file_form);
    $.ajax({
        url: '../process/import/imp_biometric.php',
        type: 'POST',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        beforeSend: (jqXHR, settings) => {
            Swal.fire({
                icon: 'info',
                title: 'Uploading Please Wait...',
                text: 'Info',
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false
            });
            jqXHR.url = settings.url;
            jqXHR.type = settings.type;
        }, 
        success: response => {
            setTimeout(() => {
                swal.close();
                if (response != '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Upload CSV Error',
                        text: `Error: ${response}`,
                        showConfirmButton: false,
                        timer : 2000
                    });
                } else {
                    Swal.fire({
                        icon: 'info',
                        title: 'Upload CSV',
                        text: 'Uploaded and updated successfully',
                        showConfirmButton: false,
                        timer : 1000
                    });
                }
                document.getElementById("file").value = '';
            }, 500);
        }
    })
    .fail((jqXHR, textStatus, errorThrown) => {
        console.log(jqXHR);
        swal('System Error', `Call IT Personnel Immediately!!! They will fix it right away. Error: url: ${jqXHR.url}, method: ${jqXHR.type} ( HTTP ${jqXHR.status} - ${jqXHR.statusText} ) Press F12 to see Console Log for more info.`, 'error');
    });
}
</script>