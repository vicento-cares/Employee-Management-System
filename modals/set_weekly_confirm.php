<!-- Modal -->
<div class="modal fade" id="set_weekly_confirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Upload Weekly Shuttle Allocation</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h5 style="text-align:center;">Are you sure to upload Weekly Shuttle Allocation on <b>Schedule Type</b> of <h3
            id="sched_type_weekly_display" class="text-bold text-danger" style="text-align:center;">SAMPLE</h3>
        </h5>
      </div>
      <br>
      <hr>
      <div class="row my-2 mx-2">
        <div class="col-6">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
        </div>
        <div class="col-6">
          <div class="float-right">
            <button type="button" class="btn btn-success" data-dismiss="modal" onclick="import_weekly()">Upload Shuttle
              Allocation</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>