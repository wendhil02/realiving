<?php
session_start();
include '../../connection/connection.php';
include '../design/mainbody.php';

include '../checkrole.php';



// Client/project defaults
$clientName   = '';
$projectScope = '';
$orderDate    = date('Y-m-d');
$contactNo    = '';
$address      = '';
$installType  = '';

// Search/item vars
$item       = null;
$query      = '';
$error      = '';
$success    = '';
$defaultImg = '../img/image_alt.jpg';
$itemImage  = $defaultImg;

// Handle search
if (!empty($_GET['query'])) {
  $query = trim($_GET['query']);

  // Prepare with parameterized query for security
  $stmt = $conn->prepare("
        SELECT * FROM items
        WHERE item_code LIKE CONCAT('%', ?, '%')
        OR item_name LIKE CONCAT('%', ?, '%')
        LIMIT 1
    ");

  if ($stmt) {
    $stmt->bind_param('ss', $query, $query);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res && $res->num_rows > 0) {
      $item = $res->fetch_assoc();

      // Process image if available
      if (!empty($item['item_image'])) {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($item['item_image']);
        $b64 = base64_encode($item['item_image']);
        $itemImage = "data:{$mimeType};base64,{$b64}";
      }
    } else {
      $error = "No item found matching '" . htmlspecialchars($query) . "'.";
    }
    $stmt->close();
  } else {
    $error = "Database error occurred. Please try again.";
  }
}

// Handle quick add to cart action
if (isset($_POST['quickAddToCart']) && !empty($_POST['item_code'])) {
  $code = trim($_POST['item_code']);
  $stmt = $conn->prepare("SELECT * FROM items WHERE item_code = ? LIMIT 1");
  if ($stmt) {
    $stmt->bind_param('s', $code);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows > 0) {
      $item = $res->fetch_assoc();
      // Process image like in search
      if (!empty($item['item_image'])) {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($item['item_image']);
        $b64 = base64_encode($item['item_image']);
        $itemImage = "data:{$mimeType};base64,{$b64}";
      }
      $success = "Item added to cart!";
    }
    $stmt->close();
  }
}

// Load recent client data
$recentClients = [];
$stmt = $conn->prepare("
    SELECT DISTINCT client_name, project_scope, contact_no, address, install_type 
    FROM orders 
    ORDER BY order_date DESC 
    LIMIT 5
");

if ($stmt && $stmt->execute()) {
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
    $recentClients[] = $row;
  }
  $stmt->close();
}

// Get popular items
$popularItems = [];
$stmt = $conn->prepare("
    SELECT i.item_code, i.item_name, COUNT(o.item_id) as count 
    FROM order_items o 
    JOIN items i ON o.item_id = i.id 
    GROUP BY i.id 
    ORDER BY count DESC 
    LIMIT 5
");

if ($stmt && $stmt->execute()) {
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
    $popularItems[] = $row;
  }
  $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Real Living Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: {
              DEFAULT: '#0F3460', // blue-900
              hover: '#0c2a4d'
            },
            accent: {
              DEFAULT: '#FFC107', // yellow-400
              hover: '#e6ac00'
            }
          }
        }
      }
    }
  </script>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }

    .animate-fade {
      animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
      }

      to {
        opacity: 1;
      }
    }

    .tooltip {
      position: relative;
    }

    .tooltip:hover .tooltip-text {
      display: block;
    }

    .tooltip-text {
      display: none;
      position: absolute;
      background: #333;
      color: white;
      padding: 5px 10px;
      border-radius: 4px;
      font-size: 12px;
      bottom: 100%;
      left: 50%;
      transform: translateX(-50%);
      white-space: nowrap;
      z-index: 10;
    }
  </style>
</head>

