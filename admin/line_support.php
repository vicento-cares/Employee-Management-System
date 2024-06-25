<?php include 'plugins/line_support_navbar.php';?>
<?php include 'plugins/sidebar/admin_bar.php';?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Line Support</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="home.php">Home</a></li>
            <li class="breadcrumb-item active">Line Support</li>
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
        <div class="col-sm-2">
          <button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#set_line_support"><i class="fas fa-plus-circle"></i> Set Line Support</button>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <div class="card card-gray-dark card-tabs">
            <div class="card-header p-0 border-bottom-0">
              <ul class="nav nav-tabs" id="line-support-tab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="line-support-1-tab" data-toggle="pill" href="#line-support-1" role="tab" aria-controls="line-support-1" aria-selected="true">Pending & Recent Line Support Acceptance History</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="line-support-2-tab" data-toggle="pill" href="#line-support-2" role="tab" aria-controls="line-support-2" aria-selected="false">Line Support Acceptance History</a>
                </li>
              </ul>
            </div>
            <div class="card-body">
              <div class="tab-content" id="line-support-tabContent">
                <div class="tab-pane fade show active" id="line-support-1" role="tabpanel" aria-labelledby="line-support-1-tab">
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="card card-gray-dark card-outline">
                        <div class="card-header">
                          <h3 class="card-title"><i class="fas fa-users"></i> Pending Line Support Acceptance Table</h3>
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
                          <div id="accordion_pending_line_support_legend">
                            <div class="card shadow">
                              <div class="card-header">
                                <h4 class="card-title w-100">
                                  <a class="d-block w-100 text-dark" data-toggle="collapse" href="#collapseOnePendingLineSupportLegend">
                                    Pending Line Support Acceptance Legend
                                  </a>
                                </h4>
                              </div>
                              <div id="collapseOnePendingLineSupportLegend" class="collapse" data-parent="#accordion_pending_line_support_legend">
                                <div class="card-body">
                                  <div class="row">
                                    <div class="col-sm-6 col-lg-6 p-1 bg-orange"><center>Pending</center></div>
                                    <div class="col-sm-6 col-lg-6 p-1 bg-warning"><center>Need Acceptance</center></div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="row mb-2">
                            <div class="col-sm-2">
                              <span id="count_view"></span>
                            </div>
                          </div>
                          <div class="table-responsive" style="max-height: 500px; overflow: auto; display:inline-block;">
                            <table id="pendingLineSupportTable" class="table table-sm table-head-fixed text-nowrap table-hover">
                              <thead style="text-align: center;">
                                <tr>
                                  <th>#</th>
                                  <th>Employee No.</th>
                                  <th>Full Name</th>
                                  <th>Department</th>
                                  <th>Process</th>
                                  <th>Day</th>
                                  <th>Shift</th>
                                  <th>Shift Group</th>
                                  <th>From Line No.</th>
                                  <th>Supported Line No.</th>
                                  <th>Set By</th>
                                  <th>Date Updated</th>
                                </tr>
                              </thead>
                              <tbody id="pendingLineSupportData" style="text-align: center;">
                                <tr>
                                  <td colspan="12" style="text-align:center;">
                                    <div class="spinner-border text-dark" role="status">
                                      <span class="sr-only">Loading...</span>
                                    </div>
                                  </td>
                                </tr>
                              </tbody>
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
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="card card-gray-dark card-outline">
                        <div class="card-header">
                          <h3 class="card-title"><i class="fas fa-history"></i> Recent Line Support Acceptance History Table</h3>
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
                          <div id="accordion_line_support_history_legend">
                            <div class="card shadow">
                              <div class="card-header">
                                <h4 class="card-title w-100">
                                  <a class="d-block w-100 text-dark" data-toggle="collapse" href="#collapseOneLineSupportHistoryLegend">
                                    Line Support Acceptance History Legend
                                  </a>
                                </h4>
                              </div>
                              <div id="collapseOneLineSupportHistoryLegend" class="collapse" data-parent="#accordion_line_support_history_legend">
                                <div class="card-body">
                                  <div class="row">
                                    <div class="col-sm-3 col-lg-3 p-1 bg-success"><center>Accepted Support</center></div>
                                    <div class="col-sm-3 col-lg-3 p-1 bg-teal"><center>Accepted Support Set</center></div>
                                    <div class="col-sm-3 col-lg-3 p-1 bg-danger"><center>Rejected Support</center></div>
                                    <div class="col-sm-3 col-lg-3 p-1 bg-purple"><center>Rejected Support Set</center></div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="row mb-2">
                            <div class="col-sm-2">
                              <span id="count_view2"></span>
                            </div>
                          </div>
                          <div class="table-responsive" style="max-height: 500px; overflow: auto; display:inline-block;">
                            <table id="recentLineSupportHistoryTable" class="table table-sm table-head-fixed text-nowrap table-hover">
                              <thead style="text-align: center;">
                                <tr>
                                  <th>#</th>
                                  <th>Employee No.</th>
                                  <th>Full Name</th>
                                  <th>Department</th>
                                  <th>Process</th>
                                  <th>Day</th>
                                  <th>Shift</th>
                                  <th>Shift Group</th>
                                  <th>From Line No.</th>
                                  <th>Supported Line No.</th>
                                  <th>Set By</th>
                                  <th>Date Updated</th>
                                </tr>
                              </thead>
                              <tbody id="recentLineSupportHistoryData" style="text-align: center;">
                                <tr>
                                  <td colspan="12" style="text-align:center;">
                                    <div class="spinner-border text-dark" role="status">
                                      <span class="sr-only">Loading...</span>
                                    </div>
                                  </td>
                                </tr>
                              </tbody>
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
                <div class="tab-pane fade" id="line-support-2" role="tabpanel" aria-labelledby="line-support-2-tab">
                  <div class="row mb-2">
                    <div class="col-sm-3">
                      <label>Day</label>
                      <input type="date" class="form-control" id="history_day_search">
                    </div>
                    <div class="col-sm-3">
                      <label>Shift</label>
                      <select class="form-control" id="history_shift_search" style="width: 100%;" required>
                        <option selected value="DS">Day Shift - (DS)</option>
                        <option value="NS">Night Shift - (NS)</option>
                      </select>
                    </div>
                    <div class="col-sm-2">
                      <label>Employee No.</label>
                      <input type="text" class="form-control" id="history_emp_no_search" placeholder="Search" autocomplete="off" maxlength="255">
                    </div>
                    <div class="col-sm-4">
                      <label>Full Name</label>
                      <input type="text" class="form-control" id="history_full_name_search" placeholder="Search" autocomplete="off" maxlength="255">
                    </div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-sm-3">
                      <label>From Line No.</label>
                      <select class="form-control" id="history_line_no_from_search" style="width: 100%;" required>
                        <option disabled selected value="">Select Line</option>
                      </select>
                    </div>
                    <div class="col-sm-3">
                      <label>Supported Line No.</label>
                      <select class="form-control" id="history_line_no_to_search" style="width: 100%;" required>
                        <option disabled selected value="">Select Line</option>
                      </select>
                    </div>
                    <div class="col-sm-2">
                      <label>Status</label>
                      <select class="form-control" id="history_status_search" style="width: 100%;" required>
                        <option selected value="">All</option>
                        <option value="1">Accepted Support</option>
                        <option value="2">Accepted Support Set</option>
                        <option value="3">Rejected Support</option>
                        <option value="4">Rejected Support Set</option>
                      </select>
                    </div>
                    <div class="col-sm-2">
                      <label>&nbsp;</label>
                      <button type="button" class="btn bg-gray btn-block" onclick="export_line_support_history('lineSupportHistoryTable')"><i class="fas fa-download"></i> Export</button>
                    </div>
                    <div class="col-sm-2">
                      <label>&nbsp;</label>
                      <button type="button" class="btn bg-gray-dark btn-block" onclick="get_line_support_history()"><i class="fas fa-search"></i> Search</button>
                    </div>
                  </div>
                  <div id="accordion_line_support_history2_legend">
                    <div class="card shadow">
                      <div class="card-header">
                        <h4 class="card-title w-100">
                          <a class="d-block w-100 text-dark" data-toggle="collapse" href="#collapseOneLineSupportHistory2Legend">
                            Line Support Acceptance History Legend
                          </a>
                        </h4>
                      </div>
                      <div id="collapseOneLineSupportHistory2Legend" class="collapse" data-parent="#accordion_line_support_history2_legend">
                        <div class="card-body">
                          <div class="row">
                            <div class="col-sm-3 col-lg-3 p-1 bg-success"><center>Accepted Support</center></div>
                            <div class="col-sm-3 col-lg-3 p-1 bg-teal"><center>Accepted Support Set</center></div>
                            <div class="col-sm-3 col-lg-3 p-1 bg-danger"><center>Rejected Support</center></div>
                            <div class="col-sm-3 col-lg-3 p-1 bg-purple"><center>Rejected Support Set</center></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-sm-2">
                      <span id="count_view3"></span>
                    </div>
                  </div>
                  <div class="table-responsive" style="max-height: 500px; overflow: auto; display:inline-block;">
                    <table id="lineSupportHistoryTable" class="table table-sm table-head-fixed text-nowrap table-hover">
                      <thead style="text-align: center;">
                        <tr>
                          <th>#</th>
                          <th>Employee No.</th>
                          <th>Full Name</th>
                          <th>Department</th>
                          <th>Process</th>
                          <th>Day</th>
                          <th>Shift</th>
                          <th>Shift Group</th>
                          <th>From Line No.</th>
                          <th>Supported Line No.</th>
                          <th>Set By</th>
                          <th>Status</th>
                          <th>Date Updated</th>
                        </tr>
                      </thead>
                      <tbody id="lineSupportHistoryData" style="text-align: center;"></tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<?php include 'plugins/footer.php';?>
<?php include 'plugins/js/line_support_script.php';?>