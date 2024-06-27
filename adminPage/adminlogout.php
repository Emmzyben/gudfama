<?php
// In logout.php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// Start or resume the session
session_start();

if (!isset($_SESSION['username'])) {
    session_unset();
    session_destroy();

            header("Location: ../adminlogin.php");
            exit();
        } 
else {
    // User not logged in, handle accordingly (e.g., log the attempt, deny access)
    session_unset();
    session_destroy();
    header("Location: ../adminlogin.php");
    exit();
}
