<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Admin']);
?>
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
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/home'">Back</button>

    <h1>Bus Schedules</h1>

    <!-- Add Schedule Button -->
    <div class="top-actions">
        <button class="add-schedule-btn" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/addschedule'">Add Schedule</button>
    </div>

    <!-- Schedule table -->
    <div class="table-container">
        <table class="schedule-table">
            <thead>
                <tr>
                    <th>Schedule ID</th>
                    <th>License ID</th>
                    <th>Direction</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Departure Time</th>
                    <th>Arrival Time</th>
                    <th>Duration</th>
                    <th>Available Seats</th>
                    <th>Booked Seats</th>
                    <th>Update</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Sample data for demonstration purposes
                /*
                $schedules = [
                    ['scheduleId' => '1', 'licenseId' => 'L123', 'date' => '2024-11-10', 'departureTime' => '6:00 AM', 'arrivalTime' => '3:00 PM', 'duration' => '9 hours 30 mins', 'price' => 'Rs. 700', 'availableSeats' => 50, 'bookedSeats' => 10],
                    ['scheduleId' => '2', 'licenseId' => 'L456', 'date' => '2024-11-11', 'departureTime' => '7:00 AM', 'arrivalTime' => '4:00 PM', 'duration' => '9 hours', 'price' => 'Rs. 750', 'availableSeats' => 45, 'bookedSeats' => 15]
                ]; */
                if(isset($data['schedule']) && is_array($data['schedule'])){
                    foreach ($data['schedule'] as $schedule) {
                        echo "<tr onclick='selectRow(this)'>";
                        echo "<td>{$schedule['scheduleId']}</td>";
                        echo "<td>{$schedule['License_id']}</td>";
                        echo "<td>{$schedule['direction']}</td>";
                        echo "<td>{$schedule['type']}</td>";
                        echo "<td>{$schedule['date']}</td>";
                        echo "<td>{$schedule['departureTime']}</td>";
                        echo "<td>{$schedule['arrivalTime']}</td>";
                        echo "<td>{$schedule['duration']}</td>";
                        echo "<td>{$schedule['availableSeats']}</td>";
                        echo "<td>{$schedule['bookedSeats']}</td>";
                        echo "<td><button class='update-btn' onclick='updateSchedule({$schedule['scheduleId']})'>Update</button></td>";
                        echo "<td><button class='delete-btn' onclick='deleteSchedule(\"{$schedule['scheduleId']}\")'>Delete</button></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='14'>No bus data available.</td></tr>";
                }
                
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function selectRow(row) {
            const selectedRow = document.querySelector(".schedule-table tr.selected");
            if (selectedRow) {
                selectedRow.classList.remove("selected");
            }
            row.classList.add("selected");
        }

        function updateSchedule(scheduleId) {
            window.location.href = `update_schedule.php?scheduleId=${scheduleId}`;
        }

        function deleteSchedule(scheduleId) {
            if (confirm("Are you sure you want to delete this schedule?")) {
                fetch('<?php echo URLROOT; ?>/SuperAdminPages/deleteSchedule', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json'},
                    body: JSON.stringify({scheduleId})
                })
                .then(response => response.json())
                .then(data => {
                    if(data.status === 'success'){
                        const rows = Array.from(document.querySelectorAll("table.schedule-table tbody tr"));
                        const row = rows.find(row => row.cells[0].innerText.trim() === String(scheduleId));
                        if(row){
                            row.remove();
                        }
                        alert(data.message);
                    } else {
                        alert(data.message);
                    }
                })
                .catch(() => alert('Error deleting the schedule.'));
            }
        }
    </script>
</body>
</html>
