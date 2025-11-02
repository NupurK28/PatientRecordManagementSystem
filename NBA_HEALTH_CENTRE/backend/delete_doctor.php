<?php
header('Content-Type: application/json');

// Allow DELETE method only
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['success' => false, 'message' => 'Only DELETE method allowed']);
    exit;
}

// Parse the query string for `id` parameter
parse_str($_SERVER['QUERY_STRING'], $params);
if (!isset($params['id']) || empty($params['id'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'message' => 'Missing doctor ID']);
    exit;
}

$doctorId = intval($params['id']);

if ($doctorId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid doctor ID']);
    exit;
}


require_once 'includes/Database.php'; 
$db = new Database();
$conn = $db->connect();
try {


    // Prepare DELETE query
    $stmt = $conn->prepare("DELETE FROM doctors WHERE id = ?");
    $stmt->bind_param("i", $doctorId);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Doctor deleted successfully.']);
        } else {
            // No row found with that ID
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Doctor not found.']);
        }
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to delete doctor.']);
    }

    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
?>
