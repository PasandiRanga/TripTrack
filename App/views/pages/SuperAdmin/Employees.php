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
    <style>
/* Common Popup Overlay */
.popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5); /* semi-transparent dark background */
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    display: none; /* hidden by default */
}

/* Popup Content (First Box) */
.popup-content {
    background-color: white;
    width: 400px;
    height: auto;
    padding: 30px 25px;
    box-sizing: border-box;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
    position: relative;
    max-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    border-top: 5px solid #00897b;
    animation: popupFadeIn 0.3s ease-out;
}

/* Popup Box (Second Box) */
.popup-box {
    background-color: white;
    width: 400px;
    height: auto;
    padding: 30px 25px;
    box-sizing: border-box;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
    position: relative;
    max-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    border-top: 5px solid #00897b;
    animation: popupFadeIn 0.3s ease-out;
}

/* Popup Message (Heading inside popup-box) */
#deletePopupMessage {
    font-size: 24px;
    color: #424242;
    margin-top: 5px;
    margin-bottom: 20px;
}

/* Buttons inside .popup-content */
.popup-content button {
    width: 120px;
    height: 40px;
    font-size: 16px;
    font-weight: 600;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    margin: 10px;
}

/* Popup Buttons container inside .popup-box */
.popup-buttons {
    display: flex;
    justify-content: center;
    gap: 20px;
    width: 100%;
    margin-top: 20px;
}

/* Confirm Button */
.confirm-btn {
    background-color: white;
    color: #e22222;
    border: 2px solid #e22222;
    width: 120px;
    height: 40px;
    font-size: 16px;
    font-weight: 600;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.confirm-btn:hover {
    background-color: #e22222;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(226, 34, 34, 0.2);
}

/* Cancel Button */
.cancel-btn {
    background-color: white;
    color: #00897b;
    border: 2px solid #00897b;
    width: 120px;
    height: 40px;
    font-size: 16px;
    font-weight: 600;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.cancel-btn:hover {
    background-color: #00897b;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 137, 123, 0.2);
}

/* Animation */
@keyframes popupFadeIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}


</style>

    <!-- Back Button -->
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/home'">Back</button>

    <div class="box">

    <!-- Page Title -->
    <h1 class="centered">View Employees</h1>


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
                        echo "<tr data-employee-id=\"{$user['employee_id']}\">";
                        echo "<td>{$user['employee_id']}</td>";
                        echo "<td>{$user['name']}</td>";
                        echo "<td>{$user['nic']}</td>";
                        echo "<td>{$user['address']}</td>";
                        echo "<td>{$user['contactNo']}</td>";
                        echo "<td>" . (!empty($user['email']) ? $user['email'] : '-') . "</td>";
                        echo "<td>{$user['role']}</td>";
                        echo "<td><button class='update' onclick=\"editUser('{$user['employee_id']}')\">Update</button></td>";
                        echo "<td><button class='delete' onclick='deleteUser(\"{$user['employee_id']}\")'>Delete</button></td>";
                        echo "</tr>";
                    }
                } else {
                        echo "<tr><td colspan='14'>No Employee data available.</td></tr>";

                }
                ?>
            </tbody>
        </table>
    </div>

    </div>
    <div id="popup" class="popup-overlay" style="display: none;">
    <div class="popup-content">
        <p id="popup-message"></p>
        <button onclick="closePopup()">OK</button>
    </div>
    </div>

    <div class="popup-overlay" id="deletePopupOverlay">
        <div class="popup-box">
            <p id="deletePopupMessage">Are you sure you want to delete this schedule?</p>
            <div class="popup-buttons">
                <button class="confirm-btn" id="confirmDeleteBtn">Yes</button>
                <button class="cancel-btn" onclick="closeDeletePopup()">No</button>
            </div>
        </div>
    </div>

    <script>

        function showPopup(message, onCloseCallback = null) {
            const popup = document.getElementById('popup');
            const messageElement = document.getElementById('popup-message');
            messageElement.textContent = message;
            popup.style.display = 'flex'; // or 'block' depending on your CSS
            popup.dataset.callback = onCloseCallback ? 'true' : '';

            // Save the callback if provided
            popup.onCloseCallback = onCloseCallback;
        }

        function closePopup() {
            const popup = document.getElementById('popup');
            popup.style.display = 'none';

            // Run the callback if it exists
            if (popup.onCloseCallback) {
                popup.onCloseCallback();
                popup.onCloseCallback = null; // Clear it after running
            }
        }

                function showPopup(message) {
            document.getElementById('popup-message').textContent = message;
            document.getElementById('popup').style.display = 'flex';
        }

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
                showPopup('Employee not found.');
            }
        }

// Keep the employeeIdToDelete outside
        let employeeIdToDelete = null;

        // Function to trigger the delete popup
        function deleteUser(employee_id) {
            employeeIdToDelete = employee_id;
            document.getElementById("deletePopupOverlay").style.display = "flex";
        }

        // Confirm Delete Button
        document.getElementById("confirmDeleteBtn").addEventListener("click", function() {
            if (employeeIdToDelete) {
                fetch('<?php echo URLROOT; ?>/SuperAdminPages/deleteEmployee', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ employee_id: employeeIdToDelete })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Find the table row with the matching employee_id
                        const row = document.querySelector(`tr[data-employee-id="${employeeIdToDelete}"]`);
                        if (row) {
                            // Optional: fade-out animation before removing
                            row.style.transition = "opacity 0.3s ease";
                            row.style.opacity = "0";
                            setTimeout(() => row.remove(), 300);
                        }
                        showPopup(data.message); // success popup
                    } else {
                        showPopup(data.message); // error popup
                    }
                    closeDeletePopup();
                })
                .catch(() => {
                    showPopup('Error deleting the employee.');
                    closeDeletePopup();
                });
            }
        });

        // Function to close the delete popup
        function closeDeletePopup() {
            document.getElementById("deletePopupOverlay").style.display = "none";
            employeeIdToDelete = null;
        }



    </script>
</body>
</html>
