<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EmpMgtSys | Viewer</title>

    <link rel="icon" href="../dist/img/logo.ico" type="image/x-icon" />
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="../dist/css/font.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="../plugins/ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
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
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(1080deg);
            }
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
    </style>
</head>