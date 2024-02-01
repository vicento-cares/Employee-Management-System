<?php
date_default_timezone_set('Asia/Manila');
$servername = 'localhost'; $username = 'root'; $password = '';

$server_date_time = date('Y-m-d H:i:s');
$server_date_only = date('Y-m-d');
$server_date_only_yesterday = date('Y-m-d',(strtotime('-1 day',strtotime($server_date_only))));
$server_time = date('H:i:s');
$server_time_a = date('h:i:s A');

try {
    $conn = new PDO ("mysql:host=$servername;dbname=emp_mgt_db",$username,$password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'NO CONNECTION'.$e->getMessage();
}
?>