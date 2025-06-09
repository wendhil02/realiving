<?php include '../connection/connection.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="header/header.css">
    <title>Document</title>
</head>
<header>
    <div><img src="images/logo.png" height="30"></div>
    <nav>
      <a href="index.php">HOME</a>
      <a href="about.php">CABINET</a>
      <a href="about.php">DIY MODULAR</a>
      <a href="about.php">ABOUT</a>
      <a href="services.php">SERVICES</a>
      <div class="dropdown">
        <a href="all-projects.php">PROJECTS ▾</a>
        <div class="dropdown-content">
    <?php
        $sql = "SELECT * FROM project ORDER BY id DESC"; 
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
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
      <a href="news.php">WHAT'S NEW</a>
      <a href="contact.php">CONTACT</a>
      <a href="XXXX" class="quote-button">GET QUOTE</a>
    </nav>
  </header>
<script src="script.js"></script>