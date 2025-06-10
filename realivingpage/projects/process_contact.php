<?php
include 'database.php';

// Sanitize and get POST data
$name = $conn->real_escape_string($_POST['name']);
$phone = $conn->real_escape_string($_POST['phone']);
$email = $conn->real_escape_string($_POST['email']);
// Insert query
$sql = "INSERT INTO contact (name, phone, email) 
        VALUES ('$name', '$phone', '$email')";

if ($conn->query($sql) === TRUE) {
  echo "Message sent successfully!";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

// PWEDE MO TO SIYA GAWAN NG BAGONG TABLE SA DATABASE PARA HINDI SILA IISA NG KUKUHANAN NG DATA KASI MAGKAIBA YUNG SA CONTACT US NA FORM PATI YUNG SA ROOMS AT WALANG SUBJECT AND MESSAGE YON. GETS???
?>

