  <?php 
  include("database.php");


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
  </script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

  include("header.php");
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
    <link rel="stylesheet" href="./css/contact.css?v=2.0">
  </head>
  <body>


  <section class="sub-header">
    <h1>Contact</h1>
  </section>

  <section class="contact-section">
    <div class="contact-container">
      <div class="contact-details">
        <div class="detail-item">
          <img src="./images/location-icon.png" alt="Location Icon" class="icon">
          <div>
            <h4>Address</h4>
            <p>MC Premier – EDSA Balintawak, Quezon City</p>
          </div>
        </div>
        <div class="detail-item">
          <img src="./images/call-icon.png" class="icon">
          <div>
            <h4>Call Us Now</h4>
            <p>(+63) 923 456 789</p>
          </div>
        </div>
        <div class="detail-item">
          <img src="./images/email-icon.png" class="icon">
          <div>
            <h4>Mail Us Now</h4>
            <p>Company Email</p>
          </div>
        </div>
        <div class="detail-item">
          <img src="./images/time-icon.png" class="icon">
          <div>
            <h4>Office Hours</h4>
            <p>Mon - Fri: 9:00 AM - 6:00 PM</p>
          </div>
        </div>
      </div>
      <form class="contact-form" action="contact.php" method="POST">
      <h3 class="form-heading">Fill out the form and we’ll get back to you!</h3>
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

  <section class="map-section">
    <h2>Site Location</h2>
    <div class="map-container">
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3859.9789399779693!2d120.99845827981834!3d14.657136639190796!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b712dc89bb99%3A0x935f93a6e49ab912!2sMC%20Premiere!5e0!3m2!1sen!2sph!4v1745391323582!5m2!1sen!2sph" 
      width="100%" 
      height="450" 
      style="border:0;" 
      allowfullscreen="" 
      loading="lazy" 
      referrerpolicy="no-referrer-when-downgrade">
      </iframe>
    </div>
  </section>
    <script src="script.js"></script>
    <?php
include 'footer.php';
?>
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
