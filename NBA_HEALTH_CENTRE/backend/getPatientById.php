<?php
require_once('includes/Database.php');
    session_start();
    if (!isset($_SESSION['userEmailorPhone'])) {
        header("Location: login.html");
        exit;
    }
header('Content-Type: application/json');

$db = new Database();
$conn = $db->connect();

$caseNo = $_GET['case_no'] ?? '';

if (!$caseNo) {
    echo json_encode(['error' => 'No case number provided.']);
    exit;
}

$sql = "SELECT * FROM patients WHERE case_no = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $caseNo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['error' => 'Patient not found.']);
} else {
    $patient = $result->fetch_assoc();
    echo json_encode($patient);
}

$stmt->close();
$conn->close();
?>
