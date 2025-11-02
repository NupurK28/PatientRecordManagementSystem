<?php 
    require_once 'includes/Database.php';

    class Patient {
        private $conn;
        // private $table = patients;

        public function __construct($db){
            $this->conn = $db;
        }

        public function addPatient($data) {
            $sql = $this->conn->prepare("INSERT INTO patients 
            (case_no, name, gender, dob, guardian_name, phone, email, address, doctor_assigned , symptoms, addmission_date, blood_group, photo, medical_history )
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)
            ");
            $sql->bind_param("ssssssssssssss",
                $data['caseNo'],
                $data['name'],
                $data['gender'],
                $data['dob'],
                $data['guardian'],
                $data['phone'],
                $data['email'],
                $data['address'],
                $data['doctor'],
                $data['symptoms'],
                $data['admission'],
                $data['blood'],
                $data['photo'],
                $data['history']
            );
            return $sql->execute();
        }

        public function getAllPatients() {
            $result = $this->conn->query("SELECT case_no, name, gender, phone, doctor_assigned FROM patients");
            $patients = [];
        
            while ($row = $result->fetch_assoc()) {
                $patients[] = $row;
            }
        
            return $patients;
        }
    }

?>