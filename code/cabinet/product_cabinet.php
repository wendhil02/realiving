<?php 
session_start(); 
include '../header/header.php';
include '../../connection/connection.php';

if (!isset($conn)) {
    die("Database connection failed. Please check your connection file.");
}

$sql = "SELECT 
            p.product_id,
            p.product_name,
            p.main_image,
            p.description,
            p.status,
            p.created_at,
            MIN(pt.price) as min_price,
            MAX(pt.price) as max_price,
            COUNT(pt.type_id) as type_count
        FROM products p
        LEFT JOIN product_types pt ON p.product_id = pt.product_id
        GROUP BY p.product_id, p.product_name, p.main_image, p.description, p.status, p.created_at
        ORDER BY p.created_at DESC";

$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Product Display</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="icon" type="image/png" sizes="32x32" href="../../logo/favicon.ico">
  <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&family=Montserrat&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../cabinet/design/product_cabinet.css">
</head>
<body>

  <!-- Stats Section -->
  <div class="stats-container">
    <div class="stats-card">
      <div class="stats-content">
      <h1>CABINET</h1>
        <!-- Search Bar -->
        <div class="search-filter">
          <div class="search-box">
            <i class="fas fa-search search-icon"></i>
            <input type="text" id="searchInput" placeholder="Search products...">
          </div>
          <select id="statusFilter">
            <option value="">All Status</option>
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
            <option value="Archived">Archived</option>
          </select>
        </div>
      </div>
    </div>
  </div>

  <!-- Products Grid -->
  <div class="products-container">
    <div id="products-grid" class="products-grid">
      <?php 
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()): 
      ?>
        <div class="product-card" 
             data-name="<?= strtolower(htmlspecialchars($row['product_name'] ?? '')) ?>"
             data-status="<?= htmlspecialchars($row['status'] ?? '') ?>">
          
          <!-- Product Image -->
          <div class="product-image-container">
            <?php if (!empty($row['main_image'])): ?>
              <img src="../../<?= htmlspecialchars($row['main_image']) ?>" 
                   alt="<?= htmlspecialchars($row['product_name'] ?? 'Product') ?>" 
                   class="product-image">
            <?php else: ?>
              <div class="placeholder-image">
                <i class="fas fa-image"></i>
              </div>
            <?php endif; ?>
            
            <!-- Status Badge -->
            <div class="status-badge <?= 
              $row['status'] === 'Active' ? 'active' : 
              ($row['status'] === 'Inactive' ? 'inactive' : 
              ($row['status'] === 'Archived' ? 'archived' : 'default')) ?>">
              <?= htmlspecialchars($row['status'] ?? 'Unknown') ?>
            </div>

            <!-- Types Count Badge -->
            <?php if ($row['type_count'] > 0): ?>
            <div class="variant-badge">
              <?= $row['type_count'] ?> <?= $row['type_count'] > 1 ? 'variants' : 'variant' ?>
            </div>
            <?php endif; ?>

            <!-- Hover Overlay -->
            <div class="hover-overlay">
              <a href="get_products.php?id=<?= $row['product_id'] ?? 0 ?>" class="view-button">
                <i class="fas fa-eye"></i> View Details
              </a>
            </div>
          </div>

          <!-- Product Info -->
          <div class="product-info">
            <h2 class="product-name">
              <?= htmlspecialchars($row['product_name'] ?? 'Unnamed Product') ?>
            </h2>
            
            <?php if (!empty($row['description'])): ?>
            <p class="product-description">
              <?= htmlspecialchars(substr($row['description'], 0, 80)) ?><?= strlen($row['description']) > 80 ? '...' : '' ?>
            </p>
            <?php endif; ?>

            <!-- Price Range -->
            <div class="price-range">
              <?php if ($row['min_price'] == $row['max_price']): ?>
                $<?= number_format($row['min_price'], 2) ?>
              <?php else: ?>
                $<?= number_format($row['min_price'], 2) ?> - ₱<?= number_format($row['max_price'], 2) ?>
              <?php endif; ?>
            </div>

            <!-- Created Date -->
            <div class="product-meta">
              <i class="fas fa-calendar-alt"></i>
              <?= date('M d, Y', strtotime($row['created_at'])) ?>
            </div>
          </div>
        </div>
      <?php 
        endwhile;
      } else { 
      ?>
        <div class="empty-state">
          <i class="fas fa-box-open empty-icon"></i>
          <h3 class="empty-title">No products found</h3>
          <p class="empty-description">Start by adding your first product to showcase your inventory.</p>
          <a href="add_product.php" class="add-product-button">
            <i class="fas fa-plus"></i> Add Your First Product
          </a>
        </div>
      <?php } ?>
    </div>
  </div>

  <!-- Loading Spinner -->
  <div id="loading" class="loading-overlay hidden">
    <div class="loading-content">
      <div class="loading-spinner"></div>
      <span>Loading...</span>
    </div>
  </div>

  <script>
    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function() {
      const searchValue = this.value.toLowerCase();
      filterProducts();
    });

    // Status filter
    document.getElementById('statusFilter').addEventListener('change', function() {
      filterProducts();
    });

    function filterProducts() {
      const searchValue = document.getElementById('searchInput').value.toLowerCase();
      const statusValue = document.getElementById('statusFilter').value;
      const products = document.querySelectorAll('.product-card');

      products.forEach(product => {
        const name = product.getAttribute('data-name');
        const status = product.getAttribute('data-status');
        
        const matchesSearch = name.includes(searchValue);
        const matchesStatus = statusValue === '' || status === statusValue;
        
        if (matchesSearch && matchesStatus) {
          product.style.display = 'block';
        } else {
          product.style.display = 'none';
        }
      });
    }

    // View toggle (could be expanded for list view)
    function toggleView() {
      const grid = document.getElementById('products-grid');
      const icon = document.getElementById('view-icon');
      const text = document.getElementById('view-text');
      
      if (grid.classList.contains('grid-view')) {
        grid.classList.remove('grid-view');
        grid.classList.add('list-view');
        icon.className = 'fas fa-list';
        text.textContent = 'List';
      } else {
        grid.classList.remove('list-view');
        grid.classList.add('grid-view');
        icon.className = 'fas fa-th';
        text.textContent = 'Grid';
      }
    }

    // Add some smooth animations on load
    window.addEventListener('load', function() {
      const cards = document.querySelectorAll('.product-card');
      cards.forEach((card, index) => {
        setTimeout(() => {
          card.style.opacity = '0';
          card.style.transform = 'translateY(20px)';
          card.style.transition = 'all 0.5s ease';  
          
          setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
          }, 50);
        }, index * 100);
      });
    });
  </script>
</body>
</html>

<?php $conn->close(); ?>