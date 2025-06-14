<?php
session_start();
include '../../connection/connection.php';

// Validate and sanitize the ID parameter
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='min-h-screen flex items-center justify-center bg-gray-100'>
            <div class='text-center'>
                <h1 class='text-2xl font-bold text-gray-700 mb-4'>Invalid product ID</h1>
                <a href='../product_cabinet.php' class='bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700'>Back to Products</a>
            </div>
          </div>";
    exit;
}

$id = intval($_GET['id']);

// Get main product
$product_stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$product_stmt->bind_param("i", $id);
$product_stmt->execute();
$product_result = $product_stmt->get_result();

if ($product_result->num_rows === 0) {
    echo "<div class='min-h-screen flex items-center justify-center bg-gray-100'>
            <div class='text-center'>
                <h1 class='text-2xl font-bold text-gray-700 mb-4'>Product not found</h1>
                <a href='../product_cabinet.php' class='bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700'>Back to Products</a>
            </div>
          </div>";
    exit;
}

$product = $product_result->fetch_assoc();

// Fetch product types
$all_data = [];
$types_stmt = $conn->prepare("SELECT id, type_name, type_image_blob, type_image_type, base_price FROM product_types WHERE product_id = ? ORDER BY type_name ASC");
$types_stmt->bind_param("i", $id);
$types_stmt->execute();
$types_result = $types_stmt->get_result();

while ($type_row = $types_result->fetch_assoc()) {
    $type_id = $type_row['id'];

    // Handle type image blob
    $type_image = '';
    if (!empty($type_row['type_image_blob'])) {
        $image_type = $type_row['type_image_type'] ?? 'image/jpeg';
        $type_image = "data:" . $image_type . ";base64," . base64_encode($type_row['type_image_blob']);
    }

    $all_data[$type_id] = [
        'type_id' => $type_id,
        'type_name' => $type_row['type_name'] ?? 'Unnamed Type',
        'type_image' => $type_image,
        'price' => $type_row['base_price'] ?? 0
    ];
}

