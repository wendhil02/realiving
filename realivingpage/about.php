<?php
include 'htmldesign/mainhead.php';
include 'htmldesign/top.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Realiving Design Center</title>
  <link rel="icon" type="image/png" href="./images/favicon.png">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- AOS CSS -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
</head>

<body class="pt-12 text-gray-800">

  <!-- Sub Header -->
  <section class="relative bg-cover bg-center h-48 flex items-center justify-center text-white" style="background-image: url('images/background-image.jpg');">
    <div class="absolute inset-0 bg-black bg-opacity-40"></div>
    <h1 class="relative text-4xl italic font-normal text-center drop-shadow-lg" data-aos="fade-up">About Us</h1>
  </section>

  <!-- About Section -->
  <section class="py-16 px-8 bg-white font-montserrat">
    <div class="max-w-6xl mx-auto flex flex-wrap justify-between gap-8">
      <div class="flex-1 min-w-[280px] bg-white shadow-lg p-8 rounded-lg hover:-translate-y-1 transition-transform" data-aos="fade-up">
        <h2 class="text-2xl text-sky-500 text-center font-playfair">Our Vision</h2>
        <div class="w-12 h-1 bg-yellow-400 mx-auto my-4 rounded-full"></div>
        <p class="text-justify leading-relaxed">To be the elite provider of interiors and to be the forefront of the special architectural industry in the Philippines.</p>
      </div>

      <div class="flex-1 min-w-[280px] bg-white shadow-lg p-8 rounded-lg hover:-translate-y-1 transition-transform" data-aos="fade-up">
        <h2 class="text-2xl text-sky-500 text-center font-playfair">Our Mission</h2>
        <div class="w-12 h-1 bg-yellow-400 mx-auto my-4 rounded-full"></div>
        <p class="text-justify leading-relaxed">To provide customized and sustainable modular cabinet solutions, utilizing cutting-edge technology, skilled craftsmanship, and a customer-centric approach, while ensuring timely delivery and exceeding client expectations.</p>
      </div>
    </div>
  </section>

  <!-- Core Values -->
  <section class="py-16 font-montserrat bg-cover bg-center bg-no-repeat relative" style="background-image: url('images/background-image2.jpg');">
    <div class="absolute inset-0 bg-black bg-opacity-40"></div>

    <div class="relative max-w-6xl mx-auto text-center text-white z-10">
      <h2 class="text-3xl text-sky-300 font-playfair mb-10" data-aos="fade-up">Our Core Values</h2>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
        <div class="bg-white text-gray-900 shadow-lg rounded-lg p-8" data-aos="fade-up">
          <img src="./images/excellence-icon.png" alt="Excellence" class="mx-auto mb-4 w-16">
          <h3 class="text-xl font-semibold mb-2">Excellence</h3>
          <p>We strive for excellence in every aspect of our work, from design conception to project completion.</p>
        </div>
        <div class="bg-white text-gray-900 shadow-lg rounded-lg p-8" data-aos="fade-up">
          <img src="./images/innovation-icon.png" alt="Innovation" class="mx-auto mb-4 w-16">
          <h3 class="text-xl font-semibold mb-2">Innovation</h3>
          <p>We embrace innovation and continuously explore new techniques and materials to enhance our products and services.</p>
        </div>
        <div class="bg-white text-gray-900 shadow-lg rounded-lg p-8" data-aos="fade-up">
          <img src="./images/collaboration-icon.png" alt="Collaboration" class="mx-auto mb-4 w-16">
          <h3 class="text-xl font-semibold mb-2">Collaboration</h3>
          <p>We believe in the power of collaboration and aim to build strong partnerships with industry professionals.</p>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mt-10">
        <div class="bg-white text-gray-900 shadow-lg rounded-lg p-8" data-aos="fade-up">
          <img src="./images/integrity-icon.png" alt="Integrity" class="mx-auto mb-4 w-16">
          <h3 class="text-xl font-semibold mb-2">Integrity</h3>
          <p>We conduct our business with integrity, transparency, and ethical practices.</p>
        </div>
        <div class="bg-white text-gray-900 shadow-lg rounded-lg p-8" data-aos="fade-up">
          <img src="./images/satisfaction-icon.png" alt="Customer Satisfaction" class="mx-auto mb-4 w-16">
          <h3 class="text-xl font-semibold mb-2">Customer Satisfaction</h3>
          <p>We prioritize customer satisfaction and work closely with clients to understand their unique needs and deliver tailored solutions.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Quote Section -->
  <section class="py-16 bg-yellow-100">
    <blockquote class="text-2xl text-center italic font-playfair max-w-2xl mx-auto" data-aos="fade-up">
      “Making your dream space a reality.”
    </blockquote>
  </section>

  <!-- Company Section -->
  <section class="py-16 px-8 bg-white font-montserrat">
    <div class="max-w-6xl mx-auto flex flex-wrap gap-8 items-center">
      <div class="flex-1 min-w-[280px]" data-aos="fade-up">
        <h2 class="text-3xl text-sky-500 font-playfair mb-6">Our Company</h2>
        <p class="text-justify leading-relaxed mb-6">
          Realiving Design Center Corp. is a leading architectural fit-out company specializing in design, manufacturing, and installation of premium modular cabinets. We are driven by a passion for creating beautifully functional spaces that enhance the lives of our clients.
        </p>
        <p class="text-justify leading-relaxed">
          Realiving was duly incorporated under the trade name Brava Homes situated at Warehouse 5, Sunhope Compound, Purok 7, Brgy. Calasag, San Ildefonso, Bulacan. It primarily engages in wholesale and retail of competitively priced, high-quality construction, plumbing, and decorative materials.
        </p>
      </div>
      <div class="flex-1 min-w-[280px]" data-aos="fade-up">
        <img src="./images/background-image.jpg" alt="Company Image" class="rounded-lg shadow-lg">
      </div>
    </div>
  </section>

  <!-- Footer Section -->
  <footer class="flex flex-wrap justify-between p-8 bg-[#ffefd8] text-black">
    <div class="flex-1 min-w-[250px]">
      <div class="mb-4">
        <img src="./images/logo.png" alt="Realiving Logo" class="h-10 mb-4">
      </div>
      <div class="space-y-2 text-sm">
        <div class="flex items-center">
          <img src="./images/location-icon.png" class="w-5 mr-2">
          <p>MC Premier – EDSA Balintawak, Quezon City</p>
        </div>
        <div class="flex items-center">
          <img src="./images/call-icon.png" class="w-5 mr-2">
          <p>+63 912 345 6789</p>
        </div>
      </div>
    </div>
    <div class="flex-1 min-w-[200px] mt-8 md:mt-0 order-first">
      <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
      <div class="flex flex-col gap-2 montserrat">
        <a href="index.php" class="hover:underline">Home</a>
        <a href="about.php" class="hover:underline">About</a>
        <a href="contact.php" class="hover:underline">Contact</a>
      </div>
    </div>
    <div class="flex-1 min-w-[250px] mt-8 md:mt-0">
      <h4 class="text-lg font-semibold mb-4">Follow Us</h4>
      <div class="flex flex-col gap-2 montserrat">
        <a href="#" class="hover:underline">Facebook</a>
        <a href="#" class="hover:underline">Instagram</a>
        <a href="#" class="hover:underline">Twitter</a>
      </div>
    </div>
  </footer>
  <!-- Scroll to Top Button -->
  <button id="scrollToTop" class="fixed bottom-8 right-8 w-12 h-12 bg-yellow-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-yellow-600 transition-transform transform hover:scale-110" style="display: none;">
    ↑
  </button>

  <!-- Footer Text -->
  <div class="text-center py-4 bg-gray-800 text-gray-400 text-sm">
    © 2025 Realiving Design Center. All rights reserved.
  </div>


  <!-- AOS Scripts -->
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init({
      once: false,
      mirror: true,
      duration: 1000,
      anchorPlacement: 'top-bottom'
    });
  </script>
</body>

</html>
