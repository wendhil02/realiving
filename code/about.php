<?php
include 'htmldesign/top.php';
include 'htmldesign/mainhead.php';
include 'htmldesign/top.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>About Us | Realiving Design Center</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="pt-[70px] font-['Playfair_Display'] text-gray-800 leading-relaxed">

  <!-- Sub Header -->
  <section class="bg-white py-8">
    <h1 class="text-4xl italic text-[#1f96c9] text-center">About Us</h1>
  </section>

  <!-- Vision & Mission -->
  <section class="bg-white py-16 px-8 font-['Montserrat']">
    <div class="max-w-6xl mx-auto flex flex-wrap justify-between gap-10">
      <div class="flex-1 min-w-[300px]">
        <h2 class="text-2xl text-[#e4a314] text-center">Our Vision</h2>
        <hr class="w-[70%] mx-auto my-3 border-t-2 border-black opacity-15 rounded">
        <p class="text-justify text-base">To be the elite provider of the interiors and to be the forefront of the special architectural industry in the Philippines.</p>
      </div>
      <div class="flex-1 min-w-[300px]">
        <h2 class="text-2xl text-[#e4a314] text-center">Our Mission</h2>
        <hr class="w-[70%] mx-auto my-3 border-t-2 border-black opacity-15 rounded">
        <p class="text-justify text-base">To design, to build, and to equip home with the most beautiful and most functional interior through: cost-efficiency, incomparable craftsmanship and quality materials.</p>
      </div>
    </div>
  </section>

  <!-- Company Section -->
  <section class="bg-[#fdfdfd] py-16 px-8 font-['Montserrat']">
    <div class="max-w-6xl mx-auto flex flex-wrap justify-between gap-10 text-[#e4a314]">
      <div class="flex-1 min-w-[300px]">
        <h2 class="text-2xl text-right mb-4">About the Company</h2>
        <p class="text-black text-base text-justify indent-10">Realiving Design Center Corporation is one of the biggest building material supplier in Philippines. NX Trend's, Realflooring, ECON Global, GrandEast aluminium and door. Each of them represents the top level in respective field in Philippines.</p>
        <br>
        <p class="text-black text-base text-justify indent-10">Realiving Design Center Corporation was duly incorporated under the trade name Realiving Design center corporation situated at Warehouse 5, Sunhope Compound, Purok 7, Brgy. Calasag, San Ildefonso, Bulacan...</p>
      </div>
      <div class="flex-1 flex items-center justify-center">
        <img src="../logo/mm.png" alt="Company Photo" class="w-full max-w-[450px] rounded-xl shadow-lg">
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-[#ffefd8] px-8 py-12 flex flex-wrap justify-between font-['Montserrat'] text-sm">
    <div class="flex-1 min-w-[300px] mb-6">
      <div class="mb-4">
        <img src="./images/logo.png" alt="Realiving Logo" class="h-10">
      </div>
      <div class="space-y-3">
        <div class="flex items-center gap-2">
          <img src="./images/location-icon.png" class="h-5 w-5" alt="Location">
          <p>MC Premier – EDSA Balintawak, Quezon City</p>
        </div>
        <div class="flex items-center gap-2">
          <img src="./images/call-icon.png" class="h-5 w-5" alt="Call">
          <p>Company number</p>
        </div>
        <div class="flex items-center gap-2">
          <img src="./images/email-icon.png" class="h-5 w-5" alt="Email">
          <p>Company Email</p>
        </div>
        <div class="flex items-center gap-2">
          <img src="./images/calendar-icon.png" class="h-5 w-5" alt="Hours">
          <p><b>Mon–Fri:</b> 7:00AM – 5:00PM</p>
        </div>
      </div>
    </div>
    
    <div class="flex flex-col items-end flex-1 min-w-[200px]">
      <h4 class="mb-4 text-[#1f96c9] font-semibold">QUICK LINKS</h4>
      <a href="contact.html" class="hover:text-[#e4a314]">Contact Us</a>
      <a href="#" class="hover:text-[#e4a314]">Privacy Policy</a>
      <a href="#" class="hover:text-[#e4a314]">Terms & Conditions</a>
    </div>
  </footer>

  <!-- Footer Bottom -->
  <div class="bg-[#1f96c9] text-white text-center py-2 text-xs font-['Montserrat']">
    ©2025 Realiving Design Center Corporation. All Rights Reserved.
  </div>

  <script src="script.js"></script>
</body>
</html>
