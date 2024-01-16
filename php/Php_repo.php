<?php
include("../includes/db.php");

$lat = $_POST['latloc'];
$long = $_POST['longloc'];
$heading = $_POST['headingloc'];
$accuracy = $_POST['radiusloc'];
$activity = $_POST['activityl'];
$timestamp = $_POST['timestamp'];
$currentDateTime = date('Y-m-d H:i:s'); 


if ($conn) {
    $tsql = "INSERT INTO location (user_id, lat, lon, accuracy, heading, activity_id , time_epoch,timestamp) VALUES (?, ?, ?, ?, ?, ?,?, ?)";
    $params = array(1, $lat, $long, $accuracy, $heading,$activity,$timestamp,$currentDateTime);
    
    $stmt = sqlsrv_query($conn, $tsql, $params);
    
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        echo "Record inserted successfully.";
    }
    
    sqlsrv_close($conn);
} else {
    echo "Connection failed.";
}
?>
