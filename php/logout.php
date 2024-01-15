<?php
session_start();

if (isset($_SESSION['user'])) {
    // Unset and destroy the session
    unset($_SESSION['user']);
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
?>
