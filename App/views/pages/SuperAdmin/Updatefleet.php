<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Fleet</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/Updatefleet.css?v=<?php echo time(); ?>">
</head>
<body>

    <!-- Back button -->
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/fleet'">Back </button>

    <h1>Update</h1>

    <!-- Fleet form -->
    <form id="fleet-form" method="POST" action="<?php echo URLROOT; ?>/SuperAdminPages/Updatefleet">
    <div class="form-group">
        <label for="bus_id">Bus ID:</label>
        <input type="text" id="bus_id" name="bus_id" value="<?php echo $busDetails['busID'] ?>">
    </div>

    <div class="form-group">
        <label for="licence_id">Licence ID:</label>
        <input type="text" id="licence_id" name="licence_id" value="<?php echo $busDetails['Licence_id'] ?>">
    </div>

    <div class="form-group">
        <label for="route_no">Route No:</label>
        <input type="text" id="route_no" name="route_no"  value="<?php echo $busDetails['routeNumber'] ?>">
    </div>

    <div class="form-group">
        <label for="route">Route:</label>
        <input type="text" id="route" name="route"  value="<?php echo $busDetails['route'] ?>">
    </div>

    <div class="form-group">
        <label for="bus_type">Bus Type:</label>
        <input type="text" id="bus_type" name="bus_type"  value="<?php echo $busDetails['busType'] ?>">
    </div>

    <div class="form-group">
        <label for="stops">Stops:</label>
        <textarea id="stops" name="stops" rows="3"  value="<?php echo $busDetails['stops'] ?>"></textarea>
    </div>

    <div class="form-group">
        <label for="starts">Starts:</label>
        <input type="text" id="starts" name="starts"  value="<?php echo $busDetails['start_location'] ?>">
    </div>

    <div class="form-group">
        <label for="destination">Destination:</label>
        <input type="text" id="destination" name="destination"  value="<?php echo $busDetails['destination'] ?>">
    </div>

    <div class="form-group">
        <label for="ratings">Ratings:</label>
        <input type="number" id="ratings" name="ratings"  value="<?php echo $busDetails['rating'] ?>">
    </div>

    <div class="form-group">
        <label for="passengers">Passengers:</label>
        <input type="number" id="passengers" name="passengers"  value="<?php echo $busDetails['passengers'] ?>">
    </div>

    <div class="form-group">
        <label for="price">Price:</label>
        <input type="number" id="price" name="price"  value="<?php echo $busDetails['price'] ?>">
    </div>

    <div class="form-group">
        <label for="price_per_km">Price per KM:</label>
        <input type="number" id="price_per_km" name="price_per_km"  value="<?php echo $busDetails['priceperkm'] ?>">
    </div>

    <!-- Submit and Clear buttons -->
    <button class="button" type="submit">Update Bus</button>
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
