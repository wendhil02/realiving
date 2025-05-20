     document.addEventListener('DOMContentLoaded', function() {
            const carousel = document.getElementById('carousel-slides');
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
            const dots = document.querySelectorAll('.dot');
            
            let currentIndex = 0;
            const slideCount = document.querySelectorAll('#carousel-slides > div').length;
            
            function updateCarousel() {
                carousel.style.transform = `translateX(-${currentIndex * 100}%)`;
                
                // Update active dot
                dots.forEach((dot, index) => {
                    if (index === currentIndex) {
                        dot.classList.add('bg-gray-200');
                        dot.classList.remove('bg-gray-400');
                    } else {
                        dot.classList.add('bg-gray-400');
                        dot.classList.remove('bg-gray-200');
                    }
                });
            }
            
            function goToSlide(index) {
                currentIndex = index;
                updateCarousel();
            }
            
            // Next button click
            nextBtn.addEventListener('click', function() {
                currentIndex = (currentIndex + 1) % slideCount;
                updateCarousel();
            });
            
            // Previous button click
            prevBtn.addEventListener('click', function() {
                currentIndex = (currentIndex - 1 + slideCount) % slideCount;
                updateCarousel();
            });
            
            // Dot navigation
            dots.forEach((dot) => {
                dot.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    goToSlide(index);
                });
            });
        });