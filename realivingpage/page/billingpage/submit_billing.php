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

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Input sanitization
    $reference_number = trim($_POST['reference_number'] ?? '');
    $clientname = trim($_POST['clientname'] ?? '');
    $total_project_cost = trim($_POST['total_project_cost'] ?? '');
    $remaining_balance = trim($_POST['remaining_balance'] ?? '');

    if (empty($reference_number)) throw new Exception('Reference number is required');
    if (empty($clientname)) throw new Exception('Client name is required');
    if (!empty($total_project_cost) && !is_numeric($total_project_cost)) throw new Exception('Total project cost must be numeric');
    if (!empty($remaining_balance) && !is_numeric($remaining_balance)) throw new Exception('Remaining balance must be numeric');

    $total_project_cost = !empty($total_project_cost) ? floatval($total_project_cost) : null;
    $remaining_balance = !empty($remaining_balance) ? floatval($remaining_balance) : null;

    // ✅ Check if reference number exists in user_info table
    $stmt = $conn->prepare("SELECT reference_number FROM user_info WHERE reference_number = ?");
    $stmt->bind_param("s", $reference_number);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result->fetch_assoc()) {
        throw new Exception("Reference number does not exist in the user_info table. Please check and try again.");
    }
    $stmt->close();

    // ✅ IMPORTANT: Ensure the reference_number also exists in billing table due to foreign key constraint
    // If it doesn't exist in billing table, create it first
    $stmt = $conn->prepare("SELECT reference_number FROM billing WHERE reference_number = ?");
    $stmt->bind_param("s", $reference_number);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result->fetch_assoc()) {
        // Insert into billing table to satisfy foreign key constraint
        $stmt_insert = $conn->prepare("INSERT INTO billing (reference_number) VALUES (?)");
        $stmt_insert->bind_param("s", $reference_number);
        if (!$stmt_insert->execute()) {
            throw new Exception('Failed to create billing record: ' . $stmt_insert->error);
        }
        $stmt_insert->close();
    }
    $stmt->close();

    // Upload receipt if available
    $receipt_filename = null;
    if (isset($_FILES['receipt_image']) && $_FILES['receipt_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/receipts/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

        $file = $_FILES['receipt_image'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed_exts = ['jpg', 'jpeg', 'png', 'webp'];
        $allowed_mimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        if (!in_array($ext, $allowed_exts)) throw new Exception('Invalid file extension');
        if ($file['size'] > 10 * 1024 * 1024) throw new Exception('File exceeds 10MB limit');

        $mime = mime_content_type($file['tmp_name']);
        if (!in_array($mime, $allowed_mimes)) throw new Exception('Invalid file MIME type');

        $safe_reference = preg_replace('/[^a-zA-Z0-9_-]/', '_', $reference_number);
        $receipt_filename = "receipt_{$safe_reference}_" . uniqid() . '.' . $ext;
        $upload_path = $upload_dir . $receipt_filename;

        if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
            throw new Exception('Failed to move uploaded file');
        }
    } elseif (isset($_FILES['receipt_image']) && $_FILES['receipt_image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $upload_errors = [
            UPLOAD_ERR_INI_SIZE => 'File exceeds ini size',
            UPLOAD_ERR_FORM_SIZE => 'File exceeds form size',
            UPLOAD_ERR_PARTIAL => 'File partially uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temp folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write to disk',
            UPLOAD_ERR_EXTENSION => 'Upload stopped by extension'
        ];
        $err = $_FILES['receipt_image']['error'];
        throw new Exception($upload_errors[$err] ?? 'Unknown upload error');
    }

    // Check if record exists in billing_submissions
    $stmt = $conn->prepare("SELECT id, receipt_image FROM billing_submissions WHERE reference_number = ?");
    $stmt->bind_param("s", $reference_number);
    $stmt->execute();
    $result = $stmt->get_result();
    $existing = $result->fetch_assoc();
    $stmt->close();

    if ($existing && !$receipt_filename) {
        $receipt_filename = $existing['receipt_image'];
    }

    if ($existing) {
        // Update
        $stmt = $conn->prepare("
            UPDATE billing_submissions SET 
                client_name = ?, 
                total_project_cost = ?, 
                remaining_balance = ?, 
                receipt_image = ?, 
                submission_date = NOW()
            WHERE reference_number = ?
        ");
        $stmt->bind_param("sddss", $clientname, $total_project_cost, $remaining_balance, $receipt_filename, $reference_number);
        $action = 'updated';
        $submission_id = $existing['id'];
    } else {
        // Insert
        $stmt = $conn->prepare("
            INSERT INTO billing_submissions 
                (reference_number, client_name, total_project_cost, remaining_balance, receipt_image, submission_date)
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        $stmt->bind_param("ssdss", $reference_number, $clientname, $total_project_cost, $remaining_balance, $receipt_filename);
        $action = 'created';
    }

    if (!$stmt->execute()) {
        throw new Exception('Database error: ' . $stmt->error);
    }

    if (!$existing) {
        $submission_id = $stmt->insert_id;
    }

    $stmt->close();

    echo json_encode([
        'success' => true,
        'message' => "Billing information {$action} successfully",
        'data' => [
            'reference_number' => $reference_number,
            'client_name' => $clientname,
            'total_project_cost' => $total_project_cost,
            'remaining_balance' => $remaining_balance,
            'receipt_filename' => $receipt_filename,
            'submission_id' => $submission_id,
            'action' => $action
        ]
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?>