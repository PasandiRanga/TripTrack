<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Admin']);
?>
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
    <form id="fleet-form" method="POST" action="<?php echo URLROOT; ?>/SuperAdminPages/AddFleet">
    <div class="form-group">
        <label for="licence_id">Licence ID:</label>
        <input type="text" id="licence_id" name="licence_id" required>
    </div>

    <div class="form-group">
        <label for="route_no">Route No:</label>
        <input type="text" id="route_no" name="route_no" required>
    </div>

    <div class="form-group">
        <label for="route">Route:</label>
        <input type="text" id="route" name="route" required placeholder="e.g., Colombo - Kandy">
    </div>

    <div class="form-group">
        <label for="bus_type">Bus Type:</label>
        <input type="text" id="bus_type" name="bus_type" required placeholder="e.g., Luxury, Semi-Luxury">
    </div>

    <div class="form-group">
        <label for="stops">Stops:</label>
        <textarea id="stops" name="stops" rows="3" required placeholder="e.g., Colombo, Kegalle, Kandy"></textarea>
    </div>

    <div class="form-group">
        <label for="starts">Starts:</label>
        <input type="text" id="starts" name="starts" required placeholder="e.g., Colombo">
    </div>

    <div class="form-group">
        <label for="destination">Destination:</label>
        <input type="text" id="destination" name="destination" required placeholder="e.g., Kandy">
    </div>

    <div class="form-group">
        <label for="passengers">Passengers:</label>
        <input type="number" id="passengers" name="passengers" required min="1">
    </div>

    <div class="form-group">
        <label for="price">Price:</label>
        <input type="number" id="price" name="price" required min="0" step="0.01" placeholder="e.g., 1200.00">
    </div>

    <div class="form-group">
        <label for="price_per_km">Price per KM:</label>
        <input type="number" id="price_per_km" name="price_per_km" required min="0" step="0.01" placeholder="e.g., 10.00">
    </div>

    <!-- Submit and Clear buttons -->
    <button class="button" type="submit">Add Bus</button>
    <button class="button" onclick="clearForm()">Clear</button>
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
