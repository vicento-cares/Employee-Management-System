<?php
include 'server_date_time.php';

// Old MySql Connection
$servername = 'localhost'; $username = 'root'; $password = '';
// $servername = '172.25.116.188'; $username = 'server_113.4'; $password = 'SystemGroup@2022';

// MS SQL Server Connection
// $servername = '172.25.112.131, 1433\SQLEXPRESS'; $username = 'SA'; $password = 'SystemGroup2018';
// $servername = '172.25.116.188'; $username = 'SA'; $password = 'SystemGroup@2022';

try {
    $conn_portal = new PDO ("mysql:host=$servername;dbname=emp_mgt_portal_db",$username,$password);
    // $conn_portal = new PDO ("sqlsrv:Server=$servername;Database=emp_mgt_db",$username,$password);
    $conn_portal->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'NO CONNECTION'.$e->getMessage();
}
