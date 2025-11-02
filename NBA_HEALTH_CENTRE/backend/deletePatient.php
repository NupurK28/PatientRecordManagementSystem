<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

require_once 'includes/Database.php';

$db = new Database();
$conn = $db->connect();

if (!$conn) {
    echo json_encode(['error' => true, 'message' => 'Database connection failed']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => true, 'message' => 'Invalid request method']);
    exit;
}

$case_no = $_POST['case_no'] ?? '';

if (empty($case_no)) {
    echo json_encode(['error' => true, 'message' => 'Case number is required']);
    exit;
}

// Delete related patient_history entries first
$historyStmt = $conn->prepare("DELETE FROM patient_history WHERE case_no = ?");
if (!$historyStmt) {
    echo json_encode(['error' => true, 'message' => 'Prepare failed (history delete): ' . $conn->error]);
    exit;
}
$historyStmt->bind_param("s", $case_no);

if (!$historyStmt->execute()) {
    echo json_encode(['error' => true, 'message' => 'Failed to delete patient history: ' . $historyStmt->error]);
    $historyStmt->close();
    $conn->close();
    exit;
}
$historyStmt->close();

// Now delete patient record
$stmt = $conn->prepare("DELETE FROM patients WHERE case_no = ?");
if (!$stmt) {
    echo json_encode(['error' => true, 'message' => 'Prepare failed (patient delete): ' . $conn->error]);
    exit;
}
$stmt->bind_param("s", $case_no);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['error' => false, 'message' => 'Patient deleted successfully']);
    } else {
        echo json_encode(['error' => true, 'message' => 'No patient found with that Case No']);
    }
} else {
    echo json_encode(['error' => true, 'message' => 'Failed to delete patient: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
