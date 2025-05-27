<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="home.php" class="brand-link">
    <img src="../dist/img/logo.ico" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">EmpMgtSys | HR</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="../dist/img/user.png" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="home.php" class="d-block"><?= htmlspecialchars($_SESSION['full_name']); ?></a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
        <li class="nav-item">
          <?php if ($_SERVER['REQUEST_URI'] == "/emp_mgt/hr/home.php") { ?>
          <a href="home.php" class="nav-link active">
          <?php } else { ?>
          <a href="home.php" class="nav-link">
          <?php } ?>
            <i class="nav-icon fas fa-home"></i>
            <p>
              Home
            </p>
          </a>
        </li>
        <li class="nav-item">
          <?php if ($_SERVER['REQUEST_URI'] == "/emp_mgt/hr/dashboard.php") { ?>
          <a href="dashboard.php" class="nav-link active">
          <?php } else { ?>
          <a href="dashboard.php" class="nav-link">
          <?php } ?>
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li>
        <li class="nav-item">
          <?php if ($_SERVER['REQUEST_URI'] == "/emp_mgt/hr/accounts.php") { ?>
          <a href="accounts.php" class="nav-link active">
          <?php } else { ?>
          <a href="accounts.php" class="nav-link">
          <?php } ?>
            <i class="nav-icon fas fa-user-cog"></i>
            <p>
              Account Management
            </p>
          </a>
        </li>
        <li class="nav-item">
          <?php if ($_SERVER['REQUEST_URI'] == "/emp_mgt/hr/employees.php") { ?>
          <a href="employees.php" class="nav-link active">
          <?php } else { ?>
          <a href="employees.php" class="nav-link">
          <?php } ?>
            <i class="nav-icon fas fa-user"></i>
            <p>
              Employee Masterlist
            </p>
          </a>
        </li>
        <li class="nav-item">
          <?php if ($_SERVER['REQUEST_URI'] == "/emp_mgt/hr/attendances.php") { ?>
          <a href="attendances.php" class="nav-link active">
          <?php } else { ?>
          <a href="attendances.php" class="nav-link">
          <?php } ?>
            <i class="nav-icon fas fa-tasks"></i>
            <p>
              Attendances
            </p>
          </a>
        </li>
        <li class="nav-item">
          <?php if ($_SERVER['REQUEST_URI'] == "/emp_mgt/hr/non_compliance.php") { ?>
          <a href="non_compliance.php" class="nav-link active">
          <?php } else { ?>
          <a href="non_compliance.php" class="nav-link">
          <?php } ?>
            <i class="nav-icon fas fa-tasks"></i>
            <p>
              Non-Compliance
            </p>
          </a>
        </li>
        <li class="nav-item">
          <?php if ($_SERVER['REQUEST_URI'] == "/emp_mgt/hr/individual_attendances.php") { ?>
          <a href="individual_attendances.php" class="nav-link active">
          <?php } else { ?>
          <a href="individual_attendances.php" class="nav-link">
          <?php } ?>
            <i class="nav-icon fas fa-tasks"></i>
            <p>
              Individual Attendances
            </p>
          </a>
        </li>
        <li class="nav-item">
          <?php if ($_SERVER['REQUEST_URI'] == "/emp_mgt/hr/access_locations.php") { ?>
          <a href="access_locations.php" class="nav-link active">
          <?php } else { ?>
          <a href="access_locations.php" class="nav-link">
          <?php } ?>
            <i class="nav-icon fas fa-user-shield"></i>
            <p>
              Access Locations
            </p>
          </a>
        </li>

        <!-- revisions: jay. quick export button of osh voting -->
        <li class="nav-item">
          <a href="osh_rep_download.php" class="nav-link">
            <i class="fas fa-download"></i>
            <p>
              Download OSH Report
            </p>
          </a>
        </li>

        <?php include 'logout.php'; ?>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>