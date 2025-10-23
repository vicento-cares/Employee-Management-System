<!-- Data Info Modal -->
<div class="modal fade" id="new_employee_transfer" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-dark">
        <h4 class="modal-title">New Employee Transfer</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span class="text-white" aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="new_employee_transfer_form">
        <div class="modal-body">
          <div class="row mb-2">
            <div class="col-sm-12">
              <div class="form-group mb-0">
                <label>Employee No.</label><label style="color: red;">*</label>
                <input type="text" id="et_emp_no" class="form-control" required>
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-sm-6">
              <label>Employee Transfer Type</label><label style="color: red;">*</label>
              <select id="et_emp_transfer_type" class="form-control" required>
                <option selected disabled value="">Select Type</option>
                <option value="department">Department Transfer</option>
                <option value="section">Section Transfer</option>
              </select>
            </div>
            <div class="col-sm-6">
              <label>Department To</label><label style="color: red;">*</label>
              <select id="et_dept" class="form-control" required>
                <option selected disabled value="">Select Department</option>
              </select>
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-sm-6">
              <label>Section To</label><label style="color: red;">*</label>
              <select id="et_section" class="form-control" onchange="fetch_line_dropdown(1)" required>
                <option selected disabled value="">Select Section</option>
              </select>
            </div>
            <div class="col-sm-6">
              <label>Line No. To</label><label style="color: red;">*</label>
              <select id="et_line_no" class="form-control" required>
                <option selected disabled value="">Select Line</option>
              </select>
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-sm-12">
              <label>Date Effectivity</label><label style="color: red;">*</label>
              <input type="date" id="et_date_effectivity" class="form-control" min="<?=$server_date_only_tomorrow?>" required>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <!-- text input -->
              <div class="form-group">
                <label>Reason</label><label style="color: red;">*</label>
                <textarea id="et_reason" class="form-control" style="resize: none;" rows="3" maxlength="255" onkeyup="count_et_reason_char()" required></textarea>
                <span id="et_reason_count"></span>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn bg-dark" data-dismiss="modal" data-toggle="modal">Close</button>
          <button type="submit" class="btn bg-success">Save</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->