<div class="modal fade bd-example-modal-xl" id="new_account" tabindex="-1"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">
          <b>Register Account</b>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-4">
            <label>Employee No:</label>
            <input type="text" id="emp_no" class="form-control" autocomplete="off" style="height:45px; border: 1px solid black; font-size: 25px;">
          </div>
          <div class="col-4">
            <label>Full Name:</label>
            <input type="text" id="full_name" class="form-control" autocomplete="off" style="height:45px; border: 1px solid black; font-size: 25px;">
          </div>
          <div class="col-4">
            <label>Department:</label>
            <select id="dept" class="form-control" style="height:45px; border: 1px solid black;">
              <option value="">Select Department</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-4">
            <label>Section:</label>
            <select id="section" class="form-control" style="height:45px; border: 1px solid black;">
              <option value="">Select Section</option>
            </select>
          </div>
          <div class="col-4">
            <label>Line No:</label>
            <select id="line_no" class="form-control" style="height:45px; border: 1px solid black;">
              <option value="">Select Line No.</option>
            </select>
          </div>
          <div class="col-4">
            <label>Shift Group:</label>
            <select id="shift_group" class="form-control" style="height:45px; border: 1px solid black;">
              <option value="">Select Shift Group</option>
              <option value="A">A</option>
              <option value="B">B</option>
              <option value="ADS">ADS</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-4">
            <label>User Type:</label>
            <select id="role" class="form-control" style="height:45px; border: 1px solid black;">
              <option value="">Select User Type</option>
              <option value="admin">Admin</option>
              <option value="user">User</option>
            </select>
          </div>
        </div>
        <br>
        <hr>
        <div class="row">
          <div class="col-12">
            <div class="float-right">
              <button id="btnAddAccount" class="btn btn-primary" onclick="register_accounts()">Register Account</button>
            </div>
          </div>
        </div>
      <!-- /.card-body -->
      </div>
    <!-- /.card -->
    </div>
  </div>
</div>
