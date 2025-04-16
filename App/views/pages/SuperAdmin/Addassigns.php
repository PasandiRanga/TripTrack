<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Admin']);
?>
<?php
    // Check if this is an update operation
    $scheduleId = $_GET['scheduleId'] ?? '';
    $driverName = $_GET['driver_name'] ?? '';
    $conductorName = $_GET['conductor_name'] ?? '';
    $isUpdate = !empty($scheduleId); // Determine if it's an update operation

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isUpdate ? 'Update Assign' : 'Add Assign'; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/AddAssigns.css?v=<?php echo time(); ?>">
</head>
<body>
    <!-- Back Button -->
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/assigns'">Back</button>

    <!-- Page Title -->
    <h2><?php echo $isUpdate ? 'Update Assign' : 'Add New Assign'; ?></h2>

    <!-- Add/Update Assignment Form -->
<form id="assignForm" method="POST" action="<?php echo $isUpdate ? URLROOT . '/SuperAdminPages/updateassign' : URLROOT . '/SuperAdminPages/addassigns'; ?>" class="assign-form">
    <label for="scheduleId">Schedule ID:</label>
    <select id="scheduleId" name="scheduleId" <?php echo $isUpdate ? 'disabled' : 'required'; ?>>
        <option value="">Select Schedule</option>
        <?php foreach ($data['schedules'] as $schedule): ?>
            <option value="<?php echo $schedule['scheduleId']; ?>" <?php echo $schedule['scheduleId'] == $scheduleId ? 'selected' : ''; ?>>
                <?php echo $schedule['scheduleId']; ?>
            </option>
        <?php endforeach; ?>
    </select>

    <?php if ($isUpdate): ?>
        <!-- Hidden input to include scheduleId in the form data for updates -->
        <input type="text" name="scheduleId" value="<?php echo htmlspecialchars($scheduleId); ?>" readonly>
    <?php endif; ?>

    <label for="driver_name">Driver Name:</label>
    <select id="driver_name" name="driver_name" required>
        <?php if (!$isUpdate): ?>
            <option value="">Select Driver</option>
        <?php endif; ?>
        <?php foreach ($data['drivers'] as $driver): ?>
            <option value="<?php echo $driver['name']; ?>" <?php echo $driver['name'] == $driverName ? 'selected' : ''; ?>>
            <?php echo $driver['name']; ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="conductor_name">Conductor Name:</label>
    <select id="conductor_name" name="conductor_name" required>
        <?php if (!$isUpdate): ?>
            <option value="">Select Conductor</option>
        <?php endif; ?>
        <?php foreach ($data['conductors'] as $conductor): ?>
            <option value="<?php echo $conductor['name']; ?>" <?php echo $conductor['name'] == $conductorName ? 'selected' : ''; ?>>
            <?php echo $conductor['name']; ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit"><?php echo $isUpdate ? 'Update Assign' : 'Add Assign'; ?></button>
</form>

    <script>
        document.getElementById("assignForm").addEventListener("submit", function(event) {
        event.preventDefault();

        // Collect form data
        let scheduleId;
        <?php if ($isUpdate): ?>
            scheduleId = "<?php echo $scheduleId; ?>"; 
        <?php else: ?>
            scheduleId = document.getElementById("scheduleId").value.trim(); 
        <?php endif; ?>        
        const driverName = document.getElementById("driver_name").value.trim();
        const conductorName = document.getElementById("conductor_name").value.trim();

        // Validate form data
        if (!scheduleId || !driverName || !conductorName) {
            alert("All fields are required. view and try again.");
            return;
        }

        console.log("Form Data:", { scheduleId, driverName, conductorName });

        // Determine the correct endpoint
        const isUpdate = <?php echo json_encode($isUpdate); ?>; // Pass PHP boolean as JavaScript boolean
        const endpoint = isUpdate ? <?php echo json_encode(URLROOT . '/SuperAdminPages/updateassign'); ?> : <?php echo json_encode(URLROOT . '/SuperAdminPages/addassigns'); ?>;

        // Prepare form data
        const formData = {
            scheduleId: scheduleId,
            driverName: driverName,
            conductorName: conductorName
        };
        console.log("Form Data to Send:", formData);
        console.log("isUpdate:", isUpdate);
        console.log("Endpoint:", endpoint);
        // Send the request to the appropriate endpoint
        fetch(endpoint, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            console.log("Server Response:", data);
            if (data.status === "success") {
                alert(isUpdate ? "Assign updated successfully!" : "Assign added successfully!");
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