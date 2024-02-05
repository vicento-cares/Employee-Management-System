<?php
session_name("emp_mgt");
session_start();

include '../process/conn.php';

function get_shift($server_time) {
  if ($server_time >= '03:00:00' && $server_time < '15:00:00') {
    return 'DS';
  } else if ($server_time >= '15:00:00' && $server_time <= '23:59:59') {
    return 'NS';
  } else if ($server_time >= '00:00:00' && $server_time < '03:00:00') {
    return 'NS';
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

// REMOTE IP ADDRESS
$ip = $_SERVER['REMOTE_ADDR'];
$line_no_label = check_ip_access_location($ip, $conn);

if (!isset($_SESSION['emp_no'])) {
  header('location:../admin');
  exit;
} else if (isset($_POST['emp_no'])) {
  // Time In Process
  $emp_no = $_POST['emp_no'];
  $day = '';
  $shift = get_shift($server_time);
  $full_name = '';
  $provider = '';
  $dept = '';
  $section = '';
  $line_no = '';
  $shift_group = '';
  $unregistered = '';
  $wrong_scanning = '';
  $wrong_shift_group = '';
  $already_time_in = '';

  $sql = "SELECT `full_name`, `provider`, `dept`, `section`, `line_no`, `shift_group` FROM `m_employees` WHERE emp_no = '$emp_no' AND resigned = 0";
  $stmt = $conn -> prepare($sql);
  $stmt -> execute();

  if ($stmt -> rowCount() > 0) {
    while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
      $full_name = $row['full_name'];
      $provider = $row['provider'];
      $dept = $row['dept'];
      $section = $row['section'];
      $line_no = $row['line_no'];
      $shift_group = $row['shift_group'];
      // Added Temporarily
      if(empty($full_name)) {
        $full_name = ' ';
      }
    }

    if (!empty($line_no) && !empty($_SESSION['line_no']) && $_SESSION['line_no'] != $line_no) {
      $wrong_scanning = true;
    } else if (empty($shift_group) || empty($_SESSION['shift_group'])) {
      $wrong_shift_group = true;
    } else if (!empty($shift_group) && !empty($_SESSION['shift_group']) && $_SESSION['shift_group'] != $shift_group) {
      $wrong_shift_group = true;
    } else {
      // Set Day (Revised 2024-01-10)
      if ($server_time >= '00:00:00' && $server_time < '03:00:00') {
        $day = $server_date_only_yesterday;
      } else {
        $day = $server_date_only;
      }
      $sql = "SELECT `id` FROM `t_time_in_out` WHERE emp_no = '$emp_no' AND day = '$day' AND shift = '$shift'";
      $stmt = $conn -> prepare($sql);
      $stmt -> execute();
      if ($stmt -> rowCount() < 1) {
        $sql = "INSERT INTO `t_time_in_out` (`emp_no`, `day`, `shift`, `ip`) VALUES ('$emp_no', '$day', '$shift', '$ip')";
        $stmt = $conn -> prepare($sql);
        $stmt -> execute();
      } else {
        $already_time_in = true;
      }
    }
  } else {
    $unregistered = true;
  }
  $_POST['emp_no'] = NULL;
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
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <img src="../dist/img/logo.webp" style="height:100px;">
      <h3>Employee Management System - Time In</h3>
      <h1><b id="realtime"></b></h1>
      <h4><?=$line_no_label?></h4>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg"><b>Scan QR Code</b></p>

        <form action="" method="POST" id="scan_form">
          <div class="input-group mb-3">
            <input type="password" class="form-control" id="emp_no" name="emp_no" placeholder="Scan Here" oncopy="return false" onpaste="return false" autofocus autocomplete="off" required>
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
            <p class="login-box-msg"><b>Scanned in WRONG Line No. or PC</b></p>
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
            <p class="m-0 p-2 text-center">Employee No: <b><?=$emp_no?></b></p>
            <p class="m-0 p-2 text-center">Name: <b><?=$full_name?></b></p>
            <p class="m-0 p-2 text-center">Provider: <b><?=$provider?></b></p>
            <p class="m-0 p-2 text-center">Line No: <b><?=$line_no?></b></p>
            <p class="m-0 p-2 text-center">Time In: <b><?=$server_time_a?></b></p>
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
      if ($("#emp_no").val().length < 7) {
        $("#emp_no").val("");
      }
    }, 100);
  });

  const realtime =()=>{
    var realtime = "realtime";
    $.ajax({
      type: "GET",
      url: "../process/admin/realtime/realtime_p.php",
      cache:false,
      data: {realtime:realtime},
      success: (response)=>{
        $('#realtime').html(response);
      }
    });
  }
</script>

</body>
</html>
