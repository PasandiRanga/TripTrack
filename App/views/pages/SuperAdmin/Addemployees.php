<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Users</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/Addemployees.css?v=<?php echo time(); ?>">
</head>
<body>
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/employees'">Back</button>

    <h2>Create Users</h2>

    <form id="userForm" method="POST" action="<?php echo URLROOT; ?>/SuperAdminPages/addemployees" class="user-form">
        <!-- User Type Selection -->
        <label for="userType">Select User Type:</label>
        <select id="userType" name="userType" onchange="toggleUserForm()" required>
            <option value="">Select Type</option>
            <option value="conductor">Conductor</option>
            <option value="driver">Driver</option>
            <option value="admin">Regional Admin</option>
        </select>

        <!-- Conductor/Driver Form -->
        <div id="employeeForm" class="user-section" style="display: none;">

            <label for="employeeName">Name:</label>
            <input type="text" id="employeeName" name="employeeName" placeholder="Enter Name" required>

            <label for="email">Email:</label>
            <input type="text" id="email" name="email" placeholder="Enter email" required>

            <label for="nic">NIC:</label>
            <input type="text" id="nic" name="nic" placeholder="Enter NIC" required>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" placeholder="Enter Address" required>

            <label for="contactNo">Contact No:</label>
            <input type="text" id="contactNo" name="contactNo" placeholder="Enter Contact No" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter Password" required>
        </div>

        <!-- Regional Admin Form -->
        <div id="adminForm" class="user-section" style="display: none;">

            <label for="adminName">Admin Name:</label>
            <input type="text" id="adminName" name="adminName" placeholder="Enter Admin Name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter Email" required>

            <label for="nic">NIC:</label>
            <input type="text" id="nic" name="nic" placeholder="Enter NIC" required>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" placeholder="Enter Address" required>

            <label for="contactNo">Contact No:</label>
            <input type="text" id="contactNo" name="contactNo" placeholder="Enter Contact No" required>

            <label for="region">Region:</label>
            <input type="text" id="region" name="region" placeholder="Enter Region" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter Password" required>
        </div>

        <button type="submit">Create User</button>
    </form>

    <script>
        // Toggle between forms based on user type
        function toggleUserForm() {
            const userType = document.getElementById("userType").value;
            // Get form sections
            const employeeForm = document.getElementById("employeeForm");
            const adminForm = document.getElementById("adminForm");
            document.getElementById("employeeForm").style.display = (userType === "conductor" || userType === "driver") ? "block" : "none";
            document.getElementById("adminForm").style.display = (userType === "admin") ? "block" : "none";

            // Disable inputs in hidden forms
            const allInputs = document.querySelectorAll(".user-section input");
            allInputs.forEach(input => input.disabled = true); // Disable all inputs initially

            // Enable inputs in the visible form
            const visibleInputs = (userType === "admin") 
                ? adminForm.querySelectorAll("input") 
                : employeeForm.querySelectorAll("input");

            visibleInputs.forEach(input => input.disabled = false);
        }


        // Handle form submission
        document.getElementById("userForm").addEventListener("submit", function(event) {
            event.preventDefault();

            const userType = document.getElementById("userType").value;
            let userData = {};

            if (userType === "conductor" || userType === "driver") {
                userData = {
                    userType:document.getElementById("userType").value,
                    employeeName: document.getElementById("employeeName").value,
                    email: document.getElementById("email").value,
                    nic: document.getElementById("nic").value,
                    address: document.getElementById("address").value,
                    contactNo: document.getElementById("contactNo").value,
                    password: document.getElementById("password").value
                };
            } else if (userType === "admin") {
                userData = {
                    userType: document.getElementById("userType").value,
                    adminName: document.getElementById("adminName").value,
                    email: document.getElementById("email").value,
                    nic: document.getElementById("nic").value,
                    address: document.getElementById("address").value,
                    contactNo: document.getElementById("contactNo").value,
                    region: document.getElementById("region").value,
                    password: document.getElementById("password").value
                };
            } else {
                alert("Please select a user type.");
                return;
            }

            // Log the form data to the console
            console.log("User data:", userData);

            // Simulate successful form submission (you can replace this with actual server-side submission)
           

            this.submit(); // Submit the form

            // Optionally, clear the form fields after submission
            clearForm();
        });

        // Clear form fields
        function clearForm() {
            document.getElementById("userForm").reset();
        }
    </script>
</body>
</html>
