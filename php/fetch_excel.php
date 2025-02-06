<?php
session_start(); // Start the session

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
// header("Content-Type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=activities.csv"); // Prompt file download


include('../includes/db.php');

// Fetch start_date and end_date from the request (GET or POST)
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : null;
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : null;
function formatDateTime($dateTime) {
    if ($dateTime instanceof DateTime) {
        return $dateTime->format('Y-m-d H:i:s'); // Format as 'YYYY-MM-DD HH:MM:SS'
    }
    return $dateTime; // Return the original value if it's not a DateTime object
}

// Base SQL query
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
";

// Add date filters if provided
if ($start_date) {
    // Ensure the date format matches your database format (assumes 'YYYY-MM-DD')
    $sql .= " AND a.start_time >= '" . $start_date . "' ";
}

if ($end_date) {
    $sql .= " AND a.end_time <= '" . $end_date . "' ";
}


$sql .= "GROUP BY 
    a.id, 
    a.user_id,
    a.activity_id,
    a.start_time,
    a.end_time,
    b.name, 
    c.f_name,
    c.l_name,
    b.description, 
    b.move_type";

// Execute the query
$query = sqlsrv_query($conn, $sql);

if ($query === false) {
    echo json_encode(["error" => sqlsrv_errors()]);
    exit;
}

$activities = [];
while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
    // Format the start_time and end_time fields
    $row['start_time'] = formatDateTime($row['start_time']);
    $row['end_time'] = formatDateTime($row['end_time']);
    
    // Add the formatted row to the activities array
    $activities[] = $row;
}

// Open the output stream for CSV
$output = fopen('php://output', 'w');

// Column headers for the CSV
$headers = [
    'ID', 'User ID', 'Activity ID', 'Start Time', 'End Time', 
    'Total Duration', 'Activity Name', 'Description', 'Move Type', 
    'First Name', 'Last Name', 'Total Pause Duration'
];

fputcsv($output, $headers);

// Output the data rows to CSV
foreach ($activities as $row) {
    fputcsv($output, $row);
}

// Close the connection
sqlsrv_close($conn);
?>