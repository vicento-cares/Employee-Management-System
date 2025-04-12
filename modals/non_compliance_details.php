<!-- Data Info Modal -->
<div class="modal fade" id="non_compliance_details" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-dark">
        <h4 class="modal-title">Employee Non-Compliance Details</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span class="text-white" aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group mb-0">
              <label>Employee No. : </label>
              <span id="emp_no_ncd" class="ml-2"></span>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group mb-0">
              <label>Full Name : </label>
              <span id="full_name_ncd" class="ml-2"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group mb-0">
              <label>Department : </label>
              <span id="dept_ncd" class="ml-2"></span>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group mb-0">
              <label>Section : </label>
              <span id="section_ncd" class="ml-2"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group mb-0">
              <label>Line No. : </label>
              <span id="line_no_ncd" class="ml-2"></span>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group mb-0">
              <label>Process : </label>
              <span id="process_ncd" class="ml-2"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group mb-0">
              <label>No Time Out Count : </label>
              <span id="null_time_out_count_ncd" class="ml-2"></span>
            </div>
          </div>
        </div>
        <div class="row" id="emp_monthly_no_time_out_chart"></div>
        <div class="row">
          <div class="col-sm-9">
            <div class="form-group mb-0">
              <label>No Time Out Records Table</label>
            </div>
          </div>
          <div class="col-sm-3">
            <button type="button" class="btn bg-success btn-block" onclick="export_non_compliance('nonComplianceDetailsTable')"><i class="fas fa-download"></i> Export Non-Compliance Details</button>
          </div>
        </div>
        <div class="row mb-2">
          <div class="col-sm-2">
            <span id="count_view_ncd"></span>
          </div>
        </div>
        <div id="nonComplianceDetailsTableRes" class="table-responsive" style="max-height: 500px; overflow: auto; display:inline-block;">
          <table id="nonComplianceDetailsTable" class="table table-sm table-head-fixed table-foot-fixed text-nowrap table-hover">
            <thead style="text-align: center;">
              <tr>
                <th>#</th>
                <th>Employee No.</th>
                <th>Day</th>
                <th>Shift</th>
                <th>Time In 1</th>
                <th>Time In 2</th>
                <th>Time Out</th>
              </tr>
            </thead>
            <tbody id="nonComplianceDetailsData" style="text-align: center;"></tbody>
          </table>
        </div>
        <div class="row mt-2">
          <div class="col-sm-9">
            <div class="form-group mb-0">
              <label>Past No Time Out Records Table</label>
            </div>
          </div>
          <div class="col-sm-3">
            <button type="button" class="btn bg-success btn-block" onclick="export_non_compliance('pastNoTimeOutRecordTable')"><i class="fas fa-download"></i> Export Past No Time Out Records</button>
          </div>
        </div>
        <div class="row mb-2">
          <div class="col-sm-2">
            <span id="count_view_pntr"></span>
          </div>
        </div>
        <div id="pastNoTimeOutRecordTableRes" class="table-responsive" style="max-height: 500px; overflow: auto; display:inline-block;">
          <table id="pastNoTimeOutRecordTable" class="table table-sm table-head-fixed table-foot-fixed text-nowrap table-hover">
            <thead style="text-align: center;">
              <tr>
                <th>#</th>
                <th>Employee No.</th>
                <th>Day</th>
                <th>Shift</th>
                <th>Time In 1</th>
                <th>Time In 2</th>
                <th>Time Out</th>
              </tr>
            </thead>
            <tbody id="pastNoTimeOutRecordData" style="text-align: center;"></tbody>
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