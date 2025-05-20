<?php
include 'htmldesign/mainhead.php';
include 'htmldesign/top.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Realiving Design Center</title>
  <link rel="icon" type="image/png" href="./images/favicon.png">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- AOS CSS -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
</head>

<body class="pt-12 text-gray-800">

  <!-- Sub Header -->
  <section class="relative bg-cover bg-center h-48 flex items-center justify-center text-white" style="background-image: url('images/background-image.jpg');">
    <div class="absolute inset-0 bg-black bg-opacity-40"></div>
    <h1 class="relative text-4xl italic font-normal text-center drop-shadow-lg" data-aos="fade-up">About Us</h1>
  </section>

  <!-- About Section -->
  <section class="py-16 px-8 bg-white font-montserrat">
    <div class="max-w-6xl mx-auto flex flex-wrap justify-between gap-8">
      <div class="flex-1 min-w-[280px] bg-white shadow-lg p-8 rounded-lg hover:-translate-y-1 transition-transform" data-aos="fade-up">
        <h2 class="text-2xl text-sky-500 text-center font-playfair">Our Vision</h2>
        <div class="w-12 h-1 bg-yellow-400 mx-auto my-4 rounded-full"></div>
        <p class="text-justify leading-relaxed">To be the elite provider of interiors and to be the forefront of the special architectural industry in the Philippines.</p>
      </div>

      <div class="flex-1 min-w-[280px] bg-white shadow-lg p-8 rounded-lg hover:-translate-y-1 transition-transform" data-aos="fade-up">
        <h2 class="text-2xl text-sky-500 text-center font-playfair">Our Mission</h2>
        <div class="w-12 h-1 bg-yellow-400 mx-auto my-4 rounded-full"></div>
        <p class="text-justify leading-relaxed">To provide customized and sustainable modular cabinet solutions, utilizing cutting-edge technology, skilled craftsmanship, and a customer-centric approach, while ensuring timely delivery and exceeding client expectations.</p>
      </div>
    </div>
  </section>

  <!-- Core Values -->
  <section class="py-16 font-montserrat bg-cover bg-center bg-no-repeat relative" style="background-image: url('images/background-image2.jpg');">
    <div class="absolute inset-0 bg-black bg-opacity-40"></div>

    <div class="relative max-w-6xl mx-auto text-center text-white z-10">
      <h2 class="text-3xl text-sky-300 font-playfair mb-10" data-aos="fade-up">Our Core Values</h2>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
        <div class="bg-white text-gray-900 shadow-lg rounded-lg p-8" data-aos="fade-up">
          <img src="./images/excellence-icon.png" alt="Excellence" class="mx-auto mb-4 w-16">
          <h3 class="text-xl font-semibold mb-2">Excellence</h3>
          <p>We strive for excellence in every aspect of our work, from design conception to project completion.</p>
        </div>
        <div class="bg-white text-gray-900 shadow-lg rounded-lg p-8" data-aos="fade-up">
          <img src="./images/innovation-icon.png" alt="Innovation" class="mx-auto mb-4 w-16">
          <h3 class="text-xl font-semibold mb-2">Innovation</h3>
          <p>We embrace innovation and continuously explore new techniques and materials to enhance our products and services.</p>
        </div>
        <div class="bg-white text-gray-900 shadow-lg rounded-lg p-8" data-aos="fade-up">
          <img src="./images/collaboration-icon.png" alt="Collaboration" class="mx-auto mb-4 w-16">
          <h3 class="text-xl font-semibold mb-2">Collaboration</h3>
          <p>We believe in the power of collaboration and aim to build strong partnerships with industry professionals.</p>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mt-10">
        <div class="bg-white text-gray-900 shadow-lg rounded-lg p-8" data-aos="fade-up">
          <img src="./images/integrity-icon.png" alt="Integrity" class="mx-auto mb-4 w-16">
          <h3 class="text-xl font-semibold mb-2">Integrity</h3>
          <p>We conduct our business with integrity, transparency, and ethical practices.</p>
        </div>
        <div class="bg-white text-gray-900 shadow-lg rounded-lg p-8" data-aos="fade-up">
          <img src="./images/satisfaction-icon.png" alt="Customer Satisfaction" class="mx-auto mb-4 w-16">
          <h3 class="text-xl font-semibold mb-2">Customer Satisfaction</h3>
          <p>We prioritize customer satisfaction and work closely with clients to understand their unique needs and deliver tailored solutions.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Quote Section -->
  <section class="py-16 bg-yellow-100">
    <blockquote class="text-2xl text-center italic font-playfair max-w-2xl mx-auto" data-aos="fade-up">
      “Making your dream space a reality.”
    </blockquote>
  </section>

  <!-- Company Section -->
  <section class="py-16 px-8 bg-white font-montserrat">
    <div class="max-w-6xl mx-auto flex flex-wrap gap-8 items-center">
      <div class="flex-1 min-w-[280px]" data-aos="fade-up">
        <h2 class="text-3xl text-sky-500 font-playfair mb-6">Our Company</h2>
        <p class="text-justify leading-relaxed mb-6">
          Realiving Design Center Corp. is a leading architectural fit-out company specializing in design, manufacturing, and installation of premium modular cabinets. We are driven by a passion for creating beautifully functional spaces that enhance the lives of our clients.
        </p>
        <p class="text-justify leading-relaxed">
          Realiving was duly incorporated under the trade name Brava Homes situated at Warehouse 5, Sunhope Compound, Purok 7, Brgy. Calasag, San Ildefonso, Bulacan. It primarily engages in wholesale and retail of competitively priced, high-quality construction, plumbing, and decorative materials.
        </p>
      </div>
      <div class="flex-1 min-w-[280px]" data-aos="fade-up">
        <img src="./images/background-image.jpg" alt="Company Image" class="rounded-lg shadow-lg">
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



  <!-- AOS Scripts -->
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init({
      once: false,
      mirror: true,
      duration: 1000,
      anchorPlacement: 'top-bottom'
    });
  </script>
</body>

</html>