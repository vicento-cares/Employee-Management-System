        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand-md navbar-dark bg-gray-dark text-light border-bottom-0">
            <a href="" class="navbar-brand ml-2">
                <img src="../dist/img/logo.ico" alt="Logo" class="brand-image elevation-3 bg-light p-1"
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
                        <a href="/emp_mgt/" class="nav-link"><i class="fas fa-home"></i> Homepage</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle active"><i class="fas fa-bars"></i> Menu</a>
                        <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                            <li><a href="dashboard.php" class="dropdown-item">Dashboard</a></li>
                            <li><a href="attendance_summary_report.php" class="dropdown-item">Attendance Summary Report</a></li>
                            <li><a href="time_out_counting.php" class="dropdown-item active">Time Out Counting</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a id="dropdownSubMenu2" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle"><i class="fas fa-file"></i> Work Instruction</a>
                        <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
                            <li><a href="/emp_mgt/wi/Employee Management System Work Instruction rev. 2(Production).xlsx" target="_blank" class="dropdown-item">EmpMgtSys Production WI</a></li>
                            <li><a href="/emp_mgt/wi/Employee Management System Work Instruction rev. 2(Viewer).xlsx" target="_blank" class="dropdown-item">EmpMgtSys Viewer WI</a></li>
                            <li><a href="/emp_mgt/wi/Employee Management System Work Instruction rev. 2(HR).xlsx" target="_blank" class="dropdown-item">EmpMgtSys HR WI</a></li>
                            <li><a href="/emp_mgt/wi/Employee Management System Work Instruction rev. 1(Control Area).xlsx" target="_blank" class="dropdown-item">EmpMgtSys Control Area WI</a></li>
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