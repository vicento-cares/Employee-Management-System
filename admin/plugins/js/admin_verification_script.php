<script type="text/javascript">

var delay = (function(){
    var timer = 0;
    return function(callback, ms){
        clearTimeout (timer);
        timer = setTimeout(callback, ms);
    };
})();

$("#emp_no_verify").on("input", function() {
    delay(function(){
        if ($("#emp_no_verify").val().length < 7) {
            $("#emp_no_verify").val("");
        }
    }, 100);
});

const admin_verification = (callback) => {
    let emp_no = document.getElementById('emp_no_verify').value;

    if (emp_no != "") {
        $.ajax({
            type: "POST",
            url: "../process/admin/accounts/acct-management_p.php",
            cache:false,
            data: {
                method:"admin_verification",
                emp_no:emp_no
            },
            success: (response)=> {
                if (response == "success") {
                    callback("success");
                } else if (response == "failed") {
                    callback("failed");
                } else if (response == "unmatched") {
                    callback("unmatched");
                } else {
                    callback(response);
                }
                document.getElementById('emp_no_verify').value = '';
            }
        });
    } else {
        callback("Please scan QR Code");
    }
}

</script>