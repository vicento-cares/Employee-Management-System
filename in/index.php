<?php
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

include '../process/conn.php';

function get_shift($server_time) {
  if ($server_time >= '05:00:00' && $server_time < '17:00:00') {
    return 'DS';
  } else if ($server_time >= '17:00:00' && $server_time <= '23:59:59') {
    return 'NS';
  } else if ($server_time >= '00:00:00' && $server_time < '05:00:00') {
    return 'NS';
  }
}

function get_shift_inverse($server_time) {
  if ($server_time >= '05:00:00' && $server_time < '17:00:00') {
    return 'NS';
  } else if ($server_time >= '17:00:00' && $server_time <= '23:59:59') {
    return 'DS';
  } else if ($server_time >= '00:00:00' && $server_time < '05:00:00') {
    return 'DS';
  }
}

function check_ip_access_location($ip, $conn) {
  $line_no = '';
  $sql = "SELECT line_no FROM m_access_locations WHERE ip = '$ip'";
  $stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
  $stmt -> execute();

  if ($stmt -> rowCount() > 0) {
    while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
      $line_no = $row['line_no'];
    }
  } else {
    $line_no = 'Unregistered IP: '.$ip.'! Call IT Personnel Immediately!';
  }

  return $line_no;
}

// REMOTE IP ADDRESS
$ip = $_SERVER['REMOTE_ADDR'];
$line_no_label = check_ip_access_location($ip, $conn);
$error_message = "";

