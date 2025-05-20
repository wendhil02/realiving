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
                        primary: '#3B82F6', // Modernized blue
                        secondary: '#6366F1',
                        accent: '#10B981', // Green accent
                        dark: '#1E293B',
                        light: '#F8FAFC'
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
                    },
                    boxShadow: {
                        'nav': '0 4px 12px rgba(0, 0, 0, 0.05)',
                        'card': '0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05)',
                        'dropdown': '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)'
                    }
                }
            }
        }

        // JavaScript to show the loading screen with smooth animation
        window.onload = function() {
            const loadingScreen = document.getElementById("loadingScreen");
            loadingScreen.classList.add("opacity-0");
            setTimeout(() => {
                loadingScreen.classList.add("hidden");
            }, 500);
        }
    </script>
   
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        :where([class^="ri-"])::before {
            content: "\f3c2";
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F8FAFC;
        }

        /* Navbar styles */
        .nav-link {
            position: relative;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -6px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #3B82F6;
            transition: width 0.3s ease;
            border-radius: 2px;
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 70%;
        }

        /* Loading animation */
        .loading-animation {
            stroke-dasharray: 150;
            stroke-dashoffset: 150;
            animation: dash 1.5s ease-in-out infinite alternate;
        }

        @keyframes dash {
            from {
                stroke-dashoffset: 150;
            }
            to {
                stroke-dashoffset: 0;
            }
        }

        /* Dropdown animation */
        .dropdown {
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.3s ease, transform 0.3s ease;
            visibility: hidden;
        }

        .dropdown.active {
            opacity: 1;
            transform: translateY(0);
            visibility: visible;
        }

        /* Mobile menu animation */
        .mobile-menu {
            transform: translateX(100%);
            transition: transform 0.4s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .mobile-menu.active {
            transform: translateX(0);
        }

        .mobile-overlay {
            opacity: 0;
            transition: opacity 0.3s ease;
            visibility: hidden;
        }

        .mobile-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Subtle hover effects */
        .hover-scale {
            transition: transform 0.2s ease;
        }
        
        .hover-scale:hover {
            transform: scale(1.05);
        }

        /* User avatar glow effect */
        .avatar-glow {
            position: relative;
        }
        
        .avatar-glow::after {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            border-radius: 9999px;
            background: linear-gradient(45deg, #3B82F6, #10B981);
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .avatar-glow:hover::after {
            opacity: 1;
        }

        /* Base Styles for the Header */
.header {
  width: 100%;
  transition: all 0.3s ease;
}

.hover-scale {
  transition: transform 0.2s ease;
}

.hover-scale:hover {
  transform: scale(1.05);
}

.avatar-glow:hover {
  box-shadow: 0 0 15px rgba(59, 130, 246, 0.5);
}

.shadow-nav {
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.shadow-dropdown {
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

/* Mobile Menu - Hidden by Default */
.mobile-overlay {
  display: none;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.mobile-menu {
  transform: translateX(100%);
  transition: transform 0.3s ease;
}

.mobile-overlay.active {
  display: block;
  opacity: 1;
}

.mobile-menu.active {
  transform: translateX(0);
}

/* Dropdown Animation */
.dropdown {
  opacity: 0;
  visibility: hidden;
  transform: translateY(10px);
  transition: all 0.2s ease;
}

.dropdown.show {
  opacity: 1;
  visibility: visible;
  transform: translateY(0);
}

/* Text and UI Colors */
.text-primary {
  color: #3b82f6; /* Blue-500 */
}

.text-dark {
  color: #1f2937; /* Gray-800 */
}

/* Media Queries */

/* Small Phones (320px - 374px) */
@media screen and (max-width: 374px) {
  nav {
    height: 60px !important;
  }
  
  .header img.logo {
    height: 24px;
  }
  
  .profile-dropdown {
    width: 240px !important;
  }
  
  #mobileMenu {
    width: 85% !important;
  }
}

/* Regular Phones (375px - 639px) */
@media screen and (min-width: 375px) and (max-width: 639px) {
  nav {
    height: 64px !important;
  }
  
  .header img.logo {
    height: 28px;
  }
  
  #mobileMenu {
    width: 80% !important;
  }
  
  .profile-dropdown {
    width: 260px !important;
  }
}

/* Small Tablets/Large Phones (640px - 767px) */
@media screen and (min-width: 640px) and (max-width: 767px) {
  nav {
    height: 68px !important;
  }
  
  #mobileMenu {
    width: 320px;
  }
  
  .px-4 {
    padding-left: 1rem !important;
    padding-right: 1rem !important;
  }
}

/* Tablets (768px - 1023px) */
@media screen and (min-width: 768px) and (max-width: 1023px) {
  nav {
    height: 72px !important;
  }
  
  #mobileMenu {
    width: 350px;
  }
  
  .sm\:px-6 {
    padding-left: 1.5rem !important;
    padding-right: 1.5rem !important;
  }
}

/* Small Laptops (1024px - 1279px) */
@media screen and (min-width: 1024px) and (max-width: 1279px) {
  .lg\:space-x-6 {
    column-gap: 1.5rem !important;
  }
  
  .nav-link {
    font-size: 0.85rem !important;
  }
  
  .lg\:px-8 {
    padding-left: 2rem !important;
    padding-right: 2rem !important;
  }
}

/* Regular Laptops & Small Desktops (1280px - 1535px) */
@media screen and (min-width: 1280px) and (max-width: 1535px) {
  .lg\:space-x-8 {
    column-gap: 2rem !important;
  }
}

/* Large Desktops (1536px and above) */
@media screen and (min-width: 1536px) {
  .max-w-7xl {
    max-width: 1400px !important;
  }
  
  nav {
    height: 80px !important;
  }
}

/* Print Styles - Hide Navigation Elements */
@media print {
  .header {
    position: static !important;
    box-shadow: none !important;
  }
  
  .nav-link, #profileButton, #mobileMenuButton {
    display: none !important;
  }
  
  .max-w-7xl {
    max-width: 100% !important;
    padding: 0 !important;
  }
}

/* Dark Mode Support */
@media (prefers-color-scheme: dark) {
  .header.dark-mode {
    background-color: #111827 !important;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3) !important;
  }
  
  .dark-mode .text-dark {
    color: #f3f4f6 !important; /* Gray-100 */
  }
  
  .dark-mode .shadow-dropdown {
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4) !important;
  }
}

