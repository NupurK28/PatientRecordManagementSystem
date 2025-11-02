<?php
require_once 'includes/Database.php';

class Signup {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register($emailOrPhone, $password, $confirmPassword) {
        // ❌ Email or phone number validation
        if (!filter_var($emailOrPhone, FILTER_VALIDATE_EMAIL) && !preg_match('/^[6-9]\d{9}$/', $emailOrPhone)) {
            echo "<script>alert('Invalid input! Please enter a valid email or a 10-digit Indian phone number.'); window.location.href='../signup.html';</script>";
            exit;
        }

        // ❌ Password mismatch
        if ($password !== $confirmPassword) {
            echo "<script>alert('Passwords do not match.'); window.location.href='../signup.html';</script>";
            exit;
        }

        // Check if user already exists
        $stmt = $this->conn->prepare("SELECT id FROM user WHERE email_or_phone = ?");
        $stmt->bind_param("s", $emailOrPhone);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "<script>alert('User already exists. Please login or use a different email/phone number.'); window.location.href='../signup.html';</script>";
            exit;
        }

        // ✅ Insert into database
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $insert = $this->conn->prepare("INSERT INTO user (email_or_phone, password) VALUES (?, ?)");
        $insert->bind_param("ss", $emailOrPhone, $hashedPassword);

        if ($insert->execute()) {
            echo "<script>alert('Signup Successful! You can now log in.'); window.location.href='../login.html';</script>";
        } else {
            echo "<script>alert('Something went wrong. Please try again.'); window.location.href='../signup.html';</script>";
        }
    }
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Database();
    $conn = $db->connect();

    $signup = new Signup($conn);

    $emailOrPhone = trim($_POST['emailOrPhone']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);

    $signup->register($emailOrPhone, $password, $confirmPassword);
}
?>