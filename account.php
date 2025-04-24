<?php
include 'connection/connection.php';

// Admin details
$admin_email = 'admin@gmail.com';
$admin_password_plain = 'admin123'; // Default password (change if you want)
$admin_password_hashed = password_hash($admin_password_plain, PASSWORD_DEFAULT);

// Check if admin already exists
$sql = "SELECT * FROM account WHERE email = '$admin_email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "Admin account already exists!";
} else {
    // Insert admin account
    $insert_sql = "INSERT INTO account (email, password) VALUES ('$admin_email', '$admin_password_hashed')";

    if ($conn->query($insert_sql) === TRUE) {
        echo "Admin account created successfully!";
    } else {
        echo "Error creating admin account: " . $conn->error;
    }
}

$conn->close();
?>
