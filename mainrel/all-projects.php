<?php
include("database.php");

include("header.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Realiving Design Center</title>
  <link rel="icon" type="image/png" href="./images/favicon.png">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="./css/all-projects.css" />
</head>
<body>


  <section class="sub-header">
    <h1>Projects</h1>
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
            <!-- Link the project title to project_detail.php with the project id -->
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
</section>

  <footer>
    <div class="footer-left">
      <div class="footer-logo">
        <img src="./images/logo.png" alt="Realiving Logo">
      </div>
      <div class="footer-info">
        <div class="footer-detail">
          <img src="./images/location-icon.png" class="footer-icon" alt="Location">
          <p>MC Premier – EDSA Balintawak, Quezon City</p>
        </div>
        <div class="footer-detail">
          <img src="./images/call-icon.png" class="footer-icon" alt="Call">
          <p>Company number</p>
        </div>
        <div class="footer-detail">
          <img src="./images/email-icon.png" class="footer-icon" alt="Email">
          <p>Company Email</p>
        </div>
        <div class="footer-detail">
          <img src="./images/calendar-icon.png" class="footer-icon" alt="Hours">
          <p><b>Mon–Fri:</b> 7:00AM – 5:00PM</p>
        </div>
      </div>
    </div>
    
    <div class="quick-links">
      <h4>QUICK LINKS</h4>
      <a href="index.html">Home</a>
      <a href="about.html">About</a>
      <a href="services.html">Services</a>
      <a href="all-projects.html">Projects</a>
      <a href="news.html">News</a>
      <a href="contact.html">Contact Us</a>
      <a href="#">Privacy Policy</a>
    </div>
  </footer>

  <div class="footer-bottom">
    ©2025 Realiving Design Center Corporation. All Rights Reserved.
  </div>
  <script src="script.js"></script>
</body>
</html>
  
  
