<?php
include '../../connection/connection.php';

if (!isset($_GET['product_id'])) {
    echo json_encode([]);
    exit;
}

$product_id = intval($_GET['product_id']);

$query = "SELECT id, type_name, type_image, type_color, price, description 
          FROM product_types 
          WHERE product_id = ? 
          ORDER BY type_name";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

$types = [];
while ($row = $result->fetch_assoc()) {
    $types[] = $row;
}

header('Content-Type: application/json');
echo json_encode($types);

$conn->close();
?>