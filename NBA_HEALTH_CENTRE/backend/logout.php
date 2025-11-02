<?php
if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
    session_start();
    session_destroy();
    header("Location: ../login.html");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Confirm Logout</title>
  <script>

    const confirmLogout = confirm("Do you really want to log out?");
    if (confirmLogout) {
      
      window.location.href = "logout.php?confirm=yes";
    } else {
    
      window.history.back();
    }
  </script>
</head>
<body>
</body>
</html>
  