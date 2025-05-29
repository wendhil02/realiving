<?php

use PHPMailer\PHPMailer\Exception;

require '../../PHPMailer-master/src/Exception.php';
require '../../PHPMailer-master/src/PHPMailer.php';
require '../../PHPMailer-master/src/SMTP.php';

// Gmail SMTP Settings
define('GMAIL_SMTP_HOST', 'smtp.gmail.com');
define('GMAIL_SMTP_PORT', 587);
define('GMAIL_SMTP_SECURE', 'tls'); // or 'ssl' for port 465
define('GMAIL_USERNAME', 'wendhil10@gmail.com'); // Replace with your Gmail
define('GMAIL_PASSWORD', 'tnjqjsuopqlwzoug'); // Replace with your App Password
define('GMAIL_FROM_EMAIL', 'wendhil10@gmail.com'); // Replace with your Gmail
define('GMAIL_FROM_NAME', 'Realiving Design Center Corporation'); // Replace with your company name



// Function to send email using PHPMailer
function sendEmailWithGmail($to, $subject, $htmlBody, $fromName = GMAIL_FROM_NAME) {
    // Adjust the path based on where you installed PHPMailer
    // If using Composer: require_once 'vendor/autoload.php';
    // If manual installation: require_once 'path/to/PHPMailer/src/PHPMailer.php';
  include '../../vendor/autoload.php'; // Update this path if needed
    
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = GMAIL_SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = GMAIL_USERNAME;
        $mail->Password = GMAIL_PASSWORD;
        $mail->SMTPSecure = GMAIL_SMTP_SECURE;
        $mail->Port = GMAIL_SMTP_PORT;
        
        // Recipients
        $mail->setFrom(GMAIL_FROM_EMAIL, $fromName);
        $mail->addAddress($to);
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $htmlBody;
        
        $mail->send();
        return ['success' => true, 'message' => 'Email sent successfully'];
        
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Email error: ' . $mail->ErrorInfo];
    }
}

?>