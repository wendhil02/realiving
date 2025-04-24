<?php
ob_start();
$current_page = basename($_SERVER['PHP_SELF']);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collapsible Sidebar</title>
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4f46e5',
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
            background-color: #f9fafb;
        }

        .sidebar {
            transition: width 0.3s ease-in-out;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .menu-item {
            transition: all 0.2s ease-in-out;
        }

        .menu-item:hover {
            background-color: rgba(79, 70, 229, 0.05);
        }

        .menu-item.active {
            background-color: rgba(79, 70, 229, 0.1);
            border-left: 3px solid #4f46e5;
        }

        .menu-text {
            transition: opacity 0.2s ease-in-out;
            white-space: nowrap;
        }

        .logo-text {
            transition: opacity 0.2s ease-in-out, width 0.3s ease-in-out;
            white-space: nowrap;
        }

        @media (max-width: 768px) {
            .sidebar-overlay {
                background-color: rgba(0, 0, 0, 0.5);
                transition: opacity 0.3s ease-in-out;
            }
        }

        .main-content {
            transition: margin-left 0.3s ease-in-out;
        }

        input:focus,
        button:focus {
            outline: none;
        }

    </style>
</head>

    <!-- Sidebar Overlay (Mobile only) -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 hidden md:hidden"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar fixed md:relative min-h-screen bg-white z-30 w-64 md:w-64 flex flex-col">
        <!-- Sidebar Header -->
        <div class="flex items-center justify-between p-4 border-b">
            <div class="flex flex-col items-center overflow-hidden space-y-1">
                <!-- Logo 1 -->
                <img src="../logo/picart.png" alt="Logo 1" class="h-10 w-auto object-contain">

                <!-- Logo 2 -->
                <img src="../logo/noblebg.png" alt="Logo 2" class="h-10 w-auto object-contain">
            </div>

            <!-- Sidebar Toggle Button -->
            <button id="toggle-sidebar" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-primary focus:outline-none !rounded-button">
                <i id="toggle-icon" class="ri-arrow-left-s-line ri-lg"></i>
            </button>
        </div>


        <!-- Navigation Menu -->
        <nav class="flex-1 overflow-y-auto py-4">
            <ul class="space-y-1 px-3">
                <!-- Dashboard -->
                <li>
                    <a href="mainpage.php" class="menu-item flex items-center px-3 py-2 text-gray-700 rounded <?php if ($current_page == 'mainpage.php') { 
                                                                                                                        echo 'active';                                                                                                             } ?>">
                        <div class="w-6 h-6 flex items-center justify-center">
                            <i class="ri-home-line"></i>
                        </div>
                        <span class="menu-text ml-3">Dashboard</span>
                    </a>
                </li>

                <li class="pt-4">
                    <div class="menu-text px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Client Management</div>
                </li>
                <li>
                    <a href="dashboard.php" class="menu-item flex items-center px-3 py-2 text-gray-700 rounded <?php if ($current_page == 'dashboard.php') { 
                                                                                                                        echo 'active';                                                                                                             } ?>">
                        <div class="w-6 h-6 flex items-center justify-center">
                            <i class="ri-home-line"></i>
                        </div>
                        <span class="menu-text ml-3">chart</span>
                    </a>
                </li>

                <!-- Analytics -->
                <li>
                    <a href="client_management.php" class="menu-item flex items-center px-3 py-2 text-gray-700 rounded <?php if ($current_page == 'client_management.php') {
                                                                                                                            echo 'active';
                                                                                                                     } ?>">
                        <div class="w-6 h-6 flex items-center justify-center">
                            <i class="ri-bar-chart-line"></i>
                        </div>
                        <span class="menu-text ml-3">Add client</span>
                    </a>
                </li>

                <li>
                    <a href="allclient.php" class="menu-item flex items-center px-3 py-2 text-gray-700 rounded <?php if ($current_page == 'allclient.php') {
                                                                                                                            echo 'active';
                                                                                                                     } ?>">
                        <div class="w-6 h-6 flex items-center justify-center">
                            <i class="ri-bar-chart-line"></i>
                        </div>
                        <span class="menu-text ml-3">Client Information</span>
                    </a>
                </li>

                <!-- Projects -->
                <li class="pt-4">
                    <div class="menu-text px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Other</div>
                </li>

                <li>
                    <a href="projects.php" class="menu-item flex items-center px-3 py-2 text-gray-700 rounded <?php if ($current_page == 'projects.php') {
                                                                                                                    echo 'active';
                                                                                                                } ?>">
                        <div class="w-6 h-6 flex items-center justify-center">
                            <i class="ri-folder-line"></i>
                        </div>
                        <span class="menu-text ml-3">Projects</span>
                    </a>
                </li>

                <!-- Tasks -->
                <li>
                    <a href="viewmainpage.php" class="menu-item flex items-center px-3 py-2 text-gray-700 rounded <?php if ($current_page == 'viewmainpage.php') {
                                                                                                                echo 'active';
                                                                                                            } ?>">
                        <div class="w-6 h-6 flex items-center justify-center">
                            <i class="ri-task-line"></i>
                        </div>
                        <span class="menu-text ml-3">View Main Page</span>
                    </a>
                </li>

                <!-- Calendar -->
                <li>
                    <a href="calendar.php" class="menu-item flex items-center px-3 py-2 text-gray-700 rounded <?php if ($current_page == 'calendar.php') {
                                                                                                                    echo 'active';
                                                                                                                } ?>">
                        <div class="w-6 h-6 flex items-center justify-center">
                            <i class="ri-calendar-line"></i>
                        </div>
                        <span class="menu-text ml-3">Calendar</span>
                    </a>
                </li>

        

                <li class="pt-4">
                    <div class="menu-text px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Settings</div>
                </li>



                <!-- Settings -->
                <li>
                    <a href="../logout.php" class="menu-item flex items-center px-3 py-2 text-gray-700 rounded <?php if ($current_page == 'logout.php') {
                                                                                                                    echo 'active';
                                                                                                                } ?>">
                        <div class="w-6 h-6 flex items-center justify-center">
                            <i class="ri-settings-line"></i>
                        </div>
                        <span class="menu-text ml-3">Logout</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- User Profile -->
        <div class="border-t p-4">
            <a href="#" class="flex items-center space-x-3">
                <img src="https://readdy.ai/api/search-image?query=professional%20headshot%20of%20a%20young%20business%20person%20with%20a%20friendly%20smile%2C%20high%20quality%20portrait%2C%20neutral%20background&width=100&height=100&seq=1&orientation=squarish" alt="User" class="w-10 h-10 rounded-full object-cover object-top">
                <div class="menu-text">
                    <div class="text-sm font-medium text-gray-700">Emma Thompson</div>
                    <div class="text-xs text-gray-500">Product Manager</div>
                </div>
            </a>
        </div>
    </aside>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.5.0/echarts.min.js"></script>
    <script>
  document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    const toggleBtn = document.getElementById('toggle-sidebar');
    const toggleIcon = document.getElementById('toggle-icon');
    const menuTexts = document.querySelectorAll('.menu-text');
    const sidebarOverlay = document.getElementById('sidebar-overlay');

    let sidebarOpen = true;

    function toggleSidebar() {
        sidebarOpen = !sidebarOpen;

        if (sidebarOpen) {
            sidebar.style.width = '16rem'; // 256px
            toggleIcon.className = 'ri-arrow-left-s-line ri-lg';

            menuTexts.forEach(text => {
                text.style.opacity = '1';
                text.style.display = 'block';
            });

            if (mainContent) {
                mainContent.style.marginLeft = '16rem';
            }
        } else {
            sidebar.style.width = '5rem'; // 80px
            toggleIcon.className = 'ri-arrow-right-s-line ri-lg';

            menuTexts.forEach(text => {
                text.style.opacity = '0';
                text.style.display = 'none';
            });

            if (mainContent) {
                mainContent.style.marginLeft = '5rem';
            }
        }
    }

    toggleBtn.addEventListener('click', toggleSidebar);

    // Mobile overlay handling
    sidebarOverlay.addEventListener('click', function() {
        if (window.innerWidth < 768) {
            sidebar.style.transform = 'translateX(-100%)';
            sidebarOverlay.style.display = 'none';
        }
    });

    // Responsive behavior
    function handleResize() {
        if (window.innerWidth < 768) {
            sidebar.style.transform = 'translateX(-100%)';
            if (mainContent) {
                mainContent.style.marginLeft = '0';
            }
            sidebarOverlay.style.display = 'none';
        } else {
            sidebar.style.transform = 'translateX(0)';
            if (mainContent) {
                mainContent.style.marginLeft = sidebarOpen ? '16rem' : '5rem';
            }
        }
    }

    window.addEventListener('resize', handleResize);

    // Mobile menu toggle
    const mobileMenuToggle = document.createElement('button');
    mobileMenuToggle.className = 'md:hidden fixed top-4 left-4 z-40 w-10 h-10 flex items-center justify-center bg-white rounded-full shadow-md text-gray-700 !rounded-button';
    mobileMenuToggle.innerHTML = '<i class="ri-menu-line ri-lg"></i>';
    document.body.appendChild(mobileMenuToggle);

    mobileMenuToggle.addEventListener('click', function() {
        sidebar.style.transform = 'translateX(0)';
        sidebarOverlay.style.display = 'block';
    });

    // Initialize responsive behavior
    handleResize();
});
    </script>