<?php
header('Content-Type: application/json');
require_once 'includes/Database.php';
$db = new Database();
$conn = $db->connect();
if (!isset($_GET['case_no']) || empty($_GET['case_no'])) {
    echo json_encode(['exists' => false]);
    exit;
}

$case_no = $_GET['case_no'];

try {
    $stmt = $conn->prepare("SELECT 1 FROM patients WHERE case_no = ?");
    $stmt->execute([$case_no]);

    echo json_encode(['exists' => $stmt->fetch() ? true : false]);
} catch (PDOException $e) {
    echo json_encode(['exists' => false, 'error' => $e->getMessage()]);
}
