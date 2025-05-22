<?php
session_start();
include '../../connection/connection.php';
include '../design/mainbody.php';
include '../checkrole.php';

// Allow only admin1 to admin5
require_role([ 'admin4', 'superadmin']);

if (isset($_SESSION['admin_email'], $_SESSION['admin_role'])) {
    echo '
      <div class="mb-4 p-2 bg-gray-100 rounded text-sm text-gray-700 flex justify-end space-x-4">
        <span>Logged in as:</span>
        <span class="font-medium">' . htmlspecialchars($_SESSION['admin_email']) . '</span>
        <span class="text-gray-500">|</span>
        <span class="font-semibold">' . htmlspecialchars($_SESSION['admin_role']) . '</span>
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
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .recent-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .recent-grid-item {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            overflow: hidden;
        }

        .recent-grid-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .recent-grid-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid #e5e7eb;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <main class="flex-1 p-8">
            <?php if (isset($_GET['added']) && $_GET['added'] == 1) : ?>
                <div id="toastSuccess" class="fixed top-5 left-1/2 transform -translate-x-1/2 bg-green-600 text-white px-6 py-3 rounded shadow-lg z-50 transition-opacity duration-500 ease-in-out">
                    Product added successfully!
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
                <div id="toastDeleted" class="fixed top-5 left-1/2 transform -translate-x-1/2 bg-red-600 text-white px-6 py-3 rounded shadow-lg z-50 transition-opacity duration-500 ease-in-out">
                    Product deleted successfully!
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
                <div id="toastUpdated" class="fixed top-5 left-1/2 transform -translate-x-1/2 bg-yellow-500 text-black px-6 py-3 rounded shadow-lg z-50 transition-opacity duration-500 ease-in-out">
                    Product updated successfully!
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
                <h1 class="text-3xl font-bold mb-6 text-center">Add New Product</h1>
                <a href="products.php?<?= http_build_query(array_intersect_key($_GET, array_flip(['search', 'page', 'filter']))) ?>"
                    class="inline-block mb-6 px-5 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition duration-150 ease-in-out transform active:scale-95">
                    &larr; Back to Product Listing
                </a>

                <div class="flex justify-center items-start min-h-screen">
                    <form method="POST" enctype="multipart/form-data"
                        class="upload-form bg-white p-6 rounded shadow-md w-full max-w-lg mt-10 relative opacity-0 translate-y-5 transition-all duration-700">
                        <label class="block mb-2 font-medium">Product Name</label>
                        <input type="text" name="name" required class="w-full mb-4 p-2 border rounded">

                        <label class="block mb-2 font-medium">Description</label>
                        <textarea name="description" class="w-full mb-4 p-2 border rounded"></textarea>

                        <label class="block mb-2 font-medium">Size</label>
                        <input type="text" name="size" class="w-full mb-4 p-2 border rounded"
                            placeholder="e.g. 12x12 inches">

                        <label class="block mb-2 font-medium">Price</label>
                        <input type="number" name="price" step="0.01" min="0" required
                            class="w-full mb-4 p-2 border rounded">

                        <label class="block mb-2 font-medium">Supplier</label>
                        <input type="text" name="supplier" required class="w-full mb-4 p-2 border rounded">

                        <label class="block mb-2 font-medium">Contact #</label>
                        <input type="tel" name="contact" required class="w-full mb-4 p-2 border rounded">

                        <label class="block mb-2 font-medium">Serial #</label>
                        <input type="text" name="serial" required class="w-full mb-4 p-2 border rounded">

                        <label class="block mb-2 font-medium">Quantity</label>
                        <input type="number" name="quantity" min="0" value="0" class="w-full mb-4 p-2 border rounded">

                        <label class="block mb-2 font-medium">In Stock?</label>
                        <select name="in_stock" class="w-full mb-4 p-2 border rounded">
                            <option value="1" selected>Yes</option>
                            <option value="0">No</option>
                        </select>

                        <label class="block mb-2 font-medium">Image</label>
                        <input type="file" name="image" id="imageInput" class="mb-4" onchange="previewImage(event)">

                        <div id="imagePreview" class="mt-4 hidden">
                            <label class="block mb-2 font-medium">Image Preview</label>
                            <img id="preview" src="" alt="Image Preview"
                                class="w-32 h-32 object-cover border rounded">
                        </div>
                        <div class="absolute bottom-8 right-6">
                            <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-150 ease-in-out transform active:scale-95">
                                Save
                            </button>
                        </div>
                    </form>
                    <script>
                        function previewImage(event) {
                            const file = event.target.files[0];
                            const preview = document.getElementById('preview');
                            const imagePreview = document.getElementById('imagePreview');
                            if (file) {
                                const reader = new FileReader();
                                reader.onload = function() {
                                    preview.src = reader.result;
                                    imagePreview.classList.remove('hidden');
                                }
                                reader.readAsDataURL(file);
                            } else {
                                imagePreview.classList.add('hidden');
                            }
                        }
                        document.addEventListener('DOMContentLoaded', () => {
                            const form = document.querySelector('.upload-form');
                            if (form) {
                                setTimeout(() => {
                                    form.classList.remove('opacity-0', 'translate-y-5');
                                }, 100);
                            }
                        });
                    </script>
                </div>
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
                <h1 class="text-3xl font-bold mb-6 text-center">Edit Product</h1>
                <a href="products.php?<?= http_build_query(array_intersect_key($_GET, array_flip(['search', 'page', 'filter']))) ?>"
                    class="inline-block mb-6 px-5 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition duration-150 ease-in-out transform active:scale-95">
                    &larr; Back to Product Listing
                </a>

                <div class="flex justify-center items-start min-h-screen">
                    <form method="POST" enctype="multipart/form-data"
                        class="upload-form bg-white p-6 rounded shadow-md w-full max-w-lg mt-10 relative opacity-0 translate-y-5 transition-all duration-700">
                        <input type="hidden" name="current_image" value="<?= h($product['image']) ?>">
                        <label class="block mb-2 font-medium">Product Name</label>
                        <input type="text" name="name" value="<?= h($product['name']) ?>" required
                            class="w-full mb-4 p-2 border rounded">

                        <label class="block mb-2 font-medium">Description</label>
                        <textarea name="description" class="w-full mb-4 p-2 border rounded"><?= h($product['description']) ?></textarea>

                        <label class="block mb-2 font-medium">Size</label>
                        <input type="text" name="size" value="<?= h($product['size']) ?>"
                            class="w-full mb-4 p-2 border rounded" placeholder="e.g. 12x12 inches">

                        <label class="block mb-2 font-medium">Price</label>
                        <input type="number" name="price" value="<?= h(number_format($product['price'], 2, '.', '')) ?>"
                            step="0.01" min="0" required class="w-full mb-4 p-2 border rounded">

                        <label class="block mb-2 font-medium">Supplier</label>
                        <input type="text" name="supplier" value="<?= h($product['supplier']) ?>" required
                            class="w-full mb-4 p-2 border rounded">

                        <label class="block mb-2 font-medium">Contact #</label>
                        <input type="tel" name="contact" value="<?= h($product['contact']) ?>" required
                            class="w-full mb-4 p-2 border rounded">

                        <label class="block mb-2 font-medium">Serial #</label>
                        <input type="text" name="serial" value="<?= h($product['serial_number']) ?>" required
                            class="w-full mb-4 p-2 border rounded">

                        <label class="block mb-2 font-medium">Current Image</label>
                        <?php if (!empty($product['image'])) : ?>
                            <img src="../uploads/<?= h($product['image']) ?>" alt="Current Image"
                                class="w-32 h-32 object-cover border rounded mb-2">
                        <?php else : ?>
                            <p class="text-gray-500 mb-2">No image uploaded.</p>
                        <?php endif; ?>

                        <label class="block mb-2 font-medium">Change Image (optional)</label>
                        <input type="file" name="image" id="imageInputEdit" class="mb-4"
                            onchange="previewEditImage(event)">

                        <div id="imagePreviewEditContainer" class="mt-4 hidden">
                            <label class="block mb-2 font-medium">New Image Preview</label>
                            <img id="previewEdit" src="#" alt="New Image Preview"
                                class="w-32 h-32 object-cover border rounded">
                        </div>

                        <div class="mt-auto pt-4">
                            <button type="submit"
                                class="w-full bg-green-600 text-white px-6 py-3 rounded hover:bg-green-700 transition duration-150 ease-in-out transform active:scale-95">
                                Update Product
                            </button>
                        </div>
                    </form>
                    <script>
                        function previewEditImage(event) {
                            const file = event.target.files[0];
                            const preview = document.getElementById('previewEdit');
                            const imagePreviewContainer = document.getElementById('imagePreviewEditContainer');
                            if (file) {
                                const reader = new FileReader();
                                reader.onload = function() {
                                    preview.src = reader.result;
                                    imagePreviewContainer.classList.remove('hidden');
                                }
                                reader.readAsDataURL(file);
                            } else {
                                preview.src = "#";
                                imagePreviewContainer.classList.add('hidden');
                            }
                        }
                        document.addEventListener('DOMContentLoaded', () => {
                            const form = document.querySelector('.upload-form');
                            if (form) {
                                setTimeout(() => {
                                    form.classList.remove('opacity-0', 'translate-y-5');
                                }, 100);
                            }
                            const imageInputEdit = document.getElementById('imageInputEdit');
                            if (imageInputEdit && !imageInputEdit.value) {
                                document.getElementById('imagePreviewEditContainer').classList.add('hidden');
                            }
                        });
                    </script>
                </div>
            <?php
                exit;
            }

            // Product Listing Logic
            $search = $_GET['search'] ?? '';
            $filter = $_GET['filter'] ?? '';
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            if ($page < 1) $page = 1;
            $limit = 6;
            $offset = ($page - 1) * $limit;

            // Build WHERE conditions
            $sqlBase = "FROM productsrealiving";
            $sqlWhere = "";
            $params = [];
            $types = "";

            if (!empty($search)) {
                $sqlWhere = " WHERE (name LIKE ? OR description LIKE ? OR supplier LIKE ?)";
                $searchTerm = "%" . $search . "%";
                $params = array_fill(0, 3, $searchTerm);
                $types = "sss";
            }

            if (!empty($filter)) {
                $sqlWhere = empty($sqlWhere) ? " WHERE " : " AND ";
                $sqlWhere .= "name LIKE ?";
                $params[] = $filter . "%";
                $types .= "s";
            }

            $total_rows_sql = "SELECT COUNT(*) as total " . $sqlBase . $sqlWhere;
            $stmt_total = $conn->prepare($total_rows_sql);
            if (!empty($params)) {
                $stmt_total->bind_param($types, ...$params);
            }
            $stmt_total->execute();
            $total_rows_result = $stmt_total->get_result()->fetch_assoc();
            $total_rows = $total_rows_result['total'];
            $total_pages = ceil($total_rows / $limit);
            if ($page > $total_pages && $total_pages > 0) {
                $page = $total_pages;
                $offset = ($page - 1) * $limit;
            }

            $sql = "SELECT * " . $sqlBase . $sqlWhere;
            $sql .= " ORDER BY name ASC";
            $sql .= " LIMIT ? OFFSET ?";

            $current_page_params = $params;
            $current_page_types = $types;

            $current_page_params[] = $limit;
            $current_page_types .= "i";
            $current_page_params[] = $offset;
            $current_page_types .= "i";

            $stmt = $conn->prepare($sql);

            if (!empty($current_page_params)) {
                $bindable_params = [];
                foreach ($current_page_params as $key => $value) {
                    $bindable_params[$key] = &$current_page_params[$key];
                }
                $stmt->bind_param($current_page_types, ...$bindable_params);
            }

            $stmt->execute();
            $result = $stmt->get_result();

            $filter_params_for_links = [];
            if (!empty($search)) $filter_params_for_links['search'] = $search;
            if (!empty($filter)) $filter_params_for_links['filter'] = $filter;
            ?>

            <div class="flex flex-col gap-4">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                    <!-- Left side: Header + Add Button -->
                    <div class="flex items-center gap-4">
                        <h1 class="text-3xl font-bold">Product / Materials Listing</h1>
                        <a href="products.php?action=upload"
                            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition duration-150 transform active:scale-95 whitespace-nowrap">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Add New Product
                        </a>
                    </div>

                    <!-- Right side: Search form (unchanged) -->
                    <form method="GET" action="products.php" class="flex items-center">
                        <input type="hidden" name="action" value="list">
                        <input type="text" name="search" placeholder="Search Products/Materials"
                            value="<?= h($search) ?>" class="p-2 border rounded w-full md:w-64">
                        <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-150 transform active:scale-95 ml-2">
                            Search
                        </button>
                    </form>
                </div>

                <div class="flex gap-1 items-center mb-4">
                    <span class="font-medium">Filter:</span>
                    <div class="flex flex-wrap gap-2">

                        <?php
                        $fixedButtonSize = 'w-10 h-10 flex items-center justify-center';
                        foreach (range('A', 'Z') as $letter):
                            $is_active = $filter === $letter;

                        ?>
                            <a href="?action=list&filter=<?= $letter ?>&<?= http_build_query(['search' => $search, 'page' => $page]) ?>"
                                class="text-gray-800 w-10 h-10 flex items-center justify-center border rounded <?= $is_active ? 'bg-blue-600 text-white border-blue-600' : 'bg-white hover:bg-gray-200' ?>">
                                <?= $letter ?>
                            </a>
                        <?php endforeach; ?>
                        <a href="?action=list&<?= http_build_query(['search' => $search, 'page' => $page]) ?>"
                            class="text-gray-800 w-10 h-10 flex items-center justify-center border rounded bg-gray-200 hover:bg-gray-300">
                            All
                        </a>
                    </div>
                </div>
            </div>

            <?php if ($result->num_rows > 0) : ?>
                <div class="flex flex-col gap-4">
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <div class="product-tile-list bg-white rounded-xl shadow-md overflow-hidden flex flex-col md:flex-row h-full cursor-pointer opacity-0 translate-y-5 transition-all duration-700 
                                    hover:shadow-lg hover:bg-gray-50"
                            onclick="handleCardClick(event, <?= $row['id'] ?>, 
                                    '../uploads/<?= h($row['image'] ?? 'default.png') ?>', 
                                    '<?= h($row['name']) ?>', 
                                    '<?= h($row['description']) ?>', 
                                    '<?= h($row['size']) ?>',
                                    '<?= $row['price'] ?>', 
                                    '<?= h($row['supplier']) ?>', 
                                    '<?= h($row['contact']) ?>', 
                                    '<?= h($row['serial_number']) ?>')">

                            <div class="flex-shrink-0 w-full md:w-48 h-48 md:h-auto">
                                <img class="w-full h-full object-cover"
                                    src="../uploads/<?= h($row['image'] ? $row['image'] : 'default.png') ?>"
                                    alt="<?= h($row['name']) ?>">
                            </div>
                            <div class="flex flex-col flex-grow p-4">
                                <div class="flex flex-col md:flex-row justify-between gap-2">
                                    <div class="flex-1">
                                        <h2 class="text-xl font-semibold mb-2"><?= h($row['name']) ?></h2>
                                        <p class="text-gray-600 mb-2 text-sm">
                                            <?= h($row['description'] ? nl2br(mb_strimwidth($row['description'], 0, 150, "...")) : 'No description available.') ?>
                                        </p>
                                        <div class="text-sm text-gray-500 mb-1">Size:
                                            <?= h($row['size'] ? $row['size'] : 'N/A') ?></div>
                                        <div class="text-green-600 font-bold text-lg mb-2">
                                            ₱<?= number_format($row['price'], 2) ?></div>
                                    </div>
                                    <div class="md:text-right mt-2 md:mt-0 flex-shrink-0">
                                        <div class="text-sm text-gray-500">Supplier: <?= h($row['supplier']) ?></div>
                                        <div class="text-sm text-gray-500">Contact: <?= h($row['contact']) ?></div>
                                        <div class="text-sm text-gray-500">Serial #: <?= h($row['serial_number']) ?></div>
                                        <div class="text-xs text-gray-400 mt-1">
                                            Added: <?= date("M j, Y", strtotime($row['created_at'])) ?></div>
                                    </div>
                                </div>
                                <div class="mt-auto flex gap-2 text-sm pt-3 border-t md:border-none">
                                    <a href="products.php?action=edit&id=<?= $row['id'] ?>&<?= http_build_query($filter_params_for_links + ['page' => $page]) ?>"
                                        class="px-3 py-1.5 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-150 ease-in-out transform active:scale-95 text-xs">
                                        Edit
                                    </a>
                                    <a href="products.php?action=delete&id=<?= $row['id'] ?>&<?= http_build_query($filter_params_for_links + ['page' => $page]) ?>"
                                        class="px-3 py-1.5 bg-red-500 text-white rounded hover:bg-red-600 transition duration-150 ease-in-out transform active:scale-95 text-xs"
                                        onclick="return confirm(<?= json_encode('Delete product: ' . $row['name'] . '?') ?>)">
                                        Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else : ?>
                <p class="text-gray-500 col-span-full text-center py-10">No products found matching your criteria.</p>
            <?php endif; ?>

            <?php
            // Fetch recently added products (last 5)
            $recent_sql = "SELECT * FROM productsrealiving ORDER BY created_at DESC LIMIT 5";
            $recent_result = $conn->query($recent_sql);
            ?>

            <h2 class="text-2xl font-semibold mt-12 mb-6">Recently Added Items</h2>
            <div class="recent-grid">
                <?php if ($recent_result->num_rows > 0) : ?>
                    <?php while ($recent_row = $recent_result->fetch_assoc()) : ?>
                        <div class="recent-grid-item cursor-pointer"
                            onclick="handleCardClick(event,
                            <?= $recent_row['id'] ?>,
                            '../uploads/<?= h($recent_row['image'] ? $recent_row['image'] : 'default.png') ?>',
                            '<?= h($recent_row['name']) ?>',
                            '<?= h($recent_row['description'] ?? '') ?>',
                            '<?= h($recent_row['size'] ?? '') ?>',
                            '<?= number_format($recent_row['price'], 2) ?>',
                            '<?= h($recent_row['supplier'] ?? '') ?>',
                            '<?= h($recent_row['contact'] ?? '') ?>',
                            '<?= h($recent_row['serial_number'] ?? '') ?>'">
                            <div class="flex flex-col h-full">
                                <img src="../uploads/<?= h($recent_row['image'] ? $recent_row['image'] : 'default.png') ?>"
                                    alt="<?= h($recent_row['name']) ?>">
                                <div class="p-4 flex flex-col flex-grow">
                                    <h3 class="text-lg font-semibold mb-2"><?= h($recent_row['name']) ?></h3>
                                    <div class="text-sm text-gray-600 mb-3">
                                        <?= h(mb_strimwidth($recent_row['description'] ?? '', 0, 100, '...')) ?>
                                    </div>
                                    <div class="mt-auto space-y-1">
                                        <div class="flex justify-between text-sm">
                                            <span class="font-medium">Price:</span>
                                            <span class="text-green-600">₱<?= number_format($recent_row['price'], 2) ?></span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="font-medium">Added:</span>
                                            <span><?= date("M j, Y", strtotime($recent_row['created_at'])) ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else : ?>
                    <p class="text-gray-500 col-span-full">No recently added products.</p>
                <?php endif; ?>
            </div>

            <div class="mt-8 flex justify-center gap-2">
                <?php if ($total_pages > 1) : ?>
                    <?php if ($page > 1) : ?>
                        <a href="?page=<?= ($page - 1) ?>&<?= http_build_query($filter_params_for_links) ?>"
                            class="px-3 py-1 rounded border bg-white text-gray-700 hover:bg-gray-100">&laquo; Prev</a>
                    <?php endif; ?>

                    <?php
                    $num_links = 2;
                    $start = max(1, $page - $num_links);
                    $end = min($total_pages, $page + $num_links);

                    if ($start > 1) {
                        echo '<a href="?page=1&' . http_build_query($filter_params_for_links) . '" class="px-3 py-1 rounded border bg-white text-gray-700 hover:bg-gray-100">1</a>';
                        if ($start > 2) {
                            echo '<span class="px-3 py-1">...</span>';
                        }
                    }

                    for ($i = $start; $i <= $end; $i++) : ?>
                        <a href="?page=<?= $i ?>&<?= http_build_query($filter_params_for_links) ?>"
                            class="px-3 py-1 rounded border <?= $i == $page ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 hover:bg-gray-100' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor;

                    if ($end < $total_pages) {
                        if ($end < $total_pages - 1) {
                            echo '<span class="px-3 py-1">...</span>';
                        }
                        echo '<a href="?page=' . $total_pages . '&' . http_build_query($filter_params_for_links) . '" class="px-3 py-1 rounded border bg-white text-gray-700 hover:bg-gray-100">' . $total_pages . '</a>';
                    }
                    ?>

                    <?php if ($page < $total_pages) : ?>
                        <a href="?page=<?= ($page + 1) ?>&<?= http_build_query($filter_params_for_links) ?>"
                            class="px-3 py-1 rounded border bg-white text-gray-700 hover:bg-gray-100">Next &raquo;</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <?php include '../components/product_modal.php'; ?>
            <script src="../assets/js/product_modal.js"></script>
            <script>
                function handleCardClick(e, id, image, name, description, size, price, supplier, contact, serial_number) {
                    if (e.target.closest('a')) {
                        return;
                    }
                    openModal(id, image, name, description, size, price, supplier, contact, serial_number);
                }

                document.addEventListener('DOMContentLoaded', () => {
                    const tiles = document.querySelectorAll('.product-tile-list'); // Assuming this is for the main list
                    tiles.forEach((tile, index) => {
                        setTimeout(() => {
                            tile.classList.remove('opacity-0', 'translate-y-5');
                        }, 50 * index);
                    });

                    const form = document.querySelector('.upload-form');
                    if (form) {
                        setTimeout(() => {
                            form.classList.remove('opacity-0', 'translate-y-5');
                        }, 100);
                    }
                });
            </script>
        </main>
    </div>
</body>

</html>