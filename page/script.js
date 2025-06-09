function isNewsPage() {
  return window.location.pathname.includes("news.php");
}

// Main slideshow functionality for hero section
let mainSlideIndex = 0;

function initMainSlideshow() {
  const slides = document.getElementsByClassName("slide");
  
  if (slides.length === 0) return;

  function showSlides() {
    // Remove "active" from all slides
    for (let i = 0; i < slides.length; i++) {
      slides[i].classList.remove("active");
    }

    // Add "active" to the current slide
    slides[mainSlideIndex].classList.add("active");

    // Move to the next slide
    mainSlideIndex = (mainSlideIndex + 1) % slides.length;

    // Change slide every 3 seconds
    setTimeout(showSlides, 3000);
  }

  // Start the slideshow
  showSlides();
}

// Ads banner slideshow
function initAdsSlideshow() {
  const adsSlides = document.querySelectorAll('.ads-slide');
  if (adsSlides.length === 0) return;
  
  let adsIndex = 0;

  setInterval(() => {
    adsSlides[adsIndex].classList.remove('active');
    adsIndex = (adsIndex + 1) % adsSlides.length;
    adsSlides[adsIndex].classList.add('active');
  }, 4000);
}

// Main DOMContentLoaded event listener
document.addEventListener("DOMContentLoaded", function () {
  // Initialize main slideshow only if not on news page
  if (!isNewsPage()) {
    initMainSlideshow();
  }

  // Initialize ads slideshow
  initAdsSlideshow();

  // Initialize AOS (Animate On Scroll) if available
  if (typeof AOS !== 'undefined') {
    AOS.init({
      duration: 800,
      offset: 120,
      once: true
    });
  }

  // Unified scroll effects
  window.addEventListener("scroll", function () {
    const scrollY = window.scrollY;
    
    // Header scroll effect
    const header = document.querySelector('header');
    if (header) {
      if (scrollY > 50) {
        header.classList.add('scrolled');
      } else {
        header.classList.remove('scrolled');
      }
    }
    
    // Blur effect
    const blurTarget = document.getElementById("blurTarget");
    if (blurTarget) {
      if (scrollY > 50) {
        blurTarget.classList.add("blur-effect");
      } else {
        blurTarget.classList.remove("blur-effect");
      }
    }
  });

  // News section (disable sliding on news.html page)
  if (!isNewsPage()) {
    const newsCards = document.querySelectorAll('.news-card');
    const dots = document.querySelectorAll('.dot');
    
    // Only proceed if news cards exist
    if (newsCards.length > 0) {
      let currentIndex = 0;

      function showCard(index) {
        newsCards.forEach((card, i) => {
          card.style.transform = `translateX(${(i - index) * 30}%)`;  // Adjusted for proper sliding
        });

        dots.forEach((dot, i) => {
          if (i === index) {
            dot.classList.add('active');
          } else {
            dot.classList.remove('active');
          }
        });
      }

      function nextCard() {
        currentIndex = (currentIndex + 1) % newsCards.length;
        showCard(currentIndex);
      }

      // Initial setup for news section
      showCard(currentIndex);

      // Set interval to change card every 3 seconds
      setInterval(nextCard, 3000);
    }
  }
});

// Projects section
const cards = document.querySelectorAll('.card');

if (cards.length > 0) {
  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('appear');
      }
    });
  }, {
    threshold: 0.2
  });

  cards.forEach(card => {
    observer.observe(card);
  });
}

// Card hover effects
document.querySelectorAll('.card-image img').forEach(img => {
  const originalSrc = img.src;
  const hoverSrc = img.getAttribute('data-hover');
  
  if (hoverSrc) {
    img.addEventListener('mouseover', () => {
      img.src = hoverSrc;
    });

    img.addEventListener('mouseout', () => {
      img.src = originalSrc;
    });
  }
});

// Animation observer
document.addEventListener("DOMContentLoaded", function () {
  const items = document.querySelectorAll('.animate-left, .animate-right, .animate-up, .animate-pop');

  if (items.length > 0) {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add('in-view');
          } else {
            entry.target.classList.remove('in-view');
          }
        });
      },
      {
        threshold: 0.2,
      }
    );

    items.forEach((item) => {
      observer.observe(item);
    });
  }
});