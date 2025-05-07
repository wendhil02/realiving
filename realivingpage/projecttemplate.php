<?php
include '../connection/connection.php';

if (isset($_GET['id'])) {
    $project_id = $_GET['id'];

    $sql = "SELECT * FROM project WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $project = $result->fetch_assoc();
    } else {
        echo "<p>Project not found.</p>";
        exit;
    }

    $other_projects_sql = "SELECT * FROM project WHERE id != ? ORDER BY RAND() LIMIT 4";
    $other_projects_stmt = $conn->prepare($other_projects_sql);
    $other_projects_stmt->bind_param("i", $project_id);
    $other_projects_stmt->execute();
    $other_projects_result = $other_projects_stmt->get_result();

    $other_projects = [];
    while ($row = $other_projects_result->fetch_assoc()) {
        $other_projects[] = $row;
    }

    $stmt->close();
    $other_projects_stmt->close();
} else {
    echo "<p>No project selected.</p>";
    exit;
}

include "header.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($project['title']); ?> - Project Details</title>
    <link rel="stylesheet" href="./css/project-template.css?v=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-white text-gray-800">

<!-- Hero Banner -->
<section class="h-72 bg-cover bg-center flex items-center justify-center" style="background-image: url('<?php echo htmlspecialchars($project['main_image']); ?>');">
    <h1 class="text-white text-4xl font-bold bg-black bg-opacity-50 px-6 py-3 rounded">
        <?php echo htmlspecialchars($project['title']); ?>
    </h1>
</section>

<!-- Project Info -->
<section class="max-w-5xl mx-auto p-6">
    <p class="text-gray-700 text-lg mb-4"><?php echo nl2br(htmlspecialchars($project['description'])); ?></p>
    <p class="text-gray-500 mb-8 italic"><?php echo nl2br(htmlspecialchars($project['address'])); ?></p>

    <!-- Additional Images -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-10">
        <?php foreach (['image1', 'image2', 'image3'] as $imgField): ?>
            <?php if (!empty($project[$imgField])): ?>
                <img src="<?php echo htmlspecialchars($project[$imgField]); ?>" alt="Project Image" class="w-full h-60 object-cover rounded shadow">
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</section>

<!-- Related Projects -->
<section class="bg-gray-100 py-12 px-4 md:px-16">
    <h2 class="text-2xl font-semibold text-center mb-10">Related Projects</h2>
    <?php if (!empty($other_projects)): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 max-w-6xl mx-auto">
            <?php foreach ($other_projects as $related): ?>
                <div class="bg-white rounded shadow p-4 flex flex-col justify-between hover:shadow-lg transition">
                    
                    <!-- Image Wrapper -->
                    <div class="w-full h-48 overflow-hidden rounded mb-4 bg-gray-200 flex items-center justify-center">
                        <img src="<?php echo htmlspecialchars($related['main_image']); ?>" alt="Related Project" class="object-cover h-full w-full transition duration-300 hover:scale-105">
                    </div>

                    <div>
                        <h3 class="text-lg font-bold"><?php echo htmlspecialchars($related['title']); ?></h3>
                        <p class="text-sm text-gray-600 mt-2"><?php echo htmlspecialchars(substr($related['description'], 0, 100)) . '...'; ?></p>
                    </div>
                    <a href="project-template.php?id=<?php echo $related['id']; ?>" class="mt-4 inline-block bg-blue-600 text-white text-sm font-semibold px-4 py-2 rounded hover:bg-blue-700 transition">
                        View More
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-center text-gray-500">No related projects found.</p>
    <?php endif; ?>
</section>

</body>
</html>
