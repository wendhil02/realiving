<?php

// Turn off error display to prevent HTML output
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// Start output buffering to catch any unwanted output
ob_start();


// send_reminder.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../../connection/connection.php';
require_once 'gmail_config.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $reference_number = trim($_POST['reference_number'] ?? '');
    
    if (empty($reference_number)) {
        throw new Exception('Reference number is required');
    }

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
    $stmt->bind_param("s", $reference_number);
    $stmt->execute();
    $result = $stmt->get_result();
    $billing = $result->fetch_assoc();
    $stmt->close();

    if (!$billing) {
        throw new Exception('Billing record not found');
    }

    if (empty($billing['email'])) {
        throw new Exception('User email not found');
    }

    // Send reminder email
    $emailResult = sendReminderEmail($billing);
    
    if (!$emailResult['success']) {
        throw new Exception('Failed to send reminder: ' . $emailResult['message']);
    }

    echo json_encode([
        'success' => true,
        'message' => 'Reminder sent successfully',
        'data' => [
            'reference_number' => $reference_number,
            'email_sent_to' => $billing['email']
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

function sendReminderEmail($billing) {
    try {
        // Email configuration
        $to = $billing['email'];
        $subject = "Payment Reminder - Reference #" . $billing['reference_number'];
        
        // Use client_name from billing_submissions, fallback to clientname from user_info
        $clientName = !empty($billing['client_name']) 
            ? $billing['client_name'] 
            : (!empty($billing['clientname']) ? $billing['clientname'] : 'Valued Client');
        
        // Create HTML email content
        $htmlBody = createReminderEmailTemplate($billing, $clientName);
        
        // Send email using PHPMailer
        return sendEmailWithGmail($to, $subject, $htmlBody, GMAIL_FROM_NAME);
        
    } catch (Exception $e) {
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

function createReminderEmailTemplate($billing, $clientName) {
    $projectCost = number_format($billing['total_project_cost'] ?? 0, 2);
    $remainingBalance = number_format($billing['remaining_balance'] ?? 0, 2);
    $submissionDate = date('F j, Y', strtotime($billing['submission_date']));
    $daysSinceSubmission = floor((time() - strtotime($billing['submission_date'])) / (60 * 60 * 24));
    
    return "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Payment Reminder</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
            .content { background: #f8f9fa; padding: 30px; border-radius: 0 0 10px 10px; }
            .reminder-icon { font-size: 48px; color: #ffc107; margin-bottom: 20px; }
            .details-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
            .details-table td { padding: 12px; border-bottom: 1px solid #ddd; }
            .details-table .label { font-weight: bold; background: #e9ecef; width: 40%; }
            .footer { text-align: center; margin-top: 30px; padding: 20px; background: #e9ecef; border-radius: 5px; }
            .button { background: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 10px 0; }
            .urgent { background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 5px; padding: 15px; margin: 20px 0; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>Payment Reminder</h1>
                <p>We're following up on your recent submission</p>
            </div>
            
            <div class='content'>
                <div style='text-align: center;'>
                    <div class='reminder-icon'>⏰</div>
                    <h2>Hello, {$clientName}!</h2>
                    <p>This is a friendly reminder regarding your billing submission for reference number <strong>#{$billing['reference_number']}</strong>.</p>
                </div>
                
                <div class='urgent'>
                    <h4 style='color: #856404; margin: 0 0 10px 0;'>📋 Submission Status</h4>
                    <p style='margin: 0; color: #856404;'>
                        Your submission was received <strong>{$daysSinceSubmission} days ago</strong> and is currently being processed. 
                        If you have any updates or questions, please contact our team.
                    </p>
                </div>
                
                <table class='details-table'>
                    <tr>
                        <td class='label'>Reference Number:</td>
                        <td>{$billing['reference_number']}</td>
                    </tr>
                    <tr>
                        <td class='label'>Client Name:</td>
                        <td>{$clientName}</td>
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
                        <td class='label'>Current Status:</td>
                        <td><strong style='color: #ffc107;'>PENDING REVIEW</strong></td>
                    </tr>
                </table>
                
                <div style='background: #cce5ff; border: 1px solid #99ccff; border-radius: 5px; padding: 15px; margin: 20px 0;'>
                    <h4 style='color: #004085; margin: 0 0 10px 0;'>📞 Need Assistance?</h4>
                    <p style='margin: 0; color: #004085;'>
                        If you have any questions about your submission or need to provide additional information, 
                        please don't hesitate to contact our support team. We're here to help!
                    </p>
                </div>
                
                <div style='text-align: center; margin: 30px 0;'>
                    <a href='#' class='button'>Contact Support</a>
                </div>
            </div>
            
            <div class='footer'>
                <p><strong>Thank you for your patience!</strong></p>
                <p>We appreciate your business and will process your submission as quickly as possible.</p>
                <p style='font-size: 12px; color: #6c757d; margin-top: 20px;'>
                    This is an automated reminder. Please do not reply to this email.
                </p>
            </div>
        </div>
    </body>
    </html>
    ";
}
?>