<?php
// get_billing_records.php - Fetch billing records for admin panel

// Prevent any output before JSON
ob_start();

// Error handling - log errors instead of displaying them
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

// Function to send JSON response and exit
function sendJsonResponse($data, $httpCode = 200) {
    // Clear any output buffer
    if (ob_get_level()) {
        ob_clean();
    }
    
    // Set headers
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
    
    http_response_code($httpCode);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit();
}

// Handle OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    sendJsonResponse(['status' => 'ok']);
}

// Initialize response
$response = ['success' => false, 'message' => 'Unknown error', 'data' => []];

try {
    // Check if connection file exists
    if (!file_exists('../../connection/connection.php')) {
        throw new Exception('Database connection file not found');
    }
    
    require_once '../../connection/connection.php';
    
    if (!isset($conn) || $conn->connect_error) {
        throw new Exception('Database connection failed: ' . (isset($conn) ? $conn->connect_error : 'Connection object not created'));
    }

    // Query to get billing records with user information
    $query = "
        SELECT 
            bs.*,
            ui.email,
            ui.clientname as client_name
        FROM billing_submissions bs
        LEFT JOIN user_info ui ON bs.reference_number = ui.reference_number
        ORDER BY bs.submission_date DESC
    ";
    
    $result = $conn->query($query);
    
    if (!$result) {
        throw new Exception('Query failed: ' . $conn->error);
    }
    
    $billings = [];
    while ($row = $result->fetch_assoc()) {
        // Format the data
        $billing = [
            'id' => $row['id'],
            'reference_number' => $row['reference_number'],
            'client_name' => $row['client_name'] ?: 'Unknown Client',
            'email' => $row['email'],
            'total_project_cost' => floatval($row['total_project_cost'] ?? 0),
            'remaining_balance' => floatval($row['remaining_balance'] ?? 0),
            'payment_amount' => floatval($row['payment_amount'] ?? 0),
            'status' => $row['status'] ?? 'pending',
            'submission_date' => $row['submission_date'],
            'received_date' => $row['received_date'],
            'receipt_path' => $row['receipt_path'],
            'payment_method' => $row['payment_method'],
            'notes' => $row['notes']
        ];
        
        $billings[] = $billing;
    }
    
    $response = [
        'success' => true,
        'message' => 'Billing records retrieved successfully',
        'data' => $billings,
        'total_records' => count($billings)
    ];

} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage(),
        'debug_info' => [
            'file' => basename(__FILE__),
            'line' => $e->getLine()
        ]
    ];
} catch (Error $e) {
    $response = [
        'success' => false,
        'message' => 'Fatal error occurred: ' . $e->getMessage(),
        'debug_info' => [
            'file' => basename(__FILE__),
            'line' => $e->getLine()
        ]
    ];
} finally {
    // Close database connection
    if (isset($conn) && $conn->ping()) {
        $conn->close();
    }
}

// Send the response
sendJsonResponse($response, $response['success'] ? 200 : 400);
?>