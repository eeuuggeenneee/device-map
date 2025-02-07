<?php
include("../includes/db.php");

$user = $_POST['user'];
$activity = $_POST['activity'];
$activity_sequence = $_POST['activity_sequence'];
$run_id = $_POST['run_id'];
$remarks =   $_POST['remarks'];
if ($conn) {
    $now = new DateTime("now", new DateTimeZone("America/New_York"));

    if ($run_id == 0) {
        $insert = 'INSERT INTO fl_runs (start_date,user_id,status) OUTPUT INSERTED.id VALUES (?,?,?)';
        $params2 = array($now->format("Y-m-d H:i:s"), $user, '1');
        $query = sqlsrv_query($conn, $insert, $params2);
        if ($query) {
            $row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
            if ($row) {
                $run_id = $row['id'];
            }
        }
    } else if ($run_id != 0) {
        $tsql = "UPDATE user_activity SET end_time = ?, remarks = ?  WHERE end_time IS NULL AND user_id = ?";
        $params = array($now->format("Y-m-d H:i:s"), $remarks, $user);
        $stmt = sqlsrv_query($conn, $tsql, $params);
    }
    if ($remarks == 'Play' || $remarks == 'Start') {
        $tsql = "INSERT INTO user_activity (user_id, activity_id, start_time,run_id) VALUES (?, ?, ?,?)";
        $params = array($user, $activity, $now->format("Y-m-d H:i:s"), $run_id);
        $stmt = sqlsrv_query($conn, $tsql, $params);
    }


    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        // echo "Record inserted successfully.";
    }

    sqlsrv_close($conn);
} else {
    echo "Connection failed.";
}
