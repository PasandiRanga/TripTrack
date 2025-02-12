<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Admin']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Assigns</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/AddAssigns.css?v=<?php echo time(); ?>">
</head>
<body>
    <!-- Back Button -->
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/assigns'">Back</button>

    <!-- Page Title -->
    <h2>Add New Assing</h2>

    <!-- Add Assignment Form -->
    <form id="assignForm" method="POST" action="<?php echo URLROOT; ?>/SuperAdminPages/addassigns" class="assign-form">
        <label for="scheduleId">Schedule ID:</label>
        <input type="text" id="scheduleId" name="scheduleId" placeholder="Enter Schedule ID" required>

        <label for="driver_id">Driver ID:</label>
        <input type="text" id="driver_id" name="driver_id" placeholder="Enter Driver ID" required>

        <label for="conductor_id">Conductor ID:</label>
        <input type="text" id="conductor_id" name="conductor_id" placeholder="Enter Conductor ID" required>

        <!--

        <label for="assign_time">Assign Time:</label>
        <input type="time" id="assign_time" name="assign_time" required>

        <label for="assign_date">Assign Date:</label>
        <input type="date" id="assign_date" name="assign_date" required>

        -->

        <button type="submit">Add Assign</button>
    </form>

    <script>
       document.getElementById("assignForm").addEventListener("submit", function(event) {
            event.preventDefault();

            let formData = {
                scheduleId: document.getElementById("scheduleId").value.trim(),
                driver_id: document.getElementById("driver_id").value.trim(),
                conductor_id: document.getElementById("conductor_id").value.trim(),
                //assign_time: document.getElementById("assign_time").value.trim(),
                //assign_date: document.getElementById("assign_date").value.trim()
            };

            fetch('<?php echo URLROOT; ?>/SuperAdminPages/addassigns', {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(formData)
            })
            .then(response => response.text())  // Get text response first
            .then(text => {
                try {
                    return JSON.parse(text);  // Try parsing JSON
                } catch (error) {
                    throw new Error("Invalid JSON response: " + text);  // Handle non-JSON errors
                }
            })
            .then(data => {
                if (data.status === "success") {
                    alert("Assign added successfully!");
                    window.location.href = '<?php echo URLROOT; ?>/SuperAdminPages/assigns';
                } else {
                    alert("Error: " + data.message);
                }
            })
            .catch(error => alert("An error occurred: " + error.message));
        });

    </script>
</body>
</html>
