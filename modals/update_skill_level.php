<div class="modal fade bd-example-modal-xl" id="update_skill_level" tabindex="-1"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">
          <b>Update Skill Level</b>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="update_skill_level_form">
          <input type="hidden" id="id_skill_level_update" class="form-control">
          <input type="hidden" id="emp_no_c_update" class="form-control">
          <input type="hidden" id="process_c_update" class="form-control">
          <div class="row">
            <div class="col-12">
              <label>Skill Level:</label>
              <select id="skill_level_c_update" class="form-control" style="height:45px; border: 1px solid black;">
                <option value="">Select Skill Level</option>
                <option value="1">Level 1</option>
                <option value="2">Level 2</option>
                <option value="3">Level 3</option>
                <option value="4">Level 4</option>
              </select>
            </div>
          </div>
          <br>
          <hr>
          <div class="row">
            <div class="col-12">
              <div class="float-right">
                <button type="submit" class="btn btn-primary">Update</button>
              </div>
            </div>
          </div>
        </form>
      <!-- /.card-body -->
      </div>
    <!-- /.card -->
    </div>
  </div>
</div>
