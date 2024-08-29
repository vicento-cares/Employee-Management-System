<?php
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

function check_time_out_sa($server_time, $emp_no, $day, $shift, $conn) {

  $allow_time_out = false;

  $sql = "SELECT out_5, out_6, out_7, out_8 FROM t_shuttle_allocation WHERE emp_no = '$emp_no' AND day = '$day' AND shift = '$shift'";
  $stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
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
  $sql = "UPDATE t_time_in_out SET time_out = '$server_date_time' WHERE emp_no = '$emp_no' AND day = '$day' AND shift = '$shift'";
  $stmt = $conn -> prepare($sql);
  $stmt -> execute();
}

function get_line_no_office($conn) {
  $data = array();

  $sql = "SELECT line_no
          FROM m_access_locations
          WHERE dept NOT IN ('PD1','PD2') AND ip = '' 
          ORDER BY line_no ASC";
  $stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
  $stmt -> execute();
  while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
      array_push($data, $row['line_no']);
  }
  
  return $data;
}

// REMOTE IP ADDRESS
$ip = $_SERVER['REMOTE_ADDR'];
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
      $line_no_office_arr = get_line_no_office($conn);
      $not_office_employee = '';

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

          // Check Line No if listed as Support/Office Line No.
          if (!in_array($line_no, $line_no_office_arr)) {
            $not_office_employee = true;
          }

          if ($not_office_employee != true) {
            // MySQL
            // $sql = "SELECT day, shift FROM t_time_in_out WHERE emp_no = ? AND day = ? AND time_out IS NULL ORDER BY date_updated DESC LIMIT 1";
            // MS SQL Server
            $sql = "SELECT TOP 1 day, shift FROM t_time_in_out WHERE emp_no = ? AND day = ? AND time_out IS NULL ORDER BY date_updated DESC";
            $stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
            $params = array($emp_no, $server_date_only);
            $stmt -> execute($params);

            $row = $stmt -> fetch(PDO::FETCH_ASSOC);

            // Check first query result
            if (!$row) {
              // MySQL
              // $sql = "SELECT day, shift FROM t_time_in_out WHERE emp_no = ? AND day = ? AND shift = 'NS' AND time_out IS NULL ORDER BY date_updated DESC LIMIT 1";
              // MS SQL Server
              $sql = "SELECT TOP 1 day, shift FROM t_time_in_out WHERE emp_no = ? AND day = ? AND shift = 'NS' AND time_out IS NULL ORDER BY date_updated DESC";
              $stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
              $params = array($emp_no, $server_date_only_yesterday);
              $stmt -> execute($params);

              $row = $stmt -> fetch(PDO::FETCH_ASSOC);
            }

            // Check first or second query result
            if ($row) {
              $day = $row['day'];
              $shift = $row['shift'];

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

              $sql = "SELECT id FROM t_time_in_out WHERE emp_no = ? AND day = ? AND shift = ?";
              $stmt = $conn -> prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
              $params = array($emp_no, $server_date_only, $shift);
              $stmt -> execute($params);

              if ($stmt -> rowCount() < 1) {
                $no_time_in = true;
              } else {
                $already_time_out = true;
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

  <style type="text/css">
    .login-page {
      background: linear-gradient(to bottom, #cc2b19, #e74c3c, #ee8377, #f7c1bb);
      color: #fff;
    }
    .timeout-label {
      color: #343a40;
    }
  </style>
</head>

<body class="hold-transition login-page">
  <input type="hidden" id="server_date_time" value="<?=$server_date_time?>">
  <div class="login-box">
    <div class="login-logo">
      <img src="../dist/img/logo.webp" style="height:100px;">
      <h3 class="m-0">Employee Management System</h3>
      <h1 class="m-0"><b>TIME OUT</b></h1>
      <h1><b id="realtime"><?=$server_time_a?></b></h1>
      <h4>FAS Exit</h4>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg"><b>Scan QR Code</b></p>

        <form action="" method="POST" id="scan_form">
          <div class="input-group mb-3">
            <input type="password" class="form-control" id="emp_no" name="emp_no" placeholder="TIME OUT" oncopy="return false" onpaste="return false" autofocus autocomplete="off" maxlength="20" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-qrcode"></span>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
    <?php 
    if (!empty($full_name)) {
      if (!empty($wrong_scanning)) {
    ?>
      <div class="card mt-2">
        <div class="card-body timeout-label">
          <p class="login-box-msg"><b>Scanned in WRONG Line No. or PC<br>Note: If this was your new Line, please submit transfer form to HR</b></p>
        </div>
      </div>
    <?php
      } else if (!empty($wrong_shift_group)) {
    ?>
      <div class="card mt-2">
        <div class="card-body timeout-label">
          <p class="login-box-msg"><b>WRONG or NO Shift Group</b></p>
        </div>
      </div>
    <?php
      } else if (!empty($no_time_in)) {
    ?>
      <div class="card mt-2">
        <div class="card-body timeout-label">
          <p class="login-box-msg"><b>No Time In</b></p>
        </div>
      </div>
    <?php
      } else if (!empty($already_time_out)) {
    ?>
      <div class="card mt-2">
        <div class="card-body timeout-label">
          <p class="login-box-msg"><b>Already Time Out</b></p>
        </div>
      </div>
    <?php
      } else if (!empty($not_office_employee)) {
    ?>
        <div class="card mt-2">
          <div class="card-body">
            <p class="login-box-msg"><b>Only Support and Office Employees are allowed</b></p>
          </div>
        </div>
    <?php
      } else if (!empty($allow_time_out)) {
    ?>
      <div class="card mt-2">
        <div class="card-body timeout-label">
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
        <div class="card-body timeout-label">
          <p class="login-box-msg"><b>Time Out Failed. Maybe No Shuttle Sched, Shuttle Sched Not Now or Forgot To Time Out on time</b></p>
        </div>
      </div>
    <?php
      }
    } else if (!empty($unregistered)) {
    ?>
      <div class="card mt-2">
        <div class="card-body timeout-label">
          <p class="login-box-msg"><b>Time Out Failed. Unregistered or Resigned</b></p>
        </div>
      </div>
    <?php 
    } else if (!empty($error_message)) {
    ?>
      <div class="card mt-2">
        <div class="card-body timeout-label">
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
