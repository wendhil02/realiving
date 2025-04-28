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

<header class="bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center justify-between h-16">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center space-x-3 p-4">
                <img src="../logo/picart.png" alt="Logo" class="h-[40px] w-full object-cover">
                <img src="../logo/noblebg.png" alt="Logo" class="h-[40px] w-full object-cover">
            </div>


            <!-- Desktop Navigation -->
            <div class="hidden lg:flex lg:items-center lg:space-x-8">
                <a href="mainpage.php" class="nav-link  text-gray-900 hover:text-primary px-2 py-1 text-sm font-medium"> Dashboard </a>
                
                <div class="dropdown-trigger relative">
                    <button class="nav-link flex items-center text-gray-900 hover:text-primary px-2 py-1 text-sm font-medium">
                        Client Management
                        <div class="w-4 h-4 flex items-center justify-center ml-1">
                            <i class="ri-arrow-down-s-line"></i>
                        </div>
                    </button>
                    <div class="dropdown bg-white shadow-lg rounded p-2 mt-1">
                        <a href="allclient.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary rounded">Add New Client </a>
                        <a href="calendar.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary rounded">Appointment</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary rounded">sample</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary rounded">sample</a>
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
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary rounded">sample</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary rounded">sample</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary rounded">sample</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary rounded">sample</a>
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
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary rounded">Konsultasyon</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary rounded">Pagdisenyo</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary rounded">Pagbuo ng Website</a>
                    </div>
                </div>

                <a href="#" class="nav-link text-gray-900 hover:text-primary px-2 py-1 text-sm font-medium">Progress Client</a>
            </div>

            <!-- Right Side Actions -->
            <div class="flex items-center space-x-4">
                <!-- Search -->


                <!-- User Profile -->
                <div class="dropdown-trigger relative">
                    <button class="w-10 h-10 flex items-center justify-center bg-gray-100 rounded-full hover:bg-gray-200 focus:outline-none !rounded-button">
                        <i class="ri-user-line text-lg text-gray-600"></i>
                    </button>
                    <div class="dropdown right-0 left-auto bg-white shadow-lg rounded p-2 mt-1 w-48">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-sm font-medium">Magandang araw!</p>
                            <p class="text-xs text-gray-500">Maria Santos</p>
                        </div>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary rounded">
                            <div class="w-4 h-4 flex items-center justify-center mr-2">
                                <i class="ri-user-settings-line"></i>
                            </div>
                            Profile
                        </a>
                        <a href="dashboard.php" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary rounded">
                            <div class="w-4 h-4 flex items-center justify-center mr-2">
                                <i class="ri-settings-3-line"></i>
                            </div>
                            Setting
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary rounded">
                            <div class="w-4 h-4 flex items-center justify-center mr-2">
                                <i class="ri-logout-box-line"></i>
                            </div>
                            logout
                        </a>
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
                <a href="#" class="block px-3 py-2 text-base font-medium text-primary bg-gray-100 rounded">Tahanan</a>

                <div class="mobile-dropdown">
                    <button class="mobile-dropdown-button w-full flex justify-between items-center px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-100 hover:text-primary rounded">
                        Mga Produkto
                        <div class="w-5 h-5 flex items-center justify-center">
                            <i class="ri-arrow-down-s-line"></i>
                        </div>
                    </button>
                    <div class="mobile-dropdown-content hidden pl-4 mt-1 space-y-1">
                        <a href="#" class="block px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-primary rounded">Mga Kagamitan sa Bahay</a>
                        <a href="#" class="block px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-primary rounded">Mga Elektroniko</a>
                        <a href="#" class="block px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-primary rounded">Mga Damit</a>
                        <a href="#" class="block px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-primary rounded">Mga Pagkain</a>
                    </div>
                </div>

                <div class="mobile-dropdown">
                    <button class="mobile-dropdown-button w-full flex justify-between items-center px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-100 hover:text-primary rounded">
                        Mga Serbisyo
                        <div class="w-5 h-5 flex items-center justify-center">
                            <i class="ri-arrow-down-s-line"></i>
                        </div>
                    </button>
                    <div class="mobile-dropdown-content hidden pl-4 mt-1 space-y-1">
                        <a href="#" class="block px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-primary rounded">Konsultasyon</a>
                        <a href="#" class="block px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-primary rounded">Pagdisenyo</a>
                        <a href="#" class="block px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-primary rounded">Pagbuo ng Website</a>
                    </div>
                </div>

                <a href="#" class="block px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-100 hover:text-primary rounded">Tungkol sa Amin</a>
                <a href="#" class="block px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-100 hover:text-primary rounded">Makipag-ugnayan</a>
            </nav>

            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="flex items-center px-3 py-2">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 flex items-center justify-center bg-gray-200 rounded-full">
                            <i class="ri-user-line text-lg text-gray-600"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Maria Santos</p>
                        <p class="text-xs text-gray-500">maria.santos@email.com</p>
                    </div>
                </div>
                <div class="mt-3 space-y-1">
                    <a href="#" class="block px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-primary rounded">Aking Profile</a>
                    <a href="#" class="block px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-primary rounded">Mga Setting</a>
                    <a href="#" class="block px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-primary rounded">Mag-logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div id="mobileMenuOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>
</header>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search toggle


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