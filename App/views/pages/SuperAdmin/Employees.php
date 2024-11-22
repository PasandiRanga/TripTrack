<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Users</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/Employees.css?v=<?php echo time(); ?>">
</head>
<body>
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/home'">Back</button>

    <div class="top-actions">
        <button class="add-employee-btn" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/addemployees'">Add Employee</button>
    </div>

    <h2 class="centered">View Users</h2>

    <form id="viewUserForm" class="user-form centered">
        <label for="userRole">Select User Role:</label>
        <select id="userRole" name="userRole" onchange="filterUsers()">
            <option value="">Select Role</option>
            <option value="conductor">Conductor</option>
            <option value="driver" selected>Driver</option>
            <option value="admin">Regional Admin</option>
        </select>
    </form>

    <!-- Conductor Table -->
    <div id="conductorTable" class="user-table" style="display: none;">
        <h3 class="centered">Conductor List</h3>
        <table>
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>Name</th>
                    <th>User Name</th>
                    <th>NIC</th>
                    <th>Address</th>
                    <th>Contact No</th>
                    <th>Update</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <!-- Conductor rows will be inserted here dynamically -->
            </tbody>
        </table>
    </div>

    <!-- Driver Table (default visible table) -->
    <div id="driverTable" class="user-table" style="display: block;">
        <h3 class="centered">Driver List</h3>
        <table>
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>Name</th>
                    <th>User Name</th>
                    <th>NIC</th>
                    <th>Address</th>
                    <th>Contact No</th>
                    <th>Update</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <!-- Driver rows will be inserted here dynamically -->
            </tbody>
        </table>
    </div>

    <!-- Regional Admin Table -->
    <div id="adminTable" class="user-table" style="display: none;">
        <h3 class="centered">Regional Admin List</h3>
        <table>
            <thead>
                <tr>
                    <th>Admin ID</th>
                    <th>Admin Name</th>
                    <th>Email</th>
                    <th>NIC</th>
                    <th>Address</th>
                    <th>Contact No</th>
                    <th>Region</th>
                    <th>Update</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <!-- Admin rows will be inserted here dynamically -->
            </tbody>
        </table>
    </div>

    <script>
        // Example user data for each role (can be replaced with dynamic PHP data)
        const users = {
            conductor: [
                {employeeId: 1, name: "John Doe", username: "John001", nic: "123456789V", address: "Street 1, City", contact: "123-456-7890"},
                {employeeId: 2, name: "Bob Brown", username: "Bob002", nic: "987654321V", address: "Street 2, City", contact: "456-789-0123"}
            ],
            driver: [
                {employeeId: 3, name: "Jane Smith", username: "Jane003", nic: "111223344V", address: "Street 3, City", contact: "234-567-8901"},
                {employeeId: 4, name: "Charlie White", username: "Charlie004", nic: "112233445V", address: "Street 4, City", contact: "567-890-1234"}
            ],
            admin: [
                {adminId: 5, adminName: "Alice Johnson", email: "alice@admin.com", nic: "556677889V", address: "Admin Office, City", contact: "345-678-9012", region: "Central"},
                {adminId: 6, adminName: "Eve Green", email: "eve@admin.com", nic: "667788990V", address: "Admin Office, City", contact: "678-901-2345", region: "North"}
            ]
        };

        // Function to filter and display users based on selected role
        function filterUsers() {
            const role = document.getElementById("userRole").value;
            const allTables = document.querySelectorAll(".user-table");

            // Hide all tables
            allTables.forEach(table => table.style.display = "none");

            // Show the table corresponding to the selected role
            if (role && users[role]) {
                const tableBody = document.querySelector(`#${role}Table tbody`);
                tableBody.innerHTML = ""; // Clear the table before adding rows

                // Populate the table with users
                users[role].forEach(user => {
                    const row = tableBody.insertRow();
                    if (role === "admin") {
                        row.innerHTML = `
                            <td>${user.adminId}</td>
                            <td>${user.adminName}</td>
                            <td>${user.email}</td>
                            <td>${user.nic}</td>
                            <td>${user.address}</td>
                            <td>${user.contact}</td>
                            <td>${user.region}</td>
                            <td><button onclick="editUser(${user.adminId}, '${role}')">Update</button></td>
                            <td><button class="delete" onclick="deleteUser(${user.adminId}, '${role}')">Delete</button></td>
                        `;
                    } else {
                        row.innerHTML = `
                            <td>${user.employeeId}</td>
                            <td>${user.name}</td>
                            <td>${user.username}</td>
                            <td>${user.nic}</td>
                            <td>${user.address}</td>
                            <td>${user.contact}</td>
                            <td><button onclick="editUser(${user.employeeId}, '${role}')">Update</button></td>
                            <td><button class="delete" onclick="deleteUser(${user.employeeId}, '${role}')">Delete</button></td>
                        `;
                    }
                });

                // Display the appropriate table
                document.getElementById(`${role}Table`).style.display = "block";
            }
        }

        // Function to handle the Edit action
        function editUser(userId, role) {
            alert(`Editing ${role} with ID: ${userId}`);
            // Implement the editing functionality as needed
        }

        // Function to handle the Delete action
        function deleteUser(userId, role) {
            alert(`Deleting ${role} with ID: ${userId}`);
            // Implement the delete functionality as needed
        }

        // Populate the driver table by default on page load
        window.onload = function() {
            filterUsers();
        };
    </script>

</body>
</html>
