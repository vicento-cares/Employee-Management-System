<?php 
//SESSION
include '../process/login.php';

if (!isset($_SESSION['emp_no'])) {
  header('location:/it_emp_mgt/admin');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Print Employees</title>

  <link rel="icon" href="../dist/img/logo.ico" type="image/x-icon" />
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="../dist/css/font.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>
<body class="hold-transition layout-fixed">

  <div class="wrapper">
    <div class="content-wrapper">
      
      <!-- Main content -->
      <section class="content">
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
                  <div class="table-responsive" style="max-height: 500px; overflow: auto; display:inline-block;">
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
                        </tr>
                      </thead>
                      <tbody id="list_of_employees" style="text-align: center;"></tbody>
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
      </section>

    </div>
  </div>

  <!-- jQuery -->
  <script src="../plugins/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="../dist/js/adminlte.js"></script>

  <script type="text/javascript">
    setTimeout(print_data, 2000);
    function print_data(){  
      window.print();
    }
  </script>

</body>
</html>