<?php
session_start();
header('Content-Type: application/json');
require_once 'includes/Database.php';

if (!isset($_SESSION['case_no'])) {
    echo json_encode(['error' => 'User not logged in.']);
    exit;
}

$db = new Database();
$conn = $db->connect();
$caseNo = $_SESSION['case_no'];

$stmt = $conn->prepare("SELECT * FROM patient_history WHERE case_no = ?");
$stmt->bind_param("s", $caseNo);
$stmt->execute();
$result = $stmt->get_result();

$history = [];
while ($row = $result->fetch_assoc()) {
    $history[] = $row;
}

echo json_encode($history);