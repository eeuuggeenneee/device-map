<?php

$serverName = "DESKTOP-PM353L0";
$databaseName = "pbi_thermohygrometer_db";
$username = "";
$password = "";



try {
    $conn = new PDO("sqlsrv:Server=$serverName;Database=$databaseName", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully!";

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
