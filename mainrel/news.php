<?php
include 'database.php';



include 'header.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Realiving Design Center </title>
  <link rel="icon" type="image/png" href="./images/favicon.png">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&family=Montserrat&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./css/news.css">
</head>
<body>
<section class="sub-header">
    <h1>News</h1>
</section>

<section class="news-section">
    <div class="news-grid">
        <?php 
        // Fetch news items
        $sql = "SELECT id, image, title, description FROM news ORDER BY id DESC"; // Fetch id, image, title, and description
        $result = $conn->query($sql);

        if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="news-card">
                    <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="News Image">
                    <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                    <p><?php echo htmlspecialchars($row['description']); ?></p>
                    <a href="news-template.php?id=<?php echo $row['id']; ?>" class="view-more">View More</a> <!-- Pass the ID via URL -->
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No news available at the moment.</p>
        <?php endif; ?>
    </div>
</section>

<?php
$conn->close();
?>

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
