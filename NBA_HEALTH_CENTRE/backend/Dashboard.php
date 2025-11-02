<?php
    require_once 'includes/Database.php';
    session_start();
    if (!isset($_SESSION['userEmailorPhone'])) {
        header("Location: login.html");
        exit;
    }


    class DashboardData{
        private $conn;

        public function __construct($db){
            $this->conn = $db;
        }

        public function getStats(){
            $data = [];

            //Total Patients
            $result = $this->conn->query("SELECT COUNT(*) as total FROM patients");
            $row = $result->fetch_assoc();
            $data['totalPatients'] = $row['total'];

            //active Patients
            $result = $this->conn->query("SELECT COUNT(*) as active FROM patients WHERE status = 'Active'");
            $row = $result->fetch_assoc();
            $data['activeCases'] = $row['active'];

            //closed cases
            $result = $this->conn->query("SELECT COUNT(*) as closed FROM patients WHERE status = 'Closed'");
            $row = $result->fetch_assoc();
            $data['closedCases'] = $row['closed'];

            return $data;
        }
    }
    header('Content-Type: application/json');

    if($_SERVER['REQUEST_METHOD'] === 'GET'){
        $db = new Database();
        $conn = $db->connect();

        $dashboard = new DashboardData($conn);
        $stats = $dashboard->getStats();

        echo json_encode($stats);
    }
?>