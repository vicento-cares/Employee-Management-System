<!-- Data Info Modal -->
<div class="modal fade" id="search_multiple_toc" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-dark">
        <h4 class="modal-title">Search Multiple Time Out Counting</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span class="text-white" aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="search_multiple_toc_attendance_date_form">
          <div class="row mb-4">
            <div class="col-sm-6">
            <p class="mb-0"><label><input type="checkbox" class="singleCheck mr-2"
              id="attendance_date_search_multiple_chkbx" />Attendance Date<span></span></label></p>
            </div>
          </div>
          <div class="row mb-4">
            <div class="col-sm-6">
              <label>Attendance Date From</label>
              <input type="date" class="form-control" id="attendance_date_from_search_multiple" required disabled>
            </div>
            <div class="col-sm-6">
              <label>Attendance Date To</label>
              <input type="date" class="form-control" id="attendance_date_to_search_multiple" required disabled>
            </div>
          </div>
        </form>
        <form id="search_multiple_toc_shift_group_form">
          <div class="row mb-4">
            <div class="col-8">
              <p class="mb-0"><label><input type="checkbox" class="singleCheck mr-2"
                    id="shift_group_search_multiple_chkbx" />Shift Group<span></span></label></p>
              <select class="form-control" id="shift_group_search_multiple" style="width: 100%;" required disabled>
                <option selected value="">All</option>
                <option value="A">Shift A</option>
                <option value="B">Shift B</option>
                <option value="ADS">Shift ADS</option>
              </select>
            </div>
            <div class="col-4">
              <label>&nbsp;</label>
              <button type="submit" class="btn btn-block bg-success" id="btnAddSearchMultipleTocShiftGroup" disabled><i
                  class="fas fa-plus-circle"></i>
                Add</button>
            </div>
          </div>
        </form>
        <div class="row" id="search_multiple_toc_shift_group_container">
        </div>
        <form id="search_multiple_toc_dept_form">
          <div class="row mb-4">
            <div class="col-8">
              <p class="mb-0"><label><input type="checkbox" class="singleCheck mr-2"
                    id="dept_search_multiple_chkbx" />Department<span></span></label></p>
              <select id="dept_search_multiple" class="form-control" required disabled>
                <option selected value="">All</option>
              </select>
            </div>
            <div class="col-4">
              <label>&nbsp;</label>
              <button type="submit" class="btn btn-block bg-success" id="btnAddSearchMultipleTocDept" disabled><i
                  class="fas fa-plus-circle"></i>
                Add</button>
            </div>
          </div>
        </form>
        <div class="row" id="search_multiple_toc_dept_container">
        </div>
        <form id="search_multiple_toc_section_form">
          <div class="row mb-4">
            <div class="col-8">
              <p class="mb-0"><label><input type="checkbox" class="singleCheck mr-2"
                    id="section_search_multiple_chkbx" />Section<span></span></label></p>
              <select id="section_search_multiple" class="form-control" required disabled>
                <option value="">Select Section</option>
              </select>
            </div>
            <div class="col-4">
              <label>&nbsp;</label>
              <button type="submit" class="btn btn-block bg-success" id="btnAddSearchMultipleTocSection" disabled><i
                  class="fas fa-plus-circle"></i>
                Add</button>
            </div>
          </div>
        </form>
        <div class="row" id="search_multiple_toc_section_container">
        </div>
        <form id="search_multiple_toc_line_no_form">
          <div class="row mb-4">
            <div class="col-8">
              <p class="mb-0"><label><input type="checkbox" class="singleCheck mr-2"
                    id="line_no_search_multiple_chkbx" />Line No<span></span></label></p>
              <select id="line_no_search_multiple" class="form-control" required disabled>
                <option value="">Select Line No.</option>
              </select>
            </div>
            <div class="col-4">
              <label>&nbsp;</label>
              <button type="submit" class="btn btn-block bg-success" id="btnAddSearchMultipleTocLineNo" disabled><i
                  class="fas fa-plus-circle"></i>
                Add</button>
            </div>
          </div>
        </form>
        <div class="row" id="search_multiple_toc_line_no_container">
        </div>
      </div>
      <div class="modal-footer bg-light">
        <button type="button" class="btn bg-dark" data-dismiss="modal" data-toggle="modal">Close</button>
        <button type="button" class="btn bg-success" id="btnSearchMultipleToc"
          onclick="review_search_multiple_toc()" data-dismiss="modal" data-toggle="modal" disabled>Search</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->