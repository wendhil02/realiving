<?php
// Check if the session has already been started, if not, start it
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../connection/connection.php';

// Function to check if the user has the required role to access a page
function require_role($allowed_roles) {
    // Check if user is logged in and has a role
    if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_role'])) {
        header("Location: ../loginpage/index.php"); // Redirect to login page if not logged in
        exit();
    }

    // Check if the current user role is allowed
    if (!in_array($_SESSION['admin_role'], $allowed_roles)) {
        header("Location: ../admin_mainpage/unauthorized.php"); // Redirect to unauthorized page if role is not allowed
        exit();
    }
}
?>

