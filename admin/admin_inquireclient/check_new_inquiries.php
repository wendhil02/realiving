<?php
include '../../connection/connection.php';
header('Content-Type: application/json');

$total = $conn->query("SELECT COUNT(*) AS count FROM contact_inquiries")->fetch_assoc()['count'];
$new = $conn->query("SELECT COUNT(*) AS count FROM contact_inquiries WHERE sent_to_admin = 0")->fetch_assoc()['count'];
$sent = $conn->query("SELECT COUNT(*) AS count FROM contact_inquiries WHERE sent_to_admin = 1")->fetch_assoc()['count'];

echo json_encode([
    'success' => true,
    'stats' => ['total' => (int)$total, 'new' => (int)$new, 'sent' => (int)$sent]
]);
?>