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

  <style>
    
footer {
  background-color:rgb(255, 238, 214);
  padding: 2rem 4rem;
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  font-size: 0.9rem;
  font-family: 'Montserrat', sans-serif;
}

.footer-left {
  flex: 1 1 300px;
}

.footer-logo {
  text-align: left;
  margin-bottom: 1rem;
}

.footer-logo img {
  height: 40px;
  width: auto;
}

.footer-info {
  display: flex;
  flex-direction: column;
  gap: 0.8rem;
}

.footer-detail {
  display: flex;
  align-items: center;
  gap: 0.6rem;
}

.footer-icon {
  height: 20px;
  width: 20px;
}

.footer-detail p {
  margin: 0;
  line-height: 1.3;
}

.quick-links {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  flex: 1 1 200px;
}

.quick-links h4 {
  color: #1f96c9;
  text-align: right;
}

.quick-links a {
  text-decoration: none;
  margin: 0.2rem;
  color: #333;
  text-align: right;
  transition: color 0.3s ease-in-out;
}

.quick-links a:hover {
  color: #e4a314;
}

.footer-bottom {
  background: #1f96c9;
  color: white;
  text-align: center;
  padding: 0.5rem;
  font-size: 0.8rem;
  font-family: 'Montserrat', sans-serif;
}

  </style>