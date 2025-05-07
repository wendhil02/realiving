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
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center space-x-3 p-4">
                <img src="../../logo/picart.png" alt="Logo" class="h-[40px] w-full object-cover">
                <img src="../../logo/noblebg.png" alt="Logo" class="h-[40px] w-full object-cover">
            </div>

            <span class="text-lg font-semibold text-gray-800 ml-2">Admin Panel</span>

            <!-- Desktop Navigation -->
            <div class="hidden lg:flex lg:items-center lg:space-x-8">
                <a href="../admin_mainpage/mainpage.php" class="nav-link  text-gray-900 hover:text-primary px-2 py-1 text-sm font-medium"> Dashboard </a>

                <div class="dropdown-trigger relative">
                    <button class="nav-link flex items-center text-gray-900 hover:text-primary px-2 py-1 text-sm font-medium">
                        Client Management
                        <div class="w-4 h-4 flex items-center justify-center ml-1">
                            <i class="ri-arrow-down-s-line"></i>
                        </div>
                    </button>

                    <div class="dropdown bg-white shadow-lg rounded p-2 mt-1">
                        <a href="../admin_allclient/allclient.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary rounded">Add New Client </a>
                        <a href="../admin_calendar/calendar.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary rounded">Appointment</a>
                        <a href="../admin_insertnews/insert_news.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary rounded">Latest New Edit</a>
                        <a href="../adminstatus/adminstatusforclient.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary rounded">Update status</a>
                    </div>
                </div>

                <div class="dropdown-trigger relative">
                    <button class="nav-link flex items-center text-gray-900 hover:text-primary px-2 py-1 text-sm font-medium">
                        Product
                        <div class="w-4 h-4 flex items-center justify-center ml-1">
                            <i class="ri-arrow-down-s-line"></i>
                        </div>
                    </button>
                    <div class="dropdown bg-white shadow-lg rounded p-2 mt-1">
                        <a href="../admin_product/products.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary rounded">Inventory</a>
                    </div>
                </div>

                <div class="dropdown-trigger relative">
                    <button class="nav-link flex items-center text-gray-900 hover:text-primary px-2 py-1 text-sm font-medium">
                        Quotation
                        <div class="w-4 h-4 flex items-center justify-center ml-1">
                            <i class="ri-arrow-down-s-line"></i>
                        </div>
                    </button>
                    <div class="dropdown bg-white shadow-lg rounded p-2 mt-1">
                        <a href="../installation_quotation/quotation.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary rounded">computation</a>
                    </div>
                </div>

                <a href="../admin_inquireclient/inquireclient.php" class="nav-link text-gray-900 hover:text-primary px-2 py-1 text-sm font-medium">Client inquire</a>
            </div>

            <!-- Right Side Actions -->
            <div class="flex items-center space-x-4">

                <!-- User Profile -->
                <div class="relative">
                    <!-- Trigger Button -->
                    <button class="w-10 h-10 flex items-center justify-center bg-gray-100 rounded-full hover:bg-gray-200 focus:outline-none" id="dropdownButton">
                        <i class="ri-user-line text-xl text-gray-600"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-64 bg-white rounded-md shadow-xl z-50">
                        <!-- User Info -->
                        <div class="px-4 py-3 border-b border-gray-200">
                            <p class="text-sm text-gray-700 font-semibold">
                                <?= isset($_SESSION['admin_email']) ? htmlspecialchars($_SESSION['admin_email']) : 'Unknown'; ?>
                            </p>
                        </div>

                        <!-- Menu Buttons -->
                        <button onclick="location.href='#'" class="w-full text-left px-4 py-3 hover:bg-gray-100 flex items-center text-sm text-gray-800">
                            <i class="ri-user-settings-line mr-3 text-lg"></i>
                            Profile
                        </button>
                        <button onclick="location.href='dashboard.php'" class="w-full text-left px-4 py-3 hover:bg-gray-100 flex items-center text-sm text-gray-800">
                            <i class="ri-settings-3-line mr-3 text-lg"></i>
                            Setting
                        </button>
                        <button onclick="location.href='../../logout.php'" class="w-full text-left px-4 py-3 hover:bg-gray-100 flex items-center text-sm text-gray-800">
                            <i class="ri-logout-box-line mr-3 text-lg"></i>
                            Logout
                        </button>
                    </div>
                </div>
                <!-- Mobile Menu Button -->
                <button id="mobileMenuButton" class="lg:hidden w-10 h-10 flex items-center justify-center text-gray-500 hover:text-primary focus:outline-none !rounded-button">
                    <i class="ri-menu-line text-xl"></i>
                </button>
            </div>
        </nav>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="mobile-menu fixed inset-0 z-50 lg:hidden bg-white shadow-xl w-64 h-full overflow-auto">
        <div class="p-4 border-b border-gray-200 flex justify-between items-center">
            <a href="#" class="text-xl font-['Pacifico'] text-primary">logo</a>
            <button id="closeMenu" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-primary focus:outline-none !rounded-button">
                <i class="ri-close-line text-xl"></i>
            </button>
        </div>
        <div class="p-4">

            <nav class="space-y-1">
                <a href="../admin_mainpage/mainpage.php" class="block px-3 py-2 text-base font-medium text-primary bg-gray-100 rounded">Dashboard</a>

                <div class="mobile-dropdown">
                    <button class="mobile-dropdown-button w-full flex justify-between items-center px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-100 hover:text-primary rounded">
                        Client Management
                        <div class="w-5 h-5 flex items-center justify-center">
                            <i class="ri-arrow-down-s-line"></i>
                        </div>
                    </button>
                    <div class="mobile-dropdown-content hidden pl-4 mt-1 space-y-1">
                        <a href="../admin_allclient/allclient.php" class="block px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-primary rounded">Add New Client</a>
                        <a href="../admin_calendar/calendar.php" class="block px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-primary rounded">Appointment</a>
                        <a href="../admin_insertnews/insert_news.php" class="block px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-primary rounded">Latest News Edit</a>
                        <a href="#" class="block px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-primary rounded">sample</a>
                    </div>
                </div>

                <div class="mobile-dropdown">
                    <button class="mobile-dropdown-button w-full flex justify-between items-center px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-100 hover:text-primary rounded">
                        Products
                        <div class="w-5 h-5 flex items-center justify-center">
                            <i class="ri-arrow-down-s-line"></i>
                        </div>
                    </button>
                    <div class="mobile-dropdown-content hidden pl-4 mt-1 space-y-1">
                        <a href="../admin_product/products.php" class="block px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-primary rounded">Inventory</a>

                    </div>
                </div>

                <a href="#" class="block px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-100 hover:text-primary rounded">Tungkol sa Amin</a>
                <a href="#" class="block px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-100 hover:text-primary rounded">Makipag-ugnayan</a>
            </nav>

            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="flex items-center px-4 py-3">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 flex items-center justify-center bg-gray-200 rounded-full">
                            <i class="ri-user-line text-xl text-gray-600"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-gray-600">👤 Logged in as:</p>
                        <p class="text-sm font-semibold text-gray-800">
                            <?= isset($_SESSION['admin_email']) ? htmlspecialchars($_SESSION['admin_email']) : 'Unknown'; ?>
                        </p>
                    </div>
                </div>

                <div class="mt-3 space-y-1 px-4">
                    <a href="#" class="block px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-blue-600 rounded">
                        Aking Profile
                    </a>
                    <a href="#" class="block px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-blue-600 rounded">
                        Mga Setting
                    </a>
                    <a href="../../logout.php" class="block px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-red-600 rounded">
                        Mag-logout
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div id="mobileMenuOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>


