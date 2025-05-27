<?php 
//SESSION
include '../process/login.php';

if (!isset($_SESSION['emp_no'])) {
  header('location:/emp_mgt/admin');
  exit;
}
?>  
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin</title>

  <link rel="icon" href="../dist/img/logo.ico" type="image/x-icon" />
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="../dist/css/font.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../plugins/ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Sweet Alert -->
  <link rel="stylesheet" href="../plugins/sweetalert2/dist/sweetalert2.min.css">
  <style>
    .loader {
      border: 16px solid #f3f3f3;
      border-radius: 50%;
      border-top: 16px solid #536A6D;
      width: 50px;
      height: 50px;
      -webkit-animation: spin 2s linear infinite;
      animation: spin 2s linear infinite;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(1080deg); }
    } 

    /* Employee Picture Style */
    .update_employee_picture_img_tag {
      width: 100px; /* Fixed width */
      height: 100px; /* Fixed height */
      object-fit: contain; /* Ensure the whole image is visible */
      object-position: center; /* Center the image within the container */
    }
    .attendances_employee_picture_img_tag {
      width: 75px; /* Fixed width */
      height: 75px; /* Fixed height */
      object-fit: contain; /* Ensure the whole image is visible */
      object-position: center; /* Center the image within the container */
    }
    .osh_employee_picture_img_tag {
      width: 100%; /* Fixed width */
      object-fit: contain; /* Ensure the whole image is visible */
      object-position: center; /* Center the image within the container */
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../dist/img/logo.webp" alt="logo" height="60" width="60">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="../in" class="nav-link">Time In</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="../out" class="nav-link">Time Out</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" id="notif_badge">
          <i class="far fa-bell"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header" id="notif_title">Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="line_support.php" class="dropdown-item" id="notif_pending_ls">
            <i class="fas fa-exclamation mr-2"></i> No new pending line support
            <span class="float-right text-muted text-sm"></span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="line_support.php" class="dropdown-item" id="notif_accepted_ls">
            <i class="fas fa-check mr-2"></i> No new accepted line support
            <span class="float-right text-muted text-sm"></span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="line_support.php" class="dropdown-item" id="notif_rejected_ls">
            <i class="fas fa-times mr-2"></i> No new rejected line support
            <span class="float-right text-muted text-sm"></span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="line_support.php" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

