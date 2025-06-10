<?php session_start(); ?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realiving - Interior Design</title>

    <!-- For PNG fallback (optional) -->
    <link rel="icon" type="image/png" sizes="32x32" href="../logo/favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
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
</style>
<script>
    module.exports = {
        theme: {
            extend: {
                fontFamily: {
                    montserrat: ['Montserrat', 'serif'],
                    crimson: ['Crimson Pro', 'serif'],
                },
            },
        },
    }
</script>

<body class="text-white">
    <div class="min-h-screen flex flex-col">

        <!-- Include the Navbar -->
        <?php include 'header/headernav.php'; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div id="alert-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 transition-opacity duration-500">
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php elseif (isset($_SESSION['error'])): ?>
            <div id="alert-error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 transition-opacity duration-500">
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <script>
            setTimeout(() => {
                const success = document.getElementById('alert-success');
                const error = document.getElementById('alert-error');
                if (success) {
                    success.classList.add('opacity-0');
                    setTimeout(() => success.remove(), 500);
                }
                if (error) {
                    error.classList.add('opacity-0');
                    setTimeout(() => error.remove(), 500);
                }
            }, 2000);
        </script>


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
            <div class="relative z-20 p-6 md:p-16 flex-1 font-[crimson] ml-9">
                <h1 class="text-4xl md:text-6xl font-light mb-4 leading-tight">
                    <span class="block font-bold text-white drop-shadow-lg">Transform your space</span>
                    <span class="block font-bold text-white drop-shadow-lg">with timeless design</span>
                </h1>
                <p class="mb-2 text-xs md:text-base font-semibold text-gray-200 drop-shadow underline">
                    Modern interiors, crafted with purpose and personality.
                </p>
            </div>

            <!-- Inquiry Form -->
            <div id="inquire" class="relative z-20 p-8 md:p-8 max-w-lg hidden md:block mr-8 font-[Montserrat]">
                <div class="bg-gray-100 p-6 md:p-8">
                    <h2 class="text-2xl font-bold text-orange-900 mb-6 text-center">Inquiry</h2>
                    <!-- Font Awesome Link (if not already added) -->


                    <form class="space-y-4" action="backend/index/submit_inquiry.php" method="POST">
                        <!-- Full Name -->
                        <div>
                            <label class="block text-sm font-medium text-black mb-2">Full Name:</label>
                            <div class="relative">
                                <i class="fas fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="text" name="full_name"
                                    class="w-full pl-10 pr-4 py-3 rounded-lg text-black placeholder-gray-300 focus:outline-none transition-all duration-300"
                                    placeholder="Enter your name">
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-black mb-2">Email Address:</label>
                            <div class="relative">
                                <i class="fas fa-envelope absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="email" name="email"
                                    class="w-full pl-10 pr-4 py-3 rounded-lg text-black placeholder-gray-300 focus:outline-none transition-all duration-300"
                                    placeholder="your@email.com">
                            </div>
                        </div>

                        <!-- Contact Number -->
                        <div>
                            <label class="block text-sm font-medium text-black mb-2">Contact Number:</label>
                            <div class="relative">
                                <i class="fas fa-phone absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="tel" name="phone_number"
                                    class="w-full pl-10 pr-4 py-3 rounded-lg text-black placeholder-gray-300 focus:outline-none transition-all duration-300"
                                    placeholder="+63 xxx xxx xxxx">
                            </div>
                        </div>

                        <!-- Project Description -->
                        <div>
                            <label class="block text-sm font-medium text-black mb-2">Project Description:</label>
                            <div class="relative">
                                <i class="fas fa-pencil-alt absolute left-3 top-4 text-gray-400"></i>
                                <textarea name="project_description" rows="4"
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

        <section class="py-[50px]  text-orange-900 mr-20 ml-20" data-aos="fade-up">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12 ">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4 font-[montsarrat]">Our Services</h2>
                    <div class="h-0 bg-orange-900 w-[420px] mx-auto  p-[1px] flex"></div>
                    <p class="text-gray-600 max-w-2xl mx-auto mt-2">Crafted for comfort. Delivered with care.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 ">
                    <!-- Service 1 -->
                    <div class=" p-6 rounded-lg border border-gray-300 p-6 shadow-lg bg-white hover:-translate-y-3 transition-transform duration-300">
                        <div class="mb-6">
                            <img src="img/ourservices/Design.png" alt="Design Service" class="w-full h-[180px] rounded-lg "data-aos="fade-up" data-aos-delay="100">
                        </div>
                        <h3 class="text-xl font-medium mb-3 text-center">Design</h3>
                        <p class="text-gray-600 text-center">We create smart, space-saving, and stylish designs tailored to your space and lifestyle needs.</p>
                    </div>

                    <!-- Service 2 -->
                    <div class=" p-6 border border-gray-300 p-6 rounded-md shadow-lg bg-white hover:-translate-y-3 transition-transform duration-300">
                        <div class="mb-6">
                            <img src="img/ourservices/Fabricate.png" alt="Fabricate Service" class="w-full h-[180px] rounded-lg "data-aos="fade-down" data-aos-delay="100">
                        </div>
                        <h3 class="text-xl font-medium mb-3 text-center">Fabricate</h3>
                        <p class="text-gray-600 text-center">Using quality materials, we build each piece with precision to ensure durability and a modern finish.</p>
                    </div>

                    <!-- Service 3 -->
                    <div class=" p-6 border border-gray-300 p-6 rounded-md shadow-lg bg-white hover:-translate-y-3 transition-transform duration-300">
                        <div class="mb-6">
                            <img src="img/ourservices/Delivery.png" alt="Delivery Service" class="w-full h-[180px] rounded-lg "data-aos="fade-up" data-aos-delay="100">
                        </div>
                        <h3 class="text-xl font-medium mb-3 text-center">Delivery</h3>
                        <p class="text-gray-600 text-center">We transport your furniture safely and on time—straight to your doorstep.</p>
                    </div>

                    <!-- Service 4 -->
                    <div class=" p-6 border border-gray-300 p-6 rounded-md shadow-lg bg-white hover:-translate-y-3 transition-transform duration-300">
                        <div class="mb-6">
                            <img src="img/ourservices/Installation.png" alt="Installation Service" class="w-full h-[180px] rounded-lg "data-aos="fade-down" data-aos-delay="100">
                        </div>
                        <h3 class="text-xl font-medium mb-3 text-center">Installation</h3>
                        <p class="text-gray-600 text-center">Our team handles the setup efficiently, making sure everything is perfectly fitted and ready to use.</p>
                    </div>
                </div>

                <div class="text-center mt-12">
                    <a href="services">
                        <button class="bg-white text-black border border-orange-900 px-8 py-3 font-medium hover:bg-orange-900 transition duration-300 hover:text-white"> VIEW SERVICES</button>
                    </a>
                </div>
            </div>
        </section>

        <!-- Rooms Section -->
        <section class="rooms-section text-orange-900 py-16 font-[crimson]" data-aos="fade-up">
            <div class="max-w-7xl mx-auto px-4">
                <h2 class="text-3xl font-bold text-center mb-12 " data-aos="fade-up" data-aos-delay="100">ROOMS</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-7">

                    <!-- Kitchen -->
                    <div class=" h-80 flex flex-col">
                        <div class="h-64 overflow-hidden">
                            <img src="img/rooms/kitchen/a.jpg" alt="Kitchen" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        </div>
                        <h3 class="text-center text-lg font-medium mt-2" data-aos="fade-up" data-aos-delay="100">Kitchen</h3>
                    </div>

                    <!-- Bathroom -->
                    <div class=" h-80 flex flex-col">
                        <div class="h-64 overflow-hidden">
                            <img src="img/rooms/bath/a.jpg" alt="Bathroom" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        </div>
                        <h3 class="text-center text-lg font-medium mt-2" data-aos="fade-up" data-aos-delay="100">Bathroom</h3>
                    </div>

                    <!-- Living Room -->
                    <div class=" h-80 flex flex-col">
                        <div class="h-64 overflow-hidden">
                            <img src="img/rooms/livingroom/a.jpg" alt="Living Room" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        </div>
                        <h3 class="text-center text-lg font-medium mt-2" data-aos="fade-up" data-aos-delay="100">Living Room</h3>
                    </div>

                    <!-- Bedroom -->
                    <div class=" h-80 flex flex-col">
                        <div class="h-64 overflow-hidden">
                            <img src="img/rooms/bed/a.jpg" alt="Bedroom" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        </div>
                        <h3 class="text-center text-lg font-medium mt-2"data-aos="fade-up" data-aos-delay="100">Bedroom</h3>
                    </div>

                </div>
            </div>
        </section>
        <!-- Cabinet Section with Background Image -->
        <section class="cabinet-section mt-5" data-aos="fade-up">
            <div class="relative bg-cover bg-center h-96" style="background-image: url('https://images.unsplash.com/photo-1556702571-3e11dd2b1a92?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80');">
                <div class="absolute inset-0 bg-black bg-opacity-40"></div>
                <div class="relative max-w-7xl mx-auto px-4 h-full flex flex-col justify-center items-center text-center">
                    <h2 class="text-3xl md:text-5xl font-bold text-white mb-2 font-[montserrat]">Want a customized cabinet?</h2>
                    <p class="text-lg md:text-xl text-white mb-8">Stylish. Affordable. Ready for your space.</p>
                    <a href="#inquire" class="bg-white text-orange-900 hover:text-white hover:scale-105 transition-all duration-300 px-8 py-3 font-semibold hover:bg-orange-900">
                        INQUIRE NOW
                    </a>

                </div>
            </div>
        </section>

        <section class="text-black">
            <div class="text-center my-12">
                <h2 class="text-3xl font-light tracking-widest text-orange-900">TOP MODULAR CABINETS</h2>
            </div>

            <!-- Semantic version -->
            <div class="col-span-1 md:col-span-2 lg:col-span-4 flex justify-center">
                <a href="product_cabinet" class="border-2 border-black font-[montserrat]  rounded-full px-8 py-2 text-sm hover:bg-red-200 transition-colors">
                    ALL MODULAR CABINET
                </a>
            </div>

            <div class="col-span-1 md:col-span-2 lg:col-span-4 flex justify-center mt-5 font-bold text-orange-900 font-[crimson]">

                KITCHEN CABINET

            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-6 mt-8 mr-5 ml-5 font-[crimson] text-orange-900">
                <!-- Cabinet 1 -->
                <div class="text-center">
                    <div class=" p-4 mb-4 rounded-lg h-[300px]">
                        <img src="img/topmodularcabinet/baseCabinet/a.png" alt="Cabinet 1" class="w-full h-full object-cover rounded-md" data-aos="fade-up" data-aos-delay="300">
                    </div>
                    <h3 class="text-lg font-medium mb-3">BASE CABINET</h3>
                    <button class="border-2 border-black px-8 py-2 text-sm hover:bg-orange-900 transition-colors hover:text-white"data-aos="fade-up" data-aos-delay="300">Not Available for Now</button>
                </div>

                <!-- Cabinet 2 -->
                <div class="text-center">
                    <div class=" p-4 mb-4 rounded-lg h-[300px]">
                        <img src="img/topmodularcabinet/wallcabinet/a.png" alt="Cabinet 2" class="w-full h-full object-cover rounded-md" data-aos="fade-up" data-aos-delay="300">
                    </div>
                    <h3 class="text-lg font-medium mb-3">WALL CABINET</h3>
                    <button class="border-2 border-black px-8 py-2 text-sm hover:bg-orange-900 transition-colors hover:text-white" data-aos="fade-up" data-aos-delay="300">Not Available for Now</button>
                </div>

                <!-- Cabinet 3 -->
                <div class="text-center">
                    <div class=" p-4 mb-4 rounded-lg h-[300px]">
                        <img src="img/topmodularcabinet/tall/a.png" alt="Cabinet 3" class="w-full h-full object-cover rounded-md" data-aos="fade-up" data-aos-delay="300">
                    </div>
                    <h3 class="text-lg font-medium mb-3">TALLCABINET</h3>
                    <button class="border-2 border-black px-8 py-2 text-sm hover:bg-orange-900 transition-colors hover:text-white" data-aos="fade-up" data-aos-delay="300">Not Available for Now</button>
                </div>

                <!-- Cabinet 4 -->
                <div class="text-center">
                    <div class=" p-4 mb-4 rounded-lg h-[300px]">
                        <img src="img/topmodularcabinet/corner/a.png" alt="Cabinet 4" class="w-full h-full object-cover rounded-md" data-aos="fade-up" data-aos-delay="300">
                    </div>
                    <h3 class="text-lg font-medium mb-3">CORNERCABINET</h3>
                    <button class="border-2 border-black  px-8 py-2 text-sm hover:bg-orange-900 transition-colors hover:text-white" data-aos="fade-up" data-aos-delay="300">Not Available for Now</button>
                </div>
                <!-- Cabinet 4 -->
                <div class="text-center">
                    <div class=" p-4 mb-4 rounded-lg h-[300px]">
                        <img src="img/topmodularcabinet/sink/a.png" alt="Cabinet 5" class="w-full h-full object-cover rounded-md" data-aos="fade-up" data-aos-delay="300">
                    </div>
                    <h3 class="text-lg font-medium mb-3">SINK BASE CABINET</h3>
                    <button class="border-2 border-black  px-8 py-2 text-sm hover:bg-orange-900 transition-colors hover:text-white" data-aos="fade-up" data-aos-delay="300">Not Available for Now</button>
                </div>
            </div>
            <hr>

            <div class="col-span-1 md:col-span-2 lg:col-span-4 flex justify-center mt-9 font-bold text-orange-900 font-[crimson]">

                BATHROOM CABINET

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6 mt-8 mr-5 ml-5 font-[crimson] text-orange-900">
                <!-- Cabinet 1 -->
                <div class="text-center">
                    <div class=" p-4 mb-4 rounded-lg h-[300px]">
                        <img src="img/topmodularcabinet/vanity/a.png" alt="Cabinet 1" class="w-full h-full object-contain rounded-md" data-aos="fade-up" data-aos-delay="300">
                    </div>
                    <h3 class="text-lg font-medium mb-3">VANITY CABINET</h3>
                    <button class="border-2 border-black px-8 py-2 text-sm hover:bg-orange-900 transition-colors hover:text-white" data-aos="fade-up" data-aos-delay="300">Not Available for Now</button>
                </div>

                <!-- Cabinet 2 -->
                <div class="text-center">
                    <div class=" p-4 mb-4 rounded-lg h-[300px]">
                        <img src="img/topmodularcabinet/medicine/a.png" alt="Cabinet 2" class="w-full h-full object-contain rounded-md" data-aos="fade-up" data-aos-delay="300">
                    </div>
                    <h3 class="text-lg font-medium mb-3">MEDICINE CABINET</h3>
                    <button class="border-2 border-black px-8 py-2 text-sm hover:bg-orange-900 transition-colors hover:text-white" data-aos="fade-up" data-aos-delay="300">Not Available for Now</button>
                </div>

                <!-- Cabinet 3 -->
                <div class="text-center">
                    <div class=" p-4 mb-4 rounded-lg h-[300px]">
                        <img src="img/topmodularcabinet/bathroomcabinet/linencabinet/a.jpg" alt="Cabinet 3" class="w-full h-full object-cover rounded-md" data-aos="fade-up" data-aos-delay="300">
                    </div>
                    <h3 class="text-lg font-medium mb-3">CABINET</h3>
                    <button class="border-2 border-black px-8 py-2 text-sm hover:bg-orange-900 transition-colors hover:text-white" data-aos="fade-up" data-aos-delay="300">Not Available for Now</button>
                </div>
            </div>
        </section>


        <section class="relative w-full h-[100px]  overflow-hidden font-[montserrat] mt-5" data-aos="fade-up" data-aos-delay="400">
            <!-- Slides -->
            <div class="ads-slide absolute inset-0 bg-cover bg-center transition-opacity duration-1000 opacity-100"
                style="background-image: url('./images/alphaland-1.jpg');">
                <div class="bg-black/50 w-full h-full flex items-center justify-center">
                    <p class="text-white text-xl  font-semibold tracking-wide text-center px-4">
                        Participated in worldbex Convention Center 2023
                    </p>
                </div>
            </div>

            <div class="ads-slide absolute inset-0 bg-cover bg-center transition-opacity duration-1000 opacity-0"
                style="background-image: url('./images/alphaland-2.jpg');">
                <div class="bg-black/50 w-full h-full flex items-center justify-center">
                    <p class="text-white text-xl font-semibold tracking-wide text-center px-4">
                        Over 100+ successful interior projects nationwide
                    </p>
                </div>
            </div>

            <div class="ads-slide absolute inset-0 bg-cover bg-center transition-opacity duration-1000 opacity-0"
                style="background-image: url('./images/alphaland-3.jpg');">
                <div class="bg-black/50 w-full h-full flex items-center justify-center">
                    <p class="text-white text-xl  font-semibold tracking-wide text-center px-4">
                        Featured in Design & Architecture Weekly PH
                    </p>
                </div>
            </div>
        </section>


        <section class="bg-white py-12 text-orange-900" data-aos="fade-up" data-aos-delay="100">
            <div class="max-w-6xl mx-auto px-4">
                <h2 class="text-3xl md:text-4xl font-bold mb-4 text-center animate-up delay-1 font-[crimson]">
                    What Clients Say
                </h2>
                <p class="text-center  font-light mb-10 max-w-3xl mx-auto animate-up delay-2">
                    Don’t just take our word for it. Here’s what our valued clients have to say about their experience with Realiving Design Center Corporation.
                </p>

                <div class="grid md:grid-cols-3 gap-8 reviews-container">
                    <!-- Review Card -->
                    <div class="bg-white shadow-md rounded-2xl p-6 flex flex-col gap-4 animate-up delay-3 hover:shadow-lg transition">
                        <div class="text-yellow-400 text-xl">★★★★★</div>
                        <p class="text-gray-700 italic">“They completely transformed our space! The team was professional, creative, and easy to work with.”</p>
                        <div class="flex items-center gap-3 mt-4">
                            <img src="https://i.pravatar.cc/40" alt="Reviewer" class="w-10 h-10 rounded-full object-cover">
                            <div>
                                <p class="font-semibold">Jane Doe</p>
                                <p class="text-sm text-gray-500">March 15, 2025</p>
                            </div>
                        </div>
                    </div>

                    <!-- Repeat Card -->
                    <div class="bg-white shadow-md rounded-2xl p-6 flex flex-col gap-4 animate-up delay-3 hover:shadow-lg transition">
                        <div class="text-yellow-400 text-xl">★★★★★</div>
                        <p class="text-gray-700 italic">“Realiving made our dream home a reality. The attention to detail was amazing!”</p>
                        <div class="flex items-center gap-3 mt-4">
                            <img src="https://i.pravatar.cc/41" alt="Reviewer" class="w-10 h-10 rounded-full object-cover">
                            <div>
                                <p class="font-semibold">Carlos Rivera</p>
                                <p class="text-sm text-gray-500">April 2, 2025</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white shadow-md rounded-2xl p-6 flex flex-col gap-4 animate-up delay-3 hover:shadow-lg transition">
                        <div class="text-yellow-400 text-xl">★★★★★</div>
                        <p class="text-gray-700 italic">“Highly recommend! Realiving brought our vision to life better than we imagined.”</p>
                        <div class="flex items-center gap-3 mt-4">
                            <img src="https://i.pravatar.cc/42" alt="Reviewer" class="w-10 h-10 rounded-full object-cover">
                            <div>
                                <p class="font-semibold">Anna Lee</p>
                                <p class="text-sm text-gray-500">May 5, 2025</p>
                            </div>
                        </div>
                    </div>
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


       <?php include 'footer.php'; ?>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="js/js_index//carosel.js"></script>
    <script>
        AOS.init();

        document.addEventListener("DOMContentLoaded", function() {
            const adsSlides = document.querySelectorAll('.ads-slide');
            let adsIndex = 0;

            setInterval(() => {
                adsSlides[adsIndex].classList.remove('opacity-100');
                adsSlides[adsIndex].classList.add('opacity-0');

                adsIndex = (adsIndex + 1) % adsSlides.length;

                adsSlides[adsIndex].classList.remove('opacity-0');
                adsSlides[adsIndex].classList.add('opacity-100');
            }, 4000);
        });
    </script>

</body>

</html>