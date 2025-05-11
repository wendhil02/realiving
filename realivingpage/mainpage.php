<?php
include '../connection/connection.php';
$result = $conn->query("SELECT * FROM news ORDER BY created_at DESC");
include 'htmldesign/mainhead.php';
include 'htmldesign/top.php';
?>

<style>
  /* Add animation for decorative elements */
  @keyframes float {
    0% {
      transform: translateY(0px);
    }

    50% {
      transform: translateY(-2px);
    }

    100% {
      transform: translateY(0px);
    }
  }

  .animate-pulse {
    animation: pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
  }

  @keyframes pulse {

    0%,
    100% {
      opacity: 0.3;
    }

    50% {
      opacity: 0.5;
    }
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
      width: 56%;
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
 <section class="relative w-full h-[70vh] overflow-hidden font-sans">
  <!-- Background Video -->
  <video autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover z-0">
    <source src="../videos/realiving.mp4" type="video/mp4">
    Your browser does not support the video tag.
  </video>

  <!-- Solid Dark Overlay -->
  <div class="absolute inset-0 bg-black/60 z-10"></div>

  <!-- Hero Content -->
  <div class="relative z-20 flex flex-col items-center justify-center h-full text-center px-4 md:px-8 mt-[-40px]">
    <!-- Logo -->
    <div class="w-[1600px] bg-black/50 backdrop-blur-sm py-6 rounded-none shadow-lg mb-6 md:mb-10">
      <img src="../logo/mmone.png" alt="Company Logo" class="w-52 md:w-64 lg:w-72 mx-auto transform transition-transform duration-300 hover:scale-105">
    </div>

    <!-- Subtitle -->
    <p class="text-gray-100 text-lg md:text-xl font-medium mb-6 typing-animation">
      Your Partner in Elegant & Functional Interior Solutions
    </p>

    <!-- Call to Action Button -->
    <a href="#services" class="inline-block px-6 py-3 bg-white text-gray-900 font-semibold rounded-full shadow-lg hover:bg-gray-100 transition-all duration-300">
      Explore Our Services
    </a>
  </div>
</section>



  <section class=" py-16 bg-gray-100" data-aos="fade-up">
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



<section class="relative py-28 px-6 md:px-24 overflow-hidden bg-gradient-to-br from-white via-sky-50 to-blue-50" data-aos="fade-up">
  
  <div class="max-w-6xl mx-auto">
    <!-- Card Container -->
    <div class="bg-white bg-opacity-80 backdrop-blur-lg rounded-3xl p-10 md:p-14 border border-sky-100 shadow-xl hover:shadow-2xl transition duration-500">

      <!-- Header -->
      <div class="text-center mb-12">
        <h2 class="text-4xl md:text-5xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-blue-700 via-sky-600 to-sky-400 tracking-tight">
          <i class="fas fa-users mr-2"></i> Who We Are
        </h2>
        <div class="h-1 w-32 bg-gradient-to-r from-yellow-400 to-yellow-600 mx-auto rounded-full mt-4"></div>
      </div>

      <!-- Company Description -->
      <div class="relative mb-10 pl-4 border-l-4 border-blue-500">
        <p class="text-gray-800 text-lg md:text-xl mb-4">
          <span class="font-bold text-yellow-600 text-xl">Realiving Design Center Corp.</span> is a leading architectural fit-out company specializing in design, manufacturing, and installation of premium modular cabinets. We are passionate about creating beautifully functional spaces that elevate the lives of our clients.
        </p>
        <p class="text-gray-800 text-lg md:text-xl">
          Operating under the trade name <span class="font-bold text-sky-600 text-xl">Brava Homes</span>, Realiving is located at Warehouse 5, Sunhope Compound, Purok 7, Brgy. Calasag, San Ildefonso, Bulacan. The company also engages in the wholesale and retail of quality construction, plumbing, and decorative materials.
        </p>
      </div>

      <!-- Accordion Section -->
      <div class="space-y-6">
        <!-- Mission & Vision -->
        <details class="group rounded-xl border border-blue-100 overflow-hidden bg-white bg-opacity-60 transition-all duration-300">
          <summary class="cursor-pointer p-6 flex justify-between items-center text-sky-700 font-semibold text-xl">
            <span><i class="fas fa-eye mr-2"></i> Our Mission & Vision</span>
            <svg class="h-6 w-6 transform group-open:rotate-180 transition" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </summary>
          <div class="p-6 pt-0 space-y-6">
            <div class="flex items-start">
              <div class="mt-1 mr-4 text-yellow-500">
                <i class="fas fa-lightbulb text-xl"></i>
              </div>
              <div>
                <h4 class="font-semibold text-sky-800 text-lg">Our Vision</h4>
                <p class="text-gray-700 mt-1">To be the premier design-build company known for creating inspiring living and working spaces.</p>
              </div>
            </div>
            <div class="flex items-start">
              <div class="mt-1 mr-4 text-sky-500">
                <i class="fas fa-bullseye text-xl"></i>
              </div>
              <div>
                <h4 class="font-semibold text-sky-800 text-lg">Our Mission</h4>
                <p class="text-gray-700 mt-1">To deliver innovative, high-quality architectural solutions through craftsmanship and collaboration.</p>
              </div>
            </div>
          </div>
        </details>

        <!-- Core Values -->
        <details class="group rounded-xl border border-yellow-100 overflow-hidden bg-white bg-opacity-60 transition-all duration-300">
          <summary class="cursor-pointer p-6 flex justify-between items-center text-yellow-700 font-semibold text-xl">
            <span><i class="fas fa-star mr-2"></i> Our Core Values</span>
            <svg class="h-6 w-6 transform group-open:rotate-180 transition" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </summary>
          <div class="p-6 pt-0 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="p-5 rounded-lg bg-white border border-gray-100 shadow-sm">
              <h4 class="font-semibold text-yellow-600 mb-1">Excellence</h4>
              <p class="text-gray-700">We strive for outstanding results in every project we undertake.</p>
            </div>
            <div class="p-5 rounded-lg bg-white border border-gray-100 shadow-sm">
              <h4 class="font-semibold text-sky-600 mb-1">Integrity</h4>
              <p class="text-gray-700">We operate with honesty, transparency, and strong ethical values.</p>
            </div>
            <div class="p-5 rounded-lg bg-white border border-gray-100 shadow-sm">
              <h4 class="font-semibold text-yellow-600 mb-1">Innovation</h4>
              <p class="text-gray-700">We embrace change and pioneer creative solutions.</p>
            </div>
            <div class="p-5 rounded-lg bg-white border border-gray-100 shadow-sm">
              <h4 class="font-semibold text-sky-600 mb-1">Client-Centric</h4>
              <p class="text-gray-700">We value our clients and put their needs at the heart of our process.</p>
            </div>
          </div>
        </details>
      </div>

      <!-- Call to Action -->
      <div class="text-center mt-12">
        <a href="about.php" class="inline-block px-8 py-4 bg-gradient-to-r from-sky-600 to-blue-700 text-white font-semibold rounded-full shadow-md hover:scale-105 hover:shadow-xl transition">
          Learn More About Us
        </a>
      </div>
    </div>
  </div>
</section>

<section class="w-full min-h-[65vh] flex flex-col md:flex-row items-center justify-center font-sans bg-black" data-aos="fade-up">
  <!-- Left Side: Video with Overlay -->
  <div class="w-full md:w-1/2 h-[300px] md:h-[65vh] relative group">
    <video autoplay muted loop playsinline preload="auto" class="w-full h-full object-cover brightness-75 group-hover:brightness-90 transition duration-500">
      <source src="../videos/contentone.mp4" type="video/mp4">
      Your browser does not support the video tag.
    </video>

  </div>

  <!-- Right Side: Content -->
  <div class="w-full md:w-1/2 px-6 md:px-12 py-12 text-white space-y-6 bg-gradient-to-br from-black via-gray-900 to-black/90 backdrop-blur-sm shadow-2xl">
    <div class="border-l-4 border-white pl-4">
      <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight">Realiving Design Center</h2>
    </div>
    <p class="text-lg md:text-xl leading-relaxed">
      Transform your study or home office into an inspiring, functional retreat with our bespoke custom furniture and smart storage solutions.
      From conceptual design to precision manufacturing and seamless installation, we tailor every element to fit your workflow, aesthetic, and space requirements.
    </p>
    <a href="#services" class="inline-block px-6 py-3 bg-white text-black font-semibold rounded-full shadow-md hover:bg-gray-200 transition-all duration-300">
      Inquire
    </a>
  </div>
</section>

 <section class="py-24 relative overflow-hidden bg-gradient-to-b from-gray-50 to-gray-200">
  <!-- Decorative Elements -->
  <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0">
    <div class="absolute -top-10 -left-10 w-64 h-64 bg-yellow-500 rounded-full opacity-10"></div>
    <div class="absolute top-1/3 -right-10 w-80 h-80 bg-yellow-500 rounded-full opacity-10"></div>
    <div class="absolute bottom-0 left-1/4 w-48 h-48 bg-yellow-500 rounded-full opacity-10"></div>
  </div>

  <div class="container mx-auto px-4 relative z-10">
    <div class="text-center mb-20">
      <h2 class="text-5xl font-bold text-gray-900 uppercase tracking-wide montserrat relative inline-block">
        Our Services
        <span class="absolute -bottom-4 left-1/2 transform -translate-x-1/2 w-24 h-1 bg-yellow-500"></span>
      </h2>
      <p class="mt-6 text-gray-600 max-w-2xl mx-auto">Transforming your space with custom design, quality fabrication, reliable delivery, and expert installation.</p>
    </div>

    <div class="relative max-w-6xl mx-auto">
      <!-- Vertical Timeline Line with Animation -->
      <div class="absolute left-1/2 transform -translate-x-1/2 h-full w-1 bg-gradient-to-b from-yellow-400 to-yellow-600"></div>

      <!-- DESIGN -->
      <div class="relative flex flex-col md:flex-row items-center mb-32 group">
        <!-- Text Content -->
        <div class="md:w-1/2 md:pr-12 z-10 order-2 md:order-1 mt-10 md:mt-0">
          <div class="bg-white rounded-2xl shadow-xl p-8 border-t-4 border-yellow-500 transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl max-w-md ml-auto">
            <div class="flex items-center mb-4">
              <div class="bg-yellow-100 p-3 rounded-full mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                </svg>
              </div>
              <h3 class="text-3xl font-bold text-gray-800 montserrat">DESIGN</h3>
            </div>
            <p class="text-gray-700 mt-4 leading-relaxed">We create smart, space-saving, and stylish designs tailored to your specific space and lifestyle needs. Our expert designers work closely with you to bring your vision to life.</p>
            <a href="#design" class="inline-flex items-center mt-6 px-6 py-3 bg-yellow-500 text-white font-medium rounded-lg hover:bg-yellow-600 transition transform hover:-translate-y-1">
              Read More
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </a>
          </div>
        </div>

        <!-- Animated Circle -->
        <div class="absolute left-1/2 transform -translate-x-1/2 w-12 h-12 flex items-center justify-center z-20">
          <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center shadow-lg animate-pulse">
            <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center">
              <span class="text-yellow-500 font-bold">01</span>
            </div>
          </div>
        </div>

        <!-- Image -->
        <div class="md:w-1/2 md:pl-12 relative order-1 md:order-2">
          <div class="overflow-hidden rounded-2xl shadow-xl relative group">
            <img src="../logo/real.png" class="w-full h-80 object-cover transform transition duration-700 group-hover:scale-110" alt="Design">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-70 flex flex-col justify-end p-8">
              <h3 class="text-white text-3xl font-semibold montserrat">DESIGN</h3>
              <p class="text-yellow-300 mt-2 font-medium">Turning ideas into plans</p>
            </div>
          </div>
        </div>
      </div>

      <!-- FABRICATE -->
      <div class="relative flex flex-col md:flex-row items-center mb-32 group">
        <!-- Image -->
        <div class="md:w-1/2 md:pr-12 relative order-1">
          <div class="overflow-hidden rounded-2xl shadow-xl relative group">
            <img src="../logo/nh.jpg" class="w-full h-80 object-cover transform transition duration-700 group-hover:scale-110" alt="Fabricate">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-70 flex flex-col justify-end p-8">
              <h3 class="text-white text-3xl font-semibold montserrat">FABRICATE</h3>
              <p class="text-yellow-300 mt-2 font-medium">Crafting with precision</p>
            </div>
          </div>
        </div>

        <!-- Animated Circle -->
        <div class="absolute left-1/2 transform -translate-x-1/2 w-12 h-12 flex items-center justify-center z-20">
          <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center shadow-lg animate-pulse">
            <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center">
              <span class="text-yellow-500 font-bold">02</span>
            </div>
          </div>
        </div>

        <!-- Text Content -->
        <div class="md:w-1/2 md:pl-12 z-10 order-2 mt-10 md:mt-0">
          <div class="bg-white rounded-2xl shadow-xl p-8 border-t-4 border-yellow-500 transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl max-w-md">
            <div class="flex items-center mb-4">
              <div class="bg-yellow-100 p-3 rounded-full mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
              </div>
              <h3 class="text-3xl font-bold text-gray-800 montserrat">FABRICATE</h3>
            </div>
            <p class="text-gray-700 mt-4 leading-relaxed">Using only the highest quality materials, we build each piece with precision and care to ensure durability, functionality, and a modern finish that lasts for years.</p>
            <a href="#fabricate" class="inline-flex items-center mt-6 px-6 py-3 bg-yellow-500 text-white font-medium rounded-lg hover:bg-yellow-600 transition transform hover:-translate-y-1">
              Read More
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </a>
          </div>
        </div>
      </div>

      <!-- DELIVERED -->
      <div class="relative flex flex-col md:flex-row items-center mb-32 group">
        <!-- Text Content -->
        <div class="md:w-1/2 md:pr-12 z-10 order-2 md:order-1 mt-10 md:mt-0">
          <div class="bg-white rounded-2xl shadow-xl p-8 border-t-4 border-yellow-500 transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl max-w-md ml-auto">
            <div class="flex items-center mb-4">
              <div class="bg-yellow-100 p-3 rounded-full mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                </svg>
              </div>
              <h3 class="text-3xl font-bold text-gray-800 montserrat">DELIVERED</h3>
            </div>
            <p class="text-gray-700 mt-4 leading-relaxed">We transport your furniture safely and on time—straight to your doorstep. Our delivery team handles your custom pieces with care to ensure they arrive in perfect condition.</p>
            <a href="#delivered" class="inline-flex items-center mt-6 px-6 py-3 bg-yellow-500 text-white font-medium rounded-lg hover:bg-yellow-600 transition transform hover:-translate-y-1">
              Read More
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </a>
          </div>
        </div>

        <!-- Animated Circle -->
        <div class="absolute left-1/2 transform -translate-x-1/2 w-12 h-12 flex items-center justify-center z-20">
          <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center shadow-lg animate-pulse">
            <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center">
              <span class="text-yellow-500 font-bold">03</span>
            </div>
          </div>
        </div>

        <!-- Image -->
        <div class="md:w-1/2 md:pl-12 relative order-1 md:order-2">
          <div class="overflow-hidden rounded-2xl shadow-xl relative group">
            <img src="../logo/deli.png" class="w-full h-80 object-cover transform transition duration-700 group-hover:scale-110" alt="Delivered">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-70 flex flex-col justify-end p-8">
              <h3 class="text-white text-3xl font-semibold montserrat">DELIVERED</h3>
              <p class="text-yellow-300 mt-2 font-medium">On time, every time</p>
            </div>
          </div>
        </div>
      </div>

      <!-- INSTALLATION -->
      <div class="relative flex flex-col md:flex-row items-center group">
        <!-- Image -->
        <div class="md:w-1/2 md:pr-12 relative order-1">
          <div class="overflow-hidden rounded-2xl shadow-xl relative group">
            <img src="../logo/insta.png" class="w-full h-80 object-cover transform transition duration-700 group-hover:scale-110" alt="Installation">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-70 flex flex-col justify-end p-8">
              <h3 class="text-white text-3xl font-semibold montserrat">INSTALLATION</h3>
              <p class="text-yellow-300 mt-2 font-medium">Expert assembly and setup</p>
            </div>
          </div>
        </div>

        <!-- Animated Circle -->
        <div class="absolute left-1/2 transform -translate-x-1/2 w-12 h-12 flex items-center justify-center z-20">
          <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center shadow-lg animate-pulse">
            <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center">
              <span class="text-yellow-500 font-bold">04</span>
            </div>
          </div>
        </div>

        <!-- Text Content -->
        <div class="md:w-1/2 md:pl-12 z-10 order-2 mt-10 md:mt-0">
          <div class="bg-white rounded-2xl shadow-xl p-8 border-t-4 border-yellow-500 transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl max-w-md">
            <div class="flex items-center mb-4">
              <div class="bg-yellow-100 p-3 rounded-full mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
              </div>
              <h3 class="text-3xl font-bold text-gray-800 montserrat">INSTALLATION</h3>
            </div>
            <p class="text-gray-700 mt-4 leading-relaxed">Our team of professionals handles the setup efficiently, making sure everything is perfectly fitted, securely mounted, and ready to use immediately.</p>
            <a href="#installation" class="inline-flex items-center mt-6 px-6 py-3 bg-yellow-500 text-white font-medium rounded-lg hover:bg-yellow-600 transition transform hover:-translate-y-1">
              Read More
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

  <section class="py-20 bg-gray-200 px-6 md:px-20" data-aos="fade-up">
    <div class="text-center mb-12">
      <h2 class="text-4xl font-bold text-gray-800">What Clients Say</h2>
      <hr class="w-12 h-1 bg-yellow-500 mx-auto mt-4 rounded border-0">
    </div>

    <div class="grid md:grid-cols-2 gap-10 max-w-6xl mx-auto">

      <!-- Testimonial 1 -->
      <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition duration-300">
        <div class="flex items-center gap-4 mb-6">
          <img src="../logo/nh.png" alt="Janelle M." class="w-14 h-14 rounded-full object-cover shadow">
          <div>
            <p class="text-lg font-semibold text-gray-900">Janelle M.</p>
            <p class="text-sm text-gray-500">Homeowner</p>
          </div>
        </div>
        <p class="text-gray-700 italic">“Realiving turned our empty unit into a beautiful, functional space. Their team was professional and creative from start to finish.”</p>
      </div>

      <!-- Testimonial 2 -->
      <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition duration-300">
        <div class="flex items-center gap-4 mb-6">
          <img src="../logo/nh.png" alt="Carlo D." class="w-14 h-14 rounded-full object-cover shadow">
          <div>
            <p class="text-lg font-semibold text-gray-900">Carlo D.</p>
            <p class="text-sm text-gray-500">Business Owner</p>
          </div>
        </div>
        <p class="text-gray-700 italic">“Excellent service and amazing results. Our office renovation was seamless and exceeded expectations.”</p>
      </div>

    </div>
  </section>

  <section class="py-20 bg-cover bg-center relative" data-aos="fade-up" style="background-image: url('../uploads/home.jpg');">
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black bg-opacity-70 z-0"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 md:px-10">
      <h2 class="text-4xl font-bold text-center text-white mb-4">Latest News</h2>
      <hr class="w-16 h-1 bg-yellow-500 mx-auto mb-10 border-0 rounded">

      <div class="overflow-hidden" id="newsContainer">
        <div id="sliderTrack" class="flex gap-6 transition-all duration-500 ease-in-out w-max">
          <?php
          $news = [];
          while ($row = $result->fetch_assoc()) {
            $news[] = $row;
          }

          // duplicate news list for smooth looping
          $loopNews = array_merge($news, $news);

          foreach ($loopNews as $row):
          ?>
            <div class="min-w-[300px] max-w-sm bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition duration-300 flex-shrink-0">
              <img src="<?= '../uploads/' . $row['image'] ?>" alt="<?= htmlspecialchars($row['title']) ?>" class="w-full h-48 object-cover">
              <div class="p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-2"><?= htmlspecialchars($row['title']) ?></h3>
                <p class="text-sm text-gray-600 mb-4"><?= htmlspecialchars($row['summary']) ?></p>
                <a href="<?= $row['link'] ?>" class="block w-full text-center px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition font-medium">View More</a>
              </div>
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
            <div class="flex justify-center mb-8">
              <img src="../logo/mmone.png" alt="Your Image" class="w-[80%] max-w-[800px] h-auto rounded-lg shadow-lg">
            </div>

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
              <span>MC Premier – EDSA Balintawak, Quezon City</span>
            </div>
            <div class="flex items-center">
              <svg class="h-5 w-5 mr-3 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>Mon-Fri: 7AM-5PM | Sat: 7AM-12PM</span>
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