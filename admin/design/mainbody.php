<?php
ob_start();
?>

<!DOCTYPE html>
<html lang="tl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tagalog Navbar</title>
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#48cae4',
                        secondary: '#6366f1'
                    },
                    borderRadius: {
                        'none': '0px',
                        'sm': '4px',
                        DEFAULT: '8px',
                        'md': '12px',
                        'lg': '16px',
                        'xl': '20px',
                        '2xl': '24px',
                        '3xl': '32px',
                        'full': '9999px',
                        'button': '8px'
                    }
                }
            }
        }

        // JavaScript to show the loading screen for 2 seconds
        window.onload = function() {
            setTimeout(function() {
                document.getElementById("loadingScreen").classList.add("hidden");  // Hide loading screen after 2 seconds
            }, 2000);  // 2000 milliseconds = 2 seconds
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        :where([class^="ri-"])::before {
            content: "\f3c2";
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        .dropdown {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            min-width: 200px;
            z-index: 50;
        }

        .dropdown-trigger:hover .dropdown {
            display: block;
        }

        .nav-link {
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 0;
            background-color: #48cae4;
            transition: width 0.3s ease;
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }

        .search-container input {
            width: 0;
            padding: 0;
            opacity: 0;
            transition: all 0.3s ease;
        }

        .search-container.active input {
            width: 200px;
            padding: 0.5rem 1rem;
            opacity: 1;
        }

        @media (max-width: 1023px) {
            .mobile-menu {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .mobile-menu.active {
                transform: translateX(0);
            }
        }
    </style>
</head>

<!-- Loading Screen -->
<div id="loadingScreen" class="fixed inset-0 z-50 flex items-center justify-center bg-white">
    <div class="flex flex-col items-center">
        <!-- Spinner -->
        <div class="w-16 h-16 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
        <p class="mt-4 text-lg text-blue-600 font-semibold">Loading...</p>
    </div>
</div>

<header class="bg-white shadow-sm sticky top-0 z-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <nav class="flex items-center justify-between h-16">
      <!-- Logo Section -->
      <div class="flex items-center space-x-3 p-4">
        <img src="../../logo/picart.png" alt="Logo" class="h-10 object-cover">
      </div>

      <!-- Desktop Navigation -->
      <div class="hidden lg:flex lg:items-center lg:space-x-8">
        <a href="../admin_mainpage/mainpage.php" class="text-gray-900 hover:text-primary text-sm font-medium">Dashboard</a>

        <!-- Client Management Dropdown -->
        <div class="relative">
          <button onclick="toggleDropdown('clientDropdown')" class="flex items-center text-gray-900 hover:text-primary text-sm font-medium">
            Client Management <i class="ri-arrow-down-s-line ml-1"></i>
          </button>
          <div id="clientDropdown" class="absolute hidden bg-white shadow-lg rounded p-2 mt-1 space-y-1 z-50">
            <a href="../admin_allclient/allclient.php" class="block px-4 py-2 text-sm hover:bg-gray-100 rounded">Add New Client</a>
            <a href="../admin_calendar/calendar.php" class="block px-4 py-2 text-sm hover:bg-gray-100 rounded">Appointment</a>
            <a href="../admin_insertnews/insert_news.php" class="block px-4 py-2 text-sm hover:bg-gray-100 rounded">Latest News Edit</a>
            <a href="../adminstatus/adminstatusforclient.php" class="block px-4 py-2 text-sm hover:bg-gray-100 rounded">Update Status</a>
          </div>
        </div>

        <!-- Product Dropdown -->
        <div class="relative">
          <button onclick="toggleDropdown('productDropdown')" class="flex items-center text-gray-900 hover:text-primary text-sm font-medium">
            Product <i class="ri-arrow-down-s-line ml-1"></i>
          </button>
          <div id="productDropdown" class="absolute hidden bg-white shadow-lg rounded p-2 mt-1 z-50">
            <a href="../admin_product/products.php" class="block px-4 py-2 text-sm hover:bg-gray-100 rounded">Inventory</a>
          </div>
        </div>

        <!-- Quotation Dropdown -->
        <div class="relative">
          <button onclick="toggleDropdown('quotationDropdown')" class="flex items-center text-gray-900 hover:text-primary text-sm font-medium">
            Quotation <i class="ri-arrow-down-s-line ml-1"></i>
          </button>
          <div id="quotationDropdown" class="absolute hidden bg-white shadow-lg rounded p-2 mt-1 z-50">
            <a href="../installation_quotation/quotation.php" class="block px-4 py-2 text-sm hover:bg-gray-100 rounded">Computation</a>
          </div>
        </div>

        <a href="../admin_inquireclient/inquireclient.php" class="text-gray-900 hover:text-primary text-sm font-medium">Client Inquire</a>
      </div>

      <!-- Right Side Actions -->
      <div class="flex items-center space-x-4">
        <!-- Profile Dropdown -->
        <div class="relative">
          <button id="profileButton" class="w-10 h-10 flex items-center justify-center bg-gray-100 rounded-full hover:bg-gray-200">
            <i class="ri-user-line text-xl text-gray-600"></i>
          </button>
          <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-64 bg-white rounded-md shadow-xl z-50">
            <div class="px-4 py-3 border-b">
              <p class="text-sm font-semibold text-gray-700">
                <?= isset($_SESSION['admin_email']) ? htmlspecialchars($_SESSION['admin_email']) : 'Unknown'; ?>
              </p>
            </div>
            <button onclick="location.href='#'" class="w-full text-left px-4 py-3 hover:bg-gray-100 flex items-center text-sm">
              <i class="ri-user-settings-line mr-3 text-lg"></i> Profile
            </button>
            <button onclick="location.href='dashboard.php'" class="w-full text-left px-4 py-3 hover:bg-gray-100 flex items-center text-sm">
              <i class="ri-settings-3-line mr-3 text-lg"></i> Settings
            </button>
            <button onclick="location.href='../../logout.php'" class="w-full text-left px-4 py-3 hover:bg-gray-100 flex items-center text-sm">
              <i class="ri-logout-box-line mr-3 text-lg"></i> Logout
            </button>
          </div>
        </div>

        <!-- Mobile Toggle -->
        <button id="mobileMenuButton" class="lg:hidden w-10 h-10 flex items-center justify-center text-gray-500 hover:text-primary">
          <i class="ri-menu-line text-2xl"></i>
        </button>
      </div>
    </nav>
  </div>

  <!-- Mobile Overlay & Menu -->
  <div id="mobileMenuOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>

  <div id="mobileMenu" class="fixed top-0 right-0 h-full w-64 bg-white shadow-lg transform translate-x-full transition-transform duration-300 z-50">
    <div class="flex justify-between items-center p-4 border-b">
      <span class="font-semibold text-gray-800">Menu</span>
      <button id="closeMenu"><i class="ri-close-line text-2xl text-gray-700"></i></button>
    </div>

    <div class="p-4 space-y-4">
      <a href="../admin_mainpage/mainpage.php" class="block text-gray-700 hover:text-primary">Dashboard</a>

      <!-- Mobile Dropdowns -->
      <div>
        <button class="mobile-dropdown-button w-full text-left flex justify-between items-center text-gray-700 hover:text-primary">
          Client Management <i class="ri-arrow-down-s-line"></i>
        </button>
        <div class="pl-4 mt-2 space-y-1 hidden">
          <a href="../admin_allclient/allclient.php" class="block text-sm text-gray-600">Add New Client</a>
          <a href="../admin_calendar/calendar.php" class="block text-sm text-gray-600">Appointment</a>
          <a href="../admin_insertnews/insert_news.php" class="block text-sm text-gray-600">Latest News Edit</a>
          <a href="../adminstatus/adminstatusforclient.php" class="block text-sm text-gray-600">Update Status</a>
        </div>
      </div>

      <div>
        <button class="mobile-dropdown-button w-full text-left flex justify-between items-center text-gray-700 hover:text-primary">
          Product <i class="ri-arrow-down-s-line"></i>
        </button>
        <div class="pl-4 mt-2 hidden">
          <a href="../admin_product/products.php" class="block text-sm text-gray-600">Inventory</a>
        </div>
      </div>

      <div>
        <button class="mobile-dropdown-button w-full text-left flex justify-between items-center text-gray-700 hover:text-primary">
          Quotation <i class="ri-arrow-down-s-line"></i>
        </button>
        <div class="pl-4 mt-2 hidden">
          <a href="../installation_quotation/quotation.php" class="block text-sm text-gray-600">Computation</a>
        </div>
      </div>

      <a href="../admin_inquireclient/inquireclient.php" class="block text-gray-700 hover:text-primary">Client Inquire</a>
    </div>
  </div>
</header>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Mobile menu elements
    const mobileMenuButton = document.getElementById('mobileMenuButton');
    const closeMenu = document.getElementById('closeMenu');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');

    function openMobileMenu() {
      mobileMenu.classList.remove('translate-x-full');
      mobileMenuOverlay.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }

    function closeMobileMenu() {
      mobileMenu.classList.add('translate-x-full');
      mobileMenuOverlay.classList.add('hidden');
      document.body.style.overflow = '';
    }

    if (mobileMenuButton && closeMenu && mobileMenu && mobileMenuOverlay) {
      mobileMenuButton.addEventListener('click', openMobileMenu);
      closeMenu.addEventListener('click', closeMobileMenu);
      mobileMenuOverlay.addEventListener('click', closeMobileMenu);
    }

    // Mobile dropdown toggles with icon switching
    const mobileDropdownButtons = document.querySelectorAll('.mobile-dropdown-button');
    mobileDropdownButtons.forEach(button => {
      button.addEventListener('click', function () {
        const content = this.nextElementSibling;
        const icon = this.querySelector('i');

        if (content) {
          content.classList.toggle('hidden');
          if (icon) {
            icon.classList.toggle('ri-arrow-down-s-line');
            icon.classList.toggle('ri-arrow-up-s-line');
          }
        }
      });
    });

    // Profile dropdown logic
    const profileButton = document.getElementById("profileButton");
    const profileDropdown = document.getElementById("profileDropdown");

    if (profileButton && profileDropdown) {
      profileButton.addEventListener("click", function (e) {
        e.stopPropagation();
        profileDropdown.classList.toggle("hidden");
      });

      document.addEventListener("click", function (e) {
        if (!profileDropdown.contains(e.target) && !profileButton.contains(e.target)) {
          profileDropdown.classList.add("hidden");
        }
      });
    }

    // Generic dropdown toggle (desktop version or others)
    window.toggleDropdown = function (id) {
      const dropdown = document.getElementById(id);
      const allDropdowns = document.querySelectorAll('[id$="Dropdown"]');

      allDropdowns.forEach(menu => {
        if (menu.id !== id) menu.classList.add('hidden');
      });

      if (dropdown) dropdown.classList.toggle('hidden');
    };

    document.addEventListener('click', (e) => {
      const dropdownIds = ['clientDropdown', 'productDropdown', 'quotationDropdown', 'profileDropdown'];
      dropdownIds.forEach(id => {
        const btn = document.querySelector(`button[onclick="toggleDropdown('${id}')"]`);
        const dropdown = document.getElementById(id);
        if (btn && dropdown && !dropdown.contains(e.target) && !btn.contains(e.target)) {
          dropdown.classList.add('hidden');
        }
      });
    });

    // Optional: Loading screen fade out
    const loadingScreen = document.getElementById('loadingScreen');
    if (loadingScreen) {
      window.addEventListener('load', function () {
        loadingScreen.style.opacity = '0';
        setTimeout(() => {
          loadingScreen.style.display = 'none';
        }, 500);
      });
    }
  });
</script>
