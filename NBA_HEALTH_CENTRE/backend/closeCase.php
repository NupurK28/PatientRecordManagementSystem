<?php
header("Content-Type: application/json");
    session_start();
    if (!isset($_SESSION['userEmailorPhone'])) {
        header("Location: login.html");
        exit;
    }
include 'includes/Database.php'; 
$db = new Database();
$conn = $db->connect();
$case_no = $_POST['case_no'] ?? '';

if (empty($case_no)) {
    echo json_encode(["error" => true, "message" => "Missing case number."]);
    exit;
}

$sql = "UPDATE patients SET status='closed' WHERE case_no=?";


$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $case_no);

if ($stmt->execute()) {
    echo json_encode(["error" => false, "message" => "Case closed successfully."]);
} else {
    echo json_encode(["error" => true, "message" => "Failed to close the case."]);
}

$stmt->close();
$conn->close();
?>
