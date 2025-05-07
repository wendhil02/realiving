<?php
session_start();

include '../../connection/connection.php';
include '../checkrole.php';
include '../design/mainbody.php';


// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $related_projects = isset($_POST['related']) ? implode(",", $_POST['related']) : null;

    function uploadImage($fileInputName) {
        if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === 0) {
            $targetDir = "../uploads/";
            $fileName = basename($_FILES[$fileInputName]["name"]);
            $targetFile = $targetDir . time() . "_" . $fileName;
            move_uploaded_file($_FILES[$fileInputName]["tmp_name"], $targetFile);
            return $targetFile;
        }
        return null;
    }

    $main_image = uploadImage('main_image');
    $image1 = uploadImage('image1');
    $image2 = uploadImage('image2');
    $image3 = uploadImage('image3');

    $stmt = $conn->prepare("INSERT INTO project (title, description, main_image, image1, image2, image3, related_projects) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $title, $description, $main_image, $image1, $image2, $image3, $related_projects);

    if ($stmt->execute()) {
        header("Location: admin_add_project.php?success=1");
        exit;
    } else {
        echo "Error inserting project.";
    }
}

// Fetch existing projects for relation selection
$projects = [];
$res = $conn->query("SELECT id, title FROM project ORDER BY id DESC");
while ($row = $res->fetch_assoc()) {
    $projects[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Project with Related</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="max-w-3xl mx-auto bg-white p-8 rounded shadow mt-5">
    <h2 class="text-2xl font-bold mb-6">Add New Project</h2>

    <?php if (isset($_GET['success'])): ?>
        <p class="bg-green-100 text-green-800 p-3 rounded mb-4">Project added successfully!</p>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
        <div>
            <label class="block font-medium">Project Title</label>
            <input type="text" name="title" class="w-full border rounded p-2" required>
        </div>

        <div>
            <label class="block font-medium">Description</label>
            <textarea name="description" rows="5" class="w-full border rounded p-2" required></textarea>
        </div>

        <div>
            <label class="block font-medium">Main Image</label>
            <input type="file" name="main_image" required>
        </div>
        <div>
            <label class="block font-medium">Image 1 (optional)</label>
            <input type="file" name="image1">
        </div>
        <div>
            <label class="block font-medium">Image 2 (optional)</label>
            <input type="file" name="image2">
        </div>
        <div>
            <label class="block font-medium">Image 3 (optional)</label>
            <input type="file" name="image3">
        </div>

        <div>
            <label class="block font-medium mb-2">Related Projects (optional)</label>
            <div class="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto border p-2 rounded">
                <?php foreach ($projects as $proj): ?>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="related[]" value="<?php echo $proj['id']; ?>">
                        <span><?php echo htmlspecialchars($proj['title']); ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" type="submit">Add Project</button>
    </form>
</div>

</body>
</html>
