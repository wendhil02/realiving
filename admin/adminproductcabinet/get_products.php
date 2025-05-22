<?php 
session_start(); 
include '../../connection/connection.php';

$id = intval($_GET['id']);

// First, let's get the product info
$product_sql = "SELECT * FROM products WHERE product_id = $id";
$product_result = $conn->query($product_sql);

if ($product_result->num_rows === 0) {
    echo "<div class='min-h-screen flex items-center justify-center bg-gray-100'>
            <div class='text-center'>
                <h1 class='text-2xl font-bold text-gray-700 mb-4'>Product not found</h1>
                <a href='index.php' class='bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700'>Back to Products</a>
            </div>
          </div>";
    exit;
}

$product = $product_result->fetch_assoc();

// Get product types
$types_sql = "SELECT * FROM product_types WHERE product_id = $id ORDER BY type_name ASC";
$types_result = $conn->query($types_sql);
$product_types = [];
while ($row = $types_result->fetch_assoc()) {
    $product_types[$row['type_id']] = $row;
}

// Try to get product colors (gracefully handle if table doesn't exist)
$type_colors = [];
$colors_query_success = false;

if (!empty($product_types)) {
    try {
        $colors_sql = "SELECT pc.*, pt.type_id 
                       FROM product_colors pc 
                       INNER JOIN product_types pt ON pc.type_id = pt.type_id 
                       WHERE pt.product_id = $id 
                       ORDER BY pc.color_name ASC";
        $colors_result = $conn->query($colors_sql);
        
        if ($colors_result) {
            $colors_query_success = true;
            while ($row = $colors_result->fetch_assoc()) {
                $type_colors[$row['type_id']][] = [
                    'color_id' => $row['id'],
                    'color_name' => $row['color_name'],
                    'color_code' => $row['color_code'],
                    'color_image' => $row['color_image'],
                    'price' => $row['price']
                ];
            }
        }
    } catch (Exception $e) {
        // Table doesn't exist or there's an error, continue without colors
        $colors_query_success = false;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customize <?= htmlspecialchars($product['product_name']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .type-card, .color-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .type-card:hover, .color-card:hover {
            transform: translateY(-2px);
        }
        .type-card.selected, .color-card.selected {
            border-color: #4f46e5;
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.2);
        }
        .price-display {
            background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .image-preview {
            transition: all 0.5s ease;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    
    <!-- Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-6xl mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="index.php" class="text-gray-600 hover:text-indigo-600 transition">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Customize Product</h1>
                        <p class="text-gray-600"><?= htmlspecialchars($product['product_name']) ?></p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-palette text-indigo-600"></i>
                    <span class="text-sm text-gray-600">Choose your preferred variant</span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-6 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- Left Side - Product Image and Info -->
            <div class="space-y-6">
                <!-- Main Product Image -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="relative">
                        <img id="main-image" 
                             src="../../<?= htmlspecialchars($product['main_image']) ?>" 
                             alt="<?= htmlspecialchars($product['product_name']) ?>" 
                             class="w-full h-80 object-cover rounded-xl image-preview" />
                        
                        <!-- Image Overlay Info -->
                        <div class="absolute bottom-4 left-4 bg-black bg-opacity-70 text-white px-3 py-2 rounded-lg">
                            <span id="selected-variant-name">Main Product</span>
                        </div>
                    </div>
                </div>

                <!-- Product Description -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold mb-3 flex items-center">
                        <i class="fas fa-info-circle text-indigo-600 mr-2"></i>
                        Product Details
                    </h3>
                    <?php if (!empty($product['description'])): ?>
                        <p class="text-gray-700 leading-relaxed"><?= htmlspecialchars($product['description']) ?></p>
                    <?php else: ?>
                        <p class="text-gray-500 italic">No description available for this product.</p>
                    <?php endif; ?>
                    
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            Added on <?= date('F d, Y', strtotime($product['created_at'])) ?>
                        </div>
                    </div>
                </div>

                <!-- Selected Summary -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold mb-3 flex items-center">
                        <i class="fas fa-check-circle text-green-600 mr-2"></i>
                        Your Selection
                    </h3>
                    <div id="selection-summary" class="space-y-2">
                        <p><strong>Type:</strong> <span id="selected-type-summary">Please select a type</span></p>
                        <?php if ($colors_query_success): ?>
                        <p><strong>Color:</strong> <span id="selected-color-summary">Please select a color</span></p>
                        <?php endif; ?>
                        <p><strong>Unit Price:</strong> <span id="selected-price" class="price-display font-bold">₱0.00</span></p>
                        <p><strong>Quantity:</strong> <span id="quantity-summary">1</span></p>
                        <div class="pt-2 border-t border-gray-200">
                            <p class="text-xl"><strong>Total:</strong> <span id="total-price" class="price-display font-bold text-2xl">₱0.00</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Customization Options -->
            <div class="space-y-6">
                
                <!-- Customization Form -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-xl font-bold mb-6 flex items-center">
                        <i class="fas fa-cogs text-indigo-600 mr-2"></i>
                        Choose Your Variant
                    </h2>

                    <?php if (empty($product_types)): ?>
                        <div class="text-center py-8">
                            <i class="fas fa-exclamation-triangle text-4xl text-yellow-500 mb-4"></i>
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">No Variants Available</h3>
                            <p class="text-gray-600 mb-4">This product doesn't have any customization options yet.</p>
                        </div>
                    <?php else: ?>
                        
                        <form method="POST" action="process_customization.php" id="customization-form">
                            <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>" />
                            <input type="hidden" name="selected_type_id" id="selected_type_id" value="" />
                            <?php if ($colors_query_success): ?>
                            <input type="hidden" name="selected_color_id" id="selected_color_id" value="" />
                            <?php endif; ?>

                            <!-- Step 1: Product Types -->
                            <div class="mb-8">
                                <h3 class="text-lg font-semibold mb-4 flex items-center">
                                    <span class="bg-indigo-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm mr-2">1</span>
                                    Choose Product Type
                                </h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <?php foreach ($product_types as $type): ?>
                                        <div class="type-card border-2 border-gray-200 rounded-xl p-4 hover:shadow-md" 
                                             onclick="selectType(<?= $type['type_id'] ?>, '<?= htmlspecialchars($type['type_name'], ENT_QUOTES) ?>', '<?= addslashes($type['type_image']) ?>', <?= $type['price'] ?>)"
                                             data-type-id="<?= $type['type_id'] ?>">
                                            
                                            <!-- Type Image -->
                                            <?php if (!empty($type['type_image'])): ?>
                                                <div class="mb-3">
                                                    <img src="../../<?= htmlspecialchars($type['type_image']) ?>" 
                                                         alt="<?= htmlspecialchars($type['type_name']) ?>" 
                                                         class="w-full h-24 object-cover rounded-lg" />
                                                </div>
                                            <?php endif; ?>

                                            <!-- Type Info -->
                                            <div class="text-center">
                                                <h4 class="font-semibold text-gray-800 mb-1"><?= htmlspecialchars($type['type_name']) ?></h4>
                                                <p class="text-lg font-bold text-indigo-600">₱<?= number_format($type['price'], 2) ?></p>
                                                
                                                <?php if (!empty($type['description'])): ?>
                                                    <p class="text-xs text-gray-500 mt-1"><?= htmlspecialchars($type['description']) ?></p>
                                                <?php endif; ?>
                                            </div>

                                            <!-- Selection Indicator -->
                                            <div class="hidden selection-indicator mt-3 text-center">
                                                <i class="fas fa-check-circle text-indigo-600 text-xl"></i>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <?php if ($colors_query_success): ?>
                            <!-- Step 2: Colors (Initially hidden) -->
                            <div id="color-selection" class="mb-8 hidden">
                                <h3 class="text-lg font-semibold mb-4 flex items-center">
                                    <span class="bg-indigo-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm mr-2">2</span>
                                    Choose Color
                                </h3>
                                <div id="colors-container" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                                    <!-- Colors will be populated by JavaScript -->
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- Step 3: Quantity and Options -->
                            <div id="quantity-options" class="mb-6 <?= $colors_query_success ? 'hidden' : '' ?>">
                                <h3 class="text-lg font-semibold mb-4 flex items-center">
                                    <span class="bg-indigo-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm mr-2"><?= $colors_query_success ? '3' : '2' ?></span>
                                    Quantity & Options
                                </h3>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-semibold mb-2">Quantity</label>
                                        <div class="flex items-center space-x-3">
                                            <button type="button" onclick="changeQuantity(-1)" class="bg-gray-200 hover:bg-gray-300 text-gray-700 w-8 h-8 rounded-full flex items-center justify-center">-</button>
                                            <input type="number" name="quantity" id="quantity" value="1" min="1" class="w-20 text-center border border-gray-300 rounded px-3 py-1" readonly />
                                            <button type="button" onclick="changeQuantity(1)" class="bg-gray-200 hover:bg-gray-300 text-gray-700 w-8 h-8 rounded-full flex items-center justify-center">+</button>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold mb-2">Special Instructions (Optional)</label>
                                        <textarea name="special_instructions" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Any special requests or customizations..."></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div id="action-buttons" class="space-y-3 <?= $colors_query_success ? 'hidden' : '' ?>">
                                <button type="submit" name="action" value="add_to_cart" 
                                        class="w-full bg-indigo-600 text-white py-3 rounded-xl hover:bg-indigo-700 transition font-semibold flex items-center justify-center">
                                    <i class="fas fa-shopping-cart mr-2"></i>
                                    Add to Cart
                                </button>
                                <button type="submit" name="action" value="buy_now" 
                                        class="w-full bg-green-600 text-white py-3 rounded-xl hover:bg-green-700 transition font-semibold flex items-center justify-center">
                                    <i class="fas fa-bolt mr-2"></i>
                                    Buy Now
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Store colors data for each type
        const typeColors = <?= json_encode($type_colors) ?>;
        const hasColors = <?= $colors_query_success ? 'true' : 'false' ?>;
        let selectedType = null;
        let selectedColor = null;
        let selectedTypePrice = 0;
        let selectedColorPrice = 0;

        function selectType(typeId, typeName, typeImage, price) {
            selectedType = { id: typeId, name: typeName, image: typeImage, price: price };
            selectedTypePrice = price;
            
            // Update hidden field
            document.getElementById('selected_type_id').value = typeId;
            
            // Update main image if type has image
            const mainImage = document.getElementById('main-image');
            const selectedVariantName = document.getElementById('selected-variant-name');
            
            if (typeImage && typeImage.trim() !== '') {
                mainImage.src = '../../' + typeImage;
            } else {
                mainImage.src = '../../<?= htmlspecialchars($product['main_image']) ?>';
            }
            selectedVariantName.textContent = typeName;
            
            // Update selection summary
            document.getElementById('selected-type-summary').textContent = typeName;
            
            // Clear color selection if colors exist
            if (hasColors) {
                selectedColor = null;
                const colorIdField = document.getElementById('selected_color_id');
                if (colorIdField) colorIdField.value = '';
                const colorSummary = document.getElementById('selected-color-summary');
                if (colorSummary) colorSummary.textContent = 'Please select a color';
            }
            
            // Update card selection states
            document.querySelectorAll('.type-card').forEach(card => {
                card.classList.remove('selected');
                card.querySelector('.selection-indicator').classList.add('hidden');
            });
            
            const selectedCard = document.querySelector(`[data-type-id="${typeId}"]`);
            selectedCard.classList.add('selected');
            selectedCard.querySelector('.selection-indicator').classList.remove('hidden');
            
            if (hasColors) {
                // Show colors for this type
                showColorsForType(typeId);
                
                // Hide quantity options and action buttons until color is selected
                document.getElementById('quantity-options').classList.add('hidden');
                document.getElementById('action-buttons').classList.add('hidden');
            } else {
                // No colors system, show quantity and actions immediately
                document.getElementById('quantity-options').classList.remove('hidden');
                document.getElementById('action-buttons').classList.remove('hidden');
            }
            
            updateTotalPrice();
        }

        function showColorsForType(typeId) {
            if (!hasColors) return;
            
            const colorSelection = document.getElementById('color-selection');
            const colorsContainer = document.getElementById('colors-container');
            
            // Clear existing colors
            colorsContainer.innerHTML = '';
            
            // Check if this type has colors
            if (typeColors[typeId] && typeColors[typeId].length > 0) {
                colorSelection.classList.remove('hidden');
                
                typeColors[typeId].forEach(color => {
                    const colorCard = document.createElement('div');
                    colorCard.className = 'color-card border-2 border-gray-200 rounded-lg p-3 hover:shadow-md text-center';
                    colorCard.setAttribute('data-color-id', color.color_id);
                    colorCard.onclick = () => selectColor(color.color_id, color.color_name, color.color_code, color.color_image, color.price);
                    
                    colorCard.innerHTML = `
                        ${color.color_image ? `<img src="../../${color.color_image}" alt="${color.color_name}" class="w-full h-16 object-cover rounded mb-2" />` : ''}
                        <div class="flex items-center justify-center mb-2">
                            <div class="w-4 h-4 rounded-full mr-2 border border-gray-300" style="background-color: ${color.color_code || '#ccc'}"></div>
                            <span class="text-sm font-medium">${color.color_name}</span>
                        </div>
                        ${color.price > 0 ? `<p class="text-xs text-green-600">+₱${parseFloat(color.price).toFixed(2)}</p>` : ''}
                        <div class="hidden selection-indicator mt-2">
                            <i class="fas fa-check-circle text-indigo-600"></i>
                        </div>
                    `;
                    
                    colorsContainer.appendChild(colorCard);
                });
            } else {
                // No colors available for this type, auto-select and show next steps
                colorSelection.classList.add('hidden');
                const colorSummary = document.getElementById('selected-color-summary');
                if (colorSummary) colorSummary.textContent = 'No color options';
                document.getElementById('quantity-options').classList.remove('hidden');
                document.getElementById('action-buttons').classList.remove('hidden');
            }
        }

        function selectColor(colorId, colorName, colorCode, colorImage, price) {
            selectedColor = { id: colorId, name: colorName, code: colorCode, image: colorImage, price: price };
            selectedColorPrice = parseFloat(price) || 0;
            
            // Update hidden field
            const colorIdField = document.getElementById('selected_color_id');
            if (colorIdField) colorIdField.value = colorId;
            
            // Update main image if color has specific image
            const mainImage = document.getElementById('main-image');
            const selectedVariantName = document.getElementById('selected-variant-name');
            
            if (colorImage && colorImage.trim() !== '') {
                mainImage.src = '../../' + colorImage;
                selectedVariantName.textContent = `${selectedType.name} - ${colorName}`;
            } else if (selectedType.image && selectedType.image.trim() !== '') {
                mainImage.src = '../../' + selectedType.image;
                selectedVariantName.textContent = `${selectedType.name} - ${colorName}`;
            } else {
                mainImage.src = '../../<?= htmlspecialchars($product['main_image']) ?>';
                selectedVariantName.textContent = `${selectedType.name} - ${colorName}`;
            }
            
            // Update selection summary
            const colorSummary = document.getElementById('selected-color-summary');
            if (colorSummary) colorSummary.textContent = colorName;
            
            // Update card selection states
            document.querySelectorAll('.color-card').forEach(card => {
                card.classList.remove('selected');
                const indicator = card.querySelector('.selection-indicator');
                if (indicator) indicator.classList.add('hidden');
            });
            
            const selectedCard = document.querySelector(`[data-color-id="${colorId}"]`);
            selectedCard.classList.add('selected');
            const indicator = selectedCard.querySelector('.selection-indicator');
            if (indicator) indicator.classList.remove('hidden');
            
            // Show quantity options and action buttons
            document.getElementById('quantity-options').classList.remove('hidden');
            document.getElementById('action-buttons').classList.remove('hidden');
            
            updateTotalPrice();
        }

        function changeQuantity(change) {
            const quantityInput = document.getElementById('quantity');
            let currentQuantity = parseInt(quantityInput.value);
            let newQuantity = currentQuantity + change;
            
            if (newQuantity < 1) newQuantity = 1;
            
            quantityInput.value = newQuantity;
            document.getElementById('quantity-summary').textContent = newQuantity;
            updateTotalPrice();
        }

        function updateTotalPrice() {
            const unitPrice = selectedTypePrice + selectedColorPrice;
            const quantity = parseInt(document.getElementById('quantity').value) || 1;
            const totalPrice = unitPrice * quantity;
            
            document.getElementById('selected-price').textContent = '₱' + unitPrice.toLocaleString('en-US', {minimumFractionDigits: 2});
            document.getElementById('total-price').textContent = '₱' + totalPrice.toLocaleString('en-US', {minimumFractionDigits: 2});
        }

        // Form validation
        document.getElementById('customization-form').addEventListener('submit', function(e) {
            const selectedTypeId = document.getElementById('selected_type_id').value;
            if (!selectedTypeId) {
                e.preventDefault();
                alert('Please select a product type.');
                return false;
            }
            
            // Check if colors exist for this type and if one is selected
            if (hasColors) {
                const typeId = selectedTypeId;
                if (typeColors[typeId] && typeColors[typeId].length > 0) {
                    const colorIdField = document.getElementById('selected_color_id');
                    const selectedColorId = colorIdField ? colorIdField.value : '';
                    if (!selectedColorId) {
                        e.preventDefault();
                        alert('Please select a color.');
                        return false;
                    }
                }
            }
        });
    </script>
</body>
</html>