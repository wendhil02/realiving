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

            // Email content
            $mail->isHTML(true);
            $mail->Subject = '📥 New Inquiry from ' . $inquiry['full_name'];
            $mail->addEmbeddedImage('../../logo/mmone.png', 'realivinglogo');
            $mail->Body = '
            <div style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px; border-radius: 10px;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <img src="cid:realivinglogo" alt="Real Living Logo" style="width: 120px; height: auto;">
                </div>
                <div style="background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
                    <h2 style="color: #333333; text-align: center;">📥Your are a sign this New Client</h2>
                    <p><strong>Name:</strong> ' . htmlspecialchars($inquiry['full_name']) . '</p>
                    <p><strong>Phone:</strong> ' . htmlspecialchars($inquiry['phone_number']) . '</p>
                    <p><strong>Email:</strong> ' . htmlspecialchars($inquiry['email']) . '</p>
                    <p><strong>Date:</strong> ' . htmlspecialchars($inquiry['created_at']) . '</p>
                </div>
                <p style="text-align: center; color: #999999; font-size: 12px; margin-top: 20px;">This is an automated message from Real Living</p>
            </div>
        ';
        
           
            $mail->send();
            $conn->query("UPDATE contact_inquiries SET sent_to_admin = 1 WHERE id = $inquiry_id");
            echo "<script>alert('Inquiry sent successfully!'); window.history.back();</script>";
        } catch (Exception $e) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }
    }
}
?>

