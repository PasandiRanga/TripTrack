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
        <label for="schedule_id">Schedule ID:</label>
        <input type="text" id="schedule_id" name="schedule_id" placeholder="Enter Schedule ID" required>

        <label for="conductor_id">Conductor ID:</label>
        <input type="text" id="conductor_id" name="conductor_id" placeholder="Enter Conductor ID" required>

        <label for="driver_id">Driver ID:</label>
        <input type="text" id="driver_id" name="driver_id" placeholder="Enter Driver ID" required>

        <label for="assign_time">Assign Time:</label>
        <input type="time" id="assign_time" name="assign_time" required>

        <label for="assign_date">Assign Date:</label>
        <input type="date" id="assign_date" name="assign_date" required>

        <button type="submit">Add Assign</button>
    </form>

    <script>
         document.getElementById("assignForm").addEventListener("submit", function(event) {
            event.preventDefault();

            let schedule_id = document.getElementById("schedule_id").value.trim();
            let conductor_id = document.getElementById("conductor_id").value.trim();
            let driver_id = document.getElementById("driver_id").value.trim();
            let assign_time = document.getElementById("assign_time").value.trim();
            let assign_date = document.getElementById("assign_date").value.trim();

            if (!schedule_id || !conductor_id || !driver_id || !assign_time || !assign_date) {
                alert("All fields are required!");
                return;
            }

            fetch('<?php echo URLROOT; ?>/SuperAdminPages/addassigns', {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ schedule_id, conductor_id, driver_id, assign_time, assign_date })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    alert("Assignment added successfully!");
                    window.location.href = '<?php echo URLROOT; ?>/SuperAdminPages/assigns';
                } else {
                    alert("Error: " + data.message);
                }
            })
            .catch(error => alert("An error occurred."));
        });
    </script>
</body>
</html>
