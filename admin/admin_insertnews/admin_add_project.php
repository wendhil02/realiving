<?php
session_start();
include '../../connection/connection.php';
include '../design/mainbody.php';

// Auto-reset AUTO_INCREMENT if table is empty
$count = mysqli_query($conn, "SELECT COUNT(*) as total FROM projects");
$row = mysqli_fetch_assoc($count);
if ($row['total'] == 0) {
    mysqli_query($conn, "ALTER TABLE projects AUTO_INCREMENT = 1");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];

    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $file_type = mime_content_type($_FILES['image']['tmp_name']);

        // Reject GIFs
        if ($file_type === 'image/gif') {
            echo "<script>alert('GIF files are not allowed.'); window.location.href='admin_add_project.php';</script>";
            exit;
        }

        // Allow other image types
        $image_name = uniqid() . '_' . basename($_FILES['image']['name']);
        $target = '../../uploads/' . $image_name;
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
        $image = $image_name;
    }

    // Insert project into DB
    $query = "INSERT INTO projects (title, description, image) VALUES ('$title', '$description', '$image')";
    mysqli_query($conn, $query);
    header("Location: admin_add_project.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Project</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

    <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow mt-10">
        <h2 class="text-2xl font-bold mb-4">Upload New Project</h2>
        <form method="POST" enctype="multipart/form-data" class="space-y-4">
            <input type="text" name="title" placeholder="Project Title" class="w-full border p-2 rounded" required>
            <textarea name="description" placeholder="Project Description" class="w-full border p-2 rounded" rows="4" required></textarea>
            
            <!-- Only allow non-GIF images -->
            <input type="file" name="image" accept="image/png, image/jpeg, image/jpg, image/webp" class="block" required>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Upload</button>
        </form>
    </div>

</body>
</html>
