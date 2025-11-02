<?php
class Doctor {
    private $conn;
    private $table = "doctors";

    public function __construct($db) {
        $this->conn = $db;
    }

   public function addDoctor($data) {
    $sql = "INSERT INTO " . $this->table . " 
            (sl_no, name, gender, dob, specialty, phone, email, address, admission_date, photo) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        throw new Exception("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param(
        "isssssssss",  // i = integer, s = string
        $data['sl_no'],
        $data['name'],
        $data['gender'],
        $data['dob'],
        $data['specialty'],
        $data['phone'],
        $data['email'],
        $data['address'],
        $data['admission_date'],
        $data['photo']
    );

    return $stmt->execute();
}
    public function getAllDoctors() {
        $query = "SELECT id, name, gender, specialty FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        $doctors = [];
        while ($row = $result->fetch_assoc()) {
            $doctors[] = $row;
        }

        return $doctors;
    }
}
?>
