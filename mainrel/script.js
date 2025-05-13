// Function to check if the current page is "news.html"
function isNewsPage() {
  return window.location.pathname.includes("news.html");
}

// Slideshow functionality for index page
let slideIndex = 0;
const slides = document.getElementsByClassName("slide");

function showSlides() {
  // Remove "active" from all slides
  for (let i = 0; i < slides.length; i++) {
    slides[i].classList.remove("active");
  }

  // Add "active" to the current slide
  slides[slideIndex].classList.add("active");

  // Move to the next slide
  slideIndex = (slideIndex + 1) % slides.length;

  // Change slide every 3 seconds
  setTimeout(showSlides, 3000);
}

// Start slideshow only if we're on index.html
document.addEventListener("DOMContentLoaded", function () {
  if (!isNewsPage()) {
    showSlides();  // Only start slideshow if it's not the news page
  }

  // Scroll blur effect
  window.addEventListener("scroll", function () {
    const blurTarget = document.getElementById("blurTarget");
    if (window.scrollY > 50) {
      blurTarget.classList.add("blur-effect");
    } else {
      blurTarget.classList.remove("blur-effect");
    }
  });

  // News section (disable sliding on news.html page)
  if (!isNewsPage()) {
    const newsCards = document.querySelectorAll('.news-card');
    const dots = document.querySelectorAll('.dot');
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
});

//projects

const cards = document.querySelectorAll('.card');

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

document.querySelectorAll('.card-image img').forEach(img => {
  const originalSrc = img.src;
  const hoverSrc = img.getAttribute('data-hover');

  img.addEventListener('mouseover', () => {
    img.src = hoverSrc;
  });

  img.addEventListener('mouseout', () => {
    img.src = originalSrc;
  });
});