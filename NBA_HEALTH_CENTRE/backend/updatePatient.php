<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'includes/Database.php'; // adjust path if needed
header('Content-Type: application/json');
$db = new Database();
$conn = $db->connect();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect POSTed data
    $case_no = $_POST['case_no'] ?? '';
    $name = $_POST['name'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $guardian = $_POST['guardian_name'] ?? '';
    $address = $_POST['address'] ?? '';
    $doctor_assigned = $_POST['doctor_assigned'] ?? '';

    // Basic validation
    if (empty($case_no) || empty($name) || empty($gender) || empty($phone) || empty($doctor_assigned)) {
        echo json_encode([
            "error" => true,
            "message" => "Required fields are missing."
        ]);
        exit;
    }

    try {
        $stmt = $conn->prepare("UPDATE patients 
            SET name = ?, gender = ?, phone = ?, email = ?, guardian_name = ?, address = ?, doctor_assigned = ?
            WHERE case_no = ?");

        $stmt->bind_param("ssssssss", $name, $gender, $phone, $email, $guardian, $address, $doctor_assigned, $case_no);

        if ($stmt->execute()) {
            echo json_encode([
                "error" => false,
                "message" => "Patient updated successfully."
            ]);
        } else {
            echo json_encode([
                "error" => true,
                "message" => "Failed to update patient."
            ]);
        }

        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        echo json_encode([
            "error" => true,
            "message" => "Server error: " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        "error" => true,
        "message" => "Invalid request method."
    ]);
}
