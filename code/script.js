let slideIndex = 0;
const slides = document.getElementsByClassName("slide");

function showSlides() {
  // Remove "active" from all slides
  for (let i = 0; i < slides.length; i++) {
    slides[i].classList.remove("active");
  }

  // Optional: Add logic to show a slide if needed
  slides[slideIndex].classList.add("active");

  // Move to next slide (you can customize this behavior)
  slideIndex = (slideIndex + 1) % slides.length;

  setTimeout(showSlides, 3000); // Change slide every 3 seconds
}

function showSlides() {
  for (let i = 0; i < slides.length; i++) {
    slides[i].classList.remove("active");
  }

  slideIndex++;
  if (slideIndex > slides.length) { slideIndex = 1; }

  slides[slideIndex - 1].classList.add("active");
  setTimeout(showSlides, 3000);
}

document.addEventListener("DOMContentLoaded", showSlides);

//scroll blur

window.addEventListener("scroll", function () {
  const blurTarget = document.getElementById("blurTarget");
  if (window.scrollY > 50) {
    blurTarget.classList.add("blur-effect");
  } else {
    blurTarget.classList.remove("blur-effect");
  }
});

//end slideshow

const newsCards = document.querySelectorAll('.news-card');
const dots = document.querySelectorAll('.dot');
let currentIndex = 0;

function showCard(index) {
  newsCards.forEach((card, i) => {
    card.style.transform = `translateX(${(i - index) * 30}%)`;
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

// Initial setup
showCard(currentIndex);

// Set interval to change card every 3 seconds
setInterval(nextCard, 3000);

//END NEWS

// Start slideshow after DOM loads
document.addEventListener("DOMContentLoaded", function () {
  showSlides();

  document.querySelector('.contact-form').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent actual form submission

    const slider = document.getElementById('contact-range');
    slider.value = 1; // Move the circle to the right (Inquiry)

    // Add additional logic if needed (e.g. show inquiry section)
  });
});
