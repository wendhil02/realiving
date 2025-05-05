<header class="fixed top-0 left-0 w-full h-16 px-6 md:px-12 flex items-center justify-between bg-white border-t-4 border-yellow-500 shadow-md z-50">
  <!-- Logo -->
  <div class="flex items-center gap-2">
    <img src="images/logo.png" alt="Logo" class="h-9">
  </div>

  <!-- Mobile Toggle Button -->
  <button id="navToggle" class="md:hidden text-gray-700 focus:outline-none">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M4 6h16M4 12h16M4 18h16" />
    </svg>
  </button>

  <!-- Navigation -->
  <nav id="mainNav" class="hidden md:flex absolute md:static top-full left-0 w-full md:w-auto bg-white md:bg-transparent md:shadow-none shadow-lg md:flex-row flex-col items-start md:items-center gap-6 font-montserrat text-sm md:text-base px-6 md:px-0 py-4 md:py-0">
    <a href="index.php" class="text-gray-900 hover:text-yellow-500 transition duration-200 relative after:block after:h-0.5 after:bg-yellow-500 after:scale-x-0 hover:after:scale-x-100 after:origin-left after:transition-transform">Home</a>

    <a href="about.php" class="text-gray-900 hover:text-yellow-500 transition duration-200 relative after:block after:h-0.5 after:bg-yellow-500 after:scale-x-0 hover:after:scale-x-100 after:origin-left after:transition-transform">About</a>
    <a href="services.php" class="text-gray-900 hover:text-yellow-500 transition duration-200 relative after:block after:h-0.5 after:bg-yellow-500 after:scale-x-0 hover:after:scale-x-100 after:origin-left after:transition-transform">Services</a>
    <!-- Projects Dropdown (clickable) -->
    <div class="relative">
      <button onclick="toggleDropdown('projectsDropdown')" class="text-gray-900 hover:text-yellow-500 transition duration-200 relative after:block after:h-0.5 after:bg-yellow-500 after:scale-x-0 focus:after:scale-x-100 after:origin-left after:transition-transform cursor-pointer flex items-center gap-1">
        Projects <span class="text-xs">▾</span>
      </button>
      <div id="projectsDropdown" class="hidden absolute top-full left-0 mt-2 w-72 bg-white shadow-2xl rounded-lg z-50 p-2 max-h-72 overflow-y-auto border border-gray-200">
        <a href="allofproject.php" class="block px-4 py-2 text-gray-900 hover:bg-yellow-100 rounded-md text-sm transition">Accomplish Project</a>
      </div>
    </div>

    <a href="#" class="text-gray-900 hover:text-yellow-500 transition duration-200 relative after:block after:h-0.5 after:bg-yellow-500 after:scale-x-0 hover:after:scale-x-100 after:origin-left after:transition-transform">News</a>

    <a href="contact.php" class="text-gray-900 hover:text-yellow-500 transition duration-200 relative after:block after:h-0.5 after:bg-yellow-500 after:scale-x-0 hover:after:scale-x-100 after:origin-left after:transition-transform">Contact</a>

    <!-- Portal Dropdown (clickable) -->
    <div class="relative">
      <button onclick="toggleDropdown('portalDropdown')" class="text-gray-900 hover:text-yellow-500 transition duration-200 relative after:block after:h-0.5 after:bg-yellow-500 after:scale-x-0 focus:after:scale-x-100 after:origin-left after:transition-transform cursor-pointer flex items-center gap-1">
        Portal <span class="text-xs">▾</span>
      </button>
      <div id="portalDropdown" class="hidden absolute top-full left-0 mt-2 w-72 bg-white shadow-2xl rounded-lg z-50 p-2 max-h-72 overflow-y-auto border border-gray-200">
        <a href="../index.php" class="block px-4 py-2 text-gray-900 hover:bg-yellow-100 rounded-md text-sm transition">Return</a>
      </div>
    </div>
  </nav>
</header>



<script>
  // Mobile menu toggle
  document.getElementById('navToggle').addEventListener('click', function () {
    const nav = document.getElementById('mainNav');
    nav.classList.toggle('hidden');
  });

  // Dropdown toggles
  function toggleDropdown(id) {
    const dropdown = document.getElementById(id);
    dropdown.classList.toggle('hidden');
  }

  // Optional: close dropdowns on outside click
  document.addEventListener('click', function (e) {
    ['projectsDropdown', 'portalDropdown'].forEach(id => {
      const trigger = document.querySelector(`button[onclick="toggleDropdown('${id}')"]`);
      const dropdown = document.getElementById(id);
      if (!dropdown.contains(e.target) && !trigger.contains(e.target)) {
        dropdown.classList.add('hidden');
      }
    });
  });
</script>


