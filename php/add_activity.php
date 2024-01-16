<?php
include("../includes/db.php");

$user = $_POST['user'];
$activity = $_POST['activity'];
$event = $_POST['event'];


$currentDateTime = date('Y-m-d H:i:s'); 


if ($conn) {
    $tsql = "INSERT INTO user_activity (user_id, activity_id,event) VALUES (?, ?, ?)";
    $params = array($user, $activity,$event);
    
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
