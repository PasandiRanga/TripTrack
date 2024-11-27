<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Admin']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Report</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/Reports.css?v=<?php echo time(); ?>">
    <script>
        let selectedReportId = null;

        // Highlight selected row and store the report ID
        function selectRow(reportId, element) {
            // Remove highlight from any previously selected row
            const rows = document.querySelectorAll('.report-table tbody tr');
            rows.forEach(row => row.classList.remove('selected-row'));

            // Highlight the current row
            element.classList.add('selected-row');

            // Store the selected report ID
            selectedReportId = reportId;

            // Update hidden input value
            document.getElementById('selectedReportId').value = reportId;
        }
    </script>
    <style>
        /* Highlight selected row */
        .selected-row {
            background-color: #d3d3d3;
        }
    </style>
</head>
<body>

    <!-- Back button -->
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/home'">Back</button>

    <h1>Monthly Report</h1>

    <!-- Report Table -->
    <div class="report-table-container">
        <table class="report-table">
            <thead>
                <tr>
                    <th>Report ID</th>
                    <th>Number of Bookings</th>
                    <th>No of Cancellations</th>
                    <th>Cancellation Fees</th>
                    <th>Month</th>
                    <th>Monthly Revenue</th>
                    <th>Loss/Profit</th>
                </tr>
            </thead>
            <?php
        $reports = [
            [
                'report_id' => 1,
                'bookings' => 120,
                'cancellations' => 15,
                'cancellation_fees' => 5000,
                'month' => 'January',
                'revenue' => 200000,
                'profit' => 180000
            ],
            [
                'report_id' => 2,
                'bookings' => 95,
                'cancellations' => 10,
                'cancellation_fees' => 3000,
                'month' => 'February',
                'revenue' => 150000,
                'profit' => 120000
            ],
            [
                'report_id' => 3,
                'bookings' => 110,
                'cancellations' => 5,
                'cancellation_fees' => 1000,
                'month' => 'March',
                'revenue' => 180000,
                'profit' => 175000
            ],
            [
                'report_id' => 4,
                'bookings' => 80,
                'cancellations' => 20,
                'cancellation_fees' => 7000,
                'month' => 'April',
                'revenue' => 130000,
                'profit' => 80000
            ],
            [
                'report_id' => 5,
                'bookings' => 150,
                'cancellations' => 30,
                'cancellation_fees' => 15000,
                'month' => 'May',
                'revenue' => 250000,
                'profit' => 230000
            ]
        ];
        ?>

            <tbody>
                <?php foreach ($reports as $report): ?>
                    <tr onclick="selectRow(<?php echo $report['report_id']; ?>, this)">
                        <td><?php echo $report['report_id']; ?></td>
                        <td><?php echo $report['bookings']; ?></td>
                        <td><?php echo $report['cancellations']; ?></td>
                        <td><?php echo $report['cancellation_fees']; ?></td>
                        <td><?php echo $report['month']; ?></td>
                        <td><?php echo $report['revenue']; ?></td>
                        <td class="<?php echo $report['profit'] >= 0 ? 'profit' : 'loss'; ?>">
                            <?php echo $report['profit'] >= 0 ? "Rs. {$report['profit']}" : "- Rs. " . abs($report['profit']); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Generate PDF Button 
    <div style="text-align: center; margin-top: 20px;">
        <form action="<//?php echo URLROOT; ?>/SuperAdminPages/generateReportPDF" method="post">
            <input type="hidden" id="selectedReportId" name="report_id" value="">
            <button type="submit" class="generate-pdf-button" disabled id="generatePdfButton">Generate PDF</button>
        </form>
    </div>
                -->
    <script>
        // Enable the Generate PDF button only when a row is selected
        document.querySelector('.generate-pdf-button').disabled = true;
        document.querySelectorAll('.report-table tbody tr').forEach(row => {
            row.addEventListener('click', () => {
                document.querySelector('.generate-pdf-button').disabled = false;
            });
        });
    </script>

</body>
</html>
