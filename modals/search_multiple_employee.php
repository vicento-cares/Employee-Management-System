<!-- Data Info Modal -->
<div class="modal fade" id="search_multiple_employee" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-dark">
        <h4 class="modal-title">Search Multiple Employee</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span class="text-white" aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="search_multiple_employee_form">
          <div class="row mb-4">
            <div class="col-4">
              <label>Employee No:</label>
              <input type="text" id="emp_no_search_multiple" class="form-control" autocomplete="off" style="height:45px; border: 1px solid black; font-size: 25px;" required>
            </div>
          </div>
        </form>
        <div class="row" id="search_multiple_employee_container">
        </div>
      </div>
      <div class="modal-footer bg-light">
        <button type="button" class="btn bg-dark" data-dismiss="modal" data-toggle="modal">Close</button>
        <button type="button" class="btn bg-success" id="btnSearchMultipleEmployee" onclick="load_employees(1)" data-dismiss="modal" data-toggle="modal" disabled>Search</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->