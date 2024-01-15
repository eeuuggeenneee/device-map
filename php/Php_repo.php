<?php
include("../includes/db.php");

$lat = $_POST['latloc'];
$long = $_POST['longloc'];
$distance = $_POST['distance'];


$currentDateTime = date('Y-m-d H:i:s'); 


if ($conn) {
    $tsql = "INSERT INTO location (user_id, lat, lon, distance, timestamp) VALUES (?, ?, ?, ?, ?)";
    $params = array(1001, $lat, $long, $distance, $currentDateTime);
    
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
