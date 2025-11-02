<!-- <?php
session_start();
require_once 'includes/Database.php';

if (!isset($_SESSION['userEmailorPhone'])) {
    header("Location: login.html");
    exit;
}

$db = new Database();
$conn = $db->connect();

$user = $_SESSION['userEmailorPhone'];

$stmt = $conn->prepare("SELECT * FROM user WHERE email_or_phone = ?");
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account Settings - NBA Health Centre</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/settings.css">
</head>
<body>
    <div class="sidebar">
        <!-- Sidebar code here -->
    <!-- </div>

    <div class="main-content">
        <h2>Account Settings</h2>
        <div class="settings-card">
            <p><strong>Email / Phone:</strong> <?= htmlspecialchars($userData['email_or_phone']) ?></p>
            <p><strong>Registered On:</strong> <?= htmlspecialchars($userData['created_at']) ?></p>
        </div>
    </div>
</body>
</html> --> 

<?php
session_start();
require_once 'includes/Database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['userEmailorPhone'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$db = new Database();
$conn = $db->connect();

$user = $_SESSION['userEmailorPhone'];

$stmt = $conn->prepare("SELECT username, email_or_phone, profile_image FROM user WHERE email_or_phone = ?");
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();

if (!$userData) {
    echo json_encode(['error' => 'User not found']);
    exit;
}

echo json_encode($userData);

