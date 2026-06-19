<?php
    // email validation
    if (!isset($_GET['submission_id']) || !isset($_GET['approve_key'])) {
        echo 'invalid request';
        exit();
    }

    $submission_id = $_GET['submission_id'];
    $approve_key = $_GET['approve_key'];

    include '../../process/conn.php';

    // check and get all data
    $query = "SELECT 
                    et.emp_transfer_id, 
                    et.emp_transfer_batch_id, 
                    et.approve_key, 
                    et.emp_no, 
                    emp.full_name,
                    emp.provider,
                    emp.position,
                    et.emp_transfer_type, 
                    et.dept_from, 
                    et.section_from, 
                    et.line_no_from, 
                    et.dept_to, 
                    et.section_to, 
                    et.line_no_to, 
                    et.date_effectivity, 
                    et.reason, 
                    et.issued_by, 
                    et.date_issued_by, 
                    et.checked_by, 
                    et.date_checked_by, 
                    et.approved_by, 
                    et.date_approved_by, 
                    et.r_noted_by, 
                    et.r_date_noted_by, 
                    et.r_acknowledged_by, 
                    et.r_date_acknowledged_by, 
                    et.r_approved_by, 
                    et.r_date_approved_by,
                    et.hr_ack,
                    et.hr_date_ack 
                FROM 
                    t_employee_transfer_history et 
                LEFT JOIN m_employees emp 
                    ON emp.emp_no = et.emp_no 
                WHERE 
                    et.emp_transfer_batch_id = ? AND 
                    et.approve_key = ?";

    $stmt = $conn->prepare($query);
    $stmt->execute([$submission_id, $approve_key]);

    $submission_data = $stmt -> fetchAll(PDO::FETCH_ASSOC);

    if (!$submission_data) {
        echo 'invalid request or already proccessed';
        $conn = null;
        exit();
    }

    $conn = null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EmpMgtSys | Employee Transferred List</title>

    <link rel="icon" href="../../dist/img/logo.ico" type="image/x-icon" />
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="../../dist/css/font.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <!-- Sweet Alert -->
    <link rel="stylesheet" href="../../plugins/sweetalert2/dist/sweetalert2.min.css">
    <style>
        .loader {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #536A6D;
            width: 50px;
            height: 50px;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(1080deg);
            }
        }
    </style>
</head>

