<?php
session_start();
include 'connection/connection.php';

if (isset($_SESSION['admin_id'])) {
    $stmt = $conn->prepare("UPDATE account SET remember_token = NULL WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['admin_id']);
    $stmt->execute();
}

session_unset();
session_destroy();
setcookie('remember_token', '', time() - 3600, "/");

header("Location: loginpage/index.php");
exit();
?>
