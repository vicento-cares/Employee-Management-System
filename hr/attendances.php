<?php include 'plugins/navbar.php';?>
<?php include 'plugins/sidebar/hr_bar.php';?>

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
      <div class="row mb-4">
        <div class="col-sm-3">
          <a class="btn btn-dark btn-block" onclick="export_absences_template()"><i class="fas fa-download"></i> Download Absences Report Data Template</a>
        </div>
        <div class="col-sm-3">
          <button type="button" class="btn btn-warning btn-block btn-file">
            <form id="file_form" enctype="multipart/form-data">
              <span class="mx-0 my-0"><i class="fas fa-upload"></i> Import Absences Report Data</span><input type="file" id="file" name="file" onchange="upload_csv()" accept=".csv">
            </form>
          </button>
        </div>
        <div class="col-sm-3">
          <a class="btn btn-dark btn-block" onclick="export_absences_reasons()"><i class="fas fa-download"></i> Download Absences Reasons List</a>
        </div>
      </div>
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
                <div class="col-sm-2">
                  <label>Attendance Date</label>
                  <input type="date" class="form-control" id="attendance_date_search">
                </div>
                <div class="col-sm-2">
                  <label>Shift Group</label>
                  <select class="form-control" id="shift_group_search" style="width: 100%;" required>
                    <option selected value="">All Shift Groups</option>
                    <option value="A">Shift A</option>
                    <option value="B">Shift B</option>
                    <option value="ADS">Shift ADS</option>
                  </select>
                </div>
                <div class="col-sm-2">
                  <label>Department</label>
                  <select id="dept_search" class="form-control">
                    <option value="">Select Department</option>
                  </select>
                </div>
                <div class="col-sm-2">
                  <label>Section</label>
                  <input type="text" class="form-control" id="section_search" placeholder="Search" autocomplete="off" maxlength="255">
                </div>
                <div class="col-sm-2">
                  <label>Line No.</label>
                  <input type="text" class="form-control" id="line_no_search" placeholder="Search" autocomplete="off" maxlength="255">
                </div>
                <div class="col-sm-2">
                  <label>&nbsp;</label>
                  <button type="button" class="btn bg-gray-dark btn-block" onclick="get_attendance_list(1)"><i class="fas fa-search"></i> Search</button>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-sm-3">
                  <label>Attendance Status</label>
                  <select id="attendance_status_search" class="form-control">
                    <option selected value="0">All Attendances</option>
                    <option value="1">All Present</option>
                    <option value="2">All Absent</option>
                    <option value="3">All Absent with Absences Report</option>
                    <option value="4">All Absent without Absences Report</option>
                  </select>
                </div>
                <div class="col-sm-2">
                  <label>Present MP</label><br>
                  <span id="count_view_present"></span>
                </div>
                <div class="col-sm-1">
                  <label>Absent MP</label><br>
                  <span id="count_view_absent"></span>
                </div>
                <div class="col-sm-4">
                  <label>&nbsp;</label>
                  <button type="button" class="btn bg-danger btn-block" onclick="export_absences()"><i class="fas fa-download"></i> Export Absences Report</button>
                </div>
                <div class="col-sm-2">
                  <label>&nbsp;</label>
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
                        <div class="col-sm-6 col-lg-6 p-1 bg-lightpink"><center>Absent</center></div>
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
                  <tbody id="attendanceData" style="text-align: center;">
                    <tr>
                      <td colspan="16" style="text-align:center;">
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