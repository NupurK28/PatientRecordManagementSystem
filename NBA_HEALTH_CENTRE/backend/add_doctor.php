<?php
// Enable error reporting and set response to JSON
    session_start();
    if (!isset($_SESSION['userEmailorPhone'])) {
        header("Location: login.html");
        exit;
    }
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

require_once 'includes/Database.php';
require_once 'models/Doctor.php';

// Connect to DB
$db = new Database();
$conn = $db->connect();
$doctor = new Doctor($conn);

// Handle file upload
$photoName = '';
if (!empty($_FILES['photo']['name'])) {
    $photoName = time() . '_' . basename($_FILES['photo']['name']);
    $target = "upload/" . $photoName;
    move_uploaded_file($_FILES['photo']['tmp_name'], $target);
}

// Prepare data
$data = [
    'sl_no' => $_POST['caseNo'] ?? '',
    'name' => $_POST['name'] ?? '',
    'gender' => $_POST['gender'] ?? '',
    'dob' => $_POST['dob'] ?? '',
    'specialty' => $_POST['specialty'] ?? '',
    'phone' => $_POST['phone'] ?? '',
    'email' => $_POST['email'] ?? '',
    'address' => $_POST['address'] ?? '',
    'admission_date' => $_POST['admission'] ?? '',
    'photo' => $photoName
];

// Add doctor and return JSON
try {
    if ($doctor->addDoctor($data)) {
        echo json_encode(["message" => "Doctor added successfully"]);
        exit;
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Failed to add doctor"]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
