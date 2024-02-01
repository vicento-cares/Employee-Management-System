<div class="modal fade bd-example-modal-xl" id="update_employee" tabindex="-1"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">
          <b>Update Employee Details</b>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-4">
            <input type="hidden" id="id_employee_master_update" class="form-control">
            <label>Employee No:</label>
            <input type="text" id="emp_no_master_update" class="form-control" autocomplete="off" style="height:45px; border: 1px solid black; font-size: 25px;" disabled>
          </div>
          <div class="col-6">
            <label>Full Name:</label>
            <input type="text" id="full_name_master_update" class="form-control" autocomplete="off" style="height:45px; border: 1px solid black; font-size: 25px;">
          </div>
          <div class="col-2">
            <label>Gender:</label>
            <select id="gender_master_update" class="form-control" style="height:45px; border: 1px solid black;">
              <option value="">Select Gender</option>
              <option value="M">Male</option>
              <option value="F">Female</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-4">
            <label>Department:</label>
            <select id="dept_master_update" class="form-control" onchange="get_laf_approver_dropdowns(2)" style="height:45px; border: 1px solid black;">
              <option value="">Select Department</option>
            </select>
          </div>
          <div class="col-4">
            <label>Section:</label>
            <select id="section_master_update" class="form-control" onchange="fetch_line_dropdown(2)" style="height:45px; border: 1px solid black;">
              <option value="">Select Section</option>
            </select>
          </div>
          <div class="col-4">
            <label>Line No:</label>
            <select id="line_no_master_update" class="form-control" onchange="get_laf_approver_dropdowns(2)" style="height:45px; border: 1px solid black;">
              <option value="">Select Line No.</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <label>Position:</label>
            <select id="position_master_update" class="form-control" style="height:45px; border: 1px solid black;">
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
          <div class="col-6">
            <label>Date Hired:</label>
            <input type="date" id="date_hired_master_update" class="form-control" style="height:45px; border: 1px solid black;">
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <label>Provider:</label>
            <select id="provider_master_update" class="form-control" style="height:45px; border: 1px solid black;">
              <option value="">Select Provider</option>
              <option value="FAS">FAS</option>
              <option value="PKIMT">PKIMT</option>
              <option value="MAXIM">MAXIM</option>
              <option value="ONE SOURCE">ONE SOURCE</option>
              <option value="MEGATREND">MEGATREND</option>
              <option value="ADD EVEN">ADD EVEN</option>
            </select>
          </div>
          <div class="col-6">
            <label>Shuttle Route:</label>
            <select id="shuttle_route_master_update" class="form-control" style="height:45px; border: 1px solid black;">
              <option value="">Select Route</option>
              <option value="Batangas">Batangas</option>
              <option value="San Jose">San Jose</option>
              <option value="Ibaan">Ibaan</option>
              <option value="Rosario">Rosario</option>
              <option value="Sta. Teresita">Sta. Teresita</option>
              <option value="Padre Garcia">Padre Garcia</option>
              <option value="Lipa Malayo">Lipa Malayo</option>
              <option value="Lipa Malapit">Lipa Malapit</option>
              <option value="Malvar">Malvar</option>
              <option value="Sto. Tomas Malayo">Sto. Tomas Malayo</option>
              <option value="San Lucas">San Lucas</option>
              <option value="San Pablo via Sto. Tomas">San Pablo via Sto. Tomas</option>
              <option value="San Pablo via Lipa">San Pablo via Lipa</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <label>Address:</label>
            <input type="text" id="address_master_update" class="form-control" autocomplete="off" style="height:45px; border: 1px solid black;">
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <label>Contact No:</label>
            <input type="text" id="contact_no_master_update" class="form-control" autocomplete="off" style="height:45px; border: 1px solid black;" maxlength="11">
          </div>
          <div class="col-6">
            <label>Employment Status:</label>
            <select id="emp_status_master_update" class="form-control" style="height:45px; border: 1px solid black;">
              <option value="">Select Status</option>
              <option value="Regular">Regular</option>
              <option value="Probationary">Probationary</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <label>Jr. Staff / Staff:</label>
            <select id="emp_js_s_master_update" class="form-control" style="height:45px; border: 1px solid black;"></select>
          </div>
          <div class="col-6">
            <label>Supervisor:</label>
            <select id="emp_sv_master_update" class="form-control" style="height:45px; border: 1px solid black;"></select>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <label>Managing/Approving Officer:</label>
            <select id="emp_approver_master_update" class="form-control" style="height:45px; border: 1px solid black;"></select>
          </div>
          <div class="col-6">
            <label class="mb-3">Resigned</label>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="resigned_master_update">
              <label class="form-check-label" for="resigned_master_update">
                Resigned
              </label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <label>Date Resigned:</label>
            <input type="date" id="resigned_date_master_update" class="form-control" style="height:45px; border: 1px solid black;">
          </div>
        </div>
        <br>
        <hr>
        <div class="row">
          <div class="col-7">
            <div class="float-left">
              <a href="#" class="btn btn-danger" onclick="delete_employee()">Delete Employee</a>
            </div>
          </div>
          <div class="col-5">
            <div class="float-right">
              <a href="#" class="btn btn-success" onclick="print_employees_qr()">Print QR Code</a>
              <a href="#" class="btn btn-primary" onclick="update_employee()">Update Employee</a>
            </div>
          </div>
        </div>
      <!-- /.card-body -->
      </div>
    <!-- /.card -->
    </div>
  </div>
</div>
