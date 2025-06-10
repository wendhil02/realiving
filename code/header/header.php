<?php
include($_SERVER['DOCUMENT_ROOT'] . '/realiving/connection/connection.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../header/header.css">
    <title>Document</title>
</head>
<header>
    <div><img src="../images/logo.png" height="30"></div>
    <button class="hamburger" id="hamburger" aria-label="Menu">
        ☰
    </button>
    <nav id="nav-menu">
        <a href="../index.php">HOME</a>
        <a href="../cabinet/product_cabinet.php">CABINET</a>
        <a href="#">DIY MODULAR</a>
        <a href="../about/about.php">ABOUT</a>
        <a href="/realiving_updated/code/process/services.php">SERVICES</a>
        <div class="dropdown">
            <a href="/realiving_updated/code/projects/all-projects.php">PROJECTS ▾</a>
            <div class="dropdown-content">
                <?php
                $sql = "SELECT * FROM project ORDER BY id DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $projectId = $row['id'];
                        $projectTitle = htmlspecialchars($row['title']);
                        echo "<a href='project-template-example.php?id=$projectId'>$projectTitle</a>";
                    }
                } else {
                    echo "<a href='#'>No Projects Found</a>";
                }
                ?>
            </div>
        </div>
        <a href="../news/news.php">WHAT'S NEW</a>
        <a href="../contact/contact.php">CONTACT</a>
        <a href="XXXX" class="quote-button">GET QUOTE</a>
    </nav>
</header>

<body>

<script>
  const hamburger = document.getElementById('hamburger');
  const navMenu = document.getElementById('nav-menu');
  const dropdowns = document.querySelectorAll('.dropdown');

  hamburger.addEventListener('click', () => {
    navMenu.classList.toggle('active');
  });

  // Toggle dropdowns on mobile
  dropdowns.forEach(drop => {
    drop.addEventListener('click', function (e) {
      if (window.innerWidth <= 768) {
        e.preventDefault();
        this.classList.toggle('open');
      }
    });
  });
</script>


</body>

</html>