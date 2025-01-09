<?php include 'plugins/navbar.php'; ?>
<?php include 'plugins/sidebar/control_area_bar.php'; ?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Certification</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="employees.php">Home</a></li>
            <li class="breadcrumb-item active">Certification</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-12">
          <div class="card card-gray-dark card-outline">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-tasks"></i> Certification Table</h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="maximize">
                  <i class="fas fa-expand"></i>
                </button>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="row">
                <div class="col-sm-1">
                  <label for="">Category</label>
                  <select class="form-control btn bg-success" required name="category" id="category"
                    onchange="search_data(1)">
                    <option value="">Select</option>
                    <option>Initial</option>
                    <option>Final</option>
                  </select>
                </div>
                <div class="col-sm-3">
                  <label for="">Process Name</label>
                  <select class="form-control btn" name="pro" required id="pro"
                    style="width: 100%; border: 2px solid black;background-color: white;color: black;font-size: 16px;cursor: pointer; border-color: var(--success);">
                    <option>Please select a process.....</option>
                    <option></option>
                  </select>
                </div>
                <div class="col-sm-2">
                  <label for="">Employee ID</label>
                  <input class="form-control" placeholder="Type here..." type="text" id="emp_id_search">
                </div>
                <div class="col-sm-2">
                  <label for="">Employee Name</label>
                  <input class="form-control" placeholder="Type here..." type="text" id="fullname_search">
                </div>
                <div class="col-sm-2">
                  <label for="">Date Authorized</label>
                  <input class="form-control" type="date" placeholder="Select date..." onfocus="(this.type='date')"
                    onblur="(this.type='text')" id="date_authorized_search">
                </div>
                <div class="col-sm-2">
                  <label for="">Expire Date</label>
                  <input class="form-control" type="date" placeholder="Select date..." onfocus="(this.type='date')"
                    onblur="(this.type='text')" id="expire_date_search">
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-sm-3 offset-sm-5">
                  <?php if (empty($_SESSION['line_no'])) {?>
                  <label>Line No.</label>
                  <select id="line_no_search" class="form-control" onchange="search_data(1)">
                    <option value="">Select Line No.</option>
                  </select>
                  <?php } else { ?>
                  <input type="hidden" id="line_no_search" value="<?=$_SESSION['line_no']?>">
                  <?php } ?>
                </div>
                <div class="col-sm-2">
                  <label>&nbsp;</label>
                  <!-- search button -->
                  <button class="btn btn-block btn-success d-flex justify-content-left" id="search_btn"
                    onclick="search_data(1)"
                    style="height:34px;border-radius:.25rem;background: var(--success);font-size:15px;font-weight:normal;">
                    <i class="fas fa-search"></i>&nbsp;&nbsp;Search</button>
                </div>
                <div class="col-sm-2">
                  <label>&nbsp;</label>
                  <!-- exportt button -->
                  <a class="btn btn-block btn-secondary d-flex justify-content-left" onclick="export_data()"
                    style="height:34px;border-radius:.25rem;font-size:15px;font-weight:normal;">
                    <i class="fas fa-download"></i>&nbsp;&nbsp;Export</a>
                </div>
              </div>

              <br>
              <div class="col-12">
                <div class="card-body table-responsive p-0" style="height: 550px;">
                  <table class="table table-head-fixed text-nowrap" id="employee_data">
                    <thead style="text-align: center;">
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
                    </thead>
                    <tbody id="process_details"></tbody>
                  </table>
                </div>
                <div class="row mt-3">
                  <div class="col-sm-12 col-md-9 col-9">
                    <div class="dataTables_info" id="count_rows_display" role="status" aria-live="polite"></div>
                    <input type="hidden" id="count_rows">
                  </div>
                  <div class="col-sm-12 col-md-1 col-1">
                    <button type="button" id="btnPrevPage" class="btn bg-gray-dark btn-block"
                      onclick="get_prev_page()">Prev</button>
                  </div>
                  <div class="col-sm-12 col-md-1 col-1">
                    <input list="process_details_paginations" class="form-control" id="process_details_pagination"
                      maxlength="255">
                    <datalist id="process_details_paginations"></datalist>
                  </div>
                  <div class="col-sm-12 col-md-1 col-1">
                    <button type="button" id="btnNextPage" class="btn bg-gray-dark btn-block"
                      onclick="get_next_page()">Next</button>
                  </div>
                </div>

              </div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
  </section>
</div>

<?php include 'plugins/footer.php'; ?>
<?php include 'plugins/js/certification_script.php'; ?>