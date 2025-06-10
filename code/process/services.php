<?php
include '../connection/connection.php';
include '../header/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Realiving Design Center</title>
  <link rel="icon" type="image/png" href="./images/favicon.png">
  <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&family=Montserrat&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="services.css" />
 
</head>
<body>

  <section class="sub-header">
    <h1>Services</h1>
  </section>

<main class="container">
  <p class="intro">
    At Realiving Design Center, we offer top-notch design, fabrication, delivery, and installation services — all crafted with precision and style to bring your spaces to life.
  </p>

  <!-- SERVICE ITEM 1 -->
  <div class="service-card left">
    <div class="number">1</div>
    <img src="/realiving_updated/code/images/Design.png" alt="Design">
    <div class="text">
      <h2>Design</h2>
      <p>We visit the client's site to take precise measurements, ensuring the product will fit seamlessly into the space and accommodate any existing features or structures.</p>
    </div>
  </div>

  <!-- SERVICE ITEM 2 -->
  <div class="service-card right">
    <div class="number">2</div>
    <img src="/realiving_updated/code/images/Fabricate.png" alt="Fabricate">
    <div class="text">
      <h2>Fabricate</h2>
      <p>
        All approved designs are built at our own factory in Bulacan, where we ensure quality craftsmanship and attention to detail.<br><br>
        We fabricate custom furniture and fixtures such as:<br>
        - Residential or Office<br>
        - Cabinets & Wardrobes<br>
        - Desks & Study Tables<br>
        - Drawers & Side Tables<br><br>
        Each piece is made to match your exact requirements, combining durability with smart design.
      </p>
    </div>
  </div>

  <!-- SERVICE ITEM 3 -->
  <div class="service-card left">
    <div class="number">3</div>
    <img src="/realiving_updated/code/images/Delivery.png" alt="Delivery">
    <div class="text">
      <h2>Delivery</h2>
      <p>We ensure a smooth and secure delivery process for all fabricated items. Your furniture and fixtures are carefully handled, packed, and transported to arrive at your location in excellent condition—on time and ready for installation.</p>
    </div>
  </div>

  <!-- SERVICE ITEM 4 -->
  <div class="service-card right">
    <div class="number">4</div>
    <img src="/realiving_updated/code/images/Installation.png" alt="Installation">
    <div class="text">
      <h2>Installation</h2>
      <p>Our skilled installation team takes care of the final step: assembling and fitting everything perfectly in your space. We make sure that each detail aligns with the original design, giving you a seamless and ready-to-use setup.</p>
    </div>
  </div>
</main>

<?php 
include '../ads/promo-banner.php';
include '../footer/footer.php'; 
?>
