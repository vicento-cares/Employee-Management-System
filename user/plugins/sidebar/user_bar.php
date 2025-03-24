<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="home.php" class="brand-link">
    <img src="../dist/img/logo.ico" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">EmpMgtSys | User</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="../dist/img/user.png" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="home.php" class="d-block"><?=htmlspecialchars($_SESSION['full_name']);?></a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
        <li class="nav-item">
          <?php if ($_SERVER['REQUEST_URI'] == "/emp_mgt/user/home.php") { ?>
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
          <?php if ($_SERVER['REQUEST_URI'] == "/emp_mgt/user/individual_attendances.php") { ?>
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
        <?php include 'logout.php' ;?>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
