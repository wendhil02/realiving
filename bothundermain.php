<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Under Maintenance</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    html, body {
      margin: 0;
      padding: 0;
      height: 100%;
      font-family: 'Poppins', sans-serif;
    }

    .overlay {
      background: rgba(0, 0, 0, 0.6);
    }
  </style>
</head>
<body class="h-screen w-screen overflow-hidden bg-gray-900">

  <div class="flex flex-col md:flex-row h-full w-full">

    <!-- Left Side -->
    <div class="relative w-full md:w-1/2 h-1/2 md:h-full">
      <img src="code/images/background-image.jpg" alt="Background Nature" class="w-full h-full object-cover" />
      <div class="absolute inset-0 overlay flex flex-col items-center justify-center text-center p-6">
        <img src="logo/noblebg.png" alt="Logo 1" class="w-24 h-24 mb-6 object-contain" />
        <h1 class="text-white text-3xl md:text-4xl font-semibold tracking-wide mb-2">Site Under Maintenance</h1>
        <p class="text-gray-300 text-base md:text-lg">We are currently performing scheduled maintenance. Thank you for your patience.</p>
      </div>
    </div>

    <!-- Right Side -->
    <div class="relative w-full md:w-1/2 h-1/2 md:h-full">
      <img src="code/images/background-image2.jpg" alt="Background City" class="w-full h-full object-cover" />
      <div class="absolute inset-0 overlay flex flex-col items-center justify-center text-center p-6">
        <img src="logo/mmone.png" alt="Logo 2" class="w-24 h-24 mb-6 object-contain" />
        <h1 class="text-white text-3xl md:text-4xl font-semibold tracking-wide mb-2">We’ll Be Back Soon</h1>
        <p class="text-gray-300 text-base md:text-lg">Our website is currently undergoing improvements. We appreciate your understanding.</p>
      </div>
    </div>

  </div>

</body>
</html>

