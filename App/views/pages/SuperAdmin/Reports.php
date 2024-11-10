<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Report</title>
    <link rel="stylesheet" href="Reports/Reports.css">
</head>
<body>

    <!-- Back button -->
    <button class="back-button" onclick="window.history.back()">Back</button>

    <h1>Monthly Report</h1>

    <!-- Report Table -->
    <table>
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
            <!-- Example Row with data (replace with dynamic PHP content) -->
            <?php
            // Example data array (replace with database query results)
            $reports = [
                ['report_id' => 1, 'bookings' => 120, 'cancellations' => 10, 'cancellation_fees' => 'Rs. 500', 'month' => 'October', 'revenue' => 'Rs. 12,000', 'profit' => 2000],
                ['report_id' => 2, 'bookings' => 95, 'cancellations' => 15, 'cancellation_fees' => 'Rs. 750', 'month' => 'November', 'revenue' => 'Rs. 9,500', 'profit' => -500],
                // Add more rows as needed
            ];

            foreach ($reports as $report) {
                echo "<tr>";
                echo "<td>{$report['report_id']}</td>";
                echo "<td>{$report['bookings']}</td>";
                echo "<td>{$report['cancellations']}</td>";
                echo "<td>{$report['cancellation_fees']}</td>";
                echo "<td>{$report['month']}</td>";
                echo "<td>{$report['revenue']}</td>";

                // Conditional styling for Profit/Loss
                $profitClass = $report['profit'] >= 0 ? "profit" : "loss";
                echo "<td class='$profitClass'>" . ($report['profit'] >= 0 ? "Rs. {$report['profit']}" : "- Rs. " . abs($report['profit'])) . "</td>";
                
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

</body>
</html>
