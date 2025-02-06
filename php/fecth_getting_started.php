<?php
session_start(); // Start the session

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include('../includes/db.php');

// Fetch active user activities for the logged-in user
$sql = "SELECT 
    a.id,
    a.user_id,
    a.activity_id,
    a.start_time,
    a.end_time,
    -- Convert total duration into human-readable format without unnecessary zeros
    LTRIM(
        CONCAT(
            CASE WHEN SUM(DATEDIFF(SECOND, a.start_time, a.end_time)) >= 86400 
                THEN CONCAT(SUM(DATEDIFF(SECOND, a.start_time, a.end_time)) / 86400, ' days, ') 
                ELSE '' END,
            CASE WHEN (SUM(DATEDIFF(SECOND, a.start_time, a.end_time)) % 86400) >= 3600 
                THEN CONCAT((SUM(DATEDIFF(SECOND, a.start_time, a.end_time)) % 86400) / 3600, ' hours, ') 
                ELSE '' END,
            CASE WHEN (SUM(DATEDIFF(SECOND, a.start_time, a.end_time)) % 3600) >= 60 
                THEN CONCAT((SUM(DATEDIFF(SECOND, a.start_time, a.end_time)) % 3600) / 60, ' minutes, ') 
                ELSE '' END,
            (SUM(DATEDIFF(SECOND, a.start_time, a.end_time)) % 60), ' seconds'
        )
    ) AS total_duration_human_readable, 
    b.name, 
    b.description, 
    b.move_type,
    c.f_name AS first_name,
    c.l_name AS last_name,
    -- Convert total pause seconds into human-readable format without unnecessary zeros, return NULL if no pause exists
    CASE 
        WHEN (SELECT SUM(DATEDIFF(SECOND, ccc.start_time, ccc.end_time)) 
              FROM pause_history ccc 
              WHERE ccc.activity_id = a.id) IS NULL THEN NULL
        ELSE LTRIM(
            CONCAT(
                CASE WHEN (SELECT SUM(DATEDIFF(SECOND, ccc.start_time, ccc.end_time)) 
                           FROM pause_history ccc 
                           WHERE ccc.activity_id = a.id) >= 86400 
                    THEN CONCAT((SELECT SUM(DATEDIFF(SECOND, ccc.start_time, ccc.end_time)) 
                                 FROM pause_history ccc 
                                 WHERE ccc.activity_id = a.id) / 86400, ' days, ') 
                    ELSE '' END,
                CASE WHEN ((SELECT SUM(DATEDIFF(SECOND, ccc.start_time, ccc.end_time)) 
                            FROM pause_history ccc 
                            WHERE ccc.activity_id = a.id) % 86400) >= 3600 
                    THEN CONCAT(((SELECT SUM(DATEDIFF(SECOND, ccc.start_time, ccc.end_time)) 
                                  FROM pause_history ccc 
                                  WHERE ccc.activity_id = a.id) % 86400) / 3600, ' hours, ') 
                    ELSE '' END,
                CASE WHEN ((SELECT SUM(DATEDIFF(SECOND, ccc.start_time, ccc.end_time)) 
                            FROM pause_history ccc 
                            WHERE ccc.activity_id = a.id) % 3600) >= 60 
                    THEN CONCAT(((SELECT SUM(DATEDIFF(SECOND, ccc.start_time, ccc.end_time)) 
                                  FROM pause_history ccc 
                                  WHERE ccc.activity_id = a.id) % 3600) / 60, ' minutes, ') 
                    ELSE '' END,
                ((SELECT SUM(DATEDIFF(SECOND, ccc.start_time, ccc.end_time)) 
                  FROM pause_history ccc 
                  WHERE ccc.activity_id = a.id) % 60), ' seconds'
            )
        ) 
    END AS total_pause_seconds_human_readable
FROM user_activity a
LEFT JOIN activity b ON a.activity_id = b.id
LEFT JOIN user_tbl c ON c.id = a.user_id
WHERE a.activity_id <> 1
GROUP BY 
    a.id, 
    a.user_id,
    a.activity_id,
    a.start_time,
    a.end_time,
    b.name, 
    c.f_name,
    c.l_name,
    b.description, 
    b.move_type;
";

$query = sqlsrv_query($conn, $sql);

if ($query === false) {
    echo json_encode(["error" => sqlsrv_errors()]);
    exit;
}

// Fetch all rows
$activities = [];
while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
    $activities[] = $row;
}

// Return all rows as JSON
if (!empty($activities)) {
    echo json_encode($activities);
} else {
    echo json_encode(["error" => "No active activities found"]);
}

sqlsrv_close($conn);
