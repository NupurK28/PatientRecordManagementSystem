

<?php
session_start();
require_once 'backend/includes/Database.php';

if (!isset($_SESSION['userEmailorPhone'])) {
    header("Location: login.html");
    exit;
}

$db = new Database();
$conn = $db->connect();

$user = $_SESSION['userEmailorPhone'];

$stmt = $conn->prepare("SELECT * FROM user WHERE email_or_phone = ?");
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Account Settings - NBA Health Centre</title>
    <link rel="stylesheet" href="css/dashboard.css" />
    <link rel="stylesheet" href="css/settings.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body>
     <div class="sidebar">
        <div class="sidebar-logo">
            <img src="img/web_logo.png" alt="Logo">
        </div>
        <div class="sidebar-menu">
            <h2>NBA HEALTH CENTRE</h2>
            <a href="dashboard.html"><i class="fas fa-chart-line"></i> Dashboard</a>
            <div class="dropdown">
                <a href="#" onclick="toggleDropdown(event)"><i class="fas fa-user-injured"></i> Patients ▾</a>
                <div class="dropdown-content">
                    <a href="addPatient.html"><i class="fas fa-plus-circle"></i> Add Record</a>
                    <a href="viewRecord.html"><i class="fas fa-eye"></i> View Record</a>
                </div>
            </div>
            <div class="dropdown">
                <a href="#" onclick="toggleDropdown(event)"><i class="fas fa-user-doctor"></i> Doctors ▾</a>
                <div class="dropdown-content">
                    <a href="adddoctor.html"><i class="fas fa-plus-circle"></i> Add Record</a>
                    <a href="doctorviewrecord.html"><i class="fas fa-eye"></i> View Record</a>
                </div>
            </div>
            <div class="sidebar-bottom">
                <a href="Settings.php"><i class="fas fa-cog"></i> Settings</a>
                <a href="backend/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </div>

    <div class="settings-container">
        <div class="settings-content">
            <div class="settings-title">Account Settings</div>

            <form class="settings-form" method="POST" action="updateSettings.php" enctype="multipart/form-data">
                <!-- Show user's profile image or default -->

                <div class="form-group">
                    <label for="email">Email / Phone</label>
                    <input type="email" name="email" id="email"
                        value="<?= htmlspecialchars($userData['email_or_phone']) ?>" readonly />
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="***" readonly/>
                </div>

                <!-- <button type="submit" class="save-btn">Save Changes</button> -->
            </form>
        </div>
    </div>

    <script>
        function previewProfile(event) {
            const output = document.getElementById('profilePreview');
            output.src = URL.createObjectURL(event.target.files[0]);
        }

        function previewProfile(event) {
            const output = document.getElementById('profilePreview');
            output.src = URL.createObjectURL(event.target.files[0]);
        }


    </script>
</body>

</html>