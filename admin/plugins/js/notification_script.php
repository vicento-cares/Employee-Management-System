<script type="text/javascript">
  // Notification Global Variables for Realtime
  var realtime_load_notif_line_support;
  var realtime_load_notif_line_support_req;

  // Notifications
  const load_notif_line_support = () => {
    $.ajax({
      url: '../process/admin/notification/notif_p.php',
      type: 'POST',
      cache: false,
      data: {
        method: 'count_notif_line_support'
      },
      beforeSend: (jqXHR, settings) => {
        jqXHR.url = settings.url;
        jqXHR.type = settings.type;
      },
      success: response => {
        var icon = `<i class="far fa-bell"></i>`;
        var badge = "";
        var notif_badge = "";
        var notif_pending_ls = "";
        var notif_accepted_ls = "";
        var notif_rejected_ls = "";
        var notif_pending_ls_val = sessionStorage.getItem('notif_pending_ls');
        var notif_accepted_ls_val = sessionStorage.getItem('notif_accepted_ls');
        var notif_rejected_ls_val = sessionStorage.getItem('notif_rejected_ls');
        var notif_pending_ls_body = "";
        var notif_accepted_ls_body = "";
        var notif_rejected_ls_body = "";
        try {
          let response_array = JSON.parse(response);
          if (response_array.total > 0) {
            if (response_array.total > 99) {
              var badge = `<span class="badge badge-danger navbar-badge">99+</span>`;
            } else {
              var badge = `<span class="badge badge-danger navbar-badge">${response_array.total}</span>`;
            }
            var notif_badge = `${icon}${badge}`;
            if (response_array.pending_ls > 0) {
              if (response_array.pending_ls < 2) {
                var notif_pending_ls = `<i class="fas fa-exclamation mr-2"></i> ${response_array.pending_ls} new pending line support<span class="float-right text-muted text-sm"></span>`;
                var notif_pending_ls_body = `${response_array.pending_ls} new pending line support`;
              } else {
                var notif_pending_ls = `<i class="fas fa-exclamation mr-2"></i> ${response_array.pending_ls} new pending line supports<span class="float-right text-muted text-sm"></span>`;
                var notif_pending_ls_body = `${response_array.pending_ls} new pending line supports`;
              }
            } else {
              var notif_pending_ls = `<i class="fas fa-exclamation mr-2"></i> No new pending line supports<span class="float-right text-muted text-sm"></span>`;
            }
            if (response_array.accepted_ls > 0) {
              if (response_array.accepted_ls < 2) {
                var notif_accepted_ls = `<i class="fas fa-check mr-2"></i> ${response_array.accepted_ls} new accepted line support<span class="float-right text-muted text-sm"></span>`;
                var notif_accepted_ls_body = `${response_array.accepted_ls} new accepted line support`;
              } else {
                var notif_accepted_ls = `<i class="fas fa-check mr-2"></i> ${response_array.accepted_ls} new accepted line supports<span class="float-right text-muted text-sm"></span>`;
                var notif_accepted_ls_body = `${response_array.accepted_ls} new accepted line supports`;
              }
            } else {
              var notif_accepted_ls = `<i class="fas fa-check mr-2"></i> No new accepted line supports<span class="float-right text-muted text-sm"></span>`;
            }
            if (response_array.rejected_ls > 0) {
              if (response_array.rejected_ls < 2) {
                var notif_rejected_ls = `<i class="fas fa-times mr-2"></i> ${response_array.rejected_ls} new rejected line support<span class="float-right text-muted text-sm"></span>`;
                var notif_rejected_ls_body = `${response_array.rejected_ls} new rejected line support`;
              } else {
                var notif_rejected_ls = `<i class="fas fa-times mr-2"></i> ${response_array.rejected_ls} new rejected line supports<span class="float-right text-muted text-sm"></span>`;
                var notif_rejected_ls_body = `${response_array.rejected_ls} new rejected line supports`;
              }
            } else {
              var notif_rejected_ls = `<i class="fas fa-times mr-2"></i> No new rejected line supports<span class="float-right text-muted text-sm"></span>`;
            }
            if (notif_pending_ls_val != response_array.pending_ls) {
              $(document).Toasts('create', {
                class: 'bg-warning',
                body: notif_pending_ls_body,
                title: 'Pending Line Support',
                icon: 'fas fa-exclamation fa-lg',
                autohide: true,
                delay: 3000
              });
            }
            if (notif_accepted_ls_val != response_array.accepted_ls) {
              $(document).Toasts('create', {
                class: 'bg-success',
                body: notif_accepted_ls_body,
                title: 'Accepted Line Support',
                icon: 'fas fa-check fa-lg',
                autohide: true,
                delay: 3000
              });
            }
            if (notif_rejected_ls_val != response_array.rejected_ls) {
              $(document).Toasts('create', {
                class: 'bg-danger',
                body: notif_rejected_ls_body,
                title: 'Rejected Line Support',
                icon: 'fas fa-times fa-lg',
                autohide: true,
                delay: 3000
              });
            }
            sessionStorage.setItem('notif_pending_ls', response_array.pending_ls);
            sessionStorage.setItem('notif_accepted_ls', response_array.accepted_ls);
            sessionStorage.setItem('notif_rejected_ls', response_array.rejected_ls);
          } else {
            sessionStorage.setItem('notif_pending_ls', 0);
            sessionStorage.setItem('notif_accepted_ls', 0);
            sessionStorage.setItem('notif_rejected_ls', 0);
            var notif_badge = `${icon}`;
            var notif_pending_ls = `<i class="fas fa-exclamation mr-2"></i> No new pending line support<span class="float-right text-muted text-sm"></span>`;
            var notif_accepted_ls = `<i class="fas fa-check mr-2"></i> No new accepted line support<span class="float-right text-muted text-sm"></span>`;
            var notif_rejected_ls = `<i class="fas fa-times mr-2"></i> No new rejected line support<span class="float-right text-muted text-sm"></span>`;
          }
        } catch (e) {
          console.log(response);
          console.log(`Notification Error! Call IT Personnel Immediately!!! They will fix it right away. Error: ${response}`);
        }
        $('#notif_badge').html(notif_badge);
        $('#notif_pending_ls').html(notif_pending_ls);
        $('#notif_accepted_ls').html(notif_accepted_ls);
        $('#notif_rejected_ls').html(notif_rejected_ls);
      }
    })
      .fail((jqXHR, textStatus, errorThrown) => {
        console.log(jqXHR);
        if (textStatus == "timeout") {
          console.log(`Error: url: ${jqXHR.url}, method: ${jqXHR.type} ( Connection / Request Timeout )`);
          clearInterval(realtime_load_notif_line_support);
          setTimeout(() => { window.location.reload() }, 5000);
        } else {
          console.log(`System Error! Call IT Personnel Immediately!!! They will fix it right away. Error: url: ${jqXHR.url}, method: ${jqXHR.type} ( HTTP ${jqXHR.status} - ${jqXHR.statusText} )`);
        }
      });
  }

  // Notifications
  const update_notif_line_support = () => {
    $.ajax({
      url: '../process/admin/notification/notif_p.php',
      type: 'POST',
      cache: false,
      data: {
        method: 'update_notif_line_support'
      },
      beforeSend: (jqXHR, settings) => {
        jqXHR.url = settings.url;
        jqXHR.type = settings.type;
      },
      success: response => {
        var icon = `<i class="far fa-bell"></i>`;
        var notif_badge = `${icon}`;
        var notif_pending_ls = `<i class="fas fa-exclamation mr-2"></i> No new pending line support<span class="float-right text-muted text-sm"></span>`;
        var notif_accepted_ls = `<i class="fas fa-check mr-2"></i> No new accepted line support<span class="float-right text-muted text-sm"></span>`;
        var notif_rejected_ls = `<i class="fas fa-times mr-2"></i> No new rejected line support<span class="float-right text-muted text-sm"></span>`;
        $('#notif_badge').html(notif_badge);
        $('#notif_pending_ls').html(notif_pending_ls);
        $('#notif_accepted_ls').html(notif_accepted_ls);
        $('#notif_rejected_ls').html(notif_rejected_ls);
        if (response != '') {
          console.log(response);
          console.log(`Notification Error! Call IT Personnel Immediately!!! They will fix it right away. Error: ${response}`);
        }
      }
    })
      .fail((jqXHR, textStatus, errorThrown) => {
        console.log(jqXHR);
        console.log(`System Error! Call IT Personnel Immediately!!! They will fix it right away. Error: url: ${jqXHR.url}, method: ${jqXHR.type} ( HTTP ${jqXHR.status} - ${jqXHR.statusText} )`);
      });
  }

  // Notifications
  const load_notif_line_support_req = () => {
    $.ajax({
      url: '../process/admin/notification/notif_p.php',
      type: 'POST',
      cache: false,
      data: {
        method: 'count_notif_line_support'
      },
      beforeSend: (jqXHR, settings) => {
        jqXHR.url = settings.url;
        jqXHR.type = settings.type;
      },
      success: response => {
        var notif_pending_ls_val = sessionStorage.getItem('notif_pending_ls');
        var notif_accepted_ls_val = sessionStorage.getItem('notif_accepted_ls');
        var notif_rejected_ls_val = sessionStorage.getItem('notif_rejected_ls');
        var notif_pending_ls_body = "";
        var notif_accepted_ls_body = "";
        var notif_rejected_ls_body = "";
        try {
          let response_array = JSON.parse(response);
          if (response_array.total > 0) {
            if (response_array.pending_ls > 0) {
              if (response_array.pending_ls < 2) {
                var notif_pending_ls_body = `${response_array.pending_ls} new pending line support`;
              } else {
                var notif_pending_ls_body = `${response_array.pending_ls} new pending line supports`;
              }
            }
            if (response_array.accepted_ls > 0) {
              if (response_array.accepted_ls < 2) {
                var notif_accepted_ls_body = `${response_array.accepted_ls} new accepted line support`;
              } else {
                var notif_accepted_ls_body = `${response_array.accepted_ls} new accepted line supports`;
              }
            }
            if (response_array.rejected_ls > 0) {
              if (response_array.rejected_ls < 2) {
                var notif_rejected_ls_body = `${response_array.rejected_ls} new rejected line support`;
              } else {
                var notif_rejected_ls_body = `${response_array.rejected_ls} new rejected line supports`;
              }
            }
            if (notif_pending_ls_val != response_array.pending_ls) {
              if (notif_pending_ls_val < response_array.pending_ls) {
                $(document).Toasts('create', {
                  class: 'bg-warning',
                  body: notif_pending_ls_body,
                  title: 'Pending Line Support',
                  icon: 'fas fa-exclamation fa-lg',
                  autohide: true,
                  delay: 4800
                });
              }
            }
            if (notif_accepted_ls_val != response_array.accepted_ls) {
              if (notif_accepted_ls_val < response_array.accepted_ls) {
                $(document).Toasts('create', {
                  class: 'bg-success',
                  body: notif_accepted_ls_body,
                  title: 'Accepted Line Support',
                  icon: 'fas fa-check fa-lg',
                  autohide: true,
                  delay: 4800
                });
              }
            }
            if (notif_rejected_ls_val != response_array.rejected_ls) {
              if (notif_rejected_ls_val < response_array.rejected_ls) {
                $(document).Toasts('create', {
                  class: 'bg-danger',
                  body: notif_rejected_ls_body,
                  title: 'Rejected Line Support',
                  icon: 'fas fa-times fa-lg',
                  autohide: true,
                  delay: 4800
                });
              }
            }
            sessionStorage.setItem('notif_pending_ls', response_array.pending_ls);
            sessionStorage.setItem('notif_accepted_ls', response_array.accepted_ls);
            sessionStorage.setItem('notif_rejected_ls', response_array.rejected_ls);
          } else {
            sessionStorage.setItem('notif_pending_ls', 0);
            sessionStorage.setItem('notif_accepted_ls', 0);
            sessionStorage.setItem('notif_rejected_ls', 0);
          }
        } catch (e) {
          console.log(response);
          console.log(`Notification Error! Call IT Personnel Immediately!!! They will fix it right away. Error: ${response}`);
        }
      }
    })
      .fail((jqXHR, textStatus, errorThrown) => {
        console.log(jqXHR);
        if (textStatus == "timeout") {
          console.log(`Error: url: ${jqXHR.url}, method: ${jqXHR.type} ( Connection / Request Timeout )`);
          clearInterval(realtime_load_notif_line_support_req);
          setTimeout(() => { window.location.reload() }, 5000);
        } else {
          console.log(`System Error! Call IT Personnel Immediately!!! They will fix it right away. Error: url: ${jqXHR.url}, method: ${jqXHR.type} ( HTTP ${jqXHR.status} - ${jqXHR.statusText} )`);
        }
      });
  }
</script>