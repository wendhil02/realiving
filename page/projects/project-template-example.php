<?php
// Include your database connection
include($_SERVER['DOCUMENT_ROOT'] . '/realiving_updated/code/database.php');

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

include "../header.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&family=Montserrat&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <title><?php echo htmlspecialchars($project['title']); ?> - Project Details</title>
    <link rel="stylesheet" href="./project-template.css?v=2.0" />
</head>
<body class="scroll-hidden">

<section class="sub-header" style="background-image: url('<?php echo htmlspecialchars($project['main_image']); ?>');">
    <section class="sub-header-text"> 
        <h1 class="sub-header-text"><?php echo htmlspecialchars($project['title']); ?></h1>
    </section>
</section>

<?php
include "../ads/promo-banner.php";
?>

<section>
<div class="project-container">
    <!-- Top Left Description -->
    <section class="grid-item project-description animate-up delay-1">
        <h1 class="animate-up delay-1"><?php echo htmlspecialchars($project['title']); ?></h1>
        <p class="animate-up delay-1"><?php echo nl2br(htmlspecialchars($project['description'])); ?></p>
    </section>

    <!-- Top Right Image -->
    <section class="grid-item project-image-top animate-up delay-1">
        <?php var_dump($project['image1']); ?>
        <?php if (!empty($project['image1'])): ?>
            <div class="image-container">
                <img src="/realiving_updated/code/<?php echo htmlspecialchars($project['image1']); ?>" 
                     alt="<?php echo htmlspecialchars($project['title']); ?> - Overview">
            </div>
        <?php else: ?>
            <div class="image-placeholder"></div>
        <?php endif; ?>
        </section>

    <!-- Bottom Left Image -->
    <section class="grid-item project-image-bottom animate-left delay-3">
        <?php if (!empty($project['image2'])): ?>
            <div class="image-container">
                <img src="/realiving_updated/code/<?php echo htmlspecialchars($project['image2']); ?>" 
                     alt="<?php echo htmlspecialchars($project['title']); ?> - Details">
            </div>
        <?php else: ?>
            <div class="image-placeholder"></div>
        <?php endif; ?>
    </section>

    <!-- Bottom Right Contact Form -->
    <section class="grid-item contact-form-section animate-right delay-4">
        <h2 class="contact-header animate-right delay-4">INTERESTED?</h2>
        <form class="contact-form animate-right delay-4" method="POST" action="process_contact.php">
            <input type="hidden" name="project_id" value="<?php echo htmlspecialchars($project['id']); ?>">
            <div class="form-group">
                <input type="text" name="name" placeholder="NAME *" required>
            </div>
            <div class="form-group">
                <input type="tel" name="phone" placeholder="PHONE NUMBER *" required>
            </div>
            <div class="form-group">
                <input type="email" name="email" placeholder="EMAIL *" required>
            </div>
            <div class="form-group">
                <input type="text" name="location" placeholder="LOCATION *">
            </div>
            <button type="submit" class="submit-btn">SUBMIT</button>
        </form>
    </section>
</div>
</section>

<section class="top-modular-cabinets">
  <div class="box">
    <div class="text-wrapper-4 animate-up delay-1">TOP MODULAR CABINETS</div>
      <div class="group">
        <div class="CB animate-up delay-1">
          <img class="image-cabinet" src="../images/cabinet-example.png" />
          <div class="text-wrapper-1">CABINET</div>
          <a href="your-link-here.html" class="get-now-btn">GET NOW</a>
        </div>
        <div class="CB-1 animate-up delay-2">
          <img class="image-cabinet" src="../images/cabinet-example.png" />
          <div class="text-wrapper-1">CABINET</div>
          <a href="your-link-here.html" class="get-now-btn">GET NOW</a>
        </div>
        <div class="CB-2 animate-up delay-3">
          <img class="image-cabinet" src="../images/cabinet-example.png" />
          <div class="text-wrapper-1">CABINET</div>
          <a href="your-link-here.html" class="get-now-btn">GET NOW</a>
        </div>
        <div class="CB-3 animate-up delay-4">
          <img class="image-cabinet" src="../images/cabinet-example.png" />
          <div class="text-wrapper-1">CABINET</div>
          <a href="your-link-here.html" class="get-now-btn">GET NOW</a>
        </div>
      </div>
    </div>
</section>

<?php
include '../footer.php';
?>

</body>
</html>