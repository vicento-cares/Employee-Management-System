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
  case !isset($_GET['id_arr']):
    echo 'Query Parameters Not Set';
    exit;
    break;
}

$id_arr = [];
$id_arr = explode(",", $_GET['id_arr']);

$c = 0;
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
  <?php 
  foreach ($id_arr as $id) {
    $query = "SELECT id, emp_no, full_name, role FROM m_accounts WHERE id = ?";

    $stmt = $conn->prepare($query);
    $params = array($id);
    $stmt->execute($params);
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $c++;
  ?>
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