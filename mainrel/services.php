<?php
include 'database.php';

include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Realiving Design Center</title>
  <link rel="icon" type="image/png" href="./images/favicon.png">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="./css/services.css?v=2.0" />
</head>
<body>
  <!-- Sub Header (Headline) -->
  <section class="sub-header">
  <h1>Services</h1>
</section>

<section class="services">
  <div class="service-intro">
    <p>At Realiving Design Center, we offer top-notch design, fabrication, delivery, and installation services — all crafted with precision and style to bring your spaces to life.</p>
  </div>

  <div class="service-grid">
    <div class="service-card">
      <img src="./images/alphaland-1.jpg" alt="Design">
      <h2>Design</h2>
      <p>We transform your ideas into creative, functional designs that last.</p>
      <a href="design.php" class="service-btn">VIEW MORE</a>
    </div>

    <div class="service-card">
      <img src="./images/alphaland-1.jpg" alt="Fabrication">
      <h2>Fabrication</h2>
      <p>Expert fabrication using premium materials and precise craftsmanship.</p>
      <a href="#" class="service-btn">VIEW MORE</a>
    </div>

    <div class="service-card">
      <img src="./images/alphaland-1.jpg" alt="Delivery">
      <h2>Delivery</h2>
      <p>Safe and timely delivery, ensuring your pieces arrive perfectly.</p>
      <a href="#" class="service-btn">VIEW MORE</a>
    </div>

    <div class="service-card">
      <img src="./images/alphaland-1.jpg" alt="Installation">
      <h2>Installation</h2>
      <p>Flawless installation services, handled by skilled professionals.</p>
      <a href="#" class="service-btn">VIEW MORE</a>
    </div>
  </div>
</section>

</section>

<?php
include 'footer.php';
?>
