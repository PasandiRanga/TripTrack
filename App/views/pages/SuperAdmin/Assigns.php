<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Admin']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assigns</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/Assigns.css?v=<?php echo time(); ?>">
</head>
<body>
    <!-- Back Button -->
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/home'">Back</button>

    <!-- Page Title -->
    <h2>Assigns</h2>

    <!-- Add Assign Button -->
    <div class="top-actions">
        <button class="add-assign-btn" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/addassigns'">Add Assigns</button>
    </div>

    <!-- Assignments Table -->
    <div class="assign-table-container">
        <table class="assign-table">
            <thead>
                <tr>
                    <th>Schedule ID</th>
                    <th>Conductor ID</th>
                    <th>Driver ID</th>
                    <th>Assign Time</th>
                    <th>Assign Date</th>
                    <th>Update</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Example data for assignments (Replace with dynamic data from the database)
                /*
                $assigns = [
                    ['schedule_id' => 101, 'conductor_id' => 1, 'driver_id' => 5, 'time' => '10:00 AM', 'date' => '2024-11-23'],
                    ['schedule_id' => 102, 'conductor_id' => 2, 'driver_id' => 6, 'time' => '11:30 AM', 'date' => '2024-11-23'],
                    ['schedule_id' => 103, 'conductor_id' => 3, 'driver_id' => 7, 'time' => '01:00 PM', 'date' => '2024-11-23']
                ]; 
                */
                if(isset($data['assign']) && is_array($data['assign'])){
                    foreach ($data['assign'] as $assign) {
                        echo "<tr>
                                <td>{$assign['scheduleId']}</td>
                                <td>{$assign['conductor_id']}</td>
                                <td>{$assign['driver_id']}</td>
                                <td>{$assign['assign_time']}</td>
                                <td>{$assign['assign_date']}</td>
                                <td><button class='update-btn' onclick=\"window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/updateassign/{$assign['scheduleId']}'\">Update</button></td>
                                <td><button class='delete-btn' onclick=\"deleteAssigns({$assign['scheduleId']})\">Delete</button></td>
                            </tr>";
                    }
                } else {
                        echo "<tr><td colspan='14'>No Assigns data available.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Function to handle the Delete button click
        function deleteAssigns(scheduleId) {
            if (confirm("Are you sure you want to delete this assignment?")) {
                alert(`Assignment with Schedule ID ${scheduleId} has been deleted.`);
                // Implement actual deletion logic with an AJAX request or form submission
            }
        }
    </script>
</body>
</html>
