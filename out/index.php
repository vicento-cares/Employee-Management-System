<?php
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

function get_shift_line_support($server_time) {
  if ($server_time >= '07:00:00' && $server_time < '19:00:00') {
    return 'DS';
  } else if ($server_time >= '19:00:00' && $server_time <= '23:59:59') {
    return 'NS';
  } else if ($server_time >= '00:00:00' && $server_time < '07:00:00') {
    return 'NS';
  }
}

function get_day($server_time, $server_date_only, $server_date_only_yesterday) {
  if ($server_time >= '06:00:00' && $server_time <= '23:59:59') {
    return $server_date_only;
  } else if ($server_time >= '00:00:00' && $server_time < '06:00:00') {
    return $server_date_only_yesterday;
  }
}

function check_ip_access_location($ip, $conn) {
  $line_no = '';
  $sql = "SELECT line_no FROM `m_access_locations` WHERE ip = '$ip'";
  $stmt = $conn -> prepare($sql);
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

function check_time_out_sa($server_time, $emp_no, $day, $shift, $conn) {

  $allow_time_out = false;

  $sql = "SELECT `out_5`, `out_6`, `out_7`, `out_8` FROM `t_shuttle_allocation` WHERE emp_no = '$emp_no' AND day = '$day' AND shift = '$shift'";
  $stmt = $conn -> prepare($sql);
  $stmt -> execute();

  if ($stmt -> rowCount() > 0) {
    while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
      $out_5 = $row['out_5'];
      $out_6 = $row['out_6'];
      $out_7 = $row['out_7'];
      $out_8 = $row['out_8'];
    }

    if ($shift == 'DS') {
      $out_5_start_time = '15:00:00';
      $out_6_start_time = '16:00:00';
      $out_7_start_time = '17:00:00';
      $out_8_start_time = '18:00:00';
      $out_8_end_time = '19:00:00';
    } else if ($shift == 'NS') {
      $out_5_start_time = '03:00:00';
      $out_6_start_time = '04:00:00';
      $out_7_start_time = '05:00:00';
      $out_8_start_time = '06:00:00';
      $out_8_end_time = '07:00:00';
    }

    switch (true) {
      case !empty($out_5):
        if ($server_time >= $out_5_start_time && $server_time < $out_6_start_time) {
          $allow_time_out = true;
        }
        break;
      case !empty($out_6):
        if ($server_time >= $out_6_start_time && $server_time < $out_7_start_time) {
          $allow_time_out = true;
        }
        break;
      case !empty($out_7):
        if ($server_time >= $out_7_start_time && $server_time < $out_8_start_time) {
          $allow_time_out = true;
        }
        break;
      case !empty($out_8):
        if ($server_time >= $out_8_start_time && $server_time < $out_8_end_time) {
          $allow_time_out = true;
        }
        break;
      default:
        $allow_time_out = false;
    }
  } else {
    $allow_time_out = false;
  }

  return $allow_time_out;
}

