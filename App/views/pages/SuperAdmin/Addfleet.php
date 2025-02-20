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

    
    <label for="licence_id">Licence ID:</label>
    <input type="text" id="licence_id" name="licence_id" required>

    
    <label for="routeNumber">Route No:</label>
    <select id="routeNumber" name="routeNumber" required>
        <option value="">Select Route Number</option>
        <?php foreach ($data['route'] as $route): ?>
            <option value="<?php echo $route['routeNumber']; ?>"
                    data-route="<?php echo htmlspecialchars($route['route']); ?>"
                    data-price="<?php echo htmlspecialchars($route['price']); ?>"
                    data-priceperkm="<?php echo htmlspecialchars($route['priceperkm']); ?>"> 
                <?php echo $route['routeNumber']; ?>
            </option>
        <?php endforeach; ?>
    </select>    
    <!--check on the last " in the option values don't put the spaceses between double queotes-->
    
    <label for="route">Route:</label>
    <input type="text" id="route" name="route" required readonly>
    

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

    <label for="starts">Starts:</label>
    <input type="text" id="starts" name="starts" required placeholder="e.g., Colombo">


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


    <script>
                // Go back to the previous page
        function goBack() {
            window.history.back();
        }

        document.getElementById("routeNumber").addEventListener("change", function () {
            let selectedOption = this.options[this.selectedIndex];

            if (selectedOption) {  // Ensure an option is selected
                let route = selectedOption.getAttribute("data-route") || ""; 
                let price = selectedOption.getAttribute("data-price") || ""; 
                let priceperkm = selectedOption.getAttribute("data-priceperkm") || "";

                document.getElementById("route").value = route;
                document.getElementById("price").value = price;
                document.getElementById("priceperkm").value = priceperkm;
            }
        });


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
