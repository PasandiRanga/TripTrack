<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Admin']);

    // Retrieve and decode query parameters for updating an employee
    $employee_id = isset($_GET['employee_id']) ? urldecode($_GET['employee_id']) : '';
    $name = isset($_GET['name']) ? urldecode($_GET['name']) : '';
    $nic = isset($_GET['nic']) ? urldecode($_GET['nic']) : '';
    $address = isset($_GET['address']) ? urldecode($_GET['address']) : '';
    $contactNo = isset($_GET['contactNo']) ? urldecode($_GET['contactNo']) : '';
    $email = isset($_GET['email']) ? urldecode($_GET['email']) : '';
    $role = isset($_GET['role']) ? urldecode($_GET['role']) : '';
    $isUpdate = !empty($employee_id); // Determine if this is an update
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isUpdate ? 'Update Employee' : 'Create Employee'; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/Addemployees.css?v=<?php echo time(); ?>">
</head>
<body>
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/employees'">Back</button>
<div class="box">
    <h2><?php echo $isUpdate ? 'Update Employee' : 'Add Employee'; ?></h2>

    <form id="userForm" method="POST" class="user-form">
        <?php if ($isUpdate): ?>
            <input type="hidden" name="employee_id" id="employee_id" value="<?php echo $employee_id; ?>">
        <?php endif; ?>

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" placeholder="Enter Name" value="<?php echo $name; ?>" required>

        <label for="nic">NIC:</label>
        <input type="text" id="nic" name="nic" placeholder="Enter NIC" value="<?php echo $nic; ?>" required>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" placeholder="Enter Address" value="<?php echo $address; ?>" required>

        <label for="contactNo">Contact No:</label>
        <input type="text" id="contactNo" name="contactNo" placeholder="Enter Contact No" value="<?php echo $contactNo; ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter Email" value="<?php echo $email; ?>" required>

        <?php if (!$isUpdate): ?>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter Password" required>
        
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>

            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="">Select Role</option>
                <option value="Conductor" <?php echo $role === 'Conductor' ? 'selected' : ''; ?>>Conductor</option>
                <option value="Driver" <?php echo $role === 'Driver' ? 'selected' : ''; ?>>Driver</option>
                <option value="Admin" <?php echo $role === 'Admin' ? 'selected' : ''; ?>>Admin</option>
            </select>
        <?php else: ?>
            <input type="hidden" id="role" name="role" value="<?php echo $role; ?>">
        <?php endif; ?>

        <div class="button-group">
            <button type="button" onclick="<?php echo $isUpdate ? 'updateEmployee()' : 'addEmployee()'; ?>">
                <?php echo $isUpdate ? 'Update Employee' : 'Create Employee'; ?>
            </button>
            <button type="button" class="clear-button" onClick="clearForm()">Clear</button>
        </div>
    </form>
</div>

<!-- Popup Modal -->
<div id="popup" class="popup-overlay" style="display: none;">
  <div class="popup-content">
    <p id="popup-message"></p>
    <button onclick="closePopup()">OK</button>
  </div>
</div>
<style>
.popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}
.popup-content {
    background: #fff;
    padding: 20px 30px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}
.popup-content button {
    margin-top: 15px;
    padding: 8px 16px;
    border: none;
    background-color: #4CAF50;
    color: white;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
}
</style>


<script>

    function showPopup(message) {
        document.getElementById('popup-message').textContent = message;
        document.getElementById('popup').style.display = 'flex';
    }

    function closePopup() {
        document.getElementById('popup').style.display = 'none';
    }

    document.getElementById('togglePassword').addEventListener('change', function() {
        const passwordField = document.getElementById('password');
        passwordField.type = this.checked ? 'text' : 'password';
    });

    function clearForm() {
        document.getElementById("userForm").reset();
    }

    function addEmployee() {
        const endpoint = '<?php echo URLROOT; ?>/SuperAdminPages/addemp';

        const password = document.getElementById("password").value.trim();
        const confirmPassword = document.getElementById("confirm_password").value.trim();

        if (password !== confirmPassword) {
            showPopup("Passwords do not match. Please try again.");
            return;
        }

        const formData = {
            name: document.getElementById("name").value.trim(),
            nic: document.getElementById("nic").value.trim(),
            address: document.getElementById("address").value.trim(),
            contactNo: document.getElementById("contactNo").value.trim(),
            email: document.getElementById("email").value.trim(),
            password: password,
            role: document.getElementById("role").value.trim()
        };

        console.log("Form Data:", formData); // Debugging line to check form data
        // Send the data to the server
        fetch(endpoint, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                showPopup("Employee created successfully!");
                setTimeout(() => {
                    window.location.href = '<?php echo URLROOT; ?>/SuperAdminPages/employees';
                }, 2000);
            } else if (data.errors) {
                // Build error messages
                let errorMessages = "";

                if (data.errors.name_err) {
                    errorMessages += "Name: " + data.errors.name_err + "\n";
                }
                if (data.errors.contactNo_err) {
                    errorMessages += "Contact Number: " + data.errors.contactNo_err + "\n";
                }
                if (data.errors.nic_err) {
                    errorMessages += "NIC: " + data.errors.nic_err + "\n";
                }
                if (data.errors.address_err) {
                    errorMessages += "Address: " + data.errors.address_err + "\n";
                }
                if (data.errors.email_err) {
                    errorMessages += "Email: " + data.errors.email_err + "\n";
                }
                if (data.errors.password_err) {
                    errorMessages += "Password: " + data.errors.password_err + "\n";
                }
                if (data.errors.role_err) {
                    errorMessages += "Role: " + data.errors.role_err + "\n";
                }

                showPopup(errorMessages.trim()); // Show all errors nicely
            } else {
                // Other server error (not validation)
                showPopup("Error: " + (data.message || "An error occurred."));
            }
        })
        .catch(error => {
            showPopup("An error occurred: " + error.message);
        });

    }

    function updateEmployee() {
        const endpoint = '<?php echo URLROOT; ?>/SuperAdminPages/updateEmployee';

        const formData = {
            employee_id: document.getElementById("employee_id").value.trim(),
            name: document.getElementById("name").value.trim(),
            nic: document.getElementById("nic").value.trim(),
            address: document.getElementById("address").value.trim(),
            contactNo: document.getElementById("contactNo").value.trim(),
            email: document.getElementById("email").value.trim()
        };

        fetch(endpoint, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
           if (data.status === "success") {
                showPopup("Employee updated successfully!");
                setTimeout(() => {
                    window.location.href = '<?php echo URLROOT; ?>/SuperAdminPages/employees';
                }, 2000);
            }
            else if (data.errors) {
                // Build and display validation error messages
                let errorMessages = "";

                if (data.errors.name_err) {
                    errorMessages += "Name: " + data.errors.name_err + "\n";
                }
                if (data.errors.contactNo_err) {
                    errorMessages += "Contact Number: " + data.errors.contactNo_err + "\n";
                }
                if (data.errors.nic_err) {
                    errorMessages += "NIC: " + data.errors.nic_err + "\n";
                }
                if (data.errors.address_err) {
                    errorMessages += "Address: " + data.errors.address_err + "\n";
                }
                if (data.errors.email_err) {
                    errorMessages += "Email: " + data.errors.email_err + "\n";
                }

                showPopup(errorMessages.trim());
            } else {
                showPopup("Error: " + (data.message || "An error occurred."));
            }
        })
        .catch(error => {
            showPopup("An error occurred: " + error.message);
        });
    }

</script>
</body>
</html>