/* Animation for dropdown rotations */
@keyframes rotateDown {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(180deg);
  }
}

@keyframes rotateUp {
  from {
    transform: rotate(180deg);
  }
  to {
    transform: rotate(0deg);
  }
}

/* JavaScript enhancement for dropdown animation */
.dropdown-arrow-rotate {
  animation: rotateDown 0.3s forwards;
}

.dropdown-arrow-rotate-back {
  animation: rotateUp 0.3s forwards;
}

/* Fix for mobile dropdown panel spacing */
@media screen and (max-width: 1023px) {
  .mobile-dropdown-button + div {
    transition: max-height 0.3s ease;
    max-height: 0;
    overflow: hidden;
  }
  
  .mobile-dropdown-button + div.active {
    max-height: 200px;
  }
  
  /* Transition for mobile menu icons */
  .mobile-dropdown-button i {
    transition: transform 0.3s ease;
  }
  
  .mobile-dropdown-button.active i {
    transform: rotate(180deg);
  }
}

/* Custom scrollbar for dropdown menus with many items */
.dropdown::-webkit-scrollbar {
  width: 6px;
}

.dropdown::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

.dropdown::-webkit-scrollbar-thumb {
  background: #d1d5db;
  border-radius: 10px;
}

.dropdown::-webkit-scrollbar-thumb:hover {
  background: #9ca3af;
}