// Fetch product sizes
$sizes_data = [];
$sizes_stmt = $conn->prepare("
    SELECT ps.id, ps.product_type_id, ps.size_name, ps.dimensions, ps.extra_price 
    FROM product_sizes ps 
    INNER JOIN product_types pt ON ps.product_type_id = pt.id 
    WHERE pt.product_id = ? 
    ORDER BY ps.product_type_id, ps.size_name ASC
");
$sizes_stmt->bind_param("i", $id);
$sizes_stmt->execute();
$sizes_result = $sizes_stmt->get_result();

while ($size_row = $sizes_result->fetch_assoc()) {
    $type_id = $size_row['product_type_id'];
    if (!isset($sizes_data[$type_id])) {
        $sizes_data[$type_id] = [];
    }
    $sizes_data[$type_id][] = [
        'id' => $size_row['id'],
        'size_name' => $size_row['size_name'],
        'dimensions' => $size_row['dimensions'],
        'extra_price' => $size_row['extra_price'] ?? 0
    ];
}

// Fetch product colors
$colors_data = [];
$colors_stmt = $conn->prepare("
    SELECT pc.id, pc.product_type_id, pc.color_name, pc.color_code, pc.extra_price, pc.color_image_blob, pc.color_image_type
    FROM product_colors pc 
    INNER JOIN product_types pt ON pc.product_type_id = pt.id 
    WHERE pt.product_id = ? 
    ORDER BY pc.product_type_id, pc.color_name ASC
");
$colors_stmt->bind_param("i", $id);
$colors_stmt->execute();
$colors_result = $colors_stmt->get_result();

while ($color_row = $colors_result->fetch_assoc()) {
    $type_id = $color_row['product_type_id'];
    if (!isset($colors_data[$type_id])) {
        $colors_data[$type_id] = [];
    }

    // Handle color image blob
    $color_image = '';
    if (!empty($color_row['color_image_blob'])) {
        $image_type = $color_row['color_image_type'] ?? 'image/jpeg';
        $color_image = "data:" . $image_type . ";base64," . base64_encode($color_row['color_image_blob']);
    }

    $colors_data[$type_id][] = [
        'id' => $color_row['id'],
        'color_name' => $color_row['color_name'],
        'color_code' => $color_row['color_code'],
        'color_image' => $color_image,
        'extra_price' => $color_row['extra_price'] ?? 0
    ];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Customize <?= htmlspecialchars($product['product_name'] ?? 'Product') ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&family=Montserrat&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="design/get_products.css">
 
</head>

<body>
    <div class="header">
        <div class="header-container">
            <div class="header-content">
                <a href="../product_cabinet.php" class="back-link">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="font-[montserrat]">CREATE YOUR STYLE</h1>
                    <p><?= htmlspecialchars($product['product_name'] ?? 'Product') ?></p>
                </div>
            </div>
            <div class="header-info">
                <i class="fas fa-palette"></i>
                <span class="font-[montserrat]">Choose your preferred variant</span>
            </div>
        </div>
    </div>

    <div class="main-container">
        <div class="grid-container">
            <!-- Left Side - Product Image and Info -->
            <div class="left-column">
                <!-- Main Product Image -->
                <div class="card">
                    <div class="image-container">
                        <?php
                        $image_src = '';
                        if (!empty($product['main_image'])) {
                            $image_src = "../../" . htmlspecialchars($product['main_image']);
                        } elseif (!empty($product['main_image_blob'])) {
                            $image_src = "data:" . ($product['main_image_type'] ?? 'image/jpeg') . ";base64," . base64_encode($product['main_image_blob']);
                        } else {
                            $image_src = "../../images/placeholder.jpg";
                        }
                        ?>
                        <img id="main-image"
                            src="<?= $image_src ?>"
                            alt="<?= htmlspecialchars($product['product_name'] ?? 'Product') ?>"
                            class="image-preview" />
                        <div class="image-overlay">
                            <span id="selected-variant-name">Main Product</span>
                        </div>
                    </div>
                </div>

                <!-- Product Description -->
                <div class="card">
                    <h3><i class="fas fa-info-circle"></i>Product Details</h3>
                    <?php if (!empty($product['description'])): ?>
                        <p><?= htmlspecialchars($product['description']) ?></p>
                    <?php else: ?>
                        <p class="no-description">No description available for this product.</p>
                    <?php endif; ?>

                    <div class="product-meta">
                        <div>
                            <i class="fas fa-calendar-alt"></i>
                            Added on <?= date('F d, Y', strtotime($product['created_at'] ?? 'now')) ?>
                        </div>
                    </div>
                </div>

                <!-- Selected Summary -->
                <div class="card">
                    <h3><i class="fas fa-check-circle"></i>Your Selection</h3>
                    <div id="selection-summary" class="summary-content">
                        <p><strong>Type:</strong> <span id="selected-type-summary">Please select a type</span></p>
                        <p><strong>Size:</strong> <span id="selected-size-summary">Please select a size</span></p>
                        <p><strong>Color:</strong> <span id="selected-color-summary">Please select a color</span></p>
                        <p><strong>Unit Price:</strong> <span id="unit-price" class="price-display">₱0.00</span></p>
                        <p><strong>Quantity:</strong> <span id="quantity-summary">1</span></p>
                        <div class="total-section">
                            <p><strong>Total:</strong> <span id="total-price" class="price-display total-price">₱0.00</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Customization Options -->
            <div class="right-column">
                <div class="card">
                    <h2><i class="fas fa-cogs"></i>Choose Your Variant</h2>

                    <?php if (empty($all_data)): ?>
                        <div class="no-variants">
                            <i class="fas fa-exclamation-triangle"></i>
                            <h3>No Variants Available</h3>
                            <p>This product doesn't have any customization options yet.</p>
                        </div>
                    <?php else: ?>
                        <form method="POST" action="process_customization.php" id="customization-form">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?? 0 ?>">
                            <input type="hidden" name="selected_type_id" id="selected_type_id">
                            <input type="hidden" name="selected_size_id" id="selected_size_id">
                            <input type="hidden" name="selected_color_id" id="selected_color_id">

                            <!-- Product Types -->
                            <div class="step-section">
                                <h3><span>1</span>Choose Product Type</h3>
                                <div class="type-grid">
                                    <?php foreach ($all_data as $type_id => $type_data): ?>
                                        <div class="type-card"
                                            onclick="selectType(<?= $type_id ?>)"
                                            data-type-id="<?= $type_id ?>">
                                            <?php if (!empty($type_data['type_image'])): ?>
                                                <div class="type-image">
                                                    <img src="<?= $type_data['type_image'] ?>"
                                                        alt="<?= htmlspecialchars($type_data['type_name']) ?>" />
                                                </div>
                                            <?php else: ?>
                                                <div class="type-image placeholder">
                                                    <i class="fas fa-image"></i>
                                                    <span>No Image</span>
                                                </div>
                                            <?php endif; ?>
                                            <div class="type-info">
                                                <h4><?= htmlspecialchars($type_data['type_name']) ?></h4>
                                                <p class="type-price">₱<?= number_format($type_data['price'], 2) ?></p>
                                            </div>
                                            <div class="selection-indicator">
                                                <i class="fas fa-check-circle"></i>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Product Sizes -->
                            <div id="size-options" class="step-section hidden">
                                <h3><span>2</span>Choose Size</h3>
                                <div id="size-grid" class="size-grid">
                                    <!-- Sizes will be populated by JavaScript -->
                                </div>
                            </div>

                            <!-- Product Colors -->
                            <div id="color-options" class="step-section hidden">
                                <h3><span>3</span>Choose Color</h3>
                                <div id="color-grid" class="color-grid">
                                    <!-- Colors will be populated by JavaScript -->
                                </div>
                            </div>

                            <!-- Quantity Options -->
                            <div id="quantity-options" class="step-section hidden">
                                <h3><span>4</span>Quantity & Options</h3>
                                <div class="quantity-section">
                                    <div>
                                        <label>Quantity</label>
                                        <div class="quantity-control">
                                            <button type="button" onclick="changeQuantity(-1)">-</button>
                                            <input type="number" name="quantity" id="quantity" value="1" min="1" readonly />
                                            <button type="button" onclick="changeQuantity(1)">+</button>
                                        </div>
                                    </div>

                                    <div class="instructions">
                                        <label>Special Instructions (Optional)</label>
                                        <textarea name="special_instructions" placeholder="Any special requests or customizations..."></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div id="action-buttons" class="hidden">
                                <button type="submit" name="action" value="add_to_cart">
                                    Add to Cart
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
     // Pass data to JavaScript
window.ProductConfig = {
    product: <?= json_encode([
                    'id' => $product['id'] ?? 0,
                    'name' => $product['product_name'] ?? 'Product',
                    'main_image' => $product['main_image'] ?? '',
                    'main_image_src' => $image_src  // Add the actual image source
                ]) ?>,
    allData: <?= json_encode($all_data) ?>,
    sizesData: <?= json_encode($sizes_data) ?>,
    colorsData: <?= json_encode($colors_data) ?>
};

let selectedTypeId = null;
let selectedSizeId = null;
let selectedColorId = null;

function updateMainImage() {
    const mainImage = document.getElementById('main-image');
    const variantName = document.getElementById('selected-variant-name');
    
    // Priority order: Color image > Type image > Product main image
    let imageToShow = window.ProductConfig.product.main_image_src;
    let imageName = 'Main Product';
    
    // Check if type is selected and has image
    if (selectedTypeId && window.ProductConfig.allData[selectedTypeId].type_image) {
        imageToShow = window.ProductConfig.allData[selectedTypeId].type_image;
        imageName = window.ProductConfig.allData[selectedTypeId].type_name;
    }
    
    // Check if color is selected and has image (highest priority)
    if (selectedColorId) {
        const colors = window.ProductConfig.colorsData[selectedTypeId] || [];
        const selectedColor = colors.find(color => color.id == selectedColorId);
        if (selectedColor && selectedColor.color_image) {
            imageToShow = selectedColor.color_image;
            imageName = `${selectedColor.color_name} Variant`;
        } else if (selectedColor) {
            // If color doesn't have image but is selected, keep the type image but update name
            imageName = `${selectedColor.color_name} - ${window.ProductConfig.allData[selectedTypeId].type_name}`;
        }
    }
    
    mainImage.src = imageToShow;
    variantName.textContent = imageName;
}

function selectType(typeId) {
    selectedTypeId = typeId;
    selectedSizeId = null; // Reset size selection
    selectedColorId = null; // Reset color selection

    // Remove previous selections
    document.querySelectorAll('.type-card').forEach(card => {
        card.classList.remove('selected');
    });

    // Add selection to clicked card
    document.querySelector(`[data-type-id="${typeId}"]`).classList.add('selected');

    // Update hidden inputs
    document.getElementById('selected_type_id').value = typeId;
    document.getElementById('selected_size_id').value = '';
    document.getElementById('selected_color_id').value = '';

    // Update summary
    const typeData = window.ProductConfig.allData[typeId];
    document.getElementById('selected-type-summary').textContent = typeData.type_name;

    // Reset other summary fields
    document.getElementById('selected-size-summary').textContent = 'Please select a size';
    document.getElementById('selected-color-summary').textContent = 'Please select a color';
    document.getElementById('unit-price').textContent = `₱${parseFloat(typeData.price).toFixed(2)}`;

    // Update main image
    updateMainImage();

    // Show size options
    loadSizes(typeId);
    document.getElementById('size-options').classList.remove('hidden');

    // Hide subsequent options
    document.getElementById('color-options').classList.add('hidden');
    document.getElementById('quantity-options').classList.add('hidden');
    document.getElementById('action-buttons').classList.add('hidden');

    updateTotal();
}

function loadSizes(typeId) {
    const sizeGrid = document.getElementById('size-grid');
    sizeGrid.innerHTML = '';

    const sizes = window.ProductConfig.sizesData[typeId] || [];

    if (sizes.length === 0) {
        sizeGrid.innerHTML = '<p class="text-gray-500">No sizes available for this type.</p>';
        return;
    }

    sizes.forEach(size => {
        const sizeCard = document.createElement('div');
        sizeCard.className = 'size-card';
        sizeCard.setAttribute('data-size-id', size.id);
        sizeCard.onclick = () => selectSize(size.id, size.size_name, size.extra_price);

        sizeCard.innerHTML = `
<div class="size-info">
    <h4>${size.size_name}</h4>
    ${size.dimensions ? `<div class="size-dimensions">${size.dimensions}</div>` : ''}
</div>
<div class="selection-indicator">
    <i class="fas fa-check-circle"></i>
</div>
`;

        sizeGrid.appendChild(sizeCard);
    });
}

function selectSize(sizeId, sizeName, extraPrice) {
    selectedSizeId = sizeId;
    selectedColorId = null; // Reset color selection

    // Remove previous size selections
    document.querySelectorAll('.size-card').forEach(card => {
        card.classList.remove('selected');
    });

    // Add selection to clicked card
    document.querySelector(`[data-size-id="${sizeId}"]`).classList.add('selected');

    // Update hidden inputs
    document.getElementById('selected_size_id').value = sizeId;
    document.getElementById('selected_color_id').value = '';

    // Update summary
    document.getElementById('selected-size-summary').textContent = sizeName;

    // Reset color summary
    document.getElementById('selected-color-summary').textContent = 'Please select a color';

    // Calculate unit price (base + size extra, color will be added when selected)
    updateUnitPrice();

    // Show color options
    loadColors(selectedTypeId);
    document.getElementById('color-options').classList.remove('hidden');

    // Hide subsequent options
    document.getElementById('quantity-options').classList.add('hidden');
    document.getElementById('action-buttons').classList.add('hidden');

    updateTotal();
}

function loadColors(typeId) {
    const colorGrid = document.getElementById('color-grid');
    colorGrid.innerHTML = '';

    const colors = window.ProductConfig.colorsData[typeId] || [];

    if (colors.length === 0) {
        colorGrid.innerHTML = '<p class="text-gray-500">No colors available for this type.</p>';
        return;
    }

    colors.forEach(color => {
        const colorCard = document.createElement('div');
        colorCard.className = 'color-card';
        colorCard.setAttribute('data-color-id', color.id);
        colorCard.onclick = () => selectColor(color.id, color.color_name, color.extra_price, color.color_image);

        let colorPreview = '';
        if (color.color_image) {
            colorPreview = `<img src="${color.color_image}" alt="${color.color_name}" class="color-image" />`;
        } else if (color.color_code) {
            colorPreview = `<div class="color-preview" style="background-color: ${color.color_code};"></div>`;
        } else {
            colorPreview = `<div class="color-image placeholder"><i class="fas fa-palette"></i></div>`;
        }

        colorCard.innerHTML = `
${colorPreview}
<div class="color-info">
    <h4>${color.color_name}</h4>
</div>
<div class="selection-indicator">
    <i class="fas fa-check-circle"></i>
</div>
`;

        colorGrid.appendChild(colorCard);
    });
}

function selectColor(colorId, colorName, extraPrice, colorImage) {
    selectedColorId = colorId;

    // Remove previous color selections
    document.querySelectorAll('.color-card').forEach(card => {
        card.classList.remove('selected');
    });

    // Add selection to clicked card
    document.querySelector(`[data-color-id="${colorId}"]`).classList.add('selected');

    // Update hidden input
    document.getElementById('selected_color_id').value = colorId;

    // Update summary
    document.getElementById('selected-color-summary').textContent = colorName;

    // Update unit price
    updateUnitPrice();

    // Update main image
    updateMainImage();

    // Show quantity options and action buttons
    document.getElementById('quantity-options').classList.remove('hidden');
    document.getElementById('action-buttons').classList.remove('hidden');

    updateTotal();
}

function updateUnitPrice() {
    if (!selectedTypeId) return;

    const basePrice = parseFloat(window.ProductConfig.allData[selectedTypeId].price);
    let sizeExtra = 0;
    let colorExtra = 0;

    // Get size extra price
    if (selectedSizeId) {
        const sizes = window.ProductConfig.sizesData[selectedTypeId] || [];
        const selectedSize = sizes.find(size => size.id == selectedSizeId);
        if (selectedSize) {
            sizeExtra = parseFloat(selectedSize.extra_price) || 0;
        }
    }

    // Get color extra price
    if (selectedColorId) {
        const colors = window.ProductConfig.colorsData[selectedTypeId] || [];
        const selectedColor = colors.find(color => color.id == selectedColorId);
        if (selectedColor) {
            colorExtra = parseFloat(selectedColor.extra_price) || 0;
        }
    }

    const unitPrice = basePrice + sizeExtra + colorExtra;
    document.getElementById('unit-price').textContent = `₱${unitPrice.toFixed(2)}`;
}

function changeQuantity(change) {
    const quantityInput = document.getElementById('quantity');
    let newQuantity = parseInt(quantityInput.value) + change;
    if (newQuantity < 1) newQuantity = 1;
    quantityInput.value = newQuantity;
    document.getElementById('quantity-summary').textContent = newQuantity;
    updateTotal();
}

function updateTotal() {
    if (!selectedTypeId || !selectedSizeId || !selectedColorId) {
        document.getElementById('total-price').textContent = '₱0.00';
        return;
    }

    const unitPrice = parseFloat(document.getElementById('unit-price').textContent.replace('₱', '').replace(',', ''));
    const quantity = parseInt(document.getElementById('quantity').value);
    const total = unitPrice * quantity;
    document.getElementById('total-price').textContent = `₱${total.toFixed(2)}`;
}
    </script>
</body>

</html>