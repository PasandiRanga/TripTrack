<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Admin']);

        
    $isUpdate = isset($_GET['License_id']) && !empty($_GET['License_id']);
    $licenseId = $isUpdate ? htmlspecialchars($_GET['License_id']) : '';
    $routeNumber = $isUpdate ? htmlspecialchars($_GET['routeNumber']) : '';
    $startLocation = $isUpdate ? htmlspecialchars($_GET['start_location']) : '';
    $destination = $isUpdate ? htmlspecialchars($_GET['destination']) : '';
    $passengers = $isUpdate ? htmlspecialchars($_GET['passengers']) : '';
    $price = $isUpdate ? htmlspecialchars($_GET['price']) : '';
    $pricePerKm = $isUpdate ? htmlspecialchars($_GET['priceperkm']) : '';


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
    
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/fleet'">Back</button>
    <div class="box">
        <h1><?php echo $isUpdate ? 'Update Bus Details' : 'Add New Bus'; ?></h1>

        
        <form id="fleet-form" method="POST" action="<?php echo $isUpdate ? URLROOT . '/SuperAdminPages/updateBus' : URLROOT . '/SuperAdminPages/AddFleet'; ?>" class="form-group">
            <?php
                echo '<script>console.log(' . json_encode($data) . ');</script>';
            ?>
        <div class="box-form">
           
            <div class="form-group">
                <label for="License_id">License ID:</label>
                <input type="text" id="License_id" name="License_id" value="<?php echo $licenseId; ?>" <?php echo $isUpdate ? 'readonly' : 'required'; ?>>
            </div>

           
            <div class="form-group">
                <label for="routeNumber">Route No:</label>
                <select id="routeNumber" name="routeNumber" required>
                    <option value="">Select Route Number</option>
                    <?php if (!empty($data['route'])): foreach ($data['route'] as $route): ?>
                        <option value="<?php echo $route['routeNumber']; ?>"
                                data-routename="<?php echo htmlspecialchars($route['route']); ?>"
                                data-price="<?php echo htmlspecialchars($route['price']); ?>"
                                data-priceperkm="<?php echo htmlspecialchars($route['priceperkm']); ?>"
                                <?php echo $route['routeNumber'] == $routeNumber ? 'selected' : ''; ?>>
                            <?php echo $route['routeNumber']; ?>
                        </option>
                    <?php endforeach; else: ?>
                        <option value="" disabled>No routes available</option>
                    <?php endif; ?>
                </select>
            </div>

            
            <div class="form-group">
                <label for="start_location">Starts:</label>
                <input type="text" id="start_location" name="start_location" value="<?php echo $startLocation; ?>" required placeholder="e.g., Colombo" readonly>
            </div>

            
            <div class="form-group">
                <label for="destination">Destination:</label>
                <input type="text" id="destination" name="destination" value="<?php echo $destination; ?>" required placeholder="e.g., Kandy" readonly>
            </div>

            
            <div class="form-group">
                <label for="passengers">Passengers:</label>
                <select id="passengers" name="passengers" class="passengers" required>
                    <option value="" disabled>Select capacity</option>
                    <option value="37" <?php echo $passengers == 37 ? 'selected' : ''; ?>>37</option>
                    <option value="45" <?php echo $passengers == 45 ? 'selected' : ''; ?>>45</option>
                    <option value="50" <?php echo $passengers == 50 ? 'selected' : ''; ?>>50</option>
                    <option value="51" <?php echo $passengers == 51 ? 'selected' : ''; ?>>51</option>
                </select>
            </div>

            
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" value="<?php echo $price; ?>" required readonly>
            </div>

            
            <div class="form-group">
                <label for="priceperkm">Price per KM:</label>
                <input type="number" id="priceperkm" name="priceperkm" value="<?php echo $pricePerKm; ?>" required readonly>
            </div>

            
            <div class="button-container">
                <button class="button" type="submit"><?php echo $isUpdate ? 'Update Bus' : 'Add Bus'; ?></button>
                <button class="button" type="button" onclick="clearForm()">Clear</button>
            </div>
        </form>
    </div>
    
    <div class="popup-overlay" id="popupOverlay">
        <div class="popup-box">
            <p id="popupMessage"></p>
            <button onclick="closePopup()">OK</button>
        </div>
    </div>

<script>

    function clearForm() {
        const isUpdate = <?php echo json_encode($isUpdate); ?>;

        if (!isUpdate) {
            document.getElementById('License_id').value = '';
        }

        document.getElementById('routeNumber').value = '';
        document.getElementById('start_location').value = '';
        document.getElementById('destination').value = '';
        document.getElementById('passengers').value = '';
        document.getElementById('price').value = '';
        document.getElementById('priceperkm').value = '';
    }

    
    function showPopup(message) {
        const popupOverlay = document.getElementById("popupOverlay");
        const popupMessage = document.getElementById("popupMessage");

        popupMessage.innerText = message;
        popupOverlay.style.display = "flex";
    }

    
    function closePopup() {
        const popupOverlay = document.getElementById("popupOverlay");
        popupOverlay.style.display = "none";
    }

    document.addEventListener("DOMContentLoaded", function () {

        const routeDropdown = document.getElementById("routeNumber");
        const priceField = document.getElementById("price");
        const pricePerKmField = document.getElementById("priceperkm");
        const startLocationField = document.getElementById("start_location");
        const destinationField = document.getElementById("destination");

    
    routeDropdown.addEventListener("change", function () {
        const selectedOption = this.options[this.selectedIndex];
        const price = selectedOption.getAttribute("data-price");
        const pricePerKm = selectedOption.getAttribute("data-priceperkm");
        const routeName = selectedOption.getAttribute("data-routename");

        
        priceField.value = price || '';
        pricePerKmField.value = pricePerKm || '';

        
        if (routeName && routeName.includes('-')) {
            const [start, destination] = routeName.split('-');
            startLocationField.value = start.trim();
            destinationField.value = destination.trim();
        } else {
            startLocationField.value = '';
            destinationField.value = '';
        }
    });

    
    document.getElementById("fleet-form").addEventListener("reset", function () {
        priceField.value = '';
        pricePerKmField.value = '';
    });

    
    document.getElementById("fleet-form").addEventListener("submit", function (event) {
        event.preventDefault();

        const isUpdate = <?php echo json_encode($isUpdate); ?>;
        const endpoint = isUpdate
            ? '<?php echo URLROOT; ?>/SuperAdminPages/updateBus'
            : '<?php echo URLROOT; ?>/SuperAdminPages/AddFleet';

        const formData = {
            License_id: document.getElementById("License_id").value.trim(),
            routeNumber: routeDropdown.value.trim(),
            start_location: startLocationField.value.trim(),
            destination: destinationField.value.trim(),
            passengers: document.getElementById("passengers").value.trim(),
            price: priceField.value.trim(),
            priceperkm: pricePerKmField.value.trim()
        };

        fetch(endpoint, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                showPopup(isUpdate ? "Bus updated successfully!" : "Bus added successfully!");
                document.getElementById("popupOverlay").querySelector("button").onclick = function () {
                    window.location.href = '<?php echo URLROOT; ?>/SuperAdminPages/fleet';
                };
            } else {
                showPopup("Error: " + data.message);
            }
        })
        .catch(error => showPopup("An error occurred: " + error.message));
    });
});


</script>
</body>
</html>