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

// Try to get product sizes (gracefully handle if table doesn't exist)
$type_sizes = [];
$sizes_query_success = false;

if (!empty($product_types)) {
    try {
        $sizes_sql = "SELECT ps.*, pt.type_id 
                      FROM product_sizes ps 
                      INNER JOIN product_types pt ON ps.type_id = pt.type_id 
                      WHERE pt.product_id = $id 
                      ORDER BY ps.size_name ASC";
        $sizes_result = $conn->query($sizes_sql);

        if ($sizes_result) {
            $sizes_query_success = true;
            while ($row = $sizes_result->fetch_assoc()) {
                $type_sizes[$row['type_id']][] = [
                    'size_id' => $row['id'],
                    'size_name' => $row['size_name'],
                    'dimensions' => $row['dimensions'],
                    'price' => $row['price']
                ];
            }
        }
    } catch (Exception $e) {
        // Table doesn't exist or there's an error, continue without sizes
        $sizes_query_success = false;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Customize <?= htmlspecialchars($product['product_name']) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&family=Montserrat&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="design/get_products.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css ">
</head>

<script>
    module.exports = {
        theme: {
            extend: {
                fontFamily: {
                    montserrat: ['Montserrat', 'serif'],
                    crimson: ['Crimson Pro', 'serif'],
                },
            },
        },
    }
</script>


<body>
    <!-- Header -->
   <header class="sticky top-0 text-black bg-gray-100 shadow-md relative z-50 bg-cover bg-center font-[Montserrat]">

    <nav class="flex justify-between items-end py-4 px-6 md:px-5">
        <!-- Logo -->
        <div class="flex items-center space-x-3">
            <a href="#">
                <img src="../../realivingpage/img/logo.png" alt="Realiving Logo" class="h-10">
            </a>
        </div>


        <!-- Desktop Navigation + Get Quote Button -->
        <div class="hidden md:flex items-center space-x-5 text-orange-900 font-[montserrat]">
            <!-- Navigation Links -->
            <div class="flex space-x-5">
                <a href="index" class="flex items-center hover:text-yellow-500">
                    HOME
                </a>
                <a href="product_cabinet" class="flex items-center hover:text-yellow-500">
                    CABINET
                </a>
                <a href="#" class="flex items-center hover:text-yellow-500">
                    DIY MODULAR
                </a>
                <a href="all-projects" class="flex items-center hover:text-yellow-500">
                    PROJECTS
                </a>
                <a href="#" class="flex items-center hover:text-yellow-500">
                    WHAT'S NEW
                </a>
                <a href="contact" class="flex items-center hover:text-yellow-500">
                    CONTACT
                </a>
                <a href="header/billingpage/billing" class="flex items-center hover:text-yellow-500">
                    BILLING
                </a>
                <a href="about" class="flex items-center hover:text-yellow-500">
                    ABOUT
                </a>
            </div>

            <!-- Get Quote Button -->
            <button class="ml-6 bg-yellow-400 text-black px-6 py-2 font-medium flex items-center">
                <i class="fas fa-file-invoice-dollar mr-2"></i>GET QUOTE
            </button>
        </div>

        <!-- Mobile Menu Button -->
        <div class="md:hidden">
            <button id="mobile-menu-button" class="text-black">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-white border-t p-4 text-orange-900 font-[montserrat]">
        <div class="flex flex-col space-y-4">
            <a href="index" class="flex items-center hover:text-yellow-500">
                <i class="fas fa-home mr-2 w-6"></i>HOME
            </a>
            <a href="#" class="flex items-center hover:text-yellow-500">
                SERVICES
            </a>
            <a href="all-project" class="flex items-center hover:text-yellow-500">
                PROJECTS
            </a>
            <a href="#" class="flex items-center hover:text-yellow-500">
                WHAT'S NEW
            </a>
            <a href="contact" class="flex items-center hover:text-yellow-500">
                CONTACT
            </a>
            <a href="#" class="flex items-center hover:text-yellow-500">
                <i class="fas fa-info-circle mr-2 w-6"></i>ABOUT
            </a>
            <button class="bg-yellow-400 text-black px-6 py-2 font-medium w-full flex items-center justify-center">
                <i class="fas fa-file-invoice-dollar mr-2"></i>GET QUOTE
            </button>
        </div>
    </div>
</header>



<script>
    // Mobile menu toggle functionality
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenu.classList.toggle('hidden');
    });
