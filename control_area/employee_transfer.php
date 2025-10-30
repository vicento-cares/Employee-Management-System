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
          <div class="card card-gray-dark card-tabs">
            <div class="card-header p-0 border-bottom-0">
              <ul class="nav nav-tabs" id="et-tab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="et-1-tab" data-toggle="pill" href="#et-1" role="tab" aria-controls="et-1" aria-selected="true">Outgoing Employee Transfer Table</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="et-2-tab" data-toggle="pill" href="#et-2" role="tab" aria-controls="et-2" aria-selected="false">Employee Transfer History Table</a>
                </li>
              </ul>
            </div>
            <div class="card-body">
              <div class="tab-content" id="et-tabContent">
                <!-- Shuttle Allocation -->
                <div class="tab-pane fade show active" id="et-1" role="tabpanel" aria-labelledby="et-1-tab">
                  <div class="row">
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
                  <div class="row">
                    <div class="col-2 offset-10">
                      <button class="btn btn-secondary btn-block"><i class="fas fa-download mr-2"></i>Export</button>
                    </div>
                  </div>
                  <div class="row mb-2">
                    <div class="form-group mb-0 px-2">
                      <label><b>Employee Transfer Legend</b></label>
                    </div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-sm-6 col-lg-4 p-1 border bg-secondary"><center>Can Edit</center></div>
                    <div class="col-sm-6 col-lg-4 p-1 border bg-danger"><center>Date Effectivity Overdue</center></div>
                    <div class="col-sm-6 col-lg-4 p-1 border"><center>Ongoing</center></div>
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
                <!-- Shuttle Allocation History -->
                <div class="tab-pane fade" id="et-2" role="tabpanel" aria-labelledby="et-2-tab">
                  <div class="row">
                    <div class="col-10">
                      <div class="row">
                        <div class="col-sm-4">
                          <label>Full Name</label>
                          <input type="text" class="form-control" id="eth_full_name_search" placeholder="Search" autocomplete="off" maxlength="255">
                        </div>
                        <div class="col-sm-2">
                          <label>Employee Transfer Type:</label>
                          <select id="eth_emp_transfer_type_search" class="form-control">
                            <option value="">Select Provider</option>
                            <option value="department">Department Transfer</option>
                            <option value="section">Section Transfer</option>
                          </select>
                        </div>
                        <div class="col-sm-2">
                          <label>Provider:</label>
                          <select id="eth_provider_search" class="form-control">
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
                          <select id="eth_line_no_from_search" class="form-control"></select>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-4">
                          <label>Employee No:</label>
                          <input type="text" class="form-control" id="eth_emp_no_search" placeholder="Search" autocomplete="off" maxlength="255">
                        </div>
                        <div class="col-sm-2">
                          <label>Position:</label>
                          <select id="eth_position_search" class="form-control">
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
                          <select id="eth_dept_to_search" class="form-control"></select>
                        </div>
                        <div class="col-sm-2">
                          <label>Section To:</label>
                          <select id="eth_section_to_search" class="form-control"></select>
                        </div>
                        <div class="col-sm-2">
                          <label>Line No. To:</label>
                          <select id="eth_line_no_to_search" class="form-control"></select>
                        </div>
                      </div>
                    </div>
                    <div class="col-2">
                      <div class="row mb-2">
                        <div class="col-12">
                          <input class="ml-4" type="checkbox" name="eth_checked_by_search" id="eth_checked_by_search">
                          <label for="eth_checked_by_search">Checked By</label>
                          <br>
                          <input class="ml-4" type="checkbox" name="eth_approved_by_search" id="eth_approved_by_search">
                          <label for="eth_approved_by_search">Approved By</label>
                          <br>
                          <input class="ml-4" type="checkbox" name="eth_receiving_noted_by_search" id="eth_receiving_noted_by_search">
                          <label for="eth_receiving_noted_by_search">Receiving Noted By</label>
                          <br>
                          <input class="ml-4" type="checkbox" name="eth_receiving_acknowledged_by_search" id="eth_receiving_acknowledged_by_search">
                          <label for="eth_receiving_acknowledged_by_search">Receiving Acknowledged By</label>
                          <br>
                          <input class="ml-4" type="checkbox" name="eth_receiving_approved_by_search" id="eth_receiving_approved_by_search">
                          <label for="eth_receiving_approved_by_search">Receiving Approved By</label>
                          <br>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-2 offset-8">
                      <button class="btn btn-success btn-block" onclick="get_employee_transfer_history()"><i class="fas fa-search mr-2"></i>Search</button>
                    </div>
                    <div class="col-2">
                      <button class="btn btn-secondary btn-block"><i class="fas fa-download mr-2"></i>Export</button>
                    </div>
                  </div>
                  <div class="row mb-2">
                    <div class="form-group mb-0 px-2">
                      <label><b>Employee Transfer Status Legend</b></label>
                    </div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-sm-6 col-lg-6 p-1 border bg-success"><center>Approved</center></div>
                    <div class="col-sm-6 col-lg-6 p-1 border bg-danger"><center>Disapproved</center></div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-sm-2">
                      <span id="count_view2"></span>
                    </div>
                  </div>
                  <div id="eth_res" class="table-responsive" style="max-height: 500px; overflow: auto; display:inline-block;">
                    <table id="eth_table" class="table table-sm table-head-fixed text-nowrap table-hover">
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
                          <th>HR Acknowledged By</th>
                          <th>Status</th>
                          <th>Reason</th>
                        </tr>
                      </thead>
                      <tbody id="eth_data" style="text-align: center;"></tbody>
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
<?php include 'plugins/js/employee_transfer_script.php'; ?>