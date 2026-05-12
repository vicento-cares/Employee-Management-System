<?php include 'plugins/navbar.php';?>
<?php include 'plugins/sidebar/admin_bar.php';?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Absences</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="home.php">Home</a></li>
            <li class="breadcrumb-item active">Absences</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-12">
          <div class="card card-gray-dark card-outline">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-tasks"></i> Absences Table</h3>
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
                  <label>Attendance Date</label>
                  <input type="date" class="form-control" id="attendance_date_search" onchange="get_absences_list()">
                </div>
                <div class="col-sm-2">
                  <label>Shift Group</label>
                  <select class="form-control" id="shift_group_search" style="width: 100%;" onchange="get_absences_list()" required>
                    <option selected value="A">Shift A</option>
                    <option value="B">Shift B</option>
                    <option value="ADS">Shift ADS</option>
                  </select>
                </div>
                <div class="col-sm-2">
                  <label>Absent MP</label><br>
                  <span id="count_view_absent"></span>
                </div>
                <div class="col-sm-2 offset-sm-2">
                  <label>&nbsp;</label>
                  <button type="button" class="btn bg-gray-dark btn-block" onclick="get_absences_list()"><i class="fas fa-search"></i> Search</button>
                </div>
                <div class="col-sm-2">
                  <label>&nbsp;</label>
                  <button type="button" class="btn bg-danger btn-block" onclick="export_absences()"><i class="fas fa-download"></i> Absences Report</button>
                </div>
              </div>
              <div id="absencesTableRes" class="table-responsive" style="max-height: 500px; overflow: auto; display:inline-block;">
                <table id="absencesTable" class="table table-sm table-head-fixed table-foot-fixed text-nowrap table-hover">
                  <thead style="text-align: center;">
                    <tr>
                      <th>#</th>
                      <th>Delete</th>
                      <th>Select Reason</th>
                      <th>Select Type of Absent</th>
                      <th>Type of Absent</th>
                      <th>Reason</th>
                      <th>Picture</th>
                      <th>Employee No.</th>
                      <th>Full Name</th>
                      <th>Day</th>
                      <th>Shift</th>
                      <th>Shift Group</th>
                      <th>Provider</th>
                      <th>Department</th>
                      <th>Section</th>
                      <th>Line No.</th>
                    </tr>
                  </thead>
                  <tbody id="absencesData" style="text-align: center;"></tbody>
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

<?php include 'plugins/footer.php';?>
<?php include 'plugins/js/absences_script.php';?>