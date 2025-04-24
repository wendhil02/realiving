<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Realiving Design Center </title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&family=Montserrat&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="contact.css">
</head>
<body>
  <header>
    <div><img src="./images/logo.png" height="30"></div>
    <nav>
      <a href="index.html">Home</a>
      <a href="about.html">About</a>
      <div class="dropdown">
        <a href="#">Projects ▾</a>
        <div class="dropdown-content">
          <a href="#">Alphaland Baguio M  ountain Lodge</a>
          <a href="#">Best Western Hotel</a>
          <a href="#">The Bellevue Manila</a>
          <a href="#">Megaworld</a>
          <a href="#">Makati Development Corporation</a>
          <a href="#">Metropolitan Medical Center</a>
          <a href="#">The B Hotel</a>
          <a href="#">SMDC</a>
          <a href="#">Hampton Gardens</a>
          <a href="#">CityMall</a>
          <a href="#">Tradizo Enclave</a>
          <a href="#">DMCI Homes</a>
          <a href="#">The Hive Taytay</a>
          <a href="#">Chateau Royale</a>
          <a href="#">Camaya Coast</a>
          <a href="#">The Linear Makati</a>
          <a href="#">Trans-Asia Oil & Energy Development</a>
          <a href="#">Concentrix</a>
          <a href="#">Megawide</a>
          <a href="#">Xavier School</a>
          <a href="#">Nostalji Enclave</a>
          <a href="#">Amaia</a>
          <a href="#">Zuri Residences</a>
        </div>
      </div>
      <a href="#">News</a>
      <a href="contact.html">Contact</a>
    </nav>
  </header>

  <section class="contact-section">
    <div class="contact-container">
      <div class="contact-text">
        <h2><i>Contact Us</i></h2>
        <p>We would love to speak with you.<br>Feel free to reach out using the below details.</p>
      </div>
      <form class="contact-form">
        <div class="form-range">
          <div class="form-range-labels">
            <span>Contact</span>
            <span>Inquiry</span>
          </div>
          <input type="range" id="contact-range" name="contact-range" value="0" min="0" max="1" disabled>
        </div>
        <input type="text" placeholder="E.g. Juan Dela Cruz" required>
        <input type="text" placeholder="E.g. (+63) 923 456 789" required>
        <input type="email" placeholder="E.g. juan.delacruz@gmail.com" required>
        <button type="submit">Next</button>
      </form>
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
      <a href="contact.html">Contact Us</a>
      <a href="#">Privacy Policy</a>
      <a href="#">Terms & Conditions</a>
    </div>
  </footer>

  <div class="footer-bottom">
    ©2025 Realiving Design Center Corporation. All Rights Reserved.
  </div>
  <script src="script.js"></script>
</body>
</html>