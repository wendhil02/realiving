<?php
/**
 * RealLiving Design Center - Footer Template
 * 
 * This file contains the footer section with action buttons
 * to be included across the RealLiving website.
 */
?>

<!-- Additional Styles -->
<style>
/* Fallback styles for custom elements and animations */
.backdrop-blur-sm {
  backdrop-filter: blur(8px);
}

/* Gradient backgrounds */
.bg-gradient-to-r {
  background-image: linear-gradient(to right, var(--tw-gradient-stops));
}

.from-blue-500 {
  --tw-gradient-from: #3b82f6;
  --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(59, 130, 246, 0));
}

.to-blue-600 {
  --tw-gradient-to: #2563eb;
}

.from-yellow-400 {
  --tw-gradient-from: #fbbf24;
  --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(251, 191, 36, 0));
}

.to-yellow-500 {
  --tw-gradient-to: #f59e0b;
}

/* Enhanced shadow effects */
.drop-shadow-2xl {
  filter: drop-shadow(0 25px 25px rgb(0 0 0 / 0.3));
}

.shadow-blue-500\/30 {
  box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.3), 0 4px 6px -4px rgba(59, 130, 246, 0.3);
}

.shadow-yellow-500\/30 {
  box-shadow: 0 10px 15px -3px rgba(245, 158, 11, 0.3), 0 4px 6px -4px rgba(245, 158, 11, 0.3);
}

/* Animation durations */
.duration-2000 {
  transition-duration: 2000ms;
}

/* Custom positions */
.left-1\/5 {
  left: 20%;
}

.right-1\/5 {
  right: 20%;
}

.left-1\/3 {
  left: 33.333333%;
}

.right-1\/4 {
  right: 25%;
}
</style>

<script src="https://cdn.tailwindcss.com"></script>
<!-- Enhanced Hero Section with Advanced Animation Effects and Modern Design -->
<section class="relative w-full h-screen overflow-hidden">
  <!-- Background Slider with Improved Dynamic Transitions -->
  <div class="relative w-full h-full" id="heroSlider">
    <!-- Semi-transparent overlay with enhanced gradient -->
    <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-black/50 to-black/80 z-20"></div>
    
    <!-- Parallax Background Effect on Slides -->
    <div class="absolute w-full h-full opacity-100 transition-all duration-2000 ease-in-out transform scale-110 origin-center hero-slide">
      <img src="./images/background-image.jpg" class="w-full h-full object-cover" alt="Modern Kitchen Design">
    </div>
    <div class="absolute w-full h-full opacity-0 transition-all duration-2000 ease-in-out transform scale-110 origin-center hero-slide">
      <img src="./images/background-image2.jpg" class="w-full h-full object-cover" alt="Elegant Living Room">
    </div>
    <div class="absolute w-full h-full opacity-0 transition-all duration-2000 ease-in-out transform scale-110 origin-center hero-slide">
      <img src="./images/background-image3.jpg" class="w-full h-full object-cover" alt="Contemporary Bathroom">
    </div>
  </div>
  
  <!-- Floating Elements - Geometric Shapes -->
  <div class="absolute top-0 left-0 w-full h-full pointer-events-none z-10">
    <div class="absolute top-1/4 left-1/5 w-16 h-16 border border-white/10 rounded-full animate-floatSlow opacity-60 hidden md:block"></div>
    <div class="absolute top-2/3 right-1/4 w-24 h-24 border border-white/10 rounded-full animate-floatFast opacity-40 hidden md:block"></div>
    <div class="absolute bottom-1/4 left-1/3 w-32 h-32 border-2 border-white/5 rounded-full animate-pulse opacity-30 hidden md:block"></div>
    <div class="absolute top-1/3 right-1/5 w-20 h-20 bg-gradient-to-tr from-blue-500/10 to-purple-500/10 rounded-full blur-lg animate-floatMedium hidden md:block"></div>
  </div>
  
  <!-- Centered Content with Modern Glass Morphism -->
  <div class="absolute z-30 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-center px-8 py-12 w-full max-w-5xl">
    <!-- Top Accent Line -->
    <div class="w-16 h-1 bg-gradient-to-r from-blue-400 to-purple-500 mx-auto mb-8 animate-pulse"></div>
    
    <!-- Logo with Enhanced Animation -->
    <div class="mb-10 animate-fadeInUp">
      <img src="../logo/mmone.png" alt="RealLiving Design Center" class="max-w-full h-auto w-80 md:w-96 mx-auto transition-all duration-700 hover:scale-105 drop-shadow-2xl">
    </div>
    
    <!-- Tagline with Split Character Animation -->
    <h2 class="text-white text-2xl md:text-3xl font-light mb-8 tracking-wider leading-relaxed split-text-animation">
      <span>TRANSFORMING</span> <span>SPACES</span> <span>INTO</span> <span>EXTRAORDINARY</span> <span>EXPERIENCES</span>
    </h2>
    
    <!-- Short Descriptive Text -->
    <p class="text-gray-200 text-lg mb-10 max-w-2xl mx-auto leading-relaxed font-light opacity-0 animate-fadeInDelay">
      Award-winning interior design solutions crafted for modern living. Elevate your space with our expert team.
    </p>
    
    <!-- Interactive Pagination with Thumbnails -->
    <div class="flex justify-center items-center space-x-4 mt-8">
      <button class="group relative h-2 w-16 bg-blue-500 rounded-full transition-all duration-300 slider-dot active" data-slide="0">
        <span class="absolute -top-14 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
        
        </span>
      </button>
      <button class="group relative h-2 w-6 bg-white/40 rounded-full transition-all duration-300 slider-dot" data-slide="1">
        <span class="absolute -top-14 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
        
        </span>
      </button>
      <button class="group relative h-2 w-6 bg-white/40 rounded-full transition-all duration-300 slider-dot" data-slide="2">
        <span class="absolute -top-14 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
          
        </span>
      </button>
    </div>
    
    <!-- CTA Buttons with Modern Design -->
    <div class="flex flex-col sm:flex-row justify-center gap-6 mt-12 opacity-0 animate-fadeInDelay2">
      <a href="mainpage.php" class="px-10 py-4 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-lg transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg hover:shadow-blue-500/30 group">
        <span class="flex items-center justify-center">
          <span>Explore</span>
          <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
          </svg>
        </span>
      </a>
      <a href="contact.php" class="px-10 py-4 bg-gradient-to-r from-yellow-400 to-yellow-500 text-white font-medium rounded-lg transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg hover:shadow-yellow-500/30 group">
        <span class="flex items-center justify-center">
          <span>Free Consultation</span>
          <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
          </svg>
        </span>
      </a>
    </div>
  </div>

