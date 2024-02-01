<div class="modal fade" id="import_employees" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel"><b>Import Employees</b></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="../process/import/imp_employees.php"  enctype="multipart/form-data" method="POST">
        <div class="modal-body">
          <label>File:</label>
          <input id="file" type="file" name="file" class="form-control-lg" accept=".csv">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" class="close" data-dismiss="modal">Close</button>
          <input type="submit" class="btn btn-primary" name="download_template" value="Download Template">
          <input type="submit" class="btn btn-primary" name="upload" value="Upload">
        </div>
      </form>
    </div>
  </div>
</div>