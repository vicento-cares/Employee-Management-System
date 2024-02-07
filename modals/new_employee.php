<div class="modal fade bd-example-modal-xl" id="new_employee" tabindex="-1"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">
          <b>Register Employee</b>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-4">
            <label>Employee No:</label>
            <input type="text" id="emp_no_master" class="form-control" autocomplete="off" style="height:45px; border: 1px solid black; font-size: 25px;">
          </div>
          <div class="col-6">
            <label>Full Name:</label>
            <input type="text" id="full_name_master" class="form-control" autocomplete="off" style="height:45px; border: 1px solid black; font-size: 25px;">
          </div>
          <div class="col-2">
            <label>Gender:</label>
            <select id="gender_master" class="form-control" style="height:45px; border: 1px solid black;">
              <option value="">Select Gender</option>
              <option value="M">Male</option>
              <option value="F">Female</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-4">
            <label>Department:</label>
            <select id="dept_master" class="form-control" onchange="get_laf_approver_dropdowns(1)" style="height:45px; border: 1px solid black;">
              <option value="">Select Department</option>
            </select>
          </div>
          <div class="col-4">
            <label>Section:</label>
            <select id="section_master" class="form-control" onchange="fetch_line_dropdown(1)" style="height:45px; border: 1px solid black;">
              <option value="">Select Section</option>
            </select>
          </div>
          <div class="col-4">
            <label>Line No:</label>
            <select id="line_no_master" class="form-control" onchange="get_laf_approver_dropdowns(1)" style="height:45px; border: 1px solid black;">
              <option value="">Select Line No.</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-4">
            <label>Position:</label>
            <select id="position_master" class="form-control" style="height:45px; border: 1px solid black;">
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
          <div class="col-4">
            <label>Process:</label>
            <select id="process_master" class="form-control" style="height:45px; border: 1px solid black;">
              <option value="">Select Process</option>
            </select>
          </div>
          <div class="col-4">
            <label>Date Hired:</label>
            <input type="date" id="date_hired_master" class="form-control" style="height:45px; border: 1px solid black;">
          </div>
        </div>
        <div class="row">
          <div class="col-4">
            <label>Provider:</label>
            <select id="provider_master" class="form-control" style="height:45px; border: 1px solid black;">
              <option value="">Select Provider</option>
              <option value="FAS">FAS</option>
              <option value="PKIMT">PKIMT</option>
              <option value="MAXIM">MAXIM</option>
              <option value="ONE SOURCE">ONE SOURCE</option>
              <option value="MEGATREND">MEGATREND</option>
              <option value="ADD EVEN">ADD EVEN</option>
            </select>
          </div>
          <div class="col-2">
            <label>Shift Group:</label>
            <select id="shift_group_master" class="form-control" style="height:45px; border: 1px solid black;">
              <option value="">Select Shift Group</option>
              <option value="A">A</option>
              <option value="B">B</option>
            </select>
          </div>
          <div class="col-6">
            <label>Shuttle Route:</label>
            <select id="shuttle_route_master" class="form-control" style="height:45px; border: 1px solid black;">
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
              <option value="San Pablo via Sto. Tomas">San Pablo via Sto. Tomas</option>
              <option value="San Pablo via Lipa">San Pablo via Lipa</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <label>Address:</label>
            <input type="text" id="address_master" class="form-control" autocomplete="off" style="height:45px; border: 1px solid black;">
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <label>Contact No:</label>
            <input type="text" id="contact_no_master" class="form-control" autocomplete="off" style="height:45px; border: 1px solid black;" maxlength="11">
          </div>
          <div class="col-6">
            <label>Employment Status:</label>
            <select id="emp_status_master" class="form-control" style="height:45px; border: 1px solid black;">
              <option value="">Select Status</option>
              <option value="Regular">Regular</option>
              <option value="Probationary">Probationary</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <label>Jr. Staff / Staff:</label>
            <select id="emp_js_s_master" class="form-control" style="height:45px; border: 1px solid black;"></select>
          </div>
          <div class="col-6">
            <label>Supervisor:</label>
            <select id="emp_sv_master" class="form-control" style="height:45px; border: 1px solid black;"></select>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <label>Managing/Approving Officer:</label>
            <select id="emp_approver_master" class="form-control" style="height:45px; border: 1px solid black;"></select>
          </div>
        </div>
        <br>
        <hr>
        <div class="row">
          <div class="col-12">
            <div class="float-right">
              <a href="#" class="btn btn-primary" onclick="register_employees()">Register Employee</a>
            </div>
          </div>
        </div>
      <!-- /.card-body -->
      </div>
    <!-- /.card -->
    </div>
  </div>
</div>
