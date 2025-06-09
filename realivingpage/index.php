<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realiving - Interior Design</title>
    <!-- For .ico file -->
    <!-- For PNG fallback (optional) -->
    <link rel="icon" type="image/png" sizes="32x32" href="../logo/favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="coupon/coupon.css">
</head>
<style>
    .hero-bg {
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        height: 90vh;
    }

    .glass-effect {
        background: rgba(0, 0, 0, 0.05);

        position: relative;
        overflow: hidden;
    }

    .glass-effect::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg,
                transparent 30%,
                rgba(255, 255, 255, 0.1) 50%,
                transparent 70%);
        animation: shimmer 3s ease-in-out infinite;
        transform: rotate(45deg);
    }

    .glass-effect::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background:
            radial-gradient(circle at 20% 30%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 70%, rgba(255, 255, 255, 0.08) 0%, transparent 50%),
            radial-gradient(circle at 40% 80%, rgba(255, 255, 255, 0.06) 0%, transparent 50%);
        animation: floatingOrbs 8s ease-in-out infinite alternate;
    }

    @keyframes shimmer {

        0%,
        100% {
            transform: rotate(45deg) translateX(-100%);
            opacity: 0;
        }

        50% {
            transform: rotate(45deg) translateX(100%);
            opacity: 1;
        }
    }

    @keyframes floatingOrbs {
        0% {
            transform: scale(1) rotate(0deg);
            opacity: 0.3;
        }

        100% {
            transform: scale(1.1) rotate(10deg);
            opacity: 0.6;
        }
    }

    .glass-button {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s ease;
    }

    .glass-button:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-2px);
        box-shadow: 0 12px 40px 0 rgba(0, 0, 0, 0.3);
    }

    .hero-bg {
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.3) 0%, rgba(0, 0, 0, 0.1) 100%);
    }

    /* Animated gradient background as fallback */
    /* Glass overlay with animated patterns */
    .glass-pattern {
        background:
            linear-gradient(45deg, rgba(255, 255, 255, 0.03) 25%, transparent 25%),
            linear-gradient(-45deg, rgba(255, 255, 255, 0.03) 25%, transparent 25%),
            linear-gradient(45deg, transparent 75%, rgba(255, 255, 255, 0.03) 75%),
            linear-gradient(-45deg, transparent 75%, rgba(255, 255, 255, 0.03) 75%);
        background-size: 60px 60px;
        background-position: 0 0, 0 30px, 30px -30px, -30px 0px;
        animation: glassPattern 20s linear infinite;
    }

    @keyframes glassPattern {
        0% {
            background-position: 0 0, 0 30px, 30px -30px, -30px 0px;
        }

        100% {
            background-position: 60px 60px, 60px 90px, 90px 30px, 30px 60px;
        }
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(25px);
        -webkit-backdrop-filter: blur(25px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 20px;
        box-shadow: 0 15px 35px 0 rgba(0, 0, 0, 0.2);
    }

    body {
        font-family: 'Montserrat', sans-serif;
    }
</style>

<body class="text-white">
    <div class="min-h-screen flex flex-col">

        <!-- Include the Navbar -->
        <?php include 'header/headernav.php'; ?>

        <main class="flex-grow flex items-center justify-between relative overflow-hidden hero-bg min-h-screen">
            <!-- Video Background -->
            <video autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover z-0">
                <source src="video/realiving.mp4" type="video/mp4" />
                Your browser does not support the video tag.
            </video>

            <!-- Glass Effect Overlay on Video with Multiple Effects -->
            <div class="absolute inset-0 glass-effect z-10">
                <div class="absolute inset-0 glass-pattern opacity-40"></div>
            </div>

            <!-- Hero Content -->
            <div class="relative z-20 p-6 md:p-16 text-white flex-1 font-[Montserrat] ml-9">
                <h1 class="text-4xl md:text-6xl font-light mb-4 leading-tight">
                    <span class="block font-bold text-white drop-shadow-lg">Transform your space</span>
                    <span class="block font-bold text-white drop-shadow-lg">with timeless design</span>
                </h1>
                <p class="mb-2 text-xs md:text-base font-semibold text-gray-200 drop-shadow underline">
                    Modern interiors, crafted with purpose and personality.
                </p>
                <button class="glass-button text-white px-8 py-3 font-medium rounded-full mt-4 hover:text-yellow-300 transition-all duration-300">
                    GET STARTED
                </button>
            </div>

            <!-- Inquiry Form -->
            <div id="inquire" class="relative z-20 p-8 md:p-8 max-w-lg hidden md:block mr-8 font-[Montserrat]">
                <div class="bg-gray-100 p-6 md:p-8">
                    <h2 class="text-2xl font-bold text-black mb-6 text-center">Inquiry</h2>
                    <!-- Font Awesome Link (if not already added) -->


                    <form class="space-y-4">
                        <!-- Full Name -->
                        <div>
                            <label class="block text-sm font-medium text-black mb-2">Full Name:</label>
                            <div class="relative">
                                <i class="fas fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="text"
                                    class="w-full pl-10 pr-4 py-3 rounded-lg text-black placeholder-gray-300 focus:outline-none transition-all duration-300"
                                    placeholder="Enter your name">
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-black mb-2">Email Address:</label>
                            <div class="relative">
                                <i class="fas fa-envelope absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="email"
                                    class="w-full pl-10 pr-4 py-3 rounded-lg text-black placeholder-gray-300 focus:outline-none transition-all duration-300"
                                    placeholder="your@email.com">
                            </div>
                        </div>

                        <!-- Contact Number -->
                        <div>
                            <label class="block text-sm font-medium text-black mb-2">Contact Number:</label>
                            <div class="relative">
                                <i class="fas fa-phone absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="tel"
                                    class="w-full pl-10 pr-4 py-3 rounded-lg text-black placeholder-gray-300 focus:outline-none transition-all duration-300"
                                    placeholder="+63 xxx xxx xxxx">
                            </div>
                        </div>

                        <!-- Project Description -->
                        <div>
                            <label class="block text-sm font-medium text-black mb-2">Project Description:</label>
                            <div class="relative">
                                <i class="fas fa-pencil-alt absolute left-3 top-4 text-gray-400"></i>
                                <textarea rows="4"
                                    class="w-full pl-10 pr-4 py-3 rounded-lg text-black text-base placeholder-gray-300 focus:outline-none resize-none transition-all duration-300"
                                    placeholder="Tell us about your interior design project..."></textarea>

                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="w-full glass-button text-black px-6 py-3 font-medium rounded-full border-3 border-blue-900 hover:text-red-300 transition-all duration-300 hover:scale-105">
                            <i class="fas fa-paper-plane mr-2"></i>SEND INQUIRY
                        </button>
                    </form>

                </div>
            </div>

            <!-- Floating Glass Elements for Extra Visual Interest -->
            <div class="absolute top-20 left-1/4 w-32 h-32 glass-effect rounded-full opacity-5 animate-pulse hidden lg:block"></div>
            <div class="absolute bottom-20 left-10 w-24 h-24 glass-effect rounded-full opacity-5 animate-bounce hidden md:block"></div>
            <div class="absolute top-1/2 left-1/3 w-16 h-16 glass-effect rounded-full opacity-5 hidden lg:block"></div>
        </main>

        <!-- Rooms Section -->
        <section class="rooms-section bg-white text-black py-16" data-aos="fade-up">
            <div class="max-w-7xl mx-auto px-4">
                <h2 class="text-4xl font-bold text-center mb-12">ROOMS</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-7">

                    <!-- Kitchen -->
                    <div class="bg-gray-100 h-80 flex flex-col">
                        <div class="h-64 overflow-hidden">
                            <img src="img/rooms/kitchen/a.jpg" alt="Kitchen" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        </div>
                        <h3 class="text-center text-lg font-medium mt-2">Kitchen</h3>
                    </div>

                    <!-- Bathroom -->
                    <div class="bg-gray-100 h-80 flex flex-col">
                        <div class="h-64 overflow-hidden">
                            <img src="img/rooms/bath/a.jpg" alt="Bathroom" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        </div>
                        <h3 class="text-center text-lg font-medium mt-2">Bathroom</h3>
                    </div>

                    <!-- Living Room -->
                    <div class="bg-gray-100 h-80 flex flex-col">
                        <div class="h-64 overflow-hidden">
                            <img src="img/rooms/livingroom/a.jpg" alt="Living Room" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        </div>
                        <h3 class="text-center text-lg font-medium mt-2">Living Room</h3>
                    </div>

                    <!-- Bedroom -->
                    <div class="bg-gray-100 h-80 flex flex-col">
                        <div class="h-64 overflow-hidden">
                            <img src="img/rooms/bed/a.jpg" alt="Bedroom" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        </div>
                        <h3 class="text-center text-lg font-medium mt-2">Bedroom</h3>
                    </div>

                </div>
            </div>
        </section>
        <!-- Cabinet Section with Background Image -->
        <section class="cabinet-section mt-5" data-aos="fade-up">
            <div class="relative bg-cover bg-center h-96" style="background-image: url('https://images.unsplash.com/photo-1556702571-3e11dd2b1a92?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80');">
                <div class="absolute inset-0 bg-black bg-opacity-40"></div>
                <div class="relative max-w-7xl mx-auto px-4 h-full flex flex-col justify-center items-center text-center">
                    <h2 class="text-3xl md:text-5xl font-bold text-white mb-2">Want a customized cabinet?</h2>
                    <p class="text-lg md:text-xl text-white mb-8">Stylish. Affordable. Ready for your space.</p>
                    <a href="#inquire" class="bg-white hover:bg-gray-100 text-gray-800 px-8 py-3 font-medium">
                        INQUIRE NOW
                    </a>
                </div>
            </div>
        </section>

        <section class="w-full max-w-8xl mx-auto mt-5">
            <div class="bg-white shadow-lg overflow-hidden">
                <!-- Main carousel container -->
                <div class="relative">
                    <h1 class="text-center text-lg md:text-4xl font-bold text-gray-800 tracking-wide font-light mb-4">

                    </h1>
                </div>

                <!-- Additional Images Section -->
                <div class="px-4 py-6">
                    <h2 class="text-2xl font-light text-gray-800 mb-4"></h2>

                    <!-- Image Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <!-- Image 1 -->
                        <div class="relative group overflow-hidden rounded-lg shadow-md">
                            <img src="img/moreonidea/a.jpg" alt="Additional idea 1" class="w-full h-[200px] object-cover transition-transform duration-300 group-hover:scale-110">
                            <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end">
                                <p class="text-white p-3 text-sm font-medium">Creative Design Idea</p>
                            </div>
                        </div>

                        <!-- Image 2 -->
                        <div class="relative group overflow-hidden rounded-lg shadow-md">
                            <img src="img/moreonidea/b.jpg" alt="Additional idea 2" class="w-full h-[200px] object-cover transition-transform duration-300 group-hover:scale-110">
                            <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end">
                                <p class="text-white p-3 text-sm font-medium">Minimalist Concept</p>
                            </div>
                        </div>

                        <!-- Image 3 -->
                        <div class="relative group overflow-hidden rounded-lg shadow-md">
                            <img src="img/moreonidea/c.jpg" alt="Additional idea 3" class="w-full h-[200px] object-cover transition-transform duration-300 group-hover:scale-110">
                            <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end">
                                <p class="text-white p-3 text-sm font-medium">Nature Inspired</p>
                            </div>
                        </div>

                        <!-- Image 4 -->
                        <div class="relative group overflow-hidden rounded-lg shadow-md">
                            <img src="img/moreonidea/d.jpg" alt="Additional idea 4" class="w-full h-[200px] object-cover transition-transform duration-300 group-hover:scale-110">
                            <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end">
                                <p class="text-white p-3 text-sm font-medium">Modern Architecture</p>
                            </div>
                        </div>

                        <!-- Image 5 -->
                        <div class="relative group overflow-hidden rounded-lg shadow-md">
                            <img src="img/moreonidea/d.jpg" alt="Additional idea 5" class="w-full h-[200px] object-cover transition-transform duration-300 group-hover:scale-110">
                            <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end">
                                <p class="text-white p-3 text-sm font-medium">Industrial Style</p>
                            </div>
                        </div>

                        <!-- Image 6 -->
                        <div class="relative group overflow-hidden rounded-lg shadow-md">
                            <img src="img/moreonidea/e.jpg" alt="Additional idea 6" class="w-full h-[200px] object-cover transition-transform duration-300 group-hover:scale-110">
                            <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end">
                                <p class="text-white p-3 text-sm font-medium">Rustic Elements</p>
                            </div>
                        </div>

                        <!-- Image 7 -->
                        <div class="relative group overflow-hidden rounded-lg shadow-md">
                            <img src="img/moreonidea/f.jpg" alt="Additional idea 7" class="w-full h-[200px] object-cover transition-transform duration-300 group-hover:scale-110">
                            <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end">
                                <p class="text-white p-3 text-sm font-medium">Eco-Friendly Design</p>
                            </div>
                        </div>

                        <!-- Image 8 -->
                        <div class="relative group overflow-hidden rounded-lg shadow-md">
                            <img src="img/moreonidea/g.jpg" alt="Additional idea 8" class="w-full h-[200px] object-cover transition-transform duration-300 group-hover:scale-110">
                            <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end">
                                <p class="text-white p-3 text-sm font-medium">Contemporary Fusion</p>
                            </div>
                        </div>
                    </div>

                    <!-- View All Ideas Button -->
                    <div class="mt-6 flex justify-center">
                        <button class="bg-brown-900 text-white font-semibold px-8 py-2 rounded-full shadow-md hover:bg-blue-600 hover:text-gray-500 transition duration-300 ease-in-out" style="background-color: #3b1d0e;">
                            View All Ideas
                        </button>
                    </div>
                </div>
            </div>
        </section>


        <section class="text-black">
            <div class="text-center my-12">
                <h2 class="text-3xl font-light tracking-widest text-gray-800">TOP MODULAR CABINETS</h2>
            </div>

            <!-- Semantic version -->
            <div class="col-span-1 md:col-span-2 lg:col-span-4 flex justify-center">
                <a href="page/modular/allmodular.php" class="border-2 border-black  px-8 py-2 text-sm hover:bg-red-200 transition-colors">
                    ALL MODULAR CABINET
                </a>
            </div>

            <div class="col-span-1 md:col-span-2 lg:col-span-4 flex justify-center mt-5 font-semibold">

                KITCHEN CABINET

            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-6 mt-8 mr-5 ml-5">
                <!-- Cabinet 1 -->
                <div class="text-center">
                    <div class=" p-4 mb-4 rounded-lg h-[300px]">
                        <img src="img/topmodularcabinet/baseCabinet/a.jpg" alt="Cabinet 1" class="w-full h-full object-cover rounded-md">
                    </div>
                    <h3 class="text-lg font-medium mb-3">BASE CABINET</h3>
                    <button class="border-2 border-black px-8 py-2 text-sm hover:bg-red-200 transition-colors">GET PRICE</button>
                </div>

                <!-- Cabinet 2 -->
                <div class="text-center">
                    <div class=" p-4 mb-4 rounded-lg h-[300px]">
                        <img src="img/topmodularcabinet/WallCabinet/a.webp" alt="Cabinet 2" class="w-full h-full object-cover rounded-md">
                    </div>
                    <h3 class="text-lg font-medium mb-3">WALL CABINET</h3>
                    <button class="border-2 border-black px-8 py-2 text-sm hover:bg-red-200 transition-colors">GET PRICE</button>
                </div>

                <!-- Cabinet 3 -->
                <div class="text-center">
                    <div class=" p-4 mb-4 rounded-lg h-[300px]">
                        <img src="img/topmodularcabinet/tallCabinet/a.jpg" alt="Cabinet 3" class="w-full h-full object-cover rounded-md">
                    </div>
                    <h3 class="text-lg font-medium mb-3">TALLCABINET</h3>
                    <button class="border-2 border-black px-8 py-2 text-sm hover:bg-red-200 transition-colors">GET PRICE</button>
                </div>

                <!-- Cabinet 4 -->
                <div class="text-center">
                    <div class=" p-4 mb-4 rounded-lg h-[300px]">
                        <img src="img/topmodularcabinet/CornerCabinet/a.jpg" alt="Cabinet 4" class="w-full h-full object-cover rounded-md">
                    </div>
                    <h3 class="text-lg font-medium mb-3">CORNERCABINET</h3>
                    <button class="border-2 border-black  px-8 py-2 text-sm hover:bg-red-200 transition-colors">GET PRICE</button>
                </div>
                <!-- Cabinet 4 -->
                <div class="text-center">
                    <div class=" p-4 mb-4 rounded-lg h-[300px]">
                        <img src="img/topmodularcabinet/sinkbaseCabinet/a.jpg" alt="Cabinet 5" class="w-full h-full object-cover rounded-md">
                    </div>
                    <h3 class="text-lg font-medium mb-3">SINK BASE CABINET</h3>
                    <button class="border-2 border-black  px-8 py-2 text-sm hover:bg-red-200 transition-colors">GET PRICE</button>
                </div>
            </div>
            <hr>

            <div class="col-span-1 md:col-span-2 lg:col-span-4 flex justify-center mt-9 font-semibold">

                BATHROOM CABINET

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6 mt-8 mr-5 ml-5">
                <!-- Cabinet 1 -->
                <div class="text-center">
                    <div class=" p-4 mb-4 rounded-lg h-[300px]">
                        <img src="img/topmodularcabinet/bathroomcabinet/vanitycabinet/a.jpg" alt="Cabinet 1" class="w-full h-full object-cover rounded-md">
                    </div>
                    <h3 class="text-lg font-medium mb-3">VANITY CABINET</h3>
                    <button class="border-2 border-black px-8 py-2 text-sm hover:bg-red-200 transition-colors">GET PRICE</button>
                </div>

                <!-- Cabinet 2 -->
                <div class="text-center">
                    <div class=" p-4 mb-4 rounded-lg h-[300px]">
                        <img src="img/topmodularcabinet/bathroomcabinet/medicinecabinet/a.avif" alt="Cabinet 2" class="w-full h-full object-cover rounded-md">
                    </div>
                    <h3 class="text-lg font-medium mb-3">MEDICINE CABINET</h3>
                    <button class="border-2 border-black px-8 py-2 text-sm hover:bg-red-200 transition-colors">GET PRICE</button>
                </div>

                <!-- Cabinet 3 -->
                <div class="text-center">
                    <div class=" p-4 mb-4 rounded-lg h-[300px]">
                        <img src="img/topmodularcabinet/bathroomcabinet/linencabinet/a.jpg" alt="Cabinet 3" class="w-full h-full object-cover rounded-md">
                    </div>
                    <h3 class="text-lg font-medium mb-3">CABINET</h3>
                    <button class="border-2 border-black px-8 py-2 text-sm hover:bg-red-200 transition-colors">GET PRICE</button>
                </div>
            </div>


            <!-- Advertisement Banner -->
            <div class="relative w-full h-[100px] bg-cover bg-center" style="background-image: url('img/coupon.jpg');">
                <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                    <div class="text-center text-white p-6 bg-white/10 backdrop-blur-md rounded-xl shadow-lg w-[500px]">
                        <div class="text-md font-extrabold text-white mb-2  tracking-wide">
                            25% OFF WHEN YOUR INQUIRE!
                        </div>
                    </div>
                </div>
            </div>

        </section>


        <section class="py-16 bg-gray-200 text-gray-800">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-light mb-4">Our Services</h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">Crafted for comfort. Delivered with care.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- Service 1 -->
                    <div class=" p-6 rounded-lg">
                        <div class="mb-6">
                            <img src="img/ourservices/real.png" alt="Design Service" class="w-full h-[180px] rounded-lg">
                        </div>
                        <h3 class="text-xl font-medium mb-3 text-center">Design</h3>
                        <p class="text-gray-600 text-center">We create smart, space-saving, and stylish designs tailored to your space and lifestyle needs.</p>
                    </div>

                    <!-- Service 2 -->
                    <div class=" p-6 rounded-lg">
                        <div class="mb-6">
                            <img src="img/ourservices/noblehome.png" alt="Fabricate Service" class="w-full h-[180px] rounded-lg">
                        </div>
                        <h3 class="text-xl font-medium mb-3 text-center">Fabricate</h3>
                        <p class="text-gray-600 text-center">Using quality materials, we build each piece with precision to ensure durability and a modern finish.</p>
                    </div>

                    <!-- Service 3 -->
                    <div class=" p-6 rounded-lg">
                        <div class="mb-6">
                            <img src="img/ourservices/deli.png" alt="Delivery Service" class="w-full h-[180px] rounded-lg">
                        </div>
                        <h3 class="text-xl font-medium mb-3 text-center">Delivery</h3>
                        <p class="text-gray-600 text-center">We transport your furniture safely and on time—straight to your doorstep.</p>
                    </div>

                    <!-- Service 4 -->
                    <div class=" p-6 rounded-lg">
                        <div class="mb-6">
                            <img src="img/ourservices/installer.png" alt="Installation Service" class="w-full h-[180px] rounded-lg">
                        </div>
                        <h3 class="text-xl font-medium mb-3 text-center">Installation</h3>
                        <p class="text-gray-600 text-center">Our team handles the setup efficiently, making sure everything is perfectly fitted and ready to use.</p>
                    </div>
                </div>

                <div class="text-center mt-12">
                    <button class="bg-gray-800 text-white px-8 py-3 font-medium hover:bg-gray-700 transition duration-300">VIEW SERVICES</button>
                </div>
            </div>
        </section>

        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Section Header -->
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 sm:text-4xl mb-4">What Clients Say</h2>
                    <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                        Don't just take our word for it. Here's what our valued clients have to say about their experience with Realiving Design Center Corp.
                    </p>
                </div>

                <!-- Testimonials Container -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Testimonial 1 -->
                    <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
                        <!-- Stars -->
                        <div class="flex mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2 text-gray-900">Exceptional Design Service</h3>
                        <p class="text-gray-600 mb-6">Working with Realiving Design Center was a game-changer for our home renovation. Their attention to detail and creative solutions exceeded our expectations.</p>
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center mr-3">
                                <span class="text-gray-600 font-bold">MS</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Maria Santos</p>
                                <p class="text-sm text-gray-500">April 15, 2025</p>
                            </div>
                        </div>
                    </div>

                    <!-- Testimonial 2 -->
                    <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
                        <!-- Stars -->
                        <div class="flex mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2 text-gray-900">Professional and Reliable</h3>
                        <p class="text-gray-600 mb-6">The team at Realiving delivered our project on time and within budget. Their communication throughout the process made everything stress-free.</p>
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center mr-3">
                                <span class="text-gray-600 font-bold">JW</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">James Wilson</p>
                                <p class="text-sm text-gray-500">March 22, 2025</p>
                            </div>
                        </div>
                    </div>

                    <!-- Testimonial 3 -->
                    <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
                        <!-- Stars -->
                        <div class="flex mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2 text-gray-900">Transformed Our Space</h3>
                        <p class="text-gray-600 mb-6">We couldn't be happier with the results. Realiving understood our vision perfectly and brought it to life with their expertise and innovative ideas.</p>
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center mr-3">
                                <span class="text-gray-600 font-bold">EM</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Elena Morales</p>
                                <p class="text-sm text-gray-500">February 10, 2025</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Controls -->
                <div class="flex justify-center items-center mt-12 space-x-2">
                    <button class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-l">
                        PREVIOUS
                    </button>
                    <div class="h-1 bg-gray-300 w-64 mx-4 rounded-full">
                        <div class="h-1 bg-gray-800 w-1/3 rounded-full"></div>
                    </div>
                    <button class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-r">
                        NEXT
                    </button>
                </div>
            </div>
        </section>

        <!-- Latest News Section -->
        <section class="py-16 px-6 bg-brown-900" style="background-color: #3b1d0e;">
            <h2 class="text-4xl font-bold text-white text-center mb-12">Latest News</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- News Card 1 -->
                <div class="bg-white rounded-lg overflow-hidden">
                    <img src="/api/placeholder/400/300" alt="Modern kitchen with island" class="w-full h-64 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-semibold mb-2">News Title</h3>
                        <p class="text-gray-600 text-sm mb-4">Lorem ipsum dolor sit amet consectetur adipiscing elit. Dolor sit amet consectetur adipiscing elit quisque faucibus.</p>
                        <button class="bg-brown-900 text-white uppercase text-sm font-medium py-2 px-4 rounded" style="background-color: #3b1d0e;">READ MORE</button>
                    </div>
                </div>

                <!-- News Card 2 -->
                <div class="bg-white rounded-lg overflow-hidden">
                    <img src="/api/placeholder/400/300" alt="Modern kitchen with island" class="w-full h-64 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-semibold mb-2">News Title</h3>
                        <p class="text-gray-600 text-sm mb-4">Lorem ipsum dolor sit amet consectetur adipiscing elit. Dolor sit amet consectetur adipiscing elit quisque faucibus.</p>
                        <button class="bg-brown-900 text-white uppercase text-sm font-medium py-2 px-4 rounded" style="background-color: #3b1d0e;">READ MORE</button>
                    </div>
                </div>

                <!-- News Card 3 -->
                <div class="bg-white rounded-lg overflow-hidden">
                    <img src="/api/placeholder/400/300" alt="Modern kitchen with island" class="w-full h-64 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-semibold mb-2">News Title</h3>
                        <p class="text-gray-600 text-sm mb-4">Lorem ipsum dolor sit amet consectetur adipiscing elit. Dolor sit amet consectetur adipiscing elit quisque faucibus.</p>
                        <button class="bg-brown-900 text-white uppercase text-sm font-medium py-2 px-4 rounded" style="background-color: #3b1d0e;">READ MORE</button>
                    </div>
                </div>

                <!-- News Card 4 -->
                <div class="bg-white rounded-lg overflow-hidden">
                    <img src="/api/placeholder/400/300" alt="Modern kitchen with island" class="w-full h-64 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-semibold mb-2">News Title</h3>
                        <p class="text-gray-600 text-sm mb-4">Lorem ipsum dolor sit amet consectetur adipiscing elit. Dolor sit amet consectetur adipiscing elit quisque faucibus.</p>
                        <button class="bg-brown-900 text-white uppercase text-sm font-medium py-2 px-4 rounded" style="background-color: #3b1d0e;">READ MORE</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Navigation Controls Bottom -->
        <div class="flex justify-between items-center px-6 py-4 bg-brown-900" style="background-color: #3b1d0e;">
            <button class="font-medium text-white">PREVIOUS</button>
            <div class="h-0.5 flex-1 mx-4 bg-gray-600"></div>
            <button class="font-medium text-white">NEXT</button>
        </div>


        <!-- Footer -->
        <footer class="bg-[#faf6f0] py-8 px-4 md:px-12 text-black">
            <div class="flex flex-col md:flex-row justify-between items-start mb-8">
                <!-- Logo and Contact Info -->
                <div class="mb-6 md:mb-0 md:w-1/3">
                    <div class="mb-6">
                        <span class="text-4xl font-bold text-[#0096c7]">Real</span><span class="text-4xl font-bold text-[#f59e0b]">Living</span>
                        <p class="text-xs text-gray-500">DESIGN · CENTER · CORPORATION</p>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center">
                            <div class="w-6 h-6 rounded-full bg-[#f59e0b] flex items-center justify-center mr-3">
                                <span class="text-white text-xs">☏</span>
                            </div>
                            <span class="text-sm">(+63) 912 345 6789</span>
                        </div>

                        <div class="flex items-center">
                            <div class="w-6 h-6 rounded-full bg-[#f59e0b] flex items-center justify-center mr-3">
                                <span class="text-white text-xs">✉</span>
                            </div>
                            <span class="text-sm">info@realliving.com</span>
                        </div>

                        <div class="flex items-center">
                            <div class="w-6 h-6 rounded-full bg-[#f59e0b] flex items-center justify-center mr-3">
                                <span class="text-white text-xs">◎</span>
                            </div>
                            <span class="text-sm">MC Premier-EDSA Balintawak, Quezon City</span>
                        </div>

                        <div class="flex items-center">
                            <div class="w-6 h-6 rounded-full bg-[#f59e0b] flex items-center justify-center mr-3">
                                <span class="text-white text-xs">⏱</span>
                            </div>
                            <span class="text-sm">Mon-Fr: 7AM-5PM | Sat 7AM-12PM</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Links Section -->
                <div class="mb-6 md:mb-0 md:w-1/3">
                    <h3 class="text-lg font-semibold mb-4 text-[#3c1f0e]">Quick Links</h3>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <ul class="space-y-2">
                                <li><a href="#" class="text-sm hover:text-[#f59e0b] transition-colors duration-200">Home</a></li>
                                <li><a href="#" class="text-sm hover:text-[#f59e0b] transition-colors duration-200">About Us</a></li>
                                <li><a href="#" class="text-sm hover:text-[#f59e0b] transition-colors duration-200">Services</a></li>
                                <li><a href="#" class="text-sm hover:text-[#f59e0b] transition-colors duration-200">Projects</a></li>
                            </ul>
                        </div>
                        <div>
                            <ul class="space-y-2">
                                <li><a href="#" class="text-sm hover:text-[#f59e0b] transition-colors duration-200">Appointment</a></li>
                                <li><a href="#" class="text-sm hover:text-[#f59e0b] transition-colors duration-200">Contact</a></li>
                                <li><a href="#" class="text-sm hover:text-[#f59e0b] transition-colors duration-200">FAQ</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Social Media Links -->
                <div class="md:w-1/4">
                    <h3 class="text-lg font-semibold mb-4 text-[#3c1f0e]">Follow us</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-700 hover:text-[#f59e0b] transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-700 hover:text-[#f59e0b] transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-700 hover:text-[#f59e0b] transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-700 hover:text-[#f59e0b] transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z" />
                            </svg>
                        </a>
                    </div>

                    <!-- Newsletter Signup -->
                    <div class="mt-6">
                        <h3 class="text-sm font-medium mb-2 text-[#3c1f0e]">Subscribe to our newsletter</h3>
                        <div class="flex">
                            <input type="email" placeholder="Your email" class="px-3 py-2 text-sm bg-white border border-gray-300 rounded-l-md focus:outline-none focus:ring-1 focus:ring-[#f59e0b] w-full" />
                            <button class="bg-[#f59e0b] text-white px-3 py-2 rounded-r-md hover:bg-[#e59000] transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Copyright -->
            <div class="border-t border-gray-300 pt-4 text-center">
                <p class="text-xs uppercase text-gray-700">All rights reserved</p>
            </div>
        </footer>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="js/js_index//carosel.js"></script>
    <script>
        AOS.init();
    </script>

</body>

</html>