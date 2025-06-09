<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Realiving Design Center Corporation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'fade-in': 'fadeIn 0.6s ease-in-out',
                        'slide-up': 'slideUp 0.8s ease-out',
                        'float': 'float 3s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' }
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(50px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' }
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-10px)' }
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 min-h-screen text-white">
    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 bg-black/20 backdrop-blur-lg border-b border-white/10">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="text-2xl font-bold bg-gradient-to-r from-yellow-400 to-blue-400 bg-clip-text text-transparent">
                    Realiving Design Center
                </div>
                <div class="hidden md:flex space-x-8">
                    <a href="#" class="hover:text-purple-400 transition-colors duration-300">Home</a>
                    <a href="#" class="text-purple-400">About</a>
                    <a href="#" class="hover:text-purple-400 transition-colors duration-300">Services</a>
                    <a href="#" class="hover:text-purple-400 transition-colors duration-300">Contact</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-32 pb-20 px-6">
        <div class="max-w-7xl mx-auto text-center">
            <div class="animate-fade-in">
                <h1 class="text-6xl md:text-8xl font-extrabold mb-6 bg-gradient-to-r from-white via-purple-200 to-pink-200 bg-clip-text text-transparent">
                    About Us
                </h1>
                <p class="text-xl md:text-2xl text-gray-300 max-w-3xl mx-auto leading-relaxed">
                    Transforming spaces and experiences through innovative design solutions that breathe new life into every project
                </p>
            </div>
            <div class="mt-16 animate-float">
                <div class="w-32 h-32 mx-auto bg-gradient-to-r from-purple-600 to-pink-600 rounded-full flex items-center justify-center shadow-2xl">
                    <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/>
                    </svg>
                </div>
            </div>
        </div>
    </section>

    <!-- Company Overview -->
    <section class="py-20 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div class="animate-slide-up">
                    <h2 class="text-4xl md:text-5xl font-bold mb-8 bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
                        Our Story
                    </h2>
                    <p class="text-lg text-gray-300 mb-6 leading-relaxed">
                        Founded in 2015, Realiving Design Center Corporation emerged from a passion for transforming ordinary spaces into extraordinary experiences. What began as a boutique design studio has evolved into a comprehensive design powerhouse, specializing in interior design, architectural planning, and brand identity solutions.
                    </p>
                    <p class="text-lg text-gray-300 leading-relaxed">
                        Today, we've successfully completed over 800+ projects across residential, commercial, and hospitality sectors, earning recognition as one of the Philippines' most innovative design firms. Our philosophy of "realiving" spaces means breathing new life into every environment we touch.
                    </p>
                </div>
                <div class="relative">
                    <div class="bg-gradient-to-r from-purple-600/20 to-pink-600/20 backdrop-blur-sm rounded-3xl p-8 border border-white/10">
                        <div class="grid grid-cols-2 gap-6 text-center">
                            <div class="p-6">
                                <div class="text-4xl font-bold text-purple-400 mb-2">800+</div>
                                <div class="text-gray-300">Projects Completed</div>
                            </div>
                            <div class="p-6">
                                <div class="text-4xl font-bold text-pink-400 mb-2">35+</div>
                                <div class="text-gray-300">Design Experts</div>
                            </div>
                            <div class="p-6">
                                <div class="text-4xl font-bold text-purple-400 mb-2">300+</div>
                                <div class="text-gray-300">Happy Clients</div>
                            </div>
                            <div class="p-6">
                                <div class="text-4xl font-bold text-pink-400 mb-2">9</div>
                                <div class="text-gray-300">Years Excellence</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision -->
    <section class="py-20 px-6 bg-black/20">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-4 bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
                    Mission & Vision
                </h2>
            </div>
            <div class="grid md:grid-cols-2 gap-12">
                <div class="bg-gradient-to-br from-purple-600/10 to-transparent backdrop-blur-sm rounded-3xl p-8 border border-purple-500/20 hover:border-purple-400/40 transition-all duration-500 group">
                    <div class="w-16 h-16 bg-gradient-to-r from-purple-600 to-purple-400 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-purple-400">Our Mission</h3>
                    <p class="text-gray-300 leading-relaxed">
                        To transform spaces and experiences through innovative design solutions that enhance quality of life, foster creativity, and create environments where people thrive. We believe every space has the potential to be extraordinary.
                    </p>
                </div>
                <div class="bg-gradient-to-br from-pink-600/10 to-transparent backdrop-blur-sm rounded-3xl p-8 border border-pink-500/20 hover:border-pink-400/40 transition-all duration-500 group">
                    <div class="w-16 h-16 bg-gradient-to-r from-pink-600 to-pink-400 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-pink-400">Our Vision</h3>
                    <p class="text-gray-300 leading-relaxed">
                        To be the Philippines' premier design corporation, recognized globally for our innovative approach to spatial transformation and our commitment to creating timeless, sustainable designs that inspire and elevate human experiences.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Core Values -->
    <section class="py-20 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-4 bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
                    Our Core Values
                </h2>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                    The principles that guide everything we do
                </p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="group">
                    <div class="bg-gradient-to-br from-purple-600/10 via-transparent to-pink-600/10 backdrop-blur-sm rounded-3xl p-8 border border-white/10 hover:border-purple-400/40 transition-all duration-500 h-full">
                        <div class="w-16 h-16 bg-gradient-to-r from-purple-600 to-pink-600 rounded-2xl flex items-center justify-center mb-6 group-hover:rotate-12 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-4 text-white">Creativity</h3>
                        <p class="text-gray-300 leading-relaxed">
                            We push creative boundaries, exploring innovative design concepts and artistic solutions that transform ordinary spaces into extraordinary experiences.
                        </p>
                    </div>
                </div>
                <div class="group">
                    <div class="bg-gradient-to-br from-purple-600/10 via-transparent to-pink-600/10 backdrop-blur-sm rounded-3xl p-8 border border-white/10 hover:border-pink-400/40 transition-all duration-500 h-full">
                        <div class="w-16 h-16 bg-gradient-to-r from-pink-600 to-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:rotate-12 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-4 text-white">Functionality</h3>
                        <p class="text-gray-300 leading-relaxed">
                            We believe beautiful design must also be functional, creating spaces that not only look stunning but work seamlessly for their intended purpose.
                        </p>
                    </div>
                </div>
                <div class="group">
                    <div class="bg-gradient-to-br from-purple-600/10 via-transparent to-pink-600/10 backdrop-blur-sm rounded-3xl p-8 border border-white/10 hover:border-purple-400/40 transition-all duration-500 h-full">
                        <div class="w-16 h-16 bg-gradient-to-r from-purple-600 to-pink-600 rounded-2xl flex items-center justify-center mb-6 group-hover:rotate-12 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-4 text-white">Sustainability</h3>
                        <p class="text-gray-300 leading-relaxed">
                            We are committed to eco-friendly design practices, using sustainable materials and creating timeless designs that reduce environmental impact.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="py-20 px-6 bg-black/20">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-4 bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
                    Meet Our Creative Leaders
                </h2>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                    The visionary designers shaping extraordinary spaces
                </p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="group">
                    <div class="bg-gradient-to-br from-purple-600/10 to-transparent backdrop-blur-sm rounded-3xl p-8 border border-white/10 hover:border-purple-400/40 transition-all duration-500 text-center">
                        <div class="w-24 h-24 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full mx-auto mb-6 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <span class="text-2xl font-bold text-white">AS</span>
                        </div>
                        <h3 class="text-xl font-bold mb-2 text-white">Arch. Sofia Cruz</h3>
                        <p class="text-purple-400 mb-4">CEO & Principal Architect</p>
                        <p class="text-gray-300 text-sm leading-relaxed">
                            Visionary architect with 20+ years in design innovation, leading transformative projects across residential and commercial sectors.
                        </p>
                    </div>
                </div>
                <div class="group">
                    <div class="bg-gradient-to-br from-pink-600/10 to-transparent backdrop-blur-sm rounded-3xl p-8 border border-white/10 hover:border-pink-400/40 transition-all duration-500 text-center">
                        <div class="w-24 h-24 bg-gradient-to-r from-pink-600 to-purple-600 rounded-full mx-auto mb-6 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <span class="text-2xl font-bold text-white">MR</span>
                        </div>
                        <h3 class="text-xl font-bold mb-2 text-white">Marco Rivera</h3>
                        <p class="text-pink-400 mb-4">Creative Director</p>
                        <p class="text-gray-300 text-sm leading-relaxed">
                            Award-winning interior designer specializing in luxury residential and hospitality design, bringing artistic vision to life.
                        </p>
                    </div>
                </div>
                <div class="group">
                    <div class="bg-gradient-to-br from-purple-600/10 to-transparent backdrop-blur-sm rounded-3xl p-8 border border-white/10 hover:border-purple-400/40 transition-all duration-500 text-center">
                        <div class="w-24 h-24 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full mx-auto mb-6 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <span class="text-2xl font-bold text-white">LA</span>
                        </div>
                        <h3 class="text-xl font-bold mb-2 text-white">Lisa Aquino</h3>
                        <p class="text-purple-400 mb-4">Head of Operations</p>
                        <p class="text-gray-300 text-sm leading-relaxed">
                            Project management expert ensuring seamless execution from concept to completion, maintaining quality across all design projects.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-20 px-6">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-4xl md:text-5xl font-bold mb-6 bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
                Ready to Transform Your Space?
            </h2>
            <p class="text-xl text-gray-300 mb-10 leading-relaxed">
                Join hundreds of satisfied clients who trust Realiving Design Center Corporation to bring their vision to life
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-8 py-4 rounded-full font-semibold text-lg hover:from-purple-700 hover:to-pink-700 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl">
                    Start Your Project
                </button>
                <button class="border-2 border-purple-400 text-purple-400 px-8 py-4 rounded-full font-semibold text-lg hover:bg-purple-400 hover:text-white transition-all duration-300">
                    View Portfolio
                </button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-black/40 border-t border-white/10 py-12 px-6">
        <div class="max-w-7xl mx-auto text-center">
            <div class="text-2xl font-bold mb-4 bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
                Realiving Design Center Corporation
            </div>
            <p class="text-gray-400 mb-6">Transforming spaces through innovative design solutions</p>mb-6">Transforming businesses through innovative technology</p>
            <div class="flex justify-center space-x-6 text-gray-400">
                <a href="#" class="hover:text-purple-400 transition-colors duration-300">Privacy Policy</a>
                <a href="#" class="hover:text-purple-400 transition-colors duration-300">Terms of Service</a>
                <a href="#" class="hover:text-purple-400 transition-colors duration-300">Contact Us</a>
            </div>
            <div class="mt-6 text-gray-500">
                © 2024 TechVision Solutions. All rights reserved.
            </div>
        </div>
    </footer>

    <script>
        // Add scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe elements for animation
        document.querySelectorAll('.animate-slide-up').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(50px)';
            el.style.transition = 'opacity 0.8s ease-out, transform 0.8s ease-out';
            observer.observe(el);
        });

        // Add hover effects to cards
        document.querySelectorAll('.group').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-10px)';
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0)';
            });
        });

        // Smooth scrolling for navigation
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>