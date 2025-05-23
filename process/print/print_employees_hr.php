<?php 
session_set_cookie_params(0, "/emp_mgt");
session_name("emp_mgt");
session_start();

include '../conn.php';

function count_employee_list($search_arr, $conn) {
  $query = "SELECT count(id) AS total FROM m_employees WHERE";
  $params = [];

  if (!empty($search_arr['emp_no'])) {
    $query = $query . " emp_no LIKE ?";
    $emp_no_param = $search_arr['emp_no'] ."%";
    $params[] = $emp_no_param;
  } else {
    $query = $query . " emp_no != ''";
  }
  if (!empty($search_arr['full_name'])) {
    $query = $query . " AND full_name LIKE ?";
    $full_name_param = $search_arr['full_name'] ."%";
    $params[] = $full_name_param;
  }
  if (!empty($search_arr['provider'])) {
    $query = $query . " AND provider = ?";
    $params[] = $search_arr['provider'];
  }
  if (isset($_SESSION['emp_no'])) {
    if (isset($_SESSION['dept']) && !empty($_SESSION['dept'])) {
      $dept = $_SESSION['dept'];
      $query = $query . " AND dept = '$dept'";
      $params[] = $dept;
    } else {
      $query = $query . " AND dept IS NULL";
    }
    if (isset($_SESSION['section']) && !empty($_SESSION['section'])) {
      $section = $_SESSION['section'];
      $query = $query . " AND section = '$section'";
      $params[] = $section;
    } else {
      $query = $query . " AND section IS NULL";
    }
    if (isset($_SESSION['line_no']) && !empty($_SESSION['line_no'])) {
      $line_no = $_SESSION['line_no'];
      $query = $query . " AND line_no = '$line_no'";
      $params[] = $line_no;
    } else {
      $query = $query . " AND line_no IS NULL";
    }

    /*$query = $query . " AND dept = '".$_SESSION['dept']."' AND section = '".$_SESSION['section']."' AND line_no = '".$_SESSION['line_no']."'";*/
  } else {
    if (!empty($search_arr['dept'])) {
      $query = $query . " AND dept = '".$search_arr['dept']."'";
      $params[] = $search_arr['dept'];
    }
    if (!empty($search_arr['section'])) {
      $query = $query . " AND section LIKE '".$search_arr['section']."%'";
      $section_param = $search_arr['section'] ."%";
      $params[] = $section_param;
    }
    if (!empty($search_arr['line_no'])) {
      $query = $query . " AND line_no LIKE '".$search_arr['line_no']."%'";
      $line_no_param = $search_arr['line_no'] ."%";
      $params[] = $line_no_param;
    }
  }

  if (!empty($search_arr['date_updated_from']) && !empty($search_arr['date_updated_to'])) {
    $query = $query . " AND date_updated BETWEEN ? AND ?";
    $params[] = $search_arr['date_updated_from'];
    $params[] = $search_arr['date_updated_to'];
  }

  if ($search_arr['resigned'] != '') {
    $query = $query . " AND resigned = ?";
    $params[] = $search_arr['resigned'];
  }

  $stmt = $conn->prepare($query);
  $stmt->execute($params);

  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($row) {
    $total = $row['total'];
  } else {
    $total = 0;
  }

  return $total;
}

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
  case !isset($_GET['provider']):
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

$emp_no = trim($_GET['emp_no']);
$full_name = trim($_GET['full_name']);
$provider = trim($_GET['provider']);
$dept = trim($_GET['dept']);
$section = trim($_GET['section']);
$line_no = trim($_GET['line_no']);

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

$search_arr = array(
  "emp_no" => $emp_no,
  "full_name" => $full_name,
  "provider" => $provider,
  "dept" => $dept,
  "section" => $section,
  "line_no" => $line_no,
  "date_updated_from" => $date_updated_from,
  "date_updated_to" => $date_updated_to,
  "resigned" => $resigned
);

$count_employees = count_employee_list($search_arr, $conn);

$query = "SELECT 
            id, emp_no, full_name, dept, section, line_no, position, provider, 
            date_hired, address, contact_no, emp_status, shuttle_route, 
            emp_js_s_no, emp_sv_no, emp_approver_no 
          FROM m_employees WHERE";
$params = [];

if (!empty($emp_no)) {
  $query = $query . " emp_no LIKE ?";
  $emp_no_param = $emp_no ."%";
  $params[] = $emp_no_param;
} else {
  $query = $query . " emp_no != ''";
}
if (!empty($full_name)) {
  $query = $query . " AND full_name LIKE ?";
  $full_name_param = $full_name ."%";
  $params[] = $full_name_param;
}
if (!empty($provider)) {
  $query = $query . " AND provider = ?";
  $params[] = $provider;
}
if (!empty($dept)) {
  $query = $query . " AND dept = ?";
  $params[] = $dept;
}
if (!empty($section)) {
  $query = $query . " AND section LIKE ?";
  $section_param = $section ."%";
  $params[] = $section_param;
}
if (!empty($line_no)) {
  $query = $query . " AND line_no LIKE ?";
  $line_no_param = $line_no ."%";
  $params[] = $line_no_param;
}

if (!empty($date_updated_from) && !empty($date_updated_to)) {
  $query = $query . " AND date_updated BETWEEN ? AND ?";
  $params[] = $date_updated_from;
  $params[] = $date_updated_to;
}

if ($resigned != '') {
  if ($resigned == 1 || $resigned == 0) {
    $query = $query . " AND resigned = ?";
    $params[] = $resigned;
  }
}

$stmt = $conn->prepare($query);
$stmt->execute($params);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Print Employees (HR)</title>

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
                          $row = $stmt->fetch(PDO::FETCH_ASSOC);

                          if ($row) {
                            do {
                              $c++;
                              echo '<tr>';
                                echo '<td>'.$c.'</td>';
                                echo '<td>'.$row['emp_no'].'</td>';
                                echo '<td>'.$row['full_name'].'</td>';
                                echo '<td>'.$row['dept'].'</td>';
                                echo '<td>'.$row['section'].'</td>';
                                echo '<td>'.$row['line_no'].'</td>';
                                echo '<td>'.$row['provider'].'</td>';
                                echo '<td>'.$row['shuttle_route'].'</td>';
                                echo '<td>'.$row['position'].'</td>';
                                echo '<td>'.$row['date_hired'].'</td>';
                                echo '<td>'.$row['emp_status'].'</td>';
                              echo '</tr>';
                            } while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
                          } else {
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