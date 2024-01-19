<?php
include("../includes/db.php");

$user = $_POST['user'];
$timezone = new DateTimeZone('Asia/Manila');
$currentDateTime = new DateTime('now', $timezone);
// Format the DateTime object as a string
$formattedDateTime = $currentDateTime->format('Y-m-d H:i:s');

if ($conn) {
    $tsql = "UPDATE user_activity SET end_time = ? WHERE end_time IS NULL AND user_id = ?";
    $params = array($formattedDateTime, $user);
    
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
