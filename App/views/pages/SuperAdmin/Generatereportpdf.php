<?php
require('fpdf/fpdf.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['report_id'])) {
    $reportId = $_POST['report_id'];

    // Fetch report details from the database (replace with your DB logic)
    $report = getReportById($reportId); // Replace with your query

    if ($report) {
        class PDF extends FPDF {
            function Header() {
                $this->SetFont('Arial', 'B', 14);
                $this->Cell(0, 10, 'Detailed Monthly Report', 0, 1, 'C');
                $this->Ln(10);
            }

            function ReportDetails($report) {
                $this->SetFont('Arial', '', 12);

                foreach ($report as $key => $value) {
                    $this->Cell(50, 10, ucfirst(str_replace('_', ' ', $key)) . ':', 1);
                    $this->Cell(100, 10, $value, 1);
                    $this->Ln();
                }
            }
        }

        // Generate PDF
        $pdf = new PDF();
        $pdf->AddPage();
        $pdf->ReportDetails($report);
        $pdf->Output();
    } else {
        echo "No report found with the given ID.";
    }
} else {
    echo "Invalid request.";
}

// Example function to fetch report details (replace with your database query)
function getReportById($id) {
    $reports = [
        1 => ['report_id' => 1, 'bookings' => 120, 'cancellations' => 10, 'cancellation_fees' => 'Rs. 500', 'month' => 'October', 'revenue' => 'Rs. 12,000', 'profit' => 'Rs. 2000'],
        2 => ['report_id' => 2, 'bookings' => 95, 'cancellations' => 15, 'cancellation_fees' => 'Rs. 750', 'month' => 'November', 'revenue' => 'Rs. 9,500', 'profit' => '- Rs. 500'],
    ];
    return $reports[$id] ?? null;
}
?>
