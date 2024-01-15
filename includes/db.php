<?php

$serverName = "DESKTOP-PM353L0"; // Replace with your SQL Server instance name or IP address
$connectionOptions = array(
    "Database" => "pbi_thermohygrometer_db", // Replace with your database name
    "Uid" => "", // Leave empty for Windows authentication
    "PWD" => "", // Leave empty for Windows authentication
);
 
// Establishes the connection
$conn = sqlsrv_connect($serverName, $connectionOptions);
 
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));

} else {
    // echo "Connected successfully!";
}