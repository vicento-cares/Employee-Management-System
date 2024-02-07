<!-- Data Info Modal -->
<div class="modal fade" id="set_line_support" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-dark">
        <h4 class="modal-title">Set Line Support Form</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span class="text-white" aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="line_support_id_ls">
        <div class="row mb-2">
          <div class="col-sm-3">
            <label>Scan ID Number</label>
            <input type="text" id="emp_no_ls" class="form-control" autocomplete="off">
          </div>
          <div class="col-sm-5">
            <label class="mb-2">Full Name</label><br>
            <span id="full_name_ls"></span>
          </div>
          <div class="col-sm-4">
            <label>Supported Line No.</label>
            <select class="form-control" id="line_no_ls" style="width: 100%;" required>
              <option disabled selected value="">Select Line</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4 offset-sm-8">
            <button type="button" class="btn btn-block bg-success" onclick="set_line_support()">Set</button>
          </div>
        </div>
        <div class="row mb-2">
          <div class="col-sm-2">
            <span id="count_view_set_line_support"></span>
          </div>
        </div>
        <div class="table-responsive" style="max-height: 500px; overflow: auto; display:inline-block;">
          <table id="setLineSupportTable" class="table table-sm table-head-fixed text-nowrap table-hover">
            <thead style="text-align: center;">
              <tr>
                <th>#</th>
                <th>Employee No.</th>
                <th>Full Name</th>
                <th>Dept</th>
                <th>Process</th>
                <th>Day</th>
                <th>Shift</th>
                <th>Supported Line No.</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="setLineSupportData" style="text-align: center;"></tbody>
          </table>
        </div>
        <br>
        <hr>
        <div class="row">
          <div class="col-12">
            <div class="float-left">
              <button type="button" class="btn bg-dark" data-dismiss="modal" data-toggle="modal">Close</button>
            </div>
            <div class="float-right">
              <button type="button" class="btn bg-success" id="btnSaveLineSupport" onclick="save_line_support()" disabled>Save</button>
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