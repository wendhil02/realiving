<?php
session_start();
include '../connection/connection.php';
include 'header/headernav.php';

$id = null;
$clientName   = '';
$projectScope = '';

// Get client information if ID is provided
if (isset($_GET['id'])) {
  $id = intval($_GET['id']);
  $stmt = $conn->prepare("SELECT clientname, nameproject FROM user_info WHERE id = ?");
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $res = $stmt->get_result();
  if ($res && $res->num_rows) {
    $row = $res->fetch_assoc();
    $clientName   = $row['clientname'];
    $projectScope = $row['nameproject'];
  }
  $stmt->close();
}

$orderDate   = date('Y-m-d');
$defaultImg  = '../img/image_alt.jpg';
$itemImage   = $defaultImg;
$item        = null;
$relatedItems = [];
$query       = '';
$error       = '';

// Search for items
if (!empty($_GET['query'])) {
  $query = $conn->real_escape_string(trim($_GET['query']));

  // Search for the main item (including material search)
  $stmt = $conn->prepare("
        SELECT * FROM items
        WHERE item_code LIKE CONCAT('%', ?, '%')
           OR item_name LIKE CONCAT('%', ?, '%')
           OR item_material LIKE CONCAT('%', ?, '%')
        LIMIT 1
    ");
  $stmt->bind_param('sss', $query, $query, $query);
  $stmt->execute();
  $res = $stmt->get_result();

  if ($res && $res->num_rows) {
    $item = $res->fetch_assoc();

    // Process main item image
    if (!empty($item['item_image'])) {
      $finfo    = new finfo(FILEINFO_MIME_TYPE);
      $mimeType = $finfo->buffer($item['item_image']);
      $b64      = base64_encode($item['item_image']);
      $itemImage = "data:{$mimeType};base64,{$b64}";
    }

    // Get related items from the same category (excluding the current item)
    if (!empty($item['item_category'])) {
      $relatedStmt = $conn->prepare("
                SELECT item_code, item_name, item_material, item_image, item_category, 
                       item_price, item_unit, item_width, item_height, item_length, 
                       item_labor_cost, dimension_combo
                FROM items 
                WHERE item_category = ? AND item_code != ?
                ORDER BY item_name ASC
            ");
      $relatedStmt->bind_param('ss', $item['item_category'], $item['item_code']);
      $relatedStmt->execute();
      $relatedRes = $relatedStmt->get_result();

      while ($relatedRow = $relatedRes->fetch_assoc()) {
        // Process related item images
        $relatedImg = $defaultImg;
        if (!empty($relatedRow['item_image'])) {
          $relatedFinfo = new finfo(FILEINFO_MIME_TYPE);
          $relatedMimeType = $relatedFinfo->buffer($relatedRow['item_image']);
          $relatedB64 = base64_encode($relatedRow['item_image']);
          $relatedImg = "data:{$relatedMimeType};base64,{$relatedB64}";
        }
        $relatedRow['processed_image'] = $relatedImg;
        $relatedItems[] = $relatedRow;
      }
      $relatedStmt->close();

      // Count total related products for display
      $totalRelatedCount = count($relatedItems);
    }
  } else {
    $error = "No item found matching '{$query}'.";
  }
  $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Customer Quotation Inquiry</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat&family=Poppins&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    /* Related Products Styling */
    .select-mini-product {
      transition: all 0.2s ease-in-out;
      min-height: 120px;
    }

    .select-mini-product:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(31, 150, 201, 0.15);
      border-color: #1f96c9;
    }

    .select-mini-product:active {
      transform: translateY(0) scale(0.95);
    }

    /* Text truncation */
    .line-clamp-2 {
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    /* Smooth transitions for show/hide */
    #relatedProductsContainer {
      transition: max-height 0.3s ease-in-out;
    }

    /* Grid responsive improvements */
    @media (max-width: 640px) {
      .grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }
    }

    @media (min-width: 1280px) {
      .xl\:grid-cols-8 {
        grid-template-columns: repeat(8, minmax(0, 1fr));
      }
    }

    /* Loading state */
    .select-mini-product.loading {
      opacity: 0.7;
      pointer-events: none;
    }

    /* Badge styling */
    .bg-gray-200 {
      background-color: #e5e7eb;
    }
  </style>
</head>

<body class="text-[#333] bg-gray-200">

  <main class="py-10 px-6 flex justify-center mt-15">
    <div class="max-w-6xl w-full bg-white rounded-xl shadow p-6 space-y-8">

      <!-- Search & Add Item -->
      <section class="space-y-4">
        <h2 class="text-2xl text-[#1f96c9] italic">Add Item to Cart</h2>
        <form method="GET" class="flex gap-2 items-center">
          <?php if ($id !== null): ?>
            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
          <?php endif; ?>
          <input type="text" name="query" id="searchInput" list="item_suggestions"
            value="<?= htmlspecialchars($query) ?>" placeholder="Enter code, name, or material" required
            class="flex-grow px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-[#1f96c9]" />
          <datalist id="item_suggestions"></datalist>
          <button type="submit" class="px-4 py-2 bg-[#e4a314] text-white uppercase rounded-full hover:bg-[#d4941a] transition">Search</button>
          <button type="button" id="exploreBtn" class="px-4 py-2 bg-[#1f96c9] text-white uppercase rounded-full hover:bg-[#1a85b8] transition">Explore</button>
        </form>

        <?php if ($error): ?>
          <p class="text-red-600 bg-red-50 border border-red-200 rounded-md p-3"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <?php if ($item): ?>
          <div id="itemCard" class="flex flex-col md:flex-row bg-gray-100 rounded-lg p-4 border">
            <img src="<?= $itemImage ?>" alt="<?= htmlspecialchars($item['item_name']) ?>" class="w-full md:w-32 h-32 object-cover rounded mb-4 md:mb-0" />
            <div class="md:ml-6 flex-1 space-y-2">
              <p><strong>Code:</strong> <?= htmlspecialchars($item['item_code']) ?></p>
              <p><strong>Name:</strong> <?= htmlspecialchars($item['item_name']) ?></p>
              <p><strong>Material:</strong> <?= htmlspecialchars($item['item_material'] ?? 'N/A') ?></p>
              <p><strong>Category:</strong> <?= htmlspecialchars($item['item_category'] ?? 'N/A') ?></p>

              <div class="grid grid-cols-3 gap-2">
                <div>
                  <label for="widthInput" class="block text-xs font-medium text-gray-600 mb-1">Width/Depth (mm)</label>
                  <input type="number" id="widthInput" step="0.01"
                    value="<?= htmlspecialchars($item['item_width'] ?? 0) ?>"
                    class="w-full px-2 py-1 border rounded-md focus:outline-none focus:ring-1 focus:ring-[#1f96c9]" />
                </div>
                <div>
                  <label for="heightInput" class="block text-xs font-medium text-gray-600 mb-1">Height (mm)</label>
                  <input type="number" id="heightInput" step="0.01"
                    value="<?= htmlspecialchars($item['item_height'] ?? 0) ?>"
                    class="w-full px-2 py-1 border rounded-md focus:outline-none focus:ring-1 focus:ring-[#1f96c9]" />
                </div>
                <div>
                  <label for="lengthInput" class="block text-xs font-medium text-gray-600 mb-1">Length (mm)</label>
                  <input type="number" id="lengthInput" step="0.01"
                    value="<?= htmlspecialchars($item['item_length'] ?? 0) ?>"
                    class="w-full px-2 py-1 border rounded-md focus:outline-none focus:ring-1 focus:ring-[#1f96c9]" />
                </div>
              </div>

              <div class="flex items-center space-x-2">
                <label for="qty" class="text-sm font-medium text-gray-600">Quantity:</label>
                <input type="number" id="qty" min="1" value="1"
                  class="w-16 px-2 py-1 border rounded-md focus:outline-none focus:ring-1 focus:ring-[#1f96c9]" />
                <button id="addToCart"
                  data-code="<?= htmlspecialchars($item['item_code']) ?>"
                  data-name="<?= htmlspecialchars($item['item_name']) ?>"
                  data-unit="<?= htmlspecialchars($item['item_unit']) ?>"
                  data-category="<?= htmlspecialchars($item['item_category']) ?>"
                  data-price="<?= $item['item_price'] ?>"
                  data-base-price="<?= $item['item_price'] ?>"
                  data-labor="<?= $item['item_labor_cost'] ?>"
                  data-original-width="<?= htmlspecialchars($item['item_width'] ?? 0) ?>"
                  data-original-height="<?= htmlspecialchars($item['item_height'] ?? 0) ?>"
                  data-original-length="<?= htmlspecialchars($item['item_length'] ?? 0) ?>"
                  data-dimension-combo="<?= htmlspecialchars($item['dimension_combo'] ?? '') ?>"
                  data-img="<?= $itemImage ?>"
                  class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition">
                  Add to Cart
                </button>
              </div>
            </div>
          </div>

          <?php if (!empty($relatedItems)): ?>
            <!-- Related Products Section -->
            <div class="mt-4 p-4 bg-gray-50 rounded-lg border">
              <div class="flex justify-between items-center mb-3">
                <h4 class="text-sm font-semibold text-gray-700">
                  Related Products from "<?= htmlspecialchars($item['item_category']) ?>" Category
                </h4>
                <span class="text-xs text-gray-500 bg-gray-200 px-2 py-1 rounded">
                  <?= count($relatedItems) ?> product<?= count($relatedItems) !== 1 ? 's' : '' ?> available
                </span>
              </div>

              <!-- Show/Hide Toggle for large lists -->
              <?php if (count($relatedItems) > 8): ?>
                <div class="mb-3">
                  <button id="toggleRelatedProducts" class="text-xs text-blue-600 hover:text-blue-800 underline">
                    Show all <?= count($relatedItems) ?> products
                  </button>
                </div>
              <?php endif; ?>

              <!-- Products Container -->
              <div id="relatedProductsContainer" class="<?= count($relatedItems) > 8 ? 'max-h-96 overflow-hidden' : '' ?> transition-all duration-300">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-3">
                  <?php foreach ($relatedItems as $index => $relatedItem): ?>
                    <div class="cursor-pointer select-mini-product hover:bg-white hover:shadow-md rounded-lg p-2 transition-all border border-transparent hover:border-blue-200 <?= $index >= 8 ? 'initially-hidden' : '' ?>"
                      data-code="<?= htmlspecialchars($relatedItem['item_code']) ?>"
                      data-name="<?= htmlspecialchars($relatedItem['item_name']) ?>"
                      data-material="<?= htmlspecialchars($relatedItem['item_material'] ?? 'N/A') ?>"
                      data-category="<?= htmlspecialchars($relatedItem['item_category']) ?>"
                      data-price="<?= $relatedItem['item_price'] ?? 0 ?>"
                      data-unit="<?= htmlspecialchars($relatedItem['item_unit'] ?? '') ?>"
                      data-width="<?= $relatedItem['item_width'] ?? 0 ?>"
                      data-height="<?= $relatedItem['item_height'] ?? 0 ?>"
                      data-length="<?= $relatedItem['item_length'] ?? 0 ?>"
                      data-labor="<?= $relatedItem['item_labor_cost'] ?? 0 ?>"
                      data-dimension-combo="<?= htmlspecialchars($relatedItem['dimension_combo'] ?? '') ?>"
                      data-img="<?= $relatedItem['processed_image'] ?>"
                      title="Click to view details">

                      <!-- Product Image -->
                      <div class="w-full h-16 mb-2 overflow-hidden rounded border bg-white">
                        <img src="<?= $relatedItem['processed_image'] ?>"
                          alt="<?= htmlspecialchars($relatedItem['item_name']) ?>"
                          class="w-full h-full object-cover hover:scale-105 transition-transform duration-200">
                      </div>

                      <!-- Product Info -->
                      <div class="space-y-1">
                        <p class="text-xs font-semibold text-gray-800 leading-tight line-clamp-2"
                          title="<?= htmlspecialchars($relatedItem['item_name']) ?>">
                          <?= htmlspecialchars(strlen($relatedItem['item_name']) > 20 ? substr($relatedItem['item_name'], 0, 20) . '...' : $relatedItem['item_name']) ?>
                        </p>

                        <p class="text-xs text-blue-600 font-medium" title="Item Code">
                          <?= htmlspecialchars($relatedItem['item_code']) ?>
                        </p>

                        <?php if (!empty($relatedItem['item_material'])): ?>
                          <p class="text-xs text-gray-600 leading-tight truncate"
                            title="Material: <?= htmlspecialchars($relatedItem['item_material']) ?>">
                            <?= htmlspecialchars(strlen($relatedItem['item_material']) > 15 ? substr($relatedItem['item_material'], 0, 15) . '...' : $relatedItem['item_material']) ?>
                          </p>
                        <?php endif; ?>

                        <!-- Price (optional) -->
                        <?php if (!empty($relatedItem['item_price'])): ?>
                          <p class="text-xs text-green-600 font-medium">
                            ₱<?= number_format($relatedItem['item_price'], 2) ?>
                          </p>
                        <?php endif; ?>

                        <!-- Click indicator -->
                        <div class="text-center mt-1">
                          <span class="text-xs text-blue-500 hover:text-blue-700">👆 View</span>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>

              <!-- Show More/Less Button for large lists -->
              <?php if (count($relatedItems) > 8): ?>
                <div class="text-center mt-3">
                  <button id="showMoreRelated" class="text-sm text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded transition-colors">
                    Show More (<?= count($relatedItems) - 8 ?> more)
                  </button>
                </div>
              <?php endif; ?>
            </div>

            <!-- Pass related products data to JavaScript -->
            <script>
              const relatedProductsData = <?= json_encode($relatedItems) ?>;
              const totalRelatedCount = <?= count($relatedItems) ?>;
            </script>
          <?php endif; ?>
        <?php endif; ?>
      </section>

   <!-- Cart Table -->
<!-- Cart Table -->
<section class="space-y-4">
  <h2 class="text-2xl text-[#1f96c9] italic">Your Cart</h2>
  
  <!-- Scrollable wrapper -->
  <div class="w-full overflow-x-auto max-h-[400px] overflow-y-auto border rounded shadow-sm">
    <table class="min-w-[900px] table-auto divide-y divide-gray-200">
      <thead class="bg-gray-50 sticky top-0 z-10">
        <tr>
          <th class="px-3 py-2 whitespace-nowrap bg-gray-50">Image</th>
          <th class="px-3 py-2 whitespace-nowrap bg-gray-50">No.</th>
          <th class="px-3 py-2 whitespace-nowrap bg-gray-50">Item</th>
          <th class="px-3 py-2 whitespace-nowrap bg-gray-50">Width/Depth (mm)</th>
          <th class="px-3 py-2 whitespace-nowrap bg-gray-50">Height (mm)</th>
          <th class="px-3 py-2 whitespace-nowrap bg-gray-50">Length (mm)</th>
          <th class="px-3 py-2 whitespace-nowrap bg-gray-50">Qty</th>
          <th class="px-3 py-2 whitespace-nowrap bg-gray-50">Total Amount</th>
          <th class="px-3 py-2 whitespace-nowrap bg-gray-50">Action</th>
        </tr>
      </thead>
      <tbody id="cartTable" class="bg-white divide-y divide-gray-200"></tbody>
    </table>
  </div>

  <!-- Totals -->
  <div class="mt-6 flex justify-end">
    <span class="text-lg font-semibold">Grand Total: ₱<span id="grandTotal">0.00</span></span>
  </div>

  <!-- Submit -->
  <div class="mt-4 flex justify-end">
    <button type="button" id="submitBtn" class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">Submit Quotation</button>
  </div>
</section>



    </div>
  </main>

  <!-- Modal for Client & Project Details -->
  <div id="clientDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-xl w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col">
      <div class="flex justify-between items-center border-b p-4">
        <h3 class="text-xl font-semibold text-[#1f96c9]">Client & Project Details</h3>
        <button id="closeClientModal" class="text-gray-500 hover:text-gray-700">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <div class="p-6 flex-1 overflow-auto">
        <form id="clientDetailsForm" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label for="modal_client_name" class="block text-sm font-medium mb-1">Client Name *</label>
              <input type="text" id="modal_client_name" name="client_name" value="<?= htmlspecialchars($clientName) ?>"
                required class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-[#1f96c9]" />
            </div>
            <div>
              <label for="modal_project_scope" class="block text-sm font-medium mb-1">Project Scope *</label>
              <input type="text" id="modal_project_scope" name="project_scope" value="<?= htmlspecialchars($projectScope) ?>"
                required class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-[#1f96c9]" />
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Order Date</label>
              <input type="date" name="order_date" value="<?= $orderDate ?>" readonly
                class="w-full px-3 py-2 bg-gray-100 rounded-md" />
            </div>
            <div>
              <label for="modal_contact_no" class="block text-sm font-medium mb-1">Contact No. *</label>
              <input type="text" id="modal_contact_no" name="contact_no" placeholder="Contact No."
                required class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-[#1f96c9]" />
            </div>
            <div>
              <label for="modal_address" class="block text-sm font-medium mb-1">Address</label>
              <input type="text" id="modal_address" name="address" placeholder="Address"
                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-[#1f96c9]" />
            </div>
            <div>
              <label for="modal_install_type" class="block text-sm font-medium mb-1">Installation Type</label>
              <input type="text" id="modal_install_type" name="install_type" placeholder="Type of Installation"
                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-[#1f96c9]" />
            </div>
          </div>

          <div class="mt-6 pt-4 border-t">
            <div class="flex justify-between items-center mb-4">
              <span class="text-lg font-semibold">Grand Total: ₱<span id="modalGrandTotal">0.00</span></span>
              <span class="text-sm text-gray-600">Items in cart: <span id="modalItemCount">0</span></span>
            </div>
            <div class="flex justify-end gap-4">
              <button type="button" id="cancelSubmit" class="px-6 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">Cancel</button>
              <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">Confirm & Submit</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal for Explore Products -->
  <div id="exploreModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-xl w-full max-w-5xl max-h-[90vh] overflow-hidden flex flex-col">
      <div class="flex justify-between items-center border-b p-4">
        <h3 class="text-xl font-semibold text-[#1f96c9]">Explore Products</h3>
        <button id="closeModal" class="text-gray-500 hover:text-gray-700">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <div class="p-4 flex-1 overflow-auto">
        <div class="mb-4">
          <input type="text" id="modalSearch" placeholder="Search products..."
            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-[#1f96c9]" />
        </div>
        <div id="categoriesList" class="space-y-6">
          <!-- Categories and products will be loaded here -->
          <div class="text-center py-10 text-gray-500">Loading products...</div>
        </div>
      </div>
    </div>
  </div>

  <script>
    const STORAGE_KEY = 'cart_cq';
    let cart = JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];

    // Add to Cart functionality
    document.getElementById('addToCart')?.addEventListener('click', function() {
      const qty = parseInt(document.getElementById('qty').value) || 1;
      const w = parseFloat(document.getElementById('widthInput').value) || 0;
      const h = parseFloat(document.getElementById('heightInput').value) || 0;
      const l = parseFloat(document.getElementById('lengthInput').value) || 0;
      const itm = {
        code: this.dataset.code,
        name: this.dataset.name,
        width: w,
        height: h,
        length: l,
        originalWidth: parseFloat(this.dataset.originalWidth) || 0,
        originalHeight: parseFloat(this.dataset.originalHeight) || 0,
        originalLength: parseFloat(this.dataset.originalLength) || 0,
        dimensionCombo: this.dataset.dimensionCombo,
        unit: this.dataset.unit,
        category: this.dataset.category,
        price: parseFloat(this.dataset.price),
        basePrice: parseFloat(this.dataset.basePrice),
        labor: parseFloat(this.dataset.labor),
        img: this.dataset.img,
        quantity: qty
      };
      cart.push(itm);
      updateCartTable();
    });

    function updateCartTable() {
      const tbody = document.getElementById('cartTable');
      tbody.innerHTML = '';
      let grand = 0;

      // group by category
      const groups = {},
        order = [];
      cart.forEach((it, idx) => {
        if (!groups[it.category]) {
          groups[it.category] = [];
          order.push(it.category);
        }
        groups[it.category].push({
          it,
          idx
        });
      });

      order.forEach(cat => {
        const hdr = document.createElement('tr');
        hdr.innerHTML = `<td colspan="9" class="px-3 py-2 bg-gray-100 font-semibold text-left">${cat}</td>`;
        tbody.appendChild(hdr);

        groups[cat].forEach(({
          it,
          idx
        }) => {
          // underlying cost calculations
          // — NEW: calculate area by dimension_combo —
          let area;
          switch (it.dimensionCombo) {
            case 'HxL':
              // mm² → m²
              area = (it.height * it.length) / 1_000_000;
              break;
            case 'WxL':
              area = (it.width * it.length) / 1_000_000;
              break;
            case 'Linear':
              // mm → m
              area = it.width / 1_000;
              break;
            default:
              area = 0;
          }
          const rawW = Math.max(it.width - (it.originalWidth + 10), 0);
          const rawH = Math.max(it.height - it.originalHeight, 0);
          const rawL = Math.max(it.length - it.originalLength, 0);
          const uplift = ((it.originalWidth > 0 ? rawW : 0) + (it.originalHeight > 0 ? rawH : 0) + (it.originalLength > 0 ? rawL : 0)) *
            it.basePrice * it.quantity * 0.01;
          let baseCab = area * it.price * it.quantity;
          if (it.originalHeight > 0 && it.height <= it.originalHeight / 2) baseCab *= 0.8;
          const cab = baseCab + uplift;
          const lab = area * it.labor * it.quantity;
          const line = cab + lab;

          grand += line;

          const tr = document.createElement('tr');
          tr.innerHTML = `
        <td class="px-3 py-2 text-center"><img src="${it.img}" class="w-12 h-12 object-cover rounded"/></td>
        <td class="px-3 py-2 text-center">${idx+1}</td>
        <td class="px-3 py-2">${it.name}</td>
        <td class="px-3 py-2 text-center"><input type="number" step="0.01" value="${it.width}" onchange="updateDim(${idx}, 'width', this.value)" class="w-20 px-1 py-0.5 border rounded-md"/></td>
        <td class="px-3 py-2 text-center"><input type="number" step="0.01" value="${it.height}" onchange="updateDim(${idx}, 'height', this.value)" class="w-20 px-1 py-0.5 border rounded-md"/></td>
        <td class="px-3 py-2 text-center"><input type="number" step="0.01" value="${it.length}" onchange="updateDim(${idx}, 'length', this.value)" class="w-20 px-1 py-0.5 border rounded-md"/></td>
        <td class="px-3 py-2 text-center"><input type="number" min="1" value="${it.quantity}" onchange="updateQty(${idx}, this.value)" class="w-16 px-1 py-0.5 border rounded-md"/></td>
        <td class="px-3 py-2 text-right font-semibold">${line.toFixed(2)}</td>
        <td class="px-3 py-2 text-center"><button onclick="removeItem(${idx})" class="text-red-600">Remove</button></td>
      `;
          tbody.appendChild(tr);
        });
      });

      document.getElementById('grandTotal').innerText = grand.toFixed(2);
      localStorage.setItem(STORAGE_KEY, JSON.stringify(cart));
    }

    function updateDim(i, prop, val) {
      cart[i][prop] = parseFloat(val) || 0;
      updateCartTable();
    }

    function updateQty(i, val) {
      cart[i].quantity = parseInt(val) || 1;
      updateCartTable();
    }

    function removeItem(i) {
      cart.splice(i, 1);
      updateCartTable();
    }

    // Submit Quotation Button - Opens Modal
    document.getElementById('submitBtn').addEventListener('click', () => {
      // Check if cart is empty
      if (cart.length === 0) {
        alert('Please add at least one item to the cart before submitting.');
        return;
      }

      // Update modal totals
      document.getElementById('modalGrandTotal').innerText = document.getElementById('grandTotal').innerText;
      document.getElementById('modalItemCount').innerText = cart.length;

      // Show the modal
      document.getElementById('clientDetailsModal').classList.remove('hidden');
    });

    // Client Details Form Submission
    document.getElementById('clientDetailsForm').addEventListener('submit', (e) => {
      e.preventDefault();

      // Get form data
      const formData = new FormData(e.target);
      formData.append('cart_data', JSON.stringify(cart));
      formData.append('grand_total', document.getElementById('grandTotal').innerText);

      // Submit the data
      fetch('save_quotation.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('Quotation submitted successfully!');
            // Clear the cart after successful submission
            localStorage.removeItem(STORAGE_KEY);
            cart = [];
            updateCartTable();

            // Close the modal
            document.getElementById('clientDetailsModal').classList.add('hidden');

            // Optional: redirect to a confirmation page
            // window.location.href = 'quotation_confirmation.php?id=' + data.quotation_id;
          } else {
            alert('Error: ' + (data.message || 'Failed to submit quotation.'));
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('An error occurred while submitting the quotation. Please try again.');
        });
    });

    // Close client details modal
    document.getElementById('closeClientModal').addEventListener('click', () => {
      document.getElementById('clientDetailsModal').classList.add('hidden');
    });

    document.getElementById('cancelSubmit').addEventListener('click', () => {
      document.getElementById('clientDetailsModal').classList.add('hidden');
    });

    // Modal Controls for Explore Products
    document.getElementById('exploreBtn').addEventListener('click', () => {
      document.getElementById('exploreModal').classList.remove('hidden');
      loadProducts();
    });

    document.getElementById('closeModal').addEventListener('click', () => {
      document.getElementById('exploreModal').classList.add('hidden');
    });

    // Close modals if clicked outside
    document.getElementById('exploreModal').addEventListener('click', (e) => {
      if (e.target === document.getElementById('exploreModal')) {
        document.getElementById('exploreModal').classList.add('hidden');
      }
    });

    document.getElementById('clientDetailsModal').addEventListener('click', (e) => {
      if (e.target === document.getElementById('clientDetailsModal')) {
        document.getElementById('clientDetailsModal').classList.add('hidden');
      }
    });

    // Load products from explore_products.php
    function loadProducts(searchQuery = '') {
      const categoriesList = document.getElementById('categoriesList');
      categoriesList.innerHTML = '<div class="text-center py-10 text-gray-500">Loading products...</div>';

      fetch('explore_products.php' + (searchQuery ? `?query=${encodeURIComponent(searchQuery)}` : ''))
        .then(response => response.json())
        .then(products => {
          if (products.length === 0) {
            categoriesList.innerHTML = '<div class="text-center py-10 text-gray-500">No products found</div>';
            return;
          }

          // Organize products by category
          const categories = {};
          products.forEach(product => {
            const category = product.item_category || 'Uncategorized';
            if (!categories[category]) {
              categories[category] = [];
            }
            categories[category].push(product);
          });

          // Clear the list
          categoriesList.innerHTML = '';

          // Create section for each category
          Object.keys(categories).sort().forEach(category => {
            const categorySection = document.createElement('div');
            categorySection.className = 'mb-6';

            // Category header
            const header = document.createElement('h3');
            header.className = 'text-lg font-semibold mb-3 pb-2 border-b text-[#1f96c9]';
            header.textContent = category;
            categorySection.appendChild(header);

            // Products grid
            const grid = document.createElement('div');
            grid.className = 'grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4';

            categories[category].forEach(product => {
              const card = document.createElement('div');
              card.className = 'border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow';

              // Prepare image src
              const imgSrc = product.item_image ?
                `data:${product.mime_type};base64,${product.item_image}` :
                '../img/image_alt.jpg';

              card.innerHTML = `
            <div class="h-32 overflow-hidden">
              <img src="${imgSrc}" alt="${product.item_name}" class="w-full h-full object-cover">
            </div>
            <div class="p-3">
              <p class="font-semibold text-sm truncate" title="${product.item_name}">${product.item_name}</p>
              <p class="text-xs text-gray-500 mb-1">${product.item_code}</p>
              <p class="text-xs text-gray-600 mb-2 truncate" title="${product.item_material || 'N/A'}">Material: ${product.item_material || 'N/A'}</p>
              
              <button class="select-product-btn w-full px-2 py-1 bg-[#1f96c9] text-white text-sm rounded"
                      data-code="${product.item_code}">
                Select
              </button>
            </div>
          `;
              grid.appendChild(card);
            });

            categorySection.appendChild(grid);
            categoriesList.appendChild(categorySection);
          });

          // Add event listeners to the Select buttons
          attachProductSelectListeners();
        })
        .catch(error => {
          console.error('Error loading products:', error);
          categoriesList.innerHTML = '<div class="text-center py-10 text-red-500">Error loading products</div>';
        });
    }

    // Function to attach event listeners to product selection buttons
    // Function to attach event listeners to product selection buttons
    function attachProductSelectListeners() {
      // Main product selection buttons (from explore modal)
      document.querySelectorAll('.select-product-btn').forEach(btn => {
        btn.addEventListener('click', function() {
          // Update search input with the selected product code
          document.getElementById('searchInput').value = this.dataset.code;

          // Close the modal
          document.getElementById('exploreModal').classList.add('hidden');

          // Submit the form to show the product details
          document.querySelector('form').submit();
        });
      });

      // Mini product selection buttons (for related products)
      document.querySelectorAll('.select-mini-product').forEach(btn => {
        btn.addEventListener('click', function() {
          // Update search input with the selected product code
          document.getElementById('searchInput').value = this.dataset.code;

          // Add visual feedback
          this.style.transform = 'scale(0.95)';
          setTimeout(() => {
            this.style.transform = 'scale(1)';
          }, 150);

          // Submit the form to show the product details
          setTimeout(() => {
            document.querySelector('form').submit();
          }, 200);
        });
      });
    }

    // Enhanced function specifically for related products with better UX
    function handleRelatedProductClick(productCode, element) {
      // Add loading state
      const originalContent = element.innerHTML;
      element.innerHTML = '<div class="text-center py-4"><div class="text-xs text-blue-500">Loading...</div></div>';

      // Update search input
      document.getElementById('searchInput').value = productCode;

      // Submit form after brief delay for visual feedback
      setTimeout(() => {
        const form = document.querySelector('form[method="GET"]');
        if (form) {
          form.submit();
        } else {
          // Fallback: redirect with query parameter
          const currentUrl = new URL(window.location.href);
          currentUrl.searchParams.set('query', productCode);
          if (currentUrl.searchParams.has('id')) {
            // Keep the ID parameter if it exists
          }
          window.location.href = currentUrl.toString();
        }
      }, 300);

      // Reset content if submission fails
      setTimeout(() => {
        element.innerHTML = originalContent;
        attachProductSelectListeners(); // Re-attach listeners
      }, 5000);
    }

    // Initialize related products event listeners on page load
    // Add this to your existing JavaScript section

    // Handle show/hide toggle for related products
    // Related Products Show More/Less functionality
    document.addEventListener('DOMContentLoaded', function() {
      // Existing code...
      updateCartTable();
      attachProductSelectListeners();

      // Handle show more/less for related products
      const showMoreBtn = document.getElementById('showMoreRelated');
      const toggleBtn = document.getElementById('toggleRelatedProducts');
      const container = document.getElementById('relatedProductsContainer');

      if (showMoreBtn) {
        let isExpanded = false;

        showMoreBtn.addEventListener('click', function() {
          const hiddenItems = document.querySelectorAll('.initially-hidden');

          if (!isExpanded) {
            // Show all items
            container.classList.remove('max-h-96', 'overflow-hidden');
            hiddenItems.forEach(item => {
              item.style.display = 'block';
            });
            this.textContent = 'Show Less';
            isExpanded = true;
          } else {
            // Hide extra items
            container.classList.add('max-h-96', 'overflow-hidden');
            hiddenItems.forEach(item => {
              item.style.display = 'none';
            });
            this.textContent = `Show More (${hiddenItems.length} more)`;
            isExpanded = false;

            // Scroll back to the related products section
            container.scrollIntoView({
              behavior: 'smooth',
              block: 'nearest'
            });
          }
        });

        // Initially hide items beyond the 8th
        const hiddenItems = document.querySelectorAll('.initially-hidden');
        hiddenItems.forEach(item => {
          item.style.display = 'none';
        });
      }

      if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
          const isHidden = container.classList.contains('max-h-96');

          if (isHidden) {
            container.classList.remove('max-h-96', 'overflow-hidden');
            this.textContent = 'Show less';
          } else {
            container.classList.add('max-h-96', 'overflow-hidden');
            this.textContent = `Show all ${totalRelatedCount} products`;
          }
        });
      }

      // Enhanced related product click handling
      document.querySelectorAll('.select-mini-product').forEach(element => {
        element.addEventListener('click', function(e) {
          e.preventDefault();

          // Visual feedback
          this.style.transform = 'scale(0.95)';
          this.style.opacity = '0.7';

          // Update search input
          const searchInput = document.getElementById('searchInput');
          searchInput.value = this.dataset.code;

          // Add loading state
          const clickIndicator = this.querySelector('span');
          if (clickIndicator) {
            clickIndicator.textContent = '⏳ Loading...';
          }

          // Submit form
          setTimeout(() => {
            const form = document.querySelector('form[method="GET"]');
            if (form) {
              form.submit();
            }
          }, 200);
        });

        // Hover effects
        element.addEventListener('mouseenter', function() {
          this.style.transform = 'translateY(-2px)';
        });

        element.addEventListener('mouseleave', function() {
          this.style.transform = 'translateY(0)';
        });
      });
    });

    // Function to display related products mini images
    function displayRelatedProducts(relatedItems, targetContainer) {
      if (!relatedItems || relatedItems.length === 0) {
        return;
      }

      const relatedSection = document.createElement('div');
      relatedSection.className = 'mt-4 p-3 bg-gray-50 rounded-lg border';
      relatedSection.innerHTML = `
    <h4 class="text-sm font-semibold mb-3 text-gray-700">Related Products from Same Category</h4>
    <div class="flex gap-3 overflow-x-auto pb-2">
      ${relatedItems.map(product => `
        <div class="flex-shrink-0 w-20 cursor-pointer select-mini-product hover:bg-white hover:shadow-sm rounded p-1 transition-all" 
             data-code="${product.item_code}" 
             title="${product.item_name} - ${product.item_material || 'N/A'}">
          <img src="${product.processed_image}" 
               class="w-16 h-16 object-cover rounded border">
          <p class="text-xs mt-1 truncate font-medium" title="${product.item_name}">${product.item_name}</p>
          <p class="text-xs text-gray-500 truncate" title="${product.item_material || 'N/A'}">${product.item_material || 'N/A'}</p>
        </div>
      `).join('')}
    </div>
  `;

      targetContainer.appendChild(relatedSection);

      // Attach event listeners to the new mini product buttons
      attachProductSelectListeners();
    }

    // Search within modal
    document.getElementById('modalSearch').addEventListener('input', function() {
      const searchQuery = this.value.trim();
      // Add debounce to avoid too many requests
      clearTimeout(this.searchTimeout);
      this.searchTimeout = setTimeout(() => {
        loadProducts(searchQuery);
      }, 300);
    });

    // Auto-complete functionality for main search
    document.getElementById('searchInput').addEventListener('input', function() {
      const query = this.value.trim();
      if (query.length < 2) {
        document.getElementById('item_suggestions').innerHTML = '';
        return;
      }

      // Fetch suggestions
      fetch(`get_suggestions.php?query=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(suggestions => {
          const datalist = document.getElementById('item_suggestions');
          datalist.innerHTML = '';

          suggestions.forEach(item => {
            const option = document.createElement('option');
            option.value = item.item_code;
            option.textContent = `${item.item_code} - ${item.item_name}${item.item_material ? ' (' + item.item_material + ')' : ''}`;
            datalist.appendChild(option);
          });
        })
        .catch(error => {
          console.error('Error fetching suggestions:', error);
        });
    });

    // Initialize cart table on page load
    document.addEventListener('DOMContentLoaded', function() {
      updateCartTable();

      // If related products are available from PHP, display them
      if (typeof relatedProductsData !== 'undefined' && relatedProductsData.length > 0) {
        const itemCard = document.getElementById('itemCard');
        if (itemCard) {
          displayRelatedProducts(relatedProductsData, itemCard);
        }
      }
    });
  </script>
</body>

</html>
