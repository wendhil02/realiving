<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Realiving Design Center Corporation</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />
</head>
<body class="font-sans text-gray-800">

  <!-- Hero Section -->
  <section class="h-screen bg-cover bg-center flex items-center justify-center text-white text-center" style="background-image: url('images/hero-bg.jpg');">
    <div class="bg-black bg-opacity-60 p-10 rounded" data-aos="fade-up">
      <h1 class="text-4xl md:text-6xl font-bold mb-4">Inspired Spaces. Crafted with Passion.</h1>
      <p class="text-lg md:text-xl mb-6">We turn your vision into reality through creative design and reliable construction.</p>
      <a href="#projects" class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded">See Our Work</a>
    </div>
  </section>

  <!-- About Us -->
  <section class="py-20 px-6 md:px-20 bg-white" data-aos="fade-up">
    <h2 class="text-3xl font-bold mb-6 text-center">Who We Are</h2>
    <p class="text-lg text-center max-w-4xl mx-auto">Realiving Design Center Corporation is a full-service design and build company that specializes in creating elegant, functional spaces tailored to your lifestyle or business needs. Since 2015, we've been delivering stunning interiors, custom renovations, and innovative architecture.</p>
  </section>

  <!-- Services -->
  <section class="py-20 bg-gray-50 px-6 md:px-20" data-aos="fade-up">
    <h2 class="text-3xl font-bold text-center mb-10">Our Services</h2>
    <div class="grid md:grid-cols-3 gap-8">
      <div class="bg-white p-6 rounded shadow hover:shadow-lg transition">
        <h3 class="text-xl font-semibold mb-2">Interior Design</h3>
        <p>Create stylish and functional living or working spaces personalized to your taste.</p>
      </div>
      <div class="bg-white p-6 rounded shadow hover:shadow-lg transition">
        <h3 class="text-xl font-semibold mb-2">Construction & Renovation</h3>
        <p>From ground-up builds to full renovations, we bring structures to life with quality and care.</p>
      </div>
      <div class="bg-white p-6 rounded shadow hover:shadow-lg transition">
        <h3 class="text-xl font-semibold mb-2">Architectural Planning</h3>
        <p>Blueprints and plans that meet both form and function — with compliance and creativity.</p>
      </div>
    </div>
  </section>

  <!-- Featured Projects -->
  <section id="projects" class="py-20 px-6 md:px-20" data-aos="fade-up">
    <h2 class="text-3xl font-bold text-center mb-10">Featured Projects</h2>
    <div class="grid md:grid-cols-3 gap-6">
      <div class="bg-white rounded shadow-md overflow-hidden">
        <img src="images/project1.jpg" alt="Project 1" class="w-full h-48 object-cover">
        <div class="p-4">
          <h3 class="font-semibold text-lg">Modern Condo Interior</h3>
          <p class="text-sm text-gray-600">Makati City</p>
        </div>
      </div>
      <div class="bg-white rounded shadow-md overflow-hidden">
        <img src="images/project2.jpg" alt="Project 2" class="w-full h-48 object-cover">
        <div class="p-4">
          <h3 class="font-semibold text-lg">Office Renovation</h3>
          <p class="text-sm text-gray-600">Bonifacio Global City</p>
        </div>
      </div>
      <div class="bg-white rounded shadow-md overflow-hidden">
        <img src="images/project3.jpg" alt="Project 3" class="w-full h-48 object-cover">
        <div class="p-4">
          <h3 class="font-semibold text-lg">Custom Kitchen Design</h3>
          <p class="text-sm text-gray-600">Quezon City</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Testimonials -->
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

  <!-- Contact CTA -->
  <section class="py-20 px-6 md:px-20 bg-yellow-500 text-white text-center" data-aos="fade-up">
    <h2 class="text-3xl font-bold mb-4">Ready to Build Your Dream Space?</h2>
    <p class="mb-6 text-lg">Contact us today to schedule a consultation with our experts.</p>
    <a href="contact.php" class="bg-white text-yellow-600 px-6 py-3 rounded font-semibold hover:bg-gray-100">Contact Us</a>
  </section>

  <!-- Footer -->
  <footer class="bg-gray-900 text-white py-8 text-center">
    <p>&copy; 2025 Realiving Design Center Corporation. All rights reserved.</p>
    <p class="text-sm mt-2">Designed by Realiving Web Team</p>
  </footer>

  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init();
  </script>
</body>
</html>
