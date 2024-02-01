<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="home.php" class="brand-link">
    <img src="../dist/img/logo.ico" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">EmpMgtSys | Admin</span>
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
          <a href="home.php" class="nav-link">
            <i class="nav-icon fas fa-home"></i>
            <p>
             Home
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="dashboard.php" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li>
        <?php if ($_SESSION['role'] == 'admin') {?>
        <li class="nav-item">
          <a href="accounts.php" class="nav-link">
            <i class="nav-icon fas fa-user-cog"></i>
            <p>
              Account Management
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="employees.php" class="nav-link active">
            <i class="nav-icon fas fa-user"></i>
            <p>
              Employee Masterlist
            </p>
          </a>
        </li> 
        <?php } ?>
        <li class="nav-item">
          <a href="attendances.php" class="nav-link">
            <i class="nav-icon fas fa-tasks"></i>
            <p>
              Attendances
            </p>
          </a>
        </li> 
        <li class="nav-item">
          <a href="shuttle_allocation.php" class="nav-link">
            <i class="nav-icon fas fa-file-alt"></i>
            <p>
              Shuttle Allocation
            </p>
          </a>
        </li> 
        <li class="nav-item">
          <a href="leave_form.php" class="nav-link">
            <i class="nav-icon fas fa-file-alt"></i>
            <p>
              Leave Forms
            </p>
          </a>
        </li> 
        <li class="nav-item">
          <a href="line_support.php" class="nav-link">
            <i class="nav-icon fas fa-users"></i>
            <p>
              Line Support
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
