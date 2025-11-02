<?php
require 'includes/Database.php'; 
$db = new Database();
$conn = $db->connect();
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM doctors WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        echo json_encode($res->fetch_assoc());
    } else {
        echo json_encode(["error" => "No doctor found"]);
    }
} else {
    echo json_encode(["error" => "Invalid request"]);
}
?>
