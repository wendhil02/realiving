<?php
include '../../connection/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $title = $_POST['title'];
    $status = $_POST['status'];

    $sql = "UPDATE appointments SET status = ? WHERE appointment_date = ? AND title = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $status, $date, $title);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>
