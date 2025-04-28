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
    <form id="fleet-form" method="POST" action="<?php echo URLROOT; ?>/SuperAdminPages/updateBus" class="form-group">

        <!-- License ID (Primary Key) - Read-only -->
        <label for="License_id">License ID:</label>
        <input 
            type="text" 
            id="License_id" 
            name="License_id" 
            value="<?php echo isset($data['busDetails']['License_id']) ? htmlspecialchars($data['busDetails']['License_id']) : ''; ?>" 
            readonly
        >

        <!-- Route Number -->
        <label for="routeNumber">Route No:</label>
        <select id="routeNumber" name="routeNumber" required>
            <option value="">Select Route Number</option>
            <?php foreach ($data['route'] as $route): ?>
                <option value="<?php echo $route['routeNumber']; ?>"
                        data-price="<?php echo htmlspecialchars($route['price']); ?>"
                        data-priceperkm="<?php echo htmlspecialchars($route['priceperkm']); ?>"
                        <?php echo $route['routeNumber'] == $data['busDetails']['routeNumber'] ? 'selected' : ''; ?>>
                    <?php echo $route['routeNumber']; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Start Location -->
        <label for="start_location">Starts:</label>
        <input 
            type="text" 
            id="start_location" 
            name="start_location" 
            value="<?php echo isset($data['busDetails']['start_location']) ? htmlspecialchars($data['busDetails']['start_location']) : ''; ?>" 
            required
            placeholder="e.g., Colombo"
        >

        <!-- Destination -->
        <label for="destination">Destination:</label>
        <input 
            type="text" 
            id="destination" 
            name="destination" 
            value="<?php echo isset($data['busDetails']['destination']) ? htmlspecialchars($data['busDetails']['destination']) : ''; ?>" 
            required
            placeholder="e.g., Kandy"
        >

        <!-- Passengers -->
        <label for="passengers">Passengers:</label>
        <select id="passengers" name="passengers" class="passengers" required>
            <option value="" disabled>Select capacity</option>
            <option value="37" <?php echo $data['busDetails']['passengers'] == 37 ? 'selected' : ''; ?>>37</option>
            <option value="45" <?php echo $data['busDetails']['passengers'] == 45 ? 'selected' : ''; ?>>45</option>
            <option value="50" <?php echo $data['busDetails']['passengers'] == 50 ? 'selected' : ''; ?>>50</option>
            <option value="51" <?php echo $data['busDetails']['passengers'] == 51 ? 'selected' : ''; ?>>51</option>
        </select>

        <!-- Price -->
        <label for="price">Price:</label>
        <input 
            type="number" 
            id="price" 
            name="price" 
            value="<?php echo isset($data['busDetails']['price']) ? htmlspecialchars($data['busDetails']['price']) : ''; ?>" 
            required 
            readonly
        >

        <!-- Price per KM -->
        <label for="priceperkm">Price per KM:</label>
        <input 
            type="number" 
            id="priceperkm" 
            name="priceperkm" 
            value="<?php echo isset($data['busDetails']['priceperkm']) ? htmlspecialchars($data['busDetails']['priceperkm']) : ''; ?>" 
            required 
            readonly
        >

        <!-- Submit and Clear buttons -->
        <button class="button" type="submit">Update Bus</button>
        <button class="button" type="button" onclick="clearForm()">Clear</button>
    </form>

    <script>
        // Update price and price per KM based on selected route
        document.getElementById("routeNumber").addEventListener("change", function () {
            const selectedOption = this.options[this.selectedIndex];
            const price = selectedOption.getAttribute("data-price");
            const pricePerKm = selectedOption.getAttribute("data-priceperkm");

            document.getElementById("price").value = price || '';
            document.getElementById("priceperkm").value = pricePerKm || '';
        });

        // Clear form fields
        function clearForm() {
            document.getElementById("fleet-form").reset();
            document.getElementById("price").value = '';
            document.getElementById("priceperkm").value = '';
        }
    </script>
</body>
</html>