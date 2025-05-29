<?php
// mark_received.php - Fixed version with comprehensive error handling

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
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    
    http_response_code($httpCode);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit();
}

// Handle OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    sendJsonResponse(['status' => 'ok']);
}

// Initialize response
$response = ['success' => false, 'message' => 'Unknown error'];

try {
    // Validate request method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Validate reference number
    $reference_number = trim($_POST['reference_number'] ?? '');
    if (empty($reference_number)) {
        throw new Exception('Reference number is required');
    }

    // Test database connection first
    if (!file_exists('../../connection/connection.php')) {
        throw new Exception('Database connection file not found');
    }
    
    require_once '../../connection/connection.php';
    
    if (!isset($conn) || $conn->connect_error) {
        throw new Exception('Database connection failed: ' . (isset($conn) ? $conn->connect_error : 'Connection object not created'));
    }

    // Test gmail config
    if (!file_exists('gmail_config.php')) {
        throw new Exception('Gmail configuration file not found');
    }
    
    require_once 'gmail_config.php';
    
    if (!function_exists('sendEmailWithGmail')) {
        throw new Exception('Email function not found in gmail_config.php');
    }

    // Start transaction
    $conn->begin_transaction();

    // Get billing and user information
    $stmt = $conn->prepare("
        SELECT 
            bs.*,
            ui.email,
            ui.clientname
        FROM billing_submissions bs
        LEFT JOIN user_info ui ON bs.reference_number = ui.reference_number
        WHERE bs.reference_number = ?
    ");
    
    if (!$stmt) {
        throw new Exception('Prepare statement failed: ' . $conn->error);
    }
    
    $stmt->bind_param("s", $reference_number);
    
    if (!$stmt->execute()) {
        throw new Exception('Query execution failed: ' . $stmt->error);
    }
    
    $result = $stmt->get_result();
    $billing = $result->fetch_assoc();
    $stmt->close();

    if (!$billing) {
        throw new Exception('Billing record not found for reference: ' . $reference_number);
    }

    if (empty($billing['email'])) {
        throw new Exception('User email not found for this billing record');
    }

    // Update billing status to received
    $stmt = $conn->prepare("
        UPDATE billing_submissions 
        SET status = 'received', received_date = NOW() 
        WHERE reference_number = ?
    ");
    
    if (!$stmt) {
        throw new Exception('Prepare update statement failed: ' . $conn->error);
    }
    
    $stmt->bind_param("s", $reference_number);
    
    if (!$stmt->execute()) {
        throw new Exception('Failed to update billing status: ' . $stmt->error);
    }
    
    if ($stmt->affected_rows === 0) {
        throw new Exception('No rows updated - reference number may not exist');
    }
    
    $stmt->close();

    // Send email notification
    $emailResult = sendReceiptConfirmationEmail($billing);
    
    if (!$emailResult['success']) {
        // Rollback if email fails
        $conn->rollback();
        throw new Exception('Failed to send email: ' . $emailResult['message']);
    }

    // Commit transaction
    $conn->commit();

    // Success response
    $response = [
        'success' => true,
        'message' => 'Payment marked as received and notification sent successfully',
        'data' => [
            'reference_number' => $reference_number,
            'email_sent_to' => $billing['email'],
            'client_name' => $billing['client_name'] ?? $billing['clientname'] ?? 'Unknown'
        ]
    ];

} catch (Exception $e) {
    // Rollback transaction if it was started
    if (isset($conn) && $conn->ping()) {
        $conn->rollback();
    }
    
    $response = [
        'success' => false,
        'message' => $e->getMessage(),
        'debug_info' => [
            'file' => basename(__FILE__),
            'line' => $e->getLine(),
            'reference_number' => $reference_number ?? 'not_provided'
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

// Email functions
function sendReceiptConfirmationEmail($billing) {
    try {
        $to = $billing['email'];
        $subject = "Payment Received - Reference #" . $billing['reference_number'];
        
        $clientName = !empty($billing['client_name']) 
            ? $billing['client_name'] 
            : (!empty($billing['clientname']) ? $billing['clientname'] : 'Valued Client');
        
        $htmlBody = createEmailTemplate($billing, $clientName);
        
        return sendEmailWithGmail($to, $subject, $htmlBody, GMAIL_FROM_NAME);
        
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Email function error: ' . $e->getMessage()];
    }
}

function createEmailTemplate($billing, $clientName) {
    $projectCost = number_format($billing['total_project_cost'] ?? 0, 2);
    $remainingBalance = number_format($billing['remaining_balance'] ?? 0, 2);
    $submissionDate = date('F j, Y', strtotime($billing['submission_date']));
    
    return "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Payment Confirmation</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
            .content { background: #f8f9fa; padding: 30px; border-radius: 0 0 10px 10px; }
            .success-icon { font-size: 48px; color: #28a745; margin-bottom: 20px; }
            .details-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
            .details-table td { padding: 12px; border-bottom: 1px solid #ddd; }
            .details-table .label { font-weight: bold; background: #e9ecef; width: 40%; }
            .footer { text-align: center; margin-top: 30px; padding: 20px; background: #e9ecef; border-radius: 5px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>Payment Confirmation</h1>
                <p>Your payment has been successfully received!</p>
            </div>
            
            <div class='content'>
                <div style='text-align: center;'>
                    <div class='success-icon'>✅</div>
                    <h2>Thank you, " . htmlspecialchars($clientName) . "!</h2>
                    <p>We have received and confirmed your payment for reference number <strong>#" . htmlspecialchars($billing['reference_number']) . "</strong>.</p>
                </div>
                
                <table class='details-table'>
                    <tr>
                        <td class='label'>Reference Number:</td>
                        <td>" . htmlspecialchars($billing['reference_number']) . "</td>
                    </tr>
                    <tr>
                        <td class='label'>Client Name:</td>
                        <td>" . htmlspecialchars($clientName) . "</td>
                    </tr>
                    <tr>
                        <td class='label'>Total Project Cost:</td>
                        <td>₱{$projectCost}</td>
                    </tr>
                    <tr>
                        <td class='label'>Remaining Balance:</td>
                        <td>₱{$remainingBalance}</td>
                    </tr>
                    <tr>
                        <td class='label'>Submission Date:</td>
                        <td>{$submissionDate}</td>
                    </tr>
                    <tr>
                        <td class='label'>Status:</td>
                        <td><strong style='color: #28a745;'>RECEIVED ✓</strong></td>
                    </tr>
                </table>
                
                <div style='background: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px; padding: 15px; margin: 20px 0;'>
                    <h4 style='color: #155724; margin: 0 0 10px 0;'>What's Next?</h4>
                    <p style='margin: 0; color: #155724;'>
                        Your payment has been confirmed and processed. Our team will proceed with your project accordingly. 
                        If you have any questions or concerns, please don't hesitate to contact us.
                    </p>
                </div>
            </div>
            
            <div class='footer'>
                <p><strong>Thank you for your business!</strong></p>
                <p>If you have any questions, please contact our support team.</p>
                <p style='font-size: 12px; color: #6c757d; margin-top: 20px;'>
                    This is an automated message. Please do not reply to this email.
                </p>
            </div>
        </div>
    </body>
    </html>
    ";
}
?>