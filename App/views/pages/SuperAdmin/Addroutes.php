<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Admin']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Assigns</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/AddRoutes.css?v=<?php echo time(); ?>">
</head>
<body>
    <!-- Back Button -->
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/routes'">Back</button>

    <!-- Page Title -->
    <h2>Add New Route</h2>

    <!-- Add Assignment Form -->
    <form id="routeForm" method="POST" action="<?php echo URLROOT; ?>/SuperAdminPages/addroutes" class="route-form">
        <label for="routeNumber">Route No:</label>
        <input type="text" id="routeNumber" name="routeNumber" placeholder="Enter Route Number" required>

        <label for="route">Route:</label>
        <input type="text" id="route" name="route" placeholder="Enter Route" required>

        <label for="stops">Stops:</label>
        <input type="text" id="stops" name="stops" placeholder="Enter Stops" required>

        <label for="stops">price:</label>
        <input type="text" id="price" name="price" placeholder="Enter price" required>

        <label for="stops">Price/km:</label>
        <input type="text" id="priceperkm" name="priceperkm" placeholder="Enter price per km" required>

        <button type="submit">Add Route</button>
    </form>
<script>
    document.getElementById("routeForm").addEventListener("submit", function(event) {
        event.preventDefault();

        let formData = {
            routeNumber: document.getElementById("routeNumber").value.trim(),
            route: document.getElementById("route").value.trim(),
            stops: document.getElementById("stops").value.trim(),
            price: document.getElementById("price").value.trim(),
            priceperkm: document.getElementById("priceperkm").value.trim()
        };

        fetch('<?php echo URLROOT; ?>/SuperAdminPages/addroute', {
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
                alert("Route added successfully!");
                window.location.href = '<?php echo URLROOT; ?>/SuperAdminPages/routes';
            } else {
                alert("Error: " + data.message);
            }
        })
        .catch(error => alert("An error occurred: " + error.message));

    });
</script>
</body>
</html>