<?php
session_start();
include '../../connection/connection.php';
include '../checkrole.php';
include '../design/mainbody.php';

require_role(['superadmin']);

$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);

// DELETE LOGIC
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    $stmt = $conn->prepare("SELECT image FROM news WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->bind_result($imagePath);
    $stmt->fetch();
    $stmt->close();

    if (!empty($imagePath)) {
        $filePath = '../../uploads/' . basename($imagePath);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    $stmt = $conn->prepare("DELETE FROM news WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "News deleted successfully.";
    } else {
        $_SESSION['message'] = "Delete failed: " . $stmt->error;
    }
    $stmt->close();
    header("Location: insert_news.php"); // Replace with your actual filename
    exit;
}

// INSERT LOGIC
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['delete_id'])) {
    $title = $_POST["title"] ?? '';
    $summary = $_POST["summary"] ?? '';
    $link = $_POST["link"] ?? '';

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
                    $_SESSION['message'] = "News uploaded successfully.";
                } else {
                    $_SESSION['message'] = "Database Error: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $_SESSION['message'] = "Error uploading the image.";
            }
        } else {
            $_SESSION['message'] = "Invalid file type. Allowed types: JPG, JPEG, PNG, GIF.";
        }
    } else {
        $_SESSION['message'] = "Image file not uploaded or an error occurred.";
    }

    header("Location: insert_news.php"); // Replace with your actual filename
    exit;
}
// FETCH ALL NEWS
$newsRecords = $conn->query("SELECT * FROM news ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <style>
        .transition-all {
            transition: all 0.3s ease;
        }
        .file-input-wrapper:hover {
            border-color:rgb(67, 214, 250);
        }
        .news-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .news-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .custom-file-input {
            opacity: 0;
            position: absolute;
            z-index: -1;
        }
        .custom-file-label {
            cursor: pointer;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .file-selected {
            color: #4f46e5;
            font-weight: 500;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Page Header -->
        <div class="flex justify-between items-center mb-10">
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-newspaper text-blue-600 mr-2"></i>News Management
            </h1>
            <div class="text-sm text-gray-500">
                <span class="mr-2"><i class="far fa-calendar-alt"></i> <?= date('F j, Y') ?></span>
                <span><i class="far fa-user"></i> Super Admin</span>
            </div>
        </div>

        <!-- Notification Message -->
        <?php if (!empty($message)): ?>
            <div id="notification" class="mb-6 text-center py-3 px-4 rounded-lg shadow-md border-l-4 border-green-500 bg-green-50 text-green-700 flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                    <span><?= htmlspecialchars($message) ?></span>
                </div>
                <button onclick="document.getElementById('notification').style.display='none'" class="text-green-600 hover:text-green-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- News Submission Form -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-all">
                    <div class="px-6 py-4 bg-blue-600 text-white">
                        <h2 class="text-xl font-semibold"><i class="fas fa-plus-circle mr-2"></i>Add News</h2>
                    </div>
                    <form method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500">
                                    <i class="fas fa-heading"></i>
                                </span>
                                <input type="text" name="title" required 
                                    class="pl-10 w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                                    placeholder="Enter news title">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Summary</label>
                            <div class="relative">
                                <textarea name="summary" rows="4" required 
                                    class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                                    placeholder="Enter news summary"></textarea>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Link (Optional)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500">
                                    <i class="fas fa-link"></i>
                                </span>
                                <input type="url" name="link" 
                                    class="pl-10 w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                                    placeholder="https://example.com">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                            <div class="relative file-input-wrapper border-2 border-dashed border-gray-300 rounded-lg p-4 text-center bg-gray-50 hover:bg-gray-100 transition-all">
                                <input type="file" name="image" accept="image/*" required id="fileInput" class="custom-file-input">
                                <label for="fileInput" class="custom-file-label block">
                                    <div id="file-upload-icon" class="mb-2 text-indigo-500">
                                        <i class="fas fa-cloud-upload-alt text-3xl"></i>
                                    </div>
                                    <span id="file-upload-text">Drag and drop or click to upload</span>
                                    <div class="mt-2 text-xs text-gray-500">JPG, JPEG, PNG, GIF files only</div>
                                </label>
                            </div>
                        </div>

                        <div>
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-4 rounded-lg transition-all flex items-center justify-center">
                                <i class="fas fa-save mr-2"></i> Publish News
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Display Existing News -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="px-6 py-4 bg-blue-600 text-white flex justify-between items-center">
                        <h2 class="text-xl font-semibold"><i class="fas fa-list-alt mr-2"></i>Existing News</h2>
                        <span class="bg-white text-indigo-600 text-xs font-bold px-3 py-1 rounded-full"><?= $newsRecords ? $newsRecords->num_rows : 0 ?> Items</span>
                    </div>
                    <div class="p-6">
                        <?php if ($newsRecords && $newsRecords->num_rows > 0): ?>
                            <div class="grid grid-cols-1 gap-6">
                                <?php while ($row = $newsRecords->fetch_assoc()): ?>
                                    <div class="news-card bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md">
                                        <div class="flex flex-col sm:flex-row">
                                            <div class="sm:w-1/4 p-4">
                                                <img src="<?= '../../uploads/' . basename($row['image']) ?>" alt="News Image" class="w-full h-32 object-cover rounded-lg">
                                            </div>
                                            <div class="sm:w-3/4 p-4">
                                                <div class="flex justify-between items-start">
                                                    <h3 class="text-lg font-semibold text-gray-800 mb-2"><?= htmlspecialchars($row['title']) ?></h3>
                                                    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this news item?');" class="ml-2">
                                                        <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                                
                                                <p class="text-gray-600 text-sm mb-3"><?= htmlspecialchars($row['summary']) ?></p>
                                                
                                                <div class="flex justify-between items-center text-xs text-gray-500">
                                                    <div>
                                                        <i class="far fa-calendar-alt mr-1"></i> 
                                                        <?= date('M j, Y', strtotime($row['created_at'])) ?>
                                                    </div>
                                                    <?php if (!empty($row['link'])): ?>
                                                        <a href="<?= htmlspecialchars($row['link']) ?>" target="_blank" class="text-indigo-600 hover:text-indigo-800 flex items-center">
                                                            <i class="fas fa-external-link-alt mr-1"></i> Visit Link
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-gray-400 italic">No external link</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-8">
                                <div class="text-indigo-400 mb-4">
                                    <i class="fas fa-newspaper text-6xl"></i>
                                </div>
                                <h3 class="text-xl font-medium text-gray-700">No News Items Found</h3>
                                <p class="text-gray-500 mt-2">Start by adding your first news article</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // File input preview functionality
        document.getElementById('fileInput').addEventListener('change', function(e) {
            var fileName = e.target.files[0].name;
            document.getElementById('file-upload-text').innerText = fileName;
            document.getElementById('file-upload-text').classList.add('file-selected');
            document.getElementById('file-upload-icon').innerHTML = '<i class="fas fa-check-circle text-3xl"></i>';
        });

        // Auto-hide notification after 5 seconds
        setTimeout(function() {
            const notification = document.getElementById('notification');
            if (notification) {
                notification.style.opacity = '0';
                notification.style.transition = 'opacity 1s ease';
                setTimeout(function() {
                    notification.style.display = 'none';
                }, 1000);
            }
        }, 5000);
    </script>
</body>
</html>