if (!isset($_SESSION['emp_no'])) {
  header('location:../admin');
  exit;
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['emp_no'])) {
    if (!empty($_POST['emp_no'])) {
      // Time In Process
      $emp_no = $_POST['emp_no'];
      $day = '';
      $shift = get_shift($server_time);
      $full_name = '';
      $provider = '';
      $dept = '';
      $section = '';
      $sub_section = '';
      $line_no = '';
      $line_process = '';
      $shift_group = '';
      $concat_details = '';
      $unregistered = '';
      $wrong_scanning = '';
      $is_ads = false;
      $wrong_shift_group = '';
      $already_time_in = '';

      try {
        $sql = "SELECT full_name, provider, dept, section, sub_section, process, line_no, shift_group FROM m_employees WHERE emp_no = '$emp_no' AND resigned = 0";
        $stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        $stmt -> execute();

        if ($stmt -> rowCount() > 0) {
          while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
            $full_name = $row['full_name'];
            $provider = $row['provider'];
            $dept = $row['dept'];
            $section = $row['section'];
            $sub_section = $row['sub_section'];
            $line_no = $row['line_no'];
            $line_process = $row['process'];
            $shift_group = $row['shift_group'];
            $concat_details = $dept . '\\' . $section . '\\' . $section . '\\' . $sub_section . '\\' . $line_no . '\\' . $line_process;
            // Added Temporarily
            if(empty($full_name)) {
              $full_name = ' ';
            }
          }

          if (!empty($line_no) && !empty($_SESSION['line_no']) && $_SESSION['line_no'] != $line_no) {
            $wrong_scanning = true;
          } else if (empty($line_no) && !empty($_SESSION['line_no'])) {
            $wrong_scanning = true;
          }
          
          if ($wrong_scanning != true) {
            if (empty($shift_group) || empty($_SESSION['shift_group'])) {
              $wrong_shift_group = true;
            } else if ($_SESSION['shift_group'] != $shift_group) {
              if ($_SESSION['shift_group'] == 'ADS' || $shift_group == 'ADS') {
                $is_ads = true;
              }

              if ($is_ads != true) {
                $wrong_shift_group = true;
              }
            }

            if ($wrong_shift_group != true) {
              // Set Day (Revised 2024-01-10)
              if ($server_time >= '00:00:00' && $server_time < '05:00:00') {
                $day = $server_date_only_yesterday;
              } else {
                $day = $server_date_only;
              }
              $sql = "SELECT id, shift FROM t_time_in_out WHERE emp_no = '$emp_no' AND day = '$day'";
              $stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
              $stmt -> execute();
              if ($stmt -> rowCount() < 1) {
                $sql = "INSERT INTO t_time_in_out (emp_no, day, shift, ip) VALUES ('$emp_no', '$day', '$shift', '$ip')";
                $stmt = $conn -> prepare($sql);
                $stmt -> execute();
              } else {
                $sql = "UPDATE t_time_in_out SET time_in = '$server_date_time' WHERE emp_no = '$emp_no' AND day = '$day' AND shift = '$shift'";
                $stmt = $conn -> prepare($sql);
                $stmt -> execute();
              }
            }
          }
        } else {
          $unregistered = true;
        }
      } catch (PDOException $e) {
        $full_name = '';
        $unregistered = '';
        $wrong_scanning = '';
        $wrong_shift_group = '';
        $already_time_in = '';
        $error_message .= "System Error: " . $e->getMessage() . " Call IT Personnel Immediately.";
      }
      $_POST['emp_no'] = NULL;
    } else {
      $error_message .= "Error: Empty data recieved. Please try again or call IT Personnel Immediately.";
    }
  } else {
    $error_message .= "Error: Data not set. Please try again or call IT Personnel Immediately.";
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Time In</title>

  <link rel="icon" href="../dist/img/logo.ico" type="image/x-icon" />
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="../dist/css/font.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">

  <style type="text/css">
    .login-page {
      background: linear-gradient(to bottom, #20c997, #4fe3b7, #91eed2, #d3f8ed);
    }
  </style>
</head>

<body class="hold-transition login-page">
  <input type="hidden" id="server_date_time" value="<?=$server_date_time?>">
  <div class="login-box">
    <div class="login-logo">
      <img src="../dist/img/logo.webp" style="height:100px;">
      <h3>Employee Management System</h3>
      <h1 class="m-0"><b>TIME IN</b></h1>
      <h1 class="m-0"><b id="realtime"><?=$server_time_a?></b></h1>
      <h4><?=$line_no_label?></h4>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg"><b>Scan QR Code</b></p>

        <form action="" method="POST" id="scan_form">
          <div class="input-group mb-3">
            <input type="password" class="form-control" id="emp_no" name="emp_no" placeholder="TIME IN" oncopy="return false" onpaste="return false" autofocus autocomplete="off" maxlength="20" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-qrcode"></span>
              </div>
            </div>
          </div>
        </form>

        <p class="mb-1">
          <a href="../admin/home.php">Go back to homepage</a>
        </p>
      </div>
    </div>
    <?php 
    if (!empty($full_name)) {
      if (!empty($wrong_scanning)) {
    ?>
        <div class="card mt-2">
          <div class="card-body">
            <p class="login-box-msg"><b>Scanned in WRONG Line No. or PC<br>Note: If this was your new Line, please submit transfer form to HR</b></p>
          </div>
        </div>
    <?php
      } else if (!empty($wrong_shift_group)) {
    ?>
        <div class="card mt-2">
          <div class="card-body">
            <p class="login-box-msg"><b>WRONG or NO Shift Group</b></p>
          </div>
        </div>
    <?php
      } else if (!empty($already_time_in)) {
    ?>
        <div class="card mt-2">
          <div class="card-body">
            <p class="login-box-msg"><b>Already Time In</b></p>
          </div>
        </div>
    <?php 
      } else {
    ?>
        <div class="card mt-2">
          <div class="card-body">
            <p class="m-0 p-1 text-center">Employee No: <b><?=$emp_no?></b></p>
            <p class="m-0 p-1 text-center">Name: <b><?=$full_name?></b></p>
            <p class="m-0 p-1 text-center">Provider: <b><?=$provider?></b></p>
            <p class="m-0 p-1 text-center">Line No: <b><?=$line_no?></b></p>
            <p class="m-0 p-1 text-center">Details: <b><?=$concat_details?></b></p>
            <p class="m-0 p-1 text-center">Shift Group: <b><?=$shift_group?></b></p>
            <p class="m-0 p-1 text-center">Time In: <b><?=$server_time_a?></b></p>
          </div>
        </div>
    <?php 
      }
    } else if (!empty($unregistered)) {
    ?>
      <div class="card mt-2">
        <div class="card-body">
          <p class="login-box-msg"><b>Time Out Failed. Unregistered or Resigned</b></p>
        </div>
      </div>
    <?php 
    } else if (!empty($error_message)) {
    ?>
      <div class="card mt-2">
        <div class="card-body">
          <p class="login-box-msg"><b><?=$error_message?></b></p>
        </div>
      </div>
    <?php 
    }
    ?>
  </div>
</body>

<!-- jQuery -->
<script src="../plugins/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>

<!-- Idletime Script -->
<script src="../dist/js/idletime.js"></script>

<script>
  // var serverTime = document.getElementById("server_time").value;

  // DOMContentLoaded function
  document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("emp_no").focus();
    
    // var serverDateTimeObject = new Date().toISOString().split('T')[0];
    var serverDateTimeObject = document.getElementById('server_date_time').value;
    sessionStorage.setItem("empMgtServerDateTimeObject", serverDateTimeObject);

    setInterval(realtime, 1000);
  });

  var delay = (function(){
    var timer = 0;
    return function(callback, ms){
      clearTimeout (timer);
      timer = setTimeout(callback, ms);
    };
  })();

  $("#emp_no").on("input", function() {
    delay(function(){
      if ($("#emp_no").val().length < 21) {
        $("#emp_no").val("");
      }
    }, 100);
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
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    hours = hours < 10 ? '0'+hours : hours;
    minutes = minutes < 10 ? '0'+minutes : minutes;
    seconds = seconds < 10 ? '0'+seconds : seconds;
    var strTime = hours + ':' + minutes + ':' + seconds + ' ' + ampm;

    // Display the time
    $('#realtime').html(strTime);
    // $('#realtime').html(serverTime);

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
</script>

</body>
</html>
