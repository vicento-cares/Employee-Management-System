<?php include 'plugins/navbar.php';?>
<?php include 'plugins/sidebar/control_area_bar.php';?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Line Shifting Schedule</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="employees.php">Home</a></li>
            <li class="breadcrumb-item active">Line Shifting Schedule</li>
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
              <h3 class="card-title"><i class="fas fa-user"></i> Line Shifting Schedule Table</h3>
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
                  <label>&nbsp;</label>
                  <button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#set_line_shifting"><i class="fas fa-check"></i> Line Shifting</button>
                </div>
                <div class="col-sm-2 offset-sm-2">
                  <label>Shift:</label>
                  <select id="shift_master_search" class="form-control" onchange="load_line_shifting_schedules(1)">
                    <option value="" selected>All</option>
                    <option value="DS">DS</option>
                    <option value="NS">NS</option>
                  </select>
                </div>
                <div class="col-sm-2">
                  <label>Shift Group:</label>
                  <select id="shift_group_master_search" class="form-control" onchange="load_line_shifting_schedules(1)">
                    <option value="" selected>All</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="ADS">ADS</option>
                  </select>
                </div>
                <div class="col-sm-4">
                  <label>Line No:</label>
                  <select id="line_no_master_search" class="form-control" onchange="load_line_shifting_schedules(1)"></select>
                </div>
              </div>
              <div id="list_of_lshiftsched_res" class="table-responsive" style="max-height: 500px; overflow: auto; display:inline-block;">
                <table id="list_of_lshiftsched_table" class="table table-sm table-head-fixed text-nowrap table-hover">
                  <thead style="text-align: center;">
                    <tr>
                      <th>#</th>
                      <th>Schedule Date</th>
                      <th>Department</th>
                      <th>Section</th>
                      <th>Line No.</th>
                      <th>Shift Group</th>
                      <th>Shift</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody id="list_of_lshiftsched" style="text-align: center;">
                    <tr>
                      <td colspan="7" style="text-align:center;">
                        <div class="spinner-border text-dark" role="status">
                          <span class="sr-only">Loading...</span>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="d-flex justify-content-sm-end">
                <div class="dataTables_info" id="list_of_lshiftsched_info" role="status" aria-live="polite"></div>
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
<?php include 'plugins/js/line_shifting_script.php'; ?>