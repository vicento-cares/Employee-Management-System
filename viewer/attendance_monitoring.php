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
                        <h1 class="m-0"> Attendance Monitoring</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/emp_mgt/">EmpMgtSys</a></li>
                            <li class="breadcrumb-item active">Attendance Monitoring</li>
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
                                <h3 class="card-title"><i class="fas fa-tasks"></i> Attendance Monitoring Report by Section</h3>
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
                                    <form id="attendance_monitoring_form">
                                        <div class="row mb-4">
                                        <div class="col-sm-2">
                                            <label>Year</label>
                                            <select id="am_year_search" class="form-control" required>
                                                <option selected value="">Select Year</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-2">
                                            <label>Month</label>
                                            <select id="am_month_search" class="form-control" required>
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
                                    <div class="row mt-2">
                                        <div class="col-sm-9">
                                        <div class="form-group mb-0">
                                            <label>Daily Absent Monitoring Table</label>
                                        </div>
                                        </div>
                                        <div class="col-sm-3">
                                        <button type="button" class="btn bg-success btn-block" onclick="export_attendance_monitoring('absentMonTable')"><i class="fas fa-download"></i> Export Absent Monitoring</button>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-2">
                                        <span id="count_view_absent_mon"></span>
                                        </div>
                                    </div>
                                    <div id="absentMonTableRes" class="table-responsive" style="max-height: 500px; overflow: auto; display:inline-block;">
                                        <table id="absentMonTable" class="table table-sm table-head-fixed table-foot-fixed text-nowrap">
                                        </table>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-sm-9">
                                        <div class="form-group mb-0">
                                            <label>Present MP Count Monitoring Table</label>
                                        </div>
                                        </div>
                                        <div class="col-sm-3">
                                        <button type="button" class="btn bg-success btn-block" onclick="export_attendance_monitoring('presentMonTable')"><i class="fas fa-download"></i> Export Present Monitoring</button>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-2">
                                        <span id="count_view_present_mon"></span>
                                        </div>
                                    </div>
                                    <div id="presentMonTableRes" class="table-responsive" style="max-height: 500px; overflow: auto; display:inline-block;">
                                        <table id="presentMonTable" class="table table-sm table-head-fixed table-foot-fixed text-nowrap">
                                        </table>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-sm-9">
                                        <div class="form-group mb-0">
                                            <label>Absent Rate Monitoring Table</label>
                                        </div>
                                        </div>
                                        <div class="col-sm-3">
                                        <button type="button" class="btn bg-success btn-block" onclick="export_attendance_monitoring('absentRateMonTable')"><i class="fas fa-download"></i> Export Absent Rate Monitoring</button>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-2">
                                        <span id="count_view_absent_rate_mon"></span>
                                        </div>
                                    </div>
                                    <div id="absentRateMonTableRes" class="table-responsive" style="max-height: 500px; overflow: auto; display:inline-block;">
                                        <table id="absentRateMonTable" class="table table-sm table-head-fixed table-foot-fixed text-nowrap">
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
include 'plugins/js/attendance_monitoring_script.php';
?>