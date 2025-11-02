<?php
    require_once 'includes/Database.php';
    session_start();

    class Login{
        private $conn;

        public function __construct($db){
            $this->conn = $db;
        }

        public function authenticate($email,$password){
            $stmt = $this->conn->prepare("SELECT email_or_phone, password FROM user WHERE email_or_phone = ?");
            $stmt->bind_param("s",$email);
            $stmt->execute();
            $stmt->store_result();

            if($stmt->num_rows === 1){
                $stmt->bind_result($email, $hashedPassword);
                $stmt->fetch();

                if(password_verify($password,$hashedPassword)) {
                    $_SESSION['userEmailorPhone'] = $email;
                    echo "<script>alert('Login Successful'); 
                    window.location.href = '../dashboard.html';
                    </script>";
                    exit;
                }
            }
            echo "<script>alert('Invalid Email or Password'); 
                    window.location.href = '../login.html';
                    </script>";
                    exit;
        }
    }

       //Handling the Request
       if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $db = new Database();
        $conn = $db->connect();

        $login = new Login($conn);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        $login->authenticate($email, $password);
    }
?>