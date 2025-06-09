<?php
// Include your database connection
include '../database.php';

// If the form is submitted, insert data into the database
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $title = $_POST['title'];
    $category = $_POST['category'];
    $description = $_POST['description'];

    // Handle file upload for the image
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = '../images/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
    }

    // Get the current date for date_uploaded
    $date_uploaded = date('Y-m-d H:i:s');

    // Insert into the database
    $sql = "INSERT INTO news (title, category, description, image, date_uploaded) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $title, $category, $description, $image, $date_uploaded);
    
    if ($stmt->execute()) {
        echo "News added successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add News</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<section class="news-form">
    <div class="container">
        <h2>Add News</h2>
        <form action="admin_add_news.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" placeholder="Enter title" required>
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <input type="text" id="category" name="category" placeholder="Enter category" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" placeholder="Enter description" required></textarea>
            </div>

            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" id="image" name="image" accept="image/*" required>
            </div>

            <button type="submit" class="submit-btn">Submit</button>
        </form>
    </div>
</section>

</body>
</html>