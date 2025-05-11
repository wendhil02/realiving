<header class="fixed top-0 left-0 w-full bg-gray-100 border-t-4 border-yellow-500 shadow-md z-50">
  <div class="container mx-auto px-4">
    <div class="flex items-center justify-between h-16">
      <!-- Logo -->
      <div class="flex items-center">
        <a href="index.php" class="flex items-center">
          <img src="../logo/picart.png" alt="RealLiving Logo" class="h-8 md:h-9">
        </a>
      </div>

      <!-- Mobile Toggle Button - Improved accessibility -->
      <button id="navToggle" class="md:hidden text-gray-700 focus:outline-none p-2" aria-label="Toggle navigation">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path id="hamburgerIcon" class="block" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4 6h16M4 12h16M4 18h16" />
          <path id="closeIcon" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>


      <!-- Navigation - Improved mobile layout -->
      <nav id="mainNav" class="hidden md:flex fixed md:static inset-0 top-16 md:top-0 pt-4 md:pt-0 w-full md:w-auto 
                               bg-white md:bg-transparent shadow-lg md:shadow-none flex-col md:flex-row
                               items-start md:items-center gap-2 md:gap-6 px-4 md:px-0 pb-6 md:pb-0 
                               overflow-y-auto md:overflow-visible max-h-[calc(100vh-4rem)] md:max-h-none
                               font-medium z-40">

                                 <a href="mainpage.php" class="text-gray-900 hover:text-yellow-500 transition duration-200 
                                   w-full md:w-auto py-3 md:py-2 px-3 nav-link">Our World</a>


        <!-- Projects Dropdown - Improved mobile handling -->
        <div class="relative group/projects w-full md:w-auto">


          <button id="projectsToggle" class="w-full md:w-auto text-left md:text-center flex justify-between md:justify-center 
                                            items-center text-gray-900 hover:text-yellow-500 transition duration-200 
                                            py-3 md:py-2 px-3 nav-link">


            Projects <span class="ml-1 text-xs transition-transform duration-200" id="projectsArrow">▾</span>
          </button>

          <div id="projectsDropdown" class="hidden bg-gray-50 md:bg-white md:absolute static md:top-full md:left-0 mt-0 md:mt-1 
                                           w-full md:w-72 md:shadow-xl md:rounded-lg z-50 md:p-2 md:border md:border-gray-100">
            <a href="allofproject.php" class="block px-4 py-3 md:py-2 text-gray-900 hover:bg-yellow-50 
                                             hover:text-yellow-500 md:rounded-md text-sm transition duration-200">Accomplish Project</a>
            <a href="#" class="block px-4 py-3 md:py-2 text-gray-900 hover:bg-yellow-50 
                              hover:text-yellow-500 md:rounded-md text-sm transition duration-200">Residential Projects</a>
            <a href="#" class="block px-4 py-3 md:py-2 text-gray-900 hover:bg-yellow-50 
                              hover:text-yellow-500 md:rounded-md text-sm transition duration-200">Commercial Projects</a>
          </div>
        </div>
      
        <a href="about.php" class="text-gray-900 hover:text-yellow-500 transition duration-200 
                                   w-full md:w-auto py-3 md:py-2 px-3 nav-link">About</a>
        <a href="services.php" class="text-gray-900 hover:text-yellow-500 transition duration-200 
                                      w-full md:w-auto py-3 md:py-2 px-3 nav-link">Services</a>


        <!-- What's New Dropdown - Improved mobile handling -->
        <div class="relative group/whatsnew w-full md:w-auto">
          <button id="whatsNewToggle" class="w-full md:w-auto text-left md:text-center flex justify-between md:justify-center 
                                            items-center text-gray-900 hover:text-yellow-500 transition duration-200 
                                            py-3 md:py-2 px-3 nav-link">
            What's New <span class="ml-1 text-xs transition-transform duration-200" id="whatsNewArrow">▾</span>
          </button>
          <div id="galleryDropdown" class="hidden bg-gray-50 md:bg-white md:absolute static md:top-full md:left-0 mt-0 md:mt-1 
                                          w-full md:w-72 md:shadow-xl md:rounded-lg z-50 md:p-2 md:border md:border-gray-100">
            <a href="photos.php" class="block px-4 py-3 md:py-2 text-gray-900 hover:bg-yellow-50 
                                       hover:text-yellow-500 md:rounded-md text-sm transition duration-200">News</a>
            <a href="#" class="block px-4 py-3 md:py-2 text-gray-900 hover:bg-yellow-50 
                              hover:text-yellow-500 md:rounded-md text-sm transition duration-200">Design Inspirations</a>
            <a href="#" class="block px-4 py-3 md:py-2 text-gray-900 hover:bg-yellow-50 
                              hover:text-yellow-500 md:rounded-md text-sm transition duration-200">Upcoming Events</a>
          </div>
        </div>

        <a href="contact.php" class="text-gray-900 hover:text-yellow-500 transition duration-200 
                                     w-full md:w-auto py-3 md:py-2 px-3 nav-link">Contact</a>

        <!-- Contact Button - Show on all screens now -->
        <a href="quote.php" class="block w-full md:w-auto px-4 py-3 md:py-2 mt-4 md:mt-0 bg-yellow-500 hover:bg-yellow-600 
                                   text-white text-center rounded-md transition duration-200 shadow-sm">Get Quote</a>
      </nav>
    </div>
  </div>
  
