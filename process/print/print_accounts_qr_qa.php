<?php 
include '../conn.php';

$c = 0;

$query = "SELECT emp_no, full_name FROM m_accounts WHERE emp_no LIKE 'QA%'";
$stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt->execute();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Print Accounts QR QA</title>

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
              <span class="font-weight-bold" style="font-size:12px;">FAS</span>
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