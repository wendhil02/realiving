<?php
include '../../connection/connection.php';
session_start();

$success_msg = "";
$error_msg = "";


// Check for session messages
if (isset($_SESSION['success_msg'])) {
    $success_msg = $_SESSION['success_msg'];
    unset($_SESSION['success_msg']);
}

if (isset($_SESSION['error_msg'])) {
    $error_msg = $_SESSION['error_msg'];
    unset($_SESSION['error_msg']);
}

include '../checkrole.php';


// Get product ID
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id <= 0) {
    $_SESSION['error_msg'] = "Invalid product ID.";
    header("Location: admin_process.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $conn->real_escape_string($_POST['product_name']);
    $description = $conn->real_escape_string($_POST['description']);
    $status = $conn->real_escape_string($_POST['status']);
    $updated_at = date('Y-m-d H:i:s');

    // Handle main product image update
    $upload_dir = "../../uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $main_image_update = "";
    if (!empty($_FILES['main_image']['name'])) {
        // Get old image to delete - FIXED: using product_id instead of id
        $old_image_query = "SELECT main_image FROM products WHERE product_id = ?";
        $stmt = $conn->prepare($old_image_query);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $old_image_result = $stmt->get_result();
        $old_image_row = $old_image_result->fetch_assoc();

        $main_image_file = time() . "_main_" . basename($_FILES['main_image']['name']);
        $main_image_path = $upload_dir . $main_image_file;

        if (move_uploaded_file($_FILES['main_image']['tmp_name'], $main_image_path)) {
            $main_image_rel = str_replace('../../', '', $main_image_path);
            $main_image_update = ", main_image = '$main_image_rel'";

            // Delete old image
            if (!empty($old_image_row['main_image']) && file_exists("../../" . $old_image_row['main_image'])) {
                unlink("../../" . $old_image_row['main_image']);
            }
        }
    }

    if (empty($product_name)) {
        $_SESSION['error_msg'] = "Product name is required.";
    } else {
        $conn->begin_transaction();

        try {
            // Update main product - FIXED: using product_id instead of id
            $updateProduct = "UPDATE products SET 
                              product_name = '$product_name', 
                              description = '$description', 
                              status = '$status', 
                              updated_at = '$updated_at' 
                              $main_image_update 
                              WHERE product_id = $product_id";

            if ($conn->query($updateProduct)) {

                // Handle existing types updates and new types
                if (isset($_POST['type_names']) && is_array($_POST['type_names'])) {
                    $type_names = $_POST['type_names'];
                    $type_prices = $_POST['type_prices'];
                    $type_descriptions = $_POST['type_descriptions'];
                    $existing_type_ids = isset($_POST['existing_type_ids']) ? $_POST['existing_type_ids'] : [];

                    for ($t = 0; $t < count($type_names); $t++) {
                        if (!empty($type_names[$t])) {
                            $type_name = $conn->real_escape_string($type_names[$t]);
                            $type_price = floatval($type_prices[$t]);
                            $type_desc = $conn->real_escape_string($type_descriptions[$t]);
                            $existing_type_id = isset($existing_type_ids[$t]) ? intval($existing_type_ids[$t]) : 0;

                            // Handle type image
                            $type_image_update = "";
                            if (!empty($_FILES['type_images']['name'][$t])) {
                                $type_image_file = time() . "_type_" . $t . "_" . basename($_FILES['type_images']['name'][$t]);
                                $type_image_path = $upload_dir . $type_image_file;
                                if (move_uploaded_file($_FILES['type_images']['tmp_name'][$t], $type_image_path)) {
                                    $type_image_rel = str_replace('../../', '', $type_image_path);
                                    $type_image_update = ", type_image = '$type_image_rel'";
                                }
                            }

                            if ($existing_type_id > 0) {
                                // Update existing type - FIXED: using type_id instead of id
                                $updateType = "UPDATE product_types SET 
                                               type_name = '$type_name', 
                                               price = $type_price, 
                                               description = '$type_desc',
                                               updated_at = '$updated_at'
                                               $type_image_update
                                               WHERE type_id = $existing_type_id";
                                $conn->query($updateType);
                                $type_id = $existing_type_id;
                            } else {
                                // Insert new type
                                $insertType = "INSERT INTO product_types 
                                               (product_id, type_name, type_image, price, description, created_at) 
                                               VALUES ($product_id, '$type_name', '" . str_replace(", type_image = '", "", str_replace("'", "", $type_image_update)) . "', $type_price, '$type_desc', '$updated_at')";
                                $conn->query($insertType);
                                $type_id = $conn->insert_id;
                            }

                            // Handle sizes for this type
                            if (isset($_POST['sizes'][$t]) && is_array($_POST['sizes'][$t])) {
                                // First, delete existing sizes for this type
                                $conn->query("DELETE FROM product_sizes WHERE type_id = $type_id");

                                $type_sizes = $_POST['sizes'][$t];
                                foreach ($type_sizes as $size_data) {
                                    if (!empty($size_data['name'])) {
                                        $size_name = $conn->real_escape_string($size_data['name']);
                                        $size_dimensions = $conn->real_escape_string($size_data['dimensions']);
                                        $size_price = floatval($size_data['price']);

                                        $insertSize = "INSERT INTO product_sizes 
                                                       (type_id, size_name, dimensions, price, created_at) 
                                                       VALUES ($type_id, '$size_name', '$size_dimensions', $size_price, '$updated_at')";
                                        $conn->query($insertSize);
                                    }
                                }
                            }

                            // Handle colors for this type
                            if (isset($_POST['colors'][$t]) && is_array($_POST['colors'][$t])) {
                                // First, delete existing colors for this type
                                $conn->query("DELETE FROM product_colors WHERE type_id = $type_id");

                                $type_colors = $_POST['colors'][$t];
                                foreach ($type_colors as $color_index => $color_data) {
                                    if (!empty($color_data['name'])) {
                                        $color_name = $conn->real_escape_string($color_data['name']);
                                        $color_code = $conn->real_escape_string($color_data['code']);
                                        $color_price = floatval($color_data['price']);

                                        $color_image_rel = "";
                                        if (!empty($_FILES['color_images']['name'][$t][$color_index])) {
                                            $color_image_file = time() . "_color_" . $t . "_" . $color_index . "_" . basename($_FILES['color_images']['name'][$t][$color_index]);
                                            $color_image_path = $upload_dir . $color_image_file;
                                            if (move_uploaded_file($_FILES['color_images']['tmp_name'][$t][$color_index], $color_image_path)) {
                                                $color_image_rel = str_replace('../../', '', $color_image_path);
                                            }
                                        }

                                        $insertColor = "INSERT INTO product_colors 
                                                        (type_id, color_name, color_code, color_image, price, created_at) 
                                                        VALUES ($type_id, '$color_name', '$color_code', '$color_image_rel', $color_price, '$updated_at')";
                                        $conn->query($insertColor);
                                    }
                                }
                            }
                        }
                    }
                }

                $conn->commit();
                $_SESSION['success_msg'] = "Product updated successfully!";
                header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $product_id);
                exit();
            } else {
                throw new Exception("Failed to update product: " . $conn->error);
            }
        } catch (Exception $e) {
            $conn->rollback();
            $_SESSION['error_msg'] = "Error updating product: " . $e->getMessage();
        }
    }

    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $product_id);
    exit();
}

