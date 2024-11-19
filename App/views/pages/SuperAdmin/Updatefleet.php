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
    <form id="fleet-form" method="POST" action="<?php echo URLROOT; ?>/SuperAdminPages/updateBus">
    <div class="form-group">
        <label for="bus_id">Bus ID:</label>
        <input type="text" id="bus_id" name="bus_id" value="<?php echo isset($data['busDetails']['busId']) ? htmlspecialchars($data['busDetails']['busId']) : ''; ?>" readonly>
    </div>

    <div class="form-group">
        <label for="licence_id">Licence ID:</label>
        <input type="text" id="License_id" name="License_id" value="<?php echo isset($data['busDetails']['License_id']) ? htmlspecialchars($data['busDetails']['License_id']) : ''; ?>">
    </div>

    <div class="form-group">
        <label for="route_no">Route No:</label>
        <input type="text" id="routeNumber" name="routeNumber"  value="<?php echo isset($data['busDetails']['routeNumber']) ? htmlspecialchars($data['busDetails']['routeNumber']) : ''; ?>">
    </div>

    <div class="form-group">
        <label for="route">Route:</label>
        <input type="text" id="route" name="route"  value="<?php echo isset($data['busDetails']['route']) ? htmlspecialchars($data['busDetails']['route']) : ''; ?>">
    </div>

    <div class="form-group">
        <label for="bus_type">Bus Type:</label>
        <input type="text" id="busType" name="busType"  value="<?php echo isset($data['busDetails']['busType']) ? htmlspecialchars($data['busDetails']['busType']) : ''; ?>">
    </div>

    <div class="form-group">
        <label for="stops">Stops:</label>
        <textarea types="text" id="stops" name="stops" rows="3"><?php echo isset($data['busDetails']['stops']) ? htmlspecialchars($data['busDetails']['stops']) : ''; ?>"></textarea>
    </div>

    <div class="form-group">
        <label for="starts">Starts:</label>
        <input type="text" id="start_location" name="start_location"  value="<?php echo isset($data['busDetails']['start_location']) ? htmlspecialchars($data['busDetails']['start_location']) : ''; ?>">
    </div>

    <div class="form-group">
        <label for="destination">Destination:</label>
        <input type="text" id="destination" name="destination"  value="<?php echo isset($data['busDetails']['destination']) ? htmlspecialchars($data['busDetails']['destination']) : ''; ?>">
    </div>

    <div class="form-group">
        <label for="ratings">Ratings:</label>
        <input type="number" id="rating" name="rating" value="<?php echo isset($data['busDetails']['rating']) ? htmlspecialchars($data['busDetails']['rating']) : ''; ?>">
    </div>

    <div class="form-group">
        <label for="passengers">Passengers:</label>
        <input type="number" id="passengers" name="passengers"  value="<?php echo isset($data['busDetails']['passengers']) ? htmlspecialchars($data['busDetails']['passengers']) : ''; ?>">
    </div>

    <div class="form-group">
        <label for="price">Price:</label>
        <input type="number" id="price" name="price"  value="<?php echo isset($data['busDetails']['price']) ? htmlspecialchars($data['busDetails']['price']) : ''; ?>">
    </div>

    <div class="form-group">
        <label for="price_per_km">Price per KM:</label>
        <input type="number" id="priceperkm" name="priceperkm"  value="<?php echo isset($data['busDetails']['priceperkm']) ? htmlspecialchars($data['busDetails']['priceperkm']) : ''; ?>">
    </div>

    <!-- Submit and Clear buttons -->
    <button class="button" type="submit">Update Bus</button>
    <button class="button" onclick="clearForm()">Clear</button>
</form>


    <script>
        // Go back to the previous page
        function validateForm() {
            // Get form elements
            const busId = document.getElementById("bus_id").value.trim();
            const licenseId = document.getElementById("License_id").value.trim();
            const routeNumber = document.getElementById("routeNumber").value.trim();
            const route = document.getElementById("route").value.trim();
            const busType = document.getElementById("busType").value.trim();
            const stops = document.getElementById("stops").value.trim();
            const startLocation = document.getElementById("start_location").value.trim();
            const destination = document.getElementById("destination").value.trim();
            const rating = document.getElementById("rating").value.trim();
            const passengers = document.getElementById("passengers").value.trim();
            const price = document.getElementById("price").value.trim();
            const pricePerKm = document.getElementById("priceperkm").value.trim();

            // Validation checks
            if (!busId || !licenseId || !routeNumber || !route || !busType || !startLocation || !destination) {
                alert("Please fill all the required fields.");
                return false;
            }

            if (isNaN(rating) || rating < 0 || rating > 5) {
                alert("Rating must be a number between 0 and 5.");
                return false;
            }

            if (isNaN(passengers) || passengers <= 0) {
                alert("Passengers must be a positive number.");
                return false;
            }

            if (isNaN(price) || price <= 0) {
                alert("Price must be a positive number.");
                return false;
            }

            if (isNaN(pricePerKm) || pricePerKm <= 0) {
                alert("Price per KM must be a positive number.");
                return false;
            }

            if (!stops) {
                alert("Please enter stops.");
                return false;
            }

            return true; // All validations passed
        }

        // Attach validation to form submission
        document.getElementById("fleet-form").onsubmit = function (event) {
            if (!validateForm()) {
                event.preventDefault(); // Prevent form submission if validation fails
            }
        };
        // Clear form fields
        function clearForm() {
            document.getElementById("fleet-form").reset();
        }

    </script>
</body>
</html>
