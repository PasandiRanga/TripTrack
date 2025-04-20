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

            <button type="submit">Send Notification</button>
        </form>
    </div>

    <script>

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

                        const empId = document.getElementById("employeeId").value;
                        const empName = document.getElementById("employeeName").value;
                        const empRole = document.getElementById("employeeRole").value;
                        const title = document.getElementById("notificationTitle").value;
                        const msg = document.getElementById("notificationMessage").value;

                        if (!empId || !empName || !empRole || !title || !msg) {
                            alert("Please fill all employee notification fields.");
                            return;
                        }

                        const data = {
                            employee_id: empId,
                            employee_name: empName,
                            employee_role: empRole,
                            title: title,
                            message: msg
                        };

                        console.log("Data to be sent:", data);

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
                                window.location.href = '<?php echo URLROOT; ?>/SuperAdminPages/notifications';
                            } else {
                                alert("Error: " + response.message);
                            }
                        })
                        .catch(error => {
                            console.error("Error:", error);
                            alert("An error occurred while sending the notification.");
                        });
                    });
                } else {
                    console.error("Form element not found");
                }
            });

    </script>
</body>
</html>
