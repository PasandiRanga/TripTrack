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
                        if ($schedule['bookedSeats'] == 0) {
                            echo "<td><button class='update-btn' onclick='updateSchedule(\"{$schedule['scheduleId']}\")'>Update</button></td>";
                            echo "<td><button class='delete-btn' onclick='deleteSchedule(\"{$schedule['scheduleId']}\")'>Delete</button></td>";
                        } else {
                            echo "<td><button class='update-btn' onclick='showPopup(\"Cannot update a schedule with bookings.\")'>Update</button></td>";
                            echo "<td><button class='delete-btn' onclick='showPopup(\"Cannot delete a schedule with bookings.\")'>Delete</button></td>";
                        }
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='14'>No bus data available.</td></tr>";
                }
                
                ?>
            </tbody>
        </table>
    </div>

    <div class="popup-overlay" id="popupOverlay">
        <div class="popup-box">
            <p id="popupMessage"></p>
            <button onclick="closePopup()">OK</button>
        </div>
    </div>

        <!-- Delete Confirmation Popup -->
    <div class="popup-overlay" id="deletePopupOverlay">
        <div class="popup-box">
            <p id="deletePopupMessage">Are you sure you want to delete this schedule?</p>
            <div class="popup-buttons">
                <button class="confirm-btn" id="confirmDeleteBtn">Yes</button>
                <button class="cancel-btn" onclick="closeDeletePopup()">No</button>
            </div>
        </div>
    </div>


    <script>
        function selectRow(row) {
            const selectedRow = document.querySelector(".schedule-table tr.selected");
            if (selectedRow) {
                selectedRow.classList.remove("selected");
            }
            row.classList.add("selected");
        }

        function showPopup(message) {
            const popupOverlay = document.getElementById("popupOverlay");
            const popupMessage = document.getElementById("popupMessage");

            popupMessage.innerText = message;
            popupOverlay.style.display = "flex";
        }

        function closePopup() {
            const popupOverlay = document.getElementById("popupOverlay");
            popupOverlay.style.display = "none";
        }

        function updateSchedule(scheduleId) {
            // Find the row with the matching schedule ID
            const rows = Array.from(document.querySelectorAll("table.schedule-table tbody tr"));
            const row = rows.find(row => row.cells[0].innerText.trim() === String(scheduleId));

            if (row) {
                // Extract data from the row
                const licenseId = row.cells[1].innerText.trim();
                const direction = row.cells[2].innerText.trim();
                const type = row.cells[3].innerText.trim();
                const date = row.cells[4].innerText.trim();
                const departureTime = row.cells[5].innerText.trim();
                const arrivalTime = row.cells[6].innerText.trim();
                const duration = row.cells[7].innerText.trim();

                // Ensure all required data is present
                if (!licenseId || !date || !departureTime || !arrivalTime || !duration) {
                    alert("Some required data is missing.");
                    return;
                }

                // Redirect to the AddSchedule page with pre-filled data
                const url = new URL('<?php echo URLROOT; ?>/SuperAdminPages/addschedule');

                url.searchParams.append('scheduleId', scheduleId);
                url.searchParams.append('licenseId', encodeURIComponent(licenseId));
                url.searchParams.append('direction', encodeURIComponent(direction));
                url.searchParams.append('type', encodeURIComponent(type));
                url.searchParams.append('date', encodeURIComponent(date));
                url.searchParams.append('departureTime', encodeURIComponent(departureTime));
                url.searchParams.append('arrivalTime', encodeURIComponent(arrivalTime));
                url.searchParams.append('duration', encodeURIComponent(duration));
                
                window.location.href = url.toString();
            } else {
                console.error(`Row with scheduleId ${scheduleId} not found.`);
                alert("Schedule not found.");
            }
        }
        function deleteSchedule(scheduleId) {
            const deletePopupOverlay = document.getElementById("deletePopupOverlay");
            const confirmDeleteBtn = document.getElementById("confirmDeleteBtn");

            // Show the delete confirmation popup
            deletePopupOverlay.style.display = "flex";

            // Attach a one-time event listener to the confirm button
            confirmDeleteBtn.onclick = function () {
            fetch('<?php echo URLROOT; ?>/SuperAdminPages/deleteSchedule', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ scheduleId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                const rows = Array.from(document.querySelectorAll("table.schedule-table tbody tr"));
                const row = rows.find(row => row.cells[0].innerText.trim() === String(scheduleId));
                if (row) {
                    row.remove();
                }
                showPopup(data.message);
                } else {
                showPopup(data.message);
                }
            })
            .catch(() => showPopup('Error deleting the schedule.'))
            .finally(() => {
                // Close the delete confirmation popup
                deletePopupOverlay.style.display = "none";
            });
            };
        }

        function closeDeletePopup() {
            const deletePopupOverlay = document.getElementById("deletePopupOverlay");
            deletePopupOverlay.style.display = "none";
        }
    </script>
</body>
</html>
