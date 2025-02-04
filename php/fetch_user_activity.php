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
$sql = "SELECT 
            d.start_date AS run_start_time,
            a.*,
            b.name AS activity_name,
            b.description,
            b.activity_sequence,
            c.id AS pause_id, 
            c.start_time AS pause_start_time, 
            c.end_time AS pause_end_time,
            b.move_type,

            -- Total pause duration in seconds for the current run
            (SELECT SUM(DATEDIFF(SECOND, cc.start_time, cc.end_time)) 
            FROM pause_history cc
            WHERE cc.run_id = a.run_id) AS total_pause_seconds_run,
            -- Total pause duration in seconds for the current activity
            (SELECT SUM(DATEDIFF(SECOND, ccc.start_time, ccc.end_time)) 
            FROM pause_history ccc
            WHERE ccc.activity_id = a.id) AS total_pause_seconds_activity
        FROM user_activity a
        LEFT JOIN activity b ON a.activity_id = b.id
        LEFT JOIN pause_history c ON a.id = c.activity_id AND c.end_time IS NULL
        LEFT JOIN fl_runs d ON d.id = a.run_id
        WHERE a.user_id = ? AND a.end_time IS NULL;";

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
