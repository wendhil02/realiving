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


  <section class="py-16 bg-gray-100">
  <div class="max-w-7xl mx-auto px-4 text-center">
    <h2 class="text-3xl font-bold text-sky-600 mb-4">Accomplished Projects</h2>
    <hr class="w-24 border-b-4 border-sky-600 mx-auto mb-10">

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
      <!-- Project 1 -->
      <a href="#" target="_blank" class="transform hover:scale-105 transition duration-300 shadow-md bg-white rounded-lg p-2">
        <div class="mb-2">
          <img src="./images/project-1.png" alt="Project 1" class="w-full h-32 object-cover rounded">
        
        </div>
      </a>

      <!-- Project 2 -->
      <a href="#" target="_blank" class="transform hover:scale-105 transition duration-300 shadow-md bg-white rounded-lg p-2">
        <div class="mb-2">
          <img src="./images/project-2.png" alt="Project 2" class="w-full h-32 object-cover rounded">
        
        </div>
      </a>

      <!-- Project 3 -->
      <a href="#" target="_blank" class="transform hover:scale-105 transition duration-300 shadow-md bg-white rounded-lg p-2">
        <div class="mb-2">
          <img src="./images/project-3.png" alt="Project 3" class="w-full h-32 object-cover rounded">
        
        </div>
      </a>

      <!-- Project 4 -->
      <a href="#" target="_blank" class="transform hover:scale-105 transition duration-300 shadow-md bg-white rounded-lg p-2">
        <div class="mb-2">
          <img src="./images/project-4.png" alt="Project 4" class="w-full h-32 object-cover rounded">
        
        </div>
      </a>

      <!-- Project 5 -->
      <a href="#" target="_blank" class="transform hover:scale-105 transition duration-300 shadow-md bg-white rounded-lg p-2">
        <div class="mb-2">
          <img src="./images/project-5.png" alt="Project 5" class="w-full h-32 object-cover rounded">
        
        </div>
      </a>

      <!-- Project 6 -->
      <a href="#" target="_blank" class="transform hover:scale-105 transition duration-300 shadow-md bg-white rounded-lg p-2">
        <div class="mb-2">
          <img src="./images/project-6.png" alt="Project 6" class="w-full h-32 object-cover rounded">
        
        </div>
      </a>

      <!-- Project 7 -->
      <a href="#" target="_blank" class="transform hover:scale-105 transition duration-300 shadow-md bg-white rounded-lg p-2">
        <div class="mb-2">
          <img src="./images/project-7.png" alt="Project 7" class="w-full h-32 object-cover rounded">
        
        </div>
      </a>

      <!-- Project 8 -->
      <a href="#" target="_blank" class="transform hover:scale-105 transition duration-300 shadow-md bg-white rounded-lg p-2">
        <div class="mb-2">
          <img src="./images/project-8.png" alt="Project 8" class="w-full h-32 object-cover rounded">
        
        </div>
      </a>

      <!-- Project 9 -->
      <a href="project-template-example.html" target="_blank" class="transform hover:scale-105 transition duration-300 shadow-md bg-white rounded-lg p-2">
        <div class="mb-2">
          <img src="./images/project-9.png" alt="Project 9" class="w-full h-32 object-cover rounded">
        
        </div>
      </a>

      <!-- Project 10 -->
      <a href="#" target="_blank" class="transform hover:scale-105 transition duration-300 shadow-md bg-white rounded-lg p-2">
        <div class="mb-2">
          <img src="./images/project-10.png" alt="Project 10" class="w-full h-32 object-cover rounded">
          
        </div>
      </a>

      <!-- Project 11 -->
      <a href="#" target="_blank" class="transform hover:scale-105 transition duration-300 shadow-md bg-white rounded-lg p-2">
        <div class="mb-2">
          <img src="./images/project-11.png" alt="Project 11" class="w-full h-32 object-cover rounded">
          
        </div>
      </a>

      <!-- Project 12 -->
      <a href="#" target="_blank" class="transform hover:scale-105 transition duration-300 shadow-md bg-white rounded-lg p-2">
        <div class="mb-2">
          <img src="./images/project-12.png" alt="Project 12" class="w-full h-32 object-cover rounded">
          
        </div>
      </a>

      <!-- Project 13 -->
      <a href="#" target="_blank" class="transform hover:scale-105 transition duration-300 shadow-md bg-white rounded-lg p-2">
        <div class="mb-2">
          <img src="./images/project-13.png" alt="Project 13" class="w-full h-32 object-cover rounded">
          
        </div>
      </a>

      <!-- Project 14 -->
      <a href="#" target="_blank" class="transform hover:scale-105 transition duration-300 shadow-md bg-white rounded-lg p-2">
        <div class="mb-2">
          <img src="./images/project-14.png" alt="Project 14" class="w-full h-32 object-cover rounded">
          
        </div>
      </a>

      <!-- Project 15 -->
      <a href="#" target="_blank" class="transform hover:scale-105 transition duration-300 shadow-md bg-white rounded-lg p-2">
        <div class="mb-2">
          <img src="./images/project-15.png" alt="Project 15" class="w-full h-32 object-cover rounded">
          
        </div>
      </a>
    </div>
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

<section class="py-16 bg-cover bg-center relative" style="background-image: url('../uploads/home.jpg');">
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
            <img src="<?= '../uploads/' . $row['image'] ?>" alt="<?= htmlspecialchars($row['title']) ?>" class="w-full h-48 object-cover rounded-t-xl">

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