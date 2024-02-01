Data Info Modal -->
<div class="modal fade" id="leave_form_clinic" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-dark">
        <h4 class="modal-title">Leave Application Form</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span class="text-white" aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group mb-0">
              <label>Leave Form ID : </label>
              <span id="leave_form_id_leave_clinic" class="ml-2"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group mb-0">
              <label>Name : </label>
              <span id="full_name_leave_clinic" class="ml-2"></span>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group mb-0">
              <label>Employee No. : </label>
              <span id="emp_no_leave_clinic" class="ml-2"></span>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group mb-0">
              <label>Date Filed : </label>
              <span id="date_filed_leave_clinic" class="ml-2"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-3">
            <div class="form-group mb-0">
              <label>Dept/Group : </label>
              <span id="dept_leave_clinic" class="ml-2"></span>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group mb-0">
              <label>Position : </label>
              <span id="position_leave_clinic" class="ml-2"></span>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group mb-0">
              <label>Employment Status : </label>
              <span id="emp_status_leave_clinic" class="ml-2"></span>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group mb-0">
              <label>Date Hired : </label>
              <span id="date_hired_leave_clinic" class="ml-2"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group mb-0">
              <label>Address on Leave : </label>
              <span id="address_leave_clinic" class="ml-2"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label>Contact No. on Leave : </label>
              <span id="contact_no_leave_clinic" class="ml-2"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-3">
            <!-- text input -->
            <div class="form-group">
              <label>Type of Leave</label>
              <span id="leave_type_leave_clinic" class="ml-2"></span>
            </div>
          </div>
          <div class="col-sm-3">
            <!-- text input -->
            <div class="form-group">
              <label>Date From : </label>
              <span id="leave_date_from_leave_clinic" class="ml-2"></span>
            </div>
          </div>
          <div class="col-sm-3">
            <!-- text input -->
            <div class="form-group">
              <label>Date To : </label>
              <span id="leave_date_to_leave_clinic" class="ml-2"></span>
            </div>
          </div>
          <div class="col-sm-3">
            <!-- text input -->
            <div class="form-group">
              <label>Total No. of Days : </label>
              <span id="total_leave_days_leave_clinic" class="ml-2"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group mb-0">
              <label>Information Recieved Through</label>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group mb-0">
              <label>Information Recieved By</label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <span id="irt_leave_clinic"></span>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <span id="irb_leave_clinic"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <!-- text input -->
            <div class="form-group">
              <label>Reason</label><br>
              <span id="reason_leave_clinic"></span>
            </div>
          </div>
        </div>
        <div class="dropdown-divider"></div>
        <div class="row">
          <div class="col-sm-3">
            <!-- text input -->
            <div class="form-group">
              <label>Issued By : </label><br>
              <span id="issued_by_leave_clinic">1</span>
            </div>
          </div>
          <div class="col-sm-3">
            <!-- text input -->
            <div class="form-group">
              <label>Jr. Staff / Staff : </label><br>
              <span id="js_s_leave_clinic">2</span>
            </div>
          </div>
          <div class="col-sm-3">
            <!-- text input -->
            <div class="form-group">
              <label>Supervisor : </label><br>
              <span id="sv_leave_clinic">3</span>
            </div>
          </div>
          <div class="col-sm-3">
            <!-- text input -->
            <div class="form-group">
              <label>Managing / Approving Officer : </label><br>
              <span id="approver_leave_clinic">4</span>
            </div>
          </div>
        </div>
        <div class="dropdown-divider"></div>
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label>Nurse / Doctor Use (If Applicable)</label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group mb-0">
              <label>Remarks</label>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group mb-0">
              <label>Recommendation</label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group mb-0">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="chkbx_sl_r1_1_leave_clinic">
                <label class="form-check-label" for="chkbx_sl_r1_1_leave_clinic">
                  Undertime (No. of Hours) 
                  <input type="number" class="form-control" id="sl_r1_1_hrs_leave_clinic" class="mx-2" min="1"> 
                  Date : <input type="date" class="form-control" id="sl_r1_1_date_leave_clinic" class="ml-2"> 
                  Time In : <input type="time" class="form-control" id="sl_r1_1_time_in_leave_clinic" class="ml-2"> 
                  Time Out : <input type="time" class="form-control" id="sl_r1_1_time_out_leave_clinic" class="ml-2">
                </label>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group mb-0">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="chkbx_sl_rc_1_leave_clinic">
                <label class="form-check-label" for="chkbx_sl_rc_1_leave_clinic">
                  Unfit for (day(s)) <input type="number" class="form-control" id="sl_rc_1_days_leave_clinic" class="mx-2" min="1"> 
                  From <input type="date" class="form-control" id="sl_rc_2_from_leave_clinic" class="ml-2"> 
                  To <input type="date" class="form-control" id="sl_rc_2_to_leave_clinic" class="ml-2">
                </label>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group mb-0">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="chkbx_sl_r1_2_leave_clinic">
                <label class="form-check-label" for="chkbx_sl_r1_2_leave_clinic">
                  Sick Leave For (Days):  <input type="number" class="form-control" id="sl_r1_2_days_leave_clinic" class="mx-2" min="1">
                </label>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group mb-0">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="chkbx_sl_rc_3_oc_leave_clinic">
                <label class="form-check-label" for="chkbx_sl_rc_3_oc_leave_clinic">
                  For Observation At The Clinic
                </label>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group mb-0">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="chkbx_sl_r1_3_leave_clinic">
                <label class="form-check-label" for="chkbx_sl_r1_3_leave_clinic">
                  Fit To Work Effective (Date):  <input type="date" class="form-control" id="sl_r1_3_date_leave_clinic" class="mx-2">
                </label>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group mb-0">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="chkbx_sl_rc_4_hm_leave_clinic">
                <label class="form-check-label" for="chkbx_sl_rc_4_hm_leave_clinic">
                  For Hospital Management
                </label>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6 offset-sm-6">
            <div class="form-group mb-0">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="chkbx_sl_rc_mgh_leave_clinic">
                <label class="form-check-label" for="chkbx_sl_rc_mgh_leave_clinic">
                  May Go Home
                </label>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <!-- text input -->
            <div class="form-group">
              <label>Nurse / Doctor Remarks : </label>
              <textarea id="sl_r2_leave_clinic" class="form-control" style="resize: none;" rows="3" maxlength="255" onkeyup="count_sl_r2_leave_clinic_char()"></textarea>
              <span id="sl_r2_leave_clinic_count"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <!-- text input -->
            <div class="form-group mb-0">
              <label>Nurse / Doctor Name : </label>
              <span id="sl_dr_name_leave_clinic"></span>
            </div>
          </div>
          <div class="col-sm-6">
            <!-- text input -->
            <div class="form-group mb-0">
              <label>Date : </label>
              <span id="sl_dr_date_leave_clinic"></span>
            </div>
          </div>
        </div>
        <br>
        <hr>
        <div class="row">
          <div class="col-9">
            <div class="float-left">
              <button type="button" class="btn bg-dark" data-dismiss="modal" data-toggle="modal">Close</button>
            </div>
          </div>
          <div class="col-3">
            <div class="float-right">
              <button type="button" class="btn bg-success" onclick="save_leave_form_clinic()">Save</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal