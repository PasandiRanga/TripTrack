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
       
        <form id="notificationForm" method="POST" action="<?php echo URLROOT . '/SuperAdminPages/sendnotifications'?>" class="notification-form">
            <h2 class="form_header">Send Notification</h2>

            
            <div class="field-group">
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
            </div>

            <div class="input-group">
                <div class="field-group">
                    <label for="employeeName">Employee Name:</label>
                    <input type="text" id="employeeName" readonly placeholder="Employee Name">
                </div>
                <div class="field-group">
                    <label for="employeeRole">Role:</label>
                    <input type="text" id="employeeRole" readonly placeholder="Employee Role">
                </div>
            </div>

            <div class="field-group">
                <label for="notificationTitle">Title:</label>
                <input type="text" id="notificationTitle" name="title" placeholder="Enter Title" required>
            </div>

            <div class="field-group">
                <label for="notificationMessage">Message:</label>
                <textarea id="notificationMessage" name="message" placeholder="Enter your message here" required></textarea>
            </div>

            <button type="submit">Send Notification</button>
        </form>
    </div>

    
    <div id="successPopup" class="popup">
        <div class="popup-content">
            <h2>Success!</h2>
            <p>Notification sent successfully!</p>
            <button id="closePopup">OK</button>
        </div>
    </div>


    <script>

        document.addEventListener("DOMContentLoaded", function () {
                
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

                
                const notificationForm = document.getElementById("notificationForm");

                if (notificationForm) {
                    notificationForm.addEventListener("submit", function (event) {
                        event.preventDefault(); 

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
                                const popup = document.getElementById('successPopup');
                                popup.style.display = 'block';

                                const closeBtn = document.getElementById('closePopup');
                                closeBtn.addEventListener('click', function () {
                                    popup.style.display = 'none';
                                    window.location.href = '<?php echo URLROOT; ?>/SuperAdminPages/notifications';
                                });
                            }
                            else {
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
