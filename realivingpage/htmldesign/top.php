<header class="fixed top-0 left-0 w-full bg-white z-50 transition-all duration-300">
  <!-- Top accent line with modern gradient -->
  <div class="h-1 w-full bg-gradient-to-r from-blue-500 via-blue-500 to-blue-600"></div>
  
  <div class="container mx-auto px-4">
    <div class="flex items-center justify-between h-20">
      <!-- Logo -->
      <div class="flex items-center">
        <a href="index.php" class="flex items-center">
          <img src="../logo/picart.png" alt="RealLiving Logo" class="h-10 md:h-12">
        </a>
      </div>

      <!-- Mobile Toggle Button (Sleek Hamburger) -->
      <button id="navToggle" class="md:hidden flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 focus:outline-none transition duration-200" aria-label="Toggle navigation">
        <div class="relative w-5 h-4">
          <span id="hamburgerTop" class="absolute top-0 left-0 w-5 h-0.5 bg-indigo-600 transform transition-all duration-300 rounded-full"></span>
          <span id="hamburgerMiddle" class="absolute top-1.5 left-0 w-5 h-0.5 bg-indigo-600 transform transition-all duration-300 rounded-full"></span>
          <span id="hamburgerBottom" class="absolute top-3 left-0 w-5 h-0.5 bg-indigo-600 transform transition-all duration-300 rounded-full"></span>
        </div>
      </button>

      <!-- Main Navigation -->
      <nav id="mainNav" class="hidden md:flex fixed md:static inset-0 top-20 md:top-0 pt-6 md:pt-0 w-full md:w-auto 
                             bg-white md:bg-transparent md:flex-row flex-col items-start md:items-center 
                             pb-8 md:pb-0 z-40 overflow-y-auto md:overflow-visible shadow-xl md:shadow-none
                             max-h-[calc(100vh-5rem)] md:max-h-none">
        <div class="px-6 md:px-0 w-full md:w-auto md:flex md:items-center">
          <!-- Main Nav Items -->
          <ul class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-2 w-full md:w-auto">
            <li class="w-full md:w-auto">
              <a href="mainpage.php" class="main-link group block py-2.5 px-3 md:px-4 rounded-lg hover:bg-indigo-50 md:hover:bg-transparent transition-all duration-200">
                <span class="relative font-medium text-gray-800 md:text-gray-700 group-hover:text-indigo-600 transition-colors duration-200">
                  Our World
                  <span class="md:absolute md:bottom-0 md:left-0 md:w-0 md:h-0.5 md:bg-indigo-500 md:group-hover:w-full md:transition-all md:duration-300"></span>
                </span>
              </a>
            </li>
            
            <!-- Projects Dropdown -->
            <li class="dropdown-container w-full md:w-auto relative">
              <button id="projectsToggle" class="dropdown-toggle group flex items-center justify-between w-full md:w-auto py-2.5 px-3 md:px-4 rounded-lg hover:bg-indigo-50 md:hover:bg-transparent transition-all duration-200">
                <span class="relative font-medium text-gray-800 md:text-gray-700 group-hover:text-indigo-600 transition-colors duration-200">
                  Projects
                  <span class="md:absolute md:bottom-0 md:left-0 md:w-0 md:h-0.5 md:bg-indigo-500 md:group-hover:w-full md:transition-all md:duration-300"></span>
                </span>
                <svg class="ml-1 w-4 h-4 text-gray-500 group-hover:text-indigo-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
              </button>
              
              <div id="projectsDropdown" class="dropdown-menu hidden md:absolute static top-full left-0 mt-1 py-2 px-0 md:p-2
                                              w-full md:w-64 bg-white md:bg-white/95 md:backdrop-blur-sm rounded-lg md:rounded-xl 
                                              shadow-none md:shadow-lg md:border border-gray-100 z-50">
                <a href="allofproject.php" class="block px-3 md:px-4 py-2 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-200">
                  Accomplish Project
                </a>
                <a href="#" class="block px-3 md:px-4 py-2 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-200">
                  Residential Projects
                </a>
                <a href="#" class="block px-3 md:px-4 py-2 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-200">
                  Commercial Projects
                </a>
              </div>
            </li>
          
            <li class="w-full md:w-auto">
              <a href="about.php" class="main-link group block py-2.5 px-3 md:px-4 rounded-lg hover:bg-indigo-50 md:hover:bg-transparent transition-all duration-200">
                <span class="relative font-medium text-gray-800 md:text-gray-700 group-hover:text-indigo-600 transition-colors duration-200">
                  About
                  <span class="md:absolute md:bottom-0 md:left-0 md:w-0 md:h-0.5 md:bg-indigo-500 md:group-hover:w-full md:transition-all md:duration-300"></span>
                </span>
              </a>
            </li>
            
            <li class="w-full md:w-auto">
              <a href="services.php" class="main-link group block py-2.5 px-3 md:px-4 rounded-lg hover:bg-indigo-50 md:hover:bg-transparent transition-all duration-200">
                <span class="relative font-medium text-gray-800 md:text-gray-700 group-hover:text-indigo-600 transition-colors duration-200">
                  Services
                  <span class="md:absolute md:bottom-0 md:left-0 md:w-0 md:h-0.5 md:bg-indigo-500 md:group-hover:w-full md:transition-all md:duration-300"></span>
                </span>  
              </a>
            </li>
            
            <!-- What's New Dropdown -->
            <li class="dropdown-container w-full md:w-auto relative">
              <button id="whatsNewToggle" class="dropdown-toggle group flex items-center justify-between w-full md:w-auto py-2.5 px-3 md:px-4 rounded-lg hover:bg-indigo-50 md:hover:bg-transparent transition-all duration-200">
                <span class="relative font-medium text-gray-800 md:text-gray-700 group-hover:text-indigo-600 transition-colors duration-200">
                  What's New
                  <span class="md:absolute md:bottom-0 md:left-0 md:w-0 md:h-0.5 md:bg-indigo-500 md:group-hover:w-full md:transition-all md:duration-300"></span>
                </span>
                <svg class="ml-1 w-4 h-4 text-gray-500 group-hover:text-indigo-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
              </button>
              
              <div id="galleryDropdown" class="dropdown-menu hidden md:absolute static top-full left-0 mt-1 py-2 px-0 md:p-2
                                              w-full md:w-64 bg-white md:bg-white/95 md:backdrop-blur-sm rounded-lg md:rounded-xl 
                                              shadow-none md:shadow-lg md:border border-gray-100 z-50">
                <a href="photos.php" class="block px-3 md:px-4 py-2 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-200">
                  News
                </a>
                <a href="#" class="block px-3 md:px-4 py-2 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-200">
                  Design Inspirations
                </a>
                <a href="#" class="block px-3 md:px-4 py-2 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-200">
                  Upcoming Events
                </a>
              </div>
            </li>
            
            <li class="w-full md:w-auto">
              <a href="contact.php" class="main-link group block py-2.5 px-3 md:px-4 rounded-lg hover:bg-indigo-50 md:hover:bg-transparent transition-all duration-200">
                <span class="relative font-medium text-gray-800 md:text-gray-700 group-hover:text-indigo-600 transition-colors duration-200">
                  Contact
                  <span class="md:absolute md:bottom-0 md:left-0 md:w-0 md:h-0.5 md:bg-indigo-500 md:group-hover:w-full md:transition-all md:duration-300"></span>
                </span>
              </a>
            </li>
          </ul>
          
          <!-- CTA Button -->
          <div class="mt-6 md:mt-0 md:ml-6">
            <a href="quote.php" class="inline-flex items-center justify-center w-full md:w-auto px-6 py-2.5 rounded-lg bg-gradient-to-r from-yellow-500 to-yellow-600 text-white font-medium shadow hover:shadow-lg hover:from-yellow-600 hover:to-yellow-700 transition-all duration-300 transform hover:-translate-y-0.5">
              <span>Get Quote</span>
              <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
              </svg>
            </a>
          </div>
        </div>
      </nav>
    </div>
  </div>
  
  <!-- Bottom shadow - appears on scroll -->
  <div id="headerShadow" class="absolute bottom-0 left-0 w-full h-6 bg-gradient-to-b from-gray-200/0 to-gray-200/10 pointer-events-none transition-opacity duration-300 opacity-0"></div>
