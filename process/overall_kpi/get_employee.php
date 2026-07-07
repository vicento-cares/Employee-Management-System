<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET');
    header('Access-Control-Allow-Headers: *');
    include '../conn.php';
    $stmt = $conn ->prepare("EXEC employees_GET_employee :EmpNo");
    $stmt -> bindValue(":EmpNo",trim($_GET['emp_no']));
    $stmt -> execute();
    header('Content-Type: application/json');
    echo json_encode(["data" => $stmt -> fetch(PDO::FETCH_ASSOC)]);
    exit();