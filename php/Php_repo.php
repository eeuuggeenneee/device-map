<?php
//include("includes/db.php");

$lat = $_POST['latloc'];
$long = $_POST['longloc'];
$distance = $_POST['distance'];

$serverName = "DESKTOP-PM353L0";
$databaseName = "pbi_thermohygrometer_db";
$username = "";
$password = "";


$currentDateTime = date('Y-m-d H:i:s'); 

try {
    $conn = new PDO("sqlsrv:Server=$serverName;Database=$databaseName", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO location (user_id, lat, lon, distance,timestamp) VALUES (1001, '".$lat."', '".$long."','".$distance."', '".$currentDateTime."')";

    $conn->exec($sql);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>
