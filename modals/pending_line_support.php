<!-- Data Info Modal -->
<div class="modal fade" id="pending_line_support" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-dark">
        <h4 class="modal-title">Pending Line Support</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span class="text-white" aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="pending_id_ls">
        <div class="row mb-2">
          <div class="col-sm-5">
            <label>Line Support ID : </label>
            <span id="pending_line_support_id_ls" class="ml-2"></span>
          </div>
          <div class="col-sm-4">
            <label>Day : </label>
            <span id="pending_day_ls" class="ml-2"></span>
          </div>
          <div class="col-sm-3">
            <label>Shift : </label>
            <span id="pending_shift_ls" class="ml-2"></span>
          </div>
        </div>
        <div class="row mb-2">
          <div class="col-sm-5">
            <label>Dept : </label>
            <span id="pending_dept_ls" class="ml-2"></span>
          </div>
          <div class="col-sm-7">
            <label>Process : </label>
            <span id="pending_process_ls" class="ml-2"></span>
          </div>
        </div>
        <div class="row mb-2">
          <div class="col-sm-5">
            <label>Employee No : </label>
            <span id="pending_emp_no_ls" class="ml-2"></span>
          </div>
          <div class="col-sm-7">
            <label>Full Name : </label>
            <span id="pending_full_name_ls" class="ml-2"></span>
          </div>
        </div>
        <div class="row mb-2">
          <div class="col-sm-5">
            <label>Set By No : </label>
            <span id="pending_set_by_no_ls" class="ml-2"></span>
          </div>
          <div class="col-sm-7">
            <label>Set by : </label>
            <span id="pending_set_by_ls" class="ml-2"></span>
          </div>
        </div>
        <div class="row mb-2">
          <div class="col-sm-5">
            <label>From Line No : </label>
            <span id="pending_line_no_from_ls" class="ml-2"></span>
          </div>
          <div class="col-sm-7">
            <label>Supported Line No : </label>
            <span id="pending_line_no_to_ls" class="ml-2"></span>
          </div>
        </div>
        <br>
        <hr>
        <div class="row" id="divPending">
          <div class="col-12">
            <div class="float-right">
              <button type="button" class="btn bg-dark" data-dismiss="modal" data-toggle="modal">Close</button>
            </div>
          </div>
        </div>
        <div class="row" id="divNeedConfirmation">
          <div class="col-12">
            <div class="float-left">
              <button type="button" class="btn bg-danger" onclick="reject_line_support()">Reject</button>
            </div>
            <div class="float-right">
              <button type="button" class="btn bg-success" onclick="accept_line_support()">Accept</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->