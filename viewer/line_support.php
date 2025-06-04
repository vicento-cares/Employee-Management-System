<?php
include '../process/server_date_time.php';
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
                        <h1 class="m-0"> Line Support</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/emp_mgt/">EmpMgtSys</a></li>
                            <li class="breadcrumb-item active">Line Support</li>
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
                                <h3 class="card-title"><i class="fas fa-users"></i> Line Support Table</h3>
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
                                    <div class="col-sm-3">
                                    <label>Date From</label>
                                    <input type="date" class="form-control" id="ls_day_from_search">
                                    </div>
                                    <div class="col-sm-3">
                                    <label>Date To</label>
                                    <input type="date" class="form-control" id="ls_day_to_search">
                                    </div>
                                    <div class="col-sm-2">
                                    <label>Employee No.</label>
                                    <input type="text" class="form-control" id="ls_emp_no_search" placeholder="Search" autocomplete="off" maxlength="255">
                                    </div>
                                    <div class="col-sm-4">
                                    <label>Full Name</label>
                                    <input type="text" class="form-control" id="ls_full_name_search" placeholder="Search" autocomplete="off" maxlength="255">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-3">
                                    <label>From Line No.</label>
                                    <select class="form-control" id="ls_line_no_from_search" style="width: 100%;" required>
                                        <option disabled selected value="">Select Line</option>
                                    </select>
                                    </div>
                                    <div class="col-sm-3">
                                    <label>Supported Line No.</label>
                                    <select class="form-control" id="ls_line_no_to_search" style="width: 100%;" required>
                                        <option disabled selected value="">Select Line</option>
                                    </select>
                                    </div>
                                    <div class="col-sm-2">
                                    <label>Shift</label>
                                    <select class="form-control" id="ls_shift_search" style="width: 100%;" required>
                                        <option selected value="">All Shift</option>
                                        <option value="DS">Day Shift - (DS)</option>
                                        <option value="NS">Night Shift - (NS)</option>
                                    </select>
                                    </div>
                                    <div class="col-sm-2">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn bg-success btn-block" onclick="export_line_support('lineSupportTable')"><i class="fas fa-download"></i> Export Line Support</button>
                                    </div>
                                    <div class="col-sm-2">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn bg-gray-dark btn-block" onclick="get_line_support()"><i class="fas fa-search"></i> Search</button>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-2">
                                    <span id="count_view3"></span>
                                    </div>
                                </div>
                                <div class="table-responsive" style="max-height: 500px; overflow: auto; display:inline-block;">
                                    <table id="lineSupportTable" class="table table-sm table-head-fixed text-nowrap table-hover">
                                    <thead style="text-align: center;">
                                        <tr>
                                        <th>#</th>
                                        <th>Picture</th>
                                        <th>Day</th>
                                        <th>Shift</th>
                                        <th>Shift Group</th>
                                        <th>Employee No.</th>
                                        <th>Full Name</th>
                                        <th>Department</th>
                                        <th>Section</th>
                                        <th>Process</th>
                                        <th>From Line No.</th>
                                        <th>Supported Line No.</th>
                                        <th>Set By</th>
                                        <th>Set By No.</th>
                                        <th>Accepted / Rejected By</th>
                                        <th>Accepted / Rejected By No.</th>
                                        <th>Status</th>
                                        <th>Date Updated</th>
                                        </tr>
                                    </thead>
                                    <tbody id="lineSupportData" style="text-align: center;"></tbody>
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
include 'plugins/js/line_support_script.php';
?>