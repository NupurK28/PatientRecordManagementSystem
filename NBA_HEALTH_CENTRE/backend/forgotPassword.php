<?php
require_once 'includes/Database.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $newPassword = trim($_POST['new_password']);

    if (!empty($email) && !empty($newPassword)) {
        $db = new Database();
        $conn = $db->connect();

        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Check if the user exists
        $stmt = $conn->prepare("SELECT * FROM user WHERE email_or_phone = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Update password
            $update = $conn->prepare("UPDATE user SET password = ? WHERE email_or_phone = ?");
            $update->bind_param("ss", $hashedPassword, $email);
            if ($update->execute()) {
                echo "Password changed successfully!";
                 header("Location: ../login.html");
            } else {
                echo "Error updating password.";
            }
        } else {
            echo "Email not found in our records.";
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "Please fill in all fields.";
    }
}
?>
