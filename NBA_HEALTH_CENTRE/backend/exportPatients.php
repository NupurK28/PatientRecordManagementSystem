<?php
ob_start(); // Start output buffering
    session_start();
    if (!isset($_SESSION['userEmailorPhone'])) {
        header("Location: login.html");
        exit;
    }
require('fpdf/fpdf.php');
require('includes/Database.php'); // Update this path if needed

class PDF extends FPDF
{
    function Header()
    {
        // Logo
        $this->Image('../assets/logo.png', 10, 6, 20); // Adjust logo path & size
        // Clinic Name
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, 'NBA Health Centre - Patient Report', 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer()
    {
        // Page number
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 10);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    function PatientDetails($patient)
    {
        $this->SetFont('Arial', '', 12);

        $this->Cell(50, 10, 'Case Number:', 0);
        $this->Cell(100, 10, $patient['case_no'] ?? 'N/A', 0);
        $this->Ln();

        $this->Cell(50, 10, 'Name of Patient:', 0);
        $this->Cell(100, 10, $patient['name'] ?? 'N/A', 0);
        $this->Ln();

        $this->Cell(50, 10, 'Gender:', 0);
        $this->Cell(100, 10, $patient['gender'] ?? 'N/A', 0);
        $this->Ln();

        $this->Cell(50, 10, 'Phone:', 0);
        $this->Cell(100, 10, $patient['phone'] ?? 'N/A', 0);
        $this->Ln();

        $this->Cell(50, 10, 'Address:', 0);
        $this->MultiCell(130, 10, $patient['address'] ?? 'N/A');
        $this->Ln();

        $this->Cell(50, 10, 'Guardian Name:', 0);
        $this->Cell(100, 10, $patient['guardian'] ?? 'N/A', 0);
        $this->Ln();

        $this->Cell(50, 10, 'Email:', 0);
        $this->Cell(100, 10, $patient['email'] ?? 'N/A', 0);
        $this->Ln();

        $this->Cell(50, 10, 'Doctor Assigned:', 0);
        $this->Cell(100, 10, $patient['doctor_assigned'] ?? 'N/A', 0);
        $this->Ln(15);
    }
}

// Fetch data from DB
$db = new Database();
$conn = $db->connect();
$sql = "SELECT * FROM patients";
$result = mysqli_query($conn, $sql);

// Create PDF
$pdf = new PDF();
$pdf->AddPage();

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $pdf->PatientDetails($row);
        $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY()); // Horizontal line
        $pdf->Ln(5);
    }
} else {
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'No patient records found.', 0, 1, 'C');
}

// Output PDF
ob_end_clean(); // Clear the output buffer
$pdf->Output('D', 'All_Patient_Reports_' . date('Ymd') . '.pdf');
?>
