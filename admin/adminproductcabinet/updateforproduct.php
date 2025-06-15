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

    // Handle main product image update - FIXED: Using BLOB columns
    $main_image_update = "";
    if (!empty($_FILES['main_image']['name']) && $_FILES['main_image']['error'] === UPLOAD_ERR_OK) {
        $image_data = file_get_contents($_FILES['main_image']['tmp_name']);
        $image_type = $_FILES['main_image']['type'];
        $image_name = $_FILES['main_image']['name'];

        $main_image_update = ", main_image_blob = ?, main_image_type = ?, main_image_name = ?";
    }

    if (empty($product_name)) {
        $_SESSION['error_msg'] = "Product name is required.";
    } else {
        $conn->begin_transaction();

        try {
            // Update main product - FIXED: Using correct column names
            if (!empty($main_image_update)) {
                $updateProduct = "UPDATE products SET 
                                  product_name = ?, 
                                  description = ?, 
                                  status = ?, 
                                  updated_at = ?
                                  $main_image_update 
                                  WHERE id = ?";
                $stmt = $conn->prepare($updateProduct);
                $stmt->bind_param(
                    "ssssssi",
                    $product_name,
                    $description,
                    $status,
                    $updated_at,
                    $image_data,
                    $image_type,
                    $image_name,
                    $product_id
                );
            } else {
                $updateProduct = "UPDATE products SET 
                                  product_name = ?, 
                                  description = ?, 
                                  status = ?, 
                                  updated_at = ? 
                                  WHERE id = ?";
                $stmt = $conn->prepare($updateProduct);
                $stmt->bind_param("ssssi", $product_name, $description, $status, $updated_at, $product_id);
            }

            if ($stmt->execute()) {
                // Handle existing types updates and new types
                if (isset($_POST['type_names']) && is_array($_POST['type_names'])) {
                    $type_names = $_POST['type_names'];
                    $type_prices = $_POST['type_prices'];
                    $type_descriptions = $_POST['type_descriptions'];
                    $existing_type_ids = isset($_POST['existing_type_ids']) ? $_POST['existing_type_ids'] : [];

                    for ($t = 0; $t < count($type_names); $t++) {
                        if (!empty($type_names[$t])) {
                            $type_name = $type_names[$t];
                            $type_price = floatval($type_prices[$t]);
                            $type_desc = $type_descriptions[$t];
                            $existing_type_id = isset($existing_type_ids[$t]) ? intval($existing_type_ids[$t]) : 0;

                            // Handle type image - FIXED: Using BLOB columns
                            $type_image_data = null;
                            $type_image_type = null;
                            $type_image_name = null;

                            if (!empty($_FILES['type_images']['name'][$t]) && $_FILES['type_images']['error'][$t] === UPLOAD_ERR_OK) {
                                $type_image_data = file_get_contents($_FILES['type_images']['tmp_name'][$t]);
                                $type_image_type = $_FILES['type_images']['type'][$t];
                                $type_image_name = $_FILES['type_images']['name'][$t];
                            }

                            if ($existing_type_id > 0) {
                                // Update existing type - FIXED: Using correct column names
                                if ($type_image_data) {
                                    $updateType = "UPDATE product_types SET 
                                                   type_name = ?, 
                                                   base_price = ?, 
                                                   type_description = ?,
                                                   type_image_blob = ?,
                                                   type_image_type = ?,
                                                   type_image_name = ?
                                                   WHERE id = ?";
                                    $stmt = $conn->prepare($updateType);
                                    $stmt->bind_param(
                                        "sdsssi",
                                        $type_name,
                                        $type_price,
                                        $type_desc,
                                        $type_image_data,
                                        $type_image_type,
                                        $type_image_name,
                                        $existing_type_id
                                    );
                                } else {
                                    $updateType = "UPDATE product_types SET 
                                                   type_name = ?, 
                                                   base_price = ?, 
                                                   type_description = ?
                                                   WHERE id = ?";
                                    $stmt = $conn->prepare($updateType);
                                    $stmt->bind_param("sdsi", $type_name, $type_price, $type_desc, $existing_type_id);
                                }
                                $stmt->execute();
                                $type_id = $existing_type_id;
                            } else {
                                // Insert new type - FIXED: Using correct column names
                                $insertType = "INSERT INTO product_types 
                                               (product_id, type_name, base_price, type_description, type_image_blob, type_image_type, type_image_name, created_at) 
                                               VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                                $stmt = $conn->prepare($insertType);
                                $stmt->bind_param(
                                    "isdsssss",
                                    $product_id,
                                    $type_name,
                                    $type_price,
                                    $type_desc,
                                    $type_image_data,
                                    $type_image_type,
                                    $type_image_name,
                                    $updated_at
                                );
                                $stmt->execute();
                                $type_id = $conn->insert_id;
                            }

                            // Handle sizes for this type - FIXED: Using correct column names
                            if (isset($_POST['sizes'][$t]) && is_array($_POST['sizes'][$t])) {
                                // First, delete existing sizes for this type
                                $deleteStmt = $conn->prepare("DELETE FROM product_sizes WHERE product_type_id = ?");
                                $deleteStmt->bind_param("i", $type_id);
                                $deleteStmt->execute();

                                $type_sizes = $_POST['sizes'][$t];
                                foreach ($type_sizes as $size_data) {
                                    if (!empty($size_data['name'])) {
                                        $size_name = $size_data['name'];
                                        $size_dimensions = $size_data['dimensions'];
                                        $size_price = floatval($size_data['price']);

                                        $insertSize = "INSERT INTO product_sizes 
                                                       (product_type_id, size_name, dimensions, extra_price, created_at) 
                                                       VALUES (?, ?, ?, ?, ?)";
                                        $stmt = $conn->prepare($insertSize);
                                        $stmt->bind_param("issds", $type_id, $size_name, $size_dimensions, $size_price, $updated_at);
                                        $stmt->execute();
                                    }
                                }
                            }

                            // Handle colors for this type - FIXED: Preserve existing images when not uploading new ones
                            if (isset($_POST['colors'][$t]) && is_array($_POST['colors'][$t])) {
                                // Get existing colors for this type first
                                $existing_colors_query = "SELECT id, color_image_blob, color_image_type FROM product_colors WHERE product_type_id = ?";
                                $existing_colors_stmt = $conn->prepare($existing_colors_query);
                                $existing_colors_stmt->bind_param("i", $type_id);
                                $existing_colors_stmt->execute();
                                $existing_colors_result = $existing_colors_stmt->get_result();

                                $existing_color_images = [];
                                while ($existing_color = $existing_colors_result->fetch_assoc()) {
                                    $existing_color_images[$existing_color['id']] = [
                                        'blob' => $existing_color['color_image_blob'],
                                        'type' => $existing_color['color_image_type']
                                    ];
                                }

                                // Delete existing colors for this type
                                $deleteStmt = $conn->prepare("DELETE FROM product_colors WHERE product_type_id = ?");
                                $deleteStmt->bind_param("i", $type_id);
                                $deleteStmt->execute();

                                $type_colors = $_POST['colors'][$t];
                                foreach ($type_colors as $color_index => $color_data) {
                                    if (!empty($color_data['name'])) {
                                        $color_name = $color_data['name'];
                                        $color_code = $color_data['code'];
                                        $color_price = floatval($color_data['price']);

                                        $color_image_data = null;
                                        $color_image_type = null;

                                        // Check if new image is uploaded
                                        if (
                                            !empty($_FILES['color_images']['name'][$t][$color_index]) &&
                                            $_FILES['color_images']['error'][$t][$color_index] === UPLOAD_ERR_OK
                                        ) {
                                            // New image uploaded
                                            $color_image_data = file_get_contents($_FILES['color_images']['tmp_name'][$t][$color_index]);
                                            $color_image_type = $_FILES['color_images']['type'][$t][$color_index];
                                        } else {
                                            // No new image uploaded, check if we have existing color ID to preserve image
                                            if (isset($_POST['existing_color_ids'][$t][$color_index])) {
                                                $existing_color_id = intval($_POST['existing_color_ids'][$t][$color_index]);
                                                if (isset($existing_color_images[$existing_color_id])) {
                                                    $color_image_data = $existing_color_images[$existing_color_id]['blob'];
                                                    $color_image_type = $existing_color_images[$existing_color_id]['type'];
                                                }
                                            }
                                        }

                                        $insertColor = "INSERT INTO product_colors 
                            (product_type_id, color_name, color_code, extra_price, color_image_blob, color_image_type, created_at) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)";
                                        $stmt = $conn->prepare($insertColor);
                                        $stmt->bind_param(
                                            "issdsss",
                                            $type_id,
                                            $color_name,
                                            $color_code,
                                            $color_price,
                                            $color_image_data,
                                            $color_image_type,
                                            $updated_at
                                        );
                                        $stmt->execute();
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

// Fetch product data
$product_query = "SELECT * FROM products WHERE id = ?";
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

// Fetch product types with sizes and colors - FIXED: Using correct column names
$types_query = "
    SELECT 
        pt.*,
        GROUP_CONCAT(DISTINCT CONCAT(ps.id, ':', ps.size_name, ':', ps.dimensions, ':', ps.extra_price) SEPARATOR '||') as sizes_data,
        GROUP_CONCAT(DISTINCT CONCAT(pc.id, ':', pc.color_name, ':', pc.color_code, ':', pc.extra_price) SEPARATOR '||') as colors_data
    FROM product_types pt
    LEFT JOIN product_sizes ps ON pt.id = ps.product_type_id
    LEFT JOIN product_colors pc ON pt.id = pc.product_type_id
    WHERE pt.product_id = ?
    GROUP BY pt.id
    ORDER BY pt.id";

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
                $color_parts = explode(':', $color_string, 4);
                if (count($color_parts) >= 4) {
                    $colors[] = [
                        'id' => $color_parts[0],
                        'name' => $color_parts[1],
                        'code' => $color_parts[2],
                        'price' => $color_parts[3]
                    ];
                }
            }
        }
    }

    $product_types[] = [
        'id' => $type_row['id'],
        'type_name' => $type_row['type_name'],
        'price' => $type_row['base_price'], // FIXED: Using base_price
        'description' => $type_row['type_description'], // FIXED: Using type_description
        'sizes' => $sizes,
        'colors' => $colors
    ];
}

// Function to display image from BLOB
function displayImage($imageBlob, $imageType, $imageName, $maxWidth = 150, $maxHeight = 150)
{
    if (!empty($imageBlob)) {
        $base64 = base64_encode($imageBlob);
        return "<img src='data:$imageType;base64,$base64' alt='$imageName' style='max-width: {$maxWidth}px; max-height: {$maxHeight}px;'>";
    }
    return "";
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
                                <option value="active" <?php echo $product['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo $product['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                <option value="draft" <?php echo $product['status'] == 'draft' ? 'selected' : ''; ?>>Draft</option>
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
                            <?php if (!empty($product['main_image_blob'])): ?>
                                <div class="mt-2">
                                    <?php echo displayImage($product['main_image_blob'], $product['main_image_type'], $product['main_image_name']); ?>
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
                                            <?php
                                            // Fetch type image from database
                                            $type_image_query = "SELECT type_image_blob, type_image_type, type_image_name FROM product_types WHERE id = ?";
                                            $stmt = $conn->prepare($type_image_query);
                                            $stmt->bind_param("i", $type['id']);
                                            $stmt->execute();
                                            $type_image_result = $stmt->get_result();
                                            $type_image_data = $type_image_result->fetch_assoc();

                                            if (!empty($type_image_data['type_image_blob'])): ?>
                                                <div class="mt-2">
                                                    <?php echo displayImage($type_image_data['type_image_blob'], $type_image_data['type_image_type'], $type_image_data['type_image_name'], 80, 80); ?>
                                                </div>
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
                                                                class="form-control" placeholder="Extra Price"
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
                                                    <!-- Hidden field to store existing color ID -->
                                                    <input type="hidden" name="existing_color_ids[<?php echo $index; ?>][<?php echo $color_index; ?>]" value="<?php echo $color['id']; ?>">

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
                                                            <?php
                                                            // Fetch color image from database
                                                            $color_image_query = "SELECT color_image_blob, color_image_type FROM product_colors WHERE id = ?";
                                                            $stmt = $conn->prepare($color_image_query);
                                                            $stmt->bind_param("i", $color['id']);
                                                            $stmt->execute();
                                                            $color_image_result = $stmt->get_result();
                                                            $color_image_data = $color_image_result->fetch_assoc();

                                                            if (!empty($color_image_data['color_image_blob'])): ?>
                                                                <div class="mt-2">
                                                                    <?php echo displayImage($color_image_data['color_image_blob'], $color_image_data['color_image_type'], 'Color Image', 50, 50); ?>
                                                                    <small class="text-muted d-block">Current image (will be kept if no new image uploaded)</small>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input type="number" step="0.01" name="colors[<?php echo $index; ?>][<?php echo $color_index; ?>][price]"
                                                                class="form-control" placeholder="Extra Price"
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