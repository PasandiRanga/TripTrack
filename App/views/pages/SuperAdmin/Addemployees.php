<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/Addemployees.css?v=<?php echo time(); ?>">
</head>
<body>
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/employees'">Back</button>

    <h2>Create Employee</h2>

    <form id="userForm" method="POST" action="<?php echo URLROOT; ?>/SuperAdminPages/addemployees" class="user-form">
        <label for="employeeId">Employee ID:</label>
        <input type="text" id="employeeId" name="employeeId" placeholder="Enter Employee ID" required>

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" placeholder="Enter Name" required>

        <label for="nic">NIC:</label>
        <input type="text" id="nic" name="nic" placeholder="Enter NIC" required>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" placeholder="Enter Address" required>

        <label for="contactNo">Contact No:</label>
        <input type="text" id="contactNo" name="contactNo" placeholder="Enter Contact No" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter Email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter Password" required>

        <label for="role">Role:</label>
        <select id="role" name="role" required>
            <option value="">Select Role</option>
            <option value="Conductor">Conductor</option>
            <option value="Driver">Driver</option>
            <option value="Admin">Admin</option>
        </select>

        <button type="submit">Create User</button>
    </form>

    <script>
        // Handle form submission
        document.getElementById("userForm").addEventListener("submit", function(event) {
            event.preventDefault();

            const userData = {
                employeeId: document.getElementById("employeeId").value,
                name: document.getElementById("name").value,
                nic: document.getElementById("nic").value,
                address: document.getElementById("address").value,
                contactNo: document.getElementById("contactNo").value,
                email: document.getElementById("email").value,
                password: document.getElementById("password").value,
                role: document.getElementById("role").value
            };

            // Log form data to the console (for debugging purposes)
            console.log("User data:", userData);

            // Simulate form submission success
            alert("User created successfully!");

            // Optionally, clear the form fields after submission
            document.getElementById("userForm").reset();
        });
    </script>
</body>
</html>
