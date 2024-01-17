<?php
session_start();
include("../includes/db.php");
$insert2 = 'INSERT INTO login_history (user_id,type,fl_type) VALUES (?,?,?)';


if (isset($_SESSION['user'])) {
    // Unset and destroy the session

    $params3 = array($_SESSION['user_id'],"LOGOUT",$_SESSION['fl_type']);
    sqlsrv_query($conn, $insert2, $params3);

    unset($_SESSION['user']);
    unset($_SESSION['user_id']);
    unset($_SESSION['fl_type']);
    session_destroy();
    // Delete the user cookie
    setcookie('user', '', time() - 3600, '/');

    // Redirect to the login page
    header('Location: login.php');
    exit();
} else {
    // If the user is not logged in, redirect to the login page
    header('Location: login.php');
    exit();
}
