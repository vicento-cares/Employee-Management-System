<?php 
session_name("emp_mgt");
session_start();

include '../conn.php';

function count_employee_list($search_arr, $conn) {
  $query = "SELECT count(id) AS total FROM m_employees WHERE";
  if (!empty($search_arr['emp_no'])) {
    $query = $query . " emp_no LIKE '".$search_arr['emp_no']."%'";
  } else {
    $query = $query . " emp_no != ''";
  }
  if (!empty($search_arr['full_name'])) {
    $query = $query . " AND full_name LIKE '".$search_arr['full_name']."%'";
  }
  if (!empty($search_arr['provider'])) {
    $query = $query . " AND provider = '".$search_arr['provider']."'";
  }
  if (isset($_SESSION['emp_no'])) {
    $query = $query . " AND dept = '".$_SESSION['dept']."' AND section = '".$_SESSION['section']."' AND line_no = '".$_SESSION['line_no']."'";
  } else {
    if (!empty($search_arr['dept'])) {
      $query = $query . " AND dept = '".$search_arr['dept']."'";
    }
    if (!empty($search_arr['section'])) {
      $query = $query . " AND section LIKE '".$search_arr['section']."%'";
    }
    if (!empty($search_arr['line_no'])) {
      $query = $query . " AND line_no LIKE '".$search_arr['line_no']."%'";
    }
  }

  $stmt = $conn->prepare($query);
  $stmt->execute();
  if ($stmt->rowCount() > 0) {
    foreach($stmt->fetchALL() as $j){
      $total = $j['total'];
    }
  }else{
    $total = 0;
  }
  return $total;
}

switch (true) {
  case !isset($_SESSION['emp_no']):
    header('location:/emp_mgt/admin');
    exit;
    break;
  case isset($_SESSION['emp_no_hr']):
    header('location:/emp_mgt/hr');
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
  case !isset($_GET['provider']):
    echo 'Query Parameters Not Set';
    exit;
    break;
}

$emp_no = addslashes(trim($_GET['emp_no']));
$full_name = addslashes(trim($_GET['full_name']));
$provider = trim($_GET['provider']);
$dept = $_SESSION['dept'];
$section = $_SESSION['section'];
$line_no = $_SESSION['line_no'];
$c = 0;

$search_arr = array(
  "emp_no" => $emp_no,
  "full_name" => $full_name,
  "provider" => $provider,
  "dept" => $dept,
  "section" => $section,
  "line_no" => $line_no
);

$count_employees = count_employee_list($search_arr, $conn);

$query = "SELECT id, emp_no, full_name, dept, section, line_no, position, provider, date_hired, address, contact_no, emp_status, shuttle_route, emp_js_s_no, emp_sv_no, emp_approver_no FROM m_employees WHERE";
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
$query = $query . " AND dept = '".$_SESSION['dept']."' AND section = '".$_SESSION['section']."' AND line_no = '".$_SESSION['line_no']."'";
$stmt = $conn->prepare($query);
$stmt->execute();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Print Employees (Admin)</title>

  <link rel="icon" href="../../dist/img/logo.ico" type="image/x-icon" />
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="../../dist/css/font.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
</head>
<body class="hold-transition layout-fixed">

  <div class="wrapper">

      
      <!-- Main content -->
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-12">
              <div class="card card-gray-dark card-outline">
                <div class="card-header">
                  <h3 class="card-title"><i class="fas fa-user"></i> Employees Table</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="maximize">
                      <i class="fas fa-expand"></i>
                    </button>
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="list_of_employees_table" class="table table-sm table-head-fixed text-nowrap table-hover">
                      <thead style="text-align: center;">
                        <tr>
                          <th>#</th>
                          <th>Employee No.</th>
                          <th>Full Name</th>
                          <th>Department</th>
                          <th>Section</th>
                          <th>Line No.</th>
                          <th>Provider</th>
                          <th>Shuttle Route</th>
                          <th>Position</th>
                          <th>Date Hired</th>
                          <th>Employment Status</th>
                        </tr>
                      </thead>
                      <tbody id="list_of_employees" style="text-align: center;">
                        <?php
                          if ($stmt->rowCount() > 0) {
                            foreach($stmt->fetchALL() as $j){
                              $c++;
                              echo '<tr>';
                                echo '<td>'.$c.'</td>';
                                echo '<td>'.$j['emp_no'].'</td>';
                                echo '<td>'.$j['full_name'].'</td>';
                                echo '<td>'.$j['dept'].'</td>';
                                echo '<td>'.$j['section'].'</td>';
                                echo '<td>'.$j['line_no'].'</td>';
                                echo '<td>'.$j['provider'].'</td>';
                                echo '<td>'.$j['shuttle_route'].'</td>';
                                echo '<td>'.$j['position'].'</td>';
                                echo '<td>'.$j['date_hired'].'</td>';
                                echo '<td>'.$j['emp_status'].'</td>';
                              echo '</tr>';
                            }
                          }else{
                            echo '<tr>';
                              echo '<td colspan="11" style="text-align:center; color:red;">No Result !!!</td>';
                            echo '</tr>';
                          }
                        ?>
                      </tbody>
                      <tfoot style="text-align: center;">
                        <tr>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th>Total MP :</th>
                          <th><?=$count_employees?></th>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>

  </div>

  <!-- jQuery -->
  <script src="../../plugins/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="../../dist/js/adminlte.js"></script>

  <script type="text/javascript">
    setTimeout(print_data, 2000);
    function print_data(){  
      window.print();
    }
  </script>

</body>
</html>