</header>
<script>
    window.addEventListener('load', function() {
        const loadingScreen = document.getElementById('loadingScreen');
        loadingScreen.style.opacity = '0';
        setTimeout(() => {
            loadingScreen.style.display = 'none';
        }, 500); // Optional fade-out delay
    });
</script>



<script>
    const dropdownBtn = document.getElementById('dropdownButton');
    const dropdownMenu = document.getElementById('dropdownMenu');

    dropdownBtn.addEventListener('click', () => {
        dropdownMenu.classList.toggle('hidden');
    });

    // Optional: Close dropdown when clicking outside
    window.addEventListener('click', function(e) {
        if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
            dropdownMenu.classList.add('hidden');
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Mobile menu
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const closeMenu = document.getElementById('closeMenu');
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');

        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.add('active');
            mobileMenuOverlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });

        function closeMenuFunc() {
            mobileMenu.classList.remove('active');
            mobileMenuOverlay.classList.add('hidden');
            document.body.style.overflow = '';
        }

        closeMenu.addEventListener('click', closeMenuFunc);
        mobileMenuOverlay.addEventListener('click', closeMenuFunc);

        // Mobile dropdowns
        const mobileDropdownButtons = document.querySelectorAll('.mobile-dropdown-button');

        mobileDropdownButtons.forEach(button => {
            button.addEventListener('click', function() {
                const content = this.nextElementSibling;
                const icon = this.querySelector('i');

                content.classList.toggle('hidden');

                if (!content.classList.contains('hidden')) {
                    icon.classList.remove('ri-arrow-down-s-line');
                    icon.classList.add('ri-arrow-up-s-line');
                } else {
                    icon.classList.remove('ri-arrow-up-s-line');
                    icon.classList.add('ri-arrow-down-s-line');
                }
            });
        });
    });
</script>