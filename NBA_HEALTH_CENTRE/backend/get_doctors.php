<?php
require_once 'includes/Database.php';
require_once 'models/Doctor.php';
    session_start();
    if (!isset($_SESSION['userEmailorPhone'])) {
        header("Location: login.html");
        exit;
    }
header('Content-Type: application/json');

$db = new Database();
$conn = $db->connect();

$doctor = new Doctor($conn);
$data = $doctor->getAllDoctors();

echo json_encode($data);
?>
