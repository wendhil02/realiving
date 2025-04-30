<?php
include 'htmldesign/mainhead.php';
include 'htmldesign/top.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Realiving Design Center</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Playfair Display', serif;
    }

    input[type="range"]::-webkit-slider-thumb {
      background-color: #a3844d;
    }
  </style>
</head>

<body class="text-[#333] leading-relaxed bg-gray-100 ">


  <!-- Contact Section -->
  <section class="py-10 px-6 flex justify-center mt-20">
    <div class="max-w-6xl w-full flex flex-wrap gap-12 justify-between">

      <!-- Contact Info -->
      <div class="flex-1 min-w-[280px] space-y-6 ">
        <div>
          <h2 class="text-4xl text-[#1f96c9] font-normal italic mb-2">Contact Us</h2>
          <p class="text-base font-montserrat text-black">We would love to speak with you.<br>Feel free to reach out using the below details.</p>
        </div>
        <div class="space-y-4">
          <p class="text-lg text-gray-700 font-medium">📍 MC Premier – EDSA Balintawak, Quezon City</p>
          <div class="w-full h-[350px] rounded-xl overflow-hidden shadow-md">
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1929.9894422897767!2d121.00198911044158!3d14.657139783226667!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b712dc89bb99%3A0x935f93a6e49ab912!2sMC%20Premiere!5e0!3m2!1sen!2sph!4v1745547865596!5m2!1sen!2sph"
              width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
              referrerpolicy="no-referrer-when-downgrade">
            </iframe>
          </div>
        </div>
      </div>

      <!-- Contact Form -->
      <form action="contactfunction/contact_process.php" method="POST" class="flex-1 min-w-[280px] bg-[#fdfdfd] p-6 rounded-lg shadow-lg flex flex-col gap-5">
        <?php if (isset($_GET['status'])): ?>
          <div id="notif" class="text-sm w-fit mx-auto mt-4 px-3 py-2 rounded shadow
      <?php echo $_GET['status'] == 'success'
            ? 'bg-green-100 border border-green-400 text-green-700'
            : 'bg-red-100 border border-red-400 text-red-700'; ?>">
            <?php echo $_GET['status'] == 'success'
              ? 'Thank you! Your message has been received.'
              : 'Sorry, there was an error sending your message. Please try again later.'; ?>
          </div>

          <script>
            // Auto-hide after 3 seconds
            setTimeout(() => {
              const notif = document.getElementById('notif');
              if (notif) notif.style.display = 'none';
            }, 3000);

            // Clean URL so ?status=success doesn't stay on refresh
            if (window.history.replaceState) {
              const cleanUrl = window.location.origin + window.location.pathname;
              window.history.replaceState(null, '', cleanUrl);
            }
          </script>
        <?php endif; ?>




        <!-- Inquiry Type -->
        <div class="flex flex-col items-center gap-2 mb-4 mt-10">
          <div class="flex justify-between w-full max-w-[400px] text-lg  font-montserrat">
            <span>Contact</span>
            <span>Inquiry</span>
          </div>
          <input type="range" disabled class="w-full max-w-[400px] accent-[#a3844d] pointer-events-none" />
        </div>

        <!-- Full Name -->
        <input type="text" name="full_name" placeholder="E.g. Juan Dela Cruz" required
          class="p-3 border border-gray-300 rounded-lg font-montserrat focus:outline-none focus:ring-2 focus:ring-[#1f96c9]" />

        <!-- Phone Number (max 11 digits) -->
        <input type="text" name="phone_number" placeholder="E.g. (+63) 923 456 789" required
          maxlength="11" pattern="^09\d{9}$"
          title="Enter a valid 11-digit mobile number starting with 09"
          class="p-3 border border-gray-300 rounded-lg font-montserrat focus:outline-none focus:ring-2 focus:ring-[#1f96c9]" />

        <!-- Email (HTML5 validation handles most cases) -->
        <input type="email" name="email" placeholder="E.g. juan.delacruz@gmail.com" required
          class="p-3 border border-gray-300 rounded-lg font-montserrat focus:outline-none focus:ring-2 focus:ring-[#1f96c9]" />


        <button type="submit" class="p-3 rounded-full bg-[#e4a314] text-white uppercase font-montserrat tracking-widest hover:bg-[#c58d0e] transition-all">Submit</button>
      </form>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-[#ffefd8] px-10 py-10 flex flex-wrap justify-between gap-8 text-sm font-montserrat">
    <!-- Left -->
    <div class="flex-1 min-w-[280px]">
      <img src="./images/logo.png" alt="Realiving Logo" class="h-10 mb-4">
      <div class="space-y-3 text-[#333]">
        <div class="flex gap-2">
          <img src="./images/location-icon.png" class="w-5 h-5" alt="Location">
          <p>MC Premier – EDSA Balintawak, Quezon City</p>
        </div>
        <div class="flex gap-2">
          <img src="./images/call-icon.png" class="w-5 h-5" alt="Call">
          <p>Company Number</p>
        </div>
        <div class="flex gap-2">
          <img src="./images/email-icon.png" class="w-5 h-5" alt="Email">
          <p>Company Email</p>
        </div>
        <div class="flex gap-2">
          <img src="./images/calendar-icon.png" class="w-5 h-5" alt="Hours">
          <p><strong>Mon–Fri:</strong> 7:00AM – 5:00PM</p>
        </div>
      </div>
    </div>

    <!-- Right -->
    <div class="flex-1 min-w-[200px] flex flex-col items-end text-right space-y-2">
      <h4 class="text-[#1f96c9] mb-3 font-semibold text-base">QUICK LINKS</h4>
      <a href="contact.html" class="hover:text-[#e4a314]">Contact Us</a>
      <a href="#" class="hover:text-[#e4a314]">Privacy Policy</a>
      <a href="#" class="hover:text-[#e4a314]">Terms & Conditions</a>
    </div>
  </footer>

  <!-- Bottom Bar -->
  <div class="bg-[#1f96c9] text-white text-center py-3 text-xs font-montserrat">
    ©2025 Realiving Design Center Corporation. All Rights Reserved.
  </div>

  <script src="script.js"></script>
</body>

</html>