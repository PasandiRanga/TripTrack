<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Users</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/Employees.css?v=<?php echo time(); ?>">
</head>
<body>

    <!-- Back Button -->
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/home'">Back</button>

    <!-- Top Actions -->
    <div class="top-actions">
        <button class="add-employee-btn" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/addemployees'">Add Employee</button>
    </div>

    <!-- Page Title -->
    <h2 class="centered">View Employees</h2>

    <!-- Unified User Table -->
    <div id="userTable" class="user-table">
        <table>
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>Name</th>
                    <th>NIC</th>
                    <th>Address</th>
                    <th>Contact No</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Update</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Example data - Replace this with dynamic data from the database
                $users = [
                    ['id' => 1, 'name' => 'John Doe', 'nic' => '123456789V', 'address' => 'Street 1, City', 'contact' => '123-456-7890', 'email' => 'john.doe@example.com', 'role' => 'Conductor'],
                    ['id' => 2, 'name' => 'Bob Brown', 'nic' => '987654321V', 'address' => 'Street 2, City', 'contact' => '456-789-0123', 'email' => 'bob.brown@example.com', 'role' => 'Driver'],
                    ['id' => 3, 'name' => 'Alice Johnson', 'nic' => '556677889V', 'address' => 'Admin Office, City', 'contact' => '345-678-9012', 'email' => 'alice.johnson@example.com', 'role' => 'Admin'],
                    ['id' => 4, 'name' => 'Charlie White', 'nic' => '112233445V', 'address' => 'Street 4, City', 'contact' => '567-890-1234', 'email' => 'charlie.white@example.com', 'role' => 'Driver'],
                    ['id' => 5, 'name' => 'Eve Green', 'nic' => '667788990V', 'address' => 'Admin Office, City', 'contact' => '678-901-2345', 'email' => 'eve.green@example.com', 'role' => 'Admin']
                ];

                foreach ($users as $user) {
                    echo "<tr>";
                    echo "<td>{$user['id']}</td>";
                    echo "<td>{$user['name']}</td>";
                    echo "<td>{$user['nic']}</td>";
                    echo "<td>{$user['address']}</td>";
                    echo "<td>{$user['contact']}</td>";
                    echo "<td>" . (!empty($user['email']) ? $user['email'] : '-') . "</td>";
                    echo "<td>{$user['role']}</td>";
                    echo "<td><button onclick=\"editUser({$user['id']})\">Update</button></td>";
                    echo "<td><button class='delete' onclick=\"deleteUser({$user['id']})\">Delete</button></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Function to handle the Edit action
        function editUser(userId) {
            alert(`Editing user with ID: ${userId}`);
            // Implement the editing functionality as needed
        }

        // Function to handle the Delete action
        function deleteUser(userId) {
            alert(`Deleting user with ID: ${userId}`);
            // Implement the delete functionality as needed
        }
    </script>
</body>
</html>
