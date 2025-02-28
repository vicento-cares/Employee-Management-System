<?php include 'plugins/navbar.php';?>
<?php include 'plugins/sidebar/admin_bar.php';?>

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
          <div class="card card-gray-dark card-outline">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-file-alt"></i> Shuttle Allocation Table</h3>
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
                  <label>Shuttle Allocation Date</label>
                  <input type="date" class="form-control" id="shuttle_allocation_date" disabled>
                </div>
                <div class="col-sm-3">
                  <label>Shift Group</label>
                  <select class="form-control" id="shuttle_allocation_shift_group" onchange="get_shuttle_allocation()" style="width: 100%;">
                    <option selected value="A">Shift A</option>
                    <option value="B">Shift B</option>
                    <option value="ADS">Shift ADS</option>
                  </select>
                </div>
                <div class="col-sm-2">
                  <label>Shift</label><br>
                  <span id="shuttle_allocation_shift"></span>
                </div>
                <div class="col-sm-2">
                  <label>Total Present MP</label><br>
                  <span id="count_view_present"></span>
                </div>
                <div class="col-sm-2">
                  <label>&nbsp;</label>
                  <button type="button" class="btn bg-gray-dark btn-block" onclick="get_shuttle_allocation()"><i class="fas fa-sync"></i> Refresh</button>
                </div>
              </div>
              <div class="row mb-4">
                <div class="col-sm-1">
                  <label>&nbsp;</label>
                  <button type="button" class="btn bg-success btn-block" id="btnOut5" onclick="set_out(5)">OUT 3 😁</button>
                </div>
                <div class="col-sm-1">
                  <label>&nbsp;</label>
                  <button type="button" class="btn bg-info btn-block" id="btnOut6" onclick="set_out(6)">OUT 4 😑</button>
                </div>
                <div class="col-sm-1">
                  <label>&nbsp;</label>
                  <button type="button" class="btn bg-danger btn-block" id="btnOut7" onclick="set_out(7)">OUT 5 😠</button>
                </div>
                <div class="col-sm-1">
                  <label>&nbsp;</label>
                  <button type="button" class="btn bg-purple btn-block" id="btnOut8" onclick="set_out(8)">OUT 6 👻</button>
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
                  <tfoot style="background-color: white; text-align: center; position: sticky; bottom: 0">
                    <tr>
                      <th>Total MP :</th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th id="total_out_5"></th>
                      <th id="total_out_6"></th>
                      <th id="total_out_7"></th>
                      <th id="total_out_8"></th>
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
      <div class="row">
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
              <div class="table-responsive" style="max-height: 500px; overflow: auto; display:inline-block;">
                <table id="shuttleAllocationPerRouteTable" class="table table-sm table-head-fixed text-nowrap table-hover">
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
                  <tfoot style="background-color: white; text-align: center; position: sticky; bottom: 0">
                    <tr>
                      <th>Total MP :</th>
                      <th id="sr_total_out_5"></th>
                      <th id="sr_total_out_6"></th>
                      <th id="sr_total_out_7"></th>
                      <th id="sr_total_out_8"></th>
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
  </section>
</div>

<?php include 'plugins/footer.php';?>
<?php include 'plugins/js/shuttle_allocation_script.php';?>