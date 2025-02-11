 <footer class="main-footer">
    <strong>Copyright &copy; 2023. Developed by: Vince Dale Alcantara</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Beta Version</b> 1.1.2
    </div>
  </footer>
<?php
//MODALS
include '../modals/logout_modal.php';
include '../modals/new_account.php';
include '../modals/update_account.php';
include '../modals/view_employee.php';
include '../modals/absence_details.php';
include '../modals/attendance_summary_report_details.php';
include '../modals/absence_details2.php';
include '../modals/update_shuttle_route.php';
include '../modals/leave_form_pending.php';
include '../modals/leave_form_history.php';
include '../modals/set_line_support.php';
include '../modals/pending_line_support.php';
include '../modals/line_support_history.php';
include '../modals/admin_verification.php';
?>
<!-- jQuery -->
<script src="../plugins/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="../plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- SweetAlert2 -->
<script type="text/javascript" src="../plugins/sweetalert2/dist/sweetalert2.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.js"></script>

<!-- Idletime Script -->
<script src="../dist/js/idletime.js"></script>

<!-- Admin Verification Script -->
<?php include 'plugins/js/admin_verification_script.php'; ?>

<!-- Notification Script -->
<?php include 'plugins/js/notification_script.php'; ?>

</body>
</html>