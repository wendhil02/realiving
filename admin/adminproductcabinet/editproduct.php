<?php
include '../../connection/connection.php';

// Start session to store temporary messages
session_start();

$success_msg = "";
$error_msg = "";

// Check for session messages (from redirect)
if (isset($_SESSION['success_msg'])) {
    $success_msg = $_SESSION['success_msg'];
    unset($_SESSION['success_msg']); // Clear immediately
}

if (isset($_SESSION['error_msg'])) {
    $error_msg = $_SESSION['error_msg'];
    unset($_SESSION['error_msg']); // Clear immediately
}

include '../checkrole.php';
include '../design/mainbody.php';

// Reset AUTO_INCREMENT if empty
$result = $conn->query("SELECT COUNT(*) AS count FROM products");
$row = $result->fetch_assoc();
if ($row['count'] == 0) {
    $conn->query("ALTER TABLE products AUTO_INCREMENT = 1");
}


// Reset AUTO_INCREMENT if empty
$result = $conn->query("SELECT COUNT(*) AS count FROM product_colors");
$row = $result->fetch_assoc();
if ($row['count'] == 0) {
    $conn->query("ALTER TABLE product_colors AUTO_INCREMENT = 1");
}

// Reset AUTO_INCREMENT if empty
$result = $conn->query("SELECT COUNT(*) AS count FROM product_types");
$row = $result->fetch_assoc();
if ($row['count'] == 0) {
    $conn->query("ALTER TABLE product_types AUTO_INCREMENT = 1");
}

