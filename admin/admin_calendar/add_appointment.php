<?php
include '../../connection/connection.php'; // database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $time = $_POST['time'];
    $appointment_date = $_POST['appointment_date'];

    // Convert the time to 24-hour format using strtotime
    $time = date("H:i", strtotime($time));

    $sql = "INSERT INTO appointments (title, time, appointment_date) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $title, $time, $appointment_date);

    if ($stmt->execute()) {
        header("Location: calendar.php?success=1"); // Change to your calendar page
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    header("Location: calendar.php");
}
?>


