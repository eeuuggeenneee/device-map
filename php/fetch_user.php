<?php
// Include your database connection file
include('../includes/db.php');
$activity_sequence = $_GET['activity_sequence'] ?? 0;
$user_id = $_GET['user_id'] ?? Null;
$run_id = $_GET['run_id'] ?? Null;
if ($user_id) {
    $query = "select * from user_activity where user_id = '$user_id' and run_id = '$run_id'";
}

$result = sqlsrv_query($conn, $query);

// Check for errors in query execution
if ($result === false) {
    die(print_r(sqlsrv_errors(), true)); // Error handling
}

// Create an array to store the fetched data
$activities = [];
while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    $activities[] = $row;
}

sqlsrv_close($conn);

// Output the result as JSON
echo json_encode($activities);
