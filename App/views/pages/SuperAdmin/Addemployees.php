<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Users</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/Addemployees.css?v=<?php echo time(); ?>">
</head>
<body>
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/employees'">Back </button>

    <h2>Create Users</h2>

    <form id="userForm" onsubmit="return submitUserForm()" class="user-form">
        <!-- User Type Selection -->
        <label for="userType">Select User Type:</label>
        <select id="userType" name="userType" onchange="toggleUserForm()">
            <option value="">Select Type</option>
            <option value="conductor">Conductor</option>
            <option value="driver">Driver</option>
            <option value="admin">Regional Admin</option>
        </select>

        <!-- Conductor/Driver Form -->
        <div id="employeeForm" class="user-section" style="display: none;">
            <label for="employeeId">Employee ID:</label>
            <input type="text" id="employeeId" name="employeeId" placeholder="Enter Employee ID">

            <label for="employeeName">Name:</label>
            <input type="text" id="employeeName" name="employeeName" placeholder="Enter Name">

            <label for="nic">NIC:</label>
            <input type="text" id="nic" name="nic" placeholder="Enter NIC">

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" placeholder="Enter Address">

            <label for="contactNo">Contact No:</label>
            <input type="text" id="contactNo" name="contactNo" placeholder="Enter Contact No">
        </div>

        <!-- Regional Admin Form -->
        <div id="adminForm" class="user-section" style="display: none;">
            <label for="adminId">Admin ID:</label>
            <input type="text" id="adminId" name="adminId" placeholder="Enter Admin ID">

            <label for="adminName">Admin Name:</label>
            <input type="text" id="adminName" name="adminName" placeholder="Enter Admin Name">

            <label for="email">Email:</label>
            <input type="text" id="email" name="email" placeholder="Enter Email">

            <label for="nic">NIC:</label>
            <input type="text" id="nic" name="nic" placeholder="Enter NIC">

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" placeholder="Enter Address">

            <label for="contactNo">Contact No:</label>
            <input type="text" id="contactNo" name="contactNo" placeholder="Enter Contact No">

            <label for="region">Region:</label>
            <input type="text" id="region" name="region" placeholder="Enter Region">
        </div>

        <button type="submit">Create User</button>
    </form>

    <script>
                // Function to toggle between forms based on user type
        function toggleUserForm() {
            const userType = document.getElementById("userType").value;
            const employeeForm = document.getElementById("employeeForm");
            const adminForm = document.getElementById("adminForm");

            // Show or hide forms based on user selection
            if (userType === "conductor" || userType === "driver") {
                employeeForm.style.display = "block";
                adminForm.style.display = "none";
            } else if (userType === "admin") {
                employeeForm.style.display = "none";
                adminForm.style.display = "block";
            } else {
                employeeForm.style.display = "none";
                adminForm.style.display = "none";
            }
        }

        // Function to handle form submission
        function submitUserForm() {
            const userType = document.getElementById("userType").value;

            if (userType === "conductor" || userType === "driver") {
                const employeeId = document.getElementById("employeeId").value;
                const employeeName = document.getElementById("employeeName").value;
                const nic = document.getElementById("nic").value;
                const contactNo = document.getElementById("contactNo").value;

                if (!employeeId || !employeeName || !nic || !contactNo) {
                    alert("Please fill in all fields for the Conductor/Driver.");
                    return false;
                }

                alert(`${userType === "conductor" ? "Conductor" : "Driver"} created successfully!`);
            } else if (userType === "admin") {
                const adminId = document.getElementById("adminId").value;
                const adminName = document.getElementById("adminName").value;
                const role = document.getElementById("role").value;

                if (!adminId || !adminName || !role) {
                    alert("Please fill in all fields for the Regional Admin.");
                    return false;
                }

                alert("Regional Admin created successfully!");
            } else {
                alert("Please select a user type.");
                return false;
            }

            return true; // Form submission proceeds
        }

    </script>
</body>
</html>
