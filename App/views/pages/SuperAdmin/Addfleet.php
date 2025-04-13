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
    <form id="fleet-form" method="POST" action="<?php echo URLROOT; ?>/SuperAdminPages/AddFleet" class="form-group">

    
    <label for="License_id">Licence ID:</label>
    <input type="text" id="License_id" name="License_id" required>

    
    <label for="routeNumber">Route No:</label>
    <select id="routeNumber" name="routeNumber" required>
        <option value="">Select Route Number</option>
        <?php foreach ($data['route'] as $route): ?>
            <option value="<?php echo $route['routeNumber']; ?>"
                    data-price="<?php echo htmlspecialchars($route['price']); ?>"
                    data-priceperkm="<?php echo htmlspecialchars($route['priceperkm']); ?>"> 
                <?php echo $route['routeNumber']; ?>
            </option>
        <?php endforeach; ?>
    </select>    
    <!--check on the last " in the option values don't put the spaceses between double queotes-->
    

    <!-- <div class="form-group">
        <label for="bus_type">Bus Type:</label>
        <input type="text" id="bus_type" name="bus_type" required placeholder="e.g., Luxury, Semi-Luxury">
    </div> -->
        <!-- <select id="bus_type" class="bus-type" name="bus_type">
            <option value="1">1</option>
            <option value="2">2</option>
    </div> -->

    <!--
    <div class="form-group">
        <label for="stops">Stops:</label>
        <textarea id="stops" name="stops" rows="3" required placeholder="e.g., Colombo, Kegalle, Kandy"></textarea>
    </div>  -->

    <label for="start_location">Starts:</label>
    <input type="text" id="start_location" name="start_location" required placeholder="e.g., Colombo">


    <label for="destination">Destination:</label>
    <input type="text" id="destination" name="destination" required placeholder="e.g., Kandy">


    <label for="passengers">Passengers:</label>
    <select id="passengers" name="passengers" class="passengers" required>
        <option value="" disabled selected>Select capacity</option>
        <option value="37">37</option>
        <option value="45">45</option>
        <option value="50">50</option>
        <option value="50">51</option>
    </select>

    <label for="price">Price:</label>
    <input type="number" id="price" name="price" required readonly>


    <label for="priceperkm">Price per KM:</label>
    <input type="number" id="priceperkm" name="priceperkm" required readonly>


    <!-- Submit and Clear buttons -->
    <button class="button" type="submit">Add Bus</button>
    <button class="button" onclick="clearForm()">Clear</button>

</form>

    <div class="popup-overlay" id="popupOverlay">
        <div class="popup-box">
            <p id="popupMessage"></p>
            <button onclick="closePopup()">OK</button>
        </div>
    </div>

<script>

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

    document.addEventListener("DOMContentLoaded", function() {
        function goBack() {
            window.history.back();
        }

        document.getElementById("routeNumber").addEventListener("change", function () {
            let selectedOption = this.options[this.selectedIndex];

            if (selectedOption) {  // Ensure an option is selected
                let price = selectedOption.getAttribute("data-price") || ""; 
                let priceperkm = selectedOption.getAttribute("data-priceperkm") || "";

                document.getElementById("price").value = price;
                document.getElementById("priceperkm").value = priceperkm;
            }
        });

        document.getElementById("fleet-form").addEventListener("submit", function(event) {
            event.preventDefault();

            let formData = {
                License_id: document.getElementById("License_id").value.trim(),
                routeNumber: document.getElementById("routeNumber").value.trim(),
                start_location: document.getElementById("start_location").value.trim(),
                destination: document.getElementById("destination").value.trim(),
                passengers: document.getElementById("passengers").value.trim(),
                price: document.getElementById("price").value.trim(),
                priceperkm: document.getElementById("priceperkm").value.trim()
            };

            console.log("Form Data:", formData);

            fetch('<?php echo URLROOT; ?>/SuperAdminPages/addfleet', {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(formData)
            })
                .then(response => response.text()) // Get text response first
                .then(text => {
                    try {
                        return JSON.parse(text); // Try parsing JSON
                    } catch (error) {
                        throw new Error("Invalid JSON response: " + text); // Handle non-JSON errors
                    }
                })
                .then(data => {
                    if (data.status === "success") {
                        showPopup("Fleet added successfully!");
                        setTimeout(() => {
                            window.location.href = '<?php echo URLROOT; ?>/SuperAdminPages/fleet';
                        }, 2000); // Redirect after 2 seconds
                    } else {
                        showPopup("Error: " + data.message);
                    }
                })
                .catch(error => showPopup("An error occurred: " + error.message));
        });

        // Clear form fields
        function clearForm() {
            document.getElementById("fleet-form").reset();
        }
    });
        

    </script>
</body>
</html>
