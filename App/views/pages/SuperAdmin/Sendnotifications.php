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
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/Sendnotifications.css?v=<?php echo time(); ?>">
</head>
<body>
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/notifications'">Back</button>

    <div class="left-side">
        <!-- Notification Form -->
        <form id="notificationForm" method="POST" action="<?php echo URLROOT . '/SuperAdminPages/sendnotifications'?>" class="notification-form">
            <h2 class="form_header">Send Notification</h2>

            <label for="notificationType">Select Notification Type:</label>
            <select id="notificationType" name="notificationType" onchange="toggleNotificationFields()" required>
                <option value="">Select Type</option>
                <option value="employee" selected>Employee</option> <!-- Default selected -->
                <option value="passenger">Passenger</option>
            </select>

            <!-- Employee Fields -->
            <div id="employeeFields" class="notification-section">
                <label for="employeeId">Select Employee:</label>
                <select id="employeeId" name="employeeId" required>
                    <option value="">Select Employee</option>
                    <?php foreach ($data['employees'] as $employee): ?>
                        <option value="<?php echo $employee['employee_id']; ?>" 
                                data-name="<?php echo htmlspecialchars($employee['name']); ?>" 
                                data-role="<?php echo htmlspecialchars($employee['role']); ?>">
                            <?php echo $employee['employee_id']; ?>
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

document.addEventListener("DOMContentLoaded", function () {
    // Initialize employee name and role fields based on the selected employee
    const employeeSelect = document.getElementById("employeeId");
    const nameField = document.getElementById("employeeName");
    const roleField = document.getElementById("employeeRole");

    employeeSelect.addEventListener("change", function () {
        const selectedOption = this.options[this.selectedIndex];

        const name = selectedOption.getAttribute("data-name") || "";
        const role = selectedOption.getAttribute("data-role") || "";

        nameField.value = name;
        roleField.value = role;
    });

    // Form submission logic
    const notificationForm = document.getElementById("notificationForm");

    if (notificationForm) {
        notificationForm.addEventListener("submit", function (event) {
            event.preventDefault(); // Prevent default form submission

            const notificationType = document.getElementById("notificationType").value;

            // Employee notification logic
            if (notificationType === "employee") {
                const empId = document.getElementById("employeeId").value;
                const empName = document.getElementById("employeeName").value;
                const empRole = document.getElementById("employeeRole").value;
                const title = document.getElementById("notificationTitle").value;
                const msg = document.getElementById("notificationMessage").value;

                if (!empId || !empName || !empRole || !title || !msg) {
                    alert("Please fill all employee notification fields.");
                    return false;
                }

                const data = {
                    employee_id: empId,
                    employee_name: empName,
                    employee_role: empRole,
                    title: title,
                    message: msg
                };
                console.log("Data to be sent:", data); // Debugging line
                // Send the data via fetch API
                fetch(window.location.href, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(response => {
                    if (response.status === 'success') {
                        alert("Notification sent successfully!");
                    } else {
                        alert("Error: " + response.message);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("An error occurred while sending the notification.");
                });

            }else if (notificationType === "passenger") {
                const schedule = document.getElementById("schedule").value;
                const msg = document.getElementById("passengerMessage").value;

                if (!schedule || !msg) {
                    alert("Please fill all passenger notification fields.");
                    return false;
                }

                // Handle passenger notification logic here (optional)
                alert(`Notification sent for Schedule: ${schedule}`);
            } else {
                alert("Please select a notification type.");
                return false;
            }

            return false; // Prevent default form submission
        });
    } else {
        console.error("Form element not found");
    }
});


        // Ensure the correct fields are shown on page load
        window.onload = function () {
            toggleNotificationFields();
        };
    </script>
</body>
</html>
