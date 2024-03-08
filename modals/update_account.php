<div class="modal fade bd-example-modal-xl" id="update_account" tabindex="-1"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">
          <b>Update Account Details</b>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"  onclick="javascript:window.location.reload()">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-4">
            <input type="hidden" id="id_account_update" class="form-control">
            <label>Employee No:</label>
            <input type="text" id="emp_no_update" class="form-control" autocomplete="off" style="height:45px; border: 1px solid black; font-size: 25px;">
          </div>
          <div class="col-4">
            <label>Full Name:</label>
            <input type="text" id="full_name_update" class="form-control" autocomplete="off" style="height:45px; border: 1px solid black; font-size: 25px;" disabled>
          </div>
          <div class="col-4">
            <label>Department:</label>
            <select id="dept_update" class="form-control" style="height:45px; border: 1px solid black;" disabled>
              <option value="">Select Department</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-4">
            <label>Section:</label>
            <input type="text" id="section_update" class="form-control" autocomplete="off" style="height:45px; border: 1px solid black;" disabled>
          </div>
          <div class="col-4">
            <label>Line No:</label>
            <input type="text" id="line_no_update" class="form-control" autocomplete="off" style="height:45px; border: 1px solid black;" disabled>
          </div>
          <div class="col-4">
            <label>Shift Group:</label>
            <select id="shift_group_update" class="form-control" style="height:45px; border: 1px solid black;" disabled>
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
            <select id="role_update" class="form-control" style="height:45px; border: 1px solid black;" disabled>
              <option value="">Select User Type</option>
              <option value="admin">Admin</option>
              <option value="user">User</option>
            </select>
          </div>
        </div>
        <br>
        <hr>
        <div class="row">
          <div class="col-9">
            <div class="float-left">
              <button class="btn btn-danger" id="btnDeleteAccount" onclick="delete_account()">Delete Account</button>
            </div>
          </div>
          <div class="col-3">
            <div class="float-right">
              <button class="btn btn-primary" id="btnUpdateAccount" onclick="update_account()">Update Account</button>
            </div>
          </div>
        </div>
      <!-- /.card-body -->
      </div>
    <!-- /.card -->
    </div>
  </div>
</div>
