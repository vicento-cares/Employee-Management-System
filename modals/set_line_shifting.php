<!-- Data Info Modal -->
<div class="modal fade" id="set_line_shifting" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-dark">
        <h4 class="modal-title">Set Line Shifting</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span class="text-white" aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="set_line_shifting_form">
          <div class="row mb-2">
            <div class="col-sm-6">
              <label>Line No.</label><label style="color: red;">*</label>
              <select id="line_no_lshift" class="form-control" required>
                <option selected disabled value="">Select Line</option>
              </select>
            </div>
            <div class="col-sm-3">
              <label>Shift Group:</label><label style="color: red;">*</label>
              <select id="shift_group_lshift" class="form-control" required>
                <option value="" selected disabled>Select Shift Group</option>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="ADS">ADS</option>
              </select>
            </div>
            <div class="col-sm-3">
              <label>Shift:</label><label style="color: red;">*</label>
              <select id="shift_lshift" class="form-control" required>
                <option value="" selected disabled>Select Shift</option>
                <option value="DS">DS</option>
                <option value="NS">NS</option>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <label>Schedule Date (Exactly 6:00 AM)</label><label style="color: red;">*</label>
              <input type="date" id="schedule_date_lshift" class="form-control" min="<?=$server_date_only_tomorrow?>" required>
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
                <button type="submit" class="btn btn-block bg-success">Set</button>
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