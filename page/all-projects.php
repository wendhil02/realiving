<?php
include "../connection/connection.php";

include "header.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Realiving Design Center</title>
  <link rel="icon" type="image/png" href="/realiving_updated/code/images/favicon.png">
  <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&family=Montserrat&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="projects/all-projects.css" />
</head>
<body>



<section class="sub-header">
  <h1>Projects</h1>
  <p class="project-categories">
    <a href="#" class="category-link active-category" data-category="all">ALL</a>
    <a href="#" class="category-link" data-category="site">SITE PROJECTS</a>
    <a href="#" class="category-link" data-category="commercial">COMMERCIAL SPACES</a>
    <a href="#" class="category-link" data-category="residential">RESIDENTIAL INTERIORS</a>
  </p>
</section>

<section class="project-heading">
  <h1>
    <span id="project-title-prefix">PROJECTS</span> |
    <span id="project-title-category">ALL</span>
  </h1>
</section>

<section class="projects">
  <div class="container">
    <?php
    $sql = "SELECT * FROM project ORDER BY id DESC"; // Assuming you have a `projects` table
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        ?>
        <div class="card">
          <div class="card-image">
            <img src="<?php echo htmlspecialchars($row['main_image']); ?>" alt="Main Image">
            <img class="hover-img" src="<?php echo htmlspecialchars($row['hover_image']); ?>" alt="Hover Image">
          </div>
          <div class="card-content">
            <a href="project-template-example.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="title">
              <?php echo htmlspecialchars($row['title']); ?>
            </a>
            <p class="location"><?php echo htmlspecialchars($row['address']); ?></p>
          </div>
        </div>
        <?php
      }
    } else {
      echo "<p>No projects available yet.</p>";
    }

    $conn->close();
    ?>
  </div>
  <a href="#" class="button">SHOW MORE</a>
</section>

<section class="cabinet-cost-section">
  <div class="cabinet-content">
    <div class="cabinet-text">
      <h2>Know Your Cabinet Cost with Confidence</h2>
      <p>
      Have a vision in mind but not sure where to begin? Let's talk. Our design experts are ready to guide you through every step—from concept to completion. Whether it's a modular setup or a fully customized build, we’ll help bring your ideas to life with precision and creativity.
      </p>
      <a href="#" class="cabinet-btn">BOOK AN APPOINTMENT NOW</a>
    </div>
    <div class="cabinet-image">
      <img src="images/background-image.jpg" alt="Kitchen cabinet design">
    </div>
  </div>
</section>

<?php 
include 'ads/promo-banner.php';
include 'footer/footer.php'; 
?>

<script>
  const categoryLinks = document.querySelectorAll('.category-link');
  const titlePrefix = document.getElementById('project-title-prefix');
  const titleCategory = document.getElementById('project-title-category');

  categoryLinks.forEach(link => {
    link.addEventListener('click', function(e) {
      e.preventDefault();

      // Remove active from all links
      categoryLinks.forEach(link => link.classList.remove('active-category'));

      // Add active to clicked link
      this.classList.add('active-category');

      // Get category name and format title
      const categoryText = this.textContent.trim();
      titleCategory.textContent = categoryText.toUpperCase();
    });
  });
</script>
<script src="script.js"></script>
</body>
</html>