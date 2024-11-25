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

    <!-- Generate PDF Button -->
    <div style="text-align: center; margin-top: 20px;">
        <form action="<?php echo URLROOT; ?>/SuperAdminPages/generateReportPDF" method="post">
            <input type="hidden" id="selectedReportId" name="report_id" value="">
            <button type="submit" class="generate-pdf-button" disabled id="generatePdfButton">Generate PDF</button>
        </form>
    </div>

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
