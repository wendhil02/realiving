<?php
include '../connection/connection.php';
include 'header/headernav.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - RealLiving Design Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="../images/favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="header/contact/contact.css">
</head>

<body>

    <section class="sub-header">
        <h1>Contact Us</h1>
    </section>

    <section class="contact-info-section">
        <div class="container">
            <div class="contact-details">
                <div class="contact-item">
                    <img src="img/social/call-icon.png" alt="Phone Icon" class="icon">
                    <p>(+63) 912 345 6789</p>
                </div>
                <div class="contact-item">
                    <img src="img/social/email-icon.png" alt="Email Icon" class="icon">
                    <p>info@realliving.com</p>
                </div>
                <div class="contact-item">
                    <img src="img/social/location-icon.png" alt="Location Icon" class="icon">
                    <p>MC Premier-EDSA Balintawak, Quezon City</p>
                </div>
                <div class="contact-item">
                    <img src="img/social/time-icon.png" alt="Time Icon" class="icon">
                    <p>Mon-Fr: 7AM-5PM | Sat 7AM-12PM</p>
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
                    <img src="img/social/wc.png" alt="WeChat" class="social-icon">
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
                        <textarea id="message" name="message" rows="6" placeholder="Type your message here..."
                            required></textarea>
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
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d964.997175254155!2d121.00328628041727!3d14.6565826589749!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b712dc89bb99%3A0x935f93a6e49ab912!2sMC%20Premiere!5e0!3m2!1sen!2sph!4v1748587348729!5m2!1sen!2sph"
                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
    </section>
    
    <section class="faq-section">
        <div class="container-faq">
            <h2>Frequently Asked Questions</h2>
            <p class="description-faq">Wondering how we work or what to expect? Our FAQs cover the most common questions
                to help you get started with ease.</p>
            <div class="faq-grid">
                <div class="faq-item">
                    <span class="icon">+</span>
                    <p>What services do you offer?</p>
                    <div class="answer">
                        We specialize in designing and fabricating high-quality modular cabinets for kitchens,
                        wardrobes, entertainment units, and other storage solutions. We also provide 3D design
                        presentations and on-site measurements to ensure a perfect fit for your space.
                    </div>
                </div>

                <div class="faq-item">
                    <span class="icon">+</span>
                    <p>Do you handle the installation?</p>
                    <div class="answer">
                        Yes, we provide full installation services. Our team ensures that each cabinet is installed
                        securely and precisely according to the design specifications.
                    </div>
                </div>
                <div class="faq-item">
                    <span class="icon">+</span>
                    <p>Do you offer free consultations?</p>
                    <div class="answer">
                        Absolutely! We offer free initial consultations to understand your needs and preferences. We’ll
                        guide you through our process and help you explore design options that suit your lifestyle and
                        space.

                    </div>
                </div>
                <div class="faq-item">
                    <span class="icon">+</span>
                    <p>How long does the process take?</p>
                    <div class="answer">
                        The timeline varies depending on the scope of the project, but generally, the process takes
                        around 2 to 4 weeks from final design approval to installation. We ensure timely delivery
                        without compromising quality.
                    </div>
                </div>
                <div class="faq-item">
                    <span class="icon">+</span>
                    <p>How can I request a quotation?</p>
                    <div class="answer">
                        You can request a quotation by sending us a message through our website, social media pages, or
                        by contacting us directly via phone or email. Provide basic details about your space, preferred
                        design, and measurements, and we’ll get back to you promptly.
                    </div>
                </div>
                <div class="faq-item">
                    <span class="icon">+</span>
                    <p>Is there a showroom I can visit?</p>
                    <div class="answer">
                        Yes! You can visit us at MC Premier – EDSA Balintawak, Quezon City. We’d be happy to walk you
                        through our sample designs and materials in person.
                    </div>
                </div>
                <div class="faq-item">
                    <span class="icon">+</span>
                    <p>Can I customize my cabinet design?</p>
                    <div class="answer">
                        Definitely. All our cabinets are custom-built based on your preferences, space requirements, and
                        chosen materials. We’ll work closely with you to create a design that fits your style and needs.
                    </div>
                </div>
                <div class="faq-item">
                    <span class="icon">+</span>
                    <p>Do you serve areas outside Quezon City?</p>
                    <div class="answer">
                        Yes, we cater to clients within and outside Quezon City. Reach out to us with your location
                        details, and we’ll confirm if we can accommodate your project.
                    </div>
                </div>
                <div class="faq-item">
                    <span class="icon">+</span>
                    <p>What is a modular cabinet?</p>
                    <div class="answer">
                        A modular cabinet is a type of furniture built from pre-made units or modules that can be
                        customized and arranged to fit your specific space. It offers flexibility, efficient storage,
                        and a clean, modern look.
                    </div>
                </div>
                <div class="faq-item">
                    <span class="icon">+</span>
                    <p>How do I get in touch with you?</p>
                    <div class="answer">
                        You can reach us through our official contact channels. Visit our showroom, send us a message
                        through our website or social media pages, or contact our team via phone or email. We're here to
                        assist you with any inquiries or project needs. </div>
                </div>
            </div>
        </div>
        </div>
    </section>
      <?php include 'footer.php'; ?>


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
                if (isOpening) {
                    icon.style.transform = 'rotate(45deg)';
                } else {
                    icon.style.transform = 'rotate(0deg)';
                }
            });
        });
    </script>
</body>

</html>