<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../PHPMailer-master/src/Exception.php';
require '../../PHPMailer-master/src/PHPMailer.php';
require '../../PHPMailer-master/src/SMTP.php';
include '../../vendor/autoload.php';
include '../../connection/connection.php';
session_start();
include '../checkrole.php';
require_role(['admin4', 'admin5', 'superadmin']);

function sendEmailViaPHPMailer($inquiry, $recipient, $conn, $message_type = 'pm') {
    $mail = new PHPMailer(true);
    try {
        // SMTP config
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'wendhil10@gmail.com';
        $mail->Password = 'tnjqjsuopqlwzoug';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Sender & Recipient
        $mail->setFrom('wendhil10@gmail.com', 'Real Living');
        $mail->addAddress($recipient);
        $mail->isHTML(true);
        $mail->addEmbeddedImage('../../logo/mmone.png', 'realivinglogo');

        // Subject & Body
        $mail->Subject = ' Inquiry from ' . $inquiry['full_name'];
        $header = ($message_type === 'admin')
            ? ' Forwarded Client Inquiry'
            : (($message_type === 'pm') ? ' New Client Inquiry (ADMIM)' : ' Admin: New Inquiry Received');

        $mail->Body = '
        <div style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px; border-radius: 10px;">
            <div style="text-align: center; margin-bottom: 20px;">
                <img src="cid:realivinglogo" alt="Real Living Logo" style="width: 120px; height: auto;">
            </div>
            <div style="background-color: #ffffff; padding: 20px; border-radius: 8px;">
                <h2 style="color: #333333; text-align: center;">' . $header . '</h2>
                <p><strong>Name:</strong> ' . htmlspecialchars($inquiry['full_name']) . '</p>
                <p><strong>Phone:</strong> ' . htmlspecialchars($inquiry['phone_number']) . '</p>
                <p><strong>Email:</strong> ' . htmlspecialchars($inquiry['email']) . '</p>
                <p><strong>Date:</strong> ' . date("F j, Y - g:i A", strtotime($inquiry['created_at'])) . ' (' . strtoupper($message_type) . ')</p>';
        
        if (!empty($inquiry['client_type'])) {
            $mail->Body .= '<p><strong>Client Type:</strong> ' . htmlspecialchars($inquiry['client_type']) . '</p>';
        }

        if (!empty($inquiry['message'])) {
            $mail->Body .= '<p><strong>Message:</strong><br>' . nl2br(htmlspecialchars($inquiry['message'])) . '</p>';
        }

        $mail->Body .= '
            </div>
             <p style="text-align: center; color: #999; font-size: 12px; margin-top: 20px;">This client has been assigned to you.</p>
            <p style="text-align: center; color: #999; font-size: 12px; margin-top: 20px;">This is an automated message from Real Living</p>
        </div>';

        $mail->send();

        // Update status
        $conn->query("UPDATE contact_inquiries SET sent_to_admin = 1, sent_at = NOW() WHERE id = {$inquiry['id']}");

        // Log if custom email
        if ($message_type === '') {
            $admin_email = $_SESSION['admin_email'] ?? 'unknown@domain.com';
            $stmt = $conn->prepare("INSERT INTO email_logs (inquiry_id, recipient_email, sent_by, sent_at) VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("iss", $inquiry['id'], $recipient, $admin_email);
            $stmt->execute();
        }

        return true;

    } catch (Exception $e) {
        return "Mailer Error: " . $mail->ErrorInfo;
    }
}

// ============ POST HANDLER ============
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inquiry_id = intval($_POST['inquiry_id']);
    $recipient_email = $_POST['custom_email'] ?? $_POST['recipient_email'] ?? '';
    $message_type = $_POST['message_type'] ?? 'pm';

    // Validation
    if (empty($recipient_email) || !filter_var($recipient_email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Invalid email address.';
        header('Location: inquireclient.php');
        exit;
    }

    // Fetch inquiry
    $stmt = $conn->prepare("SELECT * FROM contact_inquiries WHERE id = ?");
    $stmt->bind_param("i", $inquiry_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $inquiry = $result->fetch_assoc();

    if (!$inquiry) {
        $_SESSION['error'] = "Inquiry not found.";
        header('Location: inquireclient.php');
        exit;
    }

    // Send email via PHPMailer
    $send_result = sendEmailViaPHPMailer($inquiry, $recipient_email, $conn, $message_type);

    if ($send_result === true) {
        $_SESSION['success'] = 'Inquiry sent successfully to ' . htmlspecialchars($recipient_email);
    } else {
        $_SESSION['error'] = $send_result;
    }

    header('Location: inquireclient.php');
    exit;
}
?>
