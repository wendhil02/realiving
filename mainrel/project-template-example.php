<?php
// Include your database connection
include 'database.php';

// Get the project ID from the URL
if (isset($_GET['id'])) {
    $project_id = $_GET['id'];

    // Fetch the project details from the database
    $sql = "SELECT * FROM project WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the project data
        $project = $result->fetch_assoc();
    } else {
        echo "<p>Project not found.</p>";
        exit;
    }

    // Fetch other projects (not necessarily related) to display in the "Related Projects" section
    $other_projects_sql = "SELECT * FROM project WHERE id != ? ORDER BY RAND() LIMIT 4";
    $other_projects_stmt = $conn->prepare($other_projects_sql);
    $other_projects_stmt->bind_param("i", $project_id); // Exclude the current project
    $other_projects_stmt->execute();
    $other_projects_result = $other_projects_stmt->get_result();

    $other_projects = [];
    while ($row = $other_projects_result->fetch_assoc()) {
        $other_projects[] = $row;
    }

    // Close the database connection
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
</head>
<body>

<section class="sub-header" style="background-image: url('<?php echo htmlspecialchars($project['main_image']); ?>');">
    <h1><?php echo htmlspecialchars($project['title']); ?></h1>
</section>

<section class="project-section">
    <div class="project-container">
        <!-- Project Text Section -->
        <div class="project-text">
            <h2>Realiving Design Center's Work for <?php echo htmlspecialchars($project['title']); ?></h2>
            <p><?php echo nl2br(htmlspecialchars($project['description'])); ?></p>
        </div>

        <!-- Project Images Section -->
        <div class="project-images">
            <?php if (!empty($project['image1'])): ?>
                <div class="project-image">
                    <img src="<?php echo htmlspecialchars($project['image1']); ?>" alt="Image 1">
                    <p>Dining Area</p>
                </div>
            <?php endif; ?>

            <?php if (!empty($project['image2'])): ?>
                <div class="project-image">
                    <img src="<?php echo htmlspecialchars($project['image2']); ?>" alt="Image 2">
                    <p>Living Room</p>
                </div>
            <?php endif; ?>

            <?php if (!empty($project['image3'])): ?>
                <div class="project-image">
                    <img src="<?php echo htmlspecialchars($project['image3']); ?>" alt="Image 3">
                    <p>Bathroom</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php
$current_project_id = $_GET['id']; // Assuming the ID is passed in the URL (e.g., project_detail.php?id=1)

// Fetch related projects (excluding the current project)
$sql = "SELECT * FROM project WHERE id != ? LIMIT 3"; // You can change LIMIT to any number you prefer
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $current_project_id);
$stmt->execute();
$result = $stmt->get_result();

?><!-- Related Projects Section -->
<section class="related-projects">
    <h2>Related Projects</h2>
    <div class="project-grid">
        <?php if ($result->num_rows > 0): ?>
            <?php while($project = $result->fetch_assoc()): ?>
                <div class="project-card">
                    <img src="<?php echo htmlspecialchars($project['main_image']); ?>" alt="Project Image">
                    <div class="project-title"><?php echo htmlspecialchars($project['title']); ?></div>
                    <div class="project-description"><?php echo htmlspecialchars(substr($project['description'], 0, 100)) . '...'; ?></div>
                    <a href="project-template-example.php?id=<?php echo $project['id']; ?>" class="view-more">View More</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No related projects found.</p>
        <?php endif; ?>
    </div>
</section>


<?php
include 'footer.php';
?>
</body>

</html>

<style>


</style>