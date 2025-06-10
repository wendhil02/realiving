<?php include "../connection/connection.php";  include "header/headernav.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Realiving Design Center</title>
  <link rel="icon" type="image/png" href="/realiving_updated/code/images/favicon.png">
  <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&family=Montserrat&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="projects/all-projects.css" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
 
 <section class="sub-header">
  <h1>Projects</h1>
  <p class="project-categories">
    <a href="#" class="category-link active-category" data-category="all">ALL</a>
    <a href="#" class="category-link" data-category="site">SITE PROJECTS</a>
    <a href="#" class="category-link" data-category="commercial">COMMERCIAL SPACES</a>
    <a href="#" class="category-link" data-category="residential">RESIDENTIAL INTERIORS</a>
  </p>
</section>

<section class="project-heading">
  <h1>
    <span id="project-title-prefix">PROJECTS</span> |
    <span id="project-title-category">ALL</span>
  </h1>
</section>



  <div class="text-center px-6 py-12 max-w-2xl mx-auto" data-aos="fade-up">
        <!-- Icon -->
        <div class="mb-8">
            <i class="fas fa-tools text-6xl text-indigo-500 mb-4"></i>
        </div>
        
        <!-- Main Message -->
        <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6" style="font-family: 'Crimson Pro', serif;">
            Not Available for Now
        </h1>
        
        <p class="text-xl text-gray-600 mb-8" style="font-family: 'Montserrat', sans-serif;">
            We're working hard to bring you something amazing. This feature is currently under development and will be available soon.
        </p>
        
        <!-- Status Badge -->
        <div class="inline-flex items-center px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium mb-8">
            <i class="fas fa-clock mr-2"></i>
            Coming Soon
        </div>
        
        <!-- Additional Info -->
        <div class="bg-white/60 backdrop-blur-sm rounded-lg p-6 mb-8 border border-white/20">
            <h3 class="text-lg font-semibold text-gray-800 mb-3" style="font-family: 'Montserrat', sans-serif;">
                What to expect:
            </h3>
            <ul class="text-gray-600 space-y-2">
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    Enhanced user experience
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    New features and functionality
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    Improved performance
                </li>
            </ul>
        </div>
        
    </div>
    

<!-- 
<section class="projects">
  <div class="container">
    <?php
    $sql = "SELECT * FROM project ORDER BY id DESC";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        // Determine the category class based on your database field
        // Assuming you have a 'category' field in your database
        $categoryClass = isset($row['category']) ? strtolower($row['category']) : 'all';
        ?>
        <div class="card project-card" data-category="<?php echo htmlspecialchars($categoryClass); ?>">
          <div class="card-image">
            <img src="<?php echo htmlspecialchars($row['main_image']); ?>" alt="Main Image">
            <img class="hover-img" src="<?php echo htmlspecialchars($row['hover_image']); ?>" alt="Hover Image">
          </div>
          <div class="card-content">
            <a href="project-template-example.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="title">
              <?php echo htmlspecialchars($row['title']); ?>
            </a>
            <p class="location"><?php echo htmlspecialchars($row['address']); ?></p>
          </div>
        </div>
        <?php
      }
    } else {
      echo "<p>No projects available yet.</p>";
    }
    
    $conn->close();
    ?>
  </div>
  <a href="#" class="button">SHOW MORE</a>
</section> -->

<section class="cabinet-cost-section">
  <div class="cabinet-content">
    <div class="cabinet-text">
      <h2>Know Your Cabinet Cost with Confidence</h2>
      <p>
      Have a vision in mind but not sure where to begin? Let's talk. Our design experts are ready to guide you through every step—from concept to completion. Whether it's a modular setup or a fully customized build, we'll help bring your ideas to life with precision and creativity.
      </p>
      <a href="../appointment/appointment.php" class="cabinet-btn">BOOK AN APPOINTMENT NOW</a>
    </div>
    <div class="cabinet-image">
      <img src="img/about/background-image.jpg" alt="Kitchen cabinet design">
    </div>
  </div>
</section>

   <?php include 'footer.php'; ?>

<script>
  const categoryLinks = document.querySelectorAll('.category-link');
  const titlePrefix = document.getElementById('project-title-prefix');
  const titleCategory = document.getElementById('project-title-category');
  const projectCards = document.querySelectorAll('.project-card');

  categoryLinks.forEach(link => {
    link.addEventListener('click', function(e) {
      e.preventDefault();
      
      // Remove active from all links
      categoryLinks.forEach(link => link.classList.remove('active-category'));
      
      // Add active to clicked link
      this.classList.add('active-category');
      
      // Get category name and format title
      const categoryText = this.textContent.trim();
      titleCategory.textContent = categoryText.toUpperCase();
      
      // Get the data-category value
      const selectedCategory = this.getAttribute('data-category');
      
      // Filter the project cards
      projectCards.forEach(card => {
        const cardCategory = card.getAttribute('data-category');
        
        if (selectedCategory === 'all' || cardCategory === selectedCategory) {
          card.style.display = 'block';
        } else {
          card.style.display = 'none';
        }
      });
    });
  });
</script>

<style>
  /* Add smooth transition for card filtering */
  .project-card {
    transition: opacity 0.3s ease-in-out;
  }
  
  .project-card[style*="display: none"] {
    opacity: 0;
  }
</style>

</body>
</html>