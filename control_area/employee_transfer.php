<?php include 'plugins/navbar.php';?>
<?php include 'plugins/sidebar/control_area_bar.php';?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Employee Transfer</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="employees.php">Home</a></li>
            <li class="breadcrumb-item active">Employee Transfer</li>
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
          <button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#new_employee_transfer"><i class="fas fa-plus-circle"></i> New Employee Transfer</button>
        </div>
        <div class="col-sm-2">
          <a class="btn btn-dark btn-block" href="../template/employees_template.csv?v=<?php echo time(); ?>"><i class="fas fa-download"></i> Download Template</a>
        </div>
        <div class="col-sm-2">
          <button type="button" class="btn btn-warning btn-block btn-file">
            <form id="file_form" enctype="multipart/form-data">
              <span class="mx-0 my-0"><i class="fas fa-upload"></i> Import Employee Transfer </span><input type="file" id="file" name="file" onchange="upload_csv()" accept=".csv">
            </form>
          </button>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <div class="card card-gray-dark card-outline">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-user"></i> Employee Transfer Table</h3>
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
                <div class="col-10">
                  <div class="row mb-2">
                    <div class="col-sm-4">
                      <label>Full Name</label>
                      <input type="text" class="form-control" id="et_full_name_search" placeholder="Search" autocomplete="off" maxlength="255">
                    </div>
                    <div class="col-sm-2">
                      <label>Employee Transfer Type:</label>
                      <select id="et_emp_transfer_type_search" class="form-control" onchange="get_ongoing_employee_transfer()">
                        <option value="">Select Provider</option>
                        <option value="department">Department Transfer</option>
                        <option value="section">Section Transfer</option>
                      </select>
                    </div>
                    <div class="col-sm-2">
                      <label>Provider:</label>
                      <select id="et_provider_search" class="form-control" onchange="get_ongoing_employee_transfer()">
                        <option value="">Select Provider</option>
                        <option value="FAS">FAS</option>
                        <option value="PKIMT">PKIMT</option>
                        <option value="MAXIM">MAXIM</option>
                        <option value="ONE SOURCE">ONE SOURCE</option>
                        <option value="MEGATREND">MEGATREND</option>
                        <option value="ADD EVEN">ADD EVEN</option>
                        <option value="GOLDENHAND">GOLDENHAND</option>
                      </select>
                    </div>
                    <div class="col-sm-4">
                      <label>Line No. From:</label>
                      <select id="et_line_no_from_search" class="form-control" onchange="get_ongoing_employee_transfer()"></select>
                    </div>
                  </div>
                  <div class="row mb-4">
                    <div class="col-sm-4">
                      <label>Employee No:</label>
                      <input type="text" class="form-control" id="et_emp_no_search" placeholder="Search" autocomplete="off" maxlength="255">
                    </div>
                    <div class="col-sm-2">
                      <label>Position:</label>
                      <select id="et_position_search" class="form-control" onchange="get_ongoing_employee_transfer()">
                        <option value="">Select Position</option>
                        <option value="Associate">Associate</option>
                        <option value="Jr. Staff">Jr. Staff</option>
                        <option value="Staff">Staff</option>
                        <option value="Supervisor">Supervisor</option>
                        <option value="Assistant Manager">Assistant Manager</option>
                        <option value="Section Manager">Section Manager</option>
                        <option value="Manager">Manager</option>
                      </select>
                    </div>
                    <div class="col-sm-2">
                      <label>Department To:</label>
                      <select id="et_dept_to_search" class="form-control" onchange="get_ongoing_employee_transfer()"></select>
                    </div>
                    <div class="col-sm-2">
                      <label>Section To:</label>
                      <select id="et_section_to_search" class="form-control" onchange="get_ongoing_employee_transfer()"></select>
                    </div>
                    <div class="col-sm-2">
                      <label>Line No. To:</label>
                      <select id="et_line_no_to_search" class="form-control" onchange="get_ongoing_employee_transfer()"></select>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-2">
                      <button class="btn btn-secondary btn-block"><i class="fas fa-download mr-2"></i>Export</button>
                    </div>
                  </div>
                </div>
                <div class="col-2">
                  <div class="row">
                    <div class="col-12">
                      <input class="ml-4" type="checkbox" name="et_checked_by_search" id="et_checked_by_search" onclick="get_ongoing_employee_transfer()">
                      <label for="et_checked_by_search">Checked By</label>
                      <br>
                      <input class="ml-4" type="checkbox" name="et_approved_by_search" id="et_approved_by_search" onclick="get_ongoing_employee_transfer()">
                      <label for="et_approved_by_search">Approved By</label>
                      <br>
                      <input class="ml-4" type="checkbox" name="et_receiving_noted_by_search" id="et_receiving_noted_by_search" onclick="get_ongoing_employee_transfer()">
                      <label for="et_receiving_noted_by_search">Receiving Noted By</label>
                      <br>
                      <input class="ml-4" type="checkbox" name="et_receiving_acknowledged_by_search" id="et_receiving_acknowledged_by_search" onclick="get_ongoing_employee_transfer()">
                      <label for="et_receiving_acknowledged_by_search">Receiving Acknowledged By</label>
                      <br>
                      <input class="ml-4" type="checkbox" name="et_receiving_approved_by_search" id="et_receiving_approved_by_search" onclick="get_ongoing_employee_transfer()">
                      <label for="et_receiving_approved_by_search">Receiving Approved By</label>
                      <br>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-sm-2">
                  <span id="count_view"></span>
                </div>
              </div>
              <div id="et_res" class="table-responsive" style="max-height: 500px; overflow: auto; display:inline-block;">
                <table id="et_table" class="table table-sm table-head-fixed text-nowrap table-hover">
                  <thead style="text-align: center;">
                    <tr>
                      <th>#</th>
                      <th>Date Effectivity</th>
                      <th>Employee No.</th>
                      <th>Employee Name</th>
                      <th>Provider</th>
                      <th>Position</th>
                      <th>Transfer Type</th>
                      <th>Department From</th>
                      <th>Section From</th>
                      <th>Line No. From</th>
                      <th>Department To</th>
                      <th>Section To</th>
                      <th>Line No. To</th>
                      <th>Issued By</th>
                      <th>Checked By</th>
                      <th>Approved By</th>
                      <th>Receiving Noted By</th>
                      <th>Receiving Acknowledged By</th>
                      <th>Receiving Approved By</th>
                      <th>Reason</th>
                    </tr>
                  </thead>
                  <tbody id="et_data" style="text-align: center;">
                    <tr>
                      <td colspan="20" style="text-align:center;">
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
  </section>
</div>

<?php include 'plugins/footer.php';?>
<?php include 'plugins/js/employee_transfer_script.php'; ?>