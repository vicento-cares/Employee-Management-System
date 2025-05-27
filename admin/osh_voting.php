<?php include 'plugins/navbar.php';?>
<?php include 'plugins/sidebar/admin_bar.php';?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">OSH Voting</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="home.php">Home</a></li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row mb-4">
        <div class="col-sm-6">
          <label>Employee No.</label>
          <input type="text" class="form-control" id="emp_no_osh" name="emp_no_osh" placeholder="Scan Here" oncopy="return false" onpaste="return false" autofocus autocomplete="off" maxlength="20" required>
        </div>
        <div class="col-sm-6">
          <label>Employee Name</label>
          <input type="text" class="form-control" id="full_name_osh" name="full_name_osh" oncopy="return false" onpaste="return false" autocomplete="off" disabled>
        </div>
      </div>
      <div class="row d-none" id="osh_candidates_list"></div>
      <!-- /.row -->
    </div>
  </section>
</div>

<?php include 'plugins/footer.php';?>
<?php include 'plugins/js/osh_voting_script.php';?>