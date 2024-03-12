<?php
include 'server_date_time.php';

// $servername = 'localhost'; $username = 'root'; $password = '';
$servername = '172.25.116.188'; $username = 'server_113.4'; $password = 'SystemGroup@2022';

try {
    $conn = new PDO ("mysql:host=$servername;dbname=emp_mgt_db",$username,$password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'NO CONNECTION'.$e->getMessage();
}
?>