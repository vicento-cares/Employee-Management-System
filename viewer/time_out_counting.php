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
                        <h1 class="m-0"> Time Out Counting</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/emp_mgt/">EmpMgtSys</a></li>
                            <li class="breadcrumb-item active">Time Out Counting</li>
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
                                <h3 class="card-title"><i class="fas fa-tasks"></i> Time Out Counting Table</h3>
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
                                    <label>Attendance Date</label>
                                    <input type="date" class="form-control" id="attendance_date_search">
                                    </div>
                                    <div class="col-sm-2">
                                    <label>Shift Group</label>
                                    <select class="form-control" id="shift_group_search" style="width: 100%;" required>
                                        <option selected value="">All</option>
                                        <option value="A">Shift A</option>
                                        <option value="B">Shift B</option>
                                        <option value="ADS">Shift ADS</option>
                                    </select>
                                    </div>
                                    <div class="col-sm-2">
                                    <label>Department</label>
                                    <select id="dept_search" class="form-control">
                                        <option selected value="">All</option>
                                    </select>
                                    </div>
                                    <div class="col-sm-3">
                                    <!-- <label>Group:</label>
                                    <select id="group_search" class="form-control" onchange="get_attendance_summary_report(1)">
                                        <option value="">Select Group</option>
                                    </select> -->
                                    <label>Section</label>
                                    <select id="section_search" class="form-control">
                                        <option value="">Select Section</option>
                                    </select>
                                    </div>
                                    <div class="col-sm-3">
                                    <label>Line No.</label>
                                    <select id="line_no_search" class="form-control">
                                        <option value="">Select Line No.</option>
                                    </select>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-sm-2 offset-sm-4">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn bg-olive btn-block" data-toggle="modal" data-target="#search_multiple_toc"><i class="fas fa-search"></i> Search Multiple 🔥</button>
                                    </div>
                                    <div class="col-sm-3">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn bg-gray-dark btn-block" id="btnSearchTimeOutCounting"><i class="fas fa-search"></i> Search</button>
                                    </div>
                                    <div class="col-sm-3">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn bg-success btn-block" onclick="export_time_out_counting('timeOutCountingTable')"><i class="fas fa-download"></i> Time Out Counting</button>
                                    </div>
                                </div>
                                <div id="multipleDateTimeOutCountingTableRes" class="table-responsive" style="max-height: 500px; overflow: auto; display:inline-block;"></div>
                                <div id="timeOutCountingTableRes" class="table-responsive" style="max-height: 500px; overflow: auto; display:inline-block;">
                                    <table id="timeOutCountingTable" class="table table-sm table-head-fixed table-foot-fixed text-nowrap">
                                    <thead style="text-align: center;">
                                        <tr>
                                        <th>#</th>
                                        <th>Department</th>
                                        <th>Section</th>
                                        <th>WT</th>
                                        <th>0</th>
                                        <th>0.5</th>
                                        <th>1</th>
                                        <th>1.5</th>
                                        <th>2</th>
                                        <th>3</th>
                                        <th>Total</th>
                                        <th>Average OT</th>
                                        </tr>
                                    </thead>
                                    <tbody id="timeOutCountingData" style="text-align: center;"></tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-sm-end">
                                    <div class="dataTables_info" id="timeOutCountingTableInfo" role="status" aria-live="polite"></div>
                                </div>
                                <div class="d-flex justify-content-sm-center">
                                    <button type="button" class="btn bg-gray-dark" id="btnNextPage" style="display:none;" onclick="get_next_page()">Load more</button>
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
include '../modals/search_multiple_toc.php';
include 'plugins/js/time_out_counting_script.php';
?>