<body class="bg-gray-100">
  <!-- Notification Area -->
  <?php if ($error): ?>
    <div id="notification" class="fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded shadow-lg animate-fade">
      <div class="flex items-center">
        <i class="fas fa-exclamation-circle mr-2"></i>
        <p><?= htmlspecialchars($error) ?></p>
        <button class="ml-3" onclick="document.getElementById('notification').style.display='none';">
          <i class="fas fa-times"></i>
        </button>
      </div>
    </div>
  <?php elseif ($success): ?>
    <div id="notification" class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg animate-fade">
      <div class="flex items-center">
        <i class="fas fa-check-circle mr-2"></i>
        <p><?= htmlspecialchars($success) ?></p>
        <button class="ml-3" onclick="document.getElementById('notification').style.display='none';">
          <i class="fas fa-times"></i>
        </button>
      </div>
    </div>
    <script>
      setTimeout(() => {
        const notification = document.getElementById('notification');
        if (notification) {
          notification.style.display = 'none';
        }
      }, 3000);
    </script>
  <?php endif; ?>

  <main class="p-6 flex-col items-center justify-center">
    <!-- Branding Section -->
    <div class="max-w-7xl mx-auto mt-6 bg-white p-6 rounded-xl shadow-lg space-y-8">
      <!-- Header with Add Product Button -->
      <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-primary mb-4 sm:mb-0">
          <i class="fas fa-clipboard-list mr-2"></i>Quote Builder
        </h1>
        <div class="flex space-x-2">
          <button id="openModal" class="bg-accent hover:bg-accent-hover text-primary font-bold py-2 px-4 rounded-md flex items-center">
            <i class="fas fa-plus mr-2"></i> Add Product
          </button>
          <button id="clearCart" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded-md flex items-center">
            <i class="fas fa-trash mr-2"></i> Clear Cart
          </button>
        </div>
      </div>

      <!-- Quick Access Section -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Recent Clients -->
        <div class="bg-gray-50 p-4 rounded-lg shadow">
          <h3 class="text-lg font-semibold text-primary mb-3 flex items-center">
            <i class="fas fa-users mr-2"></i> Recent Clients
          </h3>
          <div class="overflow-x-auto max-h-40">
            <?php if (count($recentClients) > 0): ?>
              <table class="min-w-full bg-white">
                <thead class="bg-primary text-white text-xs">
                  <tr>
                    <th class="p-2">Client Name</th>
                    <th class="p-2">Project</th>
                    <th class="p-2">Action</th>
                  </tr>
                </thead>
                <tbody class="text-sm">
                  <?php foreach ($recentClients as $client): ?>
                    <tr class="hover:bg-gray-100 border-b">
                      <td class="p-2"><?= htmlspecialchars($client['client_name']) ?></td>
                      <td class="p-2"><?= htmlspecialchars($client['project_scope']) ?></td>
                      <td class="p-2">
                        <button class="text-primary hover:underline loadClient"
                          data-name="<?= htmlspecialchars($client['client_name']) ?>"
                          data-project="<?= htmlspecialchars($client['project_scope']) ?>"
                          data-contact="<?= htmlspecialchars($client['contact_no']) ?>"
                          data-address="<?= htmlspecialchars($client['address']) ?>"
                          data-install="<?= htmlspecialchars($client['install_type']) ?>">
                          <i class="fas fa-file-import"></i> Use
                        </button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            <?php else: ?>
              <p class="text-gray-500 text-center py-2">No recent clients found</p>
            <?php endif; ?>
          </div>
        </div>

        <!-- Popular Items -->
        <div class="bg-gray-50 p-4 rounded-lg shadow">
          <h3 class="text-lg font-semibold text-primary mb-3 flex items-center">
            <i class="fas fa-star mr-2"></i> Popular Items
          </h3>
          <div class="overflow-x-auto max-h-40">
            <?php if (count($popularItems) > 0): ?>
              <table class="min-w-full bg-white">
                <thead class="bg-primary text-white text-xs">
                  <tr>
                    <th class="p-2">Item Code</th>
                    <th class="p-2">Item Name</th>
                    <th class="p-2">Action</th>
                  </tr>
                </thead>
                <tbody class="text-sm">
                  <?php foreach ($popularItems as $popItem): ?>
                    <tr class="hover:bg-gray-100 border-b">
                      <td class="p-2"><?= htmlspecialchars($popItem['item_code']) ?></td>
                      <td class="p-2"><?= htmlspecialchars($popItem['item_name']) ?></td>
                      <td class="p-2">
                        <form method="POST" class="inline">
                          <input type="hidden" name="item_code" value="<?= htmlspecialchars($popItem['item_code']) ?>">
                          <button type="submit" name="quickAddToCart" class="text-primary hover:underline">
                            <i class="fas fa-cart-plus"></i> Add
                          </button>
                        </form>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            <?php else: ?>
              <p class="text-gray-500 text-center py-2">No popular items found</p>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Search Form -->
      <section class="space-y-2">
        <div class="bg-gray-50 p-4 rounded-lg shadow">
          <h3 class="text-lg font-semibold text-primary mb-3 flex items-center">
            <i class="fas fa-search mr-2"></i> Search Items
          </h3>
          <form method="GET" class="flex gap-2 items-center">
            <div class="relative flex-grow">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <i class="fas fa-search text-gray-400"></i>
              </span>
              <input
                type="text"
                name="query"
                id="searchInput"
                list="item_suggestions"
                value="<?= htmlspecialchars($query) ?>"
                placeholder="Enter item code or name"
                required
                class="w-full pl-10 pr-3 py-2 border border-primary rounded-md focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent" />
              <datalist id="item_suggestions"></datalist>
            </div>
            <button
              type="submit"
              class="bg-primary hover:bg-primary-hover text-accent font-bold py-2 px-4 rounded-md transition duration-200">
              Search
            </button>
          </form>
        </div>
      </section>

      <!-- Item Card (only shows if $item is found) -->
      <?php if ($item): ?>
        <div id="itemCard" class="flex flex-col md:flex-row bg-gray-50 rounded-lg shadow p-4 animate-fade">
          <img src="<?= $itemImage ?>" alt="<?= htmlspecialchars($item['item_name']) ?>"
            class="w-full md:w-48 h-48 object-cover rounded-md mb-4 md:mb-0" />
          <div class="md:ml-6 flex-1 space-y-4">
            <h2 class="text-xl font-semibold text-primary"><?= htmlspecialchars($item['item_name']) ?></h2>
            <div class="grid grid-cols-2 gap-4">
              <p><strong>Code:</strong> <?= htmlspecialchars($item['item_code']) ?></p>
              <p><strong>Category:</strong> <?= htmlspecialchars($item['item_category']) ?></p>
              <p><strong>Unit:</strong> <?= htmlspecialchars($item['item_unit']) ?></p>
              <p><strong>Price:</strong> ₱<?= number_format($item['item_price'], 2) ?></p>
            </div>

            <!-- Dimensions Inputs -->
            <h3 class="font-semibold text-primary">Dimensions</h3>
            <div class="grid grid-cols-3 gap-4">
              <label class="block">
                <span class="text-sm font-medium text-gray-700">Width (mm)</span>
                <div class="flex items-center">
                  <input
                    type="number"
                    id="widthInput"
                    step="0.01"
                    value="<?= htmlspecialchars($item['item_width'] ?? 0) ?>"
                    class="w-full mt-1 px-3 py-2 border border-primary rounded-md focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent" />
                </div>
              </label>
              <label class="block">
                <span class="text-sm font-medium text-gray-700">Height (mm)</span>
                <div class="flex items-center">
                  <input
                    type="number"
                    id="heightInput"
                    step="0.01"
                    value="<?= htmlspecialchars($item['item_height'] ?? 0) ?>"
                    class="w-full mt-1 px-3 py-2 border border-primary rounded-md focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent" />
                </div>
              </label>
              <label class="block">
                <span class="text-sm font-medium text-gray-700">Length/Depth (mm)</span>
                <div class="flex items-center">
                  <input
                    type="number"
                    id="lengthInput"
                    step="0.01"
                    value="<?= htmlspecialchars($item['item_length'] ?? 0) ?>"
                    class="w-full mt-1 px-3 py-2 border border-primary rounded-md focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent" />
                </div>
              </label>
            </div>

            <hr class="my-2" />

            <div class="flex items-center space-x-2">
              <div class="flex items-center">
                <button id="qtyMinus" class="bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded-l-md">
                  <i class="fas fa-minus"></i>
                </button>
                <input
                  type="number"
                  id="qty"
                  min="1"
                  value="1"
                  class="w-16 px-2 py-1 border-y border-gray-300 text-center focus:outline-none" />
                <button id="qtyPlus" class="bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded-r-md">
                  <i class="fas fa-plus"></i>
                </button>
              </div>
              <button
                id="addToCart"
                data-code="<?= htmlspecialchars($item['item_code']) ?>"
                data-name="<?= htmlspecialchars($item['item_name']) ?>"
                data-unit="<?= htmlspecialchars($item['item_unit']) ?>"
                data-category="<?= htmlspecialchars($item['item_category']) ?>"
                data-price="<?= $item['item_price'] ?>"
                data-labor="<?= $item['item_labor_cost'] ?>"
                data-img="<?= $itemImage ?>"
                class="bg-accent hover:bg-accent-hover text-primary font-bold py-2 px-4 rounded-md flex items-center transition duration-200">
                <i class="fas fa-cart-plus mr-2"></i> Add to Quote
              </button>
            </div>
          </div>
        </div>
      <?php endif; ?>

      <form method="POST" action="generate_pdf.php" id="quotationForm">
        <!-- Client & Project Details -->
        <section class="space-y-4 bg-gray-50 p-6 rounded-lg shadow mt-6">
          <h1 class="text-2xl font-bold text-primary flex items-center">
            <i class="fas fa-user-tie mr-2"></i> Client & Project Details
          </h1>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <label class="block">
              <span class="text-sm font-medium text-gray-700">Client Name</span>
              <input type="text" name="client_name" id="client_name" placeholder="Client Name" required
                class="w-full mt-1 px-3 py-2 border border-primary rounded-md focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent" />
            </label>
            <label class="block">
              <span class="text-sm font-medium text-gray-700">Project Scope</span>
              <input type="text" name="project_scope" id="project_scope" placeholder="Project Scope"
                class="w-full mt-1 px-3 py-2 border border-primary rounded-md focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent" />
            </label>
            <label class="block">
              <span class="text-sm font-medium text-gray-700">Order Date</span>
              <input type="date" name="order_date" id="order_date" value="<?= $orderDate ?>"
                class="w-full mt-1 px-3 py-2 border border-primary rounded-md focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent" />
            </label>
            <label class="block">
              <span class="text-sm font-medium text-gray-700">Contact No.</span>
              <input type="text" name="contact_no" id="contact_no" placeholder="Contact No."
                class="w-full mt-1 px-3 py-2 border border-primary rounded-md focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent" />
            </label>
            <label class="block">
              <span class="text-sm font-medium text-gray-700">Address</span>
              <input type="text" name="address" id="address" placeholder="Address"
                class="w-full mt-1 px-3 py-2 border border-primary rounded-md focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent" />
            </label>
            <label class="block">
              <span class="text-sm font-medium text-gray-700">Type of Installation</span>
              <input type="text" name="install_type" id="install_type" placeholder="Type of Installation"
                class="w-full mt-1 px-3 py-2 border border-primary rounded-md focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent" />
            </label>
          </div>
        </section>

        <!-- Cart Table -->
        <section class="mt-8">
          <h2 class="text-2xl font-bold text-primary mb-4 flex items-center">
            <i class="fas fa-shopping-cart mr-2"></i> Cart
            <span id="cartCount" class="ml-2 bg-accent text-primary text-sm py-1 px-2 rounded-full">0</span>
          </h2>
          <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-primary text-accent">
                <tr>
                  <th class="px-3 py-3 text-xs">Image</th>
                  <th class="px-3 py-3 text-xs">No.</th>
                  <th class="px-3 py-3 text-xs">Item</th>
                  <th class="px-3 py-3 text-xs">Width (mm)</th>
                  <th class="px-3 py-3 text-xs">Height (mm)</th>
                  <th class="px-3 py-3 text-xs">Length (mm)</th>
                  <th class="px-3 py-3 text-xs">Area (m)</th>
                  <th class="px-3 py-3 text-xs">Unit</th>
                  <th class="px-3 py-3 text-xs">Qty</th>
                  <th class="px-3 py-3 text-xs">Unit Price</th>
                  <th class="px-3 py-3 text-xs">Cabinet Cost</th>
                  <th class="px-3 py-3 text-xs">Labor Cost/Unit</th>
                  <th class="px-3 py-3 text-xs">Total Labor</th>
                  <th class="px-3 py-3 text-xs">Total Amount</th>
                  <th class="px-3 py-3 text-xs">Actions</th>
                </tr>
              </thead>
              <tbody id="cartTable" class="bg-white divide-y divide-gray-200">
                <!-- Cart items will be populated here -->
                <tr id="emptyCart">
                  <td colspan="15" class="px-3 py-4 text-gray-500 text-center">
                    Your cart is empty. Search for items to add them to your quote.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Totals -->
          <div class="mt-6 p-4 bg-gray-50 rounded-lg shadow space-y-3 max-w-md ml-auto">
            <h3 class="text-lg font-semibold text-primary mb-2">Summary</h3>
            <div class="flex justify-between">
              <span class="font-semibold">Grand Total:</span>
              <span>₱<span id="grandTotal">0.00</span></span>
            </div>
            <div class="flex justify-between items-center">
              <label class="font-semibold" for="discount">Discount (%):</label>
              <div class="flex items-center">
                <input
                  type="number"
                  id="discount"
                  name="discount"
                  value="0"
                  min="0"
                  max="100"
                  class="w-20 px-2 py-1 border border-primary rounded-md focus:outline-none" />
                <div class="tooltip ml-1">
                  <i class="fas fa-info-circle text-gray-500"></i>
                  <span class="tooltip-text">Applied to the total amount</span>
                </div>
              </div>
            </div>
            <div class="flex justify-between">
              <span class="font-semibold">Total Labor Cost:</span>
              <span>₱<span id="laborTotal">0.00</span></span>
            </div>
            <div class="flex justify-between border-t border-gray-300 pt-2 mt-2">
              <span class="font-semibold">Final Total:</span>
              <span class="text-xl font-bold text-primary">₱<span id="finalTotal">0.00</span></span>
            </div>
          </div>
        </section>

        <!-- Employee Name -->
        <div class="mt-6 max-w-md ml-auto">
          <label class="block mb-2">
            <span class="text-sm font-medium text-gray-700">Employee Name</span>
            <input type="text" name="employee_name" id="employee_name" required
              class="w-full mt-1 px-3 py-2 border border-primary rounded-md focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent"
              placeholder="Enter your name" />
          </label>
        </div>

        <div class="flex justify-end mt-4 space-x-2">
          <button type="button" id="saveQuote"
            class="bg-primary hover:bg-primary-hover text-white font-bold py-2 px-4 rounded-md transition duration-200">
            <i class="fas fa-save mr-2"></i> Save Quote
          </button>
          <button type="submit"
            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md transition duration-200">
            <i class="fas fa-file-excel mr-2"></i> Download Excel
          </button>
        </div>
        <input type="hidden" name="cart_data" id="cart_data" />
      </form>

    </div>
  </main>

  <!-- Modal -->
  <div id="modal"
    class="fixed inset-0 bg-black bg-opacity-60 hidden items-start justify-center overflow-auto p-6 mt-[50px] z-50">
    <div class="bg-white w-full max-w-4xl rounded-xl overflow-hidden shadow-lg">
      <div class="flex justify-between bg-primary text-accent px-4 py-3">
        <h3 class="text-lg font-semibold">Add New Item</h3>
        <button id="closeModal" class="text-2xl text-white">&times;</button>
      </div>
      <div id="modalBody" class="p-4 max-h-[80vh] overflow-y-auto">
        <div class="flex justify-center items-center py-8">
          <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-primary"></div>
          <p class="ml-3 text-gray-500">Loading…</p>
        </div>
      </div>
    </div>
  </div>

  <!-- JavaScript -->
  <script>
    // --- Utility Functions ---
    function formatNumber(num) {
      return parseFloat(num).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }

    function calculateArea(width, height, length) {
      // Convert from mm to m² and calculate area
      const widthM = parseFloat(width) / 1000;
      const heightM = parseFloat(height) / 1000;
      const lengthM = parseFloat(length) / 1000;

      // Calculate based on item dimensions
      return (widthM * heightM).toFixed(2);
    }

    function calculateTotals() {
      const cartItems = document.querySelectorAll('#cartTable tr:not(#emptyCart)');
      let grandTotal = 0;
      let laborTotal = 0;

      if (cartItems.length > 0) {
        document.getElementById('emptyCart').style.display = 'none';
      } else {
        document.getElementById('emptyCart').style.display = '';
      }

      cartItems.forEach((item, index) => {
        // Update row numbers
        const cells = item.querySelectorAll('td');
        if (cells.length > 1) {
          cells[1].textContent = (index + 1);
        }

        // Add to totals
        const itemTotal = parseFloat(item.getAttribute('data-total') || 0);
        const itemLabor = parseFloat(item.getAttribute('data-labor-total') || 0);

        grandTotal += itemTotal;
        laborTotal += itemLabor;
      });

      // Update display of totals
      document.getElementById('grandTotal').textContent = formatNumber(grandTotal);
      document.getElementById('laborTotal').textContent = formatNumber(laborTotal);

      // Calculate final total with discount
      const discount = parseFloat(document.getElementById('discount').value || 0) / 100;
      const finalTotal = grandTotal * (1 - discount);
      document.getElementById('finalTotal').textContent = formatNumber(finalTotal);

      // Update cart count
      document.getElementById('cartCount').textContent = cartItems.length;

      // Update hidden field for form submission
      updateCartData();
    }

    function updateCartData() {
      const cartItems = document.querySelectorAll('#cartTable tr:not(#emptyCart)');
      const cartData = [];

      cartItems.forEach(item => {
        const itemData = {
          item_code: item.getAttribute('data-code'),
          item_name: item.getAttribute('data-name'),
          width: item.getAttribute('data-width'),
          height: item.getAttribute('data-height'),
          length: item.getAttribute('data-length'),
          area: item.getAttribute('data-area'),
          unit: item.getAttribute('data-unit'),
          qty: item.getAttribute('data-qty'),
          price: item.getAttribute('data-price'),
          cabinet_cost: item.getAttribute('data-cabinet-cost'),
          labor_cost: item.getAttribute('data-labor'),
          labor_total: item.getAttribute('data-labor-total'),
          total: item.getAttribute('data-total'),
          image: item.getAttribute('data-img')
        };
        cartData.push(itemData);
      });

      document.getElementById('cart_data').value = JSON.stringify(cartData);
    }

    function addToCart(item, qty, width, height, length) {
      // Parse values
      const price = parseFloat(item.dataset.price);
      const laborCost = parseFloat(item.dataset.labor);
      const quantity = parseInt(qty);

      // Calculate area
      const area = calculateArea(width, height, length);

      // Calculate totals
      const cabinetCost = price * quantity;
      const totalLaborCost = laborCost * quantity;
      const totalAmount = cabinetCost + totalLaborCost;

      // Check if item is already in cart
      const existingItem = document.querySelector(`#cartTable tr[data-code="${item.dataset.code}"]`);

      if (existingItem) {
        // Update quantity and totals if item exists
        const existingQty = parseInt(existingItem.getAttribute('data-qty'));
        const newQty = existingQty + quantity;

        existingItem.setAttribute('data-qty', newQty);
        existingItem.setAttribute('data-width', width);
        existingItem.setAttribute('data-height', height);
        existingItem.setAttribute('data-length', length);
        existingItem.setAttribute('data-area', area);
        existingItem.setAttribute('data-cabinet-cost', price * newQty);
        existingItem.setAttribute('data-labor-total', laborCost * newQty);
        existingItem.setAttribute('data-total', (price + laborCost) * newQty);

        // Update display
        const cells = existingItem.querySelectorAll('td');
        cells[3].textContent = width;
        cells[4].textContent = height;
        cells[5].textContent = length;
        cells[6].textContent = area;
        cells[8].textContent = newQty;
        cells[10].textContent = '₱' + formatNumber(price * newQty);
        cells[12].textContent = '₱' + formatNumber(laborCost * newQty);
        cells[13].textContent = '₱' + formatNumber((price + laborCost) * newQty);
      } else {
        // Create new row
        const tr = document.createElement('tr');
        tr.className = 'hover:bg-gray-100';
        tr.setAttribute('data-code', item.dataset.code);
        tr.setAttribute('data-name', item.dataset.name);
        tr.setAttribute('data-unit', item.dataset.unit);
        tr.setAttribute('data-category', item.dataset.category);
        tr.setAttribute('data-price', price);
        tr.setAttribute('data-labor', laborCost);
        tr.setAttribute('data-width', width);
        tr.setAttribute('data-height', height);
        tr.setAttribute('data-length', length);
        tr.setAttribute('data-area', area);
        tr.setAttribute('data-qty', quantity);
        tr.setAttribute('data-cabinet-cost', cabinetCost);
        tr.setAttribute('data-labor-total', totalLaborCost);
        tr.setAttribute('data-total', totalAmount);
        tr.setAttribute('data-img', item.dataset.img);

        tr.innerHTML = `
            <td class="px-3 py-2">
                <img src="${item.dataset.img}" alt="${item.dataset.name}" class="w-10 h-10 object-cover rounded">
            </td>
            <td class="px-3 py-2"></td>
            <td class="px-3 py-2">${item.dataset.name}</td>
            <td class="px-3 py-2">${width}</td>
            <td class="px-3 py-2">${height}</td>
            <td class="px-3 py-2">${length}</td>
            <td class="px-3 py-2">${area}</td>
            <td class="px-3 py-2">${item.dataset.unit}</td>
            <td class="px-3 py-2">${quantity}</td>
            <td class="px-3 py-2">₱${formatNumber(price)}</td>
            <td class="px-3 py-2">₱${formatNumber(cabinetCost)}</td>
            <td class="px-3 py-2">₱${formatNumber(laborCost)}</td>
            <td class="px-3 py-2">₱${formatNumber(totalLaborCost)}</td>
            <td class="px-3 py-2">₱${formatNumber(totalAmount)}</td>
            <td class="px-3 py-2">
                <button type="button" class="editItem text-blue-600 hover:text-blue-800 mr-2">
                    <i class="fas fa-edit"></i>
                </button>
                <button type="button" class="removeItem text-red-600 hover:text-red-800">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        `;

        document.getElementById('cartTable').appendChild(tr);
      }

      // Recalculate totals
      calculateTotals();

      // Show success notification
      showNotification('Item added to cart!', 'success');
    }

    function showNotification(message, type = 'success') {
      // Remove existing notification
      const existingNotification = document.getElementById('notification');
      if (existingNotification) {
        existingNotification.remove();
      }

      // Create new notification
      const notification = document.createElement('div');
      notification.id = 'notification';
      notification.className = `fixed top-4 right-4 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white px-4 py-2 rounded shadow-lg animate-fade`;

      notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle mr-2"></i>
            <p>${message}</p>
            <button class="ml-3" onclick="this.parentNode.parentNode.style.display='none';">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;

      document.body.appendChild(notification);

      // Auto-hide after 3 seconds
      setTimeout(() => {
        if (notification) {
          notification.style.display = 'none';
        }
      }, 3000);
    }

    // --- Event Listeners ---
    document.addEventListener('DOMContentLoaded', function() {
      // Quantity adjustment buttons
      document.getElementById('qtyMinus')?.addEventListener('click', function() {
        const qtyInput = document.getElementById('qty');
        if (qtyInput.value > 1) {
          qtyInput.value = parseInt(qtyInput.value) - 1;
        }
      });

      document.getElementById('qtyPlus')?.addEventListener('click', function() {
        const qtyInput = document.getElementById('qty');
        qtyInput.value = parseInt(qtyInput.value) + 1;
      });

      // Add to cart button
      document.getElementById('addToCart')?.addEventListener('click', function() {
        const qty = document.getElementById('qty').value;
        const width = document.getElementById('widthInput').value;
        const height = document.getElementById('heightInput').value;
        const length = document.getElementById('lengthInput').value;

        if (!width || !height || !length) {
          showNotification('Please enter all dimensions', 'error');
          return;
        }

        addToCart(this, qty, width, height, length);
      });

      // Load client data
      document.querySelectorAll('.loadClient').forEach(button => {
        button.addEventListener('click', function() {
          document.getElementById('client_name').value = this.dataset.name;
          document.getElementById('project_scope').value = this.dataset.project;
          document.getElementById('contact_no').value = this.dataset.contact;
          document.getElementById('address').value = this.dataset.address;
          document.getElementById('install_type').value = this.dataset.install;

          showNotification('Client data loaded!', 'success');
        });
      });

      // Item search suggestions
      const searchInput = document.getElementById('searchInput');
      if (searchInput) {
        searchInput.addEventListener('input', function() {
          const query = this.value.trim();
          if (query.length >= 2) {
            fetch(`get_suggestions.php?query=${encodeURIComponent(query)}`)
              .then(response => response.json())
              .then(data => {
                const datalist = document.getElementById('item_suggestions');
                datalist.innerHTML = '';

                data.forEach(item => {
                  const option = document.createElement('option');
                  option.value = item.item_code;
                  option.text = `${item.item_code} - ${item.item_name}`;
                  datalist.appendChild(option);
                });
              })
              .catch(error => console.error('Error fetching suggestions:', error));
          }
        });
      }

      // Discount input
      document.getElementById('discount')?.addEventListener('input', calculateTotals);

      // Clear cart button
      document.getElementById('clearCart')?.addEventListener('click', function() {
        if (confirm('Are you sure you want to clear all items from the cart?')) {
          const cartTable = document.getElementById('cartTable');
          // Remove all rows except the empty cart message
          const rows = cartTable.querySelectorAll('tr:not(#emptyCart)');
          rows.forEach(row => row.remove());

          // Show empty cart message
          document.getElementById('emptyCart').style.display = '';

          // Update totals
          calculateTotals();

          showNotification('Cart cleared!', 'success');
        }
      });

      // Remove item from cart
      document.addEventListener('click', function(e) {
        if (e.target.closest('.removeItem')) {
          const row = e.target.closest('tr');
          if (confirm('Remove this item from the cart?')) {
            row.remove();
            calculateTotals();
            showNotification('Item removed from cart', 'success');
          }
        }
      });

      // Edit item in cart
      document.addEventListener('click', function(e) {
        if (e.target.closest('.editItem')) {
          const row = e.target.closest('tr');
          const itemCode = row.getAttribute('data-code');
          const itemName = row.getAttribute('data-name');
          const qty = row.getAttribute('data-qty');
          const width = row.getAttribute('data-width');
          const height = row.getAttribute('data-height');
          const length = row.getAttribute('data-length');

          // Create edit form
          const editForm = `
                <div class="p-4 bg-gray-50 rounded-lg">
                    <h3 class="font-semibold text-lg mb-3">Edit ${itemName}</h3>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <label class="block">
                            <span class="text-sm font-medium text-gray-700">Width (mm)</span>
                            <input type="number" id="editWidth" value="${width}" class="w-full mt-1 px-3 py-2 border border-primary rounded-md">
                        </label>
                        <label class="block">
                            <span class="text-sm font-medium text-gray-700">Height (mm)</span>
                            <input type="number" id="editHeight" value="${height}" class="w-full mt-1 px-3 py-2 border border-primary rounded-md">
                        </label>
                        <label class="block">
                            <span class="text-sm font-medium text-gray-700">Length (mm)</span>
                            <input type="number" id="editLength" value="${length}" class="w-full mt-1 px-3 py-2 border border-primary rounded-md">
                        </label>
                        <label class="block">
                            <span class="text-sm font-medium text-gray-700">Quantity</span>
                            <input type="number" id="editQty" value="${qty}" min="1" class="w-full mt-1 px-3 py-2 border border-primary rounded-md">
                        </label>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" id="cancelEdit" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                            Cancel
                        </button>
                        <button type="button" id="saveEdit" data-row-id="${row.rowIndex}" class="bg-accent hover:bg-accent-hover text-primary px-4 py-2 rounded-md">
                            Save Changes
                        </button>
                    </div>
                </div>
            `;

          // Show edit form in modal
          document.getElementById('modalBody').innerHTML = editForm;
          document.getElementById('modal').style.display = 'flex';

          // Event listeners for edit form
          document.getElementById('cancelEdit').addEventListener('click', function() {
            document.getElementById('modal').style.display = 'none';
          });

          document.getElementById('saveEdit').addEventListener('click', function() {
            const newWidth = document.getElementById('editWidth').value;
            const newHeight = document.getElementById('editHeight').value;
            const newLength = document.getElementById('editLength').value;
            const newQty = document.getElementById('editQty').value;

            if (!newWidth || !newHeight || !newLength || !newQty) {
              showNotification('Please fill all fields', 'error');
              return;
            }

            // Calculate new values
            const price = parseFloat(row.getAttribute('data-price'));
            const laborCost = parseFloat(row.getAttribute('data-labor'));
            const area = calculateArea(newWidth, newHeight, newLength);
            const cabinetCost = price * newQty;
            const totalLaborCost = laborCost * newQty;
            const totalAmount = cabinetCost + totalLaborCost;

            // Update row data attributes
            row.setAttribute('data-width', newWidth);
            row.setAttribute('data-height', newHeight);
            row.setAttribute('data-length', newLength);
            row.setAttribute('data-area', area);
            row.setAttribute('data-qty', newQty);
            row.setAttribute('data-cabinet-cost', cabinetCost);
            row.setAttribute('data-labor-total', totalLaborCost);
            row.setAttribute('data-total', totalAmount);

            // Update row display
            const cells = row.querySelectorAll('td');
            cells[3].textContent = newWidth;
            cells[4].textContent = newHeight;
            cells[5].textContent = newLength;
            cells[6].textContent = area;
            cells[8].textContent = newQty;
            cells[10].textContent = '₱' + formatNumber(cabinetCost);
            cells[12].textContent = '₱' + formatNumber(totalLaborCost);
            cells[13].textContent = '₱' + formatNumber(totalAmount);

            // Recalculate totals
            calculateTotals();

            // Close modal
            document.getElementById('modal').style.display = 'none';

            showNotification('Item updated successfully!', 'success');
          });
        }
      });

      // Modal open/close
      document.getElementById('openModal')?.addEventListener('click', function() {
        // Load items in modal
        document.getElementById('modalBody').innerHTML = '<div class="flex justify-center items-center py-8"><div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-primary"></div><p class="ml-3 text-gray-500">Loading…</p></div>';
        document.getElementById('modal').style.display = 'flex';

        // Fetch items
        fetch('get_items.php')
          .then(response => response.json())
          .then(data => {
            let html = '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">';

            data.forEach(item => {
              const imageUrl = item.has_image ? `get_image.php?id=${item.id}` : '../img/image_alt.jpg';

              html += `
                        <div class="border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition">
                            <div class="h-40 bg-gray-100 flex items-center justify-center overflow-hidden">
                                <img src="${imageUrl}" alt="${item.item_name}" class="object-cover w-full h-full">
                            </div>
                            <div class="p-3">
                                <h4 class="font-semibold text-primary truncate">${item.item_name}</h4>
                                <p class="text-sm text-gray-600">${item.item_code}</p>
                                <p class="text-sm">₱${formatNumber(item.item_price)}</p>
                                <div class="mt-2">
                                    <form method="POST" class="inline">
                                        <input type="hidden" name="item_code" value="${item.item_code}">
                                        <button type="submit" name="quickAddToCart" class="w-full bg-accent hover:bg-accent-hover text-primary px-2 py-1 rounded text-sm">
                                            <i class="fas fa-cart-plus mr-1"></i> Add to Quote
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    `;
            });

            html += '</div>';
            document.getElementById('modalBody').innerHTML = html;
          })
          .catch(error => {
            document.getElementById('modalBody').innerHTML = '<p class="text-red-500 text-center py-4">Error loading items. Please try again.</p>';
            console.error('Error loading items:', error);
          });
      });

      document.getElementById('closeModal')?.addEventListener('click', function() {
        document.getElementById('modal').style.display = 'none';
      });

      // Close modal when clicking outside
      window.addEventListener('click', function(e) {
        const modal = document.getElementById('modal');
        if (e.target === modal) {
          modal.style.display = 'none';
        }
      });

      // Save quote
      document.getElementById('saveQuote')?.addEventListener('click', function() {
        const clientName = document.getElementById('client_name').value;
        const projectScope = document.getElementById('project_scope').value;
        const employeeName = document.getElementById('employee_name').value;

        if (!clientName || !employeeName) {
          showNotification('Please enter client name and employee name', 'error');
          return;
        }

        const cartItems = document.querySelectorAll('#cartTable tr:not(#emptyCart)');
        if (cartItems.length === 0) {
          showNotification('Cart is empty. Please add items to save.', 'error');
          return;
        }

        // Update cart data
        updateCartData();

        // Get form data
        const formData = new FormData(document.getElementById('quotationForm'));

        // Add current totals
        formData.append('grand_total', document.getElementById('grandTotal').textContent);
        formData.append('labor_total', document.getElementById('laborTotal').textContent);
        formData.append('final_total', document.getElementById('finalTotal').textContent);

        // Submit form to save_quote.php
        fetch('save_quote.php', {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              showNotification('Quote saved successfully!', 'success');
            } else {
              showNotification(data.message || 'Error saving quote', 'error');
            }
          })
          .catch(error => {
            showNotification('Error saving quote', 'error');
            console.error('Error:', error);
          });
      });

      // Initialize totals on page load
      calculateTotals();
    });
  </script>
</body>

</html>