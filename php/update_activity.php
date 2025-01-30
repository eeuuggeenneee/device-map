<?php
include("../includes/db.php");

$user = $_POST['user'];

$now = new DateTime("now", new DateTimeZone("America/New_York"));
if ($conn) {

    $tsql = "UPDATE user_activity SET end_time = ? WHERE end_time IS NULL AND user_id = ?";
    $params = array($now->format("Y-m-d H:i:s"), $user);
    $stmt = sqlsrv_query($conn, $tsql, $params);

    $tsql = "UPDATE fl_runs SET end_date = ? WHERE end_date IS NULL AND user_id = ?";
    $params = array($now->format("Y-m-d H:i:s"), $user);
    $stmt = sqlsrv_query($conn, $tsql, $params);
    
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        // echo "Record inserted successfully.";
    }
    
    sqlsrv_close($conn);
} else {
    echo "Connection failed.";
}
?>
