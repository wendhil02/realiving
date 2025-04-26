<?php
include '../connection/connection.php';
$result = $conn->query("SELECT * FROM news ORDER BY created_at DESC");
include 'htmldesign/mainhead.php';
include 'htmldesign/top.php';
?>

<body class="text-gray-800 leading-relaxed">
  <!-- Hero Section -->
  <section class="pt-12 relative w-full h-[700px] overflow-hidden">
    <div class="relative w-full h-full" id="blurTarget">
      <!-- Overlay -->
      <div class="absolute inset-0 bg-black bg-opacity-50 z-10"></div>

      <!-- Slides -->
      <div class="absolute w-full h-full opacity-100 transition-opacity duration-1000 hero-slide">
        <img src="./images/background-image.jpg" class="w-full h-full object-cover" alt="Slide 1">
      </div>
      <div class="absolute w-full h-full opacity-0 transition-opacity duration-1000 hero-slide">
        <img src="./images/background-image2.jpg" class="w-full h-full object-cover" alt="Slide 2">
      </div>
      <div class="absolute w-full h-full opacity-0 transition-opacity duration-1000 hero-slide">
        <img src="./images/background-image3.jpg" class="w-full h-full object-cover" alt="Slide 3">
      </div>
    </div>

    <div class="absolute z-20 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 drop-shadow-lg">
      <img src="../logo/mmone.png" alt="Center Image" class="max-w-full h-auto w-72 md:w-96 mb-4">
    </div>

  </section>
  <section class="py-16 bg-gray-300 relative">
  <!-- Background Image with Overlay -->
  <div class="absolute inset-0 bg-cover bg-center opacity-70" style="background-image: url('../code/images/background-image2.jpg');"></div>

  <div class="container mx-auto px-6 relative z-10">
    <div class="text-center">
      <h2 class="text-3xl font-bold text-white">Services</h2>
      <hr class="w-10 h-1 bg-yellow-500 mx-auto my-4 border-0 rounded">
    </div>

    <div class="relative max-w-5xl mx-auto mt-16">
      <!-- Vertical center line -->
      <div class="absolute left-1/2 transform -translate-x-1/2 h-full w-1 bg-black"></div>

      <div class="space-y-16">

        <!-- DESIGN - Left -->
        <div class="relative flex justify-start items-center" data-aos="fade-right">
          <div class="w-1/2 pr-8 text-right">
            <div class="bg-gray-100 p-6 rounded-lg shadow-md inline-block">
              <h3 class="text-xl font-bold montserrat text-black">DESIGN</h3>
              <p class="text-black mt-2">We create smart, space-saving, and stylish designs tailored to your space and lifestyle needs.</p>
              <a href="#design" class="inline-block mt-4 px-4 py-2 bg-yellow-500 text-white rounded montserrat hover:bg-yellow-600 transition">Read More</a>
            </div>
          </div>
          <div class="w-8 h-8 bg-black rounded-full border-4 border-white absolute left-1/2 transform -translate-x-1/2"></div>
          <div class="w-1/2 ml-[50px] relative group">
            <img src="../logo/.png" alt="Design Service" class="w-full h-auto rounded-lg shadow-md" />
            <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition duration-300 rounded-lg"></div>
          </div>
        </div>

        <!-- FABRICATE - Right -->
        <div class="relative flex justify-end items-center" data-aos="fade-left">
          <div class="w-1/2 relative group">
            <img src="fabricate-image.jpg" alt="Fabricate Service" class="w-full h-auto rounded-lg shadow-md" />
            <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition duration-300 rounded-lg"></div>
          </div>
          <div class="w-8 h-8 bg-black rounded-full border-4 border-white absolute left-1/2 transform -translate-x-1/2"></div>
          <div class="w-1/2 pl-8 text-left">
            <div class="bg-gray-100 p-6 rounded-lg shadow-md inline-block">
              <h3 class="text-xl font-semibold montserrat text-black">FABRICATE</h3>
              <p class="text-black mt-2">Using quality materials, we build each piece with precision to ensure durability and a modern finish.</p>
              <a href="#fabricate" class="inline-block mt-4 px-4 py-2 bg-yellow-500 text-white rounded montserrat hover:bg-yellow-600 transition">Read More</a>
            </div>
          </div>
        </div>

        <!-- DELIVERED - Left -->
        <div class="relative flex justify-start items-center" data-aos="fade-right">
          <div class="w-1/2 pr-8 text-right">
            <div class="bg-gray-100 p-6 rounded-lg shadow-md inline-block">
              <h3 class="text-xl font-semibold montserrat text-black">DELIVERED</h3>
              <p class="text-black mt-2">We transport your furniture safely and on time—straight to your doorstep.</p>
              <a href="#delivered" class="inline-block mt-4 px-4 py-2 bg-yellow-500 text-white rounded montserrat hover:bg-yellow-600 transition">Read More</a>
            </div>
          </div>
          <div class="w-8 h-8 bg-black rounded-full border-4 border-white absolute left-1/2 transform -translate-x-1/2"></div>
          <div class="w-1/2 ml-[50px] relative group">
            <img src="../logo/deli.png" alt="Delivered Service" class="w-full h-[300px] rounded-lg shadow-md object-cover" />
            <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition duration-300 rounded-lg flex items-center justify-center">
              <span class="text-white text-lg font-semibold montserrat">Delivered</span>
            </div>
          </div>
        </div>

        <!-- INSTALLATION - Right -->
        <div class="relative flex justify-end items-center" data-aos="fade-left">
          <div class="w-1/2 mr-[50px] relative group">
            <img src="../logo/insta.png" alt="Installation Service" class="w-full h-[250px] rounded-lg shadow-md" />
            <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition duration-300 rounded-lg flex items-center justify-center">
              <span class="text-white text-lg font-semibold montserrat">INSTALLATION</span>
            </div>
          </div>
          <div class="w-8 h-8 bg-black rounded-full border-4 border-white absolute left-1/2 transform -translate-x-1/2"></div>
          <div class="w-1/2 pl-8 text-left">
            <div class="bg-gray-100 p-6 rounded-lg shadow-md inline-block">
              <h3 class="text-xl font-semibold montserrat text-black">INSTALLATION</h3>
              <p class="text-black mt-2">Our team handles the setup efficiently, making sure everything is perfectly fitted and ready to use.</p>
              <a href="#installation" class="inline-block mt-4 px-4 py-2 bg-yellow-500 text-white rounded montserrat hover:bg-yellow-600 transition">Read More</a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

  <section class="py-16 bg-cover bg-center relative" style="background-image: url('./images/background-image2.jpg');">
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black bg-opacity-70 z-0"></div>

    <!-- Content -->
    <div class="relative z-10">
      <!-- Section Title -->
      <h2 class="text-3xl font-semibold text-center text-white mb-6">Latest News</h2>
      <hr class="w-16 h-1 bg-yellow-500 mx-auto mb-8 border-0 rounded">

      <div class="overflow-hidden relative px-4">
        <div class="flex gap-6 news-container transition-transform duration-1000 ease-in-out">
          <?php while ($row = $result->fetch_assoc()): ?>
            <div class="min-w-[280px] bg-white rounded-xl overflow-hidden shadow-lg transform hover:scale-105 transition-transform duration-300">
              <!-- News Image -->
              <img src="<?= $row['image'] ?>" alt="<?= htmlspecialchars($row['title']) ?>" class="w-full h-48 object-cover rounded-t-xl">

              <!-- Title -->
              <h3 class="mt-6 text-xl font-semibold text-center text-gray-800"><?= htmlspecialchars($row['title']) ?></h3>

              <!-- Summary -->
              <p class="p-4 text-sm text-gray-600"><?= htmlspecialchars($row['summary']) ?></p>

              <!-- View More Button -->
              <a href="<?= $row['link'] ?>" class="inline-block mb-4 px-4 py-2 bg-yellow-500 text-white rounded-b-lg text-center w-full hover:bg-yellow-600 transition duration-200 montserrat">
                View More
              </a>
            </div>
          <?php endwhile; ?>
        </div>
      </div>
    </div>
  </section>

  <section class="py-20 bg-gray-100 text-center">
    <div class="max-w-7xl mx-auto px-6">
      <h2 class="text-4xl font-semibold mb-2 text-gray-800 font-serif italic">Contact Us</h2>
      <p class="mb-10 text-gray-600 text-lg">We would love to speak with you about your space.</p>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start text-left">
        <!-- Contact Form -->
        <form class="bg-white shadow-xl rounded-2xl p-8 space-y-6">
          <input type="text" placeholder="Your Full Name" required class="border border-gray-300 rounded-lg p-4 w-full focus:outline-none focus:ring-2 focus:ring-yellow-500 transition duration-300">
          <input type="text" placeholder="Mobile Number (e.g. +63 912 345 6789)" required class="border border-gray-300 rounded-lg p-4 w-full focus:outline-none focus:ring-2 focus:ring-yellow-500 transition duration-300">
          <input type="email" placeholder="Email Address" required class="border border-gray-300 rounded-lg p-4 w-full focus:outline-none focus:ring-2 focus:ring-yellow-500 transition duration-300">

          <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-3 rounded-lg transition duration-300">
            Next
          </button>
        </form>

        <!-- Map and Location Info -->
        <div class="space-y-4">
          <p class="text-lg text-gray-700 font-medium">📍 MC Premier – EDSA Balintawak, Quezon City</p>
          <div class="w-full h-[350px] rounded-xl overflow-hidden shadow-lg">
            <!-- OLD MAP EMBED -->
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1929.9894422897767!2d121.00198911044158!3d14.657139783226667!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b712dc89bb99%3A0x935f93a6e49ab912!2sMC%20Premiere!5e0!3m2!1sen!2sph!4v1745547865596!5m2!1sen!2sph"
              width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
              referrerpolicy="no-referrer-when-downgrade">
            </iframe>

          </div>
        </div>
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

  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

  <!-- JavaScript for Sliding News -->
  <script>
    const slides = document.querySelectorAll('.hero-slide');
    let current = 0;

    function showSlide(index) {
      slides.forEach((slide, i) => {
        slide.classList.remove('opacity-100');
        slide.classList.add('opacity-0');
        if (i === index) {
          slide.classList.remove('opacity-0');
          slide.classList.add('opacity-100');
        }
      });
    }

    setInterval(() => {
      current = (current + 1) % slides.length;
      showSlide(current);
    }, 4000); // every 4 seconds


    let newsContainer = document.querySelector('.news-container');
    let newsItems = document.querySelectorAll('.news-container > div');
    let currentIndex = 0;

    // Apply transition for smooth sliding
    newsContainer.style.transition = 'transform 0.5s ease-in-out'; // Smooth transition

    // Set the width of the container based on the number of items
    const totalWidth = newsItems.length * (newsItems[0].offsetWidth + 16); // 16px gap
    newsContainer.style.width = `${totalWidth}px`;

    // Create a continuous scrolling effect (train-like)
    function loopNewsItems() {
      // Move the container to the next item
      currentIndex = (currentIndex + 1) % newsItems.length;
      let offset = -currentIndex * (newsItems[0].offsetWidth + 16); // 16px gap
      newsContainer.style.transform = `translateX(${offset}px)`;

      // Reset position after the last item for seamless loop
      if (currentIndex === newsItems.length - 1) {
        setTimeout(() => {
          newsContainer.style.transition = 'none'; // Disable transition for reset
          newsContainer.style.transform = `translateX(0)`; // Reset to the first item
          currentIndex = 0; // Reset index to start
          setTimeout(() => {
            newsContainer.style.transition = 'transform 0.5s ease-in-out'; // Re-enable transition after reset
          }, 50); // Small delay for smooth transition to take effect
        }, 500); // Delay before resetting the container position
      }
    }

    setInterval(loopNewsItems, 3000); // Change every 3 seconds


    AOS.init({
      duration: 1000, // fade in duration
      once: true // animation only once when scrolling down
    });

    const scrollToTopButton = document.getElementById('scrollToTop');

    // Show the button when scrolling down
    window.onscroll = function() {
      if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
        scrollToTopButton.style.display = 'block';
      } else {
        scrollToTopButton.style.display = 'none';
      }
    };

    // Scroll to the top when the button is clicked
    scrollToTopButton.onclick = function() {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    };
  </script>


</body>

</html>