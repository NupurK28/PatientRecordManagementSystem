<?php
session_start();
require_once 'includes/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $case_no = $_POST['case_no'] ?? '';
    $email = $_POST['email'] ?? '';

    $db = new Database();
    $conn = $db->connect();

    $stmt = $conn->prepare("SELECT * FROM patients WHERE case_no = ? AND email = ?");
    $stmt->bind_param("ss", $case_no, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['case_no'] = $case_no;
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid credentials.']);
    }
}