<?php include "header.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Realiving Design Center</title>
  <link rel="icon" type="image/png" href="./images/favicon.png">
  <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&family=Montserrat&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <link rel="stylesheet" href="about/about.css?v=2.0" />
</head>

<body>

  <!-- Sub Header (Headline) -->
  <section class="sub-header">
    <h1>About Us</h1>
  </section>

  <section class="about-section" data-aos="fade-up">
    <div class="about-container">
      <div class="about-left">
        <h2>Our Vision</h2>
        <div class="section-divider"></div>
        <p>To be the elite provider of interiors and to be the forefront of the special architectural industry in the Philippines.</p>


      </div>
      <div class="about-right">
        <h2>Our Mission</h2>
        <div class="section-divider"></div>
        <p>To provide customized and sustanable modular cabinet solutions, utilizing cutting-edge technology, skilled craftsmanship, and a customer-centric approach, while ensuring timely delivery and exceeding client expectations.</p>
      </div>
    </div>
  </section>

  <section class="core-values-section">
    <div class="core-values-content">
      <h2 class="animate-up delay-1">Our Core Values</h2>
      <div class="core-values-cards">
        <div class="core-value-card animate-up delay-3">
          <img src="images/excellence-icon.png" alt="Excellence Icon">
          <h3>Excellence</h3>
          <p>We strive for excellence in every aspect of our work, from design conception to project completion.</p>
        </div>
        <div class="core-value-card animate-up delay-2">
          <img src="images/innovation-icon.png" alt="Innovation Icon">
          <h3>Innovation</h3>
          <p>We embrace innovation and continuously explore new techniques and materials to enhance our products and services.</p>
        </div>
        <div class="core-value-card animate-up delay-1">
          <img src="images/collaboration-icon.png" alt="Collaboration Icon">
          <h3>Collaboration</h3>
          <p>We believe in the power of collaboration and aim to build strong partnerships with industry professionals.</p>
        </div>
        <div class="core-value-card animate-up delay-2">
          <img src="images/integrity-icon.png" alt="Integrity Icon">
          <h3>Integrity</h3>
          <p>We conduct our business with integrity, transparency, and ethical practices.</p>
        </div>
        <div class="core-value-card animate-up delay-3">
          <img src="images/satisfaction-icon.png" alt="Customer Satisfaction Icon">
          <h3>Customer Satisfaction</h3>
          <p>We prioritize customer satisfaction and work closely with clients to understand their unique needs and deliver tailored solutions.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- New Section: About Us Quote -->
  <section class="company-quote-section" data-aos="fade-up">
    <blockquote class="company-quote">
      “Making your dream space a reality.”
    </blockquote>
  </section>

  <section class="company-section">
    <div class="company-container">
      <div class="company-text">
        <h2>Our Company</h2>
        <p>
          Realiving Design Center Corp. is a leading architectural fit-out company specializing in design, manufacturing, and installation of premium modular cabinets. We are driven by a passion for creating beautifully functional spaces that enhance the lives of our clients. Our team of experienced designers, engineers, and craftsmen work together to deliver exceptional results, exceeding expectations at every stage.
        </p>
        <br>
        <p>
          Realiving was duly incorporated under the trade name Brava Homes situated at Warehouse 5, Sunhope Compound, Purok 7, Brgy. Calasag, San Ildefonso, Bulacan. It primarily engages in wholesale and retail of competitively priced, high-quality construction, plumbing, and decorative materials to both projects and retailers nationwide. Some of the products the company carries are modular kitchen cabinets, shower enclosures/dividers, lavatory and plumbing fixtures, wooden/steel/PVC aluminum windows, bathtubs, and other ceramic items.
        </p>
      </div>
      <div class="company-image">
        <img src="images/background-image.jpg" alt="Company Image">
      </div>
    </div>
  </section>

  <?php
 
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
  </script>

</body>

</html>