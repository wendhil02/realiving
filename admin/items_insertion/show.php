<?php
// ../inserting_product/show.php
include '../../connection/connection.php';

// DEV: turn on errors in-browser
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Handle POST insertion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  header('Content-Type: application/json; charset=utf-8');

  // 1) Sanitize POST inputs
  $code           = trim($_POST['item_code']         ?? '');
  $name           = trim($_POST['item_name']         ?? '');
  $material       = trim($_POST['item_material']     ?? '');
  $width          = floatval($_POST['item_width']    ?? 0);
  $height         = floatval($_POST['item_height']   ?? 0);
  $length         = floatval($_POST['item_length']   ?? 0);
  $unit           = trim($_POST['item_unit']         ?? '');
  $dimensionCombo = trim($_POST['dimension_combo']   ?? '');
  $category       = trim($_POST['item_category']     ?? '');
  $price          = floatval($_POST['item_price']    ?? 0);
  $laborCost      = floatval($_POST['item_labor_cost'] ?? 0);

  // 2) Compute next ID if your table isn't AUTO_INCREMENT
  $res = $conn->query("SELECT COALESCE(MAX(item_id), 0) + 1 AS nextId FROM items");
  if (!$res) {
    echo json_encode(['success' => false, 'message' => 'ID query failed: ' . $conn->error]);
    exit;
  }
  $nextId = (int) $res->fetch_assoc()['nextId'];

  // 3) Handle uploaded image (optional)
  $imageBlob = null;
  if (
    isset($_FILES['item_image'])
    && $_FILES['item_image']['error'] === UPLOAD_ERR_OK
    && is_uploaded_file($_FILES['item_image']['tmp_name'])
  ) {
    $finfo   = new finfo(FILEINFO_MIME_TYPE);
    $mime    = $finfo->file($_FILES['item_image']['tmp_name']);
    $allowed = ['image/jpeg','image/png','image/gif','image/webp','image/bmp','image/svg+xml'];
    if (!in_array($mime, $allowed, true)) {
      echo json_encode(['success' => false, 'message' => 'Unsupported image format.']);
      exit;
    }
    $imageBlob = file_get_contents($_FILES['item_image']['tmp_name']);
  }

  // 4) Prepare INSERT (13 columns including item_material)
  $sql = "INSERT INTO items (
        item_id,
        item_code,
        item_name,
        item_material,
        item_width,
        item_height,
        item_length,
        item_unit,
        dimension_combo,
        item_category,
        item_price,
        item_labor_cost,
        item_image
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

  $stmt = $conn->prepare($sql);
  if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'DB prepare failed: ' . $conn->error]);
    exit;
  }

  // Bind parameters:
  // i: item_id, s: code, s: name, s: material,
  // d: width, d: height, d: length,
  // s: unit, s: dimension_combo, s: category,
  // d: price, d: laborCost, b: image
  $null = null;
  $stmt->bind_param(
    'isssdddsssddb',
    $nextId,
    $code,
    $name,
    $material,
    $width,
    $height,
    $length,
    $unit,
    $dimensionCombo,
    $category,
    $price,
    $laborCost,
    $null
  );

  // Send blob if exists (zero-based index 12)
  if ($imageBlob !== null) {
    $stmt->send_long_data(12, $imageBlob);
  }

  // Execute & return JSON
  if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Item added successfully (ID=' . $nextId . ').']);
  } else {
    echo json_encode(['success' => false, 'message' => 'DB execute failed: ' . $stmt->error]);
  }

  $stmt->close();
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Add New Item</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans">
  <div class="max-w-3xl mx-auto mt-10 bg-white p-6 rounded-xl shadow-lg">
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Add New Item</h1>
    <form id="addItemForm" method="POST" action="<?= htmlspecialchars($_SERVER['SCRIPT_NAME']) ?>" enctype="multipart/form-data" class="space-y-4">

      <!-- Image Upload -->
      <div id="imageUploadContainer" class="border-2 border-dashed border-blue-900 rounded-lg w-64 h-64 mx-auto flex items-center justify-center bg-gray-100 cursor-pointer relative">
        <img id="imagePreview" src="../../logo/quota.jpg" alt="Click to choose" class="object-cover w-full h-full rounded-lg" />
        <input type="file" name="item_image" id="fileInput" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
      </div>

      <!-- Item Code & Name -->
      <input type="text" name="item_code" placeholder="Item Code" required class="w-full px-3 py-2 border border-blue-900 rounded-md" />
      <input type="text" name="item_name" placeholder="Item Name" required class="w-full px-3 py-2 border border-blue-900 rounded-md" />

      <!-- Material -->
      <input type="text" name="item_material" placeholder="Material" required class="w-full px-3 py-2 border border-blue-900 rounded-md" />

      <!-- Dimensions -->
      <div class="grid grid-cols-3 gap-4">
        <input type="number" step="0.01" name="item_width" placeholder="Width (mm)" required class="px-3 py-2 border border-blue-900 rounded-md" />
        <input type="number" step="0.01" name="item_height" placeholder="Height (mm)" required class="px-3 py-2 border border-blue-900 rounded-md" />
        <input type="number" step="0.01" name="item_length" placeholder="Length/Depth (mm)" required class="px-3 py-2 border border-blue-900 rounded-md" />
      </div>

      <!-- Dimension Combo -->
      <div class="mt-4">
        <label for="dimension_combo" class="block text-sm font-medium text-gray-700">Dimension Combo</label>
        <select name="dimension_combo" id="dimension_combo" required class="mt-1 block w-full px-3 py-2 border border-blue-900 rounded-md bg-white">
          <option value="">-- Choose --</option>
          <option value="HxL">Height × Length</option>
          <option value="WxL">Width × Length</option>
          <option value="Linear">Linear</option>
        </select>
      </div>

      <!-- Unit & Category -->
      <div class="grid grid-cols-2 gap-4">
        <input type="text" name="item_unit" placeholder="Unit" required class="px-3 py-2 border border-blue-900 rounded-md" />
        <input type="text" name="item_category" placeholder="Category" required class="px-3 py-2 border border-blue-900 rounded-md" />
      </div>

      <!-- Pricing -->
      <h2 class="text-2xl font-semibold text-blue-900 mt-6">Cabinet Price</h2>
      <input type="number" step="0.01" name="item_price" placeholder="Unit Price" required class="w-full px-3 py-2 border border-blue-900 rounded-md" />

      <!-- Labor -->
      <h2 class="text-2xl font-semibold text-blue-900 mt-6">Labor Costing</h2>
      <input type="number" step="0.01" name="item_labor_cost" placeholder="Labor Cost" required class="w-full px-3 py-2 border border-blue-900 rounded-md" />

      <!-- Error & Submit -->
      <p class="text-red-600 form-error hidden"></p>
      <button type="submit" class="w-full bg-yellow-400 hover:bg-yellow-500 text-blue-900 font-bold py-2 rounded-md">Add Item</button>
    </form>
  </div>

  <script>
    const container = document.getElementById('imageUploadContainer');
    const fileInput = document.getElementById('fileInput');
    const imagePreview = document.getElementById('imagePreview');
    const form = document.getElementById('addItemForm');
    const errP = document.querySelector('.form-error');

    container.addEventListener('click', () => fileInput.click());
    fileInput.addEventListener('change', e => {
      const file = e.target.files[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = ev => imagePreview.src = ev.target.result;
      reader.readAsDataURL(file);
    });

    form.addEventListener('submit', async e => {
      e.preventDefault();
      errP.classList.add('hidden');
      const fd = new FormData(form);
      let res, text;
      try {
        res = await fetch(form.action, { method: 'POST', body: fd });
        text = await res.text();
      } catch (networkErr) {
        errP.textContent = 'Network error: ' + networkErr.message;
        errP.classList.remove('hidden'); return;
      }
      try {
        const json = JSON.parse(text);
        if (json.success) {
          alert(json.message);
          form.reset();
          imagePreview.src = '../../logo/quota.jpg';
        } else {
          errP.textContent = json.message;
          errP.classList.remove('hidden');
        }
      } catch {
        errP.textContent = 'Unexpected response: ' + text;
        errP.classList.remove('hidden');
      }
    });
  </script>
</body>
</html>