</header>

<!-- Add padding to prevent content from being hidden under fixed navbar -->
<div class="h-16"></div>

<style>
  /* Custom underline effect for nav links - Desktop only */
  @media (min-width: 768px) {
    .nav-link::after {
      content: '';
      position: absolute;
      left: 0;
      bottom: 0;
      width: 100%;
      height: 2px;
      background-color: #EAB308;
      /* yellow-500 */
      transform: scaleX(0);
      transition: transform 0.3s ease;
      transform-origin: left;
    }

    .nav-link:hover::after {
      transform: scaleX(1);
    }
  }

  /* Mobile specific styles */
  @media (max-width: 767px) {
    .nav-link {
      border-bottom: 1px solid #f0f0f0;
      display: block;
    }

    /* Prevent body scrolling when mobile menu is open */
    body.menu-open {
      overflow: hidden;
    }

    /* Rotate dropdown arrows when active */
    .rotate-arrow {
      transform: rotate(180deg);
    }
  }
</style>

<script>
  // Mobile menu toggle with improved UX
  const navToggle = document.getElementById('navToggle');
  const mainNav = document.getElementById('mainNav');
  const hamburgerIcon = document.getElementById('hamburgerIcon');
  const closeIcon = document.getElementById('closeIcon');

  // Project dropdown elements
  const projectsToggle = document.getElementById('projectsToggle');
  const projectsDropdown = document.getElementById('projectsDropdown');
  const projectsArrow = document.getElementById('projectsArrow');

  // What's New dropdown elements
  const whatsNewToggle = document.getElementById('whatsNewToggle');
  const galleryDropdown = document.getElementById('galleryDropdown');
  const whatsNewArrow = document.getElementById('whatsNewArrow');

  if (navToggle && mainNav) {
    navToggle.addEventListener('click', function() {
      mainNav.classList.toggle('hidden');
      hamburgerIcon.classList.toggle('hidden');
      closeIcon.classList.toggle('hidden');
      document.body.classList.toggle('menu-open');
    });
  }

  // Toggle dropdown function - Mobile friendly
  function setupDropdown(toggleBtn, dropdown, arrow) {
    if (toggleBtn && dropdown) {
      toggleBtn.addEventListener('click', function(e) {
        e.stopPropagation();

        // Close other dropdowns first
        if (projectsDropdown && projectsDropdown !== dropdown) {
          projectsDropdown.classList.add('hidden');
          if (projectsArrow) projectsArrow.classList.remove('rotate-arrow');
        }

        if (galleryDropdown && galleryDropdown !== dropdown) {
          galleryDropdown.classList.add('hidden');
          if (whatsNewArrow) whatsNewArrow.classList.remove('rotate-arrow');
        }

        // Toggle current dropdown
        dropdown.classList.toggle('hidden');
        if (arrow) arrow.classList.toggle('rotate-arrow');
      });
    }
  }

  // Setup both dropdowns
  setupDropdown(projectsToggle, projectsDropdown, projectsArrow);
  setupDropdown(whatsNewToggle, galleryDropdown, whatsNewArrow);

  // Close dropdowns on outside click
  document.addEventListener('click', function(e) {
    // Check if we need to close Project dropdown
    if (projectsDropdown && !projectsDropdown.contains(e.target) &&
      projectsToggle && !projectsToggle.contains(e.target)) {
      projectsDropdown.classList.add('hidden');
      if (projectsArrow) projectsArrow.classList.remove('rotate-arrow');
    }

    // Check if we need to close What's New dropdown
    if (galleryDropdown && !galleryDropdown.contains(e.target) &&
      whatsNewToggle && !whatsNewToggle.contains(e.target)) {
      galleryDropdown.classList.add('hidden');
      if (whatsNewArrow) whatsNewArrow.classList.remove('rotate-arrow');
    }
  });

  // Close mobile menu when clicking on a link (better UX)
  const mobileLinks = mainNav ? mainNav.querySelectorAll('a:not([href="#"])') : [];
  mobileLinks.forEach(link => {
    link.addEventListener('click', function() {
      if (window.innerWidth < 768) {
        mainNav.classList.add('hidden');
        hamburgerIcon.classList.remove('hidden');
        closeIcon.classList.add('hidden');
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
      hamburgerIcon.classList.remove('hidden');
      closeIcon.classList.add('hidden');
    }
  });

  // Add shadow on scroll
  window.addEventListener('scroll', function() {
    const header = document.querySelector('header');
    if (header) {
      if (window.scrollY > 20) {
        header.classList.add('shadow-md');
      } else {
        header.classList.remove('shadow-md');
      }
    }
  });
</script>