<?php
session_start();
include '../../connection/connection.php';
include '../design/mainbody.php';
include '../checkrole.php';

// Allow only admin1 to admin5
require_role(['admin4', 'superadmin']);

if (isset($_SESSION['admin_email'], $_SESSION['admin_role'])) {
    echo '
      <div class="mb-4 p-3 bg-gradient-to-r from-blue-50 to-gray-50 rounded-lg shadow-sm text-sm text-gray-700 flex justify-end items-center space-x-4">
        <span class="text-gray-500">Logged in as:</span>
        <span class="font-medium text-blue-700">' . htmlspecialchars($_SESSION['admin_email']) . '</span>
        <span class="text-gray-300">|</span>
        <span class="font-semibold px-2 py-1 bg-blue-100 text-blue-800 rounded-md">' . htmlspecialchars($_SESSION['admin_role']) . '</span>
      </div>
    ';
}

function h($str)
{
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

function haddslashes($str)
{
    return addslashes(htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8'));
}

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;
$search = $_GET['search'] ?? '';
$filter = $_GET['filter'] ?? '';

$products = []; // Initialize an empty array for products
$noProductsMessage = ''; // Initialize an empty message

// Construct current query string for redirects and links
$queryStringParams = [];
if (isset($_GET['search'])) $queryStringParams['search'] = $_GET['search'];
if (isset($_GET['filter'])) $queryStringParams['filter'] = $_GET['filter'];
if (isset($_GET['page'])) $queryStringParams['page'] = $_GET['page'];
$currentQueryString = !empty($queryStringParams) ? '&' . http_build_query($queryStringParams) : '';

// Delete Product
if ($action === 'delete' && $id) {
    $stmt = $conn->prepare("SELECT image FROM productsrealiving WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $product = $res->fetch_assoc();

    if ($product && !empty($product['image'])) {
        $imagePath = "../uploads/" . $product['image'];
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    $stmt = $conn->prepare("DELETE FROM productsrealiving WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: products.php?deleted=1" . $currentQueryString);
    exit;
}

// Fetch Products for Listing
if ($action === 'list') {
    $whereClauses = [];
    $params = [];

    if (!empty($search)) {
        $whereClauses[] = 'name LIKE ?';
        $params[] = '%' . $search . '%';
    }

    if (!empty($filter)) {
        $whereClauses[] = 'LEFT(name, 1) = ?';
        $params[] = strtoupper($filter);
    }

    $sql = "SELECT * FROM productsrealiving";
    if (!empty($whereClauses)) {
        $sql .= " WHERE " . implode(' AND ', $whereClauses);
    }

    $stmt = $conn->prepare($sql);

    if (!empty($params)) {
        $types = str_repeat('s', count($params)); // Assuming all are strings for LIKE and SUBSTR
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $products = $result->fetch_all(MYSQLI_ASSOC);

    // Set "No products found" message
    if (empty($products) && (!empty($search) || !empty($filter))) {
        $noProductsMessage = '<p>No products found matching your criteria.</p>';
        if (!empty($filter)) {
            $noProductsMessage = '<p>No products found starting with the letter ' . htmlspecialchars($filter) . '.</p>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Product Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    boxShadow: {
                        card: '0 0 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02)',
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
        }

        .recent-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .recent-grid-item {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 10px 15px -3px rgba(0, 0, 0, 0.01);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .recent-grid-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .recent-grid-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid #e5e7eb;
        }

        .product-card {
            transition: all 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* Custom animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.5s ease forwards;
        }

        /* Shimmer effect for loading states */
        .shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }

        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }

            100% {
                background-position: 200% 0;
            }
        }

        /* Button Styles */
        .btn-primary {
            @apply bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300 transform active:scale-95;
        }

        .btn-secondary {
            @apply bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg transition duration-300 transform active:scale-95;
        }

        .btn-success {
            @apply bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300 transform active:scale-95;
        }

        .btn-danger {
            @apply bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300 transform active:scale-95;
        }

        /* Input styles */
        .form-input {
            @apply w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-primary-300 focus:border-primary-500 outline-none transition duration-200;
        }

        /* Toast animations */
        @keyframes slideInDown {
            from {
                transform: translate(-50%, -20px);
                opacity: 0;
            }

            to {
                transform: translate(-50%, 0);
                opacity: 1;
            }
        }

        .toast {
            animation: slideInDown 0.3s ease forwards;
        }

        /* Card hover effect */
        .hover-lift {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <main class="flex-1 p-6">
            <?php if (isset($_GET['added']) && $_GET['added'] == 1) : ?>
                <div id="toastSuccess" class="fixed top-5 left-1/2 transform -translate-x-1/2 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-opacity duration-500 ease-in-out toast flex items-center">
                    <i class="fas fa-check-circle mr-2"></i> Product added successfully!
                </div>
                <script>
                    setTimeout(() => {
                        const toast = document.getElementById('toastSuccess');
                        if (toast) {
                            toast.style.opacity = '0';
                            setTimeout(() => toast.remove(), 500);
                        }
                    }, 3000);
                </script>
            <?php endif; ?>

            <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1) : ?>
                <div id="toastDeleted" class="fixed top-5 left-1/2 transform -translate-x-1/2 bg-red-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-opacity duration-500 ease-in-out toast flex items-center">
                    <i class="fas fa-trash-alt mr-2"></i> Product deleted successfully!
                </div>
                <script>
                    setTimeout(() => {
                        const toast = document.getElementById('toastDeleted');
                        if (toast) {
                            toast.style.opacity = '0';
                            setTimeout(() => toast.remove(), 500);
                        }
                    }, 3000);
                </script>
            <?php endif; ?>
            <?php if (isset($_GET['updated']) && $_GET['updated'] == 1) : ?>
                <div id="toastUpdated" class="fixed top-5 left-1/2 transform -translate-x-1/2 bg-yellow-500 text-black px-6 py-3 rounded-lg shadow-lg z-50 transition-opacity duration-500 ease-in-out toast flex items-center">
                    <i class="fas fa-edit mr-2"></i> Product updated successfully!
                </div>
                <script>
                    setTimeout(() => {
                        const toast = document.getElementById('toastUpdated');
                        if (toast) {
                            toast.style.opacity = '0';
                            setTimeout(() => toast.remove(), 500);
                        }
                    }, 3000);
                </script>
            <?php endif; ?>

            <?php
            // Upload Product
            if ($action === 'upload') {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Set default values for quantity and in_stock
                    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;
                    $in_stock = isset($_POST['in_stock']) ? intval($_POST['in_stock']) : 1;

                    $name = $_POST['name'];
                    $desc = $_POST['description'];
                    $size = $_POST['size'];
                    $priceInput = $_POST['price'];
                    if (!is_numeric($priceInput) || $priceInput < 0) {
                        die("Invalid price value. Price must be a non-negative number.");
                    }
                    $price = floatval($priceInput);

                    $supplier = $_POST['supplier'];
                    $contact = $_POST['contact'];
                    $serial = $_POST['serial'];
                    $imagePath = '';

                    if (!empty($_FILES['image']['name'])) {
                        $image = uniqid() . '_' . preg_replace("/[^a-zA-Z0-9\.\-_]/", "", basename($_FILES['image']['name']));
                        $targetDir = "../uploads/";
                        if (!is_dir($targetDir)) {
                            mkdir($targetDir, 0755, true);
                        }
                        $target = $targetDir . $image;
                        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                            $imagePath = $image;
                        }
                    }

                    // Modified to exclude ID from INSERT
                    $stmt = $conn->prepare("INSERT INTO productsrealiving 
                        (name, description, size, price, supplier, contact, serial_number, image, quantity, in_stock, created_at) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

                    $stmt->bind_param("sssdssssii", $name, $desc, $size, $price, $supplier, $contact, $serial, $imagePath, $quantity, $in_stock);

                    if ($stmt->execute()) {
                        header("Location: products.php?added=1" . $currentQueryString);
                        exit;
                    } else {
                        error_log("SQL Error: " . $stmt->error);
                        die("Error adding product. Please check database logs.");
                    }
                }
            ?>
                <!-- Add Product Form Section -->
                <div class="max-w-5xl mx-auto">
                    <div class="flex items-center justify-between mb-8">
                        <h1 class="text-3xl font-bold text-gray-800">Add New Product</h1>
                        <a href="products.php?<?= http_build_query(array_intersect_key($_GET, array_flip(['search', 'page', 'filter']))) ?>"
                            class="flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition duration-300 transform active:scale-95">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Products
                        </a>
                    </div>

                    <div class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-500 opacity-0 translate-y-5" id="uploadForm">
                        <div class="p-6">
                            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                                <div class="grid md:grid-cols-2 gap-6">
                                    <!-- Left Column -->
                                    <div class="space-y-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Product Name*</label>
                                            <input type="text" name="name" required
                                                class="form-input" placeholder="Enter product name">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                            <textarea name="description" rows="4"
                                                class="form-input" placeholder="Product description"></textarea>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Size</label>
                                            <input type="text" name="size"
                                                class="form-input" placeholder="e.g. 12x12 inches">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Price (₱)*</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500">₱</span>
                                                </div>
                                                <input type="number" name="price" step="0.01" min="0" required
                                                    class="form-input pl-8" placeholder="0.00">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right Column -->
                                    <div class="space-y-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Supplier*</label>
                                            <input type="text" name="supplier" required
                                                class="form-input" placeholder="Supplier name">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Contact #*</label>
                                            <input type="tel" name="contact" required
                                                class="form-input" placeholder="Contact number">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Serial #*</label>
                                            <input type="text" name="serial" required
                                                class="form-input" placeholder="Serial number">
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                                                <input type="number" name="quantity" min="0" value="0"
                                                    class="form-input" placeholder="0">
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">In Stock?</label>
                                                <select name="in_stock" class="form-input">
                                                    <option value="1" selected>Yes</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Image Upload Section -->
                                <div class="mt-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Product Image</label>
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                                        <div class="space-y-1 text-center">
                                            <div id="imagePreviewContainer" class="hidden mb-3">
                                                <img id="preview" src="" alt="Image Preview" class="mx-auto h-32 w-32 object-cover rounded">
                                            </div>
                                            <div class="flex text-sm text-gray-600">
                                                <label for="imageInput" class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none">
                                                    <span>Upload a file</span>
                                                    <input id="imageInput" name="image" type="file" class="sr-only" onchange="previewImage(event)">
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="flex justify-end mt-6 pt-6 border-t border-gray-100">
                                    <button type="submit" class="btn-success flex items-center px-6">
                                        <i class="fas fa-save mr-2"></i> Save Product
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <script>
                    function previewImage(event) {
                        const file = event.target.files[0];
                        const preview = document.getElementById('preview');
                        const imagePreviewContainer = document.getElementById('imagePreviewContainer');
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function() {
                                preview.src = reader.result;
                                imagePreviewContainer.classList.remove('hidden');
                            }
                            reader.readAsDataURL(file);
                        } else {
                            imagePreviewContainer.classList.add('hidden');
                        }
                    }

                    document.addEventListener('DOMContentLoaded', () => {
                        const form = document.getElementById('uploadForm');
                        if (form) {
                            setTimeout(() => {
                                form.classList.remove('opacity-0', 'translate-y-5');
                            }, 100);
                        }
                    });
                </script>
            <?php
                exit;
            }

            // Edit Product
            if ($action === 'edit' && $id) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $name = $_POST['name'];
                    $desc = $_POST['description'];
                    $size = $_POST['size'];
                    $priceInput = $_POST['price'];
                    if (!is_numeric($priceInput) || $priceInput < 0) {
                        die("Invalid price value. Price must be a non-negative number.");
                    }
                    $price = floatval($priceInput);
                    $supplier = $_POST['supplier'];
                    $contact = $_POST['contact'];
                    $serial = $_POST['serial'];
                    $new_image_file = $_FILES['image']['name'];
                    $current_image_path = $_POST['current_image'] ?? '';

                    $imagePathToSave = $current_image_path;

                    if (!empty($new_image_file)) {
                        $new_image_file_sanitized = preg_replace("/[^a-zA-Z0-9\.\-\_]/", "", basename($new_image_file));
                        $targetDir = "../uploads/";
                        if (!is_dir($targetDir)) {
                            mkdir($targetDir, 0755, true);
                        }
                        $target = $targetDir . $new_image_file_sanitized;

                        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                            if ($current_image_path && $current_image_path !== $new_image_file_sanitized && file_exists($targetDir . $current_image_path)) {
                                unlink($targetDir . $current_image_path);
                            }
                            $imagePathToSave = $new_image_file_sanitized;
                        } else {
                            error_log("Failed to move uploaded file for edit: " . $_FILES['image']['error']);
                        }
                    }

                    $stmt = $conn->prepare("UPDATE productsrealiving SET 
                        name=?, description=?, size=?, price=?, supplier=?, contact=?, serial_number=?, image=? 
                        WHERE id=?");
                    $stmt->bind_param("sssdssssi", $name, $desc, $size, $price, $supplier, $contact, $serial, $imagePathToSave, $id);

                    if ($stmt->execute()) {
                        header("Location: products.php?updated=1" . $currentQueryString);
                        exit;
                    } else {
                        error_log("SQL Error updating product: " . $stmt->error);
                        die("Error updating product.");
                    }
                }

                $stmt = $conn->prepare("SELECT * FROM productsrealiving WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $product = $result->fetch_assoc();

                if (!$product) {
                    echo "<p>Product not found.</p>";
                    exit;
                }
            ?>
                <!-- Edit Product Form Section -->
                <div class="max-w-5xl mx-auto">
                    <div class="flex items-center justify-between mb-8">
                        <h1 class="text-3xl font-bold text-gray-800">Edit Product</h1>
                        <a href="products.php?<?= http_build_query(array_intersect_key($_GET, array_flip(['search', 'page', 'filter']))) ?>"
                            class="flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition duration-300 transform active:scale-95">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Products
                        </a>
                    </div>

                    <div class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-500 opacity-0 translate-y-5" id="editForm">
                        <div class="p-6">
                            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                                <input type="hidden" name="current_image" value="<?= h($product['image']) ?>">

                                <div class="grid md:grid-cols-2 gap-6">
                                    <!-- Left Column -->
                                    <div class="space-y-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Product Name*</label>
                                            <input type="text" name="name" value="<?= h($product['name']) ?>" required
                                                class="form-input" placeholder="Enter product name">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                            <textarea name="description" rows="4"
                                                class="form-input" placeholder="Product description"><?= h($product['description']) ?></textarea>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Size</label>
                                            <input type="text" name="size" value="<?= h($product['size']) ?>"
                                                class="form-input" placeholder="e.g. 12x12 inches">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Price (₱)*</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500">₱</span>
                                                </div>
                                                <input type="number" name="price" value="<?= h(number_format($product['price'], 2, '.', '')) ?>"
                                                    step="0.01" min="0" required class="form-input pl-8" placeholder="0.00">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right Column -->
                                    <div class="space-y-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Supplier*</label>
                                            <input type="text" name="supplier" value="<?= h($product['supplier']) ?>" required
                                                class="form-input" placeholder="Supplier name">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Contact #*</label>
                                            <input type="tel" name="contact" value="<?= h($product['contact']) ?>" required
                                                class="form-input" placeholder="Contact number">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Serial #*</label>
                                            <input type="text" name="serial" value="<?= h($product['serial_number']) ?>" required
                                                class="form-input" placeholder="Serial number">
                                        </div>
                                    </div>
                                </div>

                                <!-- Image Section -->
                                <div class="mt-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                                    <div class="flex items-center mb-4">

                                        <div class="flex items-center mb-4">
                                            <?php if (!empty($product['image'])) : ?>
                                                <div class="w-24 h-24 mr-4 rounded-lg overflow-hidden border border-gray-200">
                                                    <img src="../uploads/<?= h($product['image']) ?>" alt="<?= h($product['name']) ?>" class="w-full h-full object-cover">
                                                </div>
                                            <?php else : ?>
                                                <div class="w-24 h-24 mr-4 rounded-lg overflow-hidden border border-gray-200 bg-gray-100 flex items-center justify-center">
                                                    <i class="fas fa-image text-gray-400 text-2xl"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <p class="text-sm text-gray-500"><?= !empty($product['image']) ? h($product['image']) : 'No image uploaded' ?></p>
                                            </div>
                                        </div>

                                        <label class="block text-sm font-medium text-gray-700 mb-1">Update Image</label>
                                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                                            <div class="space-y-1 text-center">
                                                <div id="imagePreviewContainer" class="hidden mb-3">
                                                    <img id="preview" src="" alt="Image Preview" class="mx-auto h-32 w-32 object-cover rounded">
                                                </div>
                                                <div class="flex text-sm text-gray-600">
                                                    <label for="imageInput" class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none">
                                                        <span>Upload a file</span>
                                                        <input id="imageInput" name="image" type="file" class="sr-only" onchange="previewImage(event)">
                                                    </label>
                                                    <p class="pl-1">or drag and drop</p>
                                                </div>
                                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="flex justify-end mt-6 pt-6 border-t border-gray-100">
                                        <button type="submit" class="btn-success flex items-center px-6">
                                            <i class="fas fa-save mr-2"></i> Update Product
                                        </button>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>

                <script>
                    function previewImage(event) {
                        const file = event.target.files[0];
                        const preview = document.getElementById('preview');
                        const imagePreviewContainer = document.getElementById('imagePreviewContainer');
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function() {
                                preview.src = reader.result;
                                imagePreviewContainer.classList.remove('hidden');
                            }
                            reader.readAsDataURL(file);
                        } else {
                            imagePreviewContainer.classList.add('hidden');
                        }
                    }

                    document.addEventListener('DOMContentLoaded', () => {
                        const form = document.getElementById('editForm');
                        if (form) {
                            setTimeout(() => {
                                form.classList.remove('opacity-0', 'translate-y-5');
                            }, 100);
                        }
                    });
                </script>
            <?php
                exit;
            }
            ?>

            <!-- Product Listing Section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-4 md:mb-0">Product Management</h1>
                <div class="flex flex-col sm:flex-row gap-4">
                    <form action="products.php" method="GET" class="flex flex-wrap gap-2">
                        <div class="relative">
                            <input type="text" name="search" value="<?= h($search) ?>" placeholder="Search products..."
                                class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-300 focus:border-primary-500 w-full">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                        <button type="submit" class="btn-primary flex items-center px-5">
                            <i class="fas fa-filter mr-2"></i> Filter
                        </button>
                        <?php if (!empty($search) || !empty($filter)) : ?>
                            <a href="products.php" class="btn-secondary flex items-center">
                                <i class="fas fa-times mr-2"></i> Clear
                            </a>
                        <?php endif; ?>
                    </form>
                    <a href="products.php?action=upload" class="btn-success flex items-center justify-center">
                        <i class="fas fa-plus mr-2"></i> Add Product
                    </a>
                </div>
            </div>

            <!-- Alphabet Filter -->
            <div class="mb-8">
                <div class="flex flex-wrap gap-1 justify-center bg-white p-4 rounded-lg shadow-sm">
                    <a href="products.php<?= !empty($search) ? '?search=' . urlencode($search) : '' ?>"
                        class="px-3 py-1 <?= empty($filter) ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?> rounded-md transition">
                        All
                    </a>
                    <?php foreach (range('A', 'Z') as $letter) : ?>
                        <a href="products.php?filter=<?= $letter ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"
                            class="px-3 py-1 <?= $filter === $letter ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?> rounded-md transition">
                            <?= $letter ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Error Message for No Products -->
            <?php if (!empty($noProductsMessage)) : ?>
                <div class="bg-gray-100 p-8 rounded-xl text-center text-gray-600">
                    <?= $noProductsMessage ?>
                    <div class="mt-4">
                        <a href="products.php" class="text-primary-600 hover:text-primary-700 font-medium">View all products</a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Products Grid -->
            <?php if (!empty($products)) : ?>
                <div class="recent-grid mb-8">
                    <?php foreach ($products as $product) : ?>
                        <div class="recent-grid-item product-card animate-fadeIn">
                            <div class="relative h-48 overflow-hidden">
                                <?php if (!empty($product['image'])) : ?>
                                    <img src="../uploads/<?= h($product['image']) ?>" alt="<?= h($product['name']) ?>" class="h-full w-full object-cover">
                                <?php else : ?>
                                    <div class="h-full w-full flex items-center justify-center bg-gray-100">
                                        <i class="fas fa-image text-gray-400 text-4xl"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="absolute top-2 right-2 flex gap-1">
                                    <?php if (isset($product['in_stock']) && $product['in_stock'] == 1) : ?>
                                        <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full flex items-center">
                                            <i class="fas fa-check mr-1"></i> In Stock
                                        </span>
                                    <?php else : ?>
                                        <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full flex items-center">
                                            <i class="fas fa-times mr-1"></i> Out of Stock
                                        </span>
                                    <?php endif; ?>
                                    <?php if (isset($product['quantity'])) : ?>
                                        <span class="bg-blue-500 text-white text-xs px-2 py-1 rounded-full flex items-center">
                                            <i class="fas fa-cubes mr-1"></i> <?= h($product['quantity']) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="p-4">
                                <h4 class="font-semibold text-lg text-gray-900 mb-1 truncate"><?= h($product['name']) ?></h4>
                                <div class="text-primary-600 font-bold mb-2">₱<?= number_format($product['price'], 2) ?></div>
                                <?php if (!empty($product['size'])) : ?>
                                    <div class="flex items-center text-sm text-gray-600 mb-1">
                                        <i class="fas fa-ruler-combined w-5"></i>
                                        <span><?= h($product['size']) ?></span>
                                    </div>
                                <?php endif; ?>
                                <div class="flex items-center text-sm text-gray-600 mb-1">
                                    <i class="fas fa-user w-5"></i>
                                    <span class="truncate"><?= h($product['supplier']) ?></span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600 mb-1">
                                    <i class="fas fa-barcode w-5"></i>
                                    <span class="truncate"><?= h($product['serial_number']) ?></span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600 mb-3">
                                    <i class="fas fa-clock w-5"></i>
                                    <span><?= date('M d, Y', strtotime($product['created_at'])) ?></span>
                                </div>

                                <div class="flex mt-3 justify-between">
                                    <a href="products.php?action=edit&id=<?= $product['id'] ?><?= $currentQueryString ?>"
                                        class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition flex items-center">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </a>
                                    <button onclick="confirmDelete(<?= $product['id'] ?>, '<?= haddslashes($product['name']) ?>')"
                                        class="px-3 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition flex items-center">
                                        <i class="fas fa-trash-alt mr-1"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php elseif (empty($noProductsMessage)) : ?>
                <div class="bg-white p-8 rounded-xl text-center">
                    <div class="text-5xl text-gray-300 mb-4">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <h3 class="text-xl font-medium text-gray-700 mb-2">No Products Yet</h3>
                    <p class="text-gray-500 mb-6">Add your first product to get started.</p>
                    <a href="products.php?action=upload" class="btn-primary inline-flex items-center">
                        <i class="fas fa-plus mr-2"></i> Add New Product
                    </a>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg max-w-md w-full p-6 transform transition-transform scale-95 opacity-0" id="modalContent">
            <div class="text-center mb-4">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Confirm Deletion</h3>
                <p class="text-gray-500" id="deleteProductName"></p>
            </div>
            <div class="flex gap-3 mt-6">
                <button id="cancelDelete" class="flex-1 py-2 px-4 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg transition duration-300">
                    Cancel
                </button>
                <a id="confirmDeleteBtn" href="#" class="flex-1 py-2 px-4 bg-red-600 hover:bg-red-700 text-white rounded-lg text-center transition duration-300">
                    Delete
                </a>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('deleteModal');
        const modalContent = document.getElementById('modalContent');
        const cancelBtn = document.getElementById('cancelDelete');

        function confirmDelete(id, name) {
            document.getElementById('deleteProductName').textContent = `Are you sure you want to delete "${name}"? This action cannot be undone.`;
            document.getElementById('confirmDeleteBtn').href = `products.php?action=delete&id=${id}<?= $currentQueryString ?>`;

            // Show modal with animation
            modal.classList.remove('hidden');
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        // Cancel button closes modal
        cancelBtn.addEventListener('click', closeModal);

        // Clicking outside the modal closes it
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });

        function closeModal() {
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Fade in animation for product cards
        document.addEventListener('DOMContentLoaded', () => {
            const productCards = document.querySelectorAll('.animate-fadeIn');
            productCards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 50);
            });
        });
    </script>
</body>

</html>