/* Maximum height for dropdown with scrollbar */
.dropdown.with-scroll {
  max-height: 400px;
  overflow-y: auto;
}
    </style>
</head>

<!-- Enhanced Loading Screen with SVG Animation -->
<div id="loadingScreen" class="fixed inset-0 z-50 flex items-center justify-center bg-white transition-opacity duration-500">
    <div class="flex flex-col items-center">
        <svg width="64" height="64" viewBox="0 0 44 44" xmlns="http://www.w3.org/2000/svg">
            <circle class="loading-animation" cx="22" cy="22" r="20" fill="none" stroke="#3B82F6" stroke-width="4" />
        </svg>
        <p class="mt-4 text-lg text-primary font-medium tracking-wide">Loading...</p>
    </div>
</div>

<header class="bg-white shadow-nav sticky top-0 z-40 transition-all duration-300">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <nav class="flex items-center justify-between h-20">
      <!-- Logo Section -->
      <div class="flex items-center space-x-3 p-2">
        <img src="../../logo/picart.png" alt="Logo" class="h-10 object-cover hover-scale">
      </div>

      <!-- Desktop Navigation -->
      <div class="hidden lg:flex lg:items-center lg:space-x-10">
        <a href="../admin_mainpage/mainpage.php" class="nav-link text-dark hover:text-primary text-sm transition-colors">
          <div class="flex items-center space-x-1">
            <i class="ri-dashboard-line text-lg"></i>
            <span>Dashboard</span>
          </div>
        </a>

        <!-- Client Management Dropdown -->
        <div class="relative group">
          <button onclick="toggleDropdown('clientDropdown')" class="nav-link flex items-center text-dark hover:text-primary text-sm group-hover:text-primary">
            <div class="flex items-center space-x-1">
              <i class="ri-user-3-line text-lg"></i>
              <span>Client Management</span>
              <i class="ri-arrow-down-s-line ml-1 transition-transform duration-300 group-hover:rotate-180"></i>
            </div>
          </button>
          <div id="clientDropdown" class="dropdown absolute bg-white shadow-dropdown rounded-lg p-2 mt-2 space-y-1 z-50 w-48 border border-gray-100">
            <a href="../admin_allclient/allclient.php" class="block px-4 py-2.5 text-sm hover:bg-blue-50 hover:text-primary rounded-md flex items-center space-x-2 transition-colors">
              <i class="ri-user-add-line"></i>
              <span>Add New Client</span>
            </a>
            <a href="../admin_calendar/calendar.php" class="block px-4 py-2.5 text-sm hover:bg-blue-50 hover:text-primary rounded-md flex items-center space-x-2 transition-colors">
              <i class="ri-calendar-line"></i>
              <span>Appointment</span>
            </a>
            <a href="../admin_insertnews/insert_news.php" class="block px-4 py-2.5 text-sm hover:bg-blue-50 hover:text-primary rounded-md flex items-center space-x-2 transition-colors">
              <i class="ri-newspaper-line"></i>
              <span>Latest News Edit</span>
            </a>
            <a href="../adminstatus/adminstatusforclient.php" class="block px-4 py-2.5 text-sm hover:bg-blue-50 hover:text-primary rounded-md flex items-center space-x-2 transition-colors">
              <i class="ri-refresh-line"></i>
              <span>Update Status</span>
            </a>
          </div>
        </div>

        <!-- Product Dropdown -->
        <div class="relative group">
          <button onclick="toggleDropdown('productDropdown')" class="nav-link flex items-center text-dark hover:text-primary text-sm group-hover:text-primary">
            <div class="flex items-center space-x-1">
              <i class="ri-store-2-line text-lg"></i>
              <span>Product</span>
              <i class="ri-arrow-down-s-line ml-1 transition-transform duration-300 group-hover:rotate-180"></i>
            </div>
          </button>
          <div id="productDropdown" class="dropdown absolute bg-white shadow-dropdown rounded-lg p-2 mt-2 z-50 w-40 border border-gray-100">
            <a href="../admin_product/products.php" class="block px-4 py-2.5 text-sm hover:bg-blue-50 hover:text-primary rounded-md flex items-center space-x-2 transition-colors">
              <i class="ri-archive-line"></i>
              <span>Inventory</span>
            </a>
          </div>
        </div>

        <!-- Quotation Dropdown -->
        <div class="relative group">
          <button onclick="toggleDropdown('quotationDropdown')" class="nav-link flex items-center text-dark hover:text-primary text-sm group-hover:text-primary">
            <div class="flex items-center space-x-1">
              <i class="ri-file-list-3-line text-lg"></i>
              <span>Quotation</span>
              <i class="ri-arrow-down-s-line ml-1 transition-transform duration-300 group-hover:rotate-180"></i>
            </div>
          </button>
          <div id="quotationDropdown" class="dropdown absolute bg-white shadow-dropdown rounded-lg p-2 mt-2 z-50 w-40 border border-gray-100">
            <a href="../installation_quotation/quotation.php" class="block px-4 py-2.5 text-sm hover:bg-blue-50 hover:text-primary rounded-md flex items-center space-x-2 transition-colors">
              <i class="ri-calculator-line"></i>
              <span>Computation</span>
            </a>
          </div>
        </div>

        <a href="../admin_inquireclient/inquireclient.php" class="nav-link text-dark hover:text-primary text-sm transition-colors">
          <div class="flex items-center space-x-1">
            <i class="ri-customer-service-2-line text-lg"></i>
            <span>Client Inquire</span>
          </div>
        </a>
      </div>

      <!-- Right Side Actions -->
      <div class="flex items-center space-x-5">
        <!-- Notification Icon -->
       
        
        <!-- Profile Dropdown -->
        <div class="relative">
          <button id="profileButton" class="avatar-glow w-10 h-10 flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-50 rounded-full hover:shadow-md transition-all duration-300 border-2 border-white">
            <i class="ri-user-smile-line text-xl text-primary"></i>
          </button>
          <div id="profileDropdown" class="hidden absolute right-0 mt-3 w-72 bg-white rounded-lg shadow-dropdown z-50 border border-gray-100 overflow-hidden">
            <div class="px-4 py-3 border-b bg-gradient-to-r from-blue-50 to-indigo-50">
              <div class="flex items-center space-x-3">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-lg font-bold">
                  <?= isset($_SESSION['admin_email']) ? strtoupper(substr($_SESSION['admin_email'], 0, 1)) : 'A'; ?>
                </div>
                <div>
                  <p class="text-sm font-semibold text-gray-700">
                    <?= isset($_SESSION['admin_email']) ? htmlspecialchars($_SESSION['admin_email']) : 'Unknown'; ?>
                  </p>
                  <p class="text-xs text-gray-500">Administrator</p>
                </div>
              </div>
            </div>
            <button onclick="location.href='#'" class="w-full text-left px-4 py-3 hover:bg-gray-50 flex items-center text-sm group transition-colors">
              <i class="ri-user-settings-line mr-3 text-lg text-gray-500 group-hover:text-primary"></i> <span class="group-hover:text-primary">Manage Profile</span>
            </button>
            <button onclick="location.href='dashboard.php'" class="w-full text-left px-4 py-3 hover:bg-gray-50 flex items-center text-sm group transition-colors">
              <i class="ri-settings-3-line mr-3 text-lg text-gray-500 group-hover:text-primary"></i> <span class="group-hover:text-primary">Account Settings</span>
            </button>
            <div class="border-t border-gray-100"></div>
            <button onclick="location.href='../../logout.php'" class="w-full text-left px-4 py-3 hover:bg-red-50 flex items-center text-sm group transition-colors">
              <i class="ri-logout-box-line mr-3 text-lg text-gray-500 group-hover:text-red-500"></i> <span class="group-hover:text-red-500">Sign Out</span>
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
  <div id="mobileMenuOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 mobile-overlay"></div>

  <div id="mobileMenu" class="fixed top-0 right-0 h-full w-72 bg-white shadow-lg mobile-menu z-50">
    <div class="flex justify-between items-center p-4 border-b bg-gradient-to-r from-blue-50 to-indigo-50">
      <div class="flex items-center space-x-2">
        <img src="../../logo/picart.png" alt="Logo" class="h-8 object-cover">
        <span class="font-semibold text-gray-800">Menu</span>
      </div>
      <button id="closeMenu" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100">
        <i class="ri-close-line text-2xl text-gray-700"></i>
      </button>
    </div>

    <!-- Mobile User Profile Summary -->
    <div class="p-4 border-b flex items-center space-x-3">
      <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-medium">
        <?= isset($_SESSION['admin_email']) ? strtoupper(substr($_SESSION['admin_email'], 0, 1)) : 'A'; ?>
      </div>
      <div class="text-sm">
        <p class="font-medium text-gray-800">
          <?= isset($_SESSION['admin_email']) ? htmlspecialchars($_SESSION['admin_email']) : 'Unknown'; ?>
        </p>
        <p class="text-xs text-gray-500">Administrator</p>
      </div>
    </div>

    <div class="py-2 space-y-1">
      <a href="../admin_mainpage/mainpage.php" class="block px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-primary flex items-center space-x-3 transition-colors">
        <i class="ri-dashboard-line text-lg"></i>
        <span>Dashboard</span>
      </a>

      <!-- Mobile Dropdowns -->
      <div>
        <button class="mobile-dropdown-button w-full text-left px-4 py-3 flex justify-between items-center text-gray-700 hover:bg-blue-50 hover:text-primary transition-colors">
          <div class="flex items-center space-x-3">
            <i class="ri-user-3-line text-lg"></i>
            <span>Client Management</span>
          </div>
          <i class="ri-arrow-down-s-line transition-transform duration-300"></i>
        </button>
        <div class="pl-12 space-y-1 hidden bg-gray-50 py-2">
          <a href="../admin_allclient/allclient.php" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary transition-colors">Add New Client</a>
          <a href="../admin_calendar/calendar.php" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary transition-colors">Appointment</a>
          <a href="../admin_insertnews/insert_news.php" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary transition-colors">Latest News Edit</a>
          <a href="../adminstatus/adminstatusforclient.php" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary transition-colors">Update Status</a>
        </div>
      </div>

      <div>
        <button class="mobile-dropdown-button w-full text-left px-4 py-3 flex justify-between items-center text-gray-700 hover:bg-blue-50 hover:text-primary transition-colors">
          <div class="flex items-center space-x-3">
            <i class="ri-store-2-line text-lg"></i>
            <span>Product</span>
          </div>
          <i class="ri-arrow-down-s-line transition-transform duration-300"></i>
        </button>
        <div class="pl-12 space-y-1 hidden bg-gray-50 py-2">
          <a href="../admin_product/products.php" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary transition-colors">Inventory</a>
        </div>
      </div>

      <div>
        <button class="mobile-dropdown-button w-full text-left px-4 py-3 flex justify-between items-center text-gray-700 hover:bg-blue-50 hover:text-primary transition-colors">
          <div class="flex items-center space-x-3">
            <i class="ri-file-list-3-line text-lg"></i>
            <span>Quotation</span>
          </div>
          <i class="ri-arrow-down-s-line transition-transform duration-300"></i>
        </button>
        <div class="pl-12 space-y-1 hidden bg-gray-50 py-2">
          <a href="../installation_quotation/quotation.php" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary transition-colors">Computation</a>
        </div>
      </div>

      <a href="../admin_inquireclient/inquireclient.php" class="block px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-primary flex items-center space-x-3 transition-colors">
        <i class="ri-customer-service-2-line text-lg"></i>
        <span>Client Inquire</span>
      </a>
      
      <!-- Mobile Logout -->
      <div class="border-t border-gray-100 mt-2 pt-2">
        <a href="../../logout.php" class="block px-4 py-3 text-gray-700 hover:bg-red-50 hover:text-red-500 flex items-center space-x-3 transition-colors">
          <i class="ri-logout-box-line text-lg"></i>
          <span>Sign Out</span>
        </a>
      </div>
    </div>
  </div>
