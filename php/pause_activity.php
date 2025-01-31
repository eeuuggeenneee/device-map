<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include your database connection file
include('../includes/db.php');

// Retrieve data from POST request (ensure to sanitize/validate input data for security)
$run_id = isset($_POST['run_id']) ? $_POST['run_id'] : '';
$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
$activity_id = isset($_POST['activity_id']) ? $_POST['activity_id'] : '';

$now = new DateTime("now", new DateTimeZone("America/New_York"));
// Ensure that required data exists
if ($run_id && $user_id) {
    // Prepare the SQL query to insert data
    $sql = "INSERT INTO pause_history (run_id, start_time, user_id,activity_id) 
            VALUES (?, ?, ?,?)";

    // Prepare and execute the query
    $params = array($run_id, $now->format("Y-m-d H:i:s"), $user_id, $activity_id);
    $query = sqlsrv_query($conn, $sql, $params);

    if ($query === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        // echo json_encode(["message" => "Record inserted successfully."]);
    }
} else {
    echo json_encode(["error" => "Missing required fields."]);
}

// Close the database connection
sqlsrv_close($conn);
?>
