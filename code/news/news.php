<?php
include '../database.php';
include '../header/header.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Realiving Design Center </title>
  <link rel="icon" type="image/png" href="./images/favicon.png">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&family=Montserrat&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./news.css">
</head>
<body>
<section class="sub-header">
    <h1>News</h1>
</section>

<section class="news-section">
    <div class="news-grid">
        <?php 
        // Fetch news items
        $sql = "SELECT id, image, title, description FROM news ORDER BY id DESC"; // Fetch id, image, title, and description
        $result = $conn->query($sql);

        if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="news-card">
                    <img src="/realiving_updated/code/<?php echo htmlspecialchars($row['image']); ?>" alt="News Image">
                    <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                    <p><?php echo htmlspecialchars($row['description']); ?></p>
                    <a href="news-template.php?id=<?php echo $row['id']; ?>" class="view-more">VIEW MORE</a> <!-- Pass the ID via URL -->
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No news available at the moment.</p>
        <?php endif; ?>
    </div>
</section>

<?php
$conn->close();
?>

<?php 
include '../ads/promo-banner.php';
include '../footer/footer.php'; 
?>

  <script src="script.js"></script>
</body>
</html>
