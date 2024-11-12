<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Records</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/Bookings.css?v=<?php echo time(); ?>">
</head>
<body>
    <!-- Back button -->
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/home'">Back </button>

    <h1>Booking Records</h1>

    <!-- Booking table -->
    <table class="booking-table">
        <thead>
            <tr>
                <th>Book ID</th>
                <th>Booking Date</th>
                <th>Booking Time</th>
                <th>No. of Seats</th>
                <th>Amount</th>
                <th>State</th>
            </tr>
        </thead>
        <tbody id="booking-tbody">
            <?php
            // Example data; replace this with a database query to fetch real booking data
            $bookingData = [
                ['1001', '2024-11-05', '14:30', 2, '$40', 'Confirmed'],
                ['1002', '2024-11-06', '09:00', 1, '$20', 'Pending'],
                ['1003', '2024-11-07', '11:00', 3, '$60', 'Confirmed']
                // Add more data as needed
            ];

            foreach ($bookingData as $booking) {
                echo "<tr onclick='selectRow(this)'>";
                foreach ($booking as $item) {
                    echo "<td>$item</td>";
                }
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <script>
                // Function to navigate back to the previous page
        function goBack() {
            window.history.back();
        }

        // Function to select a row in the table
        function selectRow(row) {
            // Deselect any previously selected row
            const previouslySelectedRow = document.querySelector(".booking-table tr.selected");
            if (previouslySelectedRow) {
                previouslySelectedRow.classList.remove("selected");
            }

            // Select the clicked row
            row.classList.add("selected");
        }

    </script>
</body>
</html>
