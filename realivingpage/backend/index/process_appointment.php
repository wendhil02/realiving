<?php
include '../../../connection/connection.php';

$name = $_POST['name'];
$email = $_POST['email'];
$date = $_POST['date'];
$time = $_POST['time'];

// Check if slot already taken
$stmt = $conn->prepare("SELECT * FROM appointments WHERE date = ? AND time = ?");
$stmt->bind_param("ss", $date, $time);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "Sorry, the selected time slot is already booked.";
} else {
    // Insert new appointment
    $stmt = $conn->prepare("INSERT INTO appointments (name, email, date, time) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $date, $time);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Appointment booked successfully!";
    } else {
        echo "Error booking appointment. Please try again.";
    }
}

$stmt->close();
$conn->close();
?>