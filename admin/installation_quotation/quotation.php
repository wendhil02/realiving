<?php
session_start();
include '../../connection/connection.php';
include '../design/mainbody.php';
include '../checkrole.php';
require_role(['admin1', 'superadmin']);
// Retrieve user_info by ID
$clientName   = '';
$projectScope = '';
$quotationNo  = '';

if (!empty($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT clientname, nameproject, reference_number FROM user_info WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows) {
        $row = $res->fetch_assoc();
        $clientName   = $row['clientname'];
        $projectScope = $row['nameproject'];
        $quotationNo  = $row['reference_number'];
    }
    $stmt->close();
}

// Other defaults
$orderDate    = date('Y-m-d');
$contactNo    = '';
$address      = '';
$installType  = '';

// Search/item vars
$item       = null;
$query      = '';
$error      = '';
$defaultImg = '../img/image_alt.jpg';
$itemImage  = $defaultImg;

// Handle search (unchanged)
if (!empty($_GET['query'])) {
  $query = $conn->real_escape_string(trim($_GET['query']));
  $stmt  = $conn->prepare("SELECT * FROM items WHERE item_code LIKE CONCAT('%', ?, '%') OR item_name LIKE CONCAT('%', ?, '%') LIMIT 1");
  $stmt->bind_param('ss', $query, $query);
  $stmt->execute();
  $res = $stmt->get_result();
  if ($res && $res->num_rows) {
    $item = $res->fetch_assoc();
    if (!empty($item['item_image'])) {
      $finfo    = new finfo(FILEINFO_MIME_TYPE);
      $mimeType = $finfo->buffer($item['item_image']);
      $b64      = base64_encode($item['item_image']);
      $itemImage = "data:{$mimeType};base64,{$b64}";
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
  <title>Real Living Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }
  </style>
</head>

<body class="bg-gray-100" data-item-loaded="<?= $item ? '1' : '0' ?>">


  <main class="p-6 flex-col items-center justify-center">
    <!-- Branding Section -->
    <div class="max-w-7xl mx-auto mt-6 bg-white p-6 rounded-xl shadow-lg space-y-8">
      <!-- Add Product Button -->
      <div class="flex justify-end">
        <button id="openModal" class="bg-yellow-400 hover:bg-yellow-500 text-blue-900 font-bold py-2 px-4 rounded-md">
          Add Product
        </button>
      </div>
      <!-- Search Form (preserve id) -->
      <section class="space-y-2">
        <form method="GET" class="flex gap-2 items-center">
          <?php if ($id !== null): ?>
            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
          <?php endif; ?>
          <input
            type="text"
            name="query"
            id="searchInput"
            list="item_suggestions"
            value="<?= htmlspecialchars($query) ?>"
            placeholder="Enter item code or name"
            required
            class="flex-grow px-3 py-2 border border-blue-900 rounded-md focus:outline-none" />
          <datalist id="item_suggestions"></datalist>
          <button
            type="submit"
            class="bg-blue-900 hover:bg-blue-800 text-yellow-400 font-bold py-2 px-4 rounded-md">
            Search
          </button>
        </form>
        <?php if ($error): ?>
          <p class="text-red-600"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
      </section>

      <!-- Item Card (only shows if $item is found) -->
      <?php if ($item): ?>
        <div id="itemCard" class="flex flex-col md:flex-row bg-gray-100 rounded-lg shadow p-4">
          <img src="<?= $itemImage ?>" alt="Item Image"
            class="w-full md:w-48 h-48 object-cover rounded-md mb-4 md:mb-0" />
          <div class="md:ml-6 flex-1 space-y-4">
            <p><strong>Code:</strong> <?= htmlspecialchars($item['item_code']) ?></p>
            <p><strong>Name:</strong> <?= htmlspecialchars($item['item_name']) ?></p>

            <!-- Dimensions Inputs -->
            <div class="grid grid-cols-3 gap-4">
              <label class="block">
                <span class="text-sm font-medium text-gray-700">Width/Depth (mm)</span>
                <input
                  type="number"
                  id="widthInput"
                  step="0.01"
                  value="<?= htmlspecialchars($item['item_width'] ?? 0) ?>"
                  class="w-full mt-1 px-3 py-2 border border-blue-900 rounded-md focus:outline-none" />
              </label>
              <label class="block">
                <span class="text-sm font-medium text-gray-700">Height (mm)</span>
                <input
                  type="number"
                  id="heightInput"
                  step="0.01"
                  value="<?= htmlspecialchars($item['item_height'] ?? 0) ?>"
                  class="w-full mt-1 px-3 py-2 border border-blue-900 rounded-md focus:outline-none" />
              </label>
              <label class="block">
                <span class="text-sm font-medium text-gray-700">Length/Depth (mm)</span>
                <input
                  type="number"
                  id="lengthInput"
                  step="0.01"
                  value="<?= htmlspecialchars($item['item_length'] ?? 0) ?>"
                  class="w-full mt-1 px-3 py-2 border border-blue-900 rounded-md focus:outline-none" />
              </label>
            </div>

            <p><strong>Unit:</strong> <?= htmlspecialchars($item['item_unit']) ?></p>
            <p><strong>Category:</strong> <?= htmlspecialchars($item['item_category']) ?></p>
            <hr class="my-2" />

            <div class="flex items-center space-x-2">
              <input
                type="number"
                id="qty"
                min="1"
                value="1"
                class="w-20 px-2 py-1 border border-blue-900 rounded-md focus:outline-none" />
              <button
                id="addToCart"
                data-code="<?= htmlspecialchars($item['item_code']) ?>"
                data-name="<?= htmlspecialchars($item['item_name']) ?>"
                data-unit="<?= htmlspecialchars($item['item_unit']) ?>"
                data-category="<?= htmlspecialchars($item['item_category']) ?>"
                data-price="<?= $item['item_price'] ?>"
                data-base-price="<?= $item['item_price'] ?>"
                data-labor="<?= $item['item_labor_cost'] ?>"
                data-img="<?= $itemImage ?>"
                data-original-width="<?= htmlspecialchars($item['item_width'] ?? 0) ?>"
  data-original-height="<?= htmlspecialchars($item['item_height'] ?? 0) ?>"
  data-original-length="<?= htmlspecialchars($item['item_length'] ?? 0) ?>"
  data-dimension-combo="<?= htmlspecialchars($item['dimension_combo']) ?>"
                class="bg-yellow-400 hover:bg-yellow-500 text-blue-900 font-bold py-2 px-4 rounded-md">
                Add to Cart
              </button>
            </div>
          </div>
        </div>
      <?php endif; ?>
      <!-- Quotation Form -->
      <form method="POST" action="generate_pdf.php" id="quotationForm" class="space-y-8">
        <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
        <input type="hidden" name="quotation_no" value="<?= htmlspecialchars($quotationNo) ?>">

        <section class="space-y-4">
          <h1 class="text-3xl font-bold text-blue-900">Client & Project Details</h1>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-1">
              <span class="text-sm font-medium text-gray-700 block">Client Name</span>
              <p class="w-full px-3 py-2 bg-gray-100 rounded-md"><?= htmlspecialchars($clientName) ?></p>
            </div>
            <div class="space-y-1">
              <span class="text-sm font-medium text-gray-700 block">Project Scope</span>
              <p class="w-full px-3 py-2 bg-gray-100 rounded-md"><?= htmlspecialchars($projectScope) ?></p>
            </div>
            <label class="block"><span class="text-sm font-medium text-gray-700">Order Date</span><input type="date" name="order_date" id="order_date" value="<?= $orderDate ?>" class="w-full mt-1 px-3 py-2 border border-blue-900 rounded-md focus:outline-none" /></label>
            <label class="block"><span class="text-sm font-medium text-gray-700">Contact No.</span><input type="text" name="contact_no" id="contact_no" placeholder="Contact No." class="w-full mt-1 px-3 py-2 border border-blue-900 rounded-md focus:outline-none" /></label>
            <label class="block"><span class="text-sm font-medium text-gray-700">Address</span><input type="text" name="address" id="address" placeholder="Address" class="w-full mt-1 px-3 py-2 border border-blue-900 rounded-md focus:outline-none" /></label>
            <label class="block"><span class="text-sm font-medium text-gray-700">Type of Installation</span><input type="text" name="install_type" id="install_type" placeholder="Type of Installation" class="w-full mt-1 px-3 py-2 border border-blue-900 rounded-md focus:outline-none" /></label>
          </div>
        </section>

        <!-- Cart Table -->
        <section>
          <h2 class="text-2xl font-bold text-blue-900 mb-4">Cart</h2>
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-blue-900 text-yellow-400">
                <tr>
                  <th class="px-3 py-2">Image</th>
                  <th class="px-3 py-2">No.</th>
                  <th class="px-3 py-2">Item</th>
                  <th class="px-3 py-2">Width/Depth (mm)</th>
                  <th class="px-3 py-2">Height (mm)</th>
                  <th class="px-3 py-2">Length (mm)</th>
                  <th class="px-3 py-2">Area (m)</th>
                  <th class="px-3 py-2">Unit</th>
                  <th class="px-3 py-2">Qty</th>
                  <th class="px-3 py-2">Unit Price</th>
                  <th class="px-3 py-2">Cabinet Cost</th>
                  <th class="px-3 py-2">Labor Cost/Unit</th>
                  <th class="px-3 py-2">Total Labor</th>
                  <th class="px-3 py-2">Total Amount</th>
                  <th class="px-3 py-2">Actions</th>
                </tr>
              </thead>
              <tbody id="cartTable" class="bg-white divide-y divide-gray-200"></tbody>
            </table>
          </div>

          <!-- Totals -->
          <div class="mt-6 space-y-3 max-w-md ml-auto">
            <div class="flex justify-between">
              <span class="font-semibold">Grand Total:</span>
              <span>₱<span id="grandTotal">0.00</span></span>
            </div>
            <div class="flex justify-between items-center">
              <label class="font-semibold" for="discount">Discount (%):</label>
              <input
                type="number"
                id="discount"
                name="discount"
                value="0"
                min="0"
                max="100"
                class="w-20 px-2 py-1 border border-blue-900 rounded-md focus:outline-none" />
            </div>
            <div class="flex justify-between">
              <span class="font-semibold">Total Labor Cost:</span>
              <span>₱<span id="laborTotal">0.00</span></span>
            </div>
            <div class="flex justify-between">
              <span class="font-semibold">Final Total:</span>
              <span class="text-xl font-bold">₱<span id="finalTotal">0.00</span></span>
            </div>
          </div>
        </section>

        <!-- Employee Name -->
        <div class="mt-6 max-w-md ml-auto">
          <label class="block mb-2">
            <span class="text-sm font-medium text-gray-700">Employee Name</span>
            <input type="text" name="employee_name" id="employee_name"
              class="w-full mt-1 px-3 py-2 border border-blue-900 rounded-md focus:outline-none"
              placeholder="Enter your name" />
          </label>
        </div>

        <div class="flex justify-end mt-4 space-x-2">
          <button type="button" id="resetBtn"
            class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-md">
            Reset Form
          </button>
          <button type="submit"
            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md">
            Download Quotation Excel
          </button>
        </div>
        <input type="hidden" name="cart_data" id="cart_data" />
      </form>

</div>

  </main>

  <!-- Modal -->
  <div id="modal"
    class="fixed inset-0 bg-black bg-opacity-60 hidden items-start justify-center overflow-auto p-6 mt-[50px]">
    <div class="bg-white w-full max-w-4xl rounded-xl overflow-hidden shadow-lg">
      <div class="flex justify-between bg-blue-900 text-yellow-400 px-4 py-2">
        <h3 class="text-lg">Add New Item</h3>
        <button id="closeModal" class="text-2xl text-white">&times;</button>
      </div>
      <div id="modalBody" class="p-4 max-h-[80vh] overflow-y-auto">
        <p class="text-center text-gray-500">Loading…</p>
      </div>
    </div>
  </div>


  <!-- JavaScript for Search -->
  <script>
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const searchBtn = document.getElementById('searchBtn');

    function performSearch() {
      const filter = searchInput.value.trim().toLowerCase();
      const rows = document.querySelectorAll('.client-row'); // Use the class name for rows

      rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        let rowContainsSearch = false; // Flag to check if row matches the search

        // Loop through all columns in the row
        cells.forEach(cell => {
          const cellText = cell.textContent.trim().toLowerCase();
          if (cellText.includes(filter)) {
            rowContainsSearch = true;
          }
        });

        // Show or hide the row based on search result
        if (filter === '' || rowContainsSearch) {
          row.style.display = ''; // Show row if it matches
        } else {
          row.style.display = 'none'; // Hide row if it doesn't match
        }
      });
    }

    // Trigger on search button click
    searchBtn.addEventListener('click', performSearch);

    // Trigger on Enter key
    searchInput.addEventListener('keydown', function(e) {
      if (e.key === 'Enter') {
        e.preventDefault(); // Prevent form submission if inside form
        performSearch();
      }
    });
  </script>

  <script>
     const menuBtn = document.getElementById("menu-btn");
    const mobileMenu = document.getElementById("mobile-menu");
    menuBtn.addEventListener("click", () => {
      mobileMenu.classList.toggle("hidden");
    });
  </script>

  <script>
    const defaultImg = '<?= $defaultImg ?>';
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    // --- Add to Cart: now storing original dims ---
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
        // store originals to detect unchanged dims
        originalWidth:  parseFloat(this.dataset.originalWidth) || 0,
        originalHeight: parseFloat(this.dataset.originalHeight) || 0,
  originalLength: parseFloat(this.dataset.originalLength) || 0,
  dimensionCombo: this.dataset.dimensionCombo,    // <— NEW
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

    // --- Render Cart Table, grouped by category ---
    function updateCartTable() {
      const tbody = document.getElementById('cartTable');
      tbody.innerHTML = '';
      let grand = 0,
        laborSum = 0;

      // Group items by category in insertion order
      const groups = {};
      const order = [];
      cart.forEach((it, idx) => {
        if (!groups[it.category]) {
          groups[it.category] = [];
          order.push(it.category);
        }
        groups[it.category].push({
          item: it,
          idx
        });
      });

      // For each category, emit header then its items
      order.forEach(cat => {
        const hdr = document.createElement('tr');
        hdr.innerHTML = `
        <td colspan="16" class="px-3 py-2 bg-gray-200 font-semibold text-left">
          ${cat}
        </td>
      `;
        tbody.appendChild(hdr);

        groups[cat].forEach(({ item: it, idx }) => {
  // 1) Raw over-original for each dim:
const rawWdelta = Math.max(
  it.width - (it.originalWidth + 10),  // shift threshold +10 mm
  0
);
const rawHdelta = Math.max(it.height - it.originalHeight, 0);
const rawLdelta = Math.max(it.length - it.originalLength,  0);

// 2) Only uplift dims with non-zero originals:
const wDelta = it.originalWidth  > 0 ? rawWdelta : 0;
const hDelta = it.originalHeight > 0 ? rawHdelta : 0;
const lDelta = it.originalLength > 0 ? rawLdelta : 0;

// — NEW: calculate area based on the stored dimension combo —
let area;
switch (it.dimensionCombo) {
  case 'HxL':
    area = (it.height * it.length) / 1000000;
    break;
  case 'WxL':
    area = (it.width * it.length) / 1000000;
    break;
  case 'Linear':
    area = it.width / 1000;
    break;
  default:
    // fallback to zero
    area = 0;
}

// 4) Cabinet base cost (with any height‐based discount you already have):
let baseCab = area * it.price * it.quantity;
if (it.originalHeight > 0 && it.height <= it.originalHeight / 2) {
  baseCab *= 0.8; // your 20% height discount
}

// 5) Uplift only on those filtered deltas:
const extraMM  = wDelta + hDelta + lDelta;
const extraCab = extraMM * it.basePrice * it.quantity * 0.01;

// 6) Totals:
const cab  = baseCab + extraCab;
const lab  = area  * it.labor    * it.quantity;
const line = cab   + lab;

grand    += line;
laborSum += lab;

  // build your <tr> exactly as before, using `area`, `cab`, `lab`, `line`
  const tr = document.createElement('tr');
  tr.innerHTML = `
    <td class="px-3 py-2 text-center">
      <img src="${it.img}" class="w-12 h-12 object-cover rounded"/>
    </td>
    <td class="px-3 py-2 text-center">${idx + 1}</td>
    <td class="px-3 py-2">${it.name}</td>
    <td class="px-3 py-2 text-center">
      <input type="number" step="0.01" value="${it.width}"
             class="w-20 px-1 py-0.5 border border-blue-900 rounded"
             onchange="updateDim(${idx}, 'width', this.value)"/>
    </td>
    <td class="px-3 py-2 text-center">
      <input type="number" step="0.01" value="${it.height}"
             class="w-20 px-1 py-0.5 border border-blue-900 rounded"
             onchange="updateDim(${idx}, 'height', this.value)"/>
    </td>
    <td class="px-3 py-2 text-center">
      <input type="number" step="0.01" value="${it.length}"
             class="w-20 px-1 py-0.5 border border-blue-900 rounded"
             onchange="updateDim(${idx}, 'length', this.value)"/>
    </td>
    <td class="px-3 py-2 text-center">${area.toFixed(3)}</td>
    <td class="px-3 py-2 text-center">${it.unit}</td>
    <td class="px-3 py-2 text-center">
      <input type="number" min="1" value="${it.quantity}"
             class="w-16 px-1 py-0.5 border border-blue-900 rounded"
             onchange="changeQty(${idx}, this.value)"/>
    </td>
    <td class="px-3 py-2 text-right">
      <input type="number" step="0.01" value="${it.price.toFixed(2)}"
             class="w-20 px-1 py-0.5 border border-blue-900 rounded text-right"
             onchange="updatePrice(${idx}, this.value)"/>
    </td>
    <td class="px-3 py-2 text-right">${cab.toFixed(2)}</td>
    <td class="px-3 py-2 text-right">
      <input type="number" step="0.01" value="${it.labor.toFixed(2)}"
             class="w-20 px-1 py-0.5 border border-blue-900 rounded text-right"
             onchange="updateLabor(${idx}, this.value)"/>
    </td>
    <td class="px-3 py-2 text-right">${lab.toFixed(2)}</td>
    <td class="px-3 py-2 text-right font-semibold">${line.toFixed(2)}</td>
    <td class="px-3 py-2 text-center">
      <button onclick="removeItem(${idx})" class="text-red-600 hover:underline">Remove</button>
    </td>
  `;
  tbody.appendChild(tr);
        });
      });

      // Update totals and persist
      document.getElementById('grandTotal').innerText = grand.toFixed(2);
      document.getElementById('laborTotal').innerText = laborSum.toFixed(2);
      updateFinalTotal();
      localStorage.setItem('cart', JSON.stringify(cart));
      document.getElementById('cart_data').value = JSON.stringify(cart);
    }

    // --- Handlers ---
    function updateDim(i, prop, value) {
      cart[i][prop] = parseFloat(value) || 0;
      updateCartTable();
    }

    function changeQty(i, v) {
      cart[i].quantity = parseInt(v) || 1;
      updateCartTable();
    }

    function updatePrice(i, v) {
      cart[i].price = parseFloat(v) || 0;
      updateCartTable();
    }

    function updateLabor(i, v) {
      cart[i].labor = parseFloat(v) || 0;
      updateCartTable();
    }

    function removeItem(i) {
      cart.splice(i, 1);
      updateCartTable();
    }

    // --- Discount & Final Total ---
    document.getElementById('discount')?.addEventListener('input', updateFinalTotal);

    function updateFinalTotal() {
      const g = parseFloat(document.getElementById('grandTotal').innerText) || 0;
      const d = parseFloat(document.getElementById('discount').value) || 0;
      document.getElementById('finalTotal').innerText = (g - g * d / 100).toFixed(2);
    }

    // Initialize on load
    updateCartTable();

    // Auto‐scroll to the item card if one was loaded
    <?php if ($item): ?>
      window.addEventListener('DOMContentLoaded', () => {
        document.getElementById('itemCard').scrollIntoView({
          behavior: 'smooth'
        });
      });
    <?php endif; ?>

    // --- Modal Logic ---
    const modal = document.getElementById('modal');
    const openBtn = document.getElementById('openModal');
    const closeBtn = document.getElementById('closeModal');
    const body = document.getElementById('modalBody');

    openBtn.addEventListener('click', () => {
      modal.classList.remove('hidden');
      modal.classList.add('flex');
      fetch('../items_insertion/show.php')
        .then(r => r.text())
        .then(html => {
          body.innerHTML = html;
          attachImagePreview();
          attachFormSubmission();
        });
    });
    closeBtn.addEventListener('click', () => {
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    });
    window.addEventListener('click', e => {
      if (e.target === modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      }
    });

    // --- Functions pulled in from show.php ---
    function attachImagePreview() {
      const img = body.querySelector('#imagePreview');
      const inp = body.querySelector('#fileInput');
      if (!img || !inp) return;
      inp.addEventListener('change', function(e) {
        const f = this.files[0];
        if (!f) return;
        const r = new FileReader();
        r.onload = ev => img.src = ev.target.result;
        r.readAsDataURL(f);
      });
    }

    function attachFormSubmission() {
      const form = body.querySelector('form#addItemForm');
      const errP = body.querySelector('.form-error');
      if (!form) return;
      form.addEventListener('submit', e => {
        e.preventDefault();
        errP.classList.add('hidden');
        const fd = new FormData(form);
        fetch('../items_insertion/show.php', {
            method: 'POST',
            body: fd
          })
          .then(r => r.json())
          .then(json => {
            if (json.success) {
              alert(json.message);
              modal.classList.add('hidden');
              modal.classList.remove('flex');
            } else {
              errP.textContent = json.message;
              errP.classList.remove('hidden');
            }
          })
          .catch(() => {
            errP.textContent = 'Unexpected error.';
            errP.classList.remove('hidden');
          });
      });
    }

   // Persist form fields & cart to localStorage
   const formFields = ['order_date','contact_no','address','install_type','discount','employee_name'];
    document.addEventListener('DOMContentLoaded', () => {
      // load saved values
      formFields.forEach(id => {
        const el = document.getElementById(id);
        if (el && localStorage.getItem(id) !== null) el.value = localStorage.getItem(id);
        if (el) el.addEventListener('input', () => localStorage.setItem(id, el.value));
      });
      // load cart
      cart = JSON.parse(localStorage.getItem('cart')) || [];
      updateCartTable();
    });

    // Reset with confirmation
    document.getElementById('resetBtn').addEventListener('click', () => {
      if (confirm('Are you sure you want to reset the form and clear the cart?')) {
        // clear storage
        formFields.forEach(id => localStorage.removeItem(id));
        localStorage.removeItem('cart');
        // reset UI
        document.getElementById('quotationForm').reset();
        cart = [];
        updateCartTable();
        document.getElementById('cart_data').value = '';
      }
    });
  </script>

</body>

</html>