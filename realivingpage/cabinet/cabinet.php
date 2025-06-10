<?php
include '../../connection/connection.php';
include '../../realivingpage/header/headernav.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Realiving Design Center </title>
  <link rel="icon" type="image/png" href="./images/favicon.png">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&family=Montserrat&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="cabinet.css">
</head>

<body>


  <!-- Furniture Product Section -->
  <section class="furniture-product-section">
    <div class="main-product">
      <div class="product-image">
        <img src="../images/cabinet-example.png" alt="Main Cabinet">
      </div>
      <div class="product-details">
        <h2 class="product-title">CABINET TITLE</h2>
        <p class="product-description">
          Lorem ipsum dolor sit amet consectetur adipiscing elit. Amet consectetur adipiscing elit quisque faucibus ex
          sapien.
          Quisque faucibus ex sapien vitae pellentesque sem placerat. Vitae pellentesque sem placerat in id cursus mi.
        </p>
        <ul class="product-dimensions">
          <li><strong>LENGTH:</strong> </li>
          <li><strong>WIDTH:</strong> </li>
          <li><strong>HEIGHT:</strong> </li>
        </ul>
        <div class="quantity-wrapper">
          <button class="qty-btn minus">-</button>
          <input type="number" min="0" value="0" class="qty-input" readonly>
          <button class="qty-btn plus">+</button>
        </div>
        <div class="product-actions">
          <button class="btn dark"> REQUEST PRICE</button>
          <button class="btn outline">DESIGN YOUR OWN CABINET?</button>
        </div>
      </div>
    </div>

    <div class="related-products">
      <h3 class="section-title">YOU MAY LIKE</h3>
      <div class="product-grid">
        <div class="related-product">
          <img src="../images/cabinet-example.png" alt="Cabinet 1">
          <p class="product-name">CABINET</p>
          <button class="btn">GET PRICE</button>
        </div>
        <div class="related-product">
          <img src="../images/cabinet-example.png" alt="Cabinet 2">
          <p class="product-name">CABINET</p>
          <button class="btn">GET PRICE</button>
        </div>
        <div class="related-product">
          <img src="../images/cabinet-example.png" alt="Cabinet 3">
          <p class="product-name">CABINET</p>
          <button class="btn">GET PRICE</button>
        </div>
      </div>
    </div>
  </section>

  <section class="cabinet-cost-section">
    <div class="cabinet-content">
      <div class="cabinet-text">
        <h2>Know Your Cabinet Cost with Confidence</h2>
        <p>
          Have a vision in mind but not sure where to begin? Let's talk. Our design experts are ready to guide you
          through every step—from concept to completion. Whether it's a modular setup or a fully customized build, we’ll
          help bring your ideas to life with precision and creativity.
        </p>
        <a href="#" class="cabinet-btn">BOOK AN APPOINTMENT NOW</a>
      </div>
      <div class="cabinet-image">
        <img src="/realiving_updated/code/images/background-image.jpg" alt="Kitchen cabinet design">
      </div>
    </div>
  </section>

  <script>
    const minusBtn = document.querySelector(".qty-btn.minus");
    const plusBtn = document.querySelector(".qty-btn.plus");
    const qtyInput = document.querySelector(".qty-input");

    minusBtn.addEventListener("click", () => {
      let value = parseInt(qtyInput.value);
      if (value > 0) qtyInput.value = value - 1;
    });

    plusBtn.addEventListener("click", () => {
      let value = parseInt(qtyInput.value);
      qtyInput.value = value + 1;
    });
  </script>
</body>

</html>