// Fetch product data - FIXED: using product_id instead of id
$product_query = "SELECT * FROM products WHERE product_id = ?";
$stmt = $conn->prepare($product_query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product_result = $stmt->get_result();

if ($product_result->num_rows === 0) {
    $_SESSION['error_msg'] = "Product not found.";
    header("Location: view_products.php");
    exit();
}

$product = $product_result->fetch_assoc();

// Fetch product types with sizes and colors - FIXED: using type_id instead of id in GROUP BY
$types_query = "
    SELECT 
        pt.*,
        GROUP_CONCAT(DISTINCT CONCAT(ps.id, ':', ps.size_name, ':', ps.dimensions, ':', ps.price) SEPARATOR '||') as sizes_data,
        GROUP_CONCAT(DISTINCT CONCAT(pc.id, ':', pc.color_name, ':', pc.color_code, ':', pc.color_image, ':', pc.price) SEPARATOR '||') as colors_data
    FROM product_types pt
    LEFT JOIN product_sizes ps ON pt.type_id = ps.type_id
    LEFT JOIN product_colors pc ON pt.type_id = pc.type_id
    WHERE pt.product_id = ?
    GROUP BY pt.type_id
    ORDER BY pt.type_id";

$stmt = $conn->prepare($types_query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$types_result = $stmt->get_result();

$product_types = [];
while ($type_row = $types_result->fetch_assoc()) {
    // Parse sizes data
    $sizes = [];
    if (!empty($type_row['sizes_data'])) {
        $sizes_array = explode('||', $type_row['sizes_data']);
        foreach ($sizes_array as $size_string) {
            if (!empty($size_string)) {
                $size_parts = explode(':', $size_string, 4);
                if (count($size_parts) >= 4) {
                    $sizes[] = [
                        'id' => $size_parts[0],
                        'name' => $size_parts[1],
                        'dimensions' => $size_parts[2],
                        'price' => $size_parts[3]
                    ];
                }
            }
        }
    }

    // Parse colors data
    $colors = [];
    if (!empty($type_row['colors_data'])) {
        $colors_array = explode('||', $type_row['colors_data']);
        foreach ($colors_array as $color_string) {
            if (!empty($color_string)) {
                $color_parts = explode(':', $color_string, 5);
                if (count($color_parts) >= 5) {
                    $colors[] = [
                        'id' => $color_parts[0],
                        'name' => $color_parts[1],
                        'code' => $color_parts[2],
                        'image' => $color_parts[3],
                        'price' => $color_parts[4]
                    ];
                }
            }
        }
    }

    $product_types[] = [
        'id' => $type_row['type_id'], // FIXED: using type_id instead of id
        'type_name' => $type_row['type_name'],
        'type_image' => $type_row['type_image'],
        'price' => $type_row['price'],
        'description' => $type_row['description'],
        'sizes' => $sizes,
        'colors' => $colors
    ];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="32x32" href="../../logo/favicon.ico">
    <title>Edit Product</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .type-section {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            background-color: #f8f9fa;
        }

        .size-item,
        .color-item {
            border: 1px solid #ced4da;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 10px;
            background-color: white;
        }

        .btn-remove {
            background-color: #dc3545;
            border: none;
            color: white;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
        }

        .color-preview {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: 2px solid #ccc;
            display: inline-block;
            margin-left: 10px;
        }
    </style>
</head>

<body>
    <div class="flex items-center space-x-3 ml-5">
        <img src="../../logo/picart.png" alt="Logo" class="h-20 w-40 object-contain">
        <div class="text-black">
            <h1 class="text-lg font-semibold leading-tight">Product Management</h1>
            <p class="text-sm text-black">Dashboard Edit</p>
        </div>
    </div>



    <div class="container-fluid mt-3">

        <?php if (!empty($success_msg)): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo htmlspecialchars($success_msg); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($error_msg)): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?php echo htmlspecialchars($error_msg); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-edit"></i> Edit Product: <?php echo htmlspecialchars($product['product_name']); ?></h4>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data" id="productForm">
                    <!-- Main Product Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Product Name *</label>
                            <input type="text" name="product_name" class="form-control"
                                value="<?php echo htmlspecialchars($product['product_name']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control">
                                <option value="Active" <?php echo $product['status'] == 'Active' ? 'selected' : ''; ?>>Active</option>
                                <option value="Inactive" <?php echo $product['status'] == 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($product['description']); ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Main Product Image</label>
                            <input type="file" name="main_image" class="form-control" accept="image/*">
                            <?php if (!empty($product['main_image'])): ?>
                                <div class="mt-2">
                                    <img src="../../<?php echo htmlspecialchars($product['main_image']); ?>"
                                        alt="Current Image" style="max-width: 150px; max-height: 150px;">
                                    <p class="text-muted">Current Image</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Product Types Section -->
                    <div class="mb-4">
                        <h5><i class="fas fa-tags"></i> Product Types</h5>
                        <div id="typesContainer">
                            <?php foreach ($product_types as $index => $type): ?>
                                <div class="type-section" data-type-index="<?php echo $index; ?>">
                                    <input type="hidden" name="existing_type_ids[<?php echo $index; ?>]" value="<?php echo $type['id']; ?>">

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Type Name</label>
                                            <input type="text" name="type_names[<?php echo $index; ?>]" class="form-control"
                                                value="<?php echo htmlspecialchars($type['type_name']); ?>">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Base Price</label>
                                            <input type="number" step="0.01" name="type_prices[<?php echo $index; ?>]" class="form-control"
                                                value="<?php echo $type['price']; ?>">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Type Image</label>
                                            <input type="file" name="type_images[<?php echo $index; ?>]" class="form-control" accept="image/*">
                                            <?php if (!empty($type['type_image'])): ?>
                                                <img src="../../<?php echo htmlspecialchars($type['type_image']); ?>"
                                                    alt="Type Image" style="max-width: 80px; max-height: 80px; margin-top: 5px;">
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <label class="form-label">Type Description</label>
                                            <textarea name="type_descriptions[<?php echo $index; ?>]" class="form-control" rows="2"><?php echo htmlspecialchars($type['description']); ?></textarea>
                                        </div>
                                    </div>

                                    <!-- Sizes for this type -->
                                    <div class="mb-3">
                                        <h6><i class="fas fa-ruler"></i> Sizes</h6>
                                        <div class="sizes-container" data-type-index="<?php echo $index; ?>">
                                            <?php foreach ($type['sizes'] as $size_index => $size): ?>
                                                <div class="size-item">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <input type="text" name="sizes[<?php echo $index; ?>][<?php echo $size_index; ?>][name]"
                                                                class="form-control" placeholder="Size"
                                                                value="<?php echo htmlspecialchars($size['name']); ?>">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <input type="text" name="sizes[<?php echo $index; ?>][<?php echo $size_index; ?>][dimensions]"
                                                                class="form-control" placeholder="Dimensions"
                                                                value="<?php echo htmlspecialchars($size['dimensions']); ?>">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="number" step="0.01" name="sizes[<?php echo $index; ?>][<?php echo $size_index; ?>][price]"
                                                                class="form-control" placeholder="Additional Price"
                                                                value="<?php echo $size['price']; ?>">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="button" class="btn btn-remove remove-size">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <button type="button" class="btn btn-secondary btn-sm add-size" data-type-index="<?php echo $index; ?>">
                                            <i class="fas fa-plus"></i> Add Size
                                        </button>
                                    </div>

                                    <!-- Colors for this type -->
                                    <div class="mb-3">
                                        <h6><i class="fas fa-palette"></i> Colors</h6>
                                        <div class="colors-container" data-type-index="<?php echo $index; ?>">
                                            <?php foreach ($type['colors'] as $color_index => $color): ?>
                                                <div class="color-item">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <input type="text" name="colors[<?php echo $index; ?>][<?php echo $color_index; ?>][name]"
                                                                class="form-control" placeholder="Color Name"
                                                                value="<?php echo htmlspecialchars($color['name']); ?>">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input type="color" name="colors[<?php echo $index; ?>][<?php echo $color_index; ?>][code]"
                                                                class="form-control" value="<?php echo htmlspecialchars($color['code']); ?>">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="file" name="color_images[<?php echo $index; ?>][<?php echo $color_index; ?>]"
                                                                class="form-control" accept="image/*">
                                                            <?php if (!empty($color['image'])): ?>
                                                                <img src="../../<?php echo htmlspecialchars($color['image']); ?>"
                                                                    alt="Color Image" style="max-width: 50px; max-height: 50px; margin-top: 3px;">
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input type="number" step="0.01" name="colors[<?php echo $index; ?>][<?php echo $color_index; ?>][price]"
                                                                class="form-control" placeholder="Add'l Price"
                                                                value="<?php echo $color['price']; ?>">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="button" class="btn btn-remove remove-color">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <button type="button" class="btn btn-secondary btn-sm add-color" data-type-index="<?php echo $index; ?>">
                                            <i class="fas fa-plus"></i> Add Color
                                        </button>
                                    </div>

                                    <button type="button" class="btn btn-danger btn-sm remove-type">
                                        <i class="fas fa-trash"></i> Remove Type
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <button type="button" class="btn btn-primary" id="addType">
                            <i class="fas fa-plus"></i> Add New Type
                        </button>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Update Product
                            </button>
                            <a href="updateforedit.php" class="btn btn-secondary">
                                Back to Products
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/updateproduct/updateforproduct.js"></script>
    <script>
        // Pass PHP data to JavaScript
        const productTypes = <?php echo json_encode($product_types ?? []); ?>;

        // Initialize the form when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof initializeProductForm === 'function') {
                initializeProductForm(productTypes);
            }
        });
    </script>
</body>

</html>