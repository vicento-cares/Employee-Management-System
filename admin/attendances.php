<?php include 'plugins/navbar.php';?>
<?php include 'plugins/sidebar/attendances_bar.php';?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Attendances</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="home.php">Home</a></li>
            <li class="breadcrumb-item active">Attendances</li>
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
              <h3 class="card-title"><i class="fas fa-tasks"></i> Attendance Table</h3>
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
              <div class="row mb-2">
                <div class="col-sm-3">
                  <label>Attendance Date</label>
                  <input type="date" class="form-control" id="attendance_date_search" onchange="get_attendance_list(1)">
                </div>
                <div class="col-sm-3">
                  <label>Shift</label>
                  <select class="form-control" id="shift_search" style="width: 100%;" onchange="get_attendance_list(1)" required>
                    <option selected value="DS">Day Shift - (DS)</option>
                    <option value="NS">Night Shift - (NS)</option>
                  </select>
                </div>
                <div class="col-sm-2">
                  <label>Present MP</label><br>
                  <span id="count_view_present"></span>
                </div>
                <div class="col-sm-2">
                  <label>Absent MP</label><br>
                  <span id="count_view_absent"></span>
                </div>
                <div class="col-sm-2">
                  <label>&nbsp;</label>
                  <button type="button" class="btn bg-gray-dark btn-block" onclick="get_attendance_list(1)"><i class="fas fa-search"></i> Search</button>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-sm-4 offset-sm-6">
                  <button type="button" class="btn bg-danger btn-block" onclick="export_absences()"><i class="fas fa-download"></i> Export Absences Report</button>
                </div>
                <div class="col-sm-2">
                  <button type="button" class="btn bg-success btn-block" onclick="export_attendances()"><i class="fas fa-download"></i> Export Attendance List</button>
                </div>
              </div>
              <div id="accordion_attendance_legend">
                <div class="card shadow">
                  <div class="card-header">
                    <h4 class="card-title w-100">
                      <a class="d-block w-100 text-dark" data-toggle="collapse" href="#collapseOneAttendanceLegend">
                        Attendance History Legend
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
                      <th>Day</th>
                      <th>Shift</th>
                      <th>Provider</th>
                      <th>Employee No.</th>
                      <th>Full Name</th>
                      <th>Department</th>
                      <th>Section</th>
                      <th>Line No.</th>
                      <th>Type of Absent</th>
                      <th>Reason</th>
                    </tr>
                  </thead>
                  <tbody id="attendanceData" style="text-align: center;">
                    <tr>
                      <td colspan="11" style="text-align:center;">
                        <div class="spinner-border text-dark" role="status">
                          <span class="sr-only">Loading...</span>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="d-flex justify-content-sm-end">
                <div class="dataTables_info" id="attendanceTableInfo" role="status" aria-live="polite"></div>
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
<?php include 'plugins/js/attendances_script.php';?>