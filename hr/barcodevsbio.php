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
                  <select id="bvb_year_search" class="form-control" required>
                      <option selected value="">Select Year</option>
                  </select>
              </div>
              <div class="col-sm-2">
                  <label>Month</label>
                  <select id="bvb_month_search" class="form-control" required>
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
                <button type="submit" class="btn bg-success btn-block"><i class="fas fa-sync"></i> Generate Charts</button>
              </div>
            </div>
          </form>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <div class="row">
        <div class="col-sm-12">
          <div class="form-group mb-0">
            <label>Time In Analysis Charts</label>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12" id="month_bio_vs_barcode_time_in_chart"></div>
      </div>
      <div class="row">
        <div class="col-12" id="month_section_late_time_in_chart"></div>
      </div>
      <div class="row">
        <div class="col-12" id="month_section_no_bio_time_in_chart"></div>
      </div>
      <div class="row">
        <div class="col-12" id="month_section_no_barcode_time_in_chart"></div>
      </div>
      <div class="row">
        <div class="col-12" id="month_section_no_entries_time_in_chart"></div>
      </div>
      <div class="row">
        <div class="col-12" id="month_section_early_barcode_time_in_chart"></div>
      </div>
      <div class="row">
        <div class="col-12" id="month_section_late_barcode_time_in_chart"></div>
      </div>
      <div class="row">
        <div class="col-6" id="month_section_top_late_time_in_chart"></div>
        <div class="col-6" id="month_section_top_no_bio_time_in_chart"></div>
      </div>
      <div class="row">
        <div class="col-6" id="month_section_top_no_barcode_time_in_chart"></div>
        <div class="col-6" id="month_section_top_no_entries_time_in_chart"></div>
      </div>
      <div class="row">
        <div class="col-6" id="month_section_top_early_barcode_time_in_chart"></div>
        <div class="col-6" id="month_section_top_late_barcode_time_in_chart"></div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <div class="form-group mb-0">
            <label>Time Out Analysis Charts</label>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12" id="month_bio_vs_barcode_time_out_chart"></div>
      </div>
      <div class="row">
        <div class="col-12" id="month_section_no_bio_time_out_chart"></div>
      </div>
      <div class="row">
        <div class="col-12" id="month_section_no_barcode_time_out_chart"></div>
      </div>
      <div class="row">
        <div class="col-12" id="month_section_no_entries_time_out_chart"></div>
      </div>
      <div class="row">
        <div class="col-12" id="month_section_early_bio_time_out_chart"></div>
      </div>
      <div class="row">
        <div class="col-12" id="month_section_late_bio_time_out_chart"></div>
      </div>
      <div class="row">
        <div class="col-6" id="month_section_top_no_bio_time_out_chart"></div>
        <div class="col-6" id="month_section_top_no_barcode_time_out_chart"></div>
      </div>
      <div class="row">
        <div class="col-6" id="month_section_top_no_entries_time_out_chart"></div>
        <div class="col-6" id="month_section_top_early_bio_time_out_chart"></div>
      </div>
      <div class="row">
        <div class="col-6" id="month_section_top_late_bio_time_out_chart"></div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <div class="form-group mb-0">
            <label>Compliance Analysis Charts</label>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12" id="month_compliance_time_in_chart"></div>
      </div>
      <div class="row">
        <div class="col-12" id="month_section_compliance_time_in_chart"></div>
      </div>
      <div class="row">
        <div class="col-12" id="month_section_non_compliance_time_in_chart"></div>
      </div>
      <div class="row">
        <div class="col-6" id="month_section_top_compliance_time_in_chart"></div>
        <div class="col-6" id="month_section_top_non_compliance_time_in_chart"></div>
      </div>
      <div class="row">
        <div class="col-12" id="month_compliance_time_out_chart"></div>
      </div>
      <div class="row">
        <div class="col-12" id="month_section_compliance_time_out_chart"></div>
      </div>
      <div class="row">
        <div class="col-12" id="month_section_non_compliance_time_out_chart"></div>
      </div>
      <div class="row">
        <div class="col-6" id="month_section_top_compliance_time_out_chart"></div>
        <div class="col-6" id="month_section_top_non_compliance_time_out_chart"></div>
      </div>
      <div class="row">
        <div class="col-sm-6">
          <div class="form-group mb-0">
            <label>No Time Out Barcode Vs Biometric Table</label>
          </div>
        </div>
      </div>
      <form id="bvb_table_form">
        <div class="row mb-2">
          <div class="col-sm-2">
            <label>Biometric Vs Barcode Day</label>
            <input type="date" class="form-control" id="bvb_day_search" required>
          </div>
          <div class="col-sm-2">
            <label>Time In Remarks</label>
            <select id="bvb_time_in_remarks_search" class="form-control">
              <option selected value="">All</option>
              <option value="Time In OK">Time In OK</option>
              <option value="Absent">Absent</option>
              <option value="No Entries Both">No Entries Both</option>
              <option value="No Bio">No Bio</option>
              <option value="No Barcode">No Barcode</option>
              <option value="Early Barcode">Early Barcode</option>
              <option value="Late Barcode">Late Barcode</option>
              <option value="Late">Late</option>
            </select>
          </div>
          <div class="col-sm-2">
            <label>Time Out Remarks</label>
            <select id="bvb_time_out_remarks_search" class="form-control">
              <option selected value="">All</option>
              <option value="Time Out OK">Time Out OK</option>
              <option value="Absent">Absent</option>
              <option value="No Entries Both">No Entries Both</option>
              <option value="No Bio">No Bio</option>
              <option value="No Barcode">No Barcode</option>
              <option value="Early Bio">Early Bio</option>
              <option value="Late Bio">Late Bio</option>
            </select>
          </div>
          <div class="col-sm-3">
            <label>&nbsp;</label>
            <button type="submit" class="btn bg-gray-dark btn-block" onclick=""><i class="fas fa-search"></i> Search</button>
          </div>
          <div class="col-sm-3">
            <label>&nbsp;</label>
            <button type="button" class="btn bg-success btn-block" onclick="export_bio_vs_barcode_data('bioVsBarcodeTable')"><i class="fas fa-download"></i> Export Barcode Vs Biometric List</button>
          </div>
        </div>
      </form>
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
  </section>
</div>

<?php include 'plugins/footer.php';?>
<?php include 'plugins/js/barcodevsbio_script.php';?>