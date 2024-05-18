<?php include 'plugins/navbar.php';?>
<?php include 'plugins/sidebar/admin_bar.php';?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Employee Masterlist</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="home.php">Home</a></li>
            <li class="breadcrumb-item active">Employee Masterlist</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- <div class="row mb-4">
        <div class="col-sm-2">
          <button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#new_employee"><i class="fas fa-plus-circle"></i> Register Employee</button>
        </div>
        <div class="col-sm-2">
          <button type="button" class="btn btn-dark btn-block" data-toggle="modal" data-target="#import_employees"><i class="fas fa-upload"></i> Import Employees</button>
        </div>
        <div class="col-sm-2">
          <a class="btn btn-dark btn-block" href="../process/export/exp_employees.php"><i class="fas fa-download"></i> Export Employees</a>
        </div>
      </div> -->
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
              <div class="row mb-4">
                <div class="col-sm-2">
                  <label>Employee No.</label>
                  <input type="text" class="form-control" id="emp_no_master_search" placeholder="Search" autocomplete="off" maxlength="255">
                </div>
                <div class="col-sm-4">
                  <label>Full Name</label>
                  <input type="text" class="form-control" id="full_name_master_search" placeholder="Search" autocomplete="off" maxlength="255">
                </div>
                <div class="col-sm-2">
                  <label>Provider:</label>
                  <select id="provider_master_search" class="form-control" onchange="load_employees(1)">
                    <option value="">Select Provider</option>
                    <option value="FAS">FAS</option>
                    <option value="PKIMT">PKIMT</option>
                    <option value="MAXIM">MAXIM</option>
                    <option value="ONE SOURCE">ONE SOURCE</option>
                    <option value="MEGATREND">MEGATREND</option>
                    <option value="ADD EVEN">ADD EVEN</option>
                  </select>
                </div>
                <div class="col-sm-2">
                  <label>&nbsp;</label>
                  <button type="button" class="btn bg-gray-dark btn-block" onclick="load_employees(1)"><i class="fas fa-search"></i> Search</button>
                </div>
                <div class="col-sm-2">
                  <label>&nbsp;</label>
                  <button type="button" class="btn bg-gray-dark btn-block" onclick="print_employees()"><i class="fas fa-print"></i> Print</button>
                </div>
              </div>
              <div id="list_of_employees_res" class="table-responsive" style="max-height: 500px; overflow: auto; display:inline-block;">
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
                  <tbody id="list_of_employees" style="text-align: center;">
                    <tr>
                      <td colspan="8" style="text-align:center;">
                        <div class="spinner-border text-dark" role="status">
                          <span class="sr-only">Loading...</span>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="d-flex justify-content-sm-end">
                <div class="dataTables_info" id="list_of_employees_info" role="status" aria-live="polite"></div>
              </div>
              <div class="d-flex justify-content-sm-center">
                <button type="button" class="btn bg-gray-dark" id="btnNextPage" style="display:none;" onclick="get_next_page()">Load more</button>
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

<?php include 'plugins/footer.php';?>
<?php include 'plugins/js/employees_script.php'; ?>