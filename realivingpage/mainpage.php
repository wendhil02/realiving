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
  <section class="relative w-full h-screen overflow-hidden font-sans">
    <!-- Background Video -->
    <video autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover z-0">
      <source src="../videos/realiving.mp4" type="video/mp4">
      Your browser does not support the video tag.
    </video>

    <!-- Gradient Overlay for better text visibility -->
    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/60 to-black/40 z-10"></div>

    <!-- Hero Content -->
    <div class="relative z-20 flex flex-col items-center justify-center h-full text-center px-6 md:px-12 lg:px-20">
      <!-- Logo Container with subtle glass effect -->
      <div class="w-full max-w-2xl bg-black/30 backdrop-blur-md py-8 rounded-lg shadow-2xl mb-8 border border-white/10 transform transition-all duration-500 hover:bg-black/40">
        <img src="../logo/mmone.png" alt="MMOne" class="w-48 md:w-60 lg:w-72 mx-auto transform transition-transform duration-500 hover:scale-105">
      </div>

      <!-- Tagline with enhanced typography -->
      <h2 class="text-white text-xl md:text-2xl lg:text-3xl font-light mb-4 tracking-wider">
        Your Partner in <span class="font-semibold">Elegant & Functional</span> Interior Solutions
      </h2>

      <!-- Short Description -->
      <p class="text-gray-200 text-base md:text-lg max-w-2xl mb-10 opacity-90">
        Transforming spaces into stunning environments that perfectly balance aesthetics and practicality.
      </p>

      <!-- CTA Buttons -->
      <div class="flex flex-col sm:flex-row gap-4 md:gap-6">
        <a href="#services" class="px-8 py-3 bg-transparent text-white border-2 border-white font-medium rounded-full shadow-lg hover:bg-white/10 transition-all duration-300 transform hover:-translate-y-1">
          Explore Services
        </a>
        <a href="#portfolio" class="px-8 py-3 bg-transparent border-2 border-white text-white font-medium rounded-full shadow-lg hover:bg-white/10 transition-all duration-300 transform hover:-translate-y-1">
          View Portfolio
        </a>
      </div>
    </div>
  </section>


  <!-- Coohom Projects Section -->
  <section class="py-16 bg-gray-100" data-aos="fade-up">
    <div class="max-w-7xl mx-auto px-4">
      <div class="mb-10 text-center">
        <p class="text-gray-600 mt-2">Explore our 360° panoramic designs</p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

        <!-- Project Card 1 -->
        <div class="relative bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden h-[450px]">
          <img src="images/white.png" alt="NobleHome Logo"
            class="absolute top-2 left-3 w-[150px] bg-white p-3 rounded shadow z-10" />
          <iframe src="https://kd20-realiving.yfcad.com/pano?id=61549751"
            allowfullscreen
            class="w-full h-full border-none relative z-0"></iframe>
          <div class="absolute top-0 right-0 w-[55px] h-full bg-yellow-500 z-10 opacity-150 flex items-center justify-center mr-2.5">
            <span class="text-[20px] text-white font-semibold rotate-90 whitespace-nowrap">Site Project </span>
          </div>
        </div>

        <!-- Project Card 2 -->
        <div class="relative bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden h-[450px]">
          <img src="images/white.png" alt="NobleHome Logo"
            class="absolute top-2 left-3 w-[150px] bg-white p-3 rounded shadow z-10" />
          <iframe src="https://kd20-realiving.yfcad.com/pano?id=62840554"
            allowfullscreen
            class="w-full h-full border-none relative z-0"></iframe>
          <div class="absolute top-0 right-0 w-[55px] h-full bg-yellow-500 z-10 opacity-150 flex items-center justify-center mr-2.5">
            <span class="text-[20px] text-white font-semibold rotate-90 whitespace-nowrap">Residential Project</span>
          </div>
        </div>

        <!-- Project Card 3 -->
        <div class="relative bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden h-[450px]">
          <img src="images/white.png" alt="NobleHome Logo"
            class="absolute top-2 left-3 w-[150px] bg-white p-3 rounded shadow z-10" />
          <iframe src="https://kd20-realiving.yfcad.com/pano?id=55736419"
            allowfullscreen
            class="w-full h-full border-none relative z-0"></iframe>
          <div class="absolute top-0 right-0 w-[55px] h-full bg-yellow-500 z-10 opacity-150 flex items-center justify-center mr-2.5">
            <span class="text-[20px] text-white font-semibold rotate-90 whitespace-nowrap">Commercial Project</span>
          </div>
        </div>
      </div>

      <!-- ✅ Buttons Section -->
      <div class="mt-10 flex justify-center gap-4">
        <button class="flex items-center gap-2 px-5 py-2.5 text-base font-medium text-white bg-blue-600 border border-blue-700 rounded-full shadow-sm hover:bg-blue-700 transition-all duration-200 ease-in-out">
          <i class="fas fa-globe"></i>
          All Projects
        </button>

        <button class="flex items-center gap-2 px-5 py-2.5 text-base font-medium text-white bg-green-600 border border-green-700 rounded-full shadow-sm hover:bg-green-700 transition-all duration-200 ease-in-out">
          <i class="fas fa-home"></i>
          Residential
        </button>

        <button class="flex items-center gap-2 px-5 py-2.5 text-base font-medium text-white bg-yellow-500 border border-yellow-600 rounded-full shadow-sm hover:bg-yellow-600 transition-all duration-200 ease-in-out">
          <i class="fas fa-briefcase"></i>
          Commercial
        </button>
      </div>

    </div>
  </section>



  <section class="relative py-24 bg-gradient-to-br from-gray-50 to-blue-50 overflow-hidden" data-aos="fade-up">
    <!-- Subtle background elements -->
    <div class="absolute top-0 right-0 w-72 h-72 bg-yellow-100 rounded-full filter blur-3xl opacity-30"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-blue-100 rounded-full filter blur-3xl opacity-20"></div>

    <!-- Subtle grid pattern -->
    <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <!-- Section Header -->
      <div class="text-center mb-16">
        <span class="inline-block px-4 py-1 bg-blue-50 text-blue-700 rounded-full text-sm font-medium mb-4 shadow-sm">Our Portfolio</span>
        <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-5">Accomplished Projects</h2>
        <div class="flex items-center justify-center mb-6">
          <div class="h-1 w-12 bg-yellow-400 rounded-full"></div>
          <div class="h-1 w-24 bg-blue-600 rounded-full mx-2"></div>
          <div class="h-1 w-12 bg-yellow-400 rounded-full"></div>
        </div>
        <p class="text-gray-600 max-w-2xl mx-auto text-lg">Discover our premium architectural solutions and custom cabinet designs created for clients who value quality and craftsmanship.</p>
      </div>

      <!-- Projects Display - Masonry Style -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
        <!-- Project Card 1 - Featured (Large) -->
        <div class="col-span-1 lg:col-span-2 group" data-aos="fade-up">
          <div class="bg-white rounded-xl overflow-hidden shadow-lg transition duration-300 hover:shadow-2xl h-full flex flex-col transform hover:-translate-y-1">
            <div class="relative h-80">
              <img src="./images/project-1.png" alt="Modern Kitchen Design" class="w-full h-full object-cover">
              <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-end">
                <div class="p-6 text-white w-full">
                  <span class="px-3 py-1 bg-yellow-500 text-xs font-bold uppercase tracking-wider rounded-full">Featured</span>
                  <h3 class="text-2xl font-bold mt-2">Modern Kitchen Design</h3>
                  <p class="text-sm text-white/80 mt-1">Premium modular kitchen cabinets with high-end finishes</p>
                </div>
              </div>
            </div>
            <div class="p-6 flex-grow">
              <div class="flex items-center justify-between mb-3">
                <h3 class="font-bold text-xl text-gray-800">Modern Kitchen Design</h3>
                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">Completed</span>
              </div>
              <p class="text-gray-600 mb-4">Custom cabinetry with integrated smart storage solutions designed for modern family needs.</p>
              <div class="flex items-center justify-between mt-auto">
                <div class="flex items-center space-x-1">
                  <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">Cabinetry</span>
                  <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">Modern</span>
                </div>
                <a href="allproject/allofproject.php" class="text-blue-600 hover:text-blue-800 font-medium flex items-center">
                  View Details
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Project Card 2 -->
        <div class="group" data-aos="fade-up">
          <div class="bg-white rounded-xl overflow-hidden shadow-lg transition duration-300 hover:shadow-2xl h-full flex flex-col transform hover:-translate-y-1">
            <div class="relative h-60">
              <img src="./images/project-2.png" alt="Custom Living Room" class="w-full h-full object-cover">
              <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-end">
                <div class="p-6 text-white w-full">
                  <h3 class="text-xl font-bold">Custom Living Room</h3>
                  <p class="text-sm text-white/80 mt-1">Contemporary furniture solutions</p>
                </div>
              </div>
            </div>
            <div class="p-6 flex-grow">
              <div class="flex items-center justify-between mb-3">
                <h3 class="font-bold text-lg text-gray-800">Custom Living Room</h3>
                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">Completed</span>
              </div>
              <p class="text-gray-600 mb-4">Elegant living room design with custom-built furniture for maximum comfort.</p>
              <div class="flex items-center justify-between mt-auto">
                <div class="flex items-center space-x-1">
                  <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">Living Space</span>
                </div>
                <a href="allproject/allofproject.php" class="text-blue-600 hover:text-blue-800 font-medium flex items-center">
                  View Details
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Project Card 3 -->
        <div class="group" data-aos="fade-up">
          <div class="bg-white rounded-xl overflow-hidden shadow-lg transition duration-300 hover:shadow-2xl h-full flex flex-col transform hover:-translate-y-1">
            <div class="relative h-60">
              <img src="./images/project-3.png" alt="Minimalist Office Setup" class="w-full h-full object-cover">
              <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-end">
                <div class="p-6 text-white w-full">
                  <h3 class="text-xl font-bold">Minimalist Office</h3>
                  <p class="text-sm text-white/80 mt-1">Functional workspace solutions</p>
                </div>
              </div>
            </div>
            <div class="p-6 flex-grow">
              <div class="flex items-center justify-between mb-3">
                <h3 class="font-bold text-lg text-gray-800">Minimalist Office Setup</h3>
                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">Completed</span>
              </div>
              <p class="text-gray-600 mb-4">Clean, ergonomic workspace design optimized for productivity and comfort.</p>
              <div class="flex items-center justify-between mt-auto">
                <div class="flex items-center space-x-1">
                  <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">Office</span>
                  <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">Minimalist</span>
                </div>
                <a href="allproject/allofproject.php" class="text-blue-600 hover:text-blue-800 font-medium flex items-center">
                  View Details
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Project Card 4 -->
        <div class="group" data-aos="fade-up">
          <div class="bg-white rounded-xl overflow-hidden shadow-lg transition duration-300 hover:shadow-2xl h-full flex flex-col transform hover:-translate-y-1">
            <div class="relative h-60">
              <img src="./images/project-4.png" alt="Industrial Bar Counter" class="w-full h-full object-cover">
              <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-end">
                <div class="p-6 text-white w-full">
                  <h3 class="text-xl font-bold">Industrial Bar Counter</h3>
                  <p class="text-sm text-white/80 mt-1">Stylish bar design solutions</p>
                </div>
              </div>
            </div>
            <div class="p-6 flex-grow">
              <div class="flex items-center justify-between mb-3">
                <h3 class="font-bold text-lg text-gray-800">Industrial Bar Counter</h3>
                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">Completed</span>
              </div>
              <p class="text-gray-600 mb-4">Urban-inspired bar counter with industrial finishes for entertainment spaces.</p>
              <div class="flex items-center justify-between mt-auto">
                <div class="flex items-center space-x-1">
                  <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">Bar</span>
                  <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">Industrial</span>
                </div>
                <a href="allproject/allofproject.php" class="text-blue-600 hover:text-blue-800 font-medium flex items-center">
                  View Details
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Project Card 4 -->
        <div class="group" data-aos="fade-up">
          <div class="bg-white rounded-xl overflow-hidden shadow-lg transition duration-300 hover:shadow-2xl h-full flex flex-col transform hover:-translate-y-1">
            <div class="relative h-60">
              <img src="./images/project-4.png" alt="Industrial Bar Counter" class="w-full h-full object-cover">
              <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-end">
                <div class="p-6 text-white w-full">
                  <h3 class="text-xl font-bold">Industrial Bar Counter</h3>
                  <p class="text-sm text-white/80 mt-1">Stylish bar design solutions</p>
                </div>
              </div>
            </div>
            <div class="p-6 flex-grow">
              <div class="flex items-center justify-between mb-3">
                <h3 class="font-bold text-lg text-gray-800">Industrial Bar Counter</h3>
                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">Completed</span>
              </div>
              <p class="text-gray-600 mb-4">Urban-inspired bar counter with industrial finishes for entertainment spaces.</p>
              <div class="flex items-center justify-between mt-auto">
                <div class="flex items-center space-x-1">
                  <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">Bar</span>
                  <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">Industrial</span>
                </div>
                <a href="allproject/allofproject.php" class="text-blue-600 hover:text-blue-800 font-medium flex items-center">
                  View Details
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Project Card 4 -->
        <div class="group" data-aos="fade-up">
          <div class="bg-white rounded-xl overflow-hidden shadow-lg transition duration-300 hover:shadow-2xl h-full flex flex-col transform hover:-translate-y-1">
            <div class="relative h-60">
              <img src="./images/project-4.png" alt="Industrial Bar Counter" class="w-full h-full object-cover">
              <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-end">
                <div class="p-6 text-white w-full">
                  <h3 class="text-xl font-bold">Industrial Bar Counter</h3>
                  <p class="text-sm text-white/80 mt-1">Stylish bar design solutions</p>
                </div>
              </div>
            </div>
            <div class="p-6 flex-grow">
              <div class="flex items-center justify-between mb-3">
                <h3 class="font-bold text-lg text-gray-800">Industrial Bar Counter</h3>
                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">Completed</span>
              </div>
              <p class="text-gray-600 mb-4">Urban-inspired bar counter with industrial finishes for entertainment spaces.</p>
              <div class="flex items-center justify-between mt-auto">
                <div class="flex items-center space-x-1">
                  <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">Bar</span>
                  <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">Industrial</span>
                </div>
                <a href="allproject/allofproject.php" class="text-blue-600 hover:text-blue-800 font-medium flex items-center">
                  View Details
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Project Card 5 -->
        <div class="col-span-1 lg:col-span-2 group" data-aos="fade-up">
          <div class="bg-white rounded-xl overflow-hidden shadow-lg transition duration-300 hover:shadow-2xl h-full flex flex-col transform hover:-translate-y-1">
            <div class="relative h-80">
              <img src="./images/project-5.png" alt="Cozy Bedroom Cabinetry" class="w-full h-full object-cover">
              <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-end">
                <div class="p-6 text-white w-full">
                  <span class="px-3 py-1 bg-yellow-500 text-xs font-bold uppercase tracking-wider rounded-full">Featured</span>
                  <h3 class="text-2xl font-bold mt-2">Cozy Bedroom Cabinetry</h3>
                  <p class="text-sm text-white/80 mt-1">Space-efficient bedroom storage solutions with elegant design</p>
                </div>
              </div>
            </div>
            <div class="p-6 flex-grow">
              <div class="flex items-center justify-between mb-3">
                <h3 class="font-bold text-xl text-gray-800">Cozy Bedroom Cabinetry</h3>
                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">Completed</span>
              </div>
              <p class="text-gray-600 mb-4">Custom bedroom storage solutions designed to maximize space while maintaining aesthetic appeal.</p>
              <div class="flex items-center justify-between mt-auto">
                <div class="flex items-center space-x-1">
                  <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">Bedroom</span>
                  <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">Storage</span>
                </div>
                <a href="allproject/allofproject.php" class="text-blue-600 hover:text-blue-800 font-medium flex items-center">
                  View Details
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- "View All Projects" Button -->
      <div class="text-center mt-12">
        <a href="allproject/allofproject.php" class="inline-flex items-center justify-center px-8 py-4 font-bold text-white bg-gradient-to-r from-blue-600 to-blue-800 rounded-full shadow-lg hover:from-blue-700 hover:to-blue-900 transform hover:-translate-y-1 transition-all duration-300">
          <span>Explore All Projects</span>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
          </svg>
        </a>
      </div>
    </div>
  </section>



  <section id="services" class="py-24 relative overflow-hidden bg-gradient-to-b from-gray-50 to-gray-200" data-aos="fade-up">
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
        <div class="relative flex flex-col md:flex-row items-center mb-32 group" data-aos="fade-up">
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
              <a href="design" class="inline-flex items-center mt-6 px-6 py-3 bg-yellow-500 text-white font-medium rounded-lg hover:bg-yellow-600 transition transform hover:-translate-y-1">
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
        <div class="relative flex flex-col md:flex-row items-center mb-32 group" data-aos="fade-up">
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
        <div class="relative flex flex-col md:flex-row items-center mb-32 group" data-aos="fade-up">
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
        <div class="relative flex flex-col md:flex-row items-center group" data-aos="fade-up">
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

  <section class="w-full min-h-[75vh] flex flex-col md:flex-row font-sans relative overflow-hidden" data-aos="fade-up">
    <!-- Left Side: Video with Enhanced Overlay -->
    <div class="w-full md:w-1/2 h-[350px] md:h-[95vh] relative group overflow-hidden">
      <!-- Video Container -->
      <video autoplay muted loop playsinline preload="auto" class="w-full h-full object-cover scale-105 group-hover:scale-110 transition duration-700 ease-in-out">
        <source src="../videos/contentone.mp4" type="video/mp4">
        Your browser does not support the video tag.
      </video>

      <!-- Elegant Overlay -->
      <div class="absolute inset-0 bg-gradient-to-tr from-black/70 via-black/30 to-transparent opacity-80 group-hover:opacity-60 transition-opacity duration-500"></div>

      <!-- Floating Design Elements -->
      <div class="absolute bottom-6 left-6 md:bottom-10 md:left-10 flex items-center space-x-3">
        <div class="w-12 h-1 bg-white"></div>
        <span class="text-white text-sm tracking-widest uppercase font-light">Excellence in Design</span>
      </div>
    </div>

    <!-- Right Side: Content with Enhanced Design -->
    <div class="w-full md:w-1/2 px-8 md:px-16 py-16 text-white bg-gradient-to-br from-black via-gray-900 to-black flex flex-col justify-center relative">
      <!-- Subtle Background Pattern -->
      <div class="absolute inset-0 opacity-5">
        <div class="absolute top-0 left-0 w-full h-full" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')"></div>
      </div>

      <!-- Main Content -->
      <div class="space-y-8 relative z-10">
        <!-- Title with Elegant Border -->
        <div class="border-l-4 border-white pl-6 py-2 relative">
          <h2 class="text-4xl md:text-5xl font-bold tracking-tight leading-tight">
            Realiving <span class="block mt-1 text-3xl md:text-4xl font-light tracking-wider">Design Center</span>
          </h2>
          <div class="absolute top-0 left-0 w-1 h-full bg-gradient-to-b from-white via-white to-transparent"></div>
        </div>

        <!-- Description with Enhanced Typography -->
        <p class="text-lg leading-relaxed font-light max-w-xl">
          Transform your study or home office into an <span class="italic">inspiring, functional retreat</span> with our bespoke custom furniture and smart storage solutions.
        </p>

        <p class="text-lg leading-relaxed font-light max-w-xl">
          From conceptual design to precision manufacturing and seamless installation, we tailor every element to fit your workflow, aesthetic, and space requirements.
        </p>

        <!-- CTA Area -->

      </div>

      <!-- Bottom Accent -->
      <div class="absolute bottom-0 right-0 w-1/2 h-px bg-gradient-to-r from-transparent to-white/50"></div>
    </div>
  </section>

  <section id="clientsay" class="relative py-24 overflow-hidden bg-gradient-to-br from-gray-50 via-blue-50 to-sky-50" data-aos="fade-up">
    <!-- Decorative Elements -->
    <div class="absolute top-0 right-0 w-72 h-72 bg-yellow-200 rounded-full filter blur-3xl opacity-20"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-blue-200 rounded-full filter blur-3xl opacity-20"></div>

    <!-- Blueprint Grid Background -->
    <div class="absolute inset-0 opacity-5" style="background-image: linear-gradient(#4299e1 1px, transparent 1px), linear-gradient(to right, #4299e1 1px, transparent 1px); background-size: 20px 20px;"></div>

    <!-- Large Quote Marks -->
    <div class="absolute top-20 left-10 text-blue-200 opacity-20" style="font-size: 180px; line-height: 1;">❝</div>
    <div class="absolute bottom-20 right-10 text-blue-200 opacity-20" style="font-size: 180px; line-height: 1;">❞</div>

    <div class="max-w-7xl mx-auto px-6 relative z-10">
      <!-- Header -->
      <div class="text-center mb-16">
        <div class="inline-block mb-4">
          <div class="flex items-center justify-center bg-yellow-500 text-white h-16 w-16 rounded-full shadow-lg mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
            </svg>
          </div>
          <h2 class="text-4xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-700 via-sky-600 to-sky-400 tracking-tight">What Clients Say</h2>
        </div>
        <div class="h-1 w-32 bg-gradient-to-r from-yellow-400 to-yellow-600 mx-auto rounded-full mb-6"></div>
        <p class="text-gray-600 max-w-2xl mx-auto text-lg">Don't just take our word for it. Here's what our valued clients have to say about their experience with Realiving Design Center Corp.</p>
      </div>

      <!-- Testimonials -->
      <div class="grid md:grid-cols-2 gap-8 lg:gap-12">
        <!-- Testimonial 1 -->
        <div class="relative group">
          <!-- Card -->
          <div class="bg-white p-8 md:p-10 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 border border-sky-50 relative z-10 h-full">
            <!-- Quote Icon -->
            <div class="absolute -top-5 -left-5 bg-blue-600 text-white h-10 w-10 rounded-full flex items-center justify-center shadow-lg">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
              </svg>
            </div>

            <!-- Rating Stars -->
            <div class="flex mb-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
            </div>

            <!-- Testimonial Text -->
            <p class="text-gray-700 text-lg mb-8 leading-relaxed">
              <span class="text-blue-600 text-4xl leading-none font-serif mr-1">"</span>
              Realiving turned our empty unit into a beautiful, functional space. Their team was professional and creative from start to finish. We couldn't be happier with how our dream home has come to life!
              <span class="text-blue-600 text-4xl leading-none font-serif ml-1">"</span>
            </p>

            <!-- Project Type -->
            <div class="mb-6">
              <span class="bg-blue-50 text-blue-700 text-xs font-medium px-3 py-1 rounded-full">Residential Project</span>
            </div>

            <!-- Client Info -->
            <div class="flex items-center gap-4">
              <div class="relative">
                <div class="w-16 h-16 rounded-full overflow-hidden border-2 border-sky-100 shadow-md">
                  <img src="../logo/nh.png" alt="Janelle M." class="w-full h-full object-cover">
                </div>
                <div class="absolute -bottom-1 -right-1 bg-green-500 text-white h-6 w-6 flex items-center justify-center rounded-full border-2 border-white">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                </div>
              </div>
              <div>
                <p class="text-xl font-semibold text-gray-900">Janelle M.</p>
                <p class="text-sm text-gray-500 flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                  </svg>
                  Homeowner
                </p>
              </div>
            </div>
          </div>

          <!-- Decorative Pattern -->
          <div class="absolute -bottom-2 -right-2 w-full h-full bg-gradient-to-br from-blue-400 to-sky-300 rounded-2xl z-0 opacity-0 group-hover:opacity-20 transition-all duration-300"></div>
        </div>

        <!-- Testimonial 2 -->
        <div class="relative group">
          <!-- Card -->
          <div class="bg-white p-8 md:p-10 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 border border-sky-50 relative z-10 h-full">
            <!-- Quote Icon -->
            <div class="absolute -top-5 -left-5 bg-yellow-500 text-white h-10 w-10 rounded-full flex items-center justify-center shadow-lg">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
              </svg>
            </div>

            <!-- Rating Stars -->
            <div class="flex mb-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
            </div>

            <!-- Testimonial Text -->
            <p class="text-gray-700 text-lg mb-8 leading-relaxed">
              <span class="text-blue-600 text-4xl leading-none font-serif mr-1">"</span>
              Excellent service and amazing results. Our office renovation was seamless and exceeded expectations. The team was attentive to our needs and delivered our vision within budget and on schedule.
              <span class="text-blue-600 text-4xl leading-none font-serif ml-1">"</span>
            </p>

            <!-- Project Type -->
            <div class="mb-6">
              <span class="bg-yellow-50 text-yellow-700 text-xs font-medium px-3 py-1 rounded-full">Commercial Project</span>
            </div>

            <!-- Client Info -->
            <div class="flex items-center gap-4">
              <div class="relative">
                <div class="w-16 h-16 rounded-full overflow-hidden border-2 border-sky-100 shadow-md">
                  <img src="../logo/nh.png" alt="Carlo D." class="w-full h-full object-cover">
                </div>
                <div class="absolute -bottom-1 -right-1 bg-green-500 text-white h-6 w-6 flex items-center justify-center rounded-full border-2 border-white">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                </div>
              </div>
              <div>
                <p class="text-xl font-semibold text-gray-900">Carlo D.</p>
                <p class="text-sm text-gray-500 flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                  </svg>
                  Business Owner
                </p>
              </div>
            </div>
          </div>

          <!-- Decorative Pattern -->
          <div class="absolute -bottom-2 -right-2 w-full h-full bg-gradient-to-br from-yellow-400 to-yellow-300 rounded-2xl z-0 opacity-0 group-hover:opacity-20 transition-all duration-300"></div>
        </div>
      </div>

      <!-- Call To Action -->
      <div class="mt-16 text-center">
        <a href="#" class="group inline-flex items-center px-6 py-3 bg-white text-sky-600 font-medium rounded-full border border-sky-200 shadow-sm hover:bg-sky-50 transition-all duration-300">
          <span>Scroll To Top</span>
        </a>
      </div>
    </div>
  </section>

  <section class="py-24 bg-cover bg-fixed bg-center relative" data-aos="fade-up" style="background-image: url('../uploads/home.jpg');">
    <!-- Enhanced Overlay with Gradient -->
    <div class="absolute inset-0 bg-gradient-to-b from-black/80 via-yellow/75 to-yellow/90 z-0"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 md:px-10">
      <!-- Section Header with Enhanced Typography -->
      <div class="text-center mb-16">
        <span class="inline-block text-yellow-400 text-sm font-medium tracking-widest uppercase mb-3">Stay Updated</span>
        <h2 class="text-4xl md:text-5xl font-bold text-white mb-6 leading-tight">Latest <span class="text-yellow-400">News</span></h2>
        <div class="w-24 h-px mx-auto relative">
          <div class="absolute w-full h-px bg-gradient-to-r from-transparent via-yellow-400 to-transparent"></div>
          <div class="absolute w-16 h-px bg-yellow-400 left-1/2 transform -translate-x-1/2 -translate-y-1 opacity-75"></div>
          <div class="absolute w-8 h-px bg-yellow-400 left-1/2 transform -translate-x-1/2 translate-y-1 opacity-50"></div>
        </div>
      </div>

      <!-- News Carousel with Navigation Controls -->
      <div class="relative">
        <!-- Carousel Navigation -->
        <div class="absolute -left-4 md:-left-8 top-1/2 transform -translate-y-1/2 z-20">
          <button id="prevBtn" class="w-12 h-12 rounded-full bg-black/50 backdrop-blur-sm text-white flex items-center justify-center border border-white/20 hover:bg-yellow-500 transition duration-300 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
          </button>
        </div>

        <div class="absolute -right-4 md:-right-8 top-1/2 transform -translate-y-1/2 z-20">
          <button id="nextBtn" class="w-12 h-12 rounded-full bg-black/50 backdrop-blur-sm text-white flex items-center justify-center border border-white/20 hover:bg-yellow-500 transition duration-300 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
          </button>
        </div>

        <!-- News Container with Glass Morphism Cards -->
        <div class="overflow-hidden px-4" id="newsContainer">
          <div id="sliderTrack" class="flex gap-6 transition-all duration-500 ease-in-out w-max">
            <?php
            $news = [];
            while ($row = $result->fetch_assoc()) {
              $news[] = $row;
            }

            // duplicate news list for smooth looping
            $loopNews = array_merge($news);

            foreach ($loopNews as $row):
            ?>
              <div class="min-w-[320px] max-w-sm bg-white/10 backdrop-blur-md rounded-lg overflow-hidden shadow-xl hover:shadow-2xl transition duration-300 flex-shrink-0 group border border-white/10">
                <!-- Image with Hover Effect -->
                <div class="relative overflow-hidden h-52">
                  <img src="<?= '../uploads/' . $row['image'] ?>" alt="<?= htmlspecialchars($row['title']) ?>" class="w-full h-full object-cover transition duration-700 ease-in-out group-hover:scale-110">
                  <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition duration-300"></div>
                </div>

                <!-- Content with Better Typography -->
                <div class="p-6 relative">
                  <!-- Date Badge -->

                  <h3 class="text-xl font-semibold text-white mb-3 group-hover:text-yellow-400 transition duration-300"><?= htmlspecialchars($row['title']) ?></h3>
                  <p class="text-sm text-gray-300 mb-6 line-clamp-3"><?= htmlspecialchars($row['summary']) ?></p>

                  <!-- Button with Animation -->
                  <a href="<?= $row['link'] ?>" class="inline-flex items-center text-yellow-400 group-hover:text-white transition duration-300">
                    <span class="mr-2 font-medium">Read More</span>
                    <svg class="w-5 h-5 transform group-hover:translate-x-1 transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                  </a>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <!-- Pagination Dots -->
      <div class="flex justify-center mt-10 space-x-2" id="paginationDots">
        <?php for ($i = 0; $i < count($news); $i++): ?>
          <button class="w-2 h-2 rounded-full bg-white/30 hover:bg-yellow-400 transition-all duration-300 pagination-dot <?= $i === 0 ? 'w-6 bg-yellow-400' : '' ?>" data-index="<?= $i ?>"></button>
        <?php endfor; ?>
      </div>
    </div>

  </section>

  <!-- Footer Section -->
  <footer class="bg-sky-900 text-white">
    <div class="container mx-auto px-4 py-8">
      <!-- Main Footer Content -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 pb-8">
        <!-- Company Info -->
        <div>
          <div class="mb-6">
            <div class="flex justify-center mb-8">
              <img src="../logo/mmone.png" alt="Your Image" class="w-[80%] max-w-[800px] h-auto ">
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
    document.addEventListener('DOMContentLoaded', function() {
      const sliderTrack = document.getElementById('sliderTrack');
      const prevBtn = document.getElementById('prevBtn');
      const nextBtn = document.getElementById('nextBtn');
      const paginationDots = document.querySelectorAll('.pagination-dot');
      const cardWidth = 340; // Card width + gap
      let currentIndex = 0;
      const totalItems = <?= count($news) ?>;

      // Update slider position
      function updateSlider() {
        sliderTrack.style.transform = `translateX(-${currentIndex * cardWidth}px)`;

        // Update pagination dots
        paginationDots.forEach((dot, index) => {
          if (index === currentIndex) {
            dot.classList.add('w-6', 'bg-yellow-400');
            dot.classList.remove('bg-white/30', 'w-2');
          } else {
            dot.classList.remove('w-6', 'bg-yellow-400');
            dot.classList.add('bg-white/30', 'w-2');
          }
        });
      }

      // Next slide
      nextBtn.addEventListener('click', () => {
        currentIndex = (currentIndex + 1) % totalItems;
        updateSlider();
      });

      // Previous slide
      prevBtn.addEventListener('click', () => {
        currentIndex = (currentIndex - 1 + totalItems) % totalItems;
        updateSlider();
      });

      // Pagination dot click
      paginationDots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
          currentIndex = index;
          updateSlider();
        });
      });

      // Auto slide every 5 seconds
      setInterval(() => {
        currentIndex = (currentIndex + 1) % totalItems;
        updateSlider();
      }, 5000);
    });


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