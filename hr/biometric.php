<?php include 'plugins/navbar.php'; ?>
<?php include 'plugins/sidebar/hr_bar.php'; ?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Biometric</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="home.php">Home</a></li>
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
              <h3 class="card-title"><i class="fas fa-database"></i> Biometric Data Table</h3>
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
              <div class="row mb-4">
                <div class="col-sm-2">
                  <label>Attendance Date</label>
                  <input type="date" class="form-control" id="attendance_date_search" onchange="biometric_data_list()">
                </div>
                <div class="col-sm-2">
                  <label>&nbsp;</label>
                  <a class="btn btn-dark btn-block" href="../template/biometric_template.csv?v=<?php echo time(); ?>"><i
                      class="fas fa-download"></i> Download Template</a>
                </div>
                <div class="col-sm-2">
                  <label>&nbsp;</label>
                  <button type="button" class="btn btn-warning btn-block btn-file">
                    <form id="file_form" enctype="multipart/form-data">
                      <span class="mx-0 my-0"><i class="fas fa-upload"></i> Import Biometric Data </span><input
                        type="file" id="file" name="file" onchange="upload_csv()" accept=".csv">
                    </form>
                  </button>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-sm-2">
                  <span id="count_view"></span>
                </div>
              </div>
              <div id="biometric_table_res" class="table-responsive"
                style="max-height: 500px; overflow: auto; display:inline-block;">
                <table id="biometric_table" class="table table-sm table-head-fixed text-nowrap table-hover">
                  <thead style="text-align: center;">
                    <tr>
                      <th>#</th>
                      <th>Day</th>
                      <th>Day Code</th>
                      <th>Shift</th>
                      <th>Employee No.</th>
                      <th>Full Name</th>
                      <th>Department</th>
                      <th>Section</th>
                      <th>Line No.</th>
                      <th>Time In</th>
                      <th>Time Out</th>
                    </tr>
                  </thead>
                  <tbody id="biometric_data" style="text-align: center;">
                    <tr>
                      <td colspan="11" style="text-align:center;">
                        <div class="spinner-border text-dark" role="status">
                          <span class="sr-only">Loading...</span>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>
      <!-- /.row -->
      <div class="row">
        <div class="col-sm-12">
          <div class="card card-gray-dark card-outline">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-sync"></i> Generate Biometric Vs Barcode Data </h3>
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
              <form id="bvb_gen_form">
                <div class="row mb-4">
                  <div class="col-sm-2">
                    <label class="mr-1">Biometric Vs Barcode Date</label><label style="color: red;">*</label>
                    <input type="date" class="form-control" id="bvb_day" max="<?=$server_date_only_yesterday?>" required>
                  </div>
                  <div class="col-sm-4">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-success btn-block">
                      <i class="fas fa-sync"></i> Generate Biometric Vs Barcode Data </span>
                    </button>
                  </div>
                </div>
              </form>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>
      <!-- /.row -->
    </div>
  </section>
</div>

<?php include 'plugins/footer.php'; ?>
<?php include 'plugins/js/biometric_script.php'; ?>