<body class="hold-transition layout-top-nav accent-primary">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center bg-white">
            <img class="animation__shake elevation-3 p-1 bg-light" src="../../dist/img/logo.webp" alt="Logo"
                height="60" width="60">
            <noscript>
                <br>
                <span>We are facing <strong>Script</strong> issues. Kindly enable <strong>JavaScript</strong>!!!</span>
                <br>
                <span>Call IT Personnel Immediately!!! They will fix it right away.</span>
            </noscript>
        </div>

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand-md navbar-dark bg-gray-dark text-light border-bottom-0">
            <a href="" class="navbar-brand ml-2">
                <img src="../../dist/img/logo.ico" alt="Logo" class="brand-image elevation-3 bg-light p-1"
                    style="opacity: .8">
                <span class="brand-text font-weight-light text-light">EmpMgtSys</span>
            </a>

            <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse"
                aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="" class="nav-link active"><i class="fas fa-home"></i> Homepage</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle"><i class="fas fa-bars"></i> Menu</a>
                        <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                            <li><a href="/emp_mgt/viewer/dashboard.php" class="dropdown-item">Dashboard</a></li>
                            <li><a href="/emp_mgt/viewer/attendance_summary_report.php" class="dropdown-item">Attendance Summary Report</a></li>
                            <li><a href="/emp_mgt/viewer/attendance_monitoring.php" class="dropdown-item">Attendance Monitoring</a></li>
                            <li><a href="/emp_mgt/viewer/line_support.php" class="dropdown-item">Line Support</a></li>
                            <li><a href="/emp_mgt/viewer/time_out_counting.php" class="dropdown-item">Time Out Counting</a></li>
                            <li><a href="/emp_mgt/viewer/certification.php" class="dropdown-item">Employee Process Certification</a></li>
                            <li><a href="/emp_mgt/viewer/non_compliance.php" class="dropdown-item">Non Compliance</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a id="dropdownSubMenu2" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle"><i class="fas fa-file"></i> Work Instruction</a>
                        <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
                            <li><a href="/emp_mgt/wi/Employee Management System Work Instruction rev. 3(Production).xlsx?v=<?php echo time(); ?>" target="_blank" class="dropdown-item">EmpMgtSys Production WI</a></li>
                            <li><a href="/emp_mgt/wi/Employee Management System Work Instruction rev. 2(Viewer).xlsx?v=<?php echo time(); ?>" target="_blank" class="dropdown-item">EmpMgtSys Viewer WI</a></li>
                            <li><a href="/emp_mgt/wi/Employee Management System Work Instruction rev. 2(HR).xlsx?v=<?php echo time(); ?>" target="_blank" class="dropdown-item">EmpMgtSys HR WI</a></li>
                            <li><a href="/emp_mgt/wi/Employee Management System Work Instruction rev. 2(Control Area).xlsx?v=<?php echo time(); ?>" target="_blank" class="dropdown-item">EmpMgtSys Control Area WI</a></li>
                            <li><a href="/emp_mgt/wi/EMS How to Set Line Support.mp4" target="_blank" class="dropdown-item">EmpMgtSys Production How to Set Line Support</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a id="dropdownSubMenu3" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle"><i class="fas fa-sign-in-alt"></i> Login</a>
                        <ul aria-labelledby="dropdownSubMenu3" class="dropdown-menu border-0 shadow">
                            <li><a href="/emp_mgt/admin/" target="_blank" class="dropdown-item">EmpMgtSys Admin Login</a></li>
                            <li><a href="/emp_mgt/user/" target="_blank" class="dropdown-item">EmpMgtSys User Login</a></li>
                            <li><a href="/emp_mgt/clinic/" target="_blank" class="dropdown-item">EmpMgtSys Clinic Login</a></li>
                            <li><a href="/emp_mgt/hr/" target="_blank" class="dropdown-item">EmpMgtSys HR Login</a></li>
                            <li><a href="/emp_mgt/tc/" target="_blank" class="dropdown-item">EmpMgtSys TC Login</a></li>
                            <li><a href="/emp_mgt/control_area/" target="_blank" class="dropdown-item">EmpMgtSys Control Area Login</a></li>
                        </ul>
                    </li>
                </ul>
            </div>

            <!-- Right navbar links -->
            <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="row mb-2 ml-1 mr-1">
                    <div class="col-sm-6">
                        <h1 class="m-0"> Employee Transferred List</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/emp_mgt/">EmpMgtSys</a></li>
                            <li class="breadcrumb-item active">Employee Transferred List</li>
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
                                <h3 class="card-title"><i class="fas fa-users"></i> Employee Transferred Table</h3>
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
                                        <div class="col-sm-2 offset-sm-10">
                                            <button type="button" class="btn bg-secondary btn-block" onclick="export_employee_transferred('lineSupportTable')"><i class="fas fa-download"></i> Export</button>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-2">
                                        <span id="count_view"></span>
                                        </div>
                                    </div>
                                    <div class="table-responsive mb-4" style="max-height: 500px; overflow: auto; display:inline-block;">
                                        <table id="lineSupportTable" class="table table-sm table-head-fixed text-nowrap table-hover">
                                            <thead style="text-align: center;">
                                                <tr>
                                                <th>#</th>
                                                <th>Date Effectivity</th>
                                                <th>Employee No.</th>
                                                <th>Full Name</th>
                                                <th>Provider</th>
                                                <th>Position.</th>
                                                <th>Transfer Type</th>
                                                <th>Department From</th>
                                                <th>Section From</th>
                                                <th>Line No. From</th>
                                                <th>Department To</th>
                                                <th>Section To</th>
                                                <th>Line No. To</th>
                                                <th>Issued By</th>
                                                <th>Checked By</th>
                                                <th>Approved By</th>
                                                <th>Receiving Noted By</th>
                                                <th>Receiving Acknoledged By</th>
                                                <th>Receiving Approved By</th>
                                                <th>HR Acknowledged By</th>
                                                <th>Reason</th>
                                                </tr>
                                            </thead>
                                            <tbody id="empTransferred" style="text-align: center;"><?php 
                                            $c = 0;
                                            
                                            foreach ($submission_data as &$row) {
                                                $c++;

                                                echo '<tr>';

                                                echo '<td>'.$c.'</td>';
                                                echo '<td>'.$row['date_effectivity'].'</td>';
                                                echo '<td>'.$row['emp_no'].'</td>';
                                                echo '<td>'.$row['full_name'].'</td>';
                                                echo '<td>'.$row['provider'].'</td>';
                                                echo '<td>'.$row['position'].'</td>';
                                                echo '<td>'.$row['emp_transfer_type'].'</td>';
                                                echo '<td>'.$row['dept_from'].'</td>';
                                                echo '<td>'.$row['section_from'].'</td>';
                                                echo '<td>'.$row['line_no_from'].'</td>';
                                                echo '<td>'.$row['dept_to'].'</td>';
                                                echo '<td>'.$row['section_to'].'</td>';
                                                echo '<td>'.$row['line_no_to'].'</td>';
                                                echo '<td>'.$row['issued_by'].'</td>';
                                                echo '<td>'.$row['checked_by'].'</td>';
                                                echo '<td>'.$row['approved_by'].'</td>';
                                                echo '<td>'.$row['r_noted_by'].'</td>';
                                                echo '<td>'.$row['r_acknowledged_by'].'</td>';
                                                echo '<td>'.$row['r_approved_by'].'</td>';
                                                echo '<td>'.$row['hr_ack'].'</td>';
                                                echo '<td>'.$row['reason'].'</td>';

                                                echo '</tr>';
                                            } ?></tbody>
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

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline">
                <b>Beta Version</b> 1.1.7
            </div>
            <!-- Default to the left -->
            <strong>Copyright &copy; 2023 Vince Dale Alcantara.</strong>
            All rights reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="../../plugins/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert --->
    <script src="../../plugins/sweetalert2/dist/sweetalert2.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../../dist/js/adminlte.min.js"></script>

    <script type="text/javascript">
        // DOMContentLoaded function
        document.addEventListener("DOMContentLoaded", () => {
            let table_rows = parseInt(document.getElementById("empTransferred").childNodes.length);
			$('#count_view').html("Total: " + table_rows);
        });

        const export_employee_transferred = (table_id, separator = ',') => {
            // Select rows from table_id
            var rows = document.querySelectorAll('table#' + table_id + ' tr');

            // Construct csv
            var csv = [];
            for (var i = 0; i < rows.length; i++) {
                var row = [], cols = rows[i].querySelectorAll('td, th');
                for (var j = 0; j < cols.length; j++) {
                    var data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, '').replace(/(\s\s)/gm, ' ')
                    data = data.replace(/"/g, '""');
                    // Push escaped string
                    row.push('"' + data + '"');
                }
                csv.push(row.join(separator));
            }

            var csv_string = csv.join('\n');

            // Download it
            var filename = 'EmpMgtSys_EmployeeTransferred';
            filename += '_' + new Date().toJSON().slice(0, 10) + '.csv';
            var link = document.createElement('a');
            link.style.display = 'none';
            link.setAttribute('target', '_blank');
            link.setAttribute('href', 'data:text/csv;charset=utf-8,%EF%BB%BF' + encodeURIComponent(csv_string));
            link.setAttribute('download', filename);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>

    <noscript>We are facing Script issues. Kindly enable JavaScript</noscript>

</body>

</html>