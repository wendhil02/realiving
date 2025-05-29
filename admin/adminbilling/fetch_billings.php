<?php  
// fetch_billings.php
// Prevent any accidental output
ob_start();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Clear any output buffer that might contain errors/warnings
ob_clean();

try {
    // Include connection file with error handling
    $connection_file = '../../connection/connection.php';
    if (!file_exists($connection_file)) {
        throw new Exception('Database connection file not found');
    }
    
    require_once $connection_file;
    
    // Check if connection was successful
    if (!isset($conn) || $conn->connect_error) {
        throw new Exception('Database connection failed: ' . ($conn->connect_error ?? 'Connection variable not set'));
    }

    // Fetch billing submissions with user email information
    $query = "
        SELECT 
            bs.*,
            ui.email,
            ui.clientname,
            CASE 
                WHEN bs.status = 'received' THEN 'received'
                ELSE 'pending'
            END as status
        FROM billing_submissions bs
        LEFT JOIN user_info ui ON bs.reference_number = ui.reference_number
        ORDER BY bs.submission_date DESC
    ";
    
    $result = $conn->query($query);
    
    if (!$result) {
        throw new Exception('Database query failed: ' . $conn->error);
    }
    
    $billings = [];
    while ($row = $result->fetch_assoc()) {
        // Use client_name from billing_submissions, fallback to clientname from user_info
        if (empty($row['client_name']) && !empty($row['clientname'])) {
            $row['client_name'] = $row['clientname'];
        }
        
        // Ensure all expected fields exist with default values
        $row['email'] = $row['email'] ?? '';
        $row['client_name'] = $row['client_name'] ?? 'Unknown Client';
        $row['total_project_cost'] = $row['total_project_cost'] ?? 0;
        $row['remaining_balance'] = $row['remaining_balance'] ?? 0;
        $row['receipt_image'] = $row['receipt_image'] ?? '';
        
        // Remove the clientname field since we're using client_name
        unset($row['clientname']);
        
        $billings[] = $row;
    }
    
    // Send successful response
    echo json_encode([
        'success' => true,
        'billings' => $billings,
        'total' => count($billings)
    ]);

} catch (Exception $e) {
    // Log the error (optional)
    error_log('Fetch billings error: ' . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'debug' => [
            'file' => __FILE__,
            'line' => __LINE__,
            'connection_file_exists' => file_exists('../../connection/connection.php')
        ]
    ]);
} finally {
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}

// Prevent any additional output
exit();
?>