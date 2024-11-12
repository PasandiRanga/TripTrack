<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Bus</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/Addfleet.css?v=<?php echo time(); ?>">
</head>
<body>
    <!-- Back button -->
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/fleet'">Back </button>

    <h1>Add New Bus</h1>

    <!-- Fleet form -->
    <form id="fleet-form" >
        <div class="form-group">
            <label for="licence_id">Licence ID:</label>
            <input type="text" id="licence_id" name="licence_id" required>
        </div>
        
        <div class="form-group">
            <label for="driver_id">Driver ID:</label>
            <input type="text" id="driver_id" name="driver_id" required>
        </div>
        
        <div class="form-group">
            <label for="conductor_id">Conductor ID:</label>
            <input type="text" id="conductor_id" name="conductor_id" required>
        </div>
        
        <div class="form-group">
            <label for="no_of_seats">No of Seats:</label>
            <input type="number" id="no_of_seats" name="no_of_seats" required min="1">
        </div>
        
        <div class="form-group">
            <label for="bus_route_no">Bus Route No:</label>
            <input type="text" id="bus_route_no" name="bus_route_no" required>
        </div>
        
        <!-- Submit and Clear buttons -->
        <button type="button">Add Bus</button>
        <button type="button" onclick="clearForm()">Clear</button>
    </form>

    <script>
                // Go back to the previous page
        function goBack() {
            window.history.back();
        }

        // Handle form submission
        function submitFleetForm(event) {
            event.preventDefault();

            const fleetData = {
                licence_id: document.getElementById("licence_id").value,
                driver_id: document.getElementById("driver_id").value,
                conductor_id: document.getElementById("conductor_id").value,
                no_of_seats: document.getElementById("no_of_seats").value,
                bus_route_no: document.getElementById("bus_route_no").value
            };

            console.log("Fleet added:", fleetData);
            alert("Fleet added successfully!");

            // Optionally, clear the form fields after submission
            clearForm();
        }

        // Clear form fields
        function clearForm() {
            document.getElementById("fleet-form").reset();
        }

    </script>
</body>
</html>
