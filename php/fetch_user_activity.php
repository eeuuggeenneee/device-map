<?php
session_start(); // Start the session

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include('../includes/db.php');

// Ensure the user_id is in the session
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null; // Safely fetch user_id from session

if ($userId === null) {
    // If user_id is not set, return an error message
    echo json_encode(["error" => "User not authenticated"]);
    exit;
}

// Fetch active user activities for the logged-in user
$sql = "select d.start_date as run_start_time,a.*,b.name as activity_name ,b.description,b.activity_sequence, c.id as pause_id, b.move_type from user_activity  a
        left join activity b on a.activity_id = b.id
        left join pause_history c on a.id = c.activity_id and c.end_time is Null
        left join fl_runs d on d.id = a.run_id
        where a.user_id = ? and a.end_time is Null";

$params = array($userId);
$query = sqlsrv_query($conn, $sql, $params);


if ($query === false) {
    echo json_encode(["error" => sqlsrv_errors()]);
    exit;
}

// Fetch the first activity (or null if none found)
$activity = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);

// If an activity is found, return it, otherwise return an empty array or error
if ($activity) {
    echo json_encode($activity);
} else {
    echo json_encode(["error" => "No active activities found"]);
}

sqlsrv_close($conn);
?>
