<?php
// Set session cookie lifetime to 7 days (in seconds)
ini_set('session.cookie_lifetime', 7 * 24 * 60 * 60);

session_start();

if (!isset($_SESSION["user"])) {
    header("Location: ./php/login.php");
    exit();
} else {
    // Add your code for the case when the "user" session variable is set
    // For example, you may include code to display content for authenticated users.
}
?>
