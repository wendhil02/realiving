<?php
include '../connection/connection.php';
$result = $conn->query("SELECT * FROM news ORDER BY created_at DESC");
include 'htmldesign/mainhead.php';
include 'htmldesign/top.php';
?>
<style>
  

  @keyframes slide {
    0% {
      transform: translateX(0);
    }

    100% {
      transform: translateX(-50%);
    }
  }

/* Typing and Erasing Animation */
@keyframes typing-and-erasing {
  0% {
    width: 0;
  }
  100% {
    width: 31%;
  }

}

.typing-animation {
  display: inline-block;
  white-space: nowrap;
  overflow: hidden;
  width: 0;
  animation: typing-and-erasing 6s steps(30) infinite; /* Looping typing/erasing */
  border-right: 2px solid white; /* Cursor effect */
  font-family: 'Courier New', monospace; /* Adjust for monospace type */
  font-size: 1.25rem; /* Adjust for your font size */
}

/* Blinking Cursor Effect */
@keyframes blink {
  50% {
    border-color: transparent;
  }
}

.typing-animation::after {
  content: '';
  display: inline-block;
  margin-left: 5px;
  border-right: 1px solid white;
  animation: blink 0.75s step-end infinite;
}

</style>

<body class="text-gray-800 leading-relaxed">
  <!-- Hero Section -->
  <section class="pt-12 relative w-full h-[700px] overflow-hidden">
    <div class="relative w-full h-full" id="blurTarget">
      <!-- Full Overlay (covering the entire section including left and right) -->
      <div class="absolute inset-0 bg-black bg-opacity-60 z-20"></div>

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

    <!-- Center Content with Rectangular Overlay Effect -->
    <div class="absolute z-30 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-center px-6 md:px-8  bg-opacity-60 rounded-lg bg-gray-800 py-8 w-full max-w-4xl">
      <!-- Logo Image -->
      <img src="../logo/mmone.png" alt="Center Image" class="max-w-full h-auto w-64 md:w-80 mb-6 md:mb-10 mx-auto transform transition-all duration-300 hover:scale-105">

      <!-- Typing Effect Text -->
      <p class="text-white text-lg font-semibold typing-animation">Welcome to realiving</p>

    </div>
  </section>



  <section class="py-16 bg-gray-100" data-aos="fade-up">
    <div class="max-w-7xl mx-auto px-4 text-center">
      <h2 class="text-3xl font-bold text-sky-600 mb-4">Accomplished Projects</h2>
      <hr class="w-24 border-b-4 border-sky-600 mx-auto mb-10">

      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
        <!-- Project Item -->
        <a href="#" target="_blank" class="group transform hover:scale-105 transition duration-300 bg-white rounded-lg p-4 shadow-md flex flex-col items-center">
          <img src="./images/project-1.png" alt="Project 1" class="w-20 h-20 object-contain mb-3">
          <p class="text-sm text-gray-700 font-semibold group-hover:text-sky-600">Modern Kitchen Design</p>
        </a>

        <a href="#" target="_blank" class="group transform hover:scale-105 transition duration-300 bg-white rounded-lg p-4 shadow-md flex flex-col items-center">
          <img src="./images/project-2.png" alt="Project 2" class="w-20 h-20 object-contain mb-3">
          <p class="text-sm text-gray-700 font-semibold group-hover:text-sky-600">Custom Living Room</p>
        </a>

        <a href="#" target="_blank" class="group transform hover:scale-105 transition duration-300 bg-white rounded-lg p-4 shadow-md flex flex-col items-center">
          <img src="./images/project-3.png" alt="Project 3" class="w-20 h-20 object-contain mb-3">
          <p class="text-sm text-gray-700 font-semibold group-hover:text-sky-600">Minimalist Office Setup</p>
        </a>

        <a href="#" target="_blank" class="group transform hover:scale-105 transition duration-300 bg-white rounded-lg p-4 shadow-md flex flex-col items-center">
          <img src="./images/project-4.png" alt="Project 4" class="w-20 h-20 object-contain mb-3">
          <p class="text-sm text-gray-700 font-semibold group-hover:text-sky-600">Industrial Bar Counter</p>
        </a>

        <a href="#" target="_blank" class="group transform hover:scale-105 transition duration-300 bg-white rounded-lg p-4 shadow-md flex flex-col items-center">
          <img src="./images/project-5.png" alt="Project 5" class="w-20 h-20 object-contain mb-3">
          <p class="text-sm text-gray-700 font-semibold group-hover:text-sky-600">Cozy Bedroom Cabinetry</p>
        </a>

        <!-- Duplicate this block for other projects -->
        <!-- Just change the image and the text -->
      </div>
    </div>
  </section>


  <section class="relative py-24 px-6 md:px-20 bg-gradient-to-br from-white via-slate-100 to-white" data-aos="fade-up">
  <!-- Decorative Element -->
  <div class="absolute top-0 left-0 w-32 h-32 bg-sky-100 rounded-full opacity-30 blur-2xl -z-10"></div>
  <div class="absolute bottom-0 right-0 w-40 h-40 bg-blue-200 rounded-full opacity-30 blur-3xl -z-10"></div>

  <div class="max-w-5xl mx-auto text-center bg-white bg-opacity-80 backdrop-blur-md p-10 rounded-3xl shadow-xl border border-slate-200">
    <h2 class="text-4xl md:text-5xl font-extrabold text-sky-700 mb-6 tracking-tight">
      Who We Are
    </h2>

    <details class="text-left text-gray-700 text-lg md:text-xl leading-relaxed">
      <summary class="cursor-pointer text-sky-600 font-semibold mb-4 text-xl">Learn more about Realiving</summary>
      <div class="mt-4 space-y-4">
        <p>
          <strong class="text-yellow-600">Realiving Design Center Corp.</strong> is a leading architectural fit-out company specializing in design, manufacturing, and installation of premium modular cabinets. We are driven by a passion for creating beautifully functional spaces that enhance the lives of our clients.
        </p>
        <p>
          Realiving was duly incorporated under the trade name <strong class="text-sky-600">Brava Homes</strong> situated at Warehouse 5, Sunhope Compound, Purok 7, Brgy. Calasag, San Ildefonso, Bulacan. It primarily engages in wholesale and retail of competitively priced, high-quality construction, plumbing, and decorative materials.
        </p>
      </div>
    </details>
  </div>
