<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Admin']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Notification</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/Notifications.css?v=<?php echo time(); ?>">
</head>
<body>
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/home'">Back </button>

    
    <div class="left-side">
        
        <!-- Notification Form -->
        <form id="notificationForm" onsubmit="return submitNotification()" class="notification-form">
            <!-- Notification Type Selection -->
            <label for="notificationType">Select Notification Type:</label>
            <select id="notificationType" name="notificationType" onchange="toggleNotificationFields()">
                <option value="">Select Type</option>
                <option value="employee">Employee</option>
                <option value="passenger">Passenger</option>
            </select>

            <!-- Employee Notification Fields -->
            <div id="employeeFields" class="notification-section" style="display: none;">
                <label for="employeeId">Employee ID:</label>
                <input type="text" id="employeeId" name="employeeId" placeholder="Enter Employee ID">

                <label for="employeeMessage">Notification Content:</label>
                <textarea id="employeeMessage" name="employeeMessage" placeholder="Enter notification for employee"></textarea>
            </div>

            <!-- Passenger Notification Fields -->
            <div id="passengerFields" class="notification-section" style="display: none;">
                <label for="schedule">Select Schedule:</label>
                <select id="schedule" name="schedule">
                    <option value="1">Route 1 - Bus 1001</option>
                    <option value="2">Route 2 - Bus 1002</option>
                    <option value="3">Route 3 - Bus 1003</option>
                </select>

                <label for="passengerMessage">Notification Content:</label>
                <textarea id="passengerMessage" name="passengerMessage" placeholder="Enter notification for passengers"></textarea>
            </div>

            <button type="submit">Send Notification</button>
        </form>
    </div>

    <div class="right-side">
        <!-- Employee Notifications Table -->
        <table id="employeeTable" style="display: block;">
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>Content</th>
                    <th>Date</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <!-- Example data -->
                <tr>
                    <td>1001</td>
                    <td>Employee Notification Content</td>
                    <td>2025-01-24</td>
                    <td>10:00 AM</td>
                </tr>
            </tbody>
        </table>

        <!-- Passenger Notifications Table -->
        <table id="passengerTable" style="display: none;">
            <thead>
                <tr>
                    <th>Schedule</th>
                    <th>Content</th>
                    <th>Date</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <!-- Example data -->
                <tr>
                    <td>Route 1 - Bus 1001</td>
                    <td>Passenger Notification Content</td>
                    <td>2025-01-24</td>
                    <td>10:15 AM</td>
                </tr>
            </tbody>
        </table>
    </div>

    <script>
        // Function to toggle the fields based on the selected notification type
        function toggleNotificationFields() {
            const notificationType = document.getElementById("notificationType").value;
            const employeeFields = document.getElementById("employeeFields");
            const passengerFields = document.getElementById("passengerFields");
            const employeeTable = document.getElementById("employeeTable");
            const passengerTable = document.getElementById("passengerTable");

            // Show and hide fields and tables based on selection
            if (notificationType === "employee") {
                employeeFields.style.display = "block";
                passengerFields.style.display = "none";
                employeeTable.style.display = "block";
                passengerTable.style.display = "none";
            } else if (notificationType === "passenger") {
                employeeFields.style.display = "none";
                passengerFields.style.display = "block";
                employeeTable.style.display = "none";
                passengerTable.style.display = "block";
            } else {
                employeeFields.style.display = "none";
                passengerFields.style.display = "none";
                employeeTable.style.display = "none";
                passengerTable.style.display = "none";
            }
        }

        // Function to handle the submission of the notification form
        function submitNotification() {
            const notificationType = document.getElementById("notificationType").value;

            // Check if the required fields are filled based on the notification type
            if (notificationType === "employee") {
                const employeeId = document.getElementById("employeeId").value;
                const employeeMessage = document.getElementById("employeeMessage").value;

                if (!employeeId || !employeeMessage) {
                    alert("Please fill in all fields for the employee notification.");
                    return false;
                }

                alert(`Notification sent to Employee ID: ${employeeId}`);
            } else if (notificationType === "passenger") {
                const schedule = document.getElementById("schedule").value;
                const passengerMessage = document.getElementById("passengerMessage").value;

                if (!schedule || !passengerMessage) {
                    alert("Please fill in all fields for the passenger notification.");
                    return false;
                }

                alert(`Notification sent for Schedule: ${schedule}`);
            } else {
                alert("Please select a notification type.");
                return false;
            }

            return true; // Form submission proceeds
        }

    </script>
</body>
</html>