function set_time_out($server_date_time, $emp_no, $day, $shift, $conn) {
  $sql = "UPDATE `t_time_in_out` SET time_out = '$server_date_time' WHERE emp_no = '$emp_no' AND day = '$day' AND shift = '$shift'";
  $stmt = $conn -> prepare($sql);
  $stmt -> execute();
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
      // Time Out Process
      $emp_no = $_POST['emp_no'];
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
      $no_time_in = '';
      $already_time_out = '';
      $allow_time_out = '';

      try {
        $sql = "SELECT `full_name`, `provider`, `dept`, `section`, `sub_section`, `process`, `line_no`, `shift_group` FROM `m_employees` WHERE emp_no = '$emp_no' AND resigned = 0";
        $stmt = $conn -> prepare($sql);
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
            $shift = get_shift_line_support($server_time);
            $day = get_day($server_time, $server_date_only, $server_date_only_yesterday);

            // Line Support Query
            $sql = "SELECT id FROM t_line_support_history WHERE emp_no = '$emp_no' AND day = '$day' AND shift = '$shift' AND status = 'accepted' ORDER BY date_updated DESC LIMIT 1";
            $stmt = $conn -> prepare($sql);
            $stmt -> execute();

            if ($stmt -> rowCount() < 1) {
              $wrong_scanning = true;
            }
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
              $sql = "SELECT `day`, `shift` FROM `t_time_in_out` WHERE emp_no = '$emp_no' AND day = '$server_date_only' AND time_out IS NULL ORDER BY date_updated DESC LIMIT 1";
              $stmt = $conn -> prepare($sql);
              $stmt -> execute();

              if ($stmt -> rowCount() > 0) {
                while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
                  $day = $row['day'];
                  $shift = $row['shift'];
                }

                // Check Shuttle Allocation for Time Out
                /*$allow_time_out = check_time_out_sa($server_time, $emp_no, $day, $shift, $conn);
                if ($allow_time_out == true) {
                  set_time_out($server_date_time, $emp_no, $day, $shift, $conn);
                }*/

                // Temporary Allow Timeout W/O Shuttle Allocation
                $allow_time_out = true;
                set_time_out($server_date_time, $emp_no, $day, $shift, $conn);

              } else {

                $sql = "SELECT `day`, `shift` FROM `t_time_in_out` WHERE emp_no = '$emp_no' AND day = '$server_date_only_yesterday' AND shift = 'NS' AND time_out IS NULL ORDER BY date_updated DESC LIMIT 1";
                $stmt = $conn -> prepare($sql);
                $stmt -> execute();

                if ($stmt -> rowCount() > 0) {
                  while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
                    $day = $row['day'];
                    $shift = $row['shift'];
                  }

                  // Check Shuttle Allocation for Time Out
                  /*$allow_time_out = check_time_out_sa($server_time, $emp_no, $day, $shift, $conn);
                  if ($allow_time_out == true) {
                    set_time_out($server_date_time, $emp_no, $day, $shift, $conn);
                  }*/

                  // Temporary Allow Timeout W/O Shuttle Allocation
                  $allow_time_out = true;
                  set_time_out($server_date_time, $emp_no, $day, $shift, $conn);

                } else {

                  $shift = get_shift($server_time);

                  $sql = "SELECT `id` FROM `t_time_in_out` WHERE emp_no = '$emp_no' AND day = '$server_date_only' AND shift = '$shift'";
                  $stmt = $conn -> prepare($sql);
                  $stmt -> execute();
                  if ($stmt -> rowCount() < 1) {
                    $no_time_in = true;
                  } else {
                    $already_time_out = true;
                  }
                  
                }
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
        $no_time_in = '';
        $already_time_out = '';
        $allow_time_out = '';
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
  <title>Time Out</title>

  <link rel="icon" href="../dist/img/logo.ico" type="image/x-icon" />
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="../dist/css/font.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
  <input type="hidden" id="server_time" value="<?=$server_time?>">
  <div class="login-box">
    <div class="login-logo">
      <img src="../dist/img/logo.webp" style="height:100px;">
      <h3>Employee Management System - Time Out</h3>
      <h1><b id="realtime"></b></h1>
      <h4><?=$line_no_label?></h4>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg"><b>Scan QR Code</b></p>

        <form action="" method="POST" id="scan_form">
          <div class="input-group mb-3">
            <input type="password" class="form-control" id="emp_no" name="emp_no" placeholder="Scan Here" oncopy="return false" onpaste="return false" autofocus autocomplete="off" maxlength="20" required>
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
      } else if (!empty($no_time_in)) {
    ?>
      <div class="card mt-2">
        <div class="card-body">
          <p class="login-box-msg"><b>No Time In</b></p>
        </div>
      </div>
    <?php
      } else if (!empty($already_time_out)) {
    ?>
      <div class="card mt-2">
        <div class="card-body">
          <p class="login-box-msg"><b>Already Time Out</b></p>
        </div>
      </div>
    <?php
      } else if (!empty($allow_time_out)) {
    ?>
      <div class="card mt-2">
        <div class="card-body">
          <p class="m-0 p-1 text-center">Employee No: <b><?=$emp_no?></b></p>
          <p class="m-0 p-1 text-center">Name: <b><?=$full_name?></b></p>
          <p class="m-0 p-1 text-center">Provider: <b><?=$provider?></b></p>
          <p class="m-0 p-1 text-center">Line No: <b><?=$line_no?></b></p>
          <p class="m-0 p-1 text-center">Details: <b><?=$concat_details?></b></p>
          <p class="m-0 p-1 text-center">Shift Group: <b><?=$shift_group?></b></p>
          <p class="m-0 p-1 text-center">Time Out: <b><?=$server_time_a?></b></p>
        </div>
      </div>
    <?php 
      } else {
    ?>
      <div class="card mt-2">
        <div class="card-body">
          <p class="login-box-msg"><b>Time Out Failed. Maybe No Shuttle Sched, Shuttle Sched Not Now or Forgot To Time Out on time</b></p>
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
  var serverTime = document.getElementById("server_time").value;

  // DOMContentLoaded function
  document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("emp_no").focus();
    realtime();
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

  const realtime = () => {
    // Create a Date object from the server time
    var serverDate = new Date("1970-01-01T" + serverTime + "Z");

    // Increment the server time by one second
    serverDate.setSeconds(serverDate.getSeconds() + 1);

    // Update the serverTime variable
    serverTime = serverDate.toISOString().substr(11, 8);

    // Create a new Date object for the display time
    var displayDate = new Date(serverDate.getTime());

    // Adjust for the Philippine time zone (GMT+8)
    // -8 instead of +8
    displayDate.setHours(displayDate.getHours() - 8);

    // Convert to 12-hour format
    var hours = displayDate.getHours();
    var minutes = displayDate.getMinutes();
    var seconds = displayDate.getSeconds();
    var ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    minutes = minutes < 10 ? '0'+minutes : minutes;
    seconds = seconds < 10 ? '0'+seconds : seconds;
    var strTime = hours + ':' + minutes + ':' + seconds + ' ' + ampm;

    // Display the time
    $('#realtime').html(strTime);
  };

  // const realtime =()=>{
  //   var realtime = "realtime";
  //   $.ajax({
  //     type: "GET",
  //     url: "../process/admin/realtime/realtime_p.php",
  //     cache:false,
  //     data: {realtime:realtime},
  //     success: (response)=>{
  //       $('#realtime').html(response);
  //     }
  //   });
  // }
</script>

</body>
</html>
