<?php
include '../connection/connection.php';
$result = $conn->query("SELECT * FROM news ORDER BY created_at DESC");
include 'htmldesign/mainhead.php';
include 'htmldesign/top.php';
?>

<style>

  /* Add animation for decorative elements */
@keyframes float {
  0% { transform: translateY(0px); }
  50% { transform: translateY(-15px); }
  100% { transform: translateY(0px); }
}

.animate-pulse {
  animation: pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
  0%, 100% { opacity: 0.3; }
  50% { opacity: 0.5; }
}

/* Custom styling for details/summary */
details summary::-webkit-details-marker {
  display: none;
}

/* Group variant for details open state */
.group-open\:rotate-180[open] .transform {
  transform: rotate(180deg);
}

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
    animation: typing-and-erasing 6s steps(30) infinite;
    /* Looping typing/erasing */
    border-right: 2px solid white;
    /* Cursor effect */
    font-family: 'Courier New', monospace;
    /* Adjust for monospace type */
    font-size: 1.25rem;
    /* Adjust for your font size */
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
<section class="relative w-full h-screen overflow-hidden font-sans">
  <!-- Slideshow Wrapper -->
  <div class="absolute inset-0 z-0">
    <!-- Overlay Gradient (top to bottom) -->
    <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/30 to-black/70 z-10"></div>

    <!-- Slides -->
    <div class="absolute inset-0 w-full h-full opacity-100 transition-opacity duration-1000 hero-slide">
      <img src="./images/background-image.jpg" class="w-full h-full object-cover" alt="Slide 1">
    </div>
    <div class="absolute inset-0 w-full h-full opacity-0 transition-opacity duration-1000 hero-slide">
      <img src="./images/background-image2.jpg" class="w-full h-full object-cover" alt="Slide 2">
    </div>
    <div class="absolute inset-0 w-full h-full opacity-0 transition-opacity duration-1000 hero-slide">
      <img src="./images/background-image3.jpg" class="w-full h-full object-cover" alt="Slide 3">
    </div>
  </div>

  <!-- Hero Content -->
  <div class="relative z-20 flex flex-col items-center justify-center h-full text-center px-4 md:px-8">
    <!-- Logo -->
    <img src="../logo/mmone.png" alt="Company Logo" class="w-52 md:w-64 lg:w-72 mb-6 md:mb-10 mx-auto transform transition-transform duration-300 hover:scale-105">

    <!-- Headline -->
    <h1 class="text-white text-3xl md:text-4xl lg:text-5xl font-bold tracking-wide leading-tight mb-4">
      Welcome to Realiving Design Center
    </h1>

    <!-- Typing or Subtitle -->
    <p class="text-gray-200 text-lg md:text-xl font-medium mb-6 typing-animation">
      Your Partner in Elegant & Functional Interior Solutions
    </p>

    <!-- Call to Action Button -->
    <a href="#services" class="inline-block px-6 py-3 bg-white text-gray-900 font-semibold rounded-full shadow-lg hover:bg-gray-100 transition-all duration-300">
      Explore Our Services
    </a>
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

      </div>
    </div>
  </section>


 <section class="relative py-24 md:py-32 px-6 md:px-20 overflow-hidden" data-aos="fade-up">
  <!-- Enhanced Decorative Elements -->
  <div class="absolute top-0 left-0 w-64 h-64 bg-gradient-to-br from-sky-100 to-blue-200 rounded-full opacity-30 blur-2xl -z-10 animate-pulse"></div>
  <div class="absolute bottom-0 right-0 w-80 h-80 bg-gradient-to-tl from-yellow-100 to-blue-200 rounded-full opacity-30 blur-3xl -z-10"></div>
  <div class="absolute top-1/3 right-1/4 w-40 h-40 bg-yellow-100 rounded-full opacity-20 blur-xl -z-10"></div>
  
  <div class="max-w-5xl mx-auto">
    <div class="bg-white bg-opacity-90 backdrop-blur-lg p-8 md:p-12 rounded-3xl shadow-xl border border-slate-200 transform transition duration-500 hover:shadow-2xl">
      
      <!-- Section Header with Decorative Line -->
      <div class="flex flex-col items-center mb-10">
        <h2 class="text-4xl md:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-sky-700 to-blue-600 mb-4 tracking-tight">
          Who We Are
        </h2>
        <div class="h-1 w-24 bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-full"></div>
      </div>
      
      <!-- Custom Styled Accordion -->
      <div class="accordion-container">
        <div class="text-left">
          <!-- Company Description with Enhanced Styling -->
          <div class="relative mb-10 md:flex items-start">
            <div class="hidden md:block absolute -left-3 top-0 h-full w-1 bg-gradient-to-b from-sky-400 to-blue-600 rounded-full"></div>
            <div class="md:pl-8">
              <p class="text-gray-700 text-lg md:text-xl leading-relaxed mb-6">
                <span class="font-semibold text-yellow-600 text-xl md:text-2xl">Realiving Design Center Corp.</span> is a leading architectural fit-out company specializing in design, manufacturing, and installation of premium modular cabinets. We are driven by a passion for creating beautifully functional spaces that enhance the lives of our clients.
              </p>
              <p class="text-gray-700 text-lg md:text-xl leading-relaxed">
                Realiving was duly incorporated under the trade name <span class="font-semibold text-sky-600 text-xl md:text-2xl">Brava Homes</span> situated at Warehouse 5, Sunhope Compound, Purok 7, Brgy. Calasag, San Ildefonso, Bulacan. It primarily engages in wholesale and retail of competitively priced, high-quality construction, plumbing, and decorative materials.
              </p>
            </div>
          </div>
          
          <!-- Interactive Elements -->
          <details class="group bg-sky-50 bg-opacity-50 rounded-xl overflow-hidden border border-blue-100 transition-all duration-300">
            <summary class="cursor-pointer p-6 flex items-center justify-between text-sky-700 font-semibold text-xl">
              <span>Our Mission & Vision</span>
              <span class="transform group-open:rotate-180 transition-transform duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </span>
            </summary>
            <div class="p-6 pt-0 bg-white bg-opacity-50">
              <div class="space-y-4">
                <div class="flex items-start">
                  <div class="flex-shrink-0 mt-1">
                    <span class="flex items-center justify-center h-8 w-8 rounded-full bg-yellow-100 text-yellow-600">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                      </svg>
                    </span>
                  </div>
                  <div class="ml-4">
                    <h4 class="text-lg font-semibold text-sky-800">Our Vision</h4>
                    <p class="mt-2 text-gray-700">To be the premier design-build company recognized for creating exceptional living and working environments that inspire and elevate the human experience.</p>
                  </div>
                </div>
                <div class="flex items-start">
                  <div class="flex-shrink-0 mt-1">
                    <span class="flex items-center justify-center h-8 w-8 rounded-full bg-sky-100 text-sky-600">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7 2a1 1 0 00-.707 1.707L7 4.414v3.758a1 1 0 01-.293.707l-4 4C.817 14.769 2.156 18 4.828 18h10.343c2.673 0 4.012-3.231 2.122-5.121l-4-4A1 1 0 0113 8.172V4.414l.707-.707A1 1 0 0013 2H7zm2 6.172V4h2v4.172a3 3 0 00.879 2.12l1.168 1.168a4 4 0 00-2.278.93l-.869.87a4 4 0 00-2.278-.93l1.168-1.168A3 3 0 009 8.172z" clip-rule="evenodd" />
                      </svg>
                    </span>
                  </div>
                  <div class="ml-4">
                    <h4 class="text-lg font-semibold text-sky-800">Our Mission</h4>
                    <p class="mt-2 text-gray-700">To deliver innovative, high-quality architectural solutions through collaborative partnerships with our clients, focusing on craftsmanship, sustainability, and exceptional service at every step.</p>
                  </div>
                </div>
              </div>
            </div>
          </details>
          
          <details class="group mt-4 bg-yellow-50 bg-opacity-50 rounded-xl overflow-hidden border border-yellow-100 transition-all duration-300">
            <summary class="cursor-pointer p-6 flex items-center justify-between text-yellow-700 font-semibold text-xl">
              <span>Our Core Values</span>
              <span class="transform group-open:rotate-180 transition-transform duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </span>
            </summary>
            <div class="p-6 pt-0 bg-white bg-opacity-50">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-4 bg-white rounded-lg shadow-sm border border-gray-100">
                  <h4 class="font-semibold text-yellow-600 mb-2">Excellence</h4>
                  <p class="text-gray-700">We strive for excellence in everything we do, from design concept to final installation.</p>
                </div>
                <div class="p-4 bg-white rounded-lg shadow-sm border border-gray-100">
                  <h4 class="font-semibold text-sky-600 mb-2">Integrity</h4>
                  <p class="text-gray-700">We conduct our business with the highest level of honesty, transparency and ethical standards.</p>
                </div>
                <div class="p-4 bg-white rounded-lg shadow-sm border border-gray-100">
                  <h4 class="font-semibold text-yellow-600 mb-2">Innovation</h4>
                  <p class="text-gray-700">We continuously seek innovative solutions that push boundaries and exceed expectations.</p>
                </div>
                <div class="p-4 bg-white rounded-lg shadow-sm border border-gray-100">
                  <h4 class="font-semibold text-sky-600 mb-2">Client-Centric</h4>
                  <p class="text-gray-700">We prioritize our clients' needs and satisfaction throughout the entire process.</p>
                </div>
              </div>
            </div>
          </details>
        </div>
      </div>
      
      <!-- Call to Action -->
      <div class="mt-12 text-center">
        <a href="about.php" class="inline-block px-8 py-4 bg-gradient-to-r from-sky-600 to-blue-700 text-white font-semibold rounded-full shadow-lg transform transition duration-300 hover:scale-105 hover:shadow-xl">
          Learn More About Us
        </a>
      </div>
    </div>
  </div>
