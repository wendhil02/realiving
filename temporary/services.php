<?php
include 'htmldesign/mainhead.php';
include 'htmldesign/top.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modern Services Section</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    .montserrat {
      font-family: 'Montserrat', sans-serif;
    }

    .poppins {
      font-family: 'Poppins', sans-serif;
    }

    .service-card {
      transition: all 0.4s ease;
    }

    .service-card:hover {
      transform: translateY(-10px);
    }

    .image-container {
      overflow: hidden;
      border-radius: 16px;
    }

    .service-image {
      transition: transform 0.7s ease;
    }

    .service-card:hover .service-image {
      transform: scale(1.08);
    }

    .timeline-dot {
      animation: pulse 2s infinite;
    }

    .timeline-line {
      background: linear-gradient(to bottom, #F59E0B, #D97706);
    }

    @keyframes pulse {
      0% {
        box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7);
      }

      70% {
        box-shadow: 0 0 0 10px rgba(245, 158, 11, 0);
      }

      100% {
        box-shadow: 0 0 0 0 rgba(245, 158, 11, 0);
      }
    }

    .service-number {
      transition: all 0.3s ease;
    }

    .service-card:hover .service-number {
      transform: rotate(360deg);
      background-color: #D97706;
    }

    .bg-yellow-gradient {
      background: linear-gradient(135deg, #F59E0B, #D97706);
    }

    .fade-up {
      opacity: 0;
      transform: translateY(30px);
      transition: opacity 0.8s ease, transform 0.8s ease;
    }

    .fade-up.visible {
      opacity: 1;
      transform: translateY(0);
    }

    .service-card {
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .btn-primary {
      background: linear-gradient(135deg, #F59E0B, #D97706);
      transition: all 0.3s ease;
    }

    .btn-primary:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 20px -5px rgba(245, 158, 11, 0.4);
    }

    .decoration-circle {
      filter: blur(40px);
      animation: float 8s ease-in-out infinite;
    }

    @keyframes float {

      0%,
      100% {
        transform: translateY(0) scale(1);
      }

      50% {
        transform: translateY(-20px) scale(1.05);
      }
    }
  </style>
</head>

<body class="font-sans antialiased">
  <section id="services" class="py-24 relative overflow-hidden bg-gradient-to-b from-gray-50 to-gray-200">
    <!-- Decorative Background Elements -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0">
      <div class="decoration-circle absolute -top-40 -left-40 w-96 h-96 bg-yellow-400 rounded-full opacity-10"></div>
      <div class="decoration-circle absolute top-1/3 -right-32 w-80 h-80 bg-yellow-400 rounded-full opacity-10"></div>
      <div class="decoration-circle absolute -bottom-20 left-1/4 w-64 h-64 bg-yellow-400 rounded-full opacity-10"></div>
    </div>

    <div class="container mx-auto px-4 relative z-10 max-w-6xl">
      <!-- Section Header -->
      <div class="text-center mb-20 fade-up">
        <span class="inline-block py-1 px-3 bg-yellow-100 text-yellow-600 rounded-full text-sm font-semibold mb-3">OUR PROCESS</span>
        <h2 class="text-4xl md:text-5xl font-bold text-gray-900 montserrat relative inline-block">
          Our Services
          <div class="absolute -bottom-3 left-1/2 transform -translate-x-1/2 w-24 h-1 bg-yellow-gradient rounded-full"></div>
        </h2>
        <p class="mt-6 text-gray-600 max-w-2xl mx-auto poppins leading-relaxed">
          Transforming your space with custom design, quality fabrication, reliable delivery, and expert installation.
        </p>
      </div>

      <!-- Services Timeline -->
      <div class="relative">
        <!-- Vertical Timeline Line -->
        <div class="absolute left-1/2 transform -translate-x-1/2 h-full w-1 timeline-line rounded-full"></div>

        <!-- DESIGN -->
        <div class="relative flex flex-col md:flex-row items-center mb-28 group fade-up service-card">
          <!-- Text Content -->
          <div class="md:w-1/2 md:pr-16 z-10 order-2 md:order-1 mt-10 md:mt-0">
            <div class="bg-white rounded-2xl shadow-xl p-8 border-t-4 border-yellow-500 transform transition-all duration-300 hover:shadow-2xl max-w-md ml-auto">
              <div class="flex items-center mb-4">
                <div class="bg-yellow-100 p-3 rounded-full mr-4">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                  </svg>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 montserrat">DESIGN</h3>
              </div>
              <p class="text-gray-700 mt-4 leading-relaxed poppins">
                We create smart, space-saving, and stylish designs tailored to your specific space and lifestyle needs. Our expert designers work closely with you to bring your vision to life, ensuring every detail matches your expectations.
              </p>
              <a href="#design" class="inline-flex items-center mt-6 px-6 py-3 btn-primary text-white font-medium rounded-lg">
                Read More
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </a>
            </div>
          </div>

          <!-- Animated Circle -->
          <div class="absolute left-1/2 transform -translate-x-1/2 w-16 h-16 flex items-center justify-center z-20">
            <div class="w-16 h-16 bg-yellow-500 rounded-full flex items-center justify-center shadow-lg timeline-dot">
              <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center service-number">
                <span class="text-yellow-500 font-bold montserrat text-xl">01</span>
              </div>
            </div>
          </div>

          <!-- Image -->
          <div class="md:w-1/2 md:pl-16 relative order-1 md:order-2 image-container ">
            <div class="overflow-hidden rounded-2xl relative h-80">
              <!-- Image -->
              <img src="../logo/real.png" class="w-full h-full object-cover service-image" alt="Design">

              <!-- Text content -->
              <div class="absolute inset-0 flex flex-col justify-end p-8 z-20">
                <h3 class="text-white text-3xl font-semibold montserrat">DESIGN</h3>
                <p class="text-yellow-300 mt-2 font-medium">Turning ideas into plans</p>
              </div>
            </div>
          </div>

        </div>

        <!-- FABRICATE -->
        <div class="relative flex flex-col md:flex-row items-center mb-28 group fade-up service-card">
          <!-- Image -->
          <div class="md:w-1/2 md:pr-16 relative order-1 image-container shadow-xl">
            <div class="overflow-hidden rounded-2xl relative h-80">
              <img src="../logo/nh.jpg" class="w-full h-full object-cover service-image" alt="Fabricate">
              <div class="absolute inset-0 flex flex-col justify-end p-8">
                <h3 class="text-white text-3xl font-semibold montserrat">FABRICATE</h3>
                <p class="text-yellow-300 mt-2 font-medium">Crafting with precision</p>
              </div>
            </div>
          </div>

          <!-- Animated Circle -->
          <div class="absolute left-1/2 transform -translate-x-1/2 w-16 h-16 flex items-center justify-center z-20">
            <div class="w-16 h-16 bg-yellow-500 rounded-full flex items-center justify-center shadow-lg timeline-dot">
              <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center service-number">
                <span class="text-yellow-500 font-bold montserrat text-xl">02</span>
              </div>
            </div>
          </div>

          <!-- Text Content -->
          <div class="md:w-1/2 md:pl-16 z-10 order-2 mt-10 md:mt-0">
            <div class="bg-white rounded-2xl shadow-xl p-8 border-t-4 border-yellow-500 transform transition-all duration-300 hover:shadow-2xl max-w-md">
              <div class="flex items-center mb-4">
                <div class="bg-yellow-100 p-3 rounded-full mr-4">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                  </svg>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 montserrat">FABRICATE</h3>
              </div>
              <p class="text-gray-700 mt-4 leading-relaxed poppins">
                Using only the highest quality materials, we build each piece with precision and care to ensure durability, functionality, and a modern finish that lasts for years. Our craftsmen take pride in every detail.
              </p>
              <a href="#fabricate" class="inline-flex items-center mt-6 px-6 py-3 btn-primary text-white font-medium rounded-lg">
                Read More
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </a>
            </div>
          </div>
        </div>

        <!-- DELIVERED -->
        <div class="relative flex flex-col md:flex-row items-center mb-28 group fade-up service-card">
          <!-- Text Content -->
          <div class="md:w-1/2 md:pr-16 z-10 order-2 md:order-1 mt-10 md:mt-0">
            <div class="bg-white rounded-2xl shadow-xl p-8 border-t-4 border-yellow-500 transform transition-all duration-300 hover:shadow-2xl max-w-md ml-auto">
              <div class="flex items-center mb-4">
                <div class="bg-yellow-100 p-3 rounded-full mr-4">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                  </svg>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 montserrat">DELIVERED</h3>
              </div>
              <p class="text-gray-700 mt-4 leading-relaxed poppins">
                We transport your furniture safely and on time—straight to your doorstep. Our delivery team handles your custom pieces with care to ensure they arrive in perfect condition, ready for installation.
              </p>
              <a href="#delivered" class="inline-flex items-center mt-6 px-6 py-3 btn-primary text-white font-medium rounded-lg">
                Read More
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </a>
            </div>
          </div>

          <!-- Animated Circle -->
          <div class="absolute left-1/2 transform -translate-x-1/2 w-16 h-16 flex items-center justify-center z-20">
            <div class="w-16 h-16 bg-yellow-500 rounded-full flex items-center justify-center shadow-lg timeline-dot">
              <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center service-number">
                <span class="text-yellow-500 font-bold montserrat text-xl">03</span>
              </div>
            </div>
          </div>

          <!-- Image -->
          <div class="md:w-1/2 md:pl-16 relative order-1 md:order-2 image-container shadow-xl">
            <div class="overflow-hidden rounded-2xl relative h-80">
              <img src="../logo/deli.png" class="w-full h-full object-cover service-image" alt="Delivered">
              <div class="absolute inset-0  flex flex-col justify-end p-8">
                <h3 class="text-white text-3xl font-semibold montserrat">DELIVERED</h3>
                <p class="text-yellow-300 mt-2 font-medium">On time, every time</p>
              </div>
            </div>
          </div>
        </div>

        <!-- INSTALLATION -->
        <div class="relative flex flex-col md:flex-row items-center fade-up service-card">
          <!-- Image -->
          <div class="md:w-1/2 md:pr-16 relative order-1 image-container shadow-xl">
            <div class="overflow-hidden rounded-2xl relative h-80">
              <img src="../logo/insta.png" class="w-full h-full object-cover service-image" alt="Installation">
              <div class="absolute inset-0 flex flex-col justify-end p-8">
                <h3 class="text-white text-3xl font-semibold montserrat">INSTALLATION</h3>
                <p class="text-yellow-300 mt-2 font-medium">Expert assembly and setup</p>
              </div>
            </div>
          </div>

          <!-- Animated Circle -->
          <div class="absolute left-1/2 transform -translate-x-1/2 w-16 h-16 flex items-center justify-center z-20">
            <div class="w-16 h-16 bg-yellow-500 rounded-full flex items-center justify-center shadow-lg timeline-dot">
              <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center service-number">
                <span class="text-yellow-500 font-bold montserrat text-xl">04</span>
              </div>
            </div>
          </div>

          <!-- Text Content -->
          <div class="md:w-1/2 md:pl-16 z-10 order-2 mt-10 md:mt-0">
            <div class="bg-white rounded-2xl shadow-xl p-8 border-t-4 border-yellow-500 transform transition-all duration-300 hover:shadow-2xl max-w-md">
              <div class="flex items-center mb-4">
                <div class="bg-yellow-100 p-3 rounded-full mr-4">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 montserrat">INSTALLATION</h3>
              </div>
              <p class="text-gray-700 mt-4 leading-relaxed poppins">
                Our team of professionals handles the setup efficiently, making sure everything is perfectly fitted, securely mounted, and ready to use immediately. Your satisfaction is guaranteed.
              </p>
              <a href="#installation" class="inline-flex items-center mt-6 px-6 py-3 btn-primary text-white font-medium rounded-lg">
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

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Fade up animation function
      function checkFadeElements() {
        const fadeElements = document.querySelectorAll('.fade-up');

        fadeElements.forEach(element => {
          const elementTop = element.getBoundingClientRect().top;
          const elementVisible = 150;

          if (elementTop < window.innerHeight - elementVisible) {
            element.classList.add('visible');
          }
        });
      }

      // Initial check
      checkFadeElements();

      // On scroll check
      window.addEventListener('scroll', checkFadeElements);

      // Smooth scroll for anchor links
      document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
          e.preventDefault();

          const targetId = this.getAttribute('href');
          if (targetId === '#') return;

          const targetElement = document.querySelector(targetId);
          if (targetElement) {
            window.scrollTo({
              top: targetElement.offsetTop - 100,
              behavior: 'smooth'
            });
          }
        });
      });
    });
  </script>
</body>

</html>