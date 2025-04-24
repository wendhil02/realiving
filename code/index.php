<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Realiving Design Center</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      font-family: 'Playfair Display', serif;
    }

    .montserrat {
      font-family: 'Montserrat', sans-serif;
    }
  </style>
</head>

<body class="text-gray-800 leading-relaxed">

  <!-- Header -->
  <header class="fixed top-0 left-0 w-full h-12 px-8 flex items-center justify-between bg-white border-t-4 border-yellow-500 shadow-md z-50">
    <div><img src="./images/logo.png" class="h-8"></div>
    <nav class="flex items-center gap-4 montserrat">
      <a href="index.php" class="text-gray-900 hover:text-yellow-500 text-base">Home</a>
      <a href="about.php" class="text-gray-900 hover:text-yellow-500 text-base">About</a>
      <div class="relative group">
        <a href="#" class="text-gray-900 hover:text-yellow-500 text-base cursor-pointer">Projects ▾</a>
        <div class="hidden absolute group-hover:block top-full left-0 bg-white w-72 max-h-72 overflow-y-auto shadow-lg rounded-md mt-2 p-2 z-50">
          <a href="#" class="block px-4 py-2 text-gray-900 hover:bg-gray-100 hover:text-yellow-500 text-sm">Alphaland Baguio Mountain Lodge</a>
          <a href="#" class="block px-4 py-2 text-gray-900 hover:bg-gray-100 hover:text-yellow-500 text-sm">Best Western Hotel</a>
          <a href="#" class="block px-4 py-2 text-gray-900 hover:bg-gray-100 hover:text-yellow-500 text-sm">The Bellevue Manila</a>
          <a href="#" class="block px-4 py-2 text-gray-900 hover:bg-gray-100 hover:text-yellow-500 text-sm">Megaworld</a>
        </div>
      </div>
      <a href="#" class="text-gray-900 hover:text-yellow-500 text-base">News</a>
      <a href="contact.php" class="text-gray-900 hover:text-yellow-500 text-base">Contact</a>
    </nav>
  </header>

  <!-- Hero Section -->
  <section class="pt-12 relative w-full h-[500px] overflow-hidden">
    <div class="relative w-full h-full" id="blurTarget">
      <div class="absolute w-full h-full opacity-100 transition-opacity duration-1000">
        <img src="./images/background-image.jpg" class="w-full h-full object-cover" alt="Slide 1">
      </div>
    </div>
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-white text-4xl text-center drop-shadow-lg font-bold">
      <i>Elevate your Space</i>
    </div>
  </section>

  <!-- Services Section -->
  <section class="py-16 text-center">
    <div class="text-3xl font-semibold">Services</div>
    <hr class="w-12 h-1 bg-yellow-500 mx-auto my-4 border-0 rounded">

    <div class="flex flex-wrap justify-center gap-8 mt-8">
      <div class="bg-gray-100 p-8 rounded-lg max-w-xs">
        <h3 class="text-xl font-semibold mb-4 montserrat">DESIGN</h3>
        <p>We create smart, space-saving, and stylish designs tailored to your space and lifestyle needs.</p>
        <a href="#design" class="inline-block mt-4 px-4 py-2 bg-yellow-500 text-white rounded montserrat">Read More</a>
      </div>
      <div class="bg-gray-100 p-8 rounded-lg max-w-xs">
        <h3 class="text-xl font-semibold mb-4 montserrat">FABRICATE</h3>
        <p>Using quality materials, we build each piece with precision to ensure durability and a modern finish.</p>
        <a href="#fabricate" class="inline-block mt-4 px-4 py-2 bg-yellow-500 text-white rounded montserrat">Read More</a>
      </div>
      <div class="bg-gray-100 p-8 rounded-lg max-w-xs">
        <h3 class="text-xl font-semibold mb-4 montserrat">DELIVERED</h3>
        <p>We transport your furniture safely and on time—straight to your doorstep.</p>
        <a href="#delivered" class="inline-block mt-4 px-4 py-2 bg-yellow-500 text-white rounded montserrat">Read More</a>
      </div>
      <div class="bg-gray-100 p-8 rounded-lg max-w-xs">
        <h3 class="text-xl font-semibold mb-4 montserrat">INSTALLATION</h3>
        <p>Our team handles the setup efficiently, making sure everything is perfectly fitted and ready to use.</p>
        <a href="#installation" class="inline-block mt-4 px-4 py-2 bg-yellow-500 text-white rounded montserrat">Read More</a>
      </div>
    </div>
  </section>

  <section class="py-16 bg-gray-800">
  <h2 class="text-3xl font-semibold text-center text-white">Latest News</h2>
  <hr class="w-12 h-1 bg-yellow-500 mx-auto my-4 border-0 rounded">

  <div class="overflow-hidden relative">
    <div class="flex gap-4 transition-transform duration-1000 ease-in-out news-container">
      <div class="min-w-[250px] bg-white rounded-lg overflow-hidden shadow-md text-center">
        <img src="./images/aiah.jpg" alt="News 1" class="w-full h-40 object-cover">
        <h3 class="mt-4 text-lg font-semibold">Title 1</h3>
        <p class="p-4">Summary of the first news item.</p>
        <a href="#" class="inline-block mb-4 px-4 py-2 bg-yellow-500 text-white rounded montserrat">View More</a>
      </div>
      <div class="min-w-[250px] bg-white rounded-lg overflow-hidden shadow-md text-center">
        <img src="./images/aiah.jpg" alt="News 2" class="w-full h-40 object-cover">
        <h3 class="mt-4 text-lg font-semibold">Title 2</h3>
        <p class="p-4">Summary of the second news item.</p>
        <a href="#" class="inline-block mb-4 px-4 py-2 bg-yellow-500 text-white rounded montserrat">View More</a>
      </div>
      <div class="min-w-[250px] bg-white rounded-lg overflow-hidden shadow-md text-center">
        <img src="./images/aiah.jpg" alt="News 3" class="w-full h-40 object-cover">
        <h3 class="mt-4 text-lg font-semibold">Title 3</h3>
        <p class="p-4">Summary of the third news item.</p>
        <a href="#" class="inline-block mb-4 px-4 py-2 bg-yellow-500 text-white rounded montserrat">View More</a>
      </div>
      <!-- Add more news items here -->
    </div>
  </div>
