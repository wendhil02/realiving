<?php
session_start();
include '../../connection/connection.php';
include '../checkrole.php';
include '../design/mainbody.php';

require_role(['superadmin']);

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $summary = $_POST["summary"];
    $link = $_POST["link"];

    // Handle image upload
    $uploadDir = "../../uploads/"; // For moving the file (based on your folder structure)
    $displayPath = "../uploads/" . basename($_FILES["image"]["name"]); // What you store in DB
    $targetFilePath = $uploadDir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($imageFileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
            $stmt = $conn->prepare("INSERT INTO news (title, summary, image, link) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $title, $summary, $displayPath, $link);

            if ($stmt->execute()) {
                $message = "News uploaded successfully.";
            } else {
                $message = "Database Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $message = "Error uploading the image.";
        }
    } else {
        $message = "Invalid file type. Allowed types: JPG, JPEG, PNG, GIF.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add News</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="flex-1 flex flex-col">
        <main class="p-6 flex flex-col items-center justify-center min-h-screen">
            <div class="w-full max-w-xl bg-white p-6 rounded-lg shadow-md">
                <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">Add News</h1>

                <!-- Feedback Message -->
                <?php if (!empty($message)): ?>
                    <div class="mb-4 text-center text-sm text-white bg-green-500 rounded p-2">
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>

                <!-- Form Start -->
                <form method="POST" enctype="multipart/form-data" class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="title" required class="mt-1 w-full p-3 border border-gray-300 rounded focus:ring focus:ring-blue-300 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Summary</label>
                        <textarea name="summary" rows="4" required class="mt-1 w-full p-3 border border-gray-300 rounded focus:ring focus:ring-blue-300 focus:outline-none"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Link (Optional)</label>
                        <input type="url" name="link" class="mt-1 w-full p-3 border border-gray-300 rounded focus:ring focus:ring-blue-300 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Image</label>
                        <input type="file" name="image" accept="image/*" required class="mt-1 w-full border border-gray-300 rounded p-2 bg-white focus:ring focus:ring-blue-300 focus:outline-none">
                    </div>

                    <div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded">
                            Submit News
                        </button>
                    </div>
                </form>
                <!-- Form End -->
            </div>
        </main>
    </div>
</body>
</html>
