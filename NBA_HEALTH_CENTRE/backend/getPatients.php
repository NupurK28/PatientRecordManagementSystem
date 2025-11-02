<?php
require_once 'includes/Database.php';
require_once 'models/Patient.php';
    session_start();
    if (!isset($_SESSION['userEmailorPhone'])) {
        header("Location: login.html");
        exit;
    }
header('Content-Type: application/json');

$db = new Database();
$conn = $db->connect();

$patient = new Patient($conn);
$data = $patient->getAllPatients();

echo json_encode($data);
?>