</section>



  <!-- Contact Form Section -->
  <section class="py-16 bg-gray-100 text-center">
    <div class="max-w-xl mx-auto">
      <h2 class="text-3xl font-semibold mb-2"><i>Contact Us</i></h2>
      <p class="mb-8">We would love to speak with you.</p>
      <form class="flex flex-col gap-4 montserrat">
        <input type="text" placeholder="E.g. Juan Dela Cruz" required class="border rounded p-3 w-full">
        <input type="text" placeholder="E.g. (+63) 923 456 789" required class="border rounded p-3 w-full">
        <input type="email" placeholder="E.g. juan.delacruz@gmail.com" required class="border rounded p-3 w-full">
        <button type="submit" class="bg-yellow-500 text-white font-bold py-3 rounded w-full">Next</button>
      </form>
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

  <!-- Footer Text -->
  <div class="text-center py-4 bg-gray-800 text-gray-400 text-sm">
    © 2025 Realiving Design Center. All rights reserved.
  </div>

  <!-- JavaScript for Sliding News -->
  <script>
let newsContainer = document.querySelector('.news-container');
let newsItems = document.querySelectorAll('.news-container > div');
let currentIndex = 0;

// Apply transition for smooth sliding
newsContainer.style.transition = 'transform 0.5s ease-in-out'; // Smooth transition

// Set the width of the container based on the number of items
const totalWidth = newsItems.length * (newsItems[0].offsetWidth + 16); // 16px gap
newsContainer.style.width = `${totalWidth}px`;

setInterval(() => {
  currentIndex = (currentIndex + 1) % newsItems.length;
  let offset = -currentIndex * (newsItems[0].offsetWidth + 16); // 16px gap
  newsContainer.style.transform = `translateX(${offset}px)`;
}, 3000); // Change every 3 seconds

  </script>
</body>

</html>
