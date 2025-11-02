<?php
header('Content-Type: application/json');
require_once 'includes/Database.php';
$db = new Database();
$conn = $db->connect();
$requiredFields = ['case_no', 'visit_date', 'reason', 'medicine', 'doctor'];

foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        echo json_encode(['success' => false, 'message' => "Field '$field' is required."]);
        exit;
    }
}

$case_no   = $_POST['case_no'];
$visit_date = $_POST['visit_date'];
$reason    = $_POST['reason'];
$medicine  = $_POST['medicine'];
$doctor    = $_POST['doctor'];
$notes     = $_POST['notes'] ?? '';

try {
    $stmt = $conn->prepare("INSERT INTO patient_history (case_no, visit_date, reason, medicine, doctor, notes) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$case_no, $visit_date, $reason, $medicine, $doctor, $notes]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database Error: ' . $e->getMessage()]);
}
