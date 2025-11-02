<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userOtp = $_POST['otp'];
    $storedOtp = $_SESSION['reset_otp'] ?? '';

    if ($userOtp == $storedOtp) {
        $_SESSION['otp_verified'] = true;
        echo "OTP verified. You can now reset your password.";
    } else {
        echo "Incorrect OTP.";
    }
}
?>
