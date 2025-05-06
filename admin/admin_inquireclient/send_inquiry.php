<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../PHPMailer-master/src/Exception.php';
require '../../PHPMailer-master/src/PHPMailer.php';
require '../../PHPMailer-master/src/SMTP.php';

include '../../vendor/autoload.php';
include '../../connection/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inquiry_id = $_POST['inquiry_id'];
    $recipient = $_POST['recipient_email'];

    // Check if message_type is set, else set default
    if (isset($_POST['message_type'])) {
        $message_type = $_POST['message_type']; // PM or AM
    } else {
        // Default to 'pm' if not set
        $message_type = 'pm';
    }

    // Fetch the selected inquiry
    $stmt = $conn->prepare("SELECT * FROM contact_inquiries WHERE id = ?");
    $stmt->bind_param("i", $inquiry_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $inquiry = $result->fetch_assoc();

    if ($inquiry) {
        $mail = new PHPMailer(true);
        try {
            // SMTP configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'wendhil10@gmail.com'; // ✅ Your Gmail address
            $mail->Password = 'tnjqjsuopqlwzoug';     // ✅ App password (no spaces)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Sender and recipient
            $mail->setFrom('wendhil10@gmail.com', 'Real Living'); // ✅ Must match Gmail
            $mail->addAddress($recipient);

            // Email content based on message type
            $mail->isHTML(true);

            if ($message_type === 'pm') {
                // Private Message Content
                $mail->Subject = '📥 New Inquiry from ' . $inquiry['full_name'];
                $mail->addEmbeddedImage('../../logo/mmone.png', 'realivinglogo');
                $mail->Body = '
                <div style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px; border-radius: 10px;">
                    <div style="text-align: center; margin-bottom: 20px;">
                        <img src="cid:realivinglogo" alt="Real Living Logo" style="width: 120px; height: auto;">
                    </div>
                    <div style="background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
                        <h2 style="color: #333333; text-align: center;">📥You have received a new client inquiry (PM)</h2>
                        <p><strong>Name:</strong> ' . htmlspecialchars($inquiry['full_name']) . '</p>
                        <p><strong>Phone:</strong> ' . htmlspecialchars($inquiry['phone_number']) . '</p>
                        <p><strong>Email:</strong> ' . htmlspecialchars($inquiry['email']) . '</p>
                        <p><strong>Date:</strong> ' . htmlspecialchars($inquiry['created_at']) . ' (PM)</p>
                    </div>
                    <p style="text-align: center; color: #999999; font-size: 12px; margin-top: 20px;">This is an automated message from Real Living</p>
                </div>';
            } elseif ($message_type === 'am') {
                // Admin Message Content
                $mail->Subject = '📥 New Inquiry Alert for Admin: ' . $inquiry['full_name'];
                $mail->addEmbeddedImage('../../logo/mmone.png', 'realivinglogo');
                $mail->Body = '
                <div style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px; border-radius: 10px;">
                    <div style="text-align: center; margin-bottom: 20px;">
                        <img src="cid:realivinglogo" alt="Real Living Logo" style="width: 120px; height: auto;">
                    </div>
                    <div style="background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
                        <h2 style="color: #333333; text-align: center;">📥 Admin: New Inquiry Received</h2>
                        <p><strong>Name:</strong> ' . htmlspecialchars($inquiry['full_name']) . '</p>
                        <p><strong>Phone:</strong> ' . htmlspecialchars($inquiry['phone_number']) . '</p>
                        <p><strong>Email:</strong> ' . htmlspecialchars($inquiry['email']) . '</p>
                        <p><strong>Date:</strong> ' . htmlspecialchars($inquiry['created_at']) . ' (AM)</p>
                    </div>
                    <p style="text-align: center; color: #999999; font-size: 12px; margin-top: 20px;">This is an automated message from Real Living</p>
                </div>';
            }

            // Debugging the email body content
            var_dump($mail->Body);  // Check what the email body contains before sending

            // Send email
            if (!empty($mail->Body)) {
                $mail->send();

                // Update inquiry status to sent
                $conn->query("UPDATE contact_inquiries SET sent_to_admin = 1 WHERE id = $inquiry_id");

                echo "<script>alert('Inquiry sent successfully!'); window.history.back();</script>";
            } else {
                echo "<script>alert('Message body is empty. Please check the content!'); window.history.back();</script>";
            }

        } catch (Exception $e) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }
    }
}
?>

