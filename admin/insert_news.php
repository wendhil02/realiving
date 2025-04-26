<?php
include '../connection/connection.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $summary = $_POST["summary"];
    $link = $_POST["link"];

    // Handle image upload
    $targetDir = "../uploads/";
    $imageName = basename($_FILES["image"]["name"]);
    $targetFilePath = $targetDir . $imageName;
    $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    // Optional: check file type
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($imageFileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
            // Insert into database
            $stmt = $conn->prepare("INSERT INTO news (title, summary, image, link) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $title, $summary, $targetFilePath, $link);

            if ($stmt->execute()) {
                echo "News inserted successfully.";
            } else {
                echo "Database Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error uploading the image.";
        }
    } else {
        echo "Invalid file type. Allowed: JPG, JPEG, PNG, GIF.";
    }

    $conn->close();
}
?>