<div class="modal fade bd-example-modal-xl" id="update_employee" tabindex="-1"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">
          <b>Employee Details</b>
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
            <input type="text" id="full_name_master_update" class="form-control" autocomplete="off" style="height:45px; border: 1px solid black; font-size: 25px;" disabled>
          </div>
          <div class="col-2">
            <label>Gender:</label>
            <select id="gender_master_update" class="form-control" style="height:45px; border: 1px solid black;" disabled>
              <option value="">Select Gender</option>
              <option value="M">Male</option>
              <option value="F">Female</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-4">
            <label>Department:</label>
            <select id="dept_master_update" class="form-control" style="height:45px; border: 1px solid black;" disabled>
              <option value="">Select Department</option>
            </select>
          </div>
          <div class="col-8">
            <label>Process (Sub Section):</label>
            <select id="sub_section_master_update" class="form-control" onchange="" style="height:45px; border: 1px solid black;">
              <option value="">Select Sub Section</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-4">
            <label>Section:</label>
            <select id="section_master_update" class="form-control" onchange="fetch_line_dropdown(2)" style="height:45px; border: 1px solid black;" disabled>
              <option value="">Select Section</option>
            </select>
          </div>
          <div class="col-8">
            <label>Line No:</label>
            <select id="line_no_master_update" class="form-control" onchange="get_laf_approver_dropdowns(2)" style="height:45px; border: 1px solid black;">
              <option value="">Select Line No.</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-4">
            <label>Position:</label>
            <select id="position_master_update" class="form-control" style="height:45px; border: 1px solid black;" disabled>
              <option value="">Select Position</option>
              <option value="Assistant Manager">Assistant Manager</option>
              <option value="Associate">Associate</option>
              <option value="Jr. Staff">Jr. Staff</option>
              <option value="Manager">Manager</option>
              <option value="Section Manager">Section Manager</option>
              <option value="Staff">Staff</option>
              <option value="Supervisor">Supervisor</option>
            </select>
          </div>
          <div class="col-5">
            <label>Specific Process:</label>
            <select id="process_master_update" class="form-control" style="height:45px; border: 1px solid black;">
              <option value="">Select Process</option>
            </select>
          </div>
          <div class="col-3">
            <label>Skill Level:</label>
            <select id="skill_level_master_update" class="form-control" style="height:45px; border: 1px solid black;">
              <option value="">Select Skill Level</option>
              <option value="1">Level 1</option>
              <option value="2">Level 2</option>
              <option value="3">Level 3</option>
              <option value="4">Level 4</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-2">
            <label>Shift:</label>
            <select id="shift_master_update" class="form-control" style="height:45px; border: 1px solid black;">
              <option value="" disabled>Select Shift</option>
              <option value="DS">DS</option>
              <option value="NS">NS</option>
            </select>
          </div>
          <div class="col-3">
            <label>Shift Group:</label>
            <select id="shift_group_master_update" class="form-control" style="height:45px; border: 1px solid black;">
              <option value="" disabled>Select Shift Group</option>
              <option value="A">A</option>
              <option value="B">B</option>
              <option value="ADS">ADS</option>
            </select>
          </div>
          <div class="col-4">
            <label>Shuttle Route:</label>
            <select id="shuttle_route_master_update" class="form-control" style="height:45px; border: 1px solid black;" disabled>
              <option value="">Select Route</option>
              <option value="Batangas">Batangas</option>
              <option value="Ibaan">Ibaan</option>
              <option value="Lipa Malapit">Lipa Malapit</option>
              <option value="Lipa Malayo">Lipa Malayo</option>
              <option value="Malvar">Malvar</option>
              <option value="Padre Garcia">Padre Garcia</option>
              <option value="Rosario">Rosario</option>
              <option value="San Jose">San Jose</option>
              <option value="San Lucas">San Lucas</option>
              <option value="San Pablo via Lipa">San Pablo via Lipa</option>
              <option value="San Pablo via Sto. Tomas">San Pablo via Sto. Tomas</option>
              <option value="Sta. Teresita">Sta. Teresita</option>
              <option value="Sto. Tomas Malayo">Sto. Tomas Malayo</option>
            </select>
          </div>
          <div class="col-3">
            <label>Date Hired:</label>
            <input type="date" id="date_hired_master_update" class="form-control" style="height:45px; border: 1px solid black;" disabled>
          </div>
        </div>
        <div class="row">
          <div class="col-3">
            <label>Provider:</label>
            <select id="provider_master_update" class="form-control" style="height:45px; border: 1px solid black;" disabled>
              <option value="">Select Provider</option>
              <option value="ADD EVEN">ADD EVEN</option>
              <option value="FAS">FAS</option>
              <option value="GOLDENHAND">GOLDENHAND</option>
              <option value="MAXIM">MAXIM</option>
              <option value="MEGATREND">MEGATREND</option>
              <option value="ONE SOURCE">ONE SOURCE</option>
              <option value="PKIMT">PKIMT</option>
            </select>
          </div>
          <div class="col-9">
            <label>Address:</label>
            <input type="text" id="address_master_update" class="form-control" autocomplete="off" style="height:45px; border: 1px solid black;" disabled>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <label>Contact No:</label>
            <input type="text" id="contact_no_master_update" class="form-control" autocomplete="off" style="height:45px; border: 1px solid black;" maxlength="11" disabled>
          </div>
          <div class="col-6">
            <label>Employment Status:</label>
            <select id="emp_status_master_update" class="form-control" style="height:45px; border: 1px solid black;" disabled>
              <option value="">Select Status</option>
              <option value="Regular">Regular</option>
              <option value="Probationary">Probationary</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <label>Jr. Staff / Staff:</label>
            <select id="emp_js_s_master_update" class="form-control" style="height:45px; border: 1px solid black;" disabled></select>
          </div>
          <div class="col-6">
            <label>Supervisor:</label>
            <select id="emp_sv_master_update" class="form-control" style="height:45px; border: 1px solid black;" disabled></select>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <label>Managing/Approving Officer:</label>
            <select id="emp_approver_master_update" class="form-control" style="height:45px; border: 1px solid black;" disabled></select>
          </div>
          <div class="col-6">
            <label class="mb-3">Resigned</label>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="resigned_master_update" disabled>
              <label class="form-check-label" for="resigned_master_update">
                Resigned
              </label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <label>Date Resigned:</label>
            <input type="date" id="resigned_date_master_update" class="form-control" style="height:45px; border: 1px solid black;" disabled>
          </div>
        </div>
        <br>
        <hr>
        <div class="row">
          <div class="col-12">
            <div class="float-right">
              <a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>
              <a href="#" class="btn btn-success" onclick="update_employee()">Update Employee</a>
            </div>
          </div>
        </div>
      <!-- /.card-body -->
      </div>
    <!-- /.card -->
    </div>
  </div>
</div>
