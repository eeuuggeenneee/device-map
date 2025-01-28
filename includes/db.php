<?php

$serverName = "PBI11411\MSSQLSERVER01"; // Replace with your SQL Server instance name or IP address
$connectionOptions = array(
    "Database" => "pbi_fltracker", // Replace with your database name
    "Uid" => "", // Leave empty for Windows authentication
    "PWD" => "", // Leave empty for Windows authentication
);
 
// Establishes the connection
$conn = sqlsrv_connect($serverName, $connectionOptions);
 
if ($conn === false) {
    echo "Connected successfully!";
    // die(print_r(sqlsrv_errors(), true));

} else {
    
}