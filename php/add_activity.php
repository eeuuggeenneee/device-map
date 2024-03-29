<?php
include("../includes/db.php");

$user = $_POST['user'];
$activity = $_POST['activity'];
$event = $_POST['event'];

$timezone = new DateTimeZone('Asia/Manila');

$currentDateTime = new DateTime('now', $timezone);
// Format the DateTime object as a string
$formattedDateTime = $currentDateTime->format('Y-m-d H:i:s');

if ($conn) {
    $tsql = "INSERT INTO user_activity (user_id, activity_id, start_time) VALUES (?, ?, ?)";
    $params = array($user, $activity,$formattedDateTime);
    
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
