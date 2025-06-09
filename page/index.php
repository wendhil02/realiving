<?php
include '../connection/connection.php';
include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Realiving Design Center </title>
  <link rel="icon" type="image/png" href="images/favicon.png">
  <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&family=Montserrat&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>

<body>

  <section class="hero">
    <div class="slideshow-container" id="blurTarget">
      <div class="slideshow-ombre"></div>
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
    <div class="hero-text">
      <p>Your dream interiors, crafted with purpose and personality.</p>
      <h1>Transform your space with timeless design</h1>
      <a href="#services" class="hero-btn animate-pop delay-4">GET STARTED</a>
    </div>
  </section>

  <section class="services" id="services" data-aos="fade-up">
    <div class="section-header animate-up delay-1">
      <h2>OUR SERVICES</h2>
      <hr class="section-divider">
      <p class="section-subtext">Crafted for comfort. Delivered with care.</p>
    </div>
    <div class="service-container">
      <div class="service-card animate-pop delay-1">
        <img src="./images/Design.png" alt="Design Service">
        <h3>Design</h3>
        <p>We create smart, space-saving, and stylish designs tailored to your space and lifestyle needs.</p>
      </div>
      <div class="service-card animate-pop delay-2">
        <img src="./images/Fabricate.png" alt="Fabricate Service">
        <h3>Fabrication</h3>
        <p>Using quality materials, we build each piece with precision to ensure durability and a modern finish.</p>
      </div>
      <div class="service-card animate-pop delay-3">
        <img src="./images/Delivery.png" alt="Delivered Service">
        <h3>Delivery</h3>
        <p>We transport your furniture safely and on time—straight to your doorstep.</p>
      </div>
      <div class="service-card animate-pop delay-4">
        <img src="./images/Installation.png" alt="Installation Service">
        <h3>Installation</h3>
        <p>Our team handles the setup efficiently, making sure everything is perfectly fitted and ready to use.</p>
      </div>
    </div>
    <a href="#services" class="view-btn section-btn animate-pop delay-4">VIEW ALL SERVICES </a>
  </section>

  <section class="furniture-offer">
    <div class="furniture-overlay">
      <div class="furniture-content">
        <h2 class="animate-up delay-1">Exclusive Furniture Packages</h2>
        <p class="animate-up delay-2">Stylish. Affordable. Ready for your space.</p>
        <a href="#contact" class="offer-btn animate-up delay-3">INQUIRE NOW</a>
      </div>
    </div>
  </section>

  <section class="rooms-and-furniture">
    <div class="box">
      <div class="text-wrapper-4 animate-up delay-1">ROOMS</div>
      <div class="group">
        <div class="CR animate-up delay-1">
          <img class="image" src="images/comfort-room.jpg" />
          <div class="text-wrapper">COMFORT ROOM</div>
        </div>
        <div class="k animate-up delay-2">
          <img class="image" src="images/kitchen.png" />
          <div class="text-wrapper">KITCHEN</div>
        </div>
        <div class="LR animate-up delay-3">
          <img class="image" src="images/living-room.png" />
          <div class="text-wrapper">LIVING ROOM</div>
        </div>
        <div class="BR animate-up delay-4">
          <img class="image" src="images/bedroom.png" />
          <div class="text-wrapper">BEDROOM</div>
        </div>
      </div>
    </div>
  </section>

  <section class="top-modular-cabinets">
    <div class="box">
      <div class="text-wrapper-4 animate-up delay-1">TOP MODULAR CABINETS</div>
      <div class="group">
        <div class="CB animate-up delay-1">
          <img class="image-cabinet" src="./images/cabinet-example.png" />
          <div class="text-wrapper-1">CABINET</div>
          <a href="your-link-here.html" class="get-now-btn">GET NOW</a>
        </div>
        <div class="CB-1 animate-up delay-2">
          <img class="image-cabinet" src="./images/cabinet-example.png" />
          <div class="text-wrapper-1">CABINET</div>
          <a href="your-link-here.html" class="get-now-btn">GET NOW</a>
        </div>
        <div class="CB-2 animate-up delay-3">
          <img class="image-cabinet" src="./images/cabinet-example.png" />
          <div class="text-wrapper-1">CABINET</div>
          <a href="your-link-here.html" class="get-now-btn">GET NOW</a>
        </div>
        <div class="CB-3 animate-up delay-4">
          <img class="image-cabinet" src="./images/cabinet-example.png" />
          <div class="text-wrapper-1">CABINET</div>
          <a href="your-link-here.html" class="get-now-btn">GET NOW</a>
        </div>
      </div>
    </div>
  </section>

  <section class="ads-banner">
    <div class="ads-slide active" style="background-image: url('./images/alphaland-1.jpg');">
      <div class="ads-overlay">
        <p>Participated in WORLDBEX Convention Center 2023</p>
      </div>
    </div>
    <div class="ads-slide" style="background-image: url('./images/alphaland-2.jpg');">
      <div class="ads-overlay">
        <p>Over 100+ successful interior projects nationwide</p>
      </div>
    </div>
    <div class="ads-slide" style="background-image: url('./images/alphaland-3.jpg');">
      <div class="ads-overlay">
        <p>Featured in Design & Architecture Weekly PH</p>
      </div>
    </div>
  </section>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const adsSlides = document.querySelectorAll('.ads-slide');
      let adsIndex = 0;

      setInterval(() => {
        adsSlides[adsIndex].classList.remove('active');
        adsIndex = (adsIndex + 1) % adsSlides.length;
        adsSlides[adsIndex].classList.add('active');
      }, 4000);
    });
  </script>

  <section class="reviews-section">
    <h2 class="section-title animate-up delay-1">What Clients Say</h2>
    <p class="section-subtitle animate-up delay-1">
      Don’t just take our word for it. Here’s what our valued clients have to say about their experience with Realiving Design Center Corporation.
    </p>

    <div class="reviews-container">
      <!-- Review Card -->
      <div class="review-card animate-up delay-3">
        <div class="stars">☆☆☆☆☆</div>
        <p class="review-body">Review body</p>
        <div class="review-footer">
          <img src="https://i.pravatar.cc/40" alt="Reviewer photo" class="reviewer-img">
          <div>
            <p class="reviewer-name">Reviewer name</p>
            <p class="review-date">Date</p>
          </div>
        </div>
      </div>

      <!-- Repeat 2 more times -->
      <div class="review-card animate-up delay-3">
        <div class="stars">☆☆☆☆☆</div>
        <p class="review-body">Review body</p>
        <div class="review-footer">
          <img src="https://i.pravatar.cc/40" alt="Reviewer photo" class="reviewer-img">
          <div>
            <p class="reviewer-name">Reviewer name</p>
            <p class="review-date">Date</p>
          </div>
        </div>
      </div>

      <div class="review-card animate-up delay-3">
        <div class="stars">☆☆☆☆☆</div>
        <p class="review-body">Review body</p>
        <div class="review-footer">
          <img src="https://i.pravatar.cc/40" alt="Reviewer photo" class="reviewer-img">
          <div>
            <p class="reviewer-name">Reviewer name</p>
            <p class="review-date">Date</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="news-wrapper" data-aos="fade-up">
    <h2>Latest News</h2>
    <div class="news-carousel-wrapper">
      <div class="news-scroll-container">
        <?php
        $sql = "SELECT * FROM news ORDER BY id DESC LIMIT 6";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
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
    </div>
  </section>

  <section class="contact-section">
    <div class="contact-container">
      <div class="contact-text">
        <h2><i class="animate-left delay-2">Contact Us</i></h2>
        <p class="animate-left delay-3">We would love to speak with you.</p>
      </div>
      <form class="contact-form animate-right delay-3" action="index.php" method="POST">
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

  <?php
  include 'ads/promo-banner.php';
  include 'footer/footer.php';
  ?>
  <script src="script.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  <script>
    AOS.init({
      duration: 800,
      offset: 120,
      once: true
    });

    window.addEventListener('scroll', function() {
      const header = document.querySelector('header');
      if (window.scrollY > 50) {
        header.classList.add('scrolled');
      } else {
        header.classList.remove('scrolled');
      }
    });

    window.addEventListener('scroll', function() {
      const header = document.querySelector('header');
      if (window.scrollY > 50) {
        header.classList.add('scrolled');
      } else {
        header.classList.remove('scrolled');
      }
    });
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
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
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