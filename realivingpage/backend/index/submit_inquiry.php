<?php
session_start();
require_once '../../../connection/connection.php'; // Adjust path as needed

try {
    $full_name = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone_number = $_POST['phone_number'] ?? '';
    $project_description = $_POST['project_description'] ?? '';

    if (empty($full_name) || empty($email) || empty($phone_number)) {
        throw new Exception("Please fill in all required fields.");
    }

    $client_type = "realiving";

    $stmt = $conn->prepare("
        INSERT INTO contact_inquiries 
            (full_name, phone_number, email, client_type, sent_to_admin, created_at, sent_at)
        VALUES (?, ?, ?, ?, 0, NOW(), NOW())
    ");
    $stmt->bind_param("ssss", $full_name, $phone_number, $email, $client_type);

    if (!$stmt->execute()) {
        throw new Exception("Failed to save inquiry: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();

    $_SESSION['success'] = "Inquiry submitted successfully!";
    header("Location: ../../../realivingpage/index.php"); // ✅ No query string
    exit();
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header("Location: ../../../realivingpage/index.php");
    exit();
}