</script>

    <div class="header">
        <div class="header-container">
            <div class="header-content">
                 
                <a href="../product_cabinet.php" class="back-link">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                     
                    <h1 class="font-[montserrat]">CREATE YOUR STYLE</h1>
                    <p><?= htmlspecialchars($product['product_name']) ?></p>
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
                        <img id="main-image"
                            src="../../<?= htmlspecialchars($product['main_image']) ?>"
                            alt="<?= htmlspecialchars($product['product_name']) ?>"
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
                            Added on <?= date('F d, Y', strtotime($product['created_at'])) ?>
                        </div>
                    </div>
                </div>

                <!-- Selected Summary -->
                <div class="card">
                    <h3><i class="fas fa-check-circle"></i>Your Selection</h3>
                    <div id="selection-summary" class="summary-content">
                        <p><strong>Type:</strong> <span id="selected-type-summary">Please select a type</span></p>
                        <?php if ($sizes_query_success): ?>
                            <p><strong>Size:</strong> <span id="selected-size-summary">Please select a size</span></p>
                        <?php endif; ?>
                        <?php if ($colors_query_success): ?>
                            <p><strong>Color:</strong> <span id="selected-color-summary">Please select a color</span></p>
                        <?php endif; ?>
                        <p><strong>Unit Price:</strong> <span id="selected-price" class="price-display">₱0.00</span></p>
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

                    <?php if (empty($product_types)): ?>
                        <div class="no-variants">
                            <i class="fas fa-exclamation-triangle"></i>
                            <h3>No Variants Available</h3>
                            <p>This product doesn't have any customization options yet.</p>
                        </div>
                    <?php else: ?>
                        <form method="POST" action="process_customization.php" id="customization-form">
                            <!-- ... [same hidden inputs as before] ... -->

                            <!-- Step 1: Product Types -->
                            <div class="step-section">
                                <h3><span>1</span>Choose Product Type</h3>
                                <div class="type-grid">
                                    <?php foreach ($product_types as $type): ?>
                                        <div class="type-card"
                                            onclick="selectType(<?= $type['type_id'] ?>, '<?= htmlspecialchars($type['type_name'], ENT_QUOTES) ?>', '<?= addslashes($type['type_image']) ?>', <?= $type['price'] ?>)"
                                            data-type-id="<?= $type['type_id'] ?>">
                                            <?php if (!empty($type['type_image'])): ?>
                                                <div class="type-image">
                                                    <img src="../../<?= htmlspecialchars($type['type_image']) ?>"
                                                        alt="<?= htmlspecialchars($type['type_name']) ?>" />
                                                </div>
                                            <?php endif; ?>
                                            <div class="type-info">
                                                <h4><?= htmlspecialchars($type['type_name']) ?></h4>
                                                <p class="type-price">₱<?= number_format($type['price'], 2) ?></p>
                                                <?php if (!empty($type['description'])): ?>
                                                    <p class="type-description"><?= htmlspecialchars($type['description']) ?></p>
                                                <?php endif; ?>
                                            </div>
                                            <div class="selection-indicator">
                                                <i class="fas fa-check-circle"></i>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <?php if ($sizes_query_success): ?>
                                <!-- Step 2: Sizes -->
                                <div id="size-selection" class="step-section hidden">
                                    <h3><span>2</span>Choose Size</h3>
                                    <div id="sizes-container" class="size-grid"></div>
                                </div>
                            <?php endif; ?>

                            <?php if ($colors_query_success): ?>
                                <!-- Step 3: Colors -->
                                <div id="color-selection" class="step-section hidden">
                                    <h3><span><?= $sizes_query_success ? '3' : '2' ?></span>Choose Color</h3>
                                    <div id="colors-container" class="color-grid"></div>
                                </div>
                            <?php endif; ?>

                            <!-- Final Step: Quantity and Options -->
                            <div id="quantity-options" class="step-section <?= ($sizes_query_success || $colors_query_success) ? 'hidden' : '' ?>">
                                <h3><span><?= $sizes_query_success ? ($colors_query_success ? '4' : '3') : '2' ?></span>Quantity & Options</h3>
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
                            <div id="action-buttons" class="<?= ($sizes_query_success || $colors_query_success) ? 'hidden' : '' ?>">
                                <button type="submit" name="action" value="add_to_cart">
                                    SUBMIT
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <section class="why-us-alternating">
        <div class="container">
            <h1>WHY CHOOSE US?</h1>
            <p class="intro">At Realiving Design Center, we redefine modular cabinetry with innovative designs, premium craftsmanship, and unmatched customization.</p>

            <!-- Benefit 1: Text LEFT + Image RIGHT -->
            <div class="benefit-section left">
                <div class="benefit-text">
                    <h2>Tailored for You</h2>
                    <p>Our modular cabinets are fully customizable in size, finish, and functionality to match your exact needs.</p>
                </div>
                <div class="benefit-image">
                    <img src="images/custom-cabinets.jpg" alt="Custom cabinets">
                </div>
            </div>

            <!-- Benefit 2: Text RIGHT + Image LEFT -->
            <div class="benefit-section right">
                <div class="benefit-image">
                    <img src="images/space-saving.jpg" alt="Space-saving design">
                </div>
                <div class="benefit-text">
                    <h2>Smart & Space-Saving</h2>
                    <p>Engineered for modern living, our designs maximize storage while enhancing aesthetics.</p>
                </div>
            </div>

            <!-- Benefit 3: Text LEFT + Image RIGHT -->
            <div class="benefit-section left">
                <div class="benefit-text">
                    <h2>Premium Quality</h2>
                    <p>Built with durable, eco-friendly materials that ensure longevity without compromising on style.</p>
                </div>
                <div class="benefit-image">
                    <img src="images/eco-friendly.jpg" alt="Eco-friendly materials">
                </div>
            </div>

            <!-- Benefit 4: Text RIGHT + Image LEFT -->
            <div class="benefit-section right">
                <div class="benefit-image">
                    <img src="images/easy-install.jpg" alt="Easy installation">
                </div>
                <div class="benefit-text">
                    <h2>Hassle-Free Experience</h2>
                    <p>From seamless installation to easy reconfiguration, we make upgrading your space effortless.</p>
                </div>
            </div>
        </div>
        <!-- Benefit 5: Text LEFT + Image RIGHT -->
        <div class="benefit-section left">
            <div class="benefit-text">
                <h2>Affordable Luxury</h2>
                <p>High-end modular solutions at competitive prices, offering exceptional value.</p>
            </div>
            <div class="benefit-image">
                <img src="../images/Installation.png" alt="Eco-friendly materials">
            </div>
        </div>
        <p class="closing">
            Transform your space with <strong>Realiving Design Center</strong>—where <em>modular meets magnificent!</em>
        </p>
    </section>

    <script>
        window.ProductConfig = {
            product: <?= json_encode([
                            'id' => $product['product_id'],
                            'name' => $product['product_name'],
                            'main_image' => $product['main_image']
                        ]) ?>,
            typeColors: <?= json_encode($type_colors) ?>,
            typeSizes: <?= json_encode($type_sizes) ?>,
            hasColors: <?= $colors_query_success ? 'true' : 'false' ?>,
            hasSizes: <?= $sizes_query_success ? 'true' : 'false' ?>
        };
    </script>
    <script src="js/getcabinetproducts/getproduct.js"></script>
</body>

</html>