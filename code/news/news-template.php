<?php
include '../database.php';

if (isset($_GET['id'])) {
  $news_id = $_GET['id'];
  $sql = "SELECT * FROM news WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $news_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $news = $result->fetch_assoc();
  } else {
    echo "<p>News not found.</p>";
    exit;
  }

  $stmt->close();
} else {
  echo "<p>No news selected.</p>";
  exit;
}

$conn->close();
include '../header/header.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/png" href="./images/favicon.png">
  <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&family=Montserrat&display=swap" rel="stylesheet">
  <title><?php echo htmlspecialchars($news['title']); ?> - News Details</title>
  <link rel="stylesheet" href="./news-template.css">
</head>

<body>
  <section class="promo-banner"><?php include '../ads/promo-banner.php'; ?></section>
  <section class="news-template-section">
    <div class="news-content-wrapper">
      <h2 class="news-title-static"><?php echo htmlspecialchars($news['title']); ?></h2>
      <p class="news-category-line"><?php echo htmlspecialchars($news['category']); ?></p>
      <p><?php echo nl2br(htmlspecialchars($news['description'])); ?></p>

      <div class="news-image">
        <img src="/realiving_updated/code/<?php echo htmlspecialchars($news['image']); ?>" alt="News Image">
      </div>

      <div class="keywords">
        <span>KEYWORDS:</span>
        <?php
        $keywords = explode(',', $news['keywords']); // assumes keywords are stored as comma-separated
        foreach ($keywords as $kw) {
          echo '<a href="#">' . htmlspecialchars(trim($kw)) . '</a>';
        }
        ?>
      </div>
    </div>

    <div class="related-news">
      <h3>Related News</h3>
      <div class="related-news-grid">
        <?php
        $sql = "SELECT id, image, title, description FROM news WHERE id != ? ORDER BY date_uploaded DESC LIMIT 3";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $news_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($related = $result->fetch_assoc()):
        ?>
          <div class="related-card">
            <img src="/realiving_updated/code/<?php echo htmlspecialchars($related['image']); ?>" alt="Related News">
            <h4><?php echo htmlspecialchars($related['title']); ?></h4>
            <p><?php echo htmlspecialchars(substr($related['description'], 0, 100)); ?>...</p>
            <a href="news-template.php?id=<?php echo $related['id']; ?>">
              <button>READ MORE</button>
            </a>
          </div>
        <?php endwhile;
        $stmt->close(); ?>
      </div>
      <div class="pagination">
        <a href="#">PREV</a>
        <div class="line"></div>
        <a href="#">NEXT</a>
      </div>
    </div>
  </section>
  <div class="interested">
    <h1>Interested in customized cabinets?</h1>
    <button href="#">INQUIRE NOW</button>
  </div>
  <?php include '../footer/footer.php'; ?>
</body>

</html>