</section>



  <section class="py-16 bg-gray-300 relative" data-aos="fade-up">
    <!-- Background Image with Overlay -->
    <div class="absolute inset-0 bg-cover bg-center opacity-70" style="background-image: url('../realivingpage/images/new.png');"></div>

    <div class="container mx-auto px-6 relative z-10">
      <div class="text-center">
        <h2 class="text-3xl font-bold text-black">Services</h2>
        <hr class="w-10 h-1 bg-yellow-500 mx-auto my-4 border-0 rounded">
      </div>

      <div class="relative max-w-5xl mx-auto mt-16">
        <!-- Vertical center line -->
        <div class="absolute left-1/2 transform -translate-x-1/2 h-full w-1 bg-black"></div>

        <div class="space-y-16">

          <!-- DESIGN - Left -->
          <div class="relative flex justify-start items-center" data-aos="fade-right">
            <!-- Text Box -->
            <div class="w-1/2 pr-8 text-right">
              <div class="bg-gray-100 p-6 rounded-lg shadow-lg inline-block">
                <h3 class="text-xl font-bold montserrat text-black">DESIGN</h3>
                <p class="text-black mt-2">We create smart, space-saving, and stylish designs tailored to your space and lifestyle needs.</p>
                <a href="#design" class="inline-block mt-4 px-4 py-2 bg-yellow-500 text-white rounded montserrat hover:bg-yellow-600 transition">Read More</a>
              </div>
            </div>

            <!-- White Circle -->
            <div class="w-8 h-8 bg-black rounded-full border-4 border-white absolute left-1/2 transform -translate-x-1/2"></div>

            <!-- Image with Title on Hover -->
            <div class="w-1/2 ml-[50px] relative group">
              <div class="h-64 overflow-hidden rounded-lg shadow-md">
                <img src="../logo/real.png" alt="Design Service" class="w-full h-full object-cover" />
              </div>

              <!-- Title Overlay on Hover -->
              <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition duration-300 rounded-lg flex justify-center items-center">
                <h3 class="text-white text-3xl font-semibold montserrat">DESIGN</h3>
              </div>
            </div>
          </div>


          <!-- FABRICATE - Right -->
          <div class="relative flex justify-end items-center" data-aos="fade-left">
            <div class="w-1/2 relative group">
              <!-- Image -->
              <div class="h-64 overflow-hidden rounded-lg shadow-md">
                <img src="../logo/nh.jpg" alt="Fabricate Service" class="w-full h-full object-cover" />
              </div>

              <!-- Title Overlay on Hover -->
              <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition duration-300 rounded-lg flex justify-center items-center">
                <h3 class="text-white text-3xl font-semibold montserrat">FABRICATE</h3>
              </div>
            </div>

            <!-- White Circle -->
            <div class="w-8 h-8 bg-black rounded-full border-4 border-white absolute left-1/2 transform -translate-x-1/2"></div>

            <!-- Text Box -->
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
              <div class="h-64 overflow-hidden rounded-lg shadow-md">
                <img src="../logo/deli.png" alt="Delivered Service" class="w-full h-full object-cover" />
              </div>
              <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition duration-300 rounded-lg flex items-center justify-center">
                <h3 class="text-white text-3xl font-semibold montserrat">DELIVERED</h3>
              </div>
            </div>
          </div>

          <!-- INSTALLATION - Right -->
          <div class="relative flex justify-end items-center" data-aos="fade-left">
            <div class="w-1/2 relative group">
              <div class="h-64 overflow-hidden rounded-lg shadow-md">
                <img src="../logo/insta.png" alt="Installation Service" class="w-full h-full object-cover" />
              </div>
              <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition duration-300 rounded-lg flex items-center justify-center">
                <h3 class="text-white text-3xl font-semibold montserrat">INSTALLATION</h3>
              </div>
            </div>
            <div class="w-8 h-8 bg-black rounded-full border-4 border-white absolute left-1/2 transform -translate-x-1/2"></div>
            <div class="w-1/2 pl-8 ml-[50px] text-left">
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
  <footer class="bg-gray-900 text-white">
    <div class="container mx-auto px-4 py-8">
      <!-- Main Footer Content -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 pb-8">
        <!-- Company Info -->
        <div>
          <div class="mb-6">
            <h2 class="text-3xl font-bold mb-4">
              <span class="text-blue-400">Real</span>
              <span class="text-yellow-500">Living</span>
            </h2>
            <p class="text-gray-300">
              Transforming spaces into beautiful, functional environments
              that enhance the quality of life for our clients.
            </p>
          </div>

          <div class="space-y-3">
            <div class="flex items-center">
              <svg class="h-5 w-5 mr-3 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>
              <span>+63 912 345 6789</span>
            </div>
            <div class="flex items-center">
              <svg class="h-5 w-5 mr-3 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
              <span>info@realiving.com</span>
            </div>
            <div class="flex items-center">
              <svg class="h-5 w-5 mr-3 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <span>123 Design Street, Makati City, Philippines</span>
            </div>
            <div class="flex items-center">
              <svg class="h-5 w-5 mr-3 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>Mon-Fri: 9AM-6PM | Sat: 10AM-2PM</span>
            </div>
          </div>
        </div>

        <!-- Quick Links -->
        <div>
          <h3 class="text-xl font-semibold mb-6 border-b border-gray-700 pb-2">Quick Links</h3>
          <ul class="space-y-3">
            <li><a href="index.php" class="hover:text-blue-400 transition">Home</a></li>
            <li><a href="about.php" class="hover:text-blue-400 transition">About Us</a></li>
            <li><a href="services.php" class="hover:text-blue-400 transition">Services</a></li>
            <li><a href="projects.php" class="hover:text-blue-400 transition">Projects</a></li>
            <li><a href="whats-new.php" class="hover:text-blue-400 transition">What's New</a></li>
            <li><a href="contact.php" class="hover:text-blue-400 transition">Contact</a></li>
          </ul>
        </div>

        <!-- Newsletter -->
        <div>
          <h3 class="text-xl font-semibold mb-6 border-b border-gray-700 pb-2">Stay Updated</h3>
          <p class="mb-4 text-gray-300">Subscribe to our newsletter for design tips and exclusive offers.</p>
          <form action="subscribe.php" method="post" class="flex">
            <input
              type="email"
              name="email"
              placeholder="Your email address"
              class="px-4 py-2 w-full bg-gray-800 border border-gray-700 focus:outline-none focus:border-blue-400"
              required />
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 px-4 py-2 transition">
              Subscribe
            </button>
          </form>

          <div class="mt-6">
            <h4 class="text-lg mb-3">Follow Us</h4>
            <div class="flex space-x-4">
              <a href="#" class="bg-gray-800 p-2 rounded-full hover:bg-blue-600 transition">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z" />
                </svg>
              </a>
              <a href="#" class="bg-gray-800 p-2 rounded-full hover:bg-pink-600 transition">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                </svg>
              </a>
              <a href="#" class="bg-gray-800 p-2 rounded-full hover:bg-blue-400 transition">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M23.954 4.569a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723 9.99 9.99 0 01-3.127 1.195 4.92 4.92 0 00-8.384 4.482C7.691 8.094 4.066 6.13 1.64 3.161a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.061a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.937 4.937 0 004.604 3.417 9.868 9.868 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.054 0 13.999-7.496 13.999-13.986 0-.209 0-.42-.015-.63a9.936 9.936 0 002.46-2.548l-.047-.02z" />
                </svg>
              </a>
              <a href="#" class="bg-gray-800 p-2 rounded-full hover:bg-blue-800 transition">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                </svg>
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <a href="quote.php" class="bg-blue-500 hover:bg-blue-600 text-white py-3 font-medium transition flex items-center justify-center">
          Request Quote
        </a>
        <a href="consultation.php" class="bg-yellow-500 hover:bg-yellow-600 text-white py-3 font-medium transition flex items-center justify-center">
          Schedule Consultation
        </a>
        <a href="portfolio.php" class="bg-gray-700 hover:bg-gray-600 text-white py-3 font-medium transition flex items-center justify-center">
          View Portfolio
        </a>
        <a href="contact-designer.php" class="bg-blue-700 hover:bg-blue-800 text-white py-3 font-medium transition flex items-center justify-center">
          Contact Designer
        </a>
      </div>

      <!-- Copyright -->
      <div class="text-center pt-4 border-t border-gray-800 text-gray-400 text-sm">
        <p>&copy; <?php echo date('Y'); ?> RealLiving Design Center Corporation. All rights reserved.</p>
      </div>
    </div>
  </footer>
  <!-- Scroll to Top Button -->
  <button id="scrollToTop" class="fixed bottom-8 right-8 w-12 h-12 bg-yellow-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-yellow-600 transition-transform transform hover:scale-110" style="display: none;">
    ↑
  </button>

  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

  <script>
    AOS.init();
  </script>
  <!-- JavaScript for Sliding News -->


<script>
// Add smooth reveal animations
document.addEventListener('DOMContentLoaded', function() {
  // Get all details elements
  const allDetails = document.querySelectorAll('details');
  
  // Add transition effects
  allDetails.forEach(details => {
    details.addEventListener('toggle', function() {
      if (details.open) {
        details.classList.add('shadow-md');
      } else {
        details.classList.remove('shadow-md');
      }
    });
  });
  
  // Optional: Animate the decorative elements
  const decorElements = document.querySelectorAll('section > div:nth-child(-n+3)');
  decorElements.forEach((elem, index) => {
    elem.style.animation = `float ${6 + index}s ease-in-out infinite`;
    elem.style.animationDelay = `${index * 0.5}s`;
  });
});

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