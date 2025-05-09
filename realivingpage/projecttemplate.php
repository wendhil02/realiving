<?php
include '../connection/connection.php';
include 'htmldesign/mainhead.php';
include 'htmldesign/top.php';

// Fetch all projects first
$projects = [];
$result = mysqli_query($conn, "SELECT * FROM projects ORDER BY created_at DESC");
while ($row = mysqli_fetch_assoc($result)) {
  $projects[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Projects</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

  <script>
    AOS.init();

    let currentMainProject = {};

    function showProject(title, description, image, projectIndex) {
      const mainTitle = document.getElementById('mainTitle');
      const mainDesc = document.getElementById('mainDesc');
      const mainImg = document.getElementById('mainImg');

      const oldTitle = mainTitle.innerText;
      const oldDescription = mainDesc.innerText;
      const oldImage = mainImg.src.split("/").pop();

      mainTitle.innerText = title;
      mainDesc.innerText = description;
      mainImg.src = "../uploads/" + image;

      currentMainProject = { title: oldTitle, description: oldDescription, image: oldImage, projectIndex: projectIndex };

      const relatedProjectGrid = document.getElementById('relatedProjects');
      const clickedProject = document.getElementById('project' + projectIndex);
      const oldMainProjectInGrid = document.createElement('div');
      oldMainProjectInGrid.classList.add('cursor-pointer', 'bg-white', 'rounded-xl', 'shadow-lg', 'hover:shadow-2xl', 'transition-all', 'transform', 'hover:scale-105', 'duration-300');
      oldMainProjectInGrid.setAttribute('id', 'project' + currentMainProject.projectIndex);
      oldMainProjectInGrid.setAttribute('onclick', `showProject('${currentMainProject.title}', '${currentMainProject.description}', '${currentMainProject.image}', ${currentMainProject.projectIndex})`);

      oldMainProjectInGrid.innerHTML = `
        <img src="../uploads/${currentMainProject.image}" class="w-full h-56 object-cover rounded-t-xl">
        <div class="p-6">
          <h3 class="text-xl font-bold text-gray-800">${currentMainProject.title}</h3>
          <div class="mt-4 text-sm text-gray-500">${currentMainProject.description}</div>
        </div>
      `;

      relatedProjectGrid.replaceChild(oldMainProjectInGrid, clickedProject);
    }

    document.addEventListener("DOMContentLoaded", function() {
      <?php if (count($projects) > 0): ?>
        showProject('<?php echo addslashes($projects[0]['title']); ?>', '<?php echo addslashes($projects[0]['description']); ?>', '<?php echo $projects[0]['image']; ?>', 0);
      <?php endif; ?>
    });
  </script>
</head>

<body class="bg-gray-50 text-gray-800 font-sans">

  <div class="max-w-7xl mx-auto mt-16 px-4 sm:px-6 lg:px-8">

    <!-- Project Overview Section -->
    <section class="mb-16 text-center">
      <h2 class="text-3xl font-semibold text-gray-900 mb-6">Project Overview</h2>
      <p class="text-lg text-gray-600">Discover our range of projects, showcasing creativity, innovation, and excellence in every detail.</p>
    </section>

    <!-- Main Project Details Section -->
    <div class="mb-16 text-center mt-[40px]" data-aos="fade-up">
      <h1 id="mainTitle" class="text-4xl font-extrabold text-gray-900 mb-6">
        <?php echo count($projects) > 0 ? htmlspecialchars($projects[0]['title']) : 'No projects available'; ?>
      </h1>
      <?php if (count($projects) > 0): ?>
        <div class="flex flex-col lg:flex-row items-center justify-between gap-8 px-4">
          <img id="mainImg" src="../uploads/<?php echo $projects[0]['image']; ?>" class="w-full lg:w-1/2 max-h-96 object-contain rounded-lg shadow-xl mx-auto" alt="Project Image">
          <div class="lg:w-1/2 text-center lg:text-left">
            <p id="mainDesc" class="mt-6 text-xl text-gray-600"><?php echo htmlspecialchars($projects[0]['description']); ?></p>
          </div>
        </div>
      <?php else: ?>
        <div class="text-gray-500 text-lg">There are no projects to display at the moment.</div>
      <?php endif; ?>
    </div>

    <!-- Related Projects Section -->
    <section data-aos="fade-up">
      <h2 class="text-3xl font-semibold text-gray-900 mb-8">Related Projects</h2>
      <div id="relatedProjects" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php for ($i = 1; $i < count($projects); $i++): ?>
          <div id="project<?php echo $i; ?>"
               onclick='showProject(
                 <?php echo json_encode($projects[$i]["title"]); ?>,
                 <?php echo json_encode($projects[$i]["description"]); ?>,
                 <?php echo json_encode($projects[$i]["image"]); ?>,
                 <?php echo $i; ?>
               )'
               class="cursor-pointer bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all transform hover:scale-105 duration-300"
               data-aos="fade-up" data-aos-duration="1000"
          >
            <img src="../uploads/<?php echo htmlspecialchars($projects[$i]['image']); ?>" class="w-full h-56 object-cover rounded-t-xl" alt="Project Image">
            <div class="p-6">
              <h3 class="text-xl font-bold text-gray-800"><?php echo htmlspecialchars($projects[$i]['title']); ?></h3>
            </div>
          </div>
        <?php endfor; ?>
      </div>
    </section>

  </div>

  <!-- Footer -->
  <div class="text-center py-6 bg-gray-800 text-gray-400 text-sm mt-16">
    © 2025 Realiving Design Center. All rights reserved.
  </div>

</body>
</html>

