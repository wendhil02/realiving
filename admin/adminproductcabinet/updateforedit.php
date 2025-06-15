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
    $id = intval($_GET['delete_id']);
    
    $conn->begin_transaction();
    
    try {
        // First, check if the product exists
        $check_query = "SELECT id, product_name FROM products WHERE id = ?";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            throw new Exception("Product not found");
        }
        
        // Delete the main product
        $delete_product = $conn->prepare("DELETE FROM products WHERE id = ?");
        $delete_product->bind_param("i", $id);
        $delete_product->execute();
        
        $conn->commit();
        $_SESSION['success_msg'] = "Product deleted successfully!";
        
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error_msg'] = "Error deleting product: " . $e->getMessage();
    }
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Simplified query that only uses the products table
// Based on your database structure, using 'id' instead of 'product_id'
$products_query = "
    SELECT 
        id,
        product_name,
        description,
        status,
        main_image_blob,
        main_image_type,
        main_image_name,
        created_at,
        updated_at
    FROM products
    ORDER BY created_at DESC";

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
        .no-products { text-align: center; padding: 40px; color: #666; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .search-box { padding: 8px; border: 1px solid #ddd; border-radius: 4px; margin-right: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Product Management</h1>
            <div>
                <input type="text" class="search-box" placeholder="Search products..." id="searchBox">
                <a href="editproduct.php" class="btn btn-success">Add New Product</a>
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
                        <th>Status</th>
                        <th>Created</th>
                        <th>Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($product = $products_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['id']); ?></td>
                            <td>
                                <?php if (!empty($product['main_image_blob'])): ?>
                                    <img src="data:<?php echo htmlspecialchars($product['main_image_type']); ?>;base64,<?php echo base64_encode($product['main_image_blob']); ?>" 
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
                                <span class="status-<?php echo strtolower($product['status']); ?>">
                                    <?php echo htmlspecialchars($product['status']); ?>
                                </span>
                            </td>
                            <td><?php echo date('M j, Y', strtotime($product['created_at'])); ?></td>
                            <td>
                                <?php if (!empty($product['updated_at'])): ?>
                                    <?php echo date('M j, Y', strtotime($product['updated_at'])); ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="updateforproduct.php?id=<?php echo $product['id']; ?>" class="btn btn-primary" title="Edit Product">Edit</a>
                                <a href="?delete_id=<?php echo $product['id']; ?>" 
                                   class="btn btn-danger" 
                                   title="Delete Product"
                                   onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-products">
                <h3>No products found</h3>
                <p>Start by adding your first product to the system.</p>
                <a href="editproduct.php" class="btn btn-success">Add New Product</a>
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