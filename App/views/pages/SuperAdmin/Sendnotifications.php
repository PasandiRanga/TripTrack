<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Admin']);

    // Dummy data – replace this with real DB data in your controller
    $employees = [
        ['id' => 1, 'name' => 'Alice Perera', 'role' => 'Driver'],
        ['id' => 2, 'name' => 'Nimal Silva', 'role' => 'Conductor'],
        ['id' => 3, 'name' => 'Sunil Jayasuriya', 'role' => 'Mechanic'],
    ];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Notification</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/Sendnotifications.css?v=<?php echo time(); ?>">
</head>
<body>
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/notifications'">Back</button>

    <div class="left-side">
        <!-- Notification Form -->
        <form id="notificationForm" onsubmit="return submitNotification()" class="notification-form">
            <h2 class="form_header">Send Notification</h2>

            <label for="notificationType">Select Notification Type:</label>
            <select id="notificationType" name="notificationType" onchange="toggleNotificationFields()" required>
                <option value="">Select Type</option>
                <option value="employee">Employee</option>
                <option value="passenger">Passenger</option>
            </select>

            <!-- Employee Fields -->
            <div id="employeeFields" class="notification-section" style="display: none;">
                <label for="employeeId">Select Employee:</label>
                <select id="employeeId" name="employee_id" onchange="updateEmployeeDetails()" required>
                    <option value="">-- Select Employee --</option>
                    <?php foreach ($employees as $employee): ?>
                        <option value="<?php echo $employee['id']; ?>" 
                                data-name="<?php echo $employee['name']; ?>" 
                                data-role="<?php echo $employee['role']; ?>">
                            <?php echo $employee['id'] . ' - ' . $employee['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="employeeName">Employee Name:</label>
                <input type="text" id="employeeName" readonly placeholder="Employee Name">

                <label for="employeeRole">Role:</label>
                <input type="text" id="employeeRole" readonly placeholder="Employee Role">

                <label for="notificationTitle">Title:</label>
                <input type="text" id="notificationTitle" name="title" placeholder="Enter Title" required>

                <label for="notificationMessage">Message:</label>
                <textarea id="notificationMessage" name="message" placeholder="Enter your message here" required></textarea>

                <input type="hidden" name="created_at" value="<?php echo date('Y-m-d H:i:s'); ?>">
            </div>

            <!-- Passenger Fields -->
            <div id="passengerFields" class="notification-section" style="display: none;">
                <label for="schedule">Select Schedule:</label>
                <select id="schedule" name="schedule">
                    <option value="1">Route 1 - Bus 1001</option>
                    <option value="2">Route 2 - Bus 1002</option>
                    <option value="3">Route 3 - Bus 1003</option>
                </select>

                <label for="passengerMessage">Notification Content:</label>
                <textarea id="passengerMessage" name="passengerMessage" placeholder="Enter notification for passengers" required></textarea>
            </div>

            <button type="submit">Send Notification</button>
        </form>
    </div>

    <script>
        function toggleNotificationFields() {
            const notificationType = document.getElementById("notificationType").value;
            document.getElementById("employeeFields").style.display = notificationType === "employee" ? "block" : "none";
            document.getElementById("passengerFields").style.display = notificationType === "passenger" ? "block" : "none";
        }

        function updateEmployeeDetails() {
            const select = document.getElementById("employeeId");
            const selectedOption = select.options[select.selectedIndex];
            const name = selectedOption.getAttribute("data-name") || "";
            const role = selectedOption.getAttribute("data-role") || "";

            document.getElementById("employeeName").value = name;
            document.getElementById("employeeRole").value = role;
        }

        function submitNotification() {
            const notificationType = document.getElementById("notificationType").value;

            if (notificationType === "employee") {
                const empId = document.getElementById("employeeId").value;
                const title = document.getElementById("notificationTitle").value;
                const msg = document.getElementById("notificationMessage").value;

                if (!empId || !title || !msg) {
                    alert("Please fill all employee notification fields.");
                    return false;
                }

                alert(`Notification sent to Employee ID: ${empId}`);
            } else if (notificationType === "passenger") {
                const schedule = document.getElementById("schedule").value;
                const msg = document.getElementById("passengerMessage").value;

                if (!schedule || !msg) {
                    alert("Please fill all passenger notification fields.");
                    return false;
                }

                alert(`Notification sent for Schedule: ${schedule}`);
            } else {
                alert("Please select a notification type.");
                return false;
            }

            return true;
        }
    </script>
</body>
</html>
