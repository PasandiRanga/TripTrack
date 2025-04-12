<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Admin']);
?>
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


    <!-- Page Title -->
    <h2 class="centered">View Employees</h2>


    <!-- Top Actions -->
    <div class="top-actions">
        <button class="add-employee-btn" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/addemployees'">Add Employee</button>
    </div>

    

    <!-- User Table -->
    <div id="userTable" class="user-table-container">
        <table class="user-table">
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
                /*
                $users = [
                    ['id' => 'EM1', 'name' => 'rashmika dilmin', 'nic' => '200118201761', 'address' => 'Street 1, Colombo', 'contact' => '0767013421', 'email' => 'rashmikadilmin@gmail.com', 'role' => 'Conductor'],
                    ['id' => 'EM2', 'name' => 'sandaru kaushan', 'nic' => '200198234585', 'address' => 'Street 2, Colombo', 'contact' => '0775342334', 'email' => 'sadarukushan@gmail.com', 'role' => 'Driver'],
                    ['id' => 'EM3', 'name' => 'jaith lomitha', 'nic' => '200123948526', 'address' => 'Street 3, Colombo', 'contact' => '0723456789', 'email' => 'lomitha@gmail.com', 'role' => 'Admin'],
                    ['id' => 'EM4', 'name' => 'romain cooray', 'nic' => '200193728078', 'address' => 'Street 4, Colombo', 'contact' => '0789076543', 'email' => 'rumaincooray@gmail.com', 'role' => 'Driver'],
                    ['id' => 'EM5', 'name' => 'satheera jayawardana', 'nic' => '200198275541', 'address' => 'Main street, Colombo', 'contact' => '0775423566', 'email' => 'satheera@gmail.com', 'role' => 'Admin']
                ]; */
                if(isset($data['emp']) && is_array($data['emp'])) {
                    foreach ($data['emp'] as $user) {
                        echo "<tr>";
                        echo "<td>{$user['employee_id']}</td>";
                        echo "<td>{$user['name']}</td>";
                        echo "<td>{$user['nic']}</td>";
                        echo "<td>{$user['address']}</td>";
                        echo "<td>{$user['contactNo']}</td>";
                        echo "<td>" . (!empty($user['email']) ? $user['email'] : '-') . "</td>";
                        echo "<td>{$user['role']}</td>";
                        echo "<td><button class='update' onclick=\"editUser('{$user['employee_id']}')\">Update</button></td>";
                        echo "<td><button class='delete' onclick=deleteUser(\"{$user['employee_id']}\")>Delete</button></td>";
                        echo "</tr>";
                    }
                } else {
                        echo "<tr><td colspan='14'>No Employee data available.</td></tr>";

                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Function to handle the Edit action
        function editUser(employee_id) {
            // Find the row corresponding to the selected employee
            const rows = Array.from(document.querySelectorAll("table.user-table tbody tr"));
            const row = rows.find(row => row.cells[0].innerText.trim() === String(employee_id));

            if (row) {
                // Extract data from the row
                const name = row.cells[1].innerText.trim();
                const nic = row.cells[2].innerText.trim();
                const address = row.cells[3].innerText.trim();
                const contactNo = row.cells[4].innerText.trim();
                const email = row.cells[5].innerText.trim();

                // Redirect to the addemployee page with the data as query parameters
                const url = new URL('<?php echo URLROOT; ?>/SuperAdminPages/addemployees');
                url.searchParams.append('employee_id', employee_id);
                url.searchParams.append('name', encodeURIComponent(name));
                url.searchParams.append('nic', encodeURIComponent(nic));
                url.searchParams.append('address', encodeURIComponent(address));
                url.searchParams.append('contactNo', encodeURIComponent(contactNo));
                url.searchParams.append('email', encodeURIComponent(email));

                window.location.href = url.toString();
            } else {
                alert('Employee not found.');
            }
        }

        // Function to handle the Delete action
        function deleteUser(employee_id) {
            if(confirm('Are you sure you want to delete this Employee?')){
                fetch('<?php echo URLROOT; ?>/SuperAdminPages/deleteEmployee',{
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json'},
                    body: JSON.stringify({employee_id})
                })
                .then(response => response.json())
                .then(data => {
                    if(data.status === 'success'){
                        const rows = Array.from(document.querySelectorAll("table.employee-table tbody tr"));
                        const row = rows.find(row => row.cells[0].innerText.trim() === String(employee_id));
                        if(row){
                            row.remove();
                        }
                        alert(data.message);
                    } else {
                        alert(data.message);
                    }
                })
                .catch(() => alert('Error deleting the employee.'));
            }
        }

    </script>
</body>
</html>
