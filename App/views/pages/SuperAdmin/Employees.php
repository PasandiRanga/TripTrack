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

.popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5); 
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    display: none; 
}


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


#deletePopupMessage {
    font-size: 24px;
    color: #424242;
    margin-top: 5px;
    margin-bottom: 20px;
}


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


.popup-buttons {
    display: flex;
    justify-content: center;
    gap: 20px;
    width: 100%;
    margin-top: 20px;
}


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

    
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/home'">Back</button>

    <div class="box">

    
    <h1 class="centered">View Employees</h1>


    
    <div class="top-actions">
        <button class="add-employee-btn" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/addemployees'">Add Employee</button>
    </div>

    

    
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
            popup.style.display = 'flex'; 
            popup.dataset.callback = onCloseCallback ? 'true' : '';

            
            popup.onCloseCallback = onCloseCallback;
        }

        function closePopup() {
            const popup = document.getElementById('popup');
            popup.style.display = 'none';

            
            if (popup.onCloseCallback) {
                popup.onCloseCallback();
                popup.onCloseCallback = null; 
            }
        }

                function showPopup(message) {
            document.getElementById('popup-message').textContent = message;
            document.getElementById('popup').style.display = 'flex';
        }

        
        function editUser(employee_id) {
            
            const rows = Array.from(document.querySelectorAll("table.user-table tbody tr"));
            const row = rows.find(row => row.cells[0].innerText.trim() === String(employee_id));

            if (row) {
                
                const name = row.cells[1].innerText.trim();
                const nic = row.cells[2].innerText.trim();
                const address = row.cells[3].innerText.trim();
                const contactNo = row.cells[4].innerText.trim();
                const email = row.cells[5].innerText.trim();

                
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


        let employeeIdToDelete = null;

        
        function deleteUser(employee_id) {
            employeeIdToDelete = employee_id;
            document.getElementById("deletePopupOverlay").style.display = "flex";
        }

        
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
                        
                        const row = document.querySelector(`tr[data-employee-id="${employeeIdToDelete}"]`);
                        if (row) {
                            
                            row.style.transition = "opacity 0.3s ease";
                            row.style.opacity = "0";
                            setTimeout(() => row.remove(), 300);
                        }
                        showPopup(data.message); 
                    } else {
                        showPopup(data.message);
                    }
                    closeDeletePopup();
                })
                .catch(() => {
                    showPopup('Error deleting the employee.');
                    closeDeletePopup();
                });
            }
        });

        
        function closeDeletePopup() {
            document.getElementById("deletePopupOverlay").style.display = "none";
            employeeIdToDelete = null;
        }



    </script>
</body>
</html>
