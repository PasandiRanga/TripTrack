<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Admin']);

    // Check if this is an update operation
    $isUpdate = isset($data['busDetails']);
    $licenseId = $isUpdate && isset($data['busDetails']['License_id']) ? htmlspecialchars($data['busDetails']['License_id']) : '';
    $routeNumber = $isUpdate && isset($data['busDetails']['routeNumber']) ? htmlspecialchars($data['busDetails']['routeNumber']) : '';
    $startLocation = $isUpdate && isset($data['busDetails']['start_location']) ? htmlspecialchars($data['busDetails']['start_location']) : '';
    $destination = $isUpdate && isset($data['busDetails']['destination']) ? htmlspecialchars($data['busDetails']['destination']) : '';
    $passengers = $isUpdate && isset($data['busDetails']['passengers']) ? htmlspecialchars($data['busDetails']['passengers']) : '';
    $price = $isUpdate && isset($data['busDetails']['price']) ? htmlspecialchars($data['busDetails']['price']) : '';
    $pricePerKm = $isUpdate && isset($data['busDetails']['priceperkm']) ? htmlspecialchars($data['busDetails']['priceperkm']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isUpdate ? 'Update Bus' : 'Add Bus'; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/Addfleet.css?v=<?php echo time(); ?>">
</head>
<body>
    <!-- Back button -->
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/fleet'">Back</button>

    <h1><?php echo $isUpdate ? 'Update Bus Details' : 'Add New Bus'; ?></h1>

    <!-- Fleet form -->
    <form id="fleet-form" method="POST" action="<?php echo $isUpdate ? URLROOT . '/SuperAdminPages/updateBus' : URLROOT . '/SuperAdminPages/AddFleet'; ?>" class="form-group">
        <?php
            echo '<script>console.log(' . json_encode($data) . ');</script>';
        ?>
        <!-- License ID -->
        <label for="License_id">License ID:</label>
        <input type="text" id="License_id" name="License_id" value="<?php echo $licenseId; ?>" <?php echo $isUpdate ? 'readonly' : 'required'; ?>>

        <!-- Route Number -->
        <label for="routeNumber">Route No:</label>
        <select id="routeNumber" name="routeNumber" required>
            <option value="">Select Route Number</option>
            <?php if (!empty($data['route'])): foreach ($data['route'] as $route): ?>
                <option value="<?php echo $route['routeNumber']; ?>"
                        data-price="<?php echo htmlspecialchars($route['price']); ?>"
                        data-priceperkm="<?php echo htmlspecialchars($route['priceperkm']); ?>"
                        <?php echo $route['routeNumber'] == $routeNumber ? 'selected' : ''; ?>>
                    <?php echo $route['routeNumber']; ?>
                </option>
            <?php endforeach; else: ?>
                <option value="" disabled>No routes available</option>
            <?php endif; ?>
        </select>

        <!-- Start Location -->
        <label for="start_location">Starts:</label>
        <input type="text" id="start_location" name="start_location" value="<?php echo $startLocation; ?>" required placeholder="e.g., Colombo">

        <!-- Destination -->
        <label for="destination">Destination:</label>
        <input type="text" id="destination" name="destination" value="<?php echo $destination; ?>" required placeholder="e.g., Kandy">

        <!-- Passengers -->
        <label for="passengers">Passengers:</label>
        <select id="passengers" name="passengers" class="passengers" required>
            <option value="" disabled>Select capacity</option>
            <option value="37" <?php echo $passengers == 37 ? 'selected' : ''; ?>>37</option>
            <option value="45" <?php echo $passengers == 45 ? 'selected' : ''; ?>>45</option>
            <option value="50" <?php echo $passengers == 50 ? 'selected' : ''; ?>>50</option>
            <option value="51" <?php echo $passengers == 51 ? 'selected' : ''; ?>>51</option>
        </select>

        <!-- Price -->
        <label for="price">Price:</label>
        <input type="number" id="price" name="price" value="<?php echo $price; ?>" required readonly>

        <!-- Price per KM -->
        <label for="priceperkm">Price per KM:</label>
        <input type="number" id="priceperkm" name="priceperkm" value="<?php echo $pricePerKm; ?>" required readonly>

        <!-- Submit and Clear buttons -->
        <button class="button" type="submit"><?php echo $isUpdate ? 'Update Bus' : 'Add Bus'; ?></button>
        <button class="button" type="button" onclick="clearForm()">Clear</button>
    </form>

    <div class="popup-overlay" id="popupOverlay">
        <div class="popup-box">
            <p id="popupMessage"></p>
            <button onclick="closePopup()">OK</button>
        </div>
    </div>

<script>
    // Show popup message
    function showPopup(message) {
        const popupOverlay = document.getElementById("popupOverlay");
        const popupMessage = document.getElementById("popupMessage");

        popupMessage.innerText = message;
        popupOverlay.style.display = "flex";
    }

    // Close popup
    function closePopup() {
        const popupOverlay = document.getElementById("popupOverlay");
        popupOverlay.style.display = "none";
    }

    document.addEventListener("DOMContentLoaded", function() {
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
    });

    document.getElementById("fleetForm").addEventListener("submit", function(event) {
            event.preventDefault();

            // Determine the correct endpoint based on whether it's an update or add operation
            const isUpdate = <?php echo json_encode($isUpdate); ?>;
            const endpoint = isUpdate 
                ? '<?php echo URLROOT; ?>/SuperAdminPages/updateBus' 
                : '<?php echo URLROOT; ?>/SuperAdminPages/addFleet';

            // Collect form data
            let formData = {
                License_id: document.getElementById("License_id").value.trim(),
                routeNumber: document.getElementById("routeNumber").value.trim(),
                start_location: document.getElementById("start_location").value.trim(),
                destination: document.getElementById("destination").value.trim(),
                passengers: document.getElementById("passengers").value.trim(),
                price: document.getElementById("price").value.trim(),
                priceperkm: document.getElementById("priceperkm").value.trim()
            };

            // Send the request to the appropriate endpoint
            fetch(endpoint, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(formData)
            })
            .then(response => response.text())  // Get text response first
            .then(text => {
                try {
                    return JSON.parse(text);  // Try parsing JSON
                } catch (error) {
                    throw new Error("Invalid JSON response: " + text);  // Handle non-JSON errors
                }
            })
            .then(data => {
                if (data.status === "success") {
                    showPopup(isUpdate ? "Bus updated successfully!" : "Bus added successfully!");
                    setTimeout(() => {
                        window.location.href = '<?php echo URLROOT; ?>/SuperAdminPages/fleet';
                    }, 2000);
                } else {
                    showPopup("Error: " + data.message);
                }
            })
            .catch(error => showPopup("An error occurred: " + error.message));
        });
</script>
</body>
</html>