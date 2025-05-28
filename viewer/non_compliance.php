<?php
include 'plugins/header.php';
include 'plugins/preloader.php';
include 'plugins/navbar/viewer_navbar.php';
?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="row mb-2 ml-1 mr-1">
                    <div class="col-sm-6">
                        <h1 class="m-0"> Non-Compliance</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/emp_mgt/">EmpMgtSys</a></li>
                            <li class="breadcrumb-item active">Non-Compliance</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                        <div class="card card-gray-dark card-outline">
                            <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-chart-bar mr-2"></i>No Time Out Non-Compliance as of <?=date('F j, Y', strtotime('-1 day'))?></h3>
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
                                <div class="col-4" id="top_section_no_time_out_chart"></div>
                                <div class="col-4" id="top_line_no_time_out_chart"></div>
                                <div class="col-4" id="top_process_no_time_out_chart"></div>
                            </div>
                            <div class="row">
                                <div class="col-12" id="month_section_no_time_out_chart"></div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-9">
                                <div class="form-group mb-0">
                                    <label>No Time Out Non-Compliance Table</label>
                                </div>
                                </div>
                                <div class="col-sm-3">
                                <button type="button" class="btn bg-success btn-block" onclick="export_non_compliance('recentNonComplianceTable')"><i class="fas fa-download"></i> Export Non-Compliance List</button>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-2">
                                <span id="count_view_recent"></span>
                                </div>
                            </div>
                            <div id="recentNonComplianceTableRes" class="table-responsive" style="max-height: 500px; overflow: auto; display:inline-block;">
                                <table id="recentNonComplianceTable" class="table table-sm table-head-fixed table-foot-fixed text-nowrap table-hover">
                                <thead style="text-align: center;">
                                    <tr>
                                    <th>#</th>
                                    <th>Employee No.</th>
                                    <th>Full Name</th>
                                    <th>Department</th>
                                    <th>Section</th>
                                    <th>Line No.</th>
                                    <th>Process</th>
                                    <th>No Time Out Count</th>
                                    </tr>
                                </thead>
                                <tbody id="recentNonComplianceData" style="text-align: center;"></tbody>
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
                    <div class="row">
                        <div class="col-sm-12">
                        <div class="card card-gray-dark card-outline">
                            <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-tasks"></i> No Time Out Non-Compliance Search</h3>
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
                            <form id="non_compliance_form">
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
                            <div class="row">
                                <div class="col-4" id="top_section_no_time_out_search_chart"></div>
                                <div class="col-4" id="top_line_no_time_out_search_chart"></div>
                                <div class="col-4" id="top_process_no_time_out_search_chart"></div>
                            </div>
                            <div class="row">
                                <div class="col-12" id="month_section_no_time_out_search_chart"></div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-9">
                                <div class="form-group mb-0">
                                    <label>No Time Out Non-Compliance Table</label>
                                </div>
                                </div>
                                <div class="col-sm-3">
                                <button type="button" class="btn bg-success btn-block" onclick="export_non_compliance('nonComplianceTable')"><i class="fas fa-download"></i> Export Non-Compliance List</button>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-2">
                                <span id="count_view"></span>
                                </div>
                            </div>
                            <div id="nonComplianceTableRes" class="table-responsive" style="max-height: 500px; overflow: auto; display:inline-block;">
                                <table id="nonComplianceTable" class="table table-sm table-head-fixed table-foot-fixed text-nowrap table-hover">
                                <thead style="text-align: center;">
                                    <tr>
                                    <th>#</th>
                                    <th>Employee No.</th>
                                    <th>Full Name</th>
                                    <th>Department</th>
                                    <th>Section</th>
                                    <th>Line No.</th>
                                    <th>Process</th>
                                    <th>No Time Out Count</th>
                                    </tr>
                                </thead>
                                <tbody id="nonComplianceData" style="text-align: center;"></tbody>
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
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
<?php
include 'plugins/footer.php';
include 'plugins/js/non_compliance_script.php';
include '../modals/non_compliance_details.php';
?>