</header>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Enhanced Mobile menu elements with animations
    const mobileMenuButton = document.getElementById('mobileMenuButton');
    const closeMenu = document.getElementById('closeMenu');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');

    function openMobileMenu() {
      mobileMenu.classList.add('active');
      mobileMenuOverlay.classList.add('active');
      document.body.style.overflow = 'hidden';
    }

    function closeMobileMenu() {
      mobileMenu.classList.remove('active');
      mobileMenuOverlay.classList.remove('active');
      document.body.style.overflow = '';
    }

    if (mobileMenuButton && closeMenu && mobileMenu && mobileMenuOverlay) {
      mobileMenuButton.addEventListener('click', openMobileMenu);
      closeMenu.addEventListener('click', closeMobileMenu);
      mobileMenuOverlay.addEventListener('click', closeMobileMenu);
    }

    // Mobile dropdown toggles with animation
    const mobileDropdownButtons = document.querySelectorAll('.mobile-dropdown-button');
    mobileDropdownButtons.forEach(button => {
      button.addEventListener('click', function () {
        const content = this.nextElementSibling;
        const icon = this.querySelector('i.ri-arrow-down-s-line');

        if (content) {
          content.classList.toggle('hidden');
          if (icon) {
            icon.style.transform = content.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
          }
        }
      });
    });

    // Enhanced profile dropdown with animation
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

    // Enhanced dropdown toggle for desktop
    window.toggleDropdown = function (id) {
      const dropdown = document.getElementById(id);
      const allDropdowns = document.querySelectorAll('[id$="Dropdown"]');

      // Close all other dropdowns
      allDropdowns.forEach(menu => {
        if (menu.id !== id && menu.id !== 'profileDropdown') {
          menu.classList.remove('active');
          menu.classList.add('hidden');
        }
      });

      // Toggle current dropdown with animation
      if (dropdown) {
        if (dropdown.classList.contains('hidden')) {
          dropdown.classList.remove('hidden');
          setTimeout(() => {
            dropdown.classList.add('active');
          }, 10);
        } else {
          dropdown.classList.remove('active');
          setTimeout(() => {
            dropdown.classList.add('hidden');
          }, 300);
        }
      }
    };

    // Close dropdowns when clicking outside
    document.addEventListener('click', (e) => {
      const dropdownIds = ['clientDropdown', 'productDropdown', 'quotationDropdown'];
      dropdownIds.forEach(id => {
        const btn = document.querySelector(`button[onclick="toggleDropdown('${id}')"]`);
        const dropdown = document.getElementById(id);
        if (btn && dropdown && !dropdown.contains(e.target) && !btn.contains(e.target)) {
          dropdown.classList.remove('active');
          setTimeout(() => {
            dropdown.classList.add('hidden');
          }, 300);
        }
      });
    });

    // Enhanced scroll effect for navbar shadow
    window.addEventListener('scroll', function() {
      const header = document.querySelector('header');
      if (window.scrollY > 10) {
        header.classList.add('shadow-md');
      } else {
        header.classList.remove('shadow-md');
      }
    });

    // Initialize any active page links
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
      const href = link.getAttribute('href');
      if (href && currentPath.includes(href)) {
        link.classList.add('active');
        link.classList.add('text-primary');
      }
    });
  });
</script>