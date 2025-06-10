<?php 
header('Content-Type: application/json'); 
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST, OPTIONS'); 
header('Access-Control-Allow-Headers: Content-Type');  

// Handle CORS preflight 
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {     
    http_response_code(200);     
    exit(); 
}  

require_once '../../../connection/connection.php';  

$response = [];  

try {     
    // Get form data     
    $reference_number = $_POST['reference_number'] ?? '';     
    $client_name = $_POST['clientname'] ?? '';  // Fixed: should match form field name
    $total_project_cost = $_POST['total_project_cost'] ?? '';     
    $remaining_balance = $_POST['remaining_balance'] ?? '';     
    
    // Set default status instead of getting from POST
    $status = 'pending';  // Default status for new submissions
    $received_date = date('Y-m-d H:i:s');  // Current timestamp
    
    $receipt_image = $_FILES['receipt_image'] ?? null;      

    if (empty($reference_number)) {         
        throw new Exception("Reference number is required.");     
    }      

    // ✅ Check if reference number exists in user_info table     
    $stmt = $conn->prepare("SELECT reference_number FROM user_info WHERE reference_number = ?");     
    $stmt->bind_param("s", $reference_number);     
    $stmt->execute();     
    $result = $stmt->get_result();     
    if (!$result->fetch_assoc()) {         
        throw new Exception("Reference number does not exist in the user_info table. Please check and try again.");     
    }     
    $stmt->close();      

    // ✅ Handle image upload     
    if ($receipt_image && $receipt_image['error'] === UPLOAD_ERR_OK) {         
        $uploadDir = 'uploads/receipts/';         
        if (!is_dir($uploadDir)) {             
            mkdir($uploadDir, 0777, true); // Create uploads directory if it doesn't exist         
        }          

        $fileName = uniqid() . '_' . basename($receipt_image['name']);         
        $uploadFile = $uploadDir . $fileName;          

        if (!move_uploaded_file($receipt_image['tmp_name'], $uploadFile)) {             
            throw new Exception("Failed to move uploaded image.");         
        }          

        // ✅ Create full path for database storage
        // Option 1: Relative path from web root
        $fullPath = 'realivingpage/header/billingpage/uploads/receipts/' . $fileName;
        
        // Option 2: Absolute URL (uncomment if preferred)
        // $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        // $baseURL = $protocol . $_SERVER['HTTP_HOST'] . '/';
        // $fullPath = $baseURL . 'realivingpage/header/billingpage/uploads/receipts/' . $fileName;

        // ✅ Insert into billing_submissions table with full path        
        $stmt = $conn->prepare("             
            INSERT INTO billing_submissions                  
            (reference_number, client_name, total_project_cost, remaining_balance, receipt_image, status, received_date, submission_date)             
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())         
        ");         
        $stmt->bind_param("sssssss", $reference_number, $client_name, $total_project_cost, $remaining_balance, $fullPath, $status, $received_date);          

        if (!$stmt->execute()) {             
            throw new Exception("Failed to save billing submission: " . $stmt->error);         
        }          

        $stmt->close();          

        // ✅ Get total submissions for this reference number         
        $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM billing_submissions WHERE reference_number = ?");         
        $stmt->bind_param("s", $reference_number);         
        $stmt->execute();         
        $result = $stmt->get_result();         
        $submission_data = $result->fetch_assoc();         
        $submission_count = $submission_data['total'] ?? 1;         
        $stmt->close();          

        $response = [             
            'success' => true,             
            'message' => "Billing submitted successfully.",             
            'submission_count' => $submission_count,
            'status' => $status,
            'received_date' => $received_date,
            'image_path' => $fullPath  // Return the full path for verification
        ];     
    } else {         
        throw new Exception("Receipt image is required.");     
    } 
} catch (Exception $e) {     
    $response = [         
        'success' => false,         
        'message' => $e->getMessage()     
    ]; 
}  

$conn->close(); 
echo json_encode($response); 
?>