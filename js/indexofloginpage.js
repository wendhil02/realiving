document.addEventListener("DOMContentLoaded", function() {
    const togglePassword = document.getElementById("togglePassword");
    const passwordInput = document.getElementById("password");

    togglePassword.addEventListener("click", function() {
        const type =
            passwordInput.getAttribute("type") === "password" ? "text" : "password";
        passwordInput.setAttribute("type", type);

        if (type === "password") {
            togglePassword.classList.remove("ri-eye-line");
            togglePassword.classList.add("ri-eye-off-line");
        } else {
            togglePassword.classList.remove("ri-eye-off-line");
            togglePassword.classList.add("ri-eye-line");
        }
    });
});

const slides = [{
        title: `<img src="../logo/new.png" alt="Noble Home Logo" class="h-[150px] object-contain" />`,
        subtitle: "",
        bgImage: "url('../code/images/background-image2.jpg')" // Sample building image
    },
    {
        title: `<img src="../logo/mmone.png" alt="Real Living Logo" class="h-[150px] object-contain" />`,
        subtitle: "",
        bgImage: "url('../logo/rlthree.jpg')" // Sample real estate image
    }
];

let currentSlide = 0;
const titleText = document.getElementById('title-text');
const subtitleText = document.getElementById('subtitle-text');
const slideContainer = document.getElementById('slide-container');
const bgSlideContainer = document.getElementById('bg-slide-container');

// Initialize first background
bgSlideContainer.style.backgroundImage = slides[currentSlide].bgImage;
bgSlideContainer.style.backgroundSize = 'cover';
bgSlideContainer.style.backgroundPosition = 'center';

setInterval(() => {
    slideContainer.classList.add('opacity-0');

    setTimeout(() => {
        currentSlide = (currentSlide + 1) % slides.length;
        titleText.innerHTML = slides[currentSlide].title;
        subtitleText.textContent = slides[currentSlide].subtitle;

        // Update background image
        bgSlideContainer.style.backgroundImage = slides[currentSlide].bgImage;
        bgSlideContainer.style.backgroundSize = 'cover';
        bgSlideContainer.style.backgroundPosition = 'center';

        slideContainer.classList.remove('opacity-0');
    }, 500);
}, 4000);

