<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Admin']);
?>
<?php
    $routeNumber = $_GET['routeNumber'] ?? '';
    $route = $_GET['route'] ?? '';
    $stops = isset($_GET['stops']) ? urldecode($_GET['stops']) : '';
    $price = $_GET['price'] ?? '';
    $pricePerKm = $_GET['priceperkm'] ?? '';
    $isUpdate = !empty($routeNumber); // Check if it's an update operation
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isUpdate ? 'Update Route' : 'Add Route'; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/AddRoutes.css?v=<?php echo time(); ?>">
</head>
<body>
    <!-- Back Button -->
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/routes'">Back</button>
    
    <!-- Popup Modal -->
    <div id="popup" class="popup">
        <div class="popup-content">
            <span class="close" id="popupClose">&times;</span>
            <h3 id="popupMessage"></h3>
            <button id="popupOkButton">OK</button>
        </div>
    </div>

    <div class="box">
        <!-- Page Title -->
        <h2><?php echo $isUpdate ? 'Update Route' : 'Add New Route'; ?></h2>

        <!-- Add Assignment Form -->
        <form id="routeForm" method="POST" action="<?php echo $isUpdate ? URLROOT . '/SuperAdminPages/updateRoute' : URLROOT . '/SuperAdminPages/addRoute'; ?>" class="route-form">
            <label for="routeNumber">Route No:</label>
            <input type="text" id="routeNumber" name="routeNumber" placeholder="Enter Route Number" value="<?php echo htmlspecialchars($routeNumber); ?>" <?php echo $isUpdate ? 'readonly' : ''; ?> required>

            <label for="route">Route:</label>
            <input type="text" id="route" name="route" placeholder="Enter Route" value="<?php echo htmlspecialchars($route); ?>" required>

            <label for="stops">Stops:</label>
            <textarea id="stops" name="stops" placeholder="Enter Stops" required><?php echo htmlspecialchars($stops); ?></textarea>

            <label for="price">Price:</label>
            <input type="text" id="price" name="price" placeholder="Enter Price" value="<?php echo htmlspecialchars($price); ?>" required>

            <label for="priceperkm">Price/km:</label>
            <input type="text" id="priceperkm" name="priceperkm" placeholder="Enter Price per km" value="<?php echo htmlspecialchars($pricePerKm); ?>" required>

            <button type="submit"><?php echo $isUpdate ? 'Update Route' : 'Add Route'; ?></button>
            <button type="button" class="clear-button" onclick="clearForm()">Clear</button>
        </form>
    </div>

    <script>
        function clearForm() {
            if (confirm("Are you sure you want to clear the form?")) {
                document.getElementById("route").value = "";
                document.getElementById("stops").value = "";
                document.getElementById("price").value = "";
                document.getElementById("priceperkm").value = "";

                // Only clear routeNumber if it's NOT an update operation
                const isUpdate = <?php echo json_encode($isUpdate); ?>;
                if (!isUpdate) {
                    document.getElementById("routeNumber").value = "";
                }
            }
        }

        document.getElementById("routeForm").addEventListener("submit", function(event) {
            event.preventDefault();

            // Determine the correct endpoint based on whether it's an update or add operation
            const isUpdate = <?php echo json_encode($isUpdate); ?>;
            const endpoint = isUpdate 
                ? '<?php echo URLROOT; ?>/SuperAdminPages/updateRoute' 
                : '<?php echo URLROOT; ?>/SuperAdminPages/addRoute';

            // Collect form data
            let formData = {
                routeNumber: document.getElementById("routeNumber").value.trim(),
                route: document.getElementById("route").value.trim(),
                stops: document.getElementById("stops").value.trim(),
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
                    // Show the success message in the popup
                    const message = isUpdate ? "Route updated successfully!" : "Route added successfully!";
                    document.getElementById("popupMessage").textContent = message;
                    document.getElementById("popup").style.display = "flex";  // Show the popup
                } else {
                    alert("Error: " + data.message);
                }
            })
            .catch(error => alert("An error occurred: " + error.message));
        });

        // Close the popup when the user clicks the "OK" button
        document.getElementById("popupOkButton").addEventListener("click", function() {
            document.getElementById("popup").style.display = "none";  // Hide the popup
            window.location.href = '<?php echo URLROOT; ?>/SuperAdminPages/routes';  // Redirect to routes page
        });

        // Close the popup when the user clicks the "X"
        document.getElementById("popupClose").addEventListener("click", function() {
            document.getElementById("popup").style.display = "none";  // Hide the popup
            window.location.href = '<?php echo URLROOT; ?>/SuperAdminPages/routes';  // Redirect to routes page
        });
    </script>

    <style>
        /* Popup Styles */
.popup {
    display: none;  /* Hidden by default */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.popup-content {
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    width: 400px;  /* Adjusted width */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Soft shadow */
}

#popupMessage {
    font-size: 18px;
    margin-bottom: 20px; /* Add space between message and button */
    color: #333;
}

#popupOkButton {
    padding: 12px 30px;
    background-color: #006064; /* Primary color */
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

#popupOkButton:hover {
    background-color: #006064;  /* Darker shade for hover effect */
}

.close {
    position: absolute;
    top: 5px;
    right: 5px;
    font-size: 24px;
    color: #333;
    cursor: pointer;
    font-weight: bold;
}

/* Added Hover effect for Close button */
.close:hover {
    color: #ff0000;  /* Red for the close button on hover */
}

    </style>
</body>
</html>
