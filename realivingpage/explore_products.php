<?php
session_start();
include '../connection/connection.php';

// Initialize response array
$products = [];

// Get search query if provided
$query = isset($_GET['query']) ? trim($_GET['query']) : '';

try {
    // Prepare query based on search terms
    if (!empty($query)) {
        $stmt = $conn->prepare("
            SELECT 
                item_id, item_code, item_name, item_unit, item_category, 
                item_price, item_labor_cost, item_image, 
                item_width, item_height, item_length
            FROM items
            WHERE item_code LIKE CONCAT('%', ?, '%')
               OR item_name LIKE CONCAT('%', ?, '%')
            ORDER BY item_category, item_name
            LIMIT 100
        ");
        $stmt->bind_param('ss', $query, $query);
    } else {
        // No search query, return all products (with limit)
        $stmt = $conn->prepare("
            SELECT 
                item_id, item_code, item_name, item_unit, item_category, 
                item_price, item_labor_cost, item_image, 
                item_width, item_height, item_length
            FROM items
            ORDER BY item_category, item_name
            LIMIT 100
        ");
    }
    
    // Execute query
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Process results
    while ($row = $result->fetch_assoc()) {
        $product = [
            'item_id' => $row['item_id'],
            'item_code' => $row['item_code'],
            'item_name' => $row['item_name'],
            'item_unit' => $row['item_unit'],
            'item_category' => $row['item_category'],
            'item_price' => $row['item_price'],
            'item_labor_cost' => $row['item_labor_cost'],
            'item_width' => $row['item_width'],
            'item_height' => $row['item_height'],
            'item_length' => $row['item_length'],
            'item_image' => null,
            'mime_type' => null
        ];
        
        // Handle image data
        if (!empty($row['item_image'])) {
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($row['item_image']);
            $product['item_image'] = base64_encode($row['item_image']);
            $product['mime_type'] = $mimeType;
        }
        
        $products[] = $product;
    }
    
    $stmt->close();
    
    // Return products as JSON
    header('Content-Type: application/json');
    echo json_encode($products);
    
} catch (Exception $e) {
    // Log error
    error_log('Error in explore_products.php: ' . $e->getMessage());
    
    // Return error response
    header('Content-Type: application/json');
    echo json_encode([]);
}
?>