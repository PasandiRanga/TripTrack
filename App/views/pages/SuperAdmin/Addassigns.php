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
    <h2>Add New Assignment</h2>

    <!-- Add Assignment Form -->
    <form id="assignForm" method="POST" action="<?php echo URLROOT; ?>/SuperAdminPages/addassigns" class="assign-form">
        <label for="schedule_id">Schedule ID:</label>
        <input type="text" id="schedule_id" name="schedule_id" placeholder="Enter Schedule ID" required>

        <label for="conductor_id">Conductor ID:</label>
        <input type="text" id="conductor_id" name="conductor_id" placeholder="Enter Conductor ID" required>

        <label for="driver_id">Driver ID:</label>
        <input type="text" id="driver_id" name="driver_id" placeholder="Enter Driver ID" required>

        <label for="assignment_time">Assignment Time:</label>
        <input type="time" id="assignment_time" name="assignment_time" required>

        <label for="assignment_date">Assignment Date:</label>
        <input type="date" id="assignment_date" name="assignment_date" required>

        <button type="submit">Add Assignment</button>
    </form>

    <script>
        // Optional: Form validation or processing if needed
        document.getElementById("assignForm").addEventListener("submit", function(event) {
            // Optionally handle form submission or validation
            alert("Assignment added successfully!");
        });
    </script>
</body>
</html>
