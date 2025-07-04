<!-- Data Info Modal -->
<div class="modal fade" id="set_line_support_details" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-dark">
        <h4 class="modal-title">Set Line Support Details</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span class="text-white" aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="set_line_support_details_form">
          <input type="hidden" id="lsd_id">
          <input type="hidden" id="lsd_emp_no">
          <div class="row mb-2">
            <div class="col-sm-3">
              <label>Category:</label><label style="color: red;">*</label>
              <select class="form-control" id="lsd_category" required>
                <option selected value="">Select Category</option>
                <option value="Initial">Initial</option>
                <option value="Final">Final</option>
              </select>
            </div>
            <div class="col-sm-9">
              <label>Assigned Process:</label><label style="color: red;">*</label>
              <select class="form-control" id="lsd_assigned_process" required>
                <option selected value="">Select Process</option>
              </select>
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-sm-9">
              <label>Assigned Station:</label><label style="color: red;">*</label>
              <select class="form-control" id="lsd_assigned_station" required>
                <option selected value="">Select Assigned Station</option>
              </select>
            </div>
            <div class="col-sm-3">
              <label class="mb-2">Assigned Station No:</label><label style="color: red;">*</label>
              <input type="text" id="lsd_assigned_station_no" class="form-control" maxlength="100" autocomplete="off" required>
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-sm-6">
              <label>Start Hour:</label><label style="color: red;">*</label><br>
              <input type="time" id="lsd_start_date" required>
            </div>
            <div class="col-sm-6">
              <label>End Hour:</label><label style="color: red;">*</label><br>
              <input type="time" id="lsd_end_date" required>
            </div>
          </div>
          <br>
          <hr>
          <div class="row">
            <div class="col-12">
              <div class="float-left">
                <button type="button" class="btn bg-dark" data-dismiss="modal" data-toggle="modal">Close</button>
              </div>
              <div class="float-right">
                <button type="submit" class="btn bg-success" id="btnSaveLineSupportDetails">Save</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->