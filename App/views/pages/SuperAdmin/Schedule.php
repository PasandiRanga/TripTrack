<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Schedules</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/Schedule.css?v=<?php echo time(); ?>">
</head>
<body>
    <!-- Back button -->
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/home'">Back </button>

    <h1>Bus Schedules</h1>

    <!-- Schedule table -->
    <div class="table-container">
        <table class="schedule-table">
            <thead>
                <tr>
                    <th>Schedule ID</th>
                    <th>Date</th>
                    <th>Departure Time</th>
                    <th>Arrival Time</th>
                    <th>Duration</th>
                    <th>Price</th>
                    <th>Bus ID</th>
                    <th>Bus Number</th>
                    <th>Route</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Sample data for demonstration purposes
                $schedules = [
                    ['scheduleId' => '1', 'date' => '2024-11-10', 'departureTime' => '6:00 AM', 'arrivalTime' => '3:00 PM', 'duration' => '9 hours 30 mins', 'price' => 'Rs. 700', 'busId' => '1', 'busNumber' => 'NA-1234', 'route' => 'Colombo - Ampara'],
                    ['scheduleId' => '2', 'date' => '2024-11-11', 'departureTime' => '7:00 AM', 'arrivalTime' => '4:00 PM', 'duration' => '9 hours', 'price' => 'Rs. 750', 'busId' => '2', 'busNumber' => 'NA-5678', 'route' => 'Colombo - Galle']
                    // Add more rows as needed
                ];

                // Loop through the schedule data and create table rows
                foreach ($schedules as $schedule) {
                    echo "<tr onclick='selectRow(this)'>";
                    echo "<td>{$schedule['scheduleId']}</td>";
                    echo "<td>{$schedule['date']}</td>";
                    echo "<td>{$schedule['departureTime']}</td>";
                    echo "<td>{$schedule['arrivalTime']}</td>";
                    echo "<td>{$schedule['duration']}</td>";
                    echo "<td>{$schedule['price']}</td>";
                    echo "<td>{$schedule['busId']}</td>";
                    echo "<td>{$schedule['busNumber']}</td>";
                    echo "<td>{$schedule['route']}</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Action buttons -->
    <div class="button-group">
        <button onclick="addSchedule()">Add Schedule</button>
        <button onclick="updateSchedule()">Update</button>
        <button onclick="deleteSchedule()">Delete</button>
    </div>

    <script>
                // Function to select a row in the table
        function selectRow(row) {
            // Deselect any previously selected row
            const selectedRow = document.querySelector(".schedule-table tr.selected");
            if (selectedRow) {
                selectedRow.classList.remove("selected");
            }
            row.classList.add("selected");
        }

        // Function to handle adding a schedule
        function addSchedule() {
            window.location.href = "Schedule/Add_schedule.php";
        }

        // Function to handle updating a selected schedule
        function updateSchedule() {
            const selectedRow = document.querySelector(".schedule-table tr.selected");
            if (selectedRow) {
                const scheduleId = selectedRow.cells[0].textContent;
                window.location.href = `update_schedule.php?scheduleId=${scheduleId}`;
            } else {
                alert("Please select a schedule to update.");
            }
        }

        // Function to handle deleting a selected schedule
        function deleteSchedule() {
            const selectedRow = document.querySelector(".schedule-table tr.selected");
            if (selectedRow) {
                const scheduleId = selectedRow.cells[0].textContent;
                if (confirm(`Are you sure you want to delete schedule ID ${scheduleId}?`)) {
                    // Add AJAX request or redirection to delete page
                    alert(`Schedule ID ${scheduleId} has been deleted.`);
                }
            } else {
                alert("Please select a schedule to delete.");
            }
        }

    </script>
</body>
</html>
