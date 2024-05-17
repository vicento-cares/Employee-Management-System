// IDLE TIME DETECT INACTIVITY
var idleTime = 0;
document.addEventListener("DOMContentLoaded", () => {
  var idleInterval = setInterval(timerIncrement, 60000); //PER 1 MINUTE
  document.addEventListener("mousemove", e => {idleTime = 0});
  document.addEventListener("keypress", e => {idleTime = 0});
  document.addEventListener("mousedown", e => {idleTime = 0});
  document.addEventListener("click", e => {idleTime = 0});
  document.addEventListener("keydown", e => {idleTime = 0});
  document.addEventListener("scroll", e => {idleTime = 0});
});

const timerIncrement = () => {
  idleTime = idleTime + 1;
  if (idleTime > 2) {
    let url_path = window.location.pathname;

    switch (url_path) {
        case "/emp_mgt/admin/home.php":
        case "/emp_mgt/admin/dashboard.php":
        case "/emp_mgt/admin/accounts.php":
        case "/emp_mgt/admin/employees.php":
        case "/emp_mgt/admin/attendances.php":
        case "/emp_mgt/admin/attendance_summary_report.php":
        case "/emp_mgt/admin/shuttle_allocation.php":
        case "/emp_mgt/admin/leave_form.php":
        case "/emp_mgt/admin/line_support.php":
            // Notif Interval
            clearInterval(realtime_load_notif_line_support);
            clearInterval(realtime_load_notif_line_support_req);
            break;
        default:
    }
    
    switch (url_path) {
        case "/emp_mgt/admin/home.php":
            // Home Interval
            clearInterval(realtime_get_attendance_date);
            clearInterval(realtime_get_recent_time_in_out_ds);
            clearInterval(realtime_get_recent_time_in_out_ns);
            clearInterval(realtime_get_recent_time_in_out_ads);
            break;
        case "/emp_mgt/admin/dashboard.php":
            // Dashboard Interval
            clearInterval(realtime_count_emp_dashboard);
            break;
        case "/emp_mgt/admin/leave_form.php":
            // Leave Form Interval
            clearInterval(realtime_get_pending_leave_forms);
            clearInterval(realtime_get_recent_leave_forms_history);
            break;
        case "/emp_mgt/admin/line_support.php":
            // Line Support Interval
            clearInterval(realtime_get_pending_line_support);
            clearInterval(realtime_get_recent_line_support_history);
            break;
        default:
    }

    window.location.href = '../process/logout.php';
  }
}