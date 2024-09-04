<?php
include '../process/server_date_time.php';

function get_shift($server_time) {
  if ($server_time >= '05:00:00' && $server_time < '17:00:00') {
    return 'DS';
  } else if ($server_time >= '17:00:00' && $server_time <= '23:59:59') {
    return 'NS';
  } else if ($server_time >= '00:00:00' && $server_time < '05:00:00') {
    return 'NS';
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Emp Mgt Sys Portal Cron</title>

  <link rel="icon" href="../dist/img/logo.ico" type="image/x-icon" />
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="../dist/css/font.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
  <input type="hidden" id="server_date_time" value="<?=$server_date_time?>">
  <input type="hidden" id="shift_label" value="<?=get_shift($server_time)?>">
  <div class="login-box">
    <div class="login-logo">
      <img src="../dist/img/logo.webp" style="height:100px;">
      <h3 class="m-0">Employee Management System</h3>
      <h1 class="m-0"><b>Portal Cron</b></h1>
      <h1><b id="realtime"><?=$server_time_a?></b></h1>
      <h4>FALP Server: <?=$_SERVER['SERVER_ADDR']?></h4>
    </div>
    <!-- /.login-logo -->
  </div>
</body>

<!-- jQuery -->
<script src="../plugins/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>

<script>
  // AJAX IN PROGRESS GLOBAL VARS
  var check_user_login_req_waiting_ajax_in_process = false;
  var check_leave_form_req_waiting_ajax_in_process = false;

  // Global Variables for Realtime Count
  var realtime_check_user_login_req_waiting;
  var realtime_check_leave_form_req_waiting;

  // var serverTime = document.getElementById("server_time").value;

  setInterval(() => {
		window.location.reload();
	}, 1000 * 60 * 60);

  const recursive_realtime_check_user_login_req_waiting = () => {
    check_user_login_req_waiting();
    realtime_check_user_login_req_waiting = setTimeout(recursive_realtime_check_user_login_req_waiting, 5000);
  }

  const recursive_realtime_check_leave_form_req_waiting = () => {
    check_leave_form_req_waiting();
    realtime_check_leave_form_req_waiting = setTimeout(recursive_realtime_check_leave_form_req_waiting, 10000);
  }

  // DOMContentLoaded function
  document.addEventListener("DOMContentLoaded", () => {
    // var serverDateTimeObject = new Date().toISOString().split('T')[0];
    var serverDateTimeObject = document.getElementById('server_date_time').value;
    sessionStorage.setItem("empMgtServerDateTimeObject", serverDateTimeObject);

    setInterval(realtime, 1000);
    recursive_realtime_check_user_login_req_waiting();
    recursive_realtime_check_leave_form_req_waiting();
  });

  document.addEventListener('visibilitychange', function() {
    if (!document.hidden) {
      // tab is active again
      // restart timers
      update_realtime();
    }
  });

  const realtime = () => {
    // Create a Date object from the server time
    // var serverDate = new Date("1970-01-01T" + serverTime + "Z");

    var serverDateTimeObject = sessionStorage.getItem("empMgtServerDateTimeObject");
    var serverDateTime = new Date(serverDateTimeObject);
    serverDateTime.setSeconds(serverDateTime.getSeconds() + 1);

    // Increment the server time by one second
    // serverDate.setSeconds(serverDate.getSeconds() + 1);

    // Update the serverTime variable
    // serverTime = serverDate.toISOString().substr(11, 8);

    // Create a new Date object for the display time
    // var displayDate = new Date(serverDateTime.getTime());

    // Adjust for the Philippine time zone (GMT+8)
    // -8 instead of +8
    // displayDate.setHours(displayDate.getHours() - 8);

    // Convert to 12-hour format
    var hours = serverDateTime.getHours();
    var minutes = serverDateTime.getMinutes();
    var seconds = serverDateTime.getSeconds();
    var ampm = hours >= 12 ? 'PM' : 'AM';

    var shift = document.getElementById('shift_label').innerHTML;

    if (hours >= 5 && hours < 17) {
      shift = 'DS';
    } else if (hours >= 17 && hours <= 23) {
      shift = 'NS';
    } else if (hours >= 0 && hours < 5) {
      shift = 'NS';
    }

    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    hours = hours < 10 ? '0'+hours : hours;
    minutes = minutes < 10 ? '0'+minutes : minutes;
    seconds = seconds < 10 ? '0'+seconds : seconds;
    var strTime = hours + ':' + minutes + ':' + seconds + ' ' + ampm;

    // Display the time
    $('#realtime').html(strTime);
    // $('#realtime').html(serverTime);

    $('#shift_label').html(shift);

    sessionStorage.setItem("empMgtServerDateTimeObject", serverDateTime);
  };

  const update_realtime = () => {
    $.ajax({
      type: "GET",
      url: "../process/admin/realtime/realtime_p.php",
      cache: false,
      data: {realtime:"realtime"},
      success: (response) => {
        try {
          let response_array = JSON.parse(response);
          $('#server_date_time').val(response_array.server_date_time);
          $('#realtime').html(response_array.server_time_a);

          // var serverDateTimeObject = new Date().toISOString().split('T')[0];
          var serverDateTimeObject = document.getElementById('date_time').value;
          sessionStorage.setItem("empMgtServerDateTimeObject", serverDateTimeObject);
        } catch (e) {
          console.log(response);
        }
      }
    });
  }

  const check_user_login_req_waiting = () => {
    // If an AJAX call is already in progress, return immediately
    if (check_user_login_req_waiting_ajax_in_process) {
      return;
    }

    // Set the flag to true as we're starting an AJAX call
    check_user_login_req_waiting_ajax_in_process = true;

    $.ajax({
      url: 'emsp_cron.php',
      type: 'POST',
      cache: false,
      data: {
        method: 'check_user_login_req_waiting'
      },
      success: (response) => {
        console.log(response);
        // Set the flag back to false as the AJAX call has completed
        check_user_login_req_waiting_ajax_in_process = false;
      }
    });
  }

  const check_leave_form_req_waiting = () => {
    // If an AJAX call is already in progress, return immediately
    if (check_leave_form_req_waiting_ajax_in_process) {
      return;
    }

    // Set the flag to true as we're starting an AJAX call
    check_leave_form_req_waiting_ajax_in_process = true;

    $.ajax({
      url: 'emsp_cron.php',
      type: 'POST',
      cache: false,
      data: {
        method: 'check_leave_form_req_waiting'
      },
      success: (response) => {
        console.log(response);
        // Set the flag back to false as the AJAX call has completed
        check_leave_form_req_waiting_ajax_in_process = false;
      }
    });
  }
</script>

</body>
</html>
