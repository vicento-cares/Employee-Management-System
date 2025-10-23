<!-- Data Info Modal -->
<div class="modal fade" id="update_employee_transfer" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-dark">
        <h4 class="modal-title">Update Employee Transfer</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span class="text-white" aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="update_employee_transfer_form">
        <div class="modal-body">
          <input type="hidden" name="et_id_update" id="et_id_update">
          <div class="row mb-2">
            <div class="col-sm-12">
              <div class="form-group mb-0">
                <label>Employee No.</label><label style="color: red;">*</label>
                <input type="text" id="et_emp_no_update" class="form-control" disabled>
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-sm-6">
              <label>Employee Transfer Type</label><label style="color: red;">*</label>
              <select id="et_emp_transfer_type_update" class="form-control" disabled>
                <option selected disabled value="">Select Type</option>
                <option value="department">Department Transfer</option>
                <option value="section">Section Transfer</option>
              </select>
            </div>
            <div class="col-sm-6">
              <label>Department To</label><label style="color: red;">*</label>
              <select id="et_dept_update" class="form-control" required>
                <option selected disabled value="">Select Department</option>
              </select>
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-sm-6">
              <label>Section To</label><label style="color: red;">*</label>
              <select id="et_section_update" class="form-control" onchange="fetch_line_dropdown(2)" required>
                <option selected disabled value="">Select Section</option>
              </select>
            </div>
            <div class="col-sm-6">
              <label>Line No. To</label><label style="color: red;">*</label>
              <select id="et_line_no_update" class="form-control" required>
                <option selected disabled value="">Select Line</option>
              </select>
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-sm-12">
              <label>Date Effectivity</label><label style="color: red;">*</label>
              <input type="date" id="et_date_effectivity_update" class="form-control" min="<?=$server_date_only_tomorrow?>" required>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <!-- text input -->
              <div class="form-group">
                <label>Reason</label><label style="color: red;">*</label>
                <textarea id="et_reason_update" class="form-control" style="resize: none;" rows="3" maxlength="255" onkeyup="count_et_reason_update_char()" required></textarea>
                <span id="et_reason_update_count"></span>
              </div>
            </div>
          </div>
          <br>
          <hr>
          <div class="row">
            <div class="col-9">
              <div class="float-left">
                <button type="submit" class="btn bg-danger" id="btnCancelEmployeeTransfer">Cancel</button>
              </div>
            </div>
            <div class="col-3">
              <div class="float-right">
                <button type="button" class="btn bg-dark" data-dismiss="modal" data-toggle="modal">Close</button>
                <button type="submit" class="btn bg-success" id="btnUpdateEmployeeTransfer">Save</button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->