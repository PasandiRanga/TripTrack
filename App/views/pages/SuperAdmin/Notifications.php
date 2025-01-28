<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Admin']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/Notifications.css?v=<?php echo time(); ?>">
</head>
<body>
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/home'">Back</button>

    <div class="container">
        <h2>Notifications</h2>

        <!-- Send Notification Button -->
        <div class="button-container">
            <button 
                class="send-notification-button" 
                onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/newNotification';">
                Send Notification
            </button>
        </div>

        <!-- Selection Table -->
        <div class="table-selection">
            <label>
                <input type="radio" name="tableSelector" value="employee" checked onclick="toggleTable('employee')">
                Employees
            </label>
            <label>
                <input type="radio" name="tableSelector" value="passenger" onclick="toggleTable('passenger')">
                Passengers
            </label>
        </div>

        <!-- Tables -->
        <div class="table-wrapper">
            <table id="employeeTable" class="visible-table">
                <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>Content</th>
                        <th>Date</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1001</td>
                        <td>Employee Notification Content</td>
                        <td>2025-01-24</td>
                        <td>10:00 AM</td>
                    </tr>
                </tbody>
            </table>

            <table id="passengerTable" class="hidden-table">
                <thead>
                    <tr>
                        <th>Schedule</th>
                        <th>Content</th>
                        <th>Date</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Route 1 - Bus 1001</td>
                        <td>Passenger Notification Content</td>
                        <td>2025-01-24</td>
                        <td>10:15 AM</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Toggle between Employee and Passenger tables
        function toggleTable(tableType) {
            const employeeTable = document.getElementById("employeeTable");
            const passengerTable = document.getElementById("passengerTable");

            if (tableType === 'employee') {
                employeeTable.className = "visible-table";
                passengerTable.className = "hidden-table";
            } else if (tableType === 'passenger') {
                employeeTable.className = "hidden-table";
                passengerTable.className = "visible-table";
            }
        }
    </script>

</body>
</html>