$result = $conn->query("SELECT COUNT(*) AS count FROM product_sizes");
$row = $result->fetch_assoc();
if ($row['count'] == 0) {
    $conn->query("ALTER TABLE product_sizes AUTO_INCREMENT = 1");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $conn->real_escape_string($_POST['product_name']);
    $description = $conn->real_escape_string($_POST['description']);
    $status = $conn->real_escape_string($_POST['status']);
    $created_at = date('Y-m-d H:i:s');

    // Handle main product image
    $upload_dir = "../../uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $main_image_path = "";
    if (!empty($_FILES['main_image']['name'])) {
        $main_image_file = time() . "_main_" . basename($_FILES['main_image']['name']);
        $main_image_path = $upload_dir . $main_image_file;
        move_uploaded_file($_FILES['main_image']['tmp_name'], $main_image_path);
    }

    // Validate required fields
    if (empty($product_name) || empty($main_image_path)) {
        $_SESSION['error_msg'] = "Please fill in all required fields: Product Name and Main Image.";
    } else {
        $main_image_rel = str_replace('../../', '', $main_image_path);

        // Start transaction
        $conn->begin_transaction();

        try {
            // 1. Insert into products table
            $insertProduct = "INSERT INTO products (product_name, main_image, description, status, created_at) 
                              VALUES ('$product_name', '$main_image_rel', '$description', '$status', '$created_at')";

            if ($conn->query($insertProduct) === TRUE) {
                $product_id = $conn->insert_id;

                // 2. Handle multiple product types
                if (isset($_POST['type_names']) && is_array($_POST['type_names'])) {
                    $type_names = $_POST['type_names'];
                    $type_prices = $_POST['type_prices'];
                    $type_descriptions = $_POST['type_descriptions'];

                    for ($t = 0; $t < count($type_names); $t++) {
                        if (!empty($type_names[$t])) {
                            $type_name = $conn->real_escape_string($type_names[$t]);
                            $type_price = floatval($type_prices[$t]);
                            $type_desc = $conn->real_escape_string($type_descriptions[$t]);

                            // Handle type-specific image
                            $type_image_rel = "";
                            if (!empty($_FILES['type_images']['name'][$t])) {
                                $type_image_file = time() . "_type_" . $t . "_" . basename($_FILES['type_images']['name'][$t]);
                                $type_image_path = $upload_dir . $type_image_file;
                                if (move_uploaded_file($_FILES['type_images']['tmp_name'][$t], $type_image_path)) {
                                    $type_image_rel = str_replace('../../', '', $type_image_path);
                                }
                            }

                            // Insert product type
                            $insertType = "INSERT INTO product_types 
                                            (product_id, type_name, type_image, price, description, created_at) 
                                            VALUES (
                                                $product_id, 
                                                '$type_name', 
                                                '$type_image_rel', 
                                                 $type_price, 
                                                '$type_desc', 
                                                '$created_at'
                                            )";

                            if ($conn->query($insertType)) {
                                $type_id = $conn->insert_id;

                                // 3. Handle sizes for this specific type
                                if (isset($_POST['sizes'][$t]) && is_array($_POST['sizes'][$t])) {
                                    $type_sizes = $_POST['sizes'][$t];

                                    foreach ($type_sizes as $size_index => $size_data) {
                                        if (!empty($size_data['name'])) {
                                            $size_name = $conn->real_escape_string($size_data['name']);
                                            $size_dimensions = $conn->real_escape_string($size_data['dimensions']);
                                            $size_price = floatval($size_data['price']);

                                            // Insert product size
                                            $insertSize = "INSERT INTO product_sizes 
                                                (type_id, size_name, dimensions, price, created_at) 
                                                VALUES (
                                                    $type_id, 
                                                    '$size_name', 
                                                    '$size_dimensions', 
                                                     $size_price, 
                                                    '$created_at'
                                                )";

                                            if (!$conn->query($insertSize)) {
                                                throw new Exception("Failed to insert product size: " . $conn->error);
                                            }
                                        }
                                    }
                                }

                                // 4. Handle colors for this specific type
                                if (isset($_POST['colors'][$t]) && is_array($_POST['colors'][$t])) {
                                    $type_colors = $_POST['colors'][$t];

                                    foreach ($type_colors as $color_index => $color_data) {
                                        if (!empty($color_data['name'])) {
                                            $color_name = $conn->real_escape_string($color_data['name']);
                                            $color_code = $conn->real_escape_string($color_data['code']);
                                            $color_price = floatval($color_data['price']);

                                            // Handle color-specific image
                                            $color_image_rel = "";
                                            if (!empty($_FILES['color_images']['name'][$t][$color_index])) {
                                                $color_image_file = time() . "_color_" . $t . "_" . $color_index . "_" . basename($_FILES['color_images']['name'][$t][$color_index]);
                                                $color_image_path = $upload_dir . $color_image_file;
                                                if (move_uploaded_file($_FILES['color_images']['tmp_name'][$t][$color_index], $color_image_path)) {
                                                    $color_image_rel = str_replace('../../', '', $color_image_path);
                                                }
                                            }

                                            // Insert product color
                                            $insertColor = "INSERT INTO product_colors 
                                                                        (type_id, color_name, color_code, color_image, price, created_at) 
                                                                        VALUES (
                                                                            $type_id, 
                                                                            '$color_name', 
                                                                            '$color_code', 
                                                                            '$color_image_rel', 
                                                                             $color_price, 
                                                                            '$created_at'
                                                                        )";

                                            if (!$conn->query($insertColor)) {
                                                throw new Exception("Failed to insert product color: " . $conn->error);
                                            }
                                        }
                                    }
                                }
                            } else {
                                throw new Exception("Failed to insert product type: " . $conn->error);
                            }
                        }
                    }
                }

                $conn->commit();
                $_SESSION['success_msg'] = "Product with all types, sizes, and colors added successfully!";
                
                // Redirect to prevent form re-submission and notification persistence
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
                
            } else {
                throw new Exception("Failed to insert product: " . $conn->error);
            }
        } catch (Exception $e) {
            $conn->rollback();
            $_SESSION['error_msg'] = " " . $e->getMessage();
        }
    }
    
    // Redirect after any POST processing (success or error)
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Add Product - Multiple Types, Sizes & Colors</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }

    .step-hidden {
      display: none;
    }

    .step-visible {
      display: block;
    }

    .step-indicator.active {
      background-color: #3B82F6;
      color: white;
    }

    .step-indicator.completed {
      background-color: #10B981;
      color: white;
    }

    .step-indicator {
      background-color: #E5E7EB;
      color: #6B7280;
    }

    .connector.active {
      background-color: #3B82F6;
    }

    .connector.completed {
      background-color: #10B981;
    }

    .connector {
      background-color: #E5E7EB;
    }

    .type-item {
      border: 2px solid #E5E7EB;
      transition: all 0.3s;
    }

    .type-item:hover {
      border-color: #3B82F6;
      box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
    }

  
