<!-- Data Info Modal -->
<div class="modal fade" id="line_support_details" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-dark">
        <h4 class="modal-title">Line Support Details</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span class="text-white" aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group mb-0">
              <label>Employee No. : </label>
              <span id="emp_no_lsd" class="ml-2"></span>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group mb-0">
              <label>Full Name : </label>
              <span id="full_name_lsd" class="ml-2"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group mb-0">
              <label>Department : </label>
              <span id="dept_lsd" class="ml-2"></span>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group mb-0">
              <label>Section : </label>
              <span id="section_lsd" class="ml-2"></span>
            </div>
          </div>
        </div>
        <div class="row mb-2">
          <div class="col-sm-6">
            <div class="form-group mb-0">
              <label>Line No. : </label>
              <span id="line_no_lsd" class="ml-2"></span>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group mb-0">
              <label>Process : </label>
              <span id="process_lsd" class="ml-2"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-9">
            <div class="form-group mb-0">
              <label>Certification Table</label>
            </div>
          </div>
          <div class="col-sm-3">
            <button type="button" class="btn bg-success btn-block" onclick="export_line_support_certification('certificationTable')"><i class="fas fa-download"></i> Export Certification Details</button>
          </div>
        </div>
        <div class="row mb-2">
          <div class="col-sm-2">
            <span id="count_view_lsd"></span>
          </div>
        </div>
        <div id="certificationTableRes" class="table-responsive" style="max-height: 500px; overflow: auto; display:inline-block;">
          <table id="certificationTable" class="table table-sm table-head-fixed table-foot-fixed text-nowrap table-hover">
            <thead style="text-align: center;">
              <tr>
                <th>#</th>
                <th>Process Name</th>
                <th>Authorization No.</th>
                <th>Authorization Year</th>
                <th>Date Authorized</th>
                <th>Expire&nbsp;Date</th>
                <th>Employee Name</th>
                <th>Employee No.</th>
                <th>Batch No.</th>
                <th>Department</th>
                <th>Section</th>
                <th>Line No.</th>
                <th>Skill Level</th>
                <th>Remarks</th>
                <th>Reason of Cancellation</th>
                <th>Date of Cancellation</th>
              </tr>
            </thead>
            <tbody id="certificationData" style="text-align: center;"></tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer bg-light">
        <button type="button" class="btn bg-dark" data-dismiss="modal" data-toggle="modal">Close</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->