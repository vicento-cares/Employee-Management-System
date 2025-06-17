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
                <h1 class="m-0"> Overall Dashboard</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/emp_mgt/">EmpMgtSys</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
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
                            <h3 class="card-title"><i class="fas fa-chart-bar mr-2"></i>Overall Dashboard as of <?=date('F j, Y')?></h3>
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
                                <div class="col-4">
                                    <table class="table table-bordered table-black-white">
                                        <thead>
                                            <tr>
                                                <th>Count Label</th>
                                                <th>DS</th>
                                                <th>NS</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Present</td>
                                                <td id="od_present_ds">0</td>
                                                <td id="od_present_ns">0</td>
                                                <td id="od_present_total">0</td>
                                            </tr>
                                            <tr>
                                                <td>Registered Employees</td>
                                                <td></td>
                                                <td></td>
                                                <td id="od_registered_total">0</td>
                                            </tr>
                                            <tr>
                                                <td>Absent Rate</td>
                                                <td></td>
                                                <td></td>
                                                <td id="od_absent_rate">0</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-8" id="daily_absent_rate_chart"></div>
                            </div>
                            <div class="row ml-2 mb-2">
                                <div class="form-group mb-0">
                                    <label><b>Daily Absent Rate Trend by Providers</b></label>
                                </div>
                            </div>
                            <div class="row" id="daily_absent_rate_provider_chart"></div>
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
                            <h3 class="card-title"><i class="fas fa-chart-bar mr-2"></i>Old Dashboard Count</h3>
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
                                    <input type="date" class="form-control" id="attendance_date_search"
                                        onchange="count_emp_dashboard()">
                                </div>
                                <div class="col-sm-2">
                                    <label>Department</label>
                                    <select id="dept_master_search" class="form-control"
                                        onchange="count_emp_dashboard()">
                                        <option value="">Select Department</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <!-- <label>Group</label>
                            <select id="group_search" class="form-control" onchange="count_emp_dashboard()">
                                <option value="">Select Group</option>
                            </select> -->
                                    <label>Section</label>
                                    <select id="section_master_search" class="form-control"
                                        onchange="count_emp_dashboard()">
                                        <option value="">Select Section</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <label>Line No.</label>
                                    <select id="line_no_master_search" class="form-control"
                                        onchange="count_emp_dashboard()">
                                        <option value="">Select Line No.</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn bg-gray-dark btn-block"
                                        onclick="count_emp_dashboard()"><i class="fas fa-search"></i> Search</button>
                                </div>
                                <div class="col-sm-2">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn bg-success btn-block"
                                        onclick="export_dashboard()"><i class="fas fa-download"></i> Export</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="card card-gray-dark">
                                        <div class="card-header">
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="small-box bg-white">
                                                        <div class="inner mb-3">
                                                            <h2 id="count_emp_dashboard_value_total"></h2>
                                                            <h4><b>TOTAL MP</b></h4>
                                                            <h4>Employees</h4>
                                                        </div>
                                                        <div class="icon">
                                                            <i class="ion ion-person-stalker"></i>
                                                        </div>
                                                        <div class="small-box-footer"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="small-box bg-white">
                                                        <div class="inner mb-3">
                                                            <h2 id="count_emp_dashboard_value_total_percentage"></h2>
                                                            <h4><b>Percentage</b></h4>
                                                            <h4>&nbsp;</h4>
                                                        </div>
                                                        <div class="icon">
                                                            <i class="ion ion-person-stalker"></i>
                                                        </div>
                                                        <div class="small-box-footer"></div>
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
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="card card-gray-dark card-tabs">
                                        <div class="card-header p-0 border-bottom-0">
                                            <ul class="nav nav-tabs" id="dashboards-tab" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="dashboards-1-tab" data-toggle="pill"
                                                        href="#dashboards-1" role="tab" aria-controls="dashboards-1"
                                                        aria-selected="true">Shift Group A</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="dashboards-2-tab" data-toggle="pill"
                                                        href="#dashboards-2" role="tab" aria-controls="dashboards-2"
                                                        aria-selected="false">Shift Group B</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="dashboards-3-tab" data-toggle="pill"
                                                        href="#dashboards-3" role="tab" aria-controls="dashboards-3"
                                                        aria-selected="false">Shift Group
                                                        ADS</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="card-body">
                                            <div class="tab-content" id="dashboards-tabContent">
                                                <div class="tab-pane fade show active" id="dashboards-1" role="tabpanel"
                                                    aria-labelledby="dashboards-1-tab">

                                                    <div id="count_emp_dashboard_ds" class="row mb-2">
                                                        <div class="col-3">
                                                            <div class="small-box bg-white">
                                                                <div class="inner mb-3">
                                                                    <div class="row">
                                                                        <div class="col-md-6 col-sm-12">
                                                                            <h2 id="count_emp_dashboard_value_ds"></h2>
                                                                            <h4><b>TOTAL MP</b></h4>
                                                                            <h4>Employees</h4>
                                                                        </div>
                                                                        <div class="col-md-6 col-sm-12">
                                                                            <h2
                                                                                id="count_emp_dashboard_value_ds_percentage">
                                                                            </h2>
                                                                            <h4><b>Percentage</b></h4>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="small-box-footer"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-3">
                                                            <div class="small-box bg-success">
                                                                <div class="inner mb-3">
                                                                    <h2 id="count_emp_dashboard_present_value_ds"></h2>
                                                                    <h4><b>PRESENT MP</b></h4>
                                                                    <h4>Employees</h4>
                                                                </div>
                                                                <div class="icon">
                                                                    <i class="ion ion-person-stalker"></i>
                                                                </div>
                                                                <div class="small-box-footer"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-3">
                                                            <div class="small-box bg-secondary">
                                                                <div class="inner mb-3">
                                                                    <h2 id="count_emp_dashboard_support_value_ds"></h2>
                                                                    <h4><b>SUPPORT MP</b></h4>
                                                                    <h4>Employees</h4>
                                                                </div>
                                                                <div class="icon">
                                                                    <i class="ion ion-person-stalker"></i>
                                                                </div>
                                                                <div class="small-box-footer"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-3">
                                                            <div class="small-box bg-danger">
                                                                <div class="inner mb-3">
                                                                    <h2 id="count_emp_dashboard_absent_value_ds"></h2>
                                                                    <h4><b>ABSENT MP</b></h4>
                                                                    <h4>Employees</h4>
                                                                </div>
                                                                <div class="icon">
                                                                    <i class="ion ion-person-stalker"></i>
                                                                </div>
                                                                <div class="small-box-footer"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="count_emp_provider_dashboard_ds" class="row mb-2">
                                                    </div>

                                                </div>
                                                <div class="tab-pane fade" id="dashboards-2" role="tabpanel"
                                                    aria-labelledby="dashboards-2-tab">

                                                    <div id="count_emp_dashboard_ns" class="row mb-2">
                                                        <div class="col-3">
                                                            <div class="small-box bg-white">
                                                                <div class="inner mb-3">
                                                                    <div class="row">
                                                                        <div class="col-md-6 col-sm-12">
                                                                            <h2 id="count_emp_dashboard_value_ns"></h2>
                                                                            <h4><b>TOTAL MP</b></h4>
                                                                            <h4>Employees</h4>
                                                                        </div>
                                                                        <div class="col-md-6 col-sm-12">
                                                                            <h2
                                                                                id="count_emp_dashboard_value_ns_percentage">
                                                                            </h2>
                                                                            <h4><b>Percentage</b></h4>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="small-box-footer"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-3">
                                                            <div class="small-box bg-success">
                                                                <div class="inner mb-3">
                                                                    <h2 id="count_emp_dashboard_present_value_ns"></h2>
                                                                    <h4><b>PRESENT MP</b></h4>
                                                                    <h4>Employees</h4>
                                                                </div>
                                                                <div class="icon">
                                                                    <i class="ion ion-person-stalker"></i>
                                                                </div>
                                                                <div class="small-box-footer"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-3">
                                                            <div class="small-box bg-secondary">
                                                                <div class="inner mb-3">
                                                                    <h2 id="count_emp_dashboard_support_value_ns"></h2>
                                                                    <h4><b>SUPPORT MP</b></h4>
                                                                    <h4>Employees</h4>
                                                                </div>
                                                                <div class="icon">
                                                                    <i class="ion ion-person-stalker"></i>
                                                                </div>
                                                                <div class="small-box-footer"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-3">
                                                            <div class="small-box bg-danger">
                                                                <div class="inner mb-3">
                                                                    <h2 id="count_emp_dashboard_absent_value_ns"></h2>
                                                                    <h4><b>ABSENT MP</b></h4>
                                                                    <h4>Employees</h4>
                                                                </div>
                                                                <div class="icon">
                                                                    <i class="ion ion-person-stalker"></i>
                                                                </div>
                                                                <div class="small-box-footer"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="count_emp_provider_dashboard_ns" class="row mb-2">
                                                    </div>

                                                </div>
                                                <div class="tab-pane fade" id="dashboards-3" role="tabpanel"
                                                    aria-labelledby="dashboards-3-tab">

                                                    <div id="count_emp_dashboard_ads" class="row mb-2">
                                                        <div class="col-3">
                                                            <div class="small-box bg-white">
                                                                <div class="inner mb-3">
                                                                    <div class="row">
                                                                        <div class="col-md-6 col-sm-12">
                                                                            <h2 id="count_emp_dashboard_value_ads"></h2>
                                                                            <h4><b>TOTAL MP</b></h4>
                                                                            <h4>Employees</h4>
                                                                        </div>
                                                                        <div class="col-md-6 col-sm-12">
                                                                            <h2
                                                                                id="count_emp_dashboard_value_ads_percentage">
                                                                            </h2>
                                                                            <h4><b>Percentage</b></h4>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="small-box-footer"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-3">
                                                            <div class="small-box bg-success">
                                                                <div class="inner mb-3">
                                                                    <h2 id="count_emp_dashboard_present_value_ads"></h2>
                                                                    <h4><b>PRESENT MP</b></h4>
                                                                    <h4>Employees</h4>
                                                                </div>
                                                                <div class="icon">
                                                                    <i class="ion ion-person-stalker"></i>
                                                                </div>
                                                                <div class="small-box-footer"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-3">
                                                            <div class="small-box bg-secondary">
                                                                <div class="inner mb-3">
                                                                    <h2 id="count_emp_dashboard_support_value_ads"></h2>
                                                                    <h4><b>SUPPORT MP</b></h4>
                                                                    <h4>Employees</h4>
                                                                </div>
                                                                <div class="icon">
                                                                    <i class="ion ion-person-stalker"></i>
                                                                </div>
                                                                <div class="small-box-footer"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-3">
                                                            <div class="small-box bg-danger">
                                                                <div class="inner mb-3">
                                                                    <h2 id="count_emp_dashboard_absent_value_ads"></h2>
                                                                    <h4><b>ABSENT MP</b></h4>
                                                                    <h4>Employees</h4>
                                                                </div>
                                                                <div class="icon">
                                                                    <i class="ion ion-person-stalker"></i>
                                                                </div>
                                                                <div class="small-box-footer"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="count_emp_provider_dashboard_ads" class="row mb-2">
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card -->
                                    </div>
                                </div>
                            </div>
                            <!-- /.row -->
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
include 'plugins/js/dashboard_script.php';
?>