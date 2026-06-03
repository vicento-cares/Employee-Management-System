<?php include 'plugins/navbar.php';?>
<?php include 'plugins/sidebar/hr_bar.php';?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">EmpMgtSys Backup</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="emp_mgt_backup.php">EmpMgtSys Backup</a></li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12 callout callout-warning">
          <h5>Reminder:</h5>
          <i>This will transfer Employee Management System System Data 
            <b>(Absences Report Data, Line Shifting, Line Support, Time In and Out)</b> 
            to our Employee Management System Backup Database. <b>Please set backup date range accurately!</b> Recommended Transferring Employee Management System Data was <b>every 6 months</b>
            to apply system optimization and reduce system and server lags or hangups</i>
        </div>
      </div>
      <form id="emp_mgt_backup_form">
        <div class="row mb-4">
          <div class="col-sm-3">
            <label>Backup data from</label>
            <input type="date" id="bak_date_from" class="form-control" autocomplete="off">
          </div>
          <div class="col-sm-3">
            <label>Backup data to</label>
            <input type="date" id="bak_date_to" class="form-control" autocomplete="off">
          </div>
          <div class="col-sm-3 offset-sm-3">
            <label>&nbsp;</label>
            <button type="submit" class="btn btn-success btn-block"><i class="fas fa-database"></i> Transfer Data to Backup Database</button>
          </div>
        </div>
      </form>
      <div class="row">
        <div class="col-sm-12">
          <div class="card card-gray-dark card-outline">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-history"></i> Employee Management System Backup Logs Table</h3>
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
              <div class="row mb-2">
                <div class="col-sm-2">
                  <span id="count_view"></span>
                </div>
              </div>
              <div class="table-responsive" style="max-height: 500px; overflow: auto; display:inline-block;">
                <table id="backupLogsTable" class="table table-sm table-head-fixed text-nowrap table-hover">
                  <thead style="text-align: center;">
                    <tr>
                      <th>#</th>
                      <th>Backup Date From</th>
                      <th>Backup Date To</th>
                      <th>Date Backup</th>
                      <th>Backup By</th>
                    </tr>
                  </thead>
                  <tbody id="backupLogsData" style="text-align: center;">
                    <tr>
                      <td colspan="5" style="text-align:center;">
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
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
  </section>
</div>

<?php include 'plugins/footer.php';?>
<?php include 'plugins/js/emp_mgt_backup_script.php';?>