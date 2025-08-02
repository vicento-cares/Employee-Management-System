<!-- Data Info Modal -->
<div class="modal fade" id="leave_form" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-dark">
        <h4 class="modal-title">Leave Application Form</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span class="text-white" aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="new_leave_form">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group mb-0">
                <label>Name : </label>
                <span id="full_name_leave" class="ml-2"></span>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group mb-0">
                <label>Employee No. : </label>
                <span id="emp_no_leave" class="ml-2"></span>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group mb-0">
                <label>Date Filed : </label>
                <span id="date_filed_leave" class="ml-2"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-3">
              <div class="form-group mb-0">
                <label>Dept/Group : </label>
                <span id="dept_leave" class="ml-2"></span>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group mb-0">
                <label>Position : </label>
                <span id="position_leave" class="ml-2"></span>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group mb-0">
                <label>Employment Status : </label>
                <span id="emp_status_leave" class="ml-2"></span>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group mb-0">
                <label>Date Hired : </label>
                <span id="date_hired_leave" class="ml-2"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <label>Address on Leave</label><label style="color: red;">*</label>
                <input type="text" id="address_leave" class="form-control" maxlength="625" required>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <label>Contact No. on Leave : </label>
                <span id="contact_no_leave" class="ml-2"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-3">
              <!-- text input -->
              <div class="form-group">
                <label>Type of Leave</label><label style="color: red;">*</label>
                <select class="form-control" id="leave_type_leave" style="width: 100%;" required>
                  <option disabled selected value="">Select Type</option>
                  <option value="BL">BEREAVEMENT LEAVE</option>
                  <option value="CL">COMPENSATORY OFF/LEAVE</option>
                  <option value="EL">EMERGENCY LEAVE</option>
                  <option value="M1">MATERNITY LEAVE</option>
                  <option value="NW">NO WORK(COMPANY INITIATED)</option>
                  <option value="PL">PATERNITY LEAVE</option>
                  <option value="RE">REJECTED LEAVE</option>
                  <option value="SL">SICK LEAVE</option>
                  <option value="SP">SOLO PARENT LEAVE</option>
                  <option value="UL">UNPAID LEAVE</option>
                  <option value="VL">VACATION LEAVE</option>
                  <option value="LY">LOYALTY LEAVE</option>
                </select>
              </div>
            </div>
            <div class="col-sm-3">
              <!-- text input -->
              <div class="form-group">
                <label>Date From</label><label style="color: red;">*</label>
                <input type="date" id="leave_date_from_leave" class="form-control" onchange="gen_total_leave_days()" required>
              </div>
            </div>
            <div class="col-sm-3">
              <!-- text input -->
              <div class="form-group">
                <label>Date To</label><label style="color: red;">*</label>
                <input type="date" id="leave_date_to_leave" class="form-control" onchange="gen_total_leave_days()" required>
              </div>
            </div>
            <div class="col-sm-3">
              <!-- text input -->
              <div class="form-group">
                <label>Total No. of Days : </label><label style="color: red;">*</label>
                <input type="number" id="total_leave_days_leave" min="1" class="form-control" required>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group mb-0">
                <label>Information Recieved Through</label><label style="color: red;">*</label>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group mb-0">
                <label>Information Recieved By</label><label style="color: red;">*</label>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-3">
              <input type="radio" id="irt_phone_call_leave" name="irt_leave" value="1" required>
              <label for="irt_phone_call_leave">Phone Call</label>
            </div>
            <div class="col-sm-3">
              <input type="radio" id="irt_letter_leave" name="irt_leave" value="2">
              <label for="irt_letter_leave">Letter</label>
            </div>
            <div class="col-sm-6">
              <input type="text" id="irb_leave" class="form-control" maxlength="255" required>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <!-- text input -->
              <div class="form-group">
                <label>Reason</label><label style="color: red;">*</label>
                <textarea id="reason_leave" class="form-control" style="resize: none;" rows="3" maxlength="255" onkeyup="count_reason_leave_char()" required></textarea>
                <span id="reason_leave_count"></span>
              </div>
            </div>
          </div>
          <br>
          <hr>
          <div class="row">
            <div class="col-12">
              <div class="float-right">
                <button type="button" class="btn bg-dark mr-2" data-dismiss="modal" data-toggle="modal">Close</button>
                <button type="submit" id="btnSaveLeaveForm" name="btn_save_leave_form" class="btn bg-success">Save</button>
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