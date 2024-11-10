<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Schedules</title>
    <link rel="stylesheet" href="/Schedule/Schedule.css">
</head>
<body>
    <!-- Back button -->
    <button class="back-button" onclick="window.history.back()">Back</button>

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

    <script src="Schedule/Schedule.js"></script>
</body>
</html>
