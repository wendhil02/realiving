<?php 
session_start(); 
include '../../connection/connection.php';

if (!isset($conn)) {
    die("Database connection failed. Please check your connection file.");
}

// Updated query to get products with their minimum price from product_types
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
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
  <style>
    body { font-family: 'Poppins', sans-serif; }
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
  </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">


  <!-- Stats Section -->
  <div class="max-w-7xl mx-auto px-6 py-6">
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-6">
          <div class="text-center">
            <div class="text-2xl font-bold text-indigo-600"><?= $result->num_rows ?></div>
            <div class="text-sm text-gray-600">Total Products</div>
          </div>
          <div class="h-8 w-px bg-gray-300"></div>
          <div class="text-center">
            <div class="text-2xl font-bold text-green-600">
              <?php 
              $active_count = 0;
              $result_copy = $conn->query($sql);
              while($row = $result_copy->fetch_assoc()) {
                if ($row['status'] === 'Active') $active_count++;
              }
              echo $active_count;
              ?>
            </div>
            <div class="text-sm text-gray-600">Active Products</div>
          </div>
        </div>
        
        <!-- Search Bar -->
        <div class="flex items-center space-x-4">
          <div class="relative">
            <input type="text" id="searchInput" placeholder="Search products..." 
                   class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
          </div>
          <select id="statusFilter" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
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
  <div class="max-w-7xl mx-auto px-6 pb-12">
    <div id="products-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
      <?php 
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()): 
      ?>
        <div class="product-card bg-white rounded-xl shadow-sm hover:shadow-xl overflow-hidden" 
             data-name="<?= strtolower(htmlspecialchars($row['product_name'] ?? '')) ?>"
             data-status="<?= htmlspecialchars($row['status'] ?? '') ?>">
          
          <!-- Product Image -->
          <div class="relative group">
            <?php if (!empty($row['main_image'])): ?>
              <img src="../../<?= htmlspecialchars($row['main_image']) ?>" 
                   alt="<?= htmlspecialchars($row['product_name'] ?? 'Product') ?>" 
                   class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300" />
            <?php else: ?>
              <div class="w-full h-48 bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                <i class="fas fa-image text-4xl text-gray-400"></i>
              </div>
            <?php endif; ?>
            
            <!-- Status Badge -->
            <div class="absolute top-2 right-2">
              <span class="text-xs px-2 py-1 rounded-full font-medium <?php
                switch($row['status'] ?? '') {
                  case 'Active': echo 'bg-green-100 text-green-700'; break;
                  case 'Inactive': echo 'bg-yellow-100 text-yellow-700'; break;
                  case 'Archived': echo 'bg-red-100 text-red-700'; break;
                  default: echo 'bg-gray-100 text-gray-600';
                }
              ?>">
                <?= htmlspecialchars($row['status'] ?? 'Unknown') ?>
              </span>
            </div>

            <!-- Types Count Badge -->
            <?php if ($row['type_count'] > 0): ?>
            <div class="absolute top-2 left-2">
              <span class="bg-indigo-600 text-white text-xs px-2 py-1 rounded-full font-medium">
                <?= $row['type_count'] ?> <?= $row['type_count'] > 1 ? 'variants' : 'variant' ?>
              </span>
            </div>
            <?php endif; ?>

            <!-- Hover Overlay -->
            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 flex items-center justify-center">
              <a href="get_products.php?id=<?= $row['product_id'] ?? 0 ?>" 
                 class="opacity-0 group-hover:opacity-100 bg-white text-indigo-600 px-4 py-2 rounded-lg font-medium transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
                <i class="fas fa-eye mr-2"></i>View Details
              </a>
            </div>
          </div>

          <!-- Product Info -->
          <div class="p-4">
            <h2 class="font-semibold text-gray-800 mb-2 line-clamp-2 h-12">
              <?= htmlspecialchars($row['product_name'] ?? 'Unnamed Product') ?>
            </h2>
            
            <?php if (!empty($row['description'])): ?>
            <p class="text-gray-600 text-sm mb-3 line-clamp-2">
              <?= htmlspecialchars(substr($row['description'], 0, 80)) ?><?= strlen($row['description']) > 80 ? '...' : '' ?>
            </p>
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
      // This can be expanded to switch between grid and list view
      const grid = document.getElementById('products-grid');
      const icon = document.getElementById('view-icon');
      const text = document.getElementById('view-text');
      
      if (grid.className.includes('xl:grid-cols-5')) {
        grid.className = grid.className.replace('xl:grid-cols-5', 'xl:grid-cols-3');
        grid.className = grid.className.replace('lg:grid-cols-4', 'lg:grid-cols-2');
        icon.className = 'fas fa-list';
        text.textContent = 'List';
      } else {
        grid.className = grid.className.replace('xl:grid-cols-3', 'xl:grid-cols-5');
        grid.className = grid.className.replace('lg:grid-cols-2', 'lg:grid-cols-4');
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