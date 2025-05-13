<?php
// Include your database connection
include 'database.php';

// Get the news ID from the URL
if (isset($_GET['id'])) {
    $news_id = $_GET['id'];

    // Fetch the full news details from the database
    $sql = "SELECT * FROM news WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $news_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the news data
        $news = $result->fetch_assoc();
    } else {
        echo "<p>News not found.</p>";
        exit;
    }

    // Close the database connection
    $stmt->close();
} else {
    echo "<p>No news selected.</p>";
    exit;
}

$conn->close();

include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($news['title']); ?> - News Details</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<section class="news-detail">
  <div class="news-hero" style="background-image: url('<?php echo htmlspecialchars($news['image']); ?>');">
    <div class="overlay">
      <div class="news-header">
        <h1 class="news-title"><?php echo htmlspecialchars($news['title']); ?></h1>
        <p class="news-category"><span>Category:</span> <?php echo htmlspecialchars($news['category']); ?></p>
        <p class="news-date"><span>Published on:</span> <?php echo date("F j, Y", strtotime($news['date_uploaded'])); ?></p>
      </div>
    </div>
  </div>

  <div class="container">
  <div class="news-description-card">
    <div class="news-icon">
      <span>📰</span>
    </div>
    <div class="news-text-content">
      <h3 class="news-section-title">Description</h3>
      <p class="news-body-text"><?php echo nl2br(htmlspecialchars($news['description'])); ?></p>
    </div>
  </div>
  <a href="news.php" class="back-to-news-btn">← Back to News</a>
</div>
</section>



<style>
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f3f6fa;
  }
  

  .news-hero {
    position: relative;
    height: 350px;
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: flex-end;
    justify-content: center;
  }

  .overlay {
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: flex-end;
    justify-content: center;
  }

  .news-header {
    color: #fff;
    text-align: center;
    padding: 40px 20px;
    max-width: 900px;
  }

  .news-title {
    font-size: 3rem;
    margin-bottom: 15px;
  }

  .news-category,
  .news-date {
    font-size: 1.1rem;
    margin: 5px 0;
    color: #ddd;
  }

  .news-category span,
  .news-date span {
    font-weight: bold;
    color: #fff;
  }

 /* Container and Card */
/* Container */
.container {
  max-width: 960px;
  margin: 0 auto;
  padding: 40px 20px;
}

/* News Description Card */
.news-description-card {
  display: flex;
  background: linear-gradient(to right, #f9f9f9, #ffffff);
  border-left: 8px solid #007acc;
  border-radius: 12px;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.07);
  padding: 30px;
  gap: 20px;
  transition: box-shadow 0.3s ease;
}

.news-description-card:hover {
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
}

/* Icon Box */
.news-icon {
  font-size: 2.5rem;
  color: #007acc;
  display: flex;
  align-items: start;
}

/* Text Section */
.news-text-content {
  flex: 1;
}

.news-section-title {
  font-size: 1.7rem;
  font-weight: 700;
  color: #2c3e50;
  margin-bottom: 15px;
}

.news-body-text {
  font-size: 1.1rem;
  line-height: 1.8;
  color: #555;
  white-space: pre-line;
}

/* Back Button */
.back-to-news-btn {
  display: inline-block;
  margin-top: 30px;
  background-color: #007acc;
  color: #fff;
  text-decoration: none;
  padding: 12px 24px;
  border-radius: 8px;
  font-weight: 500;
  transition: background-color 0.3s;
}

.back-to-news-btn:hover {
  background-color: #005f99;
}

/* Responsive Design */
@media (max-width: 768px) {
  .news-description-card {
    flex-direction: column;
    padding: 20px;
  }

  .news-icon {
    justify-content: center;
  }

  .news-section-title {
    font-size: 1.4rem;
  }

  .news-body-text {
    font-size: 1rem;
  }
}


</style>


</body>
</html>
