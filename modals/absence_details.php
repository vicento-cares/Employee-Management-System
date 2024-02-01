<!-- Data Info Modal -->
<div class="modal fade" id="absence_details">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-dark">
        <h4 class="modal-title">Absence Details</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span class="text-white" aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="id_absence_update">
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group mb-0">
              <label>Employee No. : </label>
              <span id="emp_no_absence_update" class="ml-2"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group mb-0">
              <label>Full Name : </label>
              <span id="full_name_absence_update" class="ml-2"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label>Day of Absence : </label>
              <span id="absent_day_absence_update" class="ml-2"></span>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label>Shift : </label>
              <span id="absent_shift_absence_update" class="ml-2"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <!-- text input -->
            <div class="form-group">
              <label>Type of Absent</label><label style="color: red;">*</label>
              <select class="form-control" id="absent_type_absence_update" style="width: 100%;" required>
                <option disabled selected value="">Select Type</option>
                <option value="VL">VL</option>
                <option value="SL">SL</option>
                <option value="LWOP">LWOP</option>
                <option value="RD">RD</option>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <!-- text input -->
            <div class="form-group">
              <label>Reason</label><label style="color: red;">*</label>
              <textarea id="reason_absence_update" class="form-control" style="resize: none;" rows="3" maxlength="255" onkeyup="count_reason_absence_update_char()" required></textarea>
              <span id="reason_absence_update_count"></span>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer bg-light">
        <button type="button" class="btn bg-dark" data-dismiss="modal" data-toggle="modal">Close</button>
        <button type="button" class="btn bg-success" onclick="save_absence_details()">Save</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->