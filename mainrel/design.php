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
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./css/process.css" />
</head>
<body>
  <!-- Sub Header (Headline) -->
  <section class="sub-header">
  <h1>Design Process</h1>
</section>

<div class="design-page">
  <div class="main-container">
    <!-- Step 1 -->
    <div class="des-step">
      <div class="des-step-media">
        <img src= "./images/site-measurement-example.png">
      </div>
      <div class="des-step-text">
        <h2>Step 1</h2>
        <h3>Initial Site Measurement</h3>
        <hr class="section-divider">
        <p>We visit the client’s site to take precise measurements, ensuring the product will fit seamlessly into the space and accommodate any existing features or structures.</p>
      </div>
    </div>

    <!-- Step 2 (reverse) -->
    <div class="des-step reverse">
    <div class="des-step-text">
        <h2>Step 2</h2>
        <h3>Project Quotation</h3>
        <hr class="section-divider">
        <p>Based on the client’s needs and the site details, we provide a clear and detailed quotation covering materials, labor, and timelines.</p>
      </div>
      <div class="des-step-media">
        <img src= "./images/quotation-example.png">
      </div>
    </div>

    <!-- Step 3 -->
    <div class="des-step">
      <div class="des-step-media">
        <img src= "./images/3D2D-example.png">
      </div>
      <div class="des-step-text">
        <h2>Step 3</h2>
        <h3>3D/2D Designs Based on Client’s Request</h3>
        <hr class="section-divider">
        <p>Once the client’s preferences are gathered, our design team produces 3D and 2D visual representations of the proposed project. These designs help the client visualize the final outcome and make any modifications before proceeding.</p>
      </div>
    </div>

    <!-- Step 4 (reverse) -->
    <div class="des-step reverse">
    <div class="des-step-text">
        <h2>Step 4</h2>
        <h3>Approval of Layouts by the Client</h3>
        <hr class="section-divider">
        <p>After presenting the designs, we await the client’s feedback and approval. This step ensures that every detail aligns with the client’s vision, and any adjustments can be made prior to the next phase.</p>
      </div>
      <div class="des-step-media">
        <img src= "./images/approval-example.png">
      </div>
    </div>

    <!-- Step 5 -->
    <div class="des-step">
      <div class="des-step-media">
        <img src= "./images/3D2D-example.png">
      </div>
      <div class="des-step-text">
        <h2>Step 5</h2>
        <h3>Purchase Order to Be Registered to NTP</h3>
        <hr class="section-divider">
        <p>Once the design is approved, we prepare a purchase order (PO) that is registered with NTP (National Telecommunications Procurement). This ensures that all materials and resources needed for fabrication are ordered and prepared in advance.</p>
      </div>
    </div>

    <!-- Step 6 (reverse) -->
    <div class="des-step reverse">
    <div class="des-step-text">
        <h2>Step 6</h2>
        <h3>Cutting List</h3>
        <hr class="section-divider">
        <p>The cutting list is a detailed document listing all the materials needed for the fabrication process. This list helps our team organize and prepare the exact cuts required to construct the project based on the approved design.</p>
      </div>
      <div class="des-step-media">
        <img src= "./images/cutting-list-example.png">
      </div>
    </div>
  </div>
</div>
</section>

<hr class="section-divider-two">

<?php
include 'footer.php';
?>