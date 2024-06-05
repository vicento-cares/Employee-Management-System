<?php 
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

include '../conn.php';

switch (true) {
  case !isset($_SESSION['emp_no_hr']):
    header('location:/emp_mgt/hr');
    exit;
    break;
  case isset($_SESSION['emp_no']):
    header('location:/emp_mgt/admin');
    exit;
    break;
  case isset($_SESSION['emp_no_user']):
    header('location:/emp_mgt/user');
    exit;
    break;
  case isset($_SESSION['emp_no_clinic']):
    header('location:/emp_mgt/clinic');
    exit;
    break;
}

switch (true) {
  case !isset($_GET['emp_no']):
  case !isset($_GET['full_name']):
  case !isset($_GET['dept']):
  case !isset($_GET['section']):
  case !isset($_GET['line_no']):
  case !isset($_GET['date_updated_from']):
  case !isset($_GET['date_updated_to']):
  case !isset($_GET['resigned']):
    echo 'Query Parameters Not Set';
    exit;
    break;
}

$emp_no = addslashes(trim($_GET['emp_no']));
$full_name = addslashes(trim($_GET['full_name']));
$provider = trim($_GET['provider']);
$dept = trim($_GET['dept']);
$section = addslashes(trim($_GET['section']));
$line_no = addslashes(trim($_GET['line_no']));

$date_updated_from = '';
if (isset($_GET['date_updated_from'])) {
  $date_updated_from = $_GET['date_updated_from'];
}
if (!empty($date_updated_from)) {
  $date_updated_from = date_create($date_updated_from);
  $date_updated_from = date_format($date_updated_from,"Y-m-d H:i:s");
}

$date_updated_to = '';
if (isset($_GET['date_updated_to'])) {
  $date_updated_to = $_GET['date_updated_to'];
}
if (!empty($date_updated_to)) {
  $date_updated_to = date_create($date_updated_to);
  $date_updated_to = date_format($date_updated_to,"Y-m-d H:i:s");
}

$resigned = trim($_GET['resigned']);

$c = 0;

$query = "SELECT id, emp_no, full_name, provider FROM m_employees WHERE";
if (!empty($emp_no)) {
  $query = $query . " emp_no LIKE '".$emp_no."%'";
} else {
  $query = $query . " emp_no != ''";
}
if (!empty($full_name)) {
  $query = $query . " AND full_name LIKE '$full_name%'";
}
if (!empty($provider)) {
  $query = $query . " AND provider = '$provider'";
}
if (!empty($dept)) {
  $query = $query . " AND dept = '$dept'";
}
if (!empty($section)) {
  $query = $query . " AND section LIKE '$section%'";
}
if (!empty($line_no)) {
  $query = $query . " AND line_no LIKE '$line_no%'";
}

if (!empty($date_updated_from) && !empty($date_updated_to)) {
  $query = $query . " AND date_updated BETWEEN '$date_updated_from' AND '$date_updated_to'";
}

if ($resigned != '') {
  if ($resigned == 1 || $resigned == 0) {
    $query = $query . " AND resigned = '$resigned'";
  }
}

$stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt->execute();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Print Employees QR (Admin)</title>

  <link rel="icon" href="../../dist/img/logo.ico" type="image/x-icon" />
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="../../dist/css/font.min.css">
  <!-- Bootstrap -->
  <link rel="stylesheet" href="../../plugins/bootstrap/css/bootstrap.min.css">

  <style>
    @media print{@page {size: portrait;}}
    table, tr, td, th {
      color: black;
      border: 1px solid black;
      border-width: medium;
      border-collapse: collapse;
    }
  </style>

  <!-- jQuery -->
  <script src="../../plugins/jquery/dist/jquery.min.js"></script>
  <script src="../../plugins/jqueryqrcode/jquery.qrcode.min.js"></script>
</head>
<body class="mx-0 my-0">

  <noscript>
    <br>
    <span>We are facing <strong>Script</strong> issues. Kindly enable <strong>JavaScript</strong>!!!</span>
    <br>
    <span>Call IT Personnel Immediately!!! They will fix it right away.</span>
  </noscript>

  <div class="row">
  <?php foreach($stmt -> fetchAll() as $row) { $c++;?>
  <div class="col-4">
    <table class="mx-0 my-0" style="height:100%;width:100%;table-layout:fixed;">
      <tbody>
        <tr>
          <td class="pt-3">
            <center><label id="qr<?=htmlspecialchars($c);?>"></label></center>
          </td>
          <td class="w-75">
            <div class="row ml-2">
              <span class="mr-2" style="font-size:12px;">Employee No. :</span>
              <span class="font-weight-bold" style="font-size:12px;"><?=htmlspecialchars($row['emp_no'])?></span>
            </div>
            <div class="row ml-2">
              <span class="mr-1" style="font-size:11px;">Name :</span>
              <span class="font-weight-bold" style="font-size:11px;"><?=htmlspecialchars($row['full_name'])?></span>
            </div>
            <div class="row ml-2">
              <span class="mr-2" style="font-size:12px;">Provider :</span>
              <span class="font-weight-bold" style="font-size:12px;"><?=htmlspecialchars($row['provider'])?></span>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <script>
    $('#qr<?=$c;?>').qrcode({
      text: "<?=addslashes($row['emp_no']);?>",
      width: 50,
      height: 50
    });
  </script>
  <?php 
  } 
  $conn = null;
  ?>
  </div>

  <!-- Bootstrap 4 -->
  <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

  <script type="text/javascript">
    setTimeout(print_data, 2000);
    function print_data(){  
      window.print();
    }
  </script>

</body>
</html>