</header>

<!-- Add padding to prevent content from being hidden under fixed navbar -->
<div class="h-20"></div>

<style>
  /* Mobile menu specific styles */
  @media (max-width: 767px) {
    body.menu-open {
      overflow: hidden;
    }
    
    /* Mobile dropdown styling */
    .dropdown-menu {
      border-top: 1px solid #f3f4f6;
      border-bottom: 1px solid #f3f4f6;
      background-color: #f9fafb;
      margin: 8px 0;
    }
    
    /* Hamburger animation */
    body.menu-open #hamburgerTop {
      transform: translateY(7px) rotate(45deg);
    }
    
    body.menu-open #hamburgerMiddle {
      opacity: 0;
    }
    
    body.menu-open #hamburgerBottom {
      transform: translateY(-7px) rotate(-45deg);
    }
  }
  
  /* Dropdown animation */
  .dropdown-menu {
    opacity: 0;
    transform: translateY(-8px);
    transition: opacity 0.25s ease, transform 0.25s ease, visibility 0s linear 0.25s;
    visibility: hidden;
  }
  
  .dropdown-menu.active {
    opacity: 1;
    transform: translateY(0);
    transition: opacity 0.25s ease, transform 0.25s ease;
    visibility: visible;
  }
  
  /* Header shadow on scroll */
  .header-scrolled {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  }
  
  .header-scrolled #headerShadow {
    opacity: 1;
  }
  
  /* Subtle hover effects */
  .main-link:hover {
    transform: translateY(-1px);
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const navToggle = document.getElementById('navToggle');
    const mainNav = document.getElementById('mainNav');
    const header = document.querySelector('header');
    const headerShadow = document.getElementById('headerShadow');
    
    // Dropdown elements
    const projectsToggle = document.getElementById('projectsToggle');
    const projectsDropdown = document.getElementById('projectsDropdown');
    const whatsNewToggle = document.getElementById('whatsNewToggle');
    const galleryDropdown = document.getElementById('galleryDropdown');
    
    // Mobile menu toggle
    if (navToggle && mainNav) {
      navToggle.addEventListener('click', function() {
        mainNav.classList.toggle('hidden');
        document.body.classList.toggle('menu-open');
      });
    }
    
    // Function to set up dropdowns
    function setupDropdown(toggleBtn, dropdown) {
      if (toggleBtn && dropdown) {
        toggleBtn.addEventListener('click', function(e) {
          e.stopPropagation();
          
          // Close all other dropdowns first
          document.querySelectorAll('.dropdown-menu').forEach(menu => {
            if (menu !== dropdown && !menu.classList.contains('hidden')) {
              menu.classList.add('hidden');
              menu.classList.remove('active');
            }
          });
          
          // Toggle current dropdown
          dropdown.classList.toggle('hidden');
          
          // Add active class after a slight delay for animation
          if (!dropdown.classList.contains('hidden')) {
            setTimeout(() => {
              dropdown.classList.add('active');
            }, 10);
          } else {
            dropdown.classList.remove('active');
          }
        });
      }
    }
    
    // Set up dropdowns
    setupDropdown(projectsToggle, projectsDropdown);
    setupDropdown(whatsNewToggle, galleryDropdown);
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
      const dropdowns = document.querySelectorAll('.dropdown-menu');
      const toggles = document.querySelectorAll('.dropdown-toggle');
      
      // If not clicking on a dropdown or toggle button
      if (!Array.from(toggles).some(toggle => toggle.contains(e.target))) {
        dropdowns.forEach(dropdown => {
          dropdown.classList.add('hidden');
          dropdown.classList.remove('active');
        });
      }
    });
    
    // Close mobile menu when clicking a link
    const mobileLinks = mainNav ? mainNav.querySelectorAll('a:not([href="#"])') : [];
    mobileLinks.forEach(link => {
      link.addEventListener('click', function() {
        if (window.innerWidth < 768) {
          mainNav.classList.add('hidden');
          document.body.classList.remove('menu-open');
        }
      });
    });
    
    // Handle window resize - ensure menu is visible on desktop
    window.addEventListener('resize', function() {
      if (window.innerWidth >= 768) {
        if (mainNav && mainNav.classList.contains('hidden')) {
          mainNav.classList.remove('hidden');
        }
        document.body.classList.remove('menu-open');
      }
    });
    
    // Add shadow on scroll
    window.addEventListener('scroll', function() {
      if (header) {
        if (window.scrollY > 20) {
          header.classList.add('header-scrolled');
        } else {
          header.classList.remove('header-scrolled');
        }
      }
    });
    
    // Initialize header state on page load
    if (window.scrollY > 20 && header) {
      header.classList.add('header-scrolled');
    }
  });
</script>