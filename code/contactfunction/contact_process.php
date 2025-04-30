<?php
include '../../connection/connection.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$full_name = $conn->real_escape_string($_POST['full_name']);
$phone_number = $conn->real_escape_string($_POST['phone_number']);
$email = $conn->real_escape_string($_POST['email']);

$sql = "INSERT INTO contact_inquiries (full_name, phone_number, email) VALUES ('$full_name', '$phone_number', '$email')";

if ($conn->query($sql) === TRUE) {
    header("Location: ../contact.php?status=success");
    exit();
} else {
    header("Location: ../contact.php?status=error");
    exit();
}

$conn->close();
?>
