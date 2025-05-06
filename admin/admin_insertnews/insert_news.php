<?php
session_start();
include '../../connection/connection.php';
include '../checkrole.php';
include '../design/mainbody.php';

require_role(['superadmin']);

$message = '';

// DELETE LOGIC
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    // Get image path first
    $stmt = $conn->prepare("SELECT image FROM news WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->bind_result($imagePath);
    $stmt->fetch();
    $stmt->close();

    if (!empty($imagePath)) {
        $filePath = '../../uploads/' . basename($imagePath);
        if (file_exists($filePath)) {
            unlink($filePath); // delete image file
        }
    }

    // Delete from database
    $stmt = $conn->prepare("DELETE FROM news WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $message = "News deleted successfully.";
    } else {
        $message = "Delete failed: " . $stmt->error;
    }
    $stmt->close();
}

// INSERT LOGIC
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['delete_id'])) {
    $title = isset($_POST["title"]) ? $_POST["title"] : '';
    $summary = isset($_POST["summary"]) ? $_POST["summary"] : '';
    $link = isset($_POST["link"]) ? $_POST["link"] : '';

    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === 0) {
        $uploadDir = "../../uploads/";
        $filename = basename($_FILES["image"]["name"]);
        $targetFilePath = $uploadDir . $filename;
        $displayPath = "../uploads/" . $filename;
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
    } else {
        $message = "Image file not uploaded or an error occurred.";
    }
}

// FETCH ALL NEWS
$newsRecords = $conn->query("SELECT * FROM news ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add News</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="flex-1 flex-col">
        <main class="p-3 flex flex-col items-center justify-center">
            <div class="w-full max-w-xl bg-white p-6 rounded-lg shadow-md">
                <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">Add News</h1>

                <?php if (!empty($message)): ?>
                    <div class="mb-4 text-center text-sm text-white bg-green-500 rounded p-2">
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>

                <!-- News Submission Form -->
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
            </div>

            <!-- Display Existing News -->
            <div class="w-full max-w-5xl mt-10 bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-bold mb-4 text-gray-700">Existing News</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                            <tr>
                                <th class="px-4 py-2">Image</th>
                                <th class="px-4 py-2">Title</th>
                                <th class="px-4 py-2">Summary</th>
                                <th class="px-4 py-2">Link</th>
                                <th class="px-4 py-2">Date</th>
                                <th class="px-4 py-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($newsRecords && $newsRecords->num_rows > 0): ?>
                                <?php while ($row = $newsRecords->fetch_assoc()): ?>
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-2">
                                        <img src="<?= '../../uploads/' . htmlspecialchars($row['image']) ?>" alt="News Image" class="w-16 h-16 object-cover rounded">
                                        </td>
                                        <td class="px-4 py-2"><?= htmlspecialchars($row['title']) ?></td>
                                        <td class="px-4 py-2"><?= htmlspecialchars($row['summary']) ?></td>
                                        <td class="px-4 py-2">
                                            <?php if (!empty($row['link'])): ?>
                                                <a href="<?= htmlspecialchars($row['link']) ?>" target="_blank" class="text-blue-600 underline">View</a>
                                            <?php else: ?>
                                                <span class="text-gray-400 italic">No link</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-2 text-xs text-gray-500"><?= htmlspecialchars($row['created_at']) ?></td>
                                        <td class="px-4 py-2">
                                            <form method="POST" onsubmit="return confirm('Are you sure you want to delete this news item?');">
                                                <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="6" class="text-center py-4 text-gray-400">No news found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
