<?php
require_once 'includes/Database.php'; // adjust path if needed

$db = new Database();
$conn = $db->connect();

$doctor = [
    'id' => '',
    'name' => '',
    'speciality' => '',
    'email' => '',
    'phone' => ''
];

// Handle update request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['doctorName'];
    $specialization = $_POST['speciality'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("UPDATE doctors SET name=?, specialty=?, email=?, phone=? WHERE id=?");
    $stmt->bind_param("ssssi", $name, $specialization, $email, $phone, $id);
    
    if ($stmt->execute()) {
        header("Location: ../doctorviewrecord.html");
        exit;
    } else {
        echo "Error updating doctor: " . $stmt->error;
    }
    $stmt->close();
}

// Load doctor data if ID is set
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM doctors WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $doctor = $result->fetch_assoc();
    } else {
        echo "Doctor not found.";
        exit;
    }
    $stmt->close();
} else {
    echo "No doctor ID provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Doctor</title>
    <link rel="stylesheet" href="../css/editdoctor.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
     <style>
        /* Minimal CSS for dropdown functionality */
        .dropdown-content {
            display: none;
            position: relative;
            padding-left: 15px;
            /* Adjust as per your design */
        }

        .dropdown-content.show {
            display: block;
        }

        .sidebar-menu a {
            display: block;
            padding: 10px 15px;
            color:  background-color:rgb(249, 249, 249);
            text-decoration: none;
        }

        .dropdown > a {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-logo">
            <img src="../img/web_logo.png" alt="Logo">
        </div>
        <div class="sidebar-menu">
            <h2>NBA HEALTH CENTRE</h2>
            <a href="../dashboard.html"><i class="fas fa-chart-line"></i> Dashboard</a>
            <div class="dropdown">
                <a href="#" onclick="toggleDropdown(event)"><i class="fas fa-user-injured"></i> Patients ▾</a>
                <div class="dropdown-content">
                    <a href="../addPatient.html"><i class="fas fa-plus-circle"></i> Add Record</a>
                    <a href="../viewRecord.html"><i class="fas fa-eye"></i> View Record</a>
                </div>
            </div>
            <div class="dropdown">
                <a href="#" onclick="toggleDropdown(event)"><i class="fas fa-user-doctor"></i> Doctors ▾</a>
                <div class="dropdown-content">
                    <a href="../adddoctor.html"><i class="fas fa-plus-circle"></i> Add Record</a>
                    <a href="../doctorviewrecord.html"><i class="fas fa-eye"></i> View Record</a>
                </div>
            </div>
            <div class="dropdown">
                <a href="#" onclick="toggleDropdown(event)" class="dropdown-toggle"><i class="fas fa-user-doctor"></i> Patient History ▾</a>
                <div class="dropdown-content">
                    <a href="../addPatientHistory.html"><i class="fas fa-book"></i> Add Patient History</a>
                    <a href="../patientHistory.html"><i class="fas fa-book"></i> View Patient History</a>
                </div>
            </div>
            <div class="sidebar-bottom">
                <a href="../Settings.php"><i class="fas fa-cog"></i> Settings</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="edit-container">
            <h1>Edit Doctor Details</h1>

            <form class="edit-form" method="POST" action="">
                <input type="hidden" name="id" value="<?php echo $doctor['id']; ?>">
                <div class="form-group">
                    <label for="doctorName">Doctor Name</label>
                    <input type="text" id="doctorName" name="doctorName" value="<?php echo htmlspecialchars($doctor['name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="specialization">Specialization</label>
                    <input type="text" id="speciality" name="speciality" value="<?php echo htmlspecialchars($doctor['specialty']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($doctor['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone No</label>
                    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($doctor['phone']); ?>" required>
                </div>
                <div class="form-buttons">
                    <button type="submit" class="submit-btn"><i class="fas fa-save"></i> Update</button>
                    <a href="../doctorviewrecord.html" class="cancel-btn"><i class="fas fa-arrow-left"></i> Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
    function toggleDropdown(event) {
        event.preventDefault();
        const dropdownContent = event.target.closest('.dropdown').querySelector('.dropdown-content');
        dropdownContent.classList.toggle('show');
    }

    // Close dropdowns if clicked outside
    window.onclick = function (e) {
        if (!e.target.closest('.dropdown')) {
            const dropdowns = document.querySelectorAll(".dropdown-content");
            dropdowns.forEach(el => el.classList.remove("show"));
        }
    };
</script>
</body>

</html>
