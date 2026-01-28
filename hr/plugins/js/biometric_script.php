<script type="text/javascript">
// DOMContentLoaded function
document.addEventListener("DOMContentLoaded", () => {
    
});

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
                    load_employees(1);
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