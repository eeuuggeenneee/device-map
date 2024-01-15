<?php
    session_start();
    if(!isset($_SESSION["user"])) {
        header("Location: ../php/login.php");
        exit();
    }
?>