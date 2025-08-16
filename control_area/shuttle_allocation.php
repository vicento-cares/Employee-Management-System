<?php include 'plugins/navbar.php';?>
<?php include 'plugins/sidebar/control_area_bar.php';?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Shuttle Allocation</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="home.php">Home</a></li>
            <li class="breadcrumb-item active">Shuttle Allocation</li>
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
          <div class="card card-gray-dark card-tabs">
            <div class="card-header p-0 border-bottom-0">
              <ul class="nav nav-tabs" id="sa-tab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="sa-1-tab" data-toggle="pill" href="#sa-1" role="tab" aria-controls="sa-1" aria-selected="true">Shuttle Allocation Table</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="sa-2-tab" data-toggle="pill" href="#sa-2" role="tab" aria-controls="sa-2" aria-selected="false">Shuttle Allocation History</a>
                </li>
              </ul>
            </div>
            <div class="card-body">
              <div class="tab-content" id="sa-tabContent">
                <div class="tab-pane fade show active" id="sa-1" role="tabpanel" aria-labelledby="sa-1-tab">
                  <div class="row mb-4">
                    <div class="col-sm-2">
                      <label>Shuttle Allocation Date</label>
                      <input type="date" class="form-control" id="shuttle_allocation_date" onchange="get_shuttle_allocation()" disabled>
                    </div>
                    <div class="col-sm-2">
                      <label>Shift Group</label>
                      <select class="form-control" id="shuttle_allocation_shift_group" onchange="get_shuttle_allocation()" style="width: 100%;">
                        <option selected value="">ALL</option>
                        <option value="A">Shift A</option>
                        <option value="B">Shift B</option>
                        <option value="ADS">Shift ADS</option>
                      </select>
                    </div>
                    <div class="col-sm-4">
                      <label>Line No:</label>
                      <select id="shuttle_allocation_line_no" class="form-control" onchange="get_shuttle_allocation()"></select>
                    </div>
                    <div class="col-sm-2">
                      <label>Shift</label><br>
                      <span id="shuttle_allocation_shift"></span>
                    </div>
                    <div class="col-sm-2">
                      <label>Total Present MP</label><br>
                      <span id="count_view_present"></span>
                    </div>
                  </div>
                  <div class="row mb-4">
                    <div class="col-sm-1">
                      <button type="button" class="btn bg-success btn-block" id="btnOut5" onclick="verify_set_out(5)">OUT 3 😁</button>
                    </div>
                    <div class="col-sm-1">
                      <button type="button" class="btn bg-info btn-block" id="btnOut6" onclick="verify_set_out(6)">OUT 4 😑</button>
                    </div>
                    <div class="col-sm-1">
                      <button type="button" class="btn bg-danger btn-block" id="btnOut7" onclick="verify_set_out(7)">OUT 5 😠</button>
                    </div>
                    <div class="col-sm-1">
                      <button type="button" class="btn bg-purple btn-block" id="btnOut8" onclick="verify_set_out(8)">OUT 6 👻</button>
                    </div>
                    <div class="col-sm-4 m-0 p-2 callout callout-warning">
                      <h5>Note:</h5>
                      <i>Set Shuttle Allocation Time Range <b>DS (6 AM - 1:29:59 AM) & NS (6 PM - 1:29:59 PM)</b>. <br>Please set shuttle allocation within time range!</i>
                    </div>
                    <div class="col-sm-2">
                      <button type="button" class="btn bg-gray-dark btn-block" onclick="get_shuttle_allocation()"><i class="fas fa-sync"></i> Refresh</button>
                    </div>
                    <div class="col-sm-2">
                      <button type="button" class="btn bg-gray-dark btn-block" onclick="export_shuttle_allocation('shuttleAllocationTable')"><i class="fas fa-download"></i> Export</button>
                    </div>
                  </div>
                  <div class="table-responsive" style="max-height: 500px; overflow: auto; display:inline-block;">
                    <table id="shuttleAllocationTable" class="table table-sm table-head-fixed text-nowrap table-hover">
                      <thead style="text-align: center;">
                        <tr>
                          <th>
                            <input type="checkbox" name="" id="check_all_present"  onclick="select_all_func_present()">
                          </th>
                          <th>#</th>
                          <th>Provider</th>
                          <th>Employee No.</th>
                          <th>Full Name</th>
                          <th>Department</th>
                          <th>Section</th>
                          <th>Line No.</th>
                          <th>Shuttle Route</th>
                          <th class="text-success">OUT 3</th>
                          <th class="text-info">OUT 4</th>
                          <th class="text-danger">OUT 5</th>
                          <th class="text-purple">OUT 6</th>
                        </tr>
                      </thead>
                      <tbody id="shuttleAllocationData" style="text-align: center;">
                        <tr>
                          <td colspan="13" style="text-align:center;">
                            <div class="spinner-border text-dark" role="status">
                              <span class="sr-only">Loading...</span>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="row mt-2">
                    <div class="col-sm-12">
                      <div class="card card-gray-dark card-outline">
                        <div class="card-header">
                          <h3 class="card-title"><i class="fas fa-file-alt"></i> Shuttle Allocation Per Shuttle Route Table</h3>
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
                            <div class="col-sm-2 offset-sm-10">
                              <button type="button" class="btn bg-gray-dark btn-block" onclick="export_shuttle_allocation('shuttleAllocationPerRouteTable')"><i class="fas fa-download"></i> Export</button>
                            </div>
                          </div>
                          <div class="table-responsive" style="max-height: 500px; overflow: auto; display:inline-block;">
                            <table id="shuttleAllocationPerRouteTable" class="table table-sm table-head-fixed text-nowrap">
                              <thead style="text-align: center;">
                                <tr>
                                  <th>Shuttle Route</th>
                                  <th class="text-success">OUT 3</th>
                                  <th class="text-info">OUT 4</th>
                                  <th class="text-danger">OUT 5</th>
                                  <th class="text-purple">OUT 6</th>
                                </tr>
                              </thead>
                              <tbody id="shuttleAllocationPerRouteData" style="text-align: center;">
                                <tr>
                                  <td colspan="5" style="text-align:center;">
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
                  </div>
                </div>
                <div class="tab-pane fade" id="sa-2" role="tabpanel" aria-labelledby="sa-2-tab">
                  <div class="row mb-4">
                    <div class="col-sm-2">
                      <label>Shuttle Allocation Date</label>
                      <input type="date" class="form-control" id="sa_date_search">
                    </div>
                    <div class="col-sm-1">
                      <label>Shift Group</label>
                      <select class="form-control" id="sa_shift_group_search" style="width: 100%;" required>
                        <option selected value="">ALL</option>
                        <option value="A">Shift A</option>
                        <option value="B">Shift B</option>
                        <option value="ADS">Shift ADS</option>
                      </select>
                    </div>
                    <div class="col-sm-1">
                      <label>Shift</label>
                      <select class="form-control" id="sa_shift_search" style="width: 100%;" required>
                        <option selected value="">ALL</option>
                        <option value="DS">DS</option>
                        <option value="NS">NS</option>
                      </select>
                    </div>
                    <div class="col-sm-4">
                      <label>Line No:</label>
                      <select id="sa_line_no_search" class="form-control" onchange="get_shuttle_allocation()"></select>
                    </div>
                    <div class="col-sm-2">
                      <label>&nbsp;</label>
                      <button type="button" class="btn bg-gray-dark btn-block" onclick="get_shuttle_allocation_history()"><i class="fas fa-search"></i> Search</button>
                    </div>
                    <div class="col-sm-2">
                      <label>&nbsp;</label>
                      <button type="button" class="btn bg-gray-dark btn-block" onclick="export_shuttle_allocation('shuttleAllocationHistoryTable')"><i class="fas fa-download"></i> Export</button>
                    </div>
                  </div>
                  <div class="table-responsive" style="max-height: 500px; overflow: auto; display:inline-block;">
                    <table id="shuttleAllocationHistoryTable" class="table table-sm table-head-fixed text-nowrap">
                      <thead style="text-align: center;">
                        <tr>
                          <th>Shuttle Allocation Date</th>
                          <th>Provider</th>
                          <th>Employee ID</th>
                          <th>Full Name</th>
                          <th>Department</th>
                          <th>Section</th>
                          <th>Line No</th>
                          <th>Shuttle Route</th>
                          <th class="text-success">OUT 3</th>
                          <th class="text-info">OUT 4</th>
                          <th class="text-danger">OUT 5</th>
                          <th class="text-purple">OUT 6</th>
                        </tr>
                      </thead>
                      <tbody id="shuttleAllocationHistoryData" style="text-align: center;"></tbody>
                    </table>
                  </div>
                  <div class="row mt-2">
                    <div class="col-sm-12">
                      <div class="card card-gray-dark card-outline">
                        <div class="card-header">
                          <h3 class="card-title"><i class="fas fa-file-alt"></i> Shuttle Allocation Per Shuttle Route Table</h3>
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
                            <div class="col-sm-2 offset-sm-10">
                              <button type="button" class="btn bg-gray-dark btn-block" onclick="export_shuttle_allocation('shuttleAllocationHistoryPerRouteTable')"><i class="fas fa-download"></i> Export</button>
                            </div>
                          </div>
                          <div class="table-responsive" style="max-height: 500px; overflow: auto; display:inline-block;">
                            <table id="shuttleAllocationHistoryPerRouteTable" class="table table-sm table-head-fixed text-nowrap">
                              <thead style="text-align: center;">
                                <tr>
                                  <th>Shuttle Route</th>
                                  <th class="text-success">OUT 3</th>
                                  <th class="text-info">OUT 4</th>
                                  <th class="text-danger">OUT 5</th>
                                  <th class="text-purple">OUT 6</th>
                                </tr>
                              </thead>
                              <tbody id="shuttleAllocationHistoryPerRouteData" style="text-align: center;"></tbody>
                            </table>
                          </div>
                        </div>
                        <!-- /.card-body -->
                      </div>
                      <!-- /.card -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
  </section>
</div>

<?php include 'plugins/footer.php';?>
<?php include 'plugins/js/shuttle_allocation_script.php';?>