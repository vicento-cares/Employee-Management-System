<?php
include '../../server_date_time.php';

if (isset($_GET['realtime'])) {
    $response_arr = array(
        'server_time_a' => $server_time_a,
        'server_date_time' => $server_date_time
    );

    echo json_encode($response_arr, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}
?>