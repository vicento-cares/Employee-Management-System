<?php
//database
$servername_mailer = '172.25.116.188';
$username_mailer = 'sa';
$password_mailer = 'SystemGroup@2022';
$database_mailer = 'falp_email';
try {
    $conn_mailer = new PDO("sqlsrv:Server=$servername_mailer;Database=$database_mailer", $username_mailer, $password_mailer);
    $conn_mailer->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'NO CONNECTION' . $e->getMessage();
}
//end database