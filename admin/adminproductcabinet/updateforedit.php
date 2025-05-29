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
include '../design/mainbody.php';

// Handle delete request
if (isset($_GET['delete_id'])) {
    $product_id = intval($_GET['delete_id']);
    
    $conn->begin_transaction();
    
    try {
        // Get all images before deletion for cleanup
        $images_query = "
            SELECT p.main_image, pt.type_image, pc.color_image 
            FROM products p 
            LEFT JOIN product_types pt ON p.product_id = pt.product_id 
            LEFT JOIN product_colors pc ON pt.type_id = pc.type_id 
            WHERE p.product_id = ?";
        
        $stmt = $conn->prepare($images_query);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $images_result = $stmt->get_result();
        
        $images_to_delete = [];
        while ($row = $images_result->fetch_assoc()) {
            if (!empty($row['main_image'])) $images_to_delete[] = "../../" . $row['main_image'];
            if (!empty($row['type_image'])) $images_to_delete[] = "../../" . $row['type_image'];
            if (!empty($row['color_image'])) $images_to_delete[] = "../../" . $row['color_image'];
        }
        
        // Delete in reverse order of creation (colors -> sizes -> types -> product)
        $conn->query("DELETE pc FROM product_colors pc 
                     INNER JOIN product_types pt ON pc.type_id = pt.type_id 
                     WHERE pt.product_id = $product_id");
        
        $conn->query("DELETE ps FROM product_sizes ps 
                     INNER JOIN product_types pt ON ps.type_id = pt.type_id 
                     WHERE pt.product_id = $product_id");
        
        $conn->query("DELETE FROM product_types WHERE product_id = $product_id");
        $conn->query("DELETE FROM products WHERE product_id = $product_id");
        
        // Delete image files
        foreach (array_unique($images_to_delete) as $image_path) {
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        
        $conn->commit();
        $_SESSION['success_msg'] = "Product deleted successfully!";
        
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error_msg'] = "Error deleting product: " . $e->getMessage();
    }
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch products with their types count using correct column names
$products_query = "
    SELECT 
        p.*,
        COUNT(DISTINCT pt.type_id) as types_count,
        COUNT(DISTINCT ps.id) as sizes_count,
        COUNT(DISTINCT pc.id) as colors_count
    FROM products p
    LEFT JOIN product_types pt ON p.product_id = pt.product_id
    LEFT JOIN product_sizes ps ON pt.type_id = ps.type_id
    LEFT JOIN product_colors pc ON pt.type_id = pc.type_id
    GROUP BY p.product_id
    ORDER BY p.created_at DESC";

$products_result = $conn->query($products_query);

if (!$products_result) {
    $error_msg = "Error fetching products: " . $conn->error;
}
?>

<!DOCTYPE html>
<html>
<head>
     <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <link rel="icon" type="image/png" sizes="32x32" href="../../logo/favicon.ico">
    <style>
        .alert { padding: 10px; margin: 10px 0; border-radius: 4px; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .btn { padding: 8px 16px; text-decoration: none; border-radius: 4px; margin: 2px; display: inline-block; }
        .btn-danger { background-color: #dc3545; color: white; }
        .btn-primary { background-color: #007bff; color: white; }
        .btn-success { background-color: #28a745; color: white; }
        .product-image { max-width: 80px; max-height: 80px; object-fit: cover; border-radius: 4px; }
        .status-active { color: #28a745; font-weight: bold; }
        .status-inactive { color: #dc3545; font-weight: bold; }
        .counts { font-size: 0.9em; color: #666; }
        .no-products { text-align: center; padding: 40px; color: #666; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .header { display: flex; justify-content: between; align-items: center; margin-bottom: 20px; }
        .search-box { padding: 8px; border: 1px solid #ddd; border-radius: 4px; margin-right: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="mr-5">Product Management</h1>
            <div>
                <input type="text" class="search-box" placeholder="Search products..." id="searchBox">
              
            </div>
        </div>
        
        <?php if (!empty($success_msg)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success_msg); ?></div>
        <?php endif; ?>
        
        <?php if (!empty($error_msg)): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error_msg); ?></div>
        <?php endif; ?>
        
        <?php if ($products_result && $products_result->num_rows > 0): ?>
            <table id="productsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Product Name</th>
                        <th>Description</th>
                        <th>Variants</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($product = $products_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['product_id']); ?></td>
                            <td>
                                <?php if (!empty($product['main_image'])): ?>
                                    <img src="../../<?php echo htmlspecialchars($product['main_image']); ?>" 
                                         alt="<?php echo htmlspecialchars($product['product_name']); ?>" 
                                         class="product-image">
                                <?php else: ?>
                                    <div style="width: 80px; height: 80px; background: #f8f9fa; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #6c757d; font-size: 12px;">No Image</div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($product['product_name']); ?></strong>
                            </td>
                            <td>
                                <?php 
                                $desc = htmlspecialchars($product['description']);
                                echo strlen($desc) > 100 ? substr($desc, 0, 100) . '...' : $desc; 
                                ?>
                            </td>
                            <td>
                                <div class="counts">
                                    <?php echo $product['types_count']; ?> Types<br>
                                    <?php echo $product['colors_count']; ?> Colors<br>
                                    <?php echo $product['sizes_count']; ?> Sizes
                                </div>
                            </td>
                            <td>
                                <span class="status-<?php echo strtolower($product['status']); ?>">
                                    <?php echo htmlspecialchars($product['status']); ?>
                                </span>
                            </td>
                            <td><?php echo date('M j, Y', strtotime($product['created_at'])); ?></td>
                            <td>
                                <a href="updateforproduct.php?id=<?php echo $product['product_id']; ?>" class="btn btn-primary" title="Edit Product">Edit</a>
                                <a href="?delete_id=<?php echo $product['product_id']; ?>" 
                                   class="btn btn-danger" 
                                   title="Delete Product"
                                   onclick="return confirm('Are you sure you want to delete this product and all its variants?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-products">
                <h3>No products found</h3>
                <p>Start by adding your first product to the system.</p>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Simple search functionality
        document.getElementById('searchBox').addEventListener('keyup', function() {
            const searchText = this.value.toLowerCase();
            const table = document.getElementById('productsTable');
            const rows = table.getElementsByTagName('tr');
            
            for (let i = 1; i < rows.length; i++) { // Skip header row
                const row = rows[i];
                const productName = row.cells[2].textContent.toLowerCase();
                const description = row.cells[3].textContent.toLowerCase();
                
                if (productName.includes(searchText) || description.includes(searchText)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>