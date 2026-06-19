<?php require '../process/login.php';

if (isset($_SESSION['emp_no'])) {
  if ($_SESSION['role'] == 'admin') {
    header('location: home.php');
    exit;
  } else if ($_SESSION['role'] == 'user') {
    header('location: home.php');
    exit;
  }
}
?>

<?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['password']) || empty($_POST['password'])) {

      $_SESSION['registration_alert'] = <<<HTML
        <div class="alert alert-danger">
          Registration Failed. No Admin Key Typed
        </div>
      HTML;

    } else if ($_POST['password'] != 'Team@It') {

      $_SESSION['registration_alert'] = <<<HTML
        <div class="alert alert-danger">
          Registration Failed. Wrong Admin Key
        </div>
      HTML;

    } else {
      //do some sketchy insert
      $stmt = $conn -> prepare("INSERT INTO m_access_locations (dept, section, line_no, ip) VALUES (:dept, :section, :line_no, :ip)");
      $stmt -> execute([
        "dept" => $_POST['dept'],
        "section" => $_POST['section'],
        "line_no" => $_POST['line_no'],
        "ip" => $_POST['ip_address']
      ]);

      $_SESSION['registration_alert'] = <<<HTML
        <div class="alert alert-success">
          Registration Successful. Attempt to Login Now
        </div>
      HTML;
    }

    // reset request method from post to get again with this
    // removes the browser refresh back to a post request
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Employee Management System - Admin</title>

  <link rel="icon" href="../dist/img/logo.ico" type="image/x-icon" />
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="../dist/css/font.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
  <div class="position-fixed m-1 d-flex flex-column" style="top:0;right:0">
    <div class="d-flex justify-content-end">
      <button class="btn btn-sm btn-outline-primary" style="opacity:0;" onclick="document.getElementById('register_access_form').classList.remove('d-none');">API</button>
    </div>
    <?php
      if (isset($_SESSION['registration_alert'])) {
        echo $_SESSION['registration_alert'];
        unset($_SESSION['registration_alert']);
      }
    ?>
    <style>
      #register_access_form .form-group {
        margin-bottom: 0.25rem;
      }
    </style>
    <form method="POST" id="register_access_form" class="d-none" autocomplete="off">
      <div class="p-1" style="width:50vh;">
        <div class="form-group">
          <label for="dept" class="small">Department</label>
          <input type="text" list="dept-list" class="form-control form-control-sm" id="dept" name="dept" required>
          <datalist id="dept-list">
            <?php
              $stmt = $conn ->query("SELECT DISTINCT dept from m_access_locations");
              while (($row = $stmt ->fetch(PDO::FETCH_COLUMN)) !== false) {
                echo <<<HTML
                  <option>{$row}</option>
                HTML;
              }
            ?>
          </datalist>
          <small class="form-text text-muted small">Choose from the list set or type your own</small>
        </div>
        <div class="form-group">
          <label for="section" class="small">Section</label>
          <input type="text" list="section-list" class="form-control form-control-sm" id="section" name="section" required>
          <datalist id="section-list">
            <?php
              $stmt = $conn ->query("SELECT DISTINCT section from m_access_locations");
              while (($row = $stmt->fetch(PDO::FETCH_COLUMN)) !== false) {
                echo <<<HTML
                  <option>{$row}</option>
                HTML;
              }
            ?>
          </datalist>
          <small class="form-text text-muted small">Choose from the list set or type your own</small>
        </div>
        <div class="form-group">
          <label for="line_no" class="small">Line</label>
          <input type="text" list="line-list" class="form-control form-control-sm" id="line_no" name="line_no" required>
          <datalist id="line-list">
            <?php
              $stmt = $conn ->query("SELECT DISTINCT line_no from m_access_locations");
              while (($row = $stmt->fetch(PDO::FETCH_COLUMN)) !== false) {
                echo <<<HTML
                  <option>{$row}</option>
                HTML;
              }
            ?>
          </datalist>
          <small class="form-text text-muted small">Choose from the list set or type your own</small>
        </div>
        <div class="form-group">
          <label for="ip_address" class="small">IP Address</label>
          <input type="text" class="form-control form-control-sm" id="ip_address" name="ip_address" pattern="^172\.25\.\d{1,3}\.\d{1,3}$" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>" required>
        </div>
        <div class="form-group">
          <label for="ip_address" class="small">Admin Key</label>
          <input type="password" name="password" class="form-control form-control-sm" autocomplete="new-password">
        </div>
        <div class="d-flex justify-content-center">
          <button class="btn btn-sm btn-success">Register Access Location</button>
        </div>
      </div>
    </form>
  </div>
  <div class="login-box">
    <div class="login-logo">
      <img src="../dist/img/logo.webp" style="height:100px;">
      <h2>Employee Management System - Admin</h2>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg"><b>Scan QR Code</b></p>

        <form action="" method="POST" id="login_form">
          <div class="input-group mb-3">
            <input type="password" class="form-control" id="emp_no" name="emp_no" placeholder="ID Number" oncopy="return false" onpaste="return false" autofocus autocomplete="off" maxlength="50" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>   
          <!-- /.col -->
          <div class="input-group mb-3">
            <button type="submit" class="btn btn-primary btn-block" name="login_btn" value="login">Sign In</button>
          </div>
          <!-- /.col -->
        </form>
      </div>
    </div>
  </div>
</body>

<!-- jQuery -->
<script src="../plugins/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>

<script>
  // DOMContentLoaded function
  document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("emp_no").focus();
  });

  var delay = (function(){
    var timer = 0;
    return function(callback, ms){
      clearTimeout (timer);
      timer = setTimeout(callback, ms);
    };
  })();

  $("#emp_no").on("input", function() {
    delay(function(){
      if ($("#emp_no").val().length < 51) {
        $("#emp_no").val("");
      }
    }, 100);
  });
</script>

</body>
</html>
