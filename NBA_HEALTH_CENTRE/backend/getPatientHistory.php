<?php
header('Content-Type: application/json');
require_once 'includes/Database.php';

$db = new Database();
$conn = $db->connect();

if (!isset($_GET['case_no'])) {
    echo json_encode(['error' => 'Case number is required']);
    exit;
}

$caseNo = $_GET['case_no'];

try {
    $stmt = $conn->prepare("SELECT * FROM patient_history WHERE case_no = ?");
    $stmt->bind_param("s", $caseNo);
    $stmt->execute();

    $result = $stmt->get_result();
    $records = [];

    while ($row = $result->fetch_assoc()) {
        $records[] = $row;
    }

    echo json_encode($records);
    
    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
