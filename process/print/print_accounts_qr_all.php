<?php 
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

include '../conn.php';

switch (true) {
  case !isset($_SESSION['emp_no_hr']):
    header('location:/emp_mgt/hr');
    exit();
  case isset($_SESSION['emp_no']):
    header('location:/emp_mgt/admin');
    exit();
  case isset($_SESSION['emp_no_user']):
    header('location:/emp_mgt/user');
    exit();
  case isset($_SESSION['emp_no_clinic']):
    header('location:/emp_mgt/clinic');
    exit();
}

switch (true) {
  case !isset($_GET['emp_no']):
  case !isset($_GET['full_name']):
  case !isset($_GET['dept']):
  case !isset($_GET['section']):
  case !isset($_GET['line_no']):
  case !isset($_GET['role']):
    echo 'Query Parameters Not Set';
    exit();
}

$emp_no = trim($_GET['emp_no']);
$full_name = trim($_GET['full_name']);
$dept = trim($_GET['dept']);
$section = trim($_GET['section']);
$line_no = trim($_GET['line_no']);
$role = trim($_GET['role']);

$c = 0;

$query = "SELECT id, emp_no, full_name, role FROM m_accounts WHERE";
$params = [];

if (!empty($emp_no)) {
  $query = $query . " emp_no LIKE ?";
	$emp_no_search = $search_arr['emp_no'] . "%";
	$params[] = $emp_no_search;
} else {
  $query = $query . " emp_no != ''";
}
if (!empty($full_name)) {
  $query = $query . " AND full_name LIKE ?";
	$full_name_search = $search_arr['full_name'] . "%";
	$params[] = $full_name_search;
}
if (!empty($dept)) {
  $query = $query . " AND dept = ?";
  $params[] = $dept;
}
if (!empty($section)) {
  $query = $query . " AND section LIKE ?";
  $section_search = $section . "%";
  $params[] = $section_search;
}
if (!empty($line_no)) {
  $query = $query . " AND line_no LIKE ?";
  $line_no_search = $line_no . "%";
  $params[] = $line_no_search;
}
if (!empty($role)) {
  $query = $query . " AND role = ?";
	$params[] = $search_arr['role'];
}

$stmt = $conn->prepare($query);
$stmt->execute($params);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Print Accounts QR (Admin)</title>

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
  <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { $c++;?>
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
              <span class="mr-2" style="font-size:12px;">Role :</span>
              <span class="font-weight-bold" style="font-size:12px;"><?=htmlspecialchars($row['role'])?></span>
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