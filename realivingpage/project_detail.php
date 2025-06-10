<?php
// Include your database connection
include '../connection/connection.php';

// Get the project id from the URL
if (isset($_GET['id'])) {
    $project_id = $_GET['id'];

    // Fetch the project details from the database
    $sql = "SELECT * FROM project WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the project data
        $project = $result->fetch_assoc();
    } else {
        echo "<p>Project not found.</p>";
        exit;
    }

    // Close the database connection
    $stmt->close();
} else {
    echo "<p>No project selected.</p>";
    exit;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($project['title']); ?> - Project Details</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<section class="project-details">
    <div class="container">
        <div class="header">
            <h1 class="project-title"><?php echo htmlspecialchars($project['title']); ?></h1>
            <p class="location"><?php echo htmlspecialchars($project['address']); ?></p>
        </div>

        <div class="project-gallery">
            <div class="main-image">
                <img src="../realivingpage/images/<?php echo htmlspecialchars($project['image']); ?>" alt="Main Image">
            </div>
            <div class="hover-image">
                <img src="<?php echo htmlspecialchars($project['hover_image']); ?>" alt="Hover Image">
            </div>
        </div>

        <div class="project-description">
            <h3>Description</h3>
            <p><?php echo nl2br(htmlspecialchars($project['description'])); ?></p>
        </div>

        <button class="back-btn" onclick="window.location.href='all-projects.php'">Back to Projects</button>
    </div>
</section>

<style>
  /* Global Styles */
  body {
    font-family: 'Roboto', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f0f0f0;
  }

  .project-details {
    background: linear-gradient(to bottom, #ffffff, #f7f7f7);
    padding: 50px 20px;
    color: #333;
  }

  .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
  }

  /* Header Section */
  .header {
    text-align: center;
    margin-bottom: 40px;
  }

  .project-title {
    font-size: 3rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 10px;
    letter-spacing: 2px;
  }

  .location {
    font-size: 1.2rem;
    color: #7f8c8d;
    font-weight: 500;
  }

  /* Gallery Section */
  .project-gallery {
    display: flex;
    justify-content: center;
    gap: 30px;
    margin-bottom: 50px;
    position: relative;
    flex-wrap: wrap;
  }

  .main-image {
    width: 100%;
    max-width: 850px;
    border-radius: 15px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s ease;
  }

  .main-image:hover {
    transform: scale(1.05);
  }

  .main-image img {
    width: 100%;
    height: auto;
  }

  .hover-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: opacity 0.4s ease-in-out;
    border-radius: 15px;
    overflow: hidden;
  }

  .project-gallery:hover .hover-image {
    opacity: 1;
  }

  /* Description Section */
  .project-description {
    max-width: 900px;
    margin: 0 auto;
    font-size: 1.1rem;
    color: #2c3e50;
    line-height: 1.8;
    margin-bottom: 40px;
  }

  .project-description h3 {
    font-size: 1.6rem;
    color: #2c3e50;
    margin-bottom: 20px;
    font-weight: bold;
  }

  .project-description p {
    font-size: 1.1rem;
    color: #7f8c8d;
    text-align: justify;
  }

  /* Button */
  .back-btn {
    background-color: #3498db;
    color: white;
    padding: 15px 30px;
    border: none;
    border-radius: 30px;
    font-size: 1.1rem;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.3s ease;
    display: inline-block;
    margin: 0 auto;
    text-align: center;
  }

  .back-btn:hover {
    background-color: #2980b9;
    transform: scale(1.05);
  }

  /* Responsive Design */
  @media (max-width: 768px) {
    .project-title {
      font-size: 2.2rem;
    }

    .location {
      font-size: 1rem;
    }

    .project-gallery {
      flex-direction: column;
      gap: 15px;
    }

    .back-btn {
      font-size: 1rem;
      padding: 12px 25px;
    }
  }
</style>

</body>
</html>
