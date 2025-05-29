
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<header class="sticky top-0 text-black bg-gray-100 shadow-md relative z-50 bg-cover bg-center">

    <nav class="flex justify-between items-end py-4 px-6 md:px-5">
        <!-- Logo -->
        <div class="flex items-center">
            <a href="#">
                <img src="img/logo.png" alt="Realiving Logo" class="h-10">
            </a>
        </div>

        <!-- Desktop Navigation + Get Quote Button -->
        <div class="hidden md:flex items-center space-x-8">
            <!-- Navigation Links -->
            <div class="flex space-x-7">
                <a href="#" class="flex items-center hover:text-yellow-500">
                    <i class="fas fa-home mr-2"></i>HOME
                </a>
                <a href="#" class="flex items-center hover:text-yellow-500">
                    PROJECTS
                </a>
                <a href="#" class="flex items-center hover:text-yellow-500">
                   WHAT'S NEW
                </a>
                <a href="#" class="flex items-center hover:text-yellow-500">
                   CONTACT
                </a>
                 <a href="#" class="flex items-center hover:text-yellow-500">
                   BILLING
                </a>
                    <a href="#" class="flex items-center hover:text-yellow-500">
                    ABOUT
                </a>
            </div>

            <!-- Get Quote Button -->
            <button class="ml-6 bg-yellow-400 text-black px-6 py-2 font-medium flex items-center">
                <i class="fas fa-file-invoice-dollar mr-2"></i>GET QUOTE
            </button>
        </div>

        <!-- Mobile Menu Button -->
        <div class="md:hidden">
            <button id="mobile-menu-button" class="text-black">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-white border-t p-4">
        <div class="flex flex-col space-y-4">
            <a href="#" class="flex items-center hover:text-yellow-500">
                <i class="fas fa-home mr-2 w-6"></i>HOME
            </a>
            <a href="#" class="flex items-center hover:text-yellow-500">
                SERVICES
            </a>
            <a href="#" class="flex items-center hover:text-yellow-500">
              PROJECTS
            </a>
            <a href="#" class="flex items-center hover:text-yellow-500">
              WHAT'S NEW
            </a>
            <a href="#" class="flex items-center hover:text-yellow-500">
               CONTACT
            </a>
                <a href="#" class="flex items-center hover:text-yellow-500">
                <i class="fas fa-info-circle mr-2 w-6"></i>ABOUT
            </a>
            <button class="bg-yellow-400 text-black px-6 py-2 font-medium w-full flex items-center justify-center">
                <i class="fas fa-file-invoice-dollar mr-2"></i>GET QUOTE
            </button>
        </div>
    </div>
</header>



<script>
        // Mobile menu toggle functionality
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });
</script>