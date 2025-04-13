<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Admin']);
?>
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
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/fleet'">Back</button>

    <h1>Update Fleet Details</h1>

    <!-- Fleet form -->
    <form id="fleet-form" method="POST" action="<?php echo URLROOT; ?>/SuperAdminPages/updateBus">
        <!-- License ID (Primary Key) - Read-only -->
        <div class="form-group">
            <label for="License_id">License ID:</label>
            <input 
                type="text" 
                id="License_id" 
                name="License_id" 
                value="<?php echo isset($data['busDetails']['License_id']) ? htmlspecialchars($data['busDetails']['License_id']) : ''; ?>" 
                readonly
            >
        </div>

        <div class="form-group">
            <label for="routeNumber">Route No:</label>
            <input 
                type="text" 
                id="routeNumber" 
                name="routeNumber" 
                value="<?php echo isset($data['busDetails']['routeNumber']) ? htmlspecialchars($data['busDetails']['routeNumber']) : ''; ?>" 
                required
            >
        </div>

        <!-- <div class="form-group">
            <label for="route">Route:</label>
            <input 
                type="text" 
                id="route" 
                name="route" 
                value="<//?php echo isset($data['busDetails']['route']) ? htmlspecialchars($data['busDetails']['route']) : ''; ?>" 
                required
            >
        </div> -->

        <!--
        <div class="form-group">
            <label for="busType">Bus Type:</label>
            <select 
                id="busType" 
                class="bus-type"
                name="busType" 
                required
            >
                <option value="1" <//?php echo (isset($data['busDetails']['busType']) && $data['busDetails']['busType'] == '1') ? 'selected' : ''; ?>>1</option>
                <option value="2" <//?php echo (isset($data['busDetails']['busType']) && $data['busDetails']['busType'] == '2') ? 'selected' : ''; ?>>2</option>
            </select>
        </div>
        -->
        <!-- <div class="form-group">
            <label for="stops">Stops:</label>
            <textarea 
                id="stops" 
                name="stops" 
                rows="3" 
                required></?php echo isset($data['busDetails']['stops']) ? htmlspecialchars($data['busDetails']['stops']) : ''; ?></textarea>
        </div> -->

        <div class="form-group">
            <label for="start_location">Starts:</label>
            <input 
                type="text" 
                id="start_location" 
                name="start_location" 
                value="<?php echo isset($data['busDetails']['start_location']) ? htmlspecialchars($data['busDetails']['start_location']) : ''; ?>" 
                required
            >
        </div>

        <div class="form-group">
            <label for="destination">Destination:</label>
            <input 
                type="text" 
                id="destination" 
                name="destination" 
                value="<?php echo isset($data['busDetails']['destination']) ? htmlspecialchars($data['busDetails']['destination']) : ''; ?>" 
                required
            >
        </div>
        <!--
        <div class="form-group">
            <label for="rating">Ratings:</label>
            <input 
                type="number" 
                id="rating" 
                name="rating" 
                value="<//?php echo isset($data['busDetails']['rating']) ? htmlspecialchars($data['busDetails']['rating']) : ''; ?>" 
                min="0" 
                max="5" 
                step="0.1" 
                required
            >
        </div>
        -->
        <div class="form-group">
            <label for="passengers">Passengers:</label>
            <input 
                type="number" 
                id="passengers" 
                name="passengers" 
                value="<?php echo isset($data['busDetails']['passengers']) ? htmlspecialchars($data['busDetails']['passengers']) : ''; ?>" 
                min="1" 
                required
            >
        </div>

        <div class="form-group">
            <label for="price">Price:</label>
            <input 
                type="number" 
                id="price" 
                name="price" 
                value="<?php echo isset($data['busDetails']['price']) ? htmlspecialchars($data['busDetails']['price']) : ''; ?>" 
                min="0.1" 
                step="0.01" 
                required
            >
        </div>

        <div class="form-group">
            <label for="priceperkm">Price per KM:</label>
            <input 
                type="number" 
                id="priceperkm" 
                name="priceperkm" 
                value="<?php echo isset($data['busDetails']['priceperkm']) ? htmlspecialchars($data['busDetails']['priceperkm']) : ''; ?>" 
                min="0.1" 
                step="0.01" 
                required
            >
        </div>

        <!-- Submit and Clear buttons -->
        <button class="button" type="submit">Update Bus</button>
        <button class="button" type="button" onclick="clearForm()">Clear</button>
    </form>

    <script>
        // Validate form before submission
        function validateForm() {
            const rating = parseFloat(document.getElementById("rating").value);
            const passengers = parseInt(document.getElementById("passengers").value);
            const price = parseFloat(document.getElementById("price").value);
            const pricePerKm = parseFloat(document.getElementById("priceperkm").value);

            if (rating < 0 || rating > 5) {
                alert("Rating must be between 0 and 5.");
                return false;
            }

            if (passengers <= 0) {
                alert("Passengers must be a positive number.");
                return false;
            }

            if (price <= 0) {
                alert("Price must be greater than 0.");
                return false;
            }

            if (pricePerKm <= 0) {
                alert("Price per KM must be greater than 0.");
                return false;
            }

            return true;
        }

        // Attach validation to form submission
        document.getElementById("fleet-form").onsubmit = function (event) {
            if (!validateForm()) {
                event.preventDefault();
            }
        };

        // Clear form fields
        function clearForm() {
            document.getElementById("fleet-form").reset();
        }
    </script>
</body>
</html>