</section>



  <section class="py-16 bg-gray-300 relative" data-aos="fade-up">
    <!-- Background Image with Overlay -->
    <div class="absolute inset-0 bg-cover bg-center opacity-70" style="background-image: url('../realivingpage/images/background-image2.jpg');"></div>

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
              <img src="../logo/real.png" alt="Design Service" class="w-full h-auto rounded-lg shadow-md" />
              <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition duration-300 rounded-lg"></div>
            </div>
          </div>

          <!-- FABRICATE - Right -->
          <div class="relative flex justify-end items-center" data-aos="fade-left">
            <div class="w-1/2 relative group">
              <img src="../logo/nh.jpg" alt="Fabricate Service" class="w-full h-auto rounded-lg shadow-md" />
              <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition duration-300 rounded-lg"></div>
            </div>
            <div class="w-8 h-8 bg-black rounded-full border-4 border-white absolute left-1/2 transform -translate-x-1/2"></div>
            <div class="w-1/2 pl-8 ml-[50px] text-left">
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

  <section class="py-20 bg-gray-50 px-6 md:px-20" data-aos="fade-up">
    <h2 class="text-3xl font-bold text-center mb-10">What Clients Say</h2>
    <div class="grid md:grid-cols-2 gap-8 max-w-5xl mx-auto">
      <div class="bg-white p-6 rounded shadow">
        <p class="italic">“Realiving turned our empty unit into a beautiful, functional space. Their team was professional and creative from start to finish.”</p>
        <p class="mt-4 font-semibold">– Janelle M., Homeowner</p>
      </div>
      <div class="bg-white p-6 rounded shadow">
        <p class="italic">“Excellent service and amazing results. Our office renovation was seamless and exceeded expectations.”</p>
        <p class="mt-4 font-semibold">– Carlo D., Business Owner</p>
      </div>
    </div>
  </section>

  <section class="py-16 bg-cover bg-center relative" data-aos="fade-up" style="background-image: url('../uploads/home.jpg');">
  <!-- Overlay -->
  <div class="absolute inset-0 bg-black bg-opacity-70 z-0"></div>

  <div class="relative z-10">
    <h2 class="text-3xl font-semibold text-center text-white mb-6">Latest News</h2>
    <hr class="w-16 h-1 bg-yellow-500 mx-auto mb-8 border-0 rounded">

    <div class="relative overflow-hidden px-4" id="newsContainer">
      <div id="sliderTrack" class="flex transition-all duration-500 ease-in-out w-max">
        <?php
        $news = [];
        while ($row = $result->fetch_assoc()) {
          $news[] = $row;
        }

        // duplicate news list to make it loop smoothly
        $loopNews = array_merge($news, $news);

        foreach ($loopNews as $row):
        ?>
          <div class="min-w-[300px] max-w-sm mx-2 bg-white rounded-xl overflow-hidden shadow-lg flex-shrink-0">
            <img src="<?= '../uploads/' . $row['image'] ?>" alt="<?= htmlspecialchars($row['title']) ?>" class="w-full h-48 object-cover rounded-t-xl">
            <h3 class="mt-4 text-xl font-semibold text-center text-gray-800"><?= htmlspecialchars($row['title']) ?></h3>
            <p class="p-4 text-sm text-gray-600"><?= htmlspecialchars($row['summary']) ?></p>
            <a href="<?= $row['link'] ?>" class="inline-block mb-4 px-4 py-2 bg-yellow-500 text-white rounded-b-lg text-center w-full hover:bg-yellow-600 transition duration-200 montserrat">View More</a>
          </div>
        <?php endforeach; ?>
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

  <script>
    AOS.init();
  </script>
  <!-- JavaScript for Sliding News -->



  <script>
    const slidess = document.querySelectorAll('.hero-slide');
    let currentHeroIndex = 0;

    function showHeroSlide(index) {
      slidess.forEach((slide, i) => {
        slide.classList.remove('opacity-100', 'z-20');
        slide.classList.add('opacity-0', 'z-10');
        if (i === index) {
          slide.classList.remove('opacity-0');
          slide.classList.add('opacity-100', 'z-20');
        }
      });
    }

    function nextHeroSlide() {
      currentHeroIndex = (currentHeroIndex + 1) % slidess.length;
      showHeroSlide(currentHeroIndex);
    }

    showHeroSlide(currentHeroIndex);
    setInterval(nextHeroSlide, 5000);

    const container = document.getElementById('newsContainer');
  const track = document.getElementById('sliderTrack');

  let scrollStep = 310; // Adjust if needed
  let currentScroll = 0;

  function loopScroll() {
    currentScroll += scrollStep;

    // If we reached the halfway point (original list duplicated), reset instantly to 0
    if (currentScroll >= track.scrollWidth / 2) {
      currentScroll = 0;
      container.scrollLeft = 0;
    }

    container.scrollTo({
      left: currentScroll,
      behavior: 'smooth'
    });
  }

  setInterval(loopScroll, 3000); // every 3 seconds

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

window.addEventListener('scroll', () => {
  if (window.scrollY > 100) {
    scrollToTopButton.classList.remove('opacity-0', 'pointer-events-none');
    scrollToTopButton.classList.add('opacity-100', 'pointer-events-auto');
  } else {
    scrollToTopButton.classList.remove('opacity-100', 'pointer-events-auto');
    scrollToTopButton.classList.add('opacity-0', 'pointer-events-none');
  }
});

scrollToTopButton.addEventListener('click', () => {
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  });
});
  </script>


</body>

</html>