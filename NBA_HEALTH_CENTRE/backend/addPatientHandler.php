<?php 
    require_once 'includes/Database.php';
    require_once 'models/Patient.php';
    session_start();
    if (!isset($_SESSION['userEmailorPhone'])) {
        header("Location: login.html");
        exit;
    }
    if($_SERVER['REQUEST_METHOD']=== 'POST'){
        $db = new Database();
        $conn = $db->connect();

        $patients = new Patient($conn);

        //Handle Photo Upload
        $photoName = $_FILES['photo']['name'];
        $photoName = str_replace(' ','_',$photoName);
        $target = "upload/" . $photoName;
        move_uploaded_file($_FILES['photo']['tmp_name'],$target);

        $data = [
            'caseNo' => $_POST['caseNo'],
            'name' => $_POST['name'],
            'gender' => $_POST['gender'],
            'dob' => $_POST['dob'],
            'guardian' => $_POST['guardian'],
            'phone' => $_POST['phone'],
            'email' => $_POST['email'],
            'address' => $_POST['address'],
            'doctor' => $_POST['doctor'],
            'symptoms' => $_POST['symptoms'],
            'admission' => $_POST['admission'],
            'blood' => $_POST['blood'],
            'history' => $_POST['history']
        ];
        if($patients->addPatient($data)) {
            echo "<script>
                alert('Patient record added successfully'); 
                window.location.href = '../dashboard.html';
            </script>";
        }
        else{
            echo "<script>
                alert('Failed to  add patient record'); 
                window.location.href = '../addPatient.html';
            </script>";
        }
    }
?>