/* Auto-hide animation styles */
.alert-success {
  animation: slideInDown 0.5s ease-out;
}

.alert-success.fade-out {
  animation: slideOutUp 0.5s ease-in forwards;
}

@keyframes slideInDown {
  from {
    transform: translateY(-100%);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

@keyframes slideOutUp {
  from {
    transform: translateY(0);
    opacity: 1;
  }
  to {
    transform: translateY(-100%);
    opacity: 0;
  }
}

/* Progress bar for countdown */
.progress-bar {
  height: 4px;
  background: linear-gradient(90deg, #10B981, #059669);
  animation: shrink 5s linear forwards;
}

@keyframes shrink {
  from { width: 100%; }
  to { width: 0%; }
}

  </style>
</head>

<body class="bg-gray-100">
  <div class="max-w-6xl mx-auto mt-10">

    <!-- Step Progress Indicator -->
    <div class="flex justify-center mb-8">
      <div class="flex items-center space-x-4">
        <div class="flex items-center">
          <div id="step1-indicator" class="w-10 h-10 step-indicator active rounded-full flex items-center justify-center font-bold text-lg">1</div>
          <span class="ml-2 font-medium">Main Product</span>
        </div>
        <div id="connector1" class="w-16 h-1 connector"></div>
        <div class="flex items-center">
          <div id="step2-indicator" class="w-10 h-10 step-indicator rounded-full flex items-center justify-center font-bold text-lg">2</div>
          <span class="ml-2 font-medium">Product Types</span>
        </div>
        <div id="connector2" class="w-16 h-1 connector"></div>
        <div class="flex items-center">
          <div id="step3-indicator" class="w-10 h-10 step-indicator rounded-full flex items-center justify-center font-bold text-lg">3</div>
          <span class="ml-2 font-medium">Review & Submit</span>
        </div>
      </div>
    </div>

 <div id="success-message" class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg alert-success relative <?php echo $success_msg ? '' : 'hidden'; ?>">
  <div class="flex items-center justify-between">
    <div class="flex items-center">
      <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
      </svg>
      <span id="success-text"><?php echo $success_msg; ?></span>
    </div>
    <button onclick="hideSuccessMessage()" class="text-green-600 hover:text-green-800 ml-4">
      <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
      </svg>
    </button>
  </div>
  <!-- Progress bar showing countdown -->
  <?php if ($success_msg): ?>
  <div class="progress-bar mt-2"></div>
  <?php endif; ?>
</div>

<div id="error-message" class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg <?php echo $error_msg ? '' : 'hidden'; ?>">
  <div class="flex items-center justify-between">
    <div class="flex items-center">
      <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
      </svg>
      <span id="error-text"><?php echo $error_msg; ?></span>
    </div>
    <button onclick="hideErrorMessage()" class="text-red-600 hover:text-red-800 ml-4">
      <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
      </svg>
    </button>
  </div>
</div>



    <form id="product-form" method="POST" action="" enctype="multipart/form-data">

      <!-- STEP 1: Main Product Information -->
      <div id="step1" class="step-visible bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold mb-6 text-center text-blue-800">Step 1: Main Product Information</h1>

        <div class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block mb-2 font-medium text-gray-700" for="product_name">Product Name <span class="text-red-600">*</span></label>
              <input type="text" id="product_name" name="product_name" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter product name" />
            </div>

            <div>
              <label class="block mb-2 font-medium text-gray-700" for="status">Status</label>
              <select id="status" name="status" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="Active" selected>Active</option>
                <option value="Inactive">Inactive</option>
                <option value="Archived">Archived</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block mb-2 font-medium text-gray-700" for="description">Product Description</label>
            <textarea id="description" name="description" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Describe your product..."></textarea>
          </div>

          <div>
            <label class="block mb-2 font-medium text-gray-700" for="main_image">Main Product Image <span class="text-red-600">*</span></label>
            <input type="file" id="main_image" name="main_image" accept="image/*" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
            <div id="main_image_preview" class="mt-4"></div>
          </div>
        </div>

        <div class="flex justify-end mt-8">
          <button type="button" onclick="nextStep(1)" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition font-medium">
            Next: Add Types →
          </button>
        </div>
      </div>

      <!-- STEP 2: Product Types -->
      <div id="step2" class="step-hidden bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold mb-6 text-center text-green-800">Step 2: Product Types, Sizes & Colors</h1>

        <div class="mb-6">
          <div class="flex justify-between items-center mb-4">
            <p class="text-gray-600">Add different types/variants for your product (e.g., T-Shirt, Hoodie, Mug)</p>
            <button type="button" onclick="addProductType()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
              + Add New Type
            </button>
          </div>
        </div>

        <div id="types-container">
          <!-- First type (default) -->
          <div class="type-item bg-gray-50 p-6 rounded-lg mb-6">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-xl font-semibold text-green-800">Type #1</h3>
              <button type="button" onclick="removeProductType(this)" class="text-red-600 hover:text-red-800">Remove Type</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
              <div>
                <label class="block mb-2 font-medium">Type Name <span class="text-red-600">*</span></label>
                <input type="text" name="type_names[]" required class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="e.g., T-Shirt, Hoodie, Mug" />
              </div>

              <div>
                <label class="block mb-2 font-medium">Base Price (₱) <span class="text-red-600">*</span></label>
                <input type="number" step="0.01" min="0" name="type_prices[]" required class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="0.00" />
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
              <div>
                <label class="block mb-2 font-medium">Type Image</label>
                <input type="file" name="type_images[]" accept="image/*" class="w-full border border-gray-300 rounded-lg px-3 py-2" onchange="previewTypeImage(this)" />
                <div class="type-image-preview mt-2"></div>
              </div>

              <div>
                <label class="block mb-2 font-medium">Type Description</label>
                <textarea name="type_descriptions[]" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Details about this type"></textarea>
              </div>
            </div>

            <!-- Sizes for this type -->
            <div class="border-t pt-4 mb-4">
              <div class="flex justify-between items-center mb-3">
                <h4 class="font-medium text-gray-700">Sizes for this Type</h4>
                <button type="button" onclick="addSizeToType(this)" class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600">
                  + Add Size
                </button>
              </div>

              <div class="sizes-for-type">
                <div class="size-item bg-white p-3 rounded border mb-3">
                  <div class="flex justify-between items-center mb-2">
                    <span class="font-medium text-sm">Size #1</span>
                    <button type="button" onclick="removeSizeFromType(this)" class="text-red-500 text-sm">Remove</button>
                  </div>

                  <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                      <label class="block mb-1 text-sm">Size Name *</label>
                      <input type="text" name="sizes[0][0][name]" required class="w-full border border-gray-300 rounded px-2 py-1 text-sm" placeholder="Small, Medium, Large..." />
                    </div>

                    <div>
                      <label class="block mb-1 text-sm">Dimensions (e.g., 500x900mm)</label>
                      <input type="text" name="sizes[0][0][dimensions]" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" placeholder="500x900mm" />
                    </div>

                    <div>
                      <label class="block mb-1 text-sm">Extra Price (₱)</label>
                      <input type="number" step="0.01" min="0" name="sizes[0][0][price]" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" placeholder="0.00" />
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Colors for this type -->
            <div class="border-t pt-4">
              <div class="flex justify-between items-center mb-3">
                <h4 class="font-medium text-gray-700">Colors for this Type</h4>
                <button type="button" onclick="addColorToType(this)" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                  + Add Color
                </button>
              </div>

              <div class="colors-for-type">
                <div class="color-item bg-white p-3 rounded border mb-3">
                  <div class="flex justify-between items-center mb-2">
                    <span class="font-medium text-sm">Color #1</span>
                    <button type="button" onclick="removeColorFromType(this)" class="text-red-500 text-sm">Remove</button>
                  </div>

                  <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    <div>
                      <label class="block mb-1 text-sm">Color Name *</label>
                      <input type="text" name="colors[0][0][name]" required class="w-full border border-gray-300 rounded px-2 py-1 text-sm" placeholder="Red, Blue..." />
                    </div>

                    <div>
                      <label class="block mb-1 text-sm">Color Code</label>
                      <div class="flex gap-1">
                        <input type="color" class="w-8 h-8 border rounded cursor-pointer" onchange="updateColorCodeInType(this)" />
                        <input type="text" name="colors[0][0][code]" class="flex-1 border border-gray-300 rounded px-2 py-1 text-sm" placeholder="#FF0000" />
                      </div>
                    </div>

                    <div>
                      <label class="block mb-1 text-sm">Extra Price (₱)</label>
                      <input type="number" step="0.01" min="0" name="colors[0][0][price]" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" placeholder="0.00" />
                    </div>

                    <div>
                      <label class="block mb-1 text-sm">Color Image</label>
                      <input type="file" name="color_images[0][0]" accept="image/*" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" onchange="previewColorImageInType(this)" />
                      <div class="color-image-preview-small mt-1"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="flex justify-between mt-8">
          <button type="button" onclick="prevStep(2)" class="bg-gray-600 text-white px-8 py-3 rounded-lg hover:bg-gray-700 transition font-medium">
            ← Back to Product
          </button>
          <button type="button" onclick="nextStep(2)" class="bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 transition font-medium">
            Next: Review & Submit →
          </button>
        </div>
      </div>

      <!-- STEP 3: Review & Submit -->
      <div id="step3" class="step-hidden bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold mb-6 text-center text-purple-800">Step 3: Review Your Product</h1>

        <div id="review-content" class="space-y-6">
          <!-- Review content will be populated by JavaScript -->
        </div>

        <div class="flex justify-between mt-8">
          <button type="button" onclick="prevStep(3)" class="bg-gray-600 text-white px-8 py-3 rounded-lg hover:bg-gray-700 transition font-medium">
            ← Back to Types
          </button>
          <button type="submit" class="bg-purple-600 text-white px-8 py-3 rounded-lg hover:bg-purple-700 transition font-medium">
            Create Product 🎉
          </button>
        </div>
      </div>
    </form>
  </div>
 
<script src="js/editproduct/editproduct.js"></script>

<script>
  
let successTimeout;
let errorTimeout;

// Function to manually hide success message
function hideSuccessMessage() {
  const successDiv = document.getElementById('success-message');
  successDiv.classList.add('fade-out');
  
  setTimeout(() => {
    successDiv.classList.add('hidden');
    successDiv.classList.remove('fade-out');
  }, 500);
  
  if (successTimeout) {
    clearTimeout(successTimeout);
  }
}

// Function to manually hide error message
function hideErrorMessage() {
  const errorDiv = document.getElementById('error-message');
  errorDiv.classList.add('hidden');
  
  if (errorTimeout) {
    clearTimeout(errorTimeout);
  }
}

// Auto-hide messages on page load
document.addEventListener('DOMContentLoaded', function() {
  const successDiv = document.getElementById('success-message');
  const errorDiv = document.getElementById('error-message');
  
  // Auto-hide success message after 5 seconds
  if (!successDiv.classList.contains('hidden')) {
    successTimeout = setTimeout(() => {
      hideSuccessMessage();
    }, 5000);
    
    // Pause auto-hide when hovering
    successDiv.addEventListener('mouseenter', function() {
      if (successTimeout) {
        clearTimeout(successTimeout);
      }
      this.querySelector('.progress-bar').style.animationPlayState = 'paused';
    });
    
    successDiv.addEventListener('mouseleave', function() {
      this.querySelector('.progress-bar').style.animationPlayState = 'running';
      successTimeout = setTimeout(() => {
        hideSuccessMessage();
      }, 2000);
    });
  }
  
  // Auto-hide error message after 7 seconds  
  if (!errorDiv.classList.contains('hidden')) {
    errorTimeout = setTimeout(() => {
      hideErrorMessage();
    }, 7000);
  }
});
</script>
</body>

</html>