</section>

<script>
  document.querySelector('a[href="mainpage.php"]').addEventListener('click', function (e) {
    e.preventDefault();
    const button = this;

    // Change button text to "Loading..." or add spinner
    button.innerHTML = `<span class="flex items-center justify-center">
      <svg class="w-5 h-5 mr-2 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
      </svg>
      <span>Loading...</span>
    </span>`;

    // Optional: disable the button to prevent repeated clicks
    button.classList.add('pointer-events-none', 'opacity-70');

    // After 2 seconds, redirect
    setTimeout(() => {
      window.location.href = 'mainpage.php';
    }, 2000);
  });
</script>
 

<!-- JavaScript for Enhanced Functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  const slides = document.querySelectorAll('.hero-slide');
  const dots = document.querySelectorAll('.slider-dot');
  let currentSlide = 0;
  let slideInterval;
  
  // Function to change slide with enhanced transitions
  function showSlide(index) {
    // Hide all slides
    slides.forEach(slide => {
      slide.style.opacity = '0';
      slide.style.transform = 'scale(1.1)';
    });
    
    // Reset all dots
    dots.forEach(dot => {
      dot.classList.remove('active', 'bg-blue-500', 'w-16');
      dot.classList.add('bg-white/40', 'w-6');
    });
    
    // Show the current slide with dynamic scale
    slides[index].style.opacity = '1';
    
    // Create a zoom effect
    setTimeout(() => {
      slides[index].style.transform = 'scale(1.2)';
    }, 100);
    
    // Highlight the current dot
    dots[index].classList.remove('bg-white/40', 'w-6');
    dots[index].classList.add('active', 'bg-blue-500', 'w-16');
    
    // Update current slide index
    currentSlide = index;
  }
  
  // Start auto-rotation with enhanced timing
  function startSlideShow() {
    slideInterval = setInterval(function() {
      let nextSlide = (currentSlide + 1) % slides.length;
      showSlide(nextSlide);
    }, 7000); // Longer time for better user experience
  }
  
  // Stop auto-rotation when user interacts
  function stopSlideShow() {
    clearInterval(slideInterval);
  }
  
  // Click event for dots with pause/resume functionality
  dots.forEach((dot, index) => {
    dot.addEventListener('click', function() {
      stopSlideShow();
      showSlide(index);
      startSlideShow(); // Resume after user interaction
    });
  });
  
  // Scroll Down indicator functionality
  const scrollIndicator = document.querySelector('.scroll-indicator');
  if (scrollIndicator) {
    scrollIndicator.addEventListener('click', function() {
      // Calculate the height of the viewport
      const viewportHeight = window.innerHeight;
      
      // Smooth scroll to just past the hero section
      window.scrollTo({
        top: viewportHeight,
        behavior: 'smooth'
      });
    });
  }
  
  // Initialize split text animation
  const splitTextElements = document.querySelectorAll('.split-text-animation span');
  splitTextElements.forEach((element, index) => {
    // Add staggered delay to each word
    element.style.display = 'inline-block';
    element.style.opacity = '0';
    element.style.transform = 'translateY(20px)';
    element.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
    element.style.transitionDelay = `${0.3 + (index * 0.15)}s`;
    
    // Trigger animation after a small delay
    setTimeout(() => {
      element.style.opacity = '1';
      element.style.transform = 'translateY(0)';
    }, 100);
  });
  
  // Initialize fade-in animations
  const fadeElements = document.querySelectorAll('.animate-fadeInDelay');
  fadeElements.forEach((element) => {
    element.style.transition = 'opacity 1s ease, transform 1s ease';
    element.style.transitionDelay = '1.5s';
    
    setTimeout(() => {
      element.style.opacity = '1';
    }, 100);
  });
  
  const fadeElements2 = document.querySelectorAll('.animate-fadeInDelay2');
  fadeElements2.forEach((element) => {
    element.style.transition = 'opacity 1s ease, transform 1s ease';
    element.style.transitionDelay = '2s';
    
    setTimeout(() => {
      element.style.opacity = '1';
    }, 100);
  });
  
  // Add keyframe animations
  const styleSheet = document.styleSheets[0];
  
  // Enhanced Ken Burns animation
  styleSheet.insertRule(`
    @keyframes kenburns {
      0% { transform: scale(1.1) translate(0, 0); }
      25% { transform: scale(1.2) translate(-10px, 5px); }
      50% { transform: scale(1.3) translate(0, -10px); }
      75% { transform: scale(1.2) translate(10px, 0); }
      100% { transform: scale(1.1) translate(0, 0); }
    }
  `, 0);
  
  // Floating animations
  styleSheet.insertRule(`
    @keyframes floatSlow {
      0% { transform: translateY(0) rotate(0deg); }
      50% { transform: translateY(-20px) rotate(5deg); }
      100% { transform: translateY(0) rotate(0deg); }
    }
  `, 0);
  
  styleSheet.insertRule(`
    @keyframes floatMedium {
      0% { transform: translateY(0) translateX(0); }
      33% { transform: translateY(-15px) translateX(10px); }
      66% { transform: translateY(10px) translateX(-10px); }
      100% { transform: translateY(0) translateX(0); }
    }
  `, 0);
  
  styleSheet.insertRule(`
    @keyframes floatFast {
      0% { transform: translateY(0) translateX(0) scale(1); }
      50% { transform: translateY(-10px) translateX(15px) scale(1.05); }
      100% { transform: translateY(0) translateX(0) scale(1); }
    }
  `, 0);
  
  styleSheet.insertRule(`
    @keyframes fadeInUp {
      0% { opacity: 0; transform: translateY(30px); }
      100% { opacity: 1; transform: translateY(0); }
    }
  `, 0);
  
  styleSheet.insertRule(`
    @keyframes scrollBounce {
      0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
      40% { transform: translateY(-8px); }
      60% { transform: translateY(-4px); }
    }
  `, 0);
  
  // Apply animations to elements
  document.querySelectorAll('.animate-floatSlow').forEach(el => {
    el.style.animation = 'floatSlow 10s ease-in-out infinite';
  });
  
  document.querySelectorAll('.animate-floatMedium').forEach(el => {
    el.style.animation = 'floatMedium 14s ease-in-out infinite';
  });
  
  document.querySelectorAll('.animate-floatFast').forEach(el => {
    el.style.animation = 'floatFast 8s ease-in-out infinite';
  });
  
  document.querySelectorAll('.animate-fadeInUp').forEach(el => {
    el.style.animation = 'fadeInUp 1.2s ease-out forwards';
  });
  
  document.querySelectorAll('.animate-scrollBounce').forEach(el => {
    el.style.animation = 'scrollBounce 2s infinite';
  });
  
  // Apply Ken Burns effect to all slides
  slides.forEach(slide => {
    slide.style.animation = 'kenburns 20s infinite alternate ease-in-out';
  });
  
  // Start the slideshow
  showSlide(0);
  startSlideShow();
  
  // Add responsive pause/resume
  window.addEventListener('blur', stopSlideShow);
  window.addEventListener('focus', startSlideShow);
});
</script>
