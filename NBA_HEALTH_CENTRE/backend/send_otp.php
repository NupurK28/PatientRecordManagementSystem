<?php
session_start();
require_once 'includes/Database.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    $db = new Database();
    $conn = $db->connect();

    $stmt = $conn->prepare("SELECT * FROM user WHERE email_or_phone = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $otp = rand(100000, 999999);
        $_SESSION['reset_email'] = $email;
        $_SESSION['reset_otp'] = $otp;

        // Send OTP using PHPMailer
        $mail = new PHPMailer(true);
        try {
            // SMTP config
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'yourgmail@gmail.com'; // 🔒 Your Gmail
            $mail->Password = 'your-app-password';   // 🔒 App Password (not Gmail password)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Mail content
            $mail->setFrom('yourgmail@gmail.com', 'NBA Health Centre');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'OTP for Password Reset';
            $mail->Body = "Your OTP is: <strong>$otp</strong>";

            $mail->send();
            echo "OTP sent to your email.";
        } catch (Exception $e) {
            echo "Failed to send OTP. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Email not registered.";
    }
}
?>
