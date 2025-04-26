<header class="fixed top-0 left-0 w-full h-16 px-6 md:px-12 flex items-center justify-between bg-white border-t-4 border-yellow-500 shadow-md z-50">
    <!-- Logo -->
    <div class="flex items-center gap-2">
      <img src="./images/logo.png" alt="Logo" class="h-9">

    </div>

    <!-- Navigation -->
    <nav class="flex items-center gap-6 font-montserrat text-sm md:text-base">
      <a href="index.php" class="text-gray-900 hover:text-yellow-500 transition duration-200 relative after:block after:h-0.5 after:bg-yellow-500 after:scale-x-0 hover:after:scale-x-100 after:origin-left after:transition-transform">Home</a>

      <a href="about.php" class="text-gray-900 hover:text-yellow-500 transition duration-200 relative after:block after:h-0.5 after:bg-yellow-500 after:scale-x-0 hover:after:scale-x-100 after:origin-left after:transition-transform">About</a>

      <!-- Projects Dropdown -->
      <div class="relative group">
        <a href="#" class="text-gray-900 hover:text-yellow-500 transition duration-200 relative after:block after:h-0.5 after:bg-yellow-500 after:scale-x-0 hover:after:scale-x-100 after:origin-left after:transition-transform cursor-pointer flex items-center gap-1">
          Projects <span class="text-xs">▾</span>
        </a>
        <div class="hidden group-hover:block absolute top-full left-0 mt-2 w-72 bg-white shadow-2xl rounded-lg z-50 p-2 max-h-72 overflow-y-auto border border-gray-200">
          <a href="#" class="block px-4 py-2 text-gray-900 hover:bg-yellow-100 rounded-md text-sm transition">Alphaland Baguio Mountain Lodge</a>
          <a href="#" class="block px-4 py-2 text-gray-900 hover:bg-yellow-100 rounded-md text-sm transition">Best Western Hotel</a>
          <a href="#" class="block px-4 py-2 text-gray-900 hover:bg-yellow-100 rounded-md text-sm transition">The Bellevue Manila</a>
          <a href="#" class="block px-4 py-2 text-gray-900 hover:bg-yellow-100 rounded-md text-sm transition">Megaworld</a>
        </div>
      </div>

      <a href="#" class="text-gray-900 hover:text-yellow-500 transition duration-200 relative after:block after:h-0.5 after:bg-yellow-500 after:scale-x-0 hover:after:scale-x-100 after:origin-left after:transition-transform">News</a>

      <a href="contact.php" class="text-gray-900 hover:text-yellow-500 transition duration-200 relative after:block after:h-0.5 after:bg-yellow-500 after:scale-x-0 hover:after:scale-x-100 after:origin-left after:transition-transform">Contact</a>
    </nav>
  </header>