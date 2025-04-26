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
    }

    @keyframes flicker {
      0%, 19%, 21%, 23%, 25%, 54%, 56%, 100% {
        opacity: 1;
      }
      20%, 24%, 55% {
        opacity: 0;
      }
    }

    .flicker {
      animation: flicker 1.5s infinite;
    }

    .glow {
      text-shadow: 0 0 5px #fff, 0 0 10px #ff0000, 0 0 15px #ff0000, 0 0 20px #ff0000;
    }
  </style>
</head>
<body class="h-screen w-screen overflow-hidden">

  <div class="relative h-full w-full flex">
    
    <!-- Left Side -->
    <div class="w-1/2 h-full relative">
      <img src="code/images/background-image.jpg" alt="Nature" class="w-full h-full object-cover" />
      <div class="absolute inset-0 bg-black bg-opacity-60 flex items-center justify-center z-20">
        <div class="text-center">
          <img src="logo/noblebg.png" alt="Nature Logo" class="w-28 h-28 mx-auto mb-4 object-contain animate-bounce" />
          <h1 class="text-white text-3xl md:text-4xl font-bold glow flicker">Under Maintenance</h1>
        </div>
      </div>
    </div>

    <!-- Right Side -->
    <div class="w-1/2 h-full relative">
      <img src="code/images/background-image2.jpg" alt="City" class="w-full h-full object-cover" />
      <div class="absolute inset-0 bg-black bg-opacity-60 flex items-center justify-center z-20">
        <div class="text-center">
          <img src="logo/mmone.png" alt="City Logo" class="w-auto h-14 mx-auto mb-4 object-contain animate-bounce" />
          <h1 class="text-white text-3xl md:text-4xl font-bold glow flicker">Under Maintenance</h1>
        </div>
      </div>
    </div>

  </div>

</body>
</html>
