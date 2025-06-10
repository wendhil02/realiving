
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css ">
<style>
        header {
        font-family: 'Montserrat', sans-serif;
    }
</style>
<header class="sticky top-0 text-black bg-gray-100 shadow-md relative z-50 bg-cover bg-center font-[Montserrat]">

    <nav class="flex justify-between items-end py-4 px-6 md:px-5">
        <!-- Logo -->
        <div class="flex items-center space-x-3">
            <a href="#">
                <img src="img/logo.png" alt="Realiving Logo" class="h-10">
            </a>
        </div>


        <!-- Desktop Navigation + Get Quote Button -->
        <div class="hidden md:flex items-center space-x-5 text-orange-900 font-[montserrat]">
            <!-- Navigation Links -->
            <div class="flex space-x-5">
                <a href="index" class="flex items-center hover:text-yellow-500">
                    HOME
                </a>
                <a href="product_cabinet" class="flex items-center hover:text-yellow-500">
                    CABINET
                </a>
                <a href="diymodular" class="flex items-center hover:text-yellow-500">
                    DIY MODULAR
                </a>
                <a href="all-projects" class="flex items-center hover:text-yellow-500">
                    PROJECTS
                </a>
                <a href="whatnew" class="flex items-center hover:text-yellow-500">
                    WHAT'S NEW
                </a>
                <a href="contact" class="flex items-center hover:text-yellow-500">
                    CONTACT
                </a>
                <a href="header/billingpage/billing" class="flex items-center hover:text-yellow-500">
                    BILLING
                </a>
                <a href="about" class="flex items-center hover:text-yellow-500">
                    ABOUT
                </a>
            </div>

            <!-- Get Quote Button -->
            <button class="ml-6 bg-white text-black px-6 py-2 font-medium flex items-center hover:bg-orange-900 hover:text-white">
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
    <div id="mobile-menu" class="hidden md:hidden bg-white border-t p-4 text-orange-900 font-[montserrat]">
        <div class="flex flex-col space-y-4">
            <a href="index" class="flex items-center hover:text-yellow-500">
                <i class="fas fa-home mr-2 w-6"></i>HOME
            </a>
            <a href="#" class="flex items-center hover:text-yellow-500">
                SERVICES
            </a>
            <a href="all-project" class="flex items-center hover:text-yellow-500">
                PROJECTS
            </a>
            <a href="#" class="flex items-center hover:text-yellow-500">
                WHAT'S NEW
            </a>
            <a href="contact" class="flex items-center hover:text-yellow-500">
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