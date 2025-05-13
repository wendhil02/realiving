<?php
include 'database.php';
// Include your database connection
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {


  // Get data from form
  $name = $_POST['name'] ?? '';
  $phone = $_POST['phone'] ?? '';
  $email = $_POST['email'] ?? '';
  $subject = $_POST['subject'] ?? '';
  $message = $_POST['message'] ?? '';

  // Insert query
  $sql = "INSERT INTO contact (name, phone, email, subject, message) VALUES (?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sssss", $name, $phone, $email, $subject, $message);

  if ($stmt->execute()) {
    echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('thankYouModal').style.display = 'block';
        document.querySelector('.close').onclick = function() {
            document.getElementById('thankYouModal').style.display = 'none';
            window.location.href = 'index.php';
        };
        window.onclick = function(event) {
            if (event.target === document.getElementById('thankYouModal')) {
                document.getElementById('thankYouModal').style.display = 'none';
                window.location.href = 'index.php';
            }
        };
    });
</script>";  } else {
      echo "Error: " . $stmt->error;
  }

  $stmt->close();
  $conn->close();
}
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
  <link rel="stylesheet" href="./css/style.css">
</head>
<body>

  <section class="hero">
    <div class="slideshow-container" id="blurTarget">
      <div class="slide fade active">
        <img src="./images/background-image.jpg" alt="Slide 1">
      </div>
      <div class="slide fade">
        <img src="./images/background-image2.jpg" alt="Slide 2">
      </div>
      <div class="slide fade">
        <img src="./images/background-image3.jpg" alt="Slide 3">
      </div>
    </div>
    <div class="hero-text"><i>Elevate your Space</i></div>
  </section>

  <section class="projects-section">
    <div class="projects-container">
      <h2>Accomplished Projects</h2>
      <hr class="section-divider">
      <div class="projects-grid">
        <!-- Row 1 -->
        <a href="#" target="_blank">
          <img src="./images/project-1.png" alt="Project 1">
        </a>
        <a href="#" target="_blank">
          <img src="./images/project-2.png" alt="Project 2">
        </a>
        <a href="#" target="_blank">
          <img src="./images/project-3.png" alt="Project 3">
        </a>
        <a href="#" target="_blank">
          <img src="./images/project-4.png" alt="Project 4">
        </a>
        <a href="#" target="_blank">
          <img src="./images/project-5.png" alt="Project 5">
        </a>
  
        <!-- Row 2 -->
        <a href="#" target="_blank">
          <img src="./images/project-6.png" alt="Project 6">
        </a>
        <a href="#" target="_blank">
          <img src="./images/project-7.png" alt="Project 7">
        </a>
        <a href="#" target="_blank">
          <img src="./images/project-8.png" alt="Project 8">
        </a>
        <a href="project-template-example.html" target="_blank">
          <img src="./images/project-9.png" alt="Project 9">
        </a>
        <a href="#" target="_blank">
          <img src="./images/project-10.png" alt="Project 10">
        </a>
  
        <!-- Row 3 -->
        <a href="#" target="_blank">
          <img src="./images/project-11.png" alt="Project 11">
        </a>
        <a href="#" target="_blank">
          <img src="./images/project-12.png" alt="Project 12">
        </a>
        <a href="#" target="_blank">
          <img src="./images/project-13.png" alt="Project 13">
        </a>
        <a href="#" target="_blank">
          <img src="./images/project-14.png" alt="Project 14">
        </a>
        <a href="#" target="_blank">
          <img src="./images/project-15.png" alt="Project 15">
        </a>
      </div>
    </div>
  </section>
  
  
  <section class="services" id="services">
    <div class="section-header">
      <h2>Services</h2>
      <hr class="section-divider">
    </div>
    <div class="service-container">
      <div class="service-card">
        <h3>DESIGN</h3>
        <p>We create smart, space-saving, and stylish designs tailored to your space and lifestyle needs.</p>
        <a href="design.php" class="view-btn">Read More</a>
      </div>
      <div class="service-card">
        <h3>FABRICATE</h3>
        <p>Using quality materials, we build each piece with precision to ensure durability and a modern finish.</p>
        <a href="#fabricate" class="view-btn">Read More</a>
      </div>
      <div class="service-card">
        <h3>DELIVERED</h3>
        <p>We transport your furniture safely and on time—straight to your doorstep.</p>
        <a href="#delivered" class="view-btn">Read More</a>
      </div>
      <div class="service-card">
        <h3>INSTALLATION</h3>
        <p>Our team handles the setup efficiently, making sure everything is perfectly fitted and ready to use.</p>
        <a href="#installation" class="view-btn">Read More</a>
      </div>
    </div>
  </section>

  <section class="news-wrapper">
  <h2>Latest News</h2>
  <hr class="section-divider">
</section>

<div class="news-scroll-container">
  <?php

  $sql = "SELECT * FROM news ORDER BY id DESC LIMIT 6"; // Get latest 6 news
  $result = $conn->query($sql);

  $newsCount = 0; // to count the number of news for the dots

  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      ?>
      <div class="news-card">
        <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="News <?php echo $row['id']; ?>">
        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
        <p><?php echo htmlspecialchars($row['description']); ?></p>
        <a href="news-template.php?id=<?php echo $row['id']; ?>" class="view-more-btn">View More</a>
      </div>
      <?php
    }
  } else {
    echo "<p>No news available yet.</p>";
  }
  ?>
</div>

<div class="news-dots">
  <?php
  for ($i = 0; $i < $newsCount; $i++) {
    if ($i == 0) {
      echo '<span class="dot active"></span>';
    } else {
      echo '<span class="dot"></span>';
    }
  }
  $conn->close();
  ?>
</div>
  

  <section class="contact-section">
    <div class="contact-container">
      <div class="contact-text">
        <h2><i>Contact Us</i></h2>
        <p>We would love to speak with you.</p>
      </div>
      <form class="contact-form" action="index.php" method="POST">
      <input type="text" name="name" placeholder="E.g. Juan Dela Cruz" required>
      <input type="text" name="phone" placeholder="E.g. (+63) 923 456 789" required>
      <input type="email" name="email" placeholder="E.g. juan.delacruz@gmail.com" required>
      <input type="text" name="subject" placeholder="Subject" required>
      <textarea name="message" placeholder="Type your message here..." rows="5" required></textarea>
      <button type="submit" id="submitBtn">Submit</button>
    </form>     
    </div>
  </section>

  <!-- Modal -->
<div id="thankYouModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2>Thank You!</h2>
    <p>Thank you for reaching out to us.<br>Check your email for our response.</p>
  </div>
</div>

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
  <script src="script.js">
  </script>
</body>
</html>

<style>
  .modal {
  display: none; 
  position: fixed; 
  z-index: 999; 
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto; 
  background-color: rgba(0, 0, 0, 0.5); 
}

.modal-content {
  background-color: #fff;
  margin: 15% auto;
  padding: 30px;
  border-radius: 10px;
  width: 80%;
  max-width: 400px;
  text-align: center;
  font-family: 'Montserrat', sans-serif;
  box-shadow: 0 0 15px rgba(0,0,0,0.3);
}

.modal-content h2 {
  margin-bottom: 10px;
}

.close {
  color: #aaa;
  float: right;
  font-size: 24px;
  font-weight: bold;
  cursor: pointer;
}
</style>