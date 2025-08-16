<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EmpMgtSys | Homepage</title>

    <link rel="icon" href="dist/img/logo.ico" type="image/x-icon" />
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="dist/css/font.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- Sweet Alert -->
    <link rel="stylesheet" href="plugins/sweetalert2/dist/sweetalert2.min.css">
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
            <img class="animation__shake elevation-3 p-1 bg-light" src="dist/img/logo.webp" alt="Logo"
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
                <img src="dist/img/logo.ico" alt="Logo" class="brand-image elevation-3 bg-light p-1"
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
                            <li><a href="viewer/dashboard.php" class="dropdown-item">Dashboard</a></li>
                            <li><a href="viewer/attendance_summary_report.php" class="dropdown-item">Attendance Summary Report</a></li>
                            <li><a href="viewer/attendance_monitoring.php" class="dropdown-item">Attendance Monitoring</a></li>
                            <li><a href="viewer/line_support.php" class="dropdown-item">Line Support</a></li>
                            <li><a href="viewer/time_out_counting.php" class="dropdown-item">Time Out Counting</a></li>
                            <li><a href="viewer/certification.php" class="dropdown-item">Employee Process Certification</a></li>
                            <li><a href="viewer/non_compliance.php" class="dropdown-item">Non Compliance</a></li>
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
                            <li><a href="/emp_mgt/ga/" target="_blank" class="dropdown-item">EmpMgtSys GA Login</a></li>
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
            
            <!-- Main content -->
            <div class="content">
                <div class="row d-flex no-block justify-content-center align-items-center vh-100">
                    <img class="animation__wobble img-circle elevation-3 p-1 bg-white" src="dist/img/logo.webp" alt="Logo" height="240" width="240">
                    <h1 class="ml-5"><b>Employee Management System</b></h1>
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

    <!-- News Modal -->
    <input type="hidden" id="modal_stat" value="Hide">
    <div class="modal fade" id="news_window" data-backdrop="static" data-keyboard="false" style="z-index: 10000 !important;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
        <div class="modal-header bg-gray-dark">
            <h4 class="modal-title" id="news_window_title">Reminders</h4>
            <button type="button" class="close" aria-label="Close" id="news_window_close" onclick="close_modal_news_window()">
            <span class="text-white" aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body" id="news_window_body">
            <!--  News and Updates Section  -->
            <div id="carouselExampleFade" class="carousel slide carousel-fade" data-ride="carousel" data-interval="10000" data-pause="false">
            <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="d-block w-100" src="dist/img/ProperShutdown/1.png"
                alt="slide1">
                </div>
                <div class="carousel-item">
                <img class="d-block w-100" src="dist/img/ProperShutdown/2.png"
                alt="slide2">
                </div>
                <div class="carousel-item">
                <img class="d-block w-100" src="dist/img/ProperShutdown/3.png"
                alt="slide3">
                </div>
                <div class="carousel-item">
                <img class="d-block w-100" src="dist/img/ProperShutdown/4.jpg"
                alt="slide4">
                <!-- <div class="carousel-item active">
                <img class="d-block w-100" src="dist/img/HW_S/1.jpg"
                alt="slide1">
                </div>
                <div class="carousel-item">
                <img class="d-block w-100" src="dist/img/HW_S/2.jpg"
                alt="slide2">
                </div>
                <div class="carousel-item">
                <img class="d-block w-100" src="dist/img/HW_S/3.jpg"
                alt="slide3">
                </div>
                <div class="carousel-item">
                <img class="d-block w-100" src="dist/img/HW_S/4.jpg"
                alt="slide4">
                </div>
                <div class="carousel-item">
                <img class="d-block w-100" src="dist/img/HW_S/5.jpg"
                alt="slide5">
                </div>
                <div class="carousel-item">
                <img class="d-block w-100" src="dist/img/HW_S/6.jpg"
                alt="slide6">
                </div>
                <div class="carousel-item">
                <img class="d-block w-100" src="dist/img/HW_S/7.jpg"
                alt="slide7">
                </div>
                <div class="carousel-item">
                <img class="d-block w-100" src="dist/img/HW_S/8.jpg"
                alt="slide8">
                </div>
                <div class="carousel-item">
                <img class="d-block w-100" src="dist/img/HW_S/9.jpg"
                alt="slide9">
                </div>
                <div class="carousel-item">
                <img class="d-block w-100" src="dist/img/HW_S/10.jpg"
                alt="slide10">
                </div> -->
                <!-- Valentines -->
                <!-- <div class="carousel-item active">
                <img class="d-block w-100" src="dist/img/feb/1.jpg"
                alt="slide1">
                </div>
                <div class="carousel-item">
                <img class="d-block w-100" src="dist/img/feb/2.jpg"
                alt="slide2">
                </div>
                <div class="carousel-item">
                <img class="d-block w-100" src="dist/img/feb/3.jpg"
                alt="slide3">
                </div>
                <div class="carousel-item">
                <img class="d-block w-100" src="dist/img/feb/4.jpg"
                alt="slide4">
                </div>
                <div class="carousel-item">
                <img class="d-block w-100" src="dist/img/feb/6.jpg"
                alt="slide6">
                </div>
                <div class="carousel-item">
                <img class="d-block w-100" src="dist/img/feb/7.jpg"
                alt="slide7">
                </div>
                <div class="carousel-item">
                <img class="d-block w-100" src="dist/img/feb/8.jpg"
                alt="slide8">
                </div>
                <div class="carousel-item">
                <img class="d-block w-100" src="dist/img/feb/9.jpg"
                alt="slide9">
                </div>
                <div class="carousel-item">
                <img class="d-block w-100" src="dist/img/feb/10.png"
                alt="slide10">
                </div>
                <div class="carousel-item">
                <img class="d-block w-100" src="dist/img/feb/11.jpg"
                alt="slide11">
                </div> -->
                <!-- Christmas -->
                <!-- <div class="carousel-item active">
                <img class="d-block w-100" src="dist/img/1/christmas1.jpg"
                alt="slide1">
                </div>
                <div class="carousel-item">
                <img class="d-block w-100" src="dist/img/1/christmas2.jpg"
                alt="slide2">
                </div>
                <div class="carousel-item">
                <img class="d-block w-100" src="dist/img/1/christmas3.jpg"
                alt="slide3">
                </div>
                <div class="carousel-item">
                <img class="d-block w-100" src="dist/img/1/christmas4.jpg"
                alt="slide4">
                </div>
                <div class="carousel-item">
                <img class="d-block w-100" src="dist/img/1/christmas5.jpg"
                alt="slide5">
                </div>
                <div class="carousel-item">
                <img class="d-block w-100" src="dist/img/1/christmas6.jpg"
                alt="slide6">
                </div>
                <div class="carousel-item">
                <img class="d-block w-100" src="dist/img/1/christmas8.jpg"
                alt="slide7">
                </div>
                <div class="carousel-item">
                <img class="d-block w-100" src="dist/img/1/christmas9.jpg"
                alt="slide8">
                </div>
                <div class="carousel-item">
                <img class="d-block w-100" src="dist/img/1/christ1.jpg"
                alt="slide9">
                </div>
                <div class="carousel-item">
                <img class="d-block w-100" src="dist/img/1/christ2.jpg"
                alt="slide10">
                </div>
                <div class="carousel-item">
                <img class="d-block w-100" src="dist/img/1/christ3.jpg"
                alt="slide11">
                </div>
                <div class="carousel-item">
                <img class="d-block w-100" src="dist/img/1/epiphany1.jpg"
                alt="slide12">
                </div>
                <div class="carousel-item">
                <img class="d-block w-100" src="dist/img/1/epiphany2.jpg"
                alt="slide13">
                </div> -->
            </div>
            <a class="carousel-control-prev text-dark" href="#carouselExampleFade" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon text-dark" aria-hidden="true"></span>
                <span class="sr-only text-dark">Previous</span>
            </a>
            <a class="carousel-control-next text-dark" href="#carouselExampleFade" role="button" data-slide="next">
                <span class="carousel-control-next-icon text-dark" aria-hidden="true"></span>
                <span class="sr-only text-dark">Next</span>
            </a>
            </div>
            <!--  End of News and Updates Section  -->
        </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="plugins/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert --->
    <script src="plugins/sweetalert2/dist/sweetalert2.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>

    <script type="text/javascript">
        // DOMContentLoaded function
        document.addEventListener("DOMContentLoaded", () => {
            activityWatcher();
        });

        const close_modal_news_window = () => {
            $('#news_window').modal('toggle');
            document.getElementById('modal_stat').value='Hide';
        }

        const activityWatcher = () => {
            var secondsSinceLastActivity = 0;
            // Maximum 30sec of inactivity
            var maxInactivity = 30;
            setInterval(() => {
                secondsSinceLastActivity++;
                if (secondsSinceLastActivity > maxInactivity) {
                    var modal_stat = document.getElementById("modal_stat").value;

                    if (modal_stat == "Hide") {
                    $("#news_window").modal();
                    document.getElementById("modal_stat").value="Show";
                    }
                }
            }, 1000);

            const activity = () => {
                secondsSinceLastActivity = 0;
            }

            var activityEvents = ['mousedown', 'mousemove', 'keydown','scroll', 'touchstart'];
            
            activityEvents.forEach(eventName => {
                document.addEventListener(eventName, activity, true);
            });
        }
    </script>

    <noscript>We are facing Script issues. Kindly enable JavaScript</noscript>

</body>

</html>