<?php
// Include your database connection file
include('../includes/db.php');
$activity_sequence = $_GET['activity_sequence'] ?? 0;
$type = $_GET['move_type'] ?? Null;
$running_sequence = $_GET['running_sequence'] ?? 0;
if ($type) {
    $query = "SELECT * 
              FROM activity 
              WHERE move_type = '$type' 
              ORDER BY 
                  CASE 
                      WHEN activity_sequence = '$activity_sequence' THEN 0
                      WHEN activity_sequence > '$activity_sequence' THEN 1
                      ELSE 2  
                  END, 
                  activity_sequence";
}

if($activity_sequence == 99){
    $query = "SELECT * 
              FROM activity 
              WHERE move_type = '$type' 
              ORDER BY 
                  CASE 
                      WHEN activity_sequence = '$running_sequence' THEN 0
                      WHEN activity_sequence > '$running_sequence' THEN 1
                      ELSE 2  
                  END, 
                  activity_sequence";
}


// Execute the query
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
