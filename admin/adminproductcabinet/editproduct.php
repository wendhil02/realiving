<?php
include '../../connection/connection.php';

$success_msg = "";
$error_msg = "";

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
        $error_msg = "Please fill in all required fields: Product Name and Main Image.";
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

                                // 3. Handle colors for this specific type
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
                $success_msg = "✅ Product with all types and colors added successfully!";
            } else {
                throw new Exception("Failed to insert product: " . $conn->error);
            }
        } catch (Exception $e) {
            $conn->rollback();
            $error_msg = "❌ " . $e->getMessage();
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Add Product - Multiple Types & Colors</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet" />
  <style>
    body { font-family: 'Poppins', sans-serif; }
    .step-hidden { display: none; }
    .step-visible { display: block; }
    .step-indicator.active { background-color: #3B82F6; color: white; }
    .step-indicator.completed { background-color: #10B981; color: white; }
    .step-indicator { background-color: #E5E7EB; color: #6B7280; }
    .connector.active { background-color: #3B82F6; }
    .connector.completed { background-color: #10B981; }
    .connector { background-color: #E5E7EB; }
    .type-item { border: 2px solid #E5E7EB; transition: all 0.3s; }
    .type-item:hover { border-color: #3B82F6; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1); }
  </style>
</head>
<body class="bg-gray-100 p-6">
  <div class="max-w-6xl mx-auto">
    
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

    <!-- Success/Error Messages -->
    <?php if ($success_msg): ?>
      <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg"><?php echo $success_msg; ?></div>
    <?php endif; ?>
    <?php if ($error_msg): ?>
      <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg"><?php echo $error_msg; ?></div>
    <?php endif; ?>

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
        <h1 class="text-3xl font-bold mb-6 text-center text-green-800">Step 2: Product Types & Their Colors</h1>
        
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

  <script>
    let typeCounter = 1;
    let currentStep = 1;

    // Step Navigation
    function nextStep(step) {
      if (validateStep(step)) {
        hideStep(step);
        showStep(step + 1);
        updateStepIndicator(step + 1);
        currentStep = step + 1;
        
        if (step === 2) {
          generateReview();
        }
      }
    }

    function prevStep(step) {
      hideStep(step);
      showStep(step - 1);
      updateStepIndicator(step - 1);
      currentStep = step - 1;
    }

    function hideStep(step) {
      document.getElementById('step' + step).classList.remove('step-visible');
      document.getElementById('step' + step).classList.add('step-hidden');
    }

    function showStep(step) {
      document.getElementById('step' + step).classList.remove('step-hidden');
      document.getElementById('step' + step).classList.add('step-visible');
    }

    function updateStepIndicator(activeStep) {
      for (let i = 1; i <= 3; i++) {
        const indicator = document.getElementById('step' + i + '-indicator');
        const connector = document.getElementById('connector' + i);
        
        if (i < activeStep) {
          indicator.className = 'w-10 h-10 step-indicator completed rounded-full flex items-center justify-center font-bold text-lg';
          if (connector) connector.className = 'w-16 h-1 connector completed';
        } else if (i === activeStep) {
          indicator.className = 'w-10 h-10 step-indicator active rounded-full flex items-center justify-center font-bold text-lg';
          if (connector) connector.className = 'w-16 h-1 connector active';
        } else {
          indicator.className = 'w-10 h-10 step-indicator rounded-full flex items-center justify-center font-bold text-lg';
          if (connector) connector.className = 'w-16 h-1 connector';
        }
      }
    }

    function validateStep(step) {
      if (step === 1) {
        const productName = document.getElementById('product_name').value;
        const mainImage = document.getElementById('main_image').files[0];
        
        if (!productName.trim()) {
          alert('Please enter a product name');
          return false;
        }
        if (!mainImage) {
          alert('Please select a main product image');
          return false;
        }
      } else if (step === 2) {
        const typeNames = document.querySelectorAll('input[name="type_names[]"]');
        const typePrices = document.querySelectorAll('input[name="type_prices[]"]');
        
        for (let i = 0; i < typeNames.length; i++) {
          if (!typeNames[i].value.trim()) {
            alert(`Please enter a name for Type #${i + 1}`);
            return false;
          }
          if (!typePrices[i].value || parseFloat(typePrices[i].value) < 0) {
            alert(`Please enter a valid price for Type #${i + 1}`);
            return false;
          }
        }
      }
      return true;
    }

    // Product Type Management
    function addProductType() {
      typeCounter++;
      const container = document.getElementById('types-container');
      const newType = document.createElement('div');
      newType.className = 'type-item bg-gray-50 p-6 rounded-lg mb-6';
      newType.innerHTML = `
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-xl font-semibold text-green-800">Type #${typeCounter}</h3>
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
                  <input type="text" name="colors[${typeCounter-1}][0][name]" required class="w-full border border-gray-300 rounded px-2 py-1 text-sm" placeholder="Red, Blue..." />
                </div>
                
                <div>
                  <label class="block mb-1 text-sm">Color Code</label>
                  <div class="flex gap-1">
                    <input type="color" class="w-8 h-8 border rounded cursor-pointer" onchange="updateColorCodeInType(this)" />
                    <input type="text" name="colors[${typeCounter-1}][0][code]" class="flex-1 border border-gray-300 rounded px-2 py-1 text-sm" placeholder="#FF0000" />
                  </div>
                </div>
                
                <div>
                  <label class="block mb-1 text-sm">Extra Price (₱)</label>
                  <input type="number" step="0.01" min="0" name="colors[${typeCounter-1}][0][price]" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" placeholder="0.00" />
                </div>
                
                <div>
                  <label class="block mb-1 text-sm">Color Image</label>
                  <input type="file" name="color_images[${typeCounter-1}][0]" accept="image/*" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" onchange="previewColorImageInType(this)" />
                  <div class="color-image-preview-small mt-1"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      `;
      container.appendChild(newType);
    }

    function removeProductType(button) {
      const typeItems = document.querySelectorAll('.type-item');
      if (typeItems.length > 1) {
        button.closest('.type-item').remove();
        updateTypeNumbers();
      } else {
        alert('At least one product type is required!');
      }
    }

    function updateTypeNumbers() {
      const typeItems = document.querySelectorAll('.type-item');
      typeItems.forEach((item, index) => {
        const header = item.querySelector('h3');
        header.textContent = `Type #${index + 1}`;
        
        // Update color input names
        const colorInputs = item.querySelectorAll('input[name^="colors["]');
        colorInputs.forEach(input => {
          const name = input.name;
          const newName = name.replace(/colors\[\d+\]/, `colors[${index}]`);
          input.name = newName;
        });
        
        const colorFileInputs = item.querySelectorAll('input[name^="color_images["]');
        colorFileInputs.forEach(input => {
          const name = input.name;
          const newName = name.replace(/color_images\[\d+\]/, `color_images[${index}]`);
          input.name = newName;
        });
      });
    }

    // Color Management within Types
    function addColorToType(button) {
      const typeItem = button.closest('.type-item');
      const colorsContainer = typeItem.querySelector('.colors-for-type');
      const colorItems = colorsContainer.querySelectorAll('.color-item');
      const colorNumber = colorItems.length;
      const typeIndex = Array.from(document.querySelectorAll('.type-item')).indexOf(typeItem);
      
      const newColor = document.createElement('div');
      newColor.className = 'color-item bg-white p-3 rounded border mb-3';
      newColor.innerHTML = `
        <div class="flex justify-between items-center mb-2">
          <span class="font-medium text-sm">Color #${colorNumber + 1}</span>
          <button type="button" onclick="removeColorFromType(this)" class="text-red-500 text-sm">Remove</button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
          <div>
            <label class="block mb-1 text-sm">Color Name *</label>
            <input type="text" name="colors[${typeIndex}][${colorNumber}][name]" required class="w-full border border-gray-300 rounded px-2 py-1 text-sm" placeholder="Red, Blue..." />
          </div>
          
          <div>
            <label class="block mb-1 text-sm">Color Code</label>
            <div class="flex gap-1">
              <input type="color" class="w-8 h-8 border rounded cursor-pointer" onchange="updateColorCodeInType(this)" />
              <input type="text" name="colors[${typeIndex}][${colorNumber}][code]" class="flex-1 border border-gray-300 rounded px-2 py-1 text-sm" placeholder="#FF0000" />
            </div>
          </div>
          
          <div>
            <label class="block mb-1 text-sm">Extra Price (₱)</label>
            <input type="number" step="0.01" min="0" name="colors[${typeIndex}][${colorNumber}][price]" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" placeholder="0.00" />
          </div>
          
          <div>
            <label class="block mb-1 text-sm">Color Image</label>
            <input type="file" name="color_images[${typeIndex}][${colorNumber}]" accept="image/*" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" onchange="previewColorImageInType(this)" />
            <div class="color-image-preview-small mt-1"></div>
          </div>
        </div>
      `;
      colorsContainer.appendChild(newColor);
    }

    function removeColorFromType(button) {
      const typeItem = button.closest('.type-item');
      const colorItems = typeItem.querySelectorAll('.color-item');
      if (colorItems.length > 1) {
        button.closest('.color-item').remove();
        updateColorNumbersInType(typeItem);
      } else {
        alert('At least one color is required for each type!');
      }
    }

    function updateColorNumbersInType(typeItem) {
      const colorItems = typeItem.querySelectorAll('.color-item');
      const typeIndex = Array.from(document.querySelectorAll('.type-item')).indexOf(typeItem);
      
      colorItems.forEach((item, index) => {
        const colorNumber = item.querySelector('span');
        colorNumber.textContent = `Color #${index + 1}`;
        
        // Update input names
        const inputs = item.querySelectorAll('input[name^="colors["]');
        inputs.forEach(input => {
          const name = input.name;
          const newName = name.replace(/colors\[\d+\]\[\d+\]/, `colors[${typeIndex}][${index}]`);
          input.name = newName;
        });
        
        const fileInputs = item.querySelectorAll('input[name^="color_images["]');
        fileInputs.forEach(input => {
          const name = input.name;
          const newName = name.replace(/color_images\[\d+\]\[\d+\]/, `color_images[${typeIndex}][${index}]`);
          input.name = newName;
        });
      });
    }

    // Image preview functions
    function previewTypeImage(input) {
      const preview = input.parentElement.querySelector('.type-image-preview');
      preview.innerHTML = '';
      
      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          const img = document.createElement('img');
          img.src = e.target.result;
          img.className = 'w-20 h-20 object-cover rounded border';
          preview.appendChild(img);
        }
        reader.readAsDataURL(input.files[0]);
      }
    }

    function previewColorImageInType(input) {
      const preview = input.parentElement.querySelector('.color-image-preview-small');
      preview.innerHTML = '';
      
      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          const img = document.createElement('img');
          img.src = e.target.result;
          img.className = 'w-12 h-12 object-cover rounded border';
          preview.appendChild(img);
        }
        reader.readAsDataURL(input.files[0]);
      }
    }

    function updateColorCodeInType(colorPicker) {
      const colorText = colorPicker.parentElement.querySelector('input[type="text"]');
      colorText.value = colorPicker.value;
    }

    // Main image preview
    document.getElementById('main_image').addEventListener('change', function() {
      const preview = document.getElementById('main_image_preview');
      preview.innerHTML = '';
      
      if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          const img = document.createElement('img');
          img.src = e.target.result;
          img.className = 'w-32 h-32 object-cover rounded-lg border';
          preview.appendChild(img);
        }
        reader.readAsDataURL(this.files[0]);
      }
    });

    // Generate Review Content
    function generateReview() {
      const reviewContent = document.getElementById('review-content');
      const productName = document.getElementById('product_name').value;
      const description = document.getElementById('description').value;
      const status = document.getElementById('status').value;
      
      let reviewHTML = `
        <div class="bg-blue-50 p-4 rounded-lg">
          <h3 class="text-lg font-semibold text-blue-800 mb-2">📦 Main Product</h3>
          <p><strong>Name:</strong> ${productName}</p>
          <p><strong>Status:</strong> ${status}</p>
          <p><strong>Description:</strong> ${description || 'No description provided'}</p>
        </div>
      `;

      const typeItems = document.querySelectorAll('.type-item');
      typeItems.forEach((typeItem, typeIndex) => {
        const typeName = typeItem.querySelector('input[name="type_names[]"]').value;
        const typePrice = typeItem.querySelector('input[name="type_prices[]"]').value;
        const typeDesc = typeItem.querySelector('textarea[name="type_descriptions[]"]').value;
        
        reviewHTML += `
          <div class="bg-green-50 p-4 rounded-lg">
            <h3 class="text-lg font-semibold text-green-800 mb-2">🏷️ Type #${typeIndex + 1}: ${typeName}</h3>
            <p><strong>Base Price:</strong> ₱${typePrice}</p>
            <p><strong>Description:</strong> ${typeDesc || 'No description provided'}</p>
            
            <div class="mt-3">
              <h4 class="font-medium text-green-700 mb-2">Colors:</h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
        `;
        
        const colorItems = typeItem.querySelectorAll('.color-item');
        colorItems.forEach((colorItem, colorIndex) => {
          const colorName = colorItem.querySelector(`input[name="colors[${typeIndex}][${colorIndex}][name]"]`).value;
          const colorCode = colorItem.querySelector(`input[name="colors[${typeIndex}][${colorIndex}][code]"]`).value;
          const colorPrice = colorItem.querySelector(`input[name="colors[${typeIndex}][${colorIndex}][price]"]`).value;
          
          reviewHTML += `
            <div class="flex items-center gap-2 bg-white p-2 rounded border">
              <div class="w-4 h-4 rounded-full border" style="background-color: ${colorCode || '#ccc'}"></div>
              <span class="text-sm"><strong>${colorName}</strong> ${colorPrice ? `(+₱${colorPrice})` : ''}</span>
            </div>
          `;
        });
        
        reviewHTML += `
              </div>
            </div>
          </div>
        `;
      });

      reviewContent.innerHTML = reviewHTML;
    }
  </script>
</body>
</html>