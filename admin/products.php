<?php
include '../connection/connection.php';

function h($str)
{
    return htmlspecialchars($str ?? '');
}

include 'design/mainbody.php';

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

// Delete Product
if ($action === 'delete' && $id) {
    $stmt = $conn->prepare("SELECT image FROM productsrealiving WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $product = $res->fetch_assoc();

    if ($product && !empty($product['image'])) {
        $imagePath = "uploads/" . $product['image'];
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }
    $stmt = $conn->prepare("DELETE FROM productsrealiving WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: products.php?deleted=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Product Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

    <div class="flex min-h-screen">


        <!-- Main content area -->
        <main class="flex-1 p-8">
            <?php if (isset($_GET['added']) && $_GET['added'] == 1): ?>
                <div id="toastSuccess" class="fixed top-5 left-1/2 transform -translate-x-1/2 bg-green-600 text-white px-6 py-3 rounded shadow-lg z-50 transition-opacity duration-500 ease-in-out">
                    ✅ Product added successfully!
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

            <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
                <div id="toastDeleted" class="fixed top-5 left-1/2 transform -translate-x-1/2 bg-red-600 text-white px-6 py-3 rounded shadow-lg z-50 transition-opacity duration-500 ease-in-out">
                    🗑️ Product deleted successfully!
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
            <?php if (isset($_GET['updated']) && $_GET['updated'] == 1): ?>
                <div id="toastUpdated" class="fixed top-5 left-1/2 transform -translate-x-1/2 bg-yellow-600 text-white px-6 py-3 rounded shadow-lg z-50 transition-opacity duration-500 ease-in-out">
                    🔄 Product updated successfully!
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
                    $name = $_POST['name'];
                    $desc = $_POST['description'];
                    $size = $_POST['size'];
                    $image = $_FILES['image']['name'];
                    $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    if (!is_numeric($price) || $price <= 0) {
                        die("Invalid price value");
                    }
                    $supplier = $_POST['supplier'];
                    $contact = $_POST['contact'];
                    $serial = $_POST['serial'];
                    $image = $_FILES['image']['name'];

                    $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    if (!is_numeric($price) || $price <= 0) {
                        die("Invalid price value");
                    }
                    $imagePath = '';
                    if (!empty($image)) {
                        $target = "uploads/" . basename($image);
                        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                            $imagePath = $image;
                        }
                    }

                    $stmt = $conn->prepare("INSERT INTO productsrealiving 
                    (name, description, size, price, supplier, contact, serial_number, image) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

                    $stmt->bind_param("sssdssss", $name, $desc, $size, $price, $supplier, $contact, $serial, $imagePath);
                    $stmt->execute();
                    header("Location: products.php?added=1");
                    exit;
                }
            ?>
                <h1 class="text-3xl font-bold mb-6 text-center">Add New Product</h1>
                <!-- Back Button -->
                <a href="products.php"
                    class="inline-block mb-6 px-5 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition transition duration-150 ease-in-out transform active:scale-95">
                    Back to Product Listings
                </a>

                <div class="flex justify-center items-start min-h-screen">
                    <form method="POST" enctype="multipart/form-data" class="upload-form bg-white p-6 rounded shadow-md w-full max-w-lg mt-10 relative opacity-0 translate-y-5 transition-all duration-700">
                        <label class="block mb-2 font-medium">Product Name</label>
                        <input type="text" name="name" required class="w-full mb-4 p-2 border rounded">

                        <label class="block mb-2 font-medium">Description</label>
                        <textarea name="description" class="w-full mb-4 p-2 border rounded"></textarea>

                        <label class="block mb-2 font-medium">Size</label>
                        <input type="text" name="size" class="w-full mb-4 p-2 border rounded" placeholder="e.g. 12x12 inches">

                        <label class="block mb-2 font-medium">Price</label>
                        <input type="number" name="price" step="0.01" required class="w-full mb-4 p-2 border rounded">

                        <label class="block mb-2 font-medium">Supplier</label>
                        <input type="text" name="supplier" required class="w-full mb-4 p-2 border rounded">

                        <label class="block mb-2 font-medium">Contact #</label>
                        <input type="tel" name="contact" required class="w-full mb-4 p-2 border rounded">

                        <label class="block mb-2 font-medium">Serial #</label>
                        <input type="text" name="serial" required class="w-full mb-4 p-2 border rounded">

                        <label class="block mb-2 font-medium">Image</label>
                        <input type="file" name="image" id="imageInput" class="mb-4" onchange="previewImage(event)">

                        <!-- Image Preview Section -->
                        <div id="imagePreview" class="mt-4 hidden">
                            <label class="block mb-2 font-medium">Image Preview</label>
                            <img id="preview" src="" alt="Image Preview" class="w-32 h-32 object-cover border rounded">
                        </div>

                        <div class="absolute bottom-8 right-6">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-150 ease-in-out transform active:scale-95">
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
                    $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    if (!is_numeric($price) || $price <= 0) {
                        die("Invalid price value");
                    }
                    $supplier = $_POST['supplier'];
                    $contact = $_POST['contact'];
                    $serial = $_POST['serial'];

                    $image = $_FILES['image']['name'];
                    $imagePath = '';

                    if (!empty($image)) {
                        $target = "uploads/" . basename($image);
                        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                            $imagePath = $image;
                        }
                    }

                    if (!empty($imagePath)) {
                        $stmt = $conn->prepare("UPDATE products SET 
                            name=?, description=?, size=?, price=?, supplier=?, contact=?, serial_number=?, image=? 
                            WHERE id=?");
                        $stmt->bind_param("sssdssssi", $name, $desc, $size, $price, $supplier, $contact, $serial, $imagePath, $id);
                    } else {
                        $stmt = $conn->prepare("UPDATE products SET 
                            name=?, description=?, size=?, price=?, supplier=?, contact=?, serial_number=? 
                            WHERE id=?");
                        $stmt->bind_param("sssdsssi", $name, $desc, $size, $price, $supplier, $contact, $serial, $id);
                    }

                    $stmt->execute();
                    header("Location: products.php");
                    exit;
                }

                // Fetch product to prepopulate form
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
                <!-- Back Button -->
                <a href="products.php"
                    class="inline-block mb-6 px-5 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition transition duration-150 ease-in-out transform active:scale-95">
                    &larr; Back to Product Listings
                </a>

                <div class="flex justify-center items-start min-h-screen">
                    <form method="POST" enctype="multipart/form-data" class="upload-form bg-white p-6 rounded shadow-md w-full max-w-lg mt-10 relative opacity-0 translate-y-5 transition-all duration-700">
                        <label class="block mb-2 font-medium">Product Name</label>
                        <input type="text" name="name" value="<?= h($product['name']) ?>" required class="w-full mb-4 p-2 border rounded">

                        <label class="block mb-2 font-medium">Description</label>
                        <textarea name="description" class="w-full mb-4 p-2 border rounded"><?= h($product['description']) ?></textarea>

                        <label class="block mb-2 font-medium">Size</label>
                        <input type="text" name="size" value="<?= h($product['size']) ?>" class="w-full mb-4 p-2 border rounded" placeholder="e.g. 12x12 inches">

                        <label class="block mb-2 font-medium">Price</label>
                        <input type="number" name="price" value="<?= h($product['price']) ?>" step="0.01" required class="w-full mb-4 p-2 border rounded">

                        <label class="block mb-2 font-medium">Supplier</label>
                        <input type="text" name="supplier" value="<?= h($product['supplier']) ?>" required class="w-full mb-4 p-2 border rounded">

                        <label class="block mb-2 font-medium">Contact #</label>
                        <input type="tel" name="contact" value="<?= h($product['contact']) ?>" required class="w-full mb-4 p-2 border rounded">

                        <label class="block mb-2 font-medium">Serial #</label>
                        <input type="text" name="serial" value="<?= h($product['serial_number']) ?>" required class="w-full mb-4 p-2 border rounded">

                        <label class="block mb-2 font-medium">Current Image</label>
                        <?php if (!empty($product['image'])): ?>
                            <img src="uploads/<?= h($product['image']) ?>" alt="Current Image" class="w-32 mb-2">
                        <?php else: ?>
                            <p>No image uploaded.</p>
                        <?php endif; ?>

                        <label class="block mb-2 font-medium">Change Image (optional)</label>
                        <input type="file" name="image" class="mb-4">

                        <div class="mt-auto">
                            <button type="submit" class="w-full bg-green-600 text-white px-6 py-3 rounded hover:bg-green-700 transition-colors transition duration-150 ease-in-out transform active:scale-95">
                                Update Product
                            </button>
                        </div>
                    </form>

                    <script>
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

            $search = $_GET['search'] ?? '';
            $page = $_GET['page'] ?? 1;
            $limit = 6;
            $offset = ($page - 1) * $limit;

            if (!empty($search)) {
                $stmt = $conn->prepare("
    SELECT SQL_CALC_FOUND_ROWS * 
    FROM productsrealiving 
    WHERE MATCH(name, description, size, supplier, serial_number) AGAINST (? IN NATURAL LANGUAGE MODE)
    ORDER BY created_at DESC 
    LIMIT ? OFFSET ?
");
                $stmt->bind_param("sii", $search, $limit, $offset);
            } else {
                $stmt = $conn->prepare("
        SELECT SQL_CALC_FOUND_ROWS * 
        FROM productsrealiving 
        ORDER BY created_at DESC 
        LIMIT ? OFFSET ?
    ");
                $stmt->bind_param("ii", $limit, $offset);
            }
            $stmt->execute();
            $result = $stmt->get_result();

            $total_rows = $conn->query("SELECT FOUND_ROWS()")->fetch_row()[0];
            $total_pages = ceil($total_rows / $limit);
            ?>

            <!-- Top Bar (Search & Heading) -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                <h1 class="text-3xl font-bold mb-4 md:mb-0">Product / Materials Listings</h1>
                <form method="GET" class="flex gap-2">
                    <input type="hidden" name="action" value="list">
                    <input type="text" name="search" placeholder="Search..." value="<?= h($_GET['search'] ?? '') ?>"
                        class="p-2 border rounded w-64">
                    <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-150 ease-in-out transform active:scale-95">Search</button>
                </form>
            </div>

            <!-- Add Product Button -->
            <a href="products.php?action=upload"
                class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mb-6 transition duration-150 ease-in-out transform active:scale-95 ">+ Add Product</a>

            <!-- Product Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="product-tile bg-white rounded-xl shadow-md overflow-hidden flex flex-col h-full cursor-pointer opacity-0 translate-y-5 transition-all duration-700 
            hover:shadow-lg hover:brightness-95 hover:-translate-y-3 transition duration-150 ease-in-out transform active:scale-95"
                            onclick="handleCardClick(event, <?= $row['id'] ?>, 
                                'uploads/<?= h($row['image'] ?? 'default.png') ?>', 
                                '<?= h($row['name']) ?>', 
                                '<?= h($row['description']) ?>', 
                                '<?= h($row['size']) ?>',
                                '<?= $row['price'] ?>', 
                                '<?= h($row['supplier']) ?>', 
                                '<?= h($row['contact']) ?>', 
                                '<?= h($row['serial_number']) ?>')">
                            <img class="w-full h-48 object-cover transform transition-transform duration-300 hover:scale-105"
                                src="uploads/<?= h($row['image'] ?? 'default.png') ?>" alt="<?= h($row['name']) ?>">
                            <div class="flex flex-col flex-grow p-4">
                                <h2 class="text-xl font-semibold"><?= h($row['name']) ?></h2>
                                <p class="text-gray-600 flex-grow"><?= h($row['description']) ?></p>
                                <div class="text-sm text-gray-500 mb-4">Size: <?= h($row['size']) ?></div>
                                <div class="text-sm text-green-600 font-bold">Price: ₱<?= h($row['price']) ?></div>
                                <div class="text-sm text-gray-500">Supplier: <?= h($row['supplier']) ?></div>
                                <div class="text-sm text-gray-500">Contact: <?= h($row['contact']) ?></div>
                                <div class="text-sm text-gray-500 mb-4">Serial #: <?= h($row['serial_number']) ?></div>

                                <div class="mt-auto flex justify-between gap-2 text-sm">
                                    <a href="products.php?action=edit&id=<?= $row['id'] ?>"
                                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition transition duration-150 ease-in-out transform active:scale-95">
                                        Edit
                                    </a>
                                    <a href="products.php?action=delete&id=<?= $row['id'] ?>"
                                        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition transition duration-150 ease-in-out transform active:scale-95"
                                        onclick="return confirm('Are you sure you want to delete this product?')">
                                        Delete
                                    </a>
                                </div>

                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-gray-500">No products found.</p>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <div class="mt-8 flex justify-center gap-2">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"
                        class="px-3 py-1 rounded border <?= $i == $page ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>

            <?php include 'components/product_modal.php'; ?>
            <script src="assets/js/product_modal.js"></script>
            <script>
                function handleCardClick(e, id, image, name, description, size, price, supplier, contact, serial_number) {
                    // Check if the click was on an action button (Edit or Delete)
                    if (e.target.closest('a')) {
                        return;
                    }

                    openModal(id, image, name, description, size, price, supplier, contact, serial_number);
                }
            </script>

        </main>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tiles = document.querySelectorAll('.product-tile');
            tiles.forEach((tile, index) => {
                setTimeout(() => {
                    tile.classList.remove('opacity-0', 'translate-y-5');
                }, 100 * index); // Staggered animation (100ms apart)
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tiles = document.querySelectorAll('.product-tile');
            tiles.forEach((tile, index) => {
                setTimeout(() => {
                    tile.classList.remove('opacity-0', 'translate-y-5');
                }, 100 * index);
            });

            const form = document.querySelector('.upload-form');
            if (form) {
                setTimeout(() => {
                    form.classList.remove('opacity-0', 'translate-y-5');
                }, 100);
            }
        });
    </script>


</body>

</html>