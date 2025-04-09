<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Admin']);
?>
<?php
    // Check if this is an update operation
    $scheduleId = $_GET['scheduleId'] ?? '';
    $driverId = $_GET['driver_id'] ?? '';
    $conductorId = $_GET['conductor_id'] ?? '';
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
        <input type="hidden" name="scheduleId" value="<?php echo htmlspecialchars($scheduleId); ?>">
    <?php endif; ?>

    <label for="driver_id">Driver ID:</label>
    <select id="driver_id" name="driver_id" required>
        <option value="">Select Driver</option>
        <?php foreach ($data['drivers'] as $driver): ?>
            <option value="<?php echo $driver['employee_id']; ?>" <?php echo $driver['employee_id'] == $driverId ? 'selected' : ''; ?>>
                <?php echo $driver['employee_id']; ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="conductor_id">Conductor ID:</label>
    <select id="conductor_id" name="conductor_id" required>
        <option value="">Select Conductor</option>
        <?php foreach ($data['conductors'] as $conductor): ?>
            <option value="<?php echo $conductor['employee_id']; ?>" <?php echo $conductor['employee_id'] == $conductorId ? 'selected' : ''; ?>>
                <?php echo $conductor['employee_id']; ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit"><?php echo $isUpdate ? 'Update Assign' : 'Add Assign'; ?></button>
</form>

    <script>
        document.getElementById("assignForm").addEventListener("submit", function(event) {
        event.preventDefault();

        // Collect form data
        const scheduleId = <?php echo $isUpdate ? '"<?php echo $scheduleId; ?>"' : 'document.getElementById("scheduleId").value.trim()'; ?>;
        const driverId = document.getElementById("driver_id").value.trim();
        const conductorId = document.getElementById("conductor_id").value.trim();

        // Validate form data
        if (!scheduleId || !driverId || !conductorId) {
            alert("All fields are required.");
            return;
        }

        // Determine the correct endpoint
        const isUpdate = <?php echo json_encode($isUpdate); ?>;
        const endpoint = isUpdate 
            ? '<?php echo URLROOT; ?>/SuperAdminPages/updateassign' 
            : '<?php echo URLROOT; ?>/SuperAdminPages/addassigns';

        // Prepare form data
        const formData = {
            scheduleId: scheduleId,
            driver_id: driverId,
            conductor_id: conductorId
        };

        // Send the request to the appropriate endpoint
        fetch(endpoint, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
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