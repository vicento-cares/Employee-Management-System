<?php include 'plugins/navbar.php';?>
<?php include 'plugins/sidebar/hr_bar.php';?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Individual Attendances</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="home.php">Home</a></li>
            <li class="breadcrumb-item active">Individual Attendances</li>
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
              <h3 class="card-title"><i class="fas fa-tasks"></i> Individual Attendance Table</h3>
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
              <form id="individual_attendances_form">
                <div class="row mb-2">
                  <div class="col-sm-2">
                    <label>Attendance Day From</label>
                    <input type="date" class="form-control" id="attendance_day_from_search" name="day_from" required>
                  </div>
                  <div class="col-sm-2">
                    <label>Attendance Day To</label>
                    <input type="date" class="form-control" id="attendance_day_to_search" name="day_to" required>
                  </div>
                  <div class="col-sm-4">
                    <label>Employee No.</label>
                    <input type="text" class="form-control" id="emp_no_search" name="emp_no" autocomplete="off" maxlength="255" required>
                  </div>
                  <div class="col-sm-2">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn bg-gray-dark btn-block"><i class="fas fa-search"></i> Search</button>
                  </div>
                  <div class="col-sm-2">
                    <label>&nbsp;</label>
                    <button type="button" class="btn bg-success btn-block" onclick="export_individual_attendances('attendanceTable')"><i class="fas fa-download"></i> Export Attendance List</button>
                  </div>
                </div>
              </form>
              <div class="row mb-2">
                <div class="col-sm-2">
                  <span id="count_view"></span>
                </div>
              </div>
              <div id="accordion_attendance_legend">
                <div class="card shadow">
                  <div class="card-header">
                    <h4 class="card-title w-100">
                      <a class="d-block w-100 text-dark" data-toggle="collapse" href="#collapseOneAttendanceLegend">
                        Individual Attendance History Legend
                      </a>
                    </h4>
                  </div>
                  <div id="collapseOneAttendanceLegend" class="collapse" data-parent="#accordion_attendance_legend">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-sm-6 col-lg-6 p-1 bg-success"><center>Present</center></div>
                        <div class="col-sm-6 col-lg-6 p-1 bg-danger"><center>Absent</center></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div id="attendanceTableRes" class="table-responsive" style="max-height: 500px; overflow: auto; display:inline-block;">
                <table id="attendanceTable" class="table table-sm table-head-fixed table-foot-fixed text-nowrap table-hover">
                  <thead style="text-align: center;">
                    <tr>
                      <th>#</th>
                      <th>Day Name</th>
                      <th>Day</th>
                      <th>Shift</th>
                      <th>Employee No.</th>
                      <th>Time In 1</th>
                      <th>Time In 2</th>
                      <th>Time Out</th>
                      <th>IP</th>
                    </tr>
                  </thead>
                  <tbody id="attendanceData" style="text-align: center;"></tbody>
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
<?php include 'plugins/js/individual_attendances_script.php';?>