<?php include 'plugins/navbar.php';?>
<?php include 'plugins/sidebar/hr_bar.php';?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Barcode Vs Biometric</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="home.php">Home</a></li>
            <li class="breadcrumb-item active">Barcode Vs Biometric</li>
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
          <form id="bvb_form">
            <div class="row mb-4">
              <div class="col-sm-2">
                  <label>Year</label>
                  <select id="nc_year_search" class="form-control" required>
                      <option selected value="">Select Year</option>
                  </select>
              </div>
              <div class="col-sm-2">
                  <label>Month</label>
                  <select id="nc_month_search" class="form-control" required>
                      <option selected value="">Select Month</option>
                      <option value="1">January</option>
                      <option value="2">February</option>
                      <option value="3">March</option>
                      <option value="4">April</option>
                      <option value="5">May</option>
                      <option value="6">June</option>
                      <option value="7">July</option>
                      <option value="8">August</option>
                      <option value="9">September</option>
                      <option value="10">October</option>
                      <option value="11">November</option>
                      <option value="12">December</option>
                  </select>
              </div>
              <div class="col-sm-3 offset-sm-5">
                <label>&nbsp;</label>
                <button type="submit" class="btn bg-gray-dark btn-block"><i class="fas fa-search"></i> Search</button>
              </div>
            </div>
          </form>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
       <div class="row">
        <div class="col-sm-12">
          <div class="card card-gray-dark card-tabs">
            <div class="card-header p-0 border-bottom-0">
              <ul class="nav nav-tabs" id="bvb-tab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="bvb-1-tab" data-toggle="pill" href="#bvb-1" role="tab" aria-controls="bvb-1" aria-selected="true">Time In Analysis</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="bvb-2-tab" data-toggle="pill" href="#bvb-2" role="tab" aria-controls="bvb-2" aria-selected="false">Time Out Analysis</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="bvb-3-tab" data-toggle="pill" href="#bvb-3" role="tab" aria-controls="bvb-3" aria-selected="false">Compliance Analysis</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="bvb-4-tab" data-toggle="pill" href="#bvb-4" role="tab" aria-controls="bvb-4" aria-selected="false">Barcode Vs Biometric Table Data</a>
                </li>
              </ul>
            </div>
            <div class="card-body">
              <div class="tab-content" id="bvb-tabContent">
                <!-- Time In Analysis -->
                <div class="tab-pane fade show active" id="bvb-1" role="tabpanel" aria-labelledby="bvb-1-tab">
                  <div class="row">
                    <div class="col-12" id="month_bio_vs_barcode_time_in_chart"></div>
                  </div>
                </div>
                <!-- Time Out Analysis -->
                <div class="tab-pane fade" id="bvb-2" role="tabpanel" aria-labelledby="bvb-2-tab">
                  <div class="row">
                    <div class="col-12" id="month_bio_vs_barcode_time_out_chart"></div>
                  </div>
                </div>
                <!-- Compliance Analysis -->
                <div class="tab-pane fade" id="bvb-3" role="tabpanel" aria-labelledby="bvb-3-tab">
                  <div class="row">
                    <div class="col-12" id="month_compliance_time_in_chart"></div>
                  </div>
                  <div class="row">
                    <div class="col-12" id="month_compliance_time_out_chart"></div>
                  </div>
                </div>
                <!-- Barcode Vs Biometric Table Data -->
                <div class="tab-pane fade" id="bvb-4" role="tabpanel" aria-labelledby="bvb-4-tab">
                  <div class="row">
                    <div class="col-sm-9">
                      <div class="form-group mb-0">
                        <label>No Time Out Barcode Vs Biometric Table</label>
                      </div>
                    </div>
                    <div class="col-sm-3">
                      <button type="button" class="btn bg-success btn-block" onclick="export_bio_vs_barcode_data('bioVsBarcodeTable')"><i class="fas fa-download"></i> Export Barcode Vs Biometric List</button>
                    </div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-sm-2">
                      <span id="count_view"></span>
                    </div>
                  </div>
                  <div id="bioVsBarcodeTableRes" class="table-responsive" style="max-height: 500px; overflow: auto; display:inline-block;">
                    <table id="bioVsBarcodeTable" class="table table-sm table-head-fixed table-foot-fixed text-nowrap table-hover">
                      <thead style="text-align: center;">
                        <tr>
                          <th>#</th>
                          <th>Day</th>
                          <th>Employee No.</th>
                          <th>Full Name</th>
                          <th>Department</th>
                          <th>Section</th>
                          <th>Line No.</th>
                          <th>Process</th>
                          <th>Time In Remarks</th>
                          <th>Time Out Remarks</th>
                        </tr>
                      </thead>
                      <tbody id="bioVsBarcodeData" style="text-align: center;"></tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
  </section>
</div>

<?php include 'plugins/footer.php';?>
<?php include 'plugins/js/barcodevsbio_script.php';?>