<?php

// Include your database connection file
include('../includes/db.php');

// Start session if needed for user identification
// session_start();

// Get the user_id from POST data
$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;

// Ensure the user_id is provided
if ($user_id) {
    // Get the current time as the end_time
    $end_time = new DateTime("now", new DateTimeZone("America/New_York"));
    $end_time = $end_time->format("Y-m-d H:i:s");

    // SQL query to update the end_time for the active pause record for the user
    $update_sql = "UPDATE pause_history SET end_time = ? WHERE user_id = ? AND end_time IS NULL";
    $params = array($end_time, $user_id);

    // Execute the update query
    $update_query = sqlsrv_query($conn, $update_sql, $params);

    if ($update_query === false) {
        die(json_encode(["error" => "Error updating pause record."])); // Return an error if update fails
    } else {
        echo json_encode(["message" => "Pause history updated successfully."]);
    }
} else {
    echo json_encode(["error" => "Missing user ID."]);
}

// Close the database connection
sqlsrv_close($conn);
?>
