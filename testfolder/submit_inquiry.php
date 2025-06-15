<?php
include '../connection/connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';
require '../vendor/autoload.php'; // Composer PHPMailer

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name']; $email = $_POST['email']; $message = $_POST['message'];

    // Save to DB
    $stmt = $conn->prepare("INSERT INTO inquiries (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);
    $stmt->execute();

    // Send email to admin
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Set your SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'wendhil10@gmail.com';
        $mail->Password = 'tnjqjsuopqlwzoug';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom($email, $name);
        $mail->addAddress('admin@email.com', 'Admin');
        $mail->Subject = "New Inquiry from $name";
        $mail->Body = $message;

        $mail->send();
        echo "Inquiry sent!";
    } catch (Exception $e) {
        echo "Mail Error: {$mail->ErrorInfo}";
    }
}
?>
