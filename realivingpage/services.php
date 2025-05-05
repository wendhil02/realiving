<?php
include 'htmldesign/mainhead.php';
include 'htmldesign/top.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    

<section class="bg-[#fff7ec] h-[180px] h-[250px] flex items-center justify-center relative ">
  <img src="images/background-image.jpg" alt="Background" class="absolute inset-0 w-full h-full object-cover z-0" />
  <div class="absolute inset-0 bg-black opacity-40 z-0"></div>
  <h1 class="relative z-10 text-white text-[40px] italic font-normal font-playfair drop-shadow-md">Services</h1>
</section>

<section class="bg-[#fff7ec] py-16 px-4">
  <div class="max-w-3xl mx-auto mb-12 text-center">
    <p class="text-gray-600 text-lg font-montserrat">
      At Realiving Design Center, we bring your vision to life through comprehensive services that cover every stage of your project. From initial concepts to the final installation, our team ensures quality, precision, and seamless execution every step of the way.
    </p>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-12 max-w-6xl mx-auto">
    <!-- Design Service -->
    <div class="flex flex-col items-center text-center">
      <img src="images/alphaland-1.jpg" alt="Design Service" class="w-full h-[250px] object-cover mb-6" />
      <h2 class="text-2xl text-yellow-600 font-playfair mb-4">Design</h2>
      <p class="text-gray-700 px-4 font-montserrat">
        Our talented design team collaborates closely with you to craft personalized, innovative solutions tailored to your needs. We translate your ideas into detailed plans that reflect style, functionality, and lasting value.
      </p>
    </div>

    <!-- Fabrication Service -->
    <div class="flex flex-col items-center text-center">
      <img src="images/alphaland-1.jpg" alt="Fabrication Service" class="w-full h-[250px] object-cover mb-6" />
      <h2 class="text-2xl text-yellow-600 font-playfair mb-4">Fabrication</h2>
      <p class="text-gray-700 px-4 font-montserrat">
        We manufacture bespoke pieces with a commitment to craftsmanship and durability. Using premium materials and the latest techniques, our fabrication process ensures every element meets the highest standards of quality.
      </p>
    </div>

    <!-- Delivery Service -->
    <div class="flex flex-col items-center text-center">
      <img src="images/alphaland-1.jpg" alt="Delivery Service" class="w-full h-[250px] object-cover mb-6" />
      <h2 class="text-2xl text-yellow-600 font-playfair mb-4">Delivery</h2>
      <p class="text-gray-700 px-4 font-montserrat">
        Our logistics team guarantees careful and timely delivery of your custom products. We handle every item with professionalism and attention to detail to maintain the integrity and beauty of each piece.
      </p>
    </div>

    <!-- Installation Service -->
    <div class="flex flex-col items-center text-center">
      <img src="images/alphaland-1.jpg" alt="Installation Service" class="w-full h-[250px] object-cover mb-6" />
      <h2 class="text-2xl text-yellow-600 font-playfair mb-4">Installation</h2>
      <p class="text-gray-700 px-4 font-montserrat">
        Our expert installers bring the final touch to your project. We execute precise, clean installations with minimal disruption, ensuring that your spaces are ready for immediate use and long-term satisfaction.
      </p>
    </div>
  </div>
</section>
</body>
</html>