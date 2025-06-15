<?php
session_start();
include '../connection/connection.php';
include 'header/headernav.php';

if (!isset($conn)) {
  die("Database connection failed. Please check your connection file.");
}

// Updated query to match new database schema
$sql = "SELECT 
            p.id as product_id,
            p.product_name,
            p.main_image_blob,
            p.main_image_type,
            p.main_image_name,
            p.description,
            p.status,
            p.created_at,
            MIN(pt.base_price) as min_price,
            MAX(pt.base_price) as max_price,
            COUNT(pt.id) as type_count
        FROM products p
        LEFT JOIN product_types pt ON p.id = pt.product_id
        WHERE p.status != 'deleted'
        GROUP BY p.id, p.product_name, p.main_image_blob, p.main_image_type, p.main_image_name, p.description, p.status, p.created_at
        ORDER BY p.created_at DESC";

$result = $conn->query($sql);

if (!$result) {
  die("Query failed: " . $conn->error);
}

// Function to display BLOB image
function displayBlobImage($blob, $type, $name, $alt = '')
{
  if (!empty($blob)) {
    $base64 = base64_encode($blob);
    return "data:$type;base64,$base64";
  }
  return null;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Product Display</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="icon" type="image/png" sizes="32x32" href="../../../logo/favicon.ico">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&family=Montserrat&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }

    .product-card {
      transition: all 0.3s ease;
    }

    .product-card:hover {
      transform: translateY(-5px);
    }

    .price-range {
      background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .line-clamp-2 {
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .status-active {
      background-color: #dcfce7;
      color: #166534;
    }

    .status-inactive {
      background-color: #fef3c7;
      color: #92400e;
    }

    .status-draft {
      background-color: #e0e7ff;
      color: #3730a3;
    }
  </style>
</head>

<body class="min-h-screen bg-gray-50">

  <!-- Stats Section -->
  <div class="container mx-auto px-4 py-6">
    <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
      <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-center space-x-6">
          <h1 class="text-3xl font-bold text-orange-900 font-montserrat">CABINET</h1>
          <div class="text-center">
            <div class="text-2xl font-bold text-indigo-600"><?= $result->num_rows ?></div>
            <div class="text-sm text-gray-600">Total Products</div>
          </div>
        </div>

        <!-- Search and Filter Controls -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
          <div class="relative">
            <input type="text" id="searchInput" placeholder="Search products..."
              class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent w-full sm:w-64">
            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
          </div>
          <select id="statusFilter" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="draft">Draft</option>
          </select>
          <button onclick="toggleView()" id="viewToggle" class="flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
            <i id="view-icon" class="fas fa-th"></i>
            <span id="view-text">Grid</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Products Grid -->
  <div class="container mx-auto px-4 pb-12">
    <div id="products-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
      <?php
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()):
          $imageUrl = displayBlobImage($row['main_image_blob'], $row['main_image_type'], $row['main_image_name']);
      ?>
          <div class="product-card bg-white rounded-lg shadow-lg hover:shadow-xl overflow-hidden border border-gray-200"
            data-name="<?= strtolower(htmlspecialchars($row['product_name'] ?? '')) ?>"
            data-status="<?= htmlspecialchars($row['status'] ?? '') ?>">

            <!-- Product Image -->
            <div class="relative group">
              <?php if ($imageUrl): ?>
                <img src="<?= $imageUrl ?>"
                  alt="<?= htmlspecialchars($row['product_name'] ?? 'Product') ?>"
                  class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300"
                  loading="lazy" />
              <?php else: ?>
                <div class="w-full h-48 bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                  <i class="fas fa-image text-4xl text-gray-400"></i>
                </div>
              <?php endif; ?>

              <!-- Status Badge -->
              <div class="absolute top-2 right-2">
                <span class="text-xs px-2 py-1 rounded-full font-medium capitalize status-<?= strtolower($row['status'] ?? 'draft') ?>">
                  <?= htmlspecialchars($row['status'] ?? 'Draft') ?>
                </span>
              </div>

              <!-- Types Count Badge -->
              <?php if ($row['type_count'] > 0): ?>
                <div class="absolute top-2 left-2">
                  <span class="bg-orange-900 text-white text-xs px-2 py-1 rounded-full font-medium">
                    <?= $row['type_count'] ?> <?= $row['type_count'] > 1 ? 'variants' : 'variant' ?>
                  </span>
                </div>
              <?php endif; ?>
              <a href="cabinet/get_products.php?id=<?= $row['product_id'] ?? 0 ?>" class="opacity-0 group-hover:opacity-100 text-indigo-600 px-4 py-2 rounded-lg font-medium transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 hover:bg-white">
                <!-- Hover Overlay -->
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 flex items-center justify-center">
                </div>
              </a>
            </div>

            <!-- Product Info -->
            <div class="p-4">
              <h2 class="font-semibold text-gray-800 mb-2 line-clamp-2 h-12 font-montserrat">
                <?= htmlspecialchars($row['product_name'] ?? 'Unnamed Product') ?>
              </h2>

              <?php if (!empty($row['description'])): ?>
                <p class="text-gray-600 text-sm mb-3 line-clamp-2 font-montserrat">
                  <?= htmlspecialchars(substr($row['description'], 0, 80)) ?><?= strlen($row['description']) > 80 ? '...' : '' ?>
                </p>
              <?php endif; ?>

              <!-- Price Range -->
              <?php if ($row['min_price'] > 0): ?>
                <div class="mb-3">
                  <?php if ($row['min_price'] == $row['max_price']): ?>
                    <span class="text-lg font-bold text-indigo-600">₱<?= number_format($row['min_price'], 2) ?></span>
                  <?php else: ?>
                    <span class="text-lg font-bold price-range">
                      ₱<?= number_format($row['min_price'], 2) ?> - ₱<?= number_format($row['max_price'], 2) ?>
                    </span>
                  <?php endif; ?>
                </div>
              <?php endif; ?>

              <!-- Created Date -->
              <div class="mt-3 pt-3 border-t border-gray-100">
                <span class="text-xs text-gray-500">
                  <i class="fas fa-calendar-alt mr-1"></i>
                  <?= date('M d, Y', strtotime($row['created_at'])) ?>
                </span>
              </div>
            </div>
          </div>
        <?php
        endwhile;
      } else {
        ?>
        <div class="col-span-full text-center py-16">
          <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
          <h3 class="text-xl font-semibold text-gray-600 mb-2">No products found</h3>
          <p class="text-gray-500 mb-6">Start by adding your first product to showcase your inventory.</p>
          <a href="add_product.php" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition inline-flex items-center">
            <i class="fas fa-plus mr-2"></i>Add Your First Product
          </a>
        </div>
      <?php } ?>
    </div>
  </div>

  <!-- Loading Spinner -->
  <div id="loading" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg flex items-center space-x-3">
      <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-indigo-600"></div>
      <span>Loading...</span>
    </div>
  </div>

  <script>
    // Search functionality
    document.getElementById('searchInput').addEventListener('input', debounce(filterProducts, 300));

    // Status filter
    document.getElementById('statusFilter').addEventListener('change', filterProducts);

    // Debounce function to limit search frequency
    function debounce(func, wait) {
      let timeout;
      return function executedFunction(...args) {
        const later = () => {
          clearTimeout(timeout);
          func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
      };
    }

    function filterProducts() {
      const searchValue = document.getElementById('searchInput').value.toLowerCase();
      const statusValue = document.getElementById('statusFilter').value.toLowerCase();
      const products = document.querySelectorAll('.product-card');
      let visibleCount = 0;

      products.forEach(product => {
        const name = product.getAttribute('data-name');
        const status = product.getAttribute('data-status').toLowerCase();

        const matchesSearch = !searchValue || name.includes(searchValue);
        const matchesStatus = !statusValue || status === statusValue;

        if (matchesSearch && matchesStatus) {
          product.style.display = 'block';
          product.style.animation = 'fadeIn 0.3s ease-in';
          visibleCount++;
        } else {
          product.style.display = 'none';
        }
      });

      // Update results count or show no results message
      updateResultsDisplay(visibleCount);
    }

    function updateResultsDisplay(count) {
      // You can add a results counter here if needed
      console.log(`${count} products visible`);
    }

    // View toggle functionality
    function toggleView() {
      const grid = document.getElementById('products-grid');
      const icon = document.getElementById('view-icon');
      const text = document.getElementById('view-text');

      if (grid.className.includes('xl:grid-cols-5')) {
        // Switch to list view (fewer columns)
        grid.className = grid.className.replace('xl:grid-cols-5', 'xl:grid-cols-2');
        grid.className = grid.className.replace('lg:grid-cols-4', 'lg:grid-cols-2');
        grid.className = grid.className.replace('md:grid-cols-3', 'md:grid-cols-1');
        icon.className = 'fas fa-list';
        text.textContent = 'List';
      } else {
        // Switch back to grid view
        grid.className = grid.className.replace('xl:grid-cols-2', 'xl:grid-cols-5');
        grid.className = grid.className.replace('lg:grid-cols-2', 'lg:grid-cols-4');
        grid.className = grid.className.replace('md:grid-cols-1', 'md:grid-cols-3');
        icon.className = 'fas fa-th';
        text.textContent = 'Grid';
      }
    }

    // Smooth animations on load
    window.addEventListener('load', function() {
      const cards = document.querySelectorAll('.product-card');
      cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';

        setTimeout(() => {
          card.style.transition = 'all 0.5s ease';
          card.style.opacity = '1';
          card.style.transform = 'translateY(0)';
        }, index * 50);
      });
    });

    // Add CSS animation for fade in
    const style = document.createElement('style');
    style.textContent = `
      @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
      }
    `;
    document.head.appendChild(style);
  </script>
</body>

</html>

<?php $conn->close(); ?>