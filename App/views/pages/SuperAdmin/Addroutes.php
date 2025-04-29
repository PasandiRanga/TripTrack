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
    $isUpdate = !empty($routeNumber); 
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
    
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/routes'">Back</button>
    
    
    <div id="popup" class="popup">
        <div class="popup-content">
            <span class="close" id="popupClose">&times;</span>
            <h3 id="popupMessage"></h3>
            <button id="popupOkButton">OK</button>
        </div>
    </div>

    <div class="box">
        
        <h2><?php echo $isUpdate ? 'Update Route' : 'Add New Route'; ?></h2>

        
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

                
                const isUpdate = <?php echo json_encode($isUpdate); ?>;
                if (!isUpdate) {
                    document.getElementById("routeNumber").value = "";
                }
            }
        }

        document.getElementById("routeForm").addEventListener("submit", function(event) {
            event.preventDefault();

            
            const isUpdate = <?php echo json_encode($isUpdate); ?>;
            const endpoint = isUpdate 
                ? '<?php echo URLROOT; ?>/SuperAdminPages/updateRoute' 
                : '<?php echo URLROOT; ?>/SuperAdminPages/addRoute';

            
            let formData = {
                routeNumber: document.getElementById("routeNumber").value.trim(),
                route: document.getElementById("route").value.trim(),
                stops: document.getElementById("stops").value.trim(),
                price: document.getElementById("price").value.trim(),
                priceperkm: document.getElementById("priceperkm").value.trim()
            };

            
            fetch(endpoint, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(formData)
            })
            .then(response => response.text())  
            .then(text => {
                try {
                    return JSON.parse(text);  
                } catch (error) {
                    throw new Error("Invalid JSON response: " + text);  
                }
            })
            .then(data => {
                if (data.status === "success") {
                    
                    const message = isUpdate ? "Route updated successfully!" : "Route added successfully!";
                    document.getElementById("popupMessage").textContent = message;
                    document.getElementById("popup").style.display = "flex";  
                } else {
                    alert("Error: " + data.message);
                }
            })
            .catch(error => alert("An error occurred: " + error.message));
        });

        
        document.getElementById("popupOkButton").addEventListener("click", function() {
            document.getElementById("popup").style.display = "none";  
            window.location.href = '<?php echo URLROOT; ?>/SuperAdminPages/routes';  
        });

        
        document.getElementById("popupClose").addEventListener("click", function() {
            document.getElementById("popup").style.display = "none";  
            window.location.href = '<?php echo URLROOT; ?>/SuperAdminPages/routes'; 
        });
    </script>

    <style>
        
.popup {
    display: none;  
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
    width: 400px;  
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); 
}

#popupMessage {
    font-size: 18px;
    margin-bottom: 20px; 
    color: #333;
}

#popupOkButton {
    padding: 12px 30px;
    background-color: #006064; 
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

#popupOkButton:hover {
    background-color: #006064;  
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


.close:hover {
    color: #ff0000;  
}

    </style>
</body>
</html>
