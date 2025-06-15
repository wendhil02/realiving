<?php
include '../connection/connection.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';
require '../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $reply = $_POST['reply'];

    // Get original message details
    $stmt = $conn->prepare("SELECT name, email, message FROM inquiries WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    // Update reply
    $update = $conn->prepare("UPDATE inquiries SET reply = ? WHERE id = ?");
    $update->bind_param("si", $reply, $id);
    $update->execute();

    // Send email reply
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'wendhil10@gmail.com';
        $mail->Password = 'tnjqjsuopqlwzoug';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('your@email.com', 'Admin');
        $mail->addAddress($result['email'], $result['name']);
        $mail->Subject = "Reply to your inquiry";
        $mail->Body = "Your message: " . $result['message'] . "\n\nReply: " . $reply;

        $mail->send();
        header("Location: admin_inquiries.php");
    } catch (Exception $e) {
        echo "Mail Error: {$mail->ErrorInfo}";
    }
}
?>
