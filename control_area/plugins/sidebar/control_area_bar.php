<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="employees.php" class="brand-link">
    <img src="../dist/img/logo.ico" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">EmpMgtSys | Control</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="../dist/img/user.png" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="employees.php" class="d-block"><?=htmlspecialchars($_SESSION['full_name']);?></a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
        <li class="nav-item">
          <?php if ($_SERVER['REQUEST_URI'] == "/emp_mgt/control_area/employees.php") { ?>
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
          <?php if ($_SERVER['REQUEST_URI'] == "/emp_mgt/control_area/certification.php") { ?>
          <a href="certification.php" class="nav-link active">
          <?php } else { ?>
          <a href="certification.php" class="nav-link">
          <?php } ?>
            <i class="nav-icon fas fa-tasks"></i>
            <p>
              Employee Process Certification
            </p>
          </a>
        </li>
        <?php include 'logout.php' ;?>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
