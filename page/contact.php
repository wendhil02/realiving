<?php
include 'header.php';
include "../connection/connection.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - RealLiving Design Center</title>
    <link rel="icon" type="image/png" href="./images/favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="contact/contact.css?v=2.1"> </head>
<body>

    <section class="sub-header">
        <h1>Contact Us</h1>
    </section>

    <section class="contact-info-section">
        <div class="container">
            <div class="contact-details">
                <div class="contact-item">
                    <img src="images/call-icon.png" alt="Phone Icon" class="icon"> <p>(+63) 912 345 6789</p>
                </div>
                <div class="contact-item">
                    <img src="images/email-icon.png" alt="Email Icon" class="icon"> <p>info@realliving.com</p>
                </div>
                <div class="contact-item">
                    <img src="images/location-icon.png" alt="Location Icon" class="icon"> <p>MC Premier-EDSA Balintawak, Quezon City</p>
                </div>
                <div class="contact-item">
                    <img src="images/time-icon.png" alt="Time Icon" class="icon"> <p>Mon-Fr: 7AM-5PM | Sat 7AM-12PM</p>
                </div>
            </div>
            <div class="get-in-touch">
                <h1>Get in touch <br> with us!</h1>
            </div>
        </div>
    </section>

    <section class="contact-form-section">
    <div class="container">
        <!-- Left Column - Project Description -->
        <div class="project-description">
            <h2>Tell Us About Your Modular Furniture Project</h2>
            <p>Looking to upgrade your space with modular cabinetry? We'd love to hear your ideas!</p>
            <p>Or if you prefer, just fill out the form, and we'll get back to you as soon as possible.</p>
            <p class="talk-with-us">Talk with us:</p>
            <div class="social">
                <img src="images/wc.png" alt="WeChat" class="social-icon">
            </div>
        </div>

        <!-- Right Column - Form -->
        <div class="contact-form-fields">
            <form action="contact.php" method="POST">
                <div class="form-group">
                    <label for="name">NAME:</label>
                    <input type="text" id="name" name="name" placeholder="E.g. Juan Dela Cruz" required>
                </div>
                
                <div class="form-group">
                    <label for="location">LOCATION:</label>
                    <input type="text" id="location" name="location" placeholder="E.g. Quezon City">
                </div>
                
                <div class="form-group">
                    <label for="email">EMAIL:</label>
                    <input type="email" id="email" name="email" placeholder="E.g. juan.delacruz@gmail.com" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">PHONE:</label>
                    <input type="tel" id="phone" name="phone" placeholder="E.g. (+63) 923 456 789" required>
                </div>
                
                <div class="form-group">
                    <label for="subject">SUBJECT:</label>
                    <input type="text" id="subject" name="subject" placeholder="Subject" required>
                </div>
                
                <div class="form-group">
                    <label for="message">MESSAGE:</label>
                    <textarea id="message" name="message" rows="6" placeholder="Type your message here..." required></textarea>
                </div>
                
                <div class="form-group submit-group">
                    <button type="submit" class="submit-btn">SUBMIT</button>
                </div>
            </form>
        </div>
    </div>
</section>

<section class="map-section">
    <div class="container-map">
        <h2>Site Location</h2>
            <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3859.043534955745!2d121.00287127599026!3d14.707769572520624!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b0a880000001%3A0x6e788874136e4f!2sBalintawak%2C%20Quezon%20City%2C%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1716428489495!5m2!1sen!2sph"
                    width="100%"
                    height="100%"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
</section>

<?php
include 'ads/promo-banner.php';
?>

<section class="faq-section">
    <div class="container-faq">
        <h2>Frequently Asked Questions</h2>
        <p class="description-faq">Wondering how we work or what to expect? Our FAQs cover the most common questions to help you get started with ease.</p>
            <div class="faq-grid">
                <div class="faq-item">
                    <span class="icon">+</span>
                    <p>What services do you offer?</p>
                    <div class="answer">
                    We offer custom cabinet design, manufacturing, and installation services for both residential and commercial spaces.
                </div>
                </div>

                <div class="faq-item">
                    <span class="icon">+</span>
                    <p>Do you handle the installation?</p>
                    <div class="answer">
                    Yes, we provide professional installation services for all our cabinet products.
                </div>
                </div>
                <div class="faq-item">
                    <span class="icon">+</span>
                    <p>Do you offer free consultations?</p>
                    <div class="answer">
                    Yes, we provide free initial consultations to discuss your project needs.
                </div>
                </div>
                <div class="faq-item">
                    <span class="icon">+</span>
                    <p>How long does the process take?</p>
                    <div class="answer">
                    Typically 2-4 weeks from design approval to installation, depending on project complexity.
                </div>
                </div>
                <div class="faq-item">
                    <span class="icon">+</span>
                    <p>How can I request a quotation?</p>
                    <div class="answer">
                    Yes, we provide professional installation services for all our cabinet products.
                </div>
                </div>
                <div class="faq-item">
                    <span class="icon">+</span>
                    <p>Is there a showroom I can visit?</p>
                    <div class="answer">
                    Yes, we provide professional installation services for all our cabinet products.
                </div>
                </div>
                <div class="faq-item">
                    <span class="icon">+</span>
                    <p>Can I customize my cabinet design?</p>
                    <div class="answer">
                    Yes, we provide professional installation services for all our cabinet products.
                </div>
                </div>
                <div class="faq-item">
                    <span class="icon">+</span>
                    <p>Do you serve areas outside Quezon City?</p>
                    <div class="answer">
                    Yes, we provide professional installation services for all our cabinet products.
                </div>
                </div>
                <div class="faq-item">
                    <span class="icon">+</span>
                    <p>What is a modular cabinet?</p>
                    <div class="answer">
                    Yes, we provide professional installation services for all our cabinet products.
                </div>
                </div>
                <div class="faq-item">
                    <span class="icon">+</span>
                    <p>How do I get in touch with you?</p>
                    <div class="answer">
                    Yes, we provide professional installation services for all our cabinet products.
                </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <div id="thankYouModal" class="modal">
        <div class="modal-content">
            <span class="close">×</span>
            <h2>Thank You!</h2>
            <p>Thank you for reaching out to us.<br>Check your email for our response.</p>
        </div>
    </div>

    <script src="script.js"></script>
    <?php
    include 'ads/promo-banner.php';
    include 'footer/footer.php';
    ?>

<script>
document.querySelectorAll('.faq-item').forEach(item => {
    const question = item.querySelector('p');
    const icon = item.querySelector('.icon');
    
    item.addEventListener('click', () => {
        // Toggle active state with smooth transitions
        const isOpening = !item.classList.contains('active');
        
        // Close other items if needed (optional)
        // document.querySelectorAll('.faq-item').forEach(otherItem => {
        //     if(otherItem !== item) otherItem.classList.remove('active');
        // });
        
        item.classList.toggle('active');
        
        // Enhanced animation control
        if(isOpening) {
            icon.style.transform = 'rotate(45deg)';
        } else {
            icon.style.transform = 'rotate(0deg)';
        }
    });
});
</script>
</body>
</html>