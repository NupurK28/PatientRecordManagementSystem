<?php
session_start();
require_once 'includes/Database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['otp_verified']) && $_SESSION['otp_verified'] === true) {
    $newPassword = $_POST['newPassword'];
    $email = $_SESSION['reset_email'];

    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    $db = new Database();
    $conn = $db->connect();

    $stmt = $conn->prepare("UPDATE user SET password = ? WHERE email_or_phone = ?");
    $stmt->bind_param("ss", $hashedPassword, $email);
    
    if ($stmt->execute()) {
        echo "Password updated successfully.";
        session_unset(); // clear session
    } else {
        echo "Failed to update password.";
    }
} else {
    echo "OTP verification failed or session expired.";
}
?>
