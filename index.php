<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Split Screen Images</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    html, body {
      margin: 0;
      padding: 0;
      height: 100%;
    }
  </style>
</head>
<body class="h-screen w-screen overflow-hidden">

  <div class="relative h-full w-full flex">
    
    <!-- Left Side Image -->
    <a href="noblehome/user/index.php" class="w-1/2 h-full relative group">
      <!-- Overlay -->
      <div class="absolute inset-0 bg-black bg-opacity-50 z-10 group-hover:bg-opacity-0 transition-opacity duration-300"></div>
      <img src="realivingpage/images/background-image.jpg" alt="Nature Landscape" class="w-full h-full object-cover" />
      <div class="absolute inset-0 flex items-center justify-center z-20 group-hover:scale-110 transition-transform duration-300">
        <img src="logo/noblebg.png" alt="Nature Landscape Image" class="w-32 h-32 object-cover" />
      </div>
    </a>

    <!-- Right Side Image -->
    <a href="realivingpage/index.php" class="w-1/2 h-full relative group">
      <!-- Overlay -->
      <div class="absolute inset-0 bg-black bg-opacity-50 z-10 group-hover:bg-opacity-0 transition-opacity duration-300"></div>
      <img src="realivingpage/images/background-image2.jpg" alt="Cityscape" class="w-full h-full object-cover" />
      <div class="absolute inset-0 flex items-center justify-center z-20 group-hover:scale-110 transition-transform duration-300">
        <img src="logo/mmone.png" alt="Cityscape Image" class="w-auto h-[60px] object-cover" />
      </div>
    </a>

  </div>

</body>
</html>




