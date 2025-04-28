<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Admin']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Routes</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/Routes.css?v=<?php echo time(); ?>">
    <style>
    
        .popup-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        
        .popup-content {
            background-color: white;
            width: 400px;
            height: auto;
            padding: 30px 25px;
            box-sizing: border-box;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
            position: relative;
            max-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            border-top: 5px solid #00897b;
            animation: fadeIn 0.3s ease;
        }

        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        
        .popup-content h2 {
            color: #424242;
            font-size: 24px;
            margin-bottom: 10px;
            margin-top: 5px;
        }

        
        .popup-content h4 {
            color: #757575;
            font-size: 16px;
            font-weight: normal;
            margin-top: 0;
            margin-bottom: 30px;
        }

        
        .popup-content p {
            display: flex;
            justify-content: center;
            gap: 20px;
            width: 100%;
            margin-bottom: 10px;
        }

        
        .popup-content button {
            width: 120px;
            height: 40px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        
        .popup-content .btn-yes {
            background-color: white;
            color: #e22222;
            border: 2px solid #e22222;
        }

        .popup-content .btn-yes:hover {
            background-color: #e22222;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(226, 34, 34, 0.2);
        }

        
        .popup-content .btn-no {
            background-color: white;
            color: #00897b;
            border: 2px solid #00897b;
        }

        .popup-content .btn-no:hover {
            background-color: #00897b;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 137, 123, 0.2);
        }

        
        .ok-button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/home'">Back</button>
    <div class="box">
        <div class="header-container">
            <h1>Routes</h1>
        </div>

        <div class="add-button-container">
            <a href="<?php echo URLROOT; ?>/SuperAdminPages/AddRoute">
                <button class="add-button">Add Route</button>
            </a>
        </div>

        
        <div class="routes-container">
            <table class="routes-table">
                <thead>
                    <tr>
                        <th>Route No.</th>
                        <th>Route</th>
                        <th>Stops</th>
                        <th>Price</th>
                        <th>Price per km</th>
                        <th>Update</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if(isset($data['routes']) && is_array($data['routes'])) {
                        foreach ($data['routes'] as $routes) {
                            echo "<tr>";
                            echo "<td>{$routes['routeNumber']}</td>";
                            echo "<td>{$routes['route']}</td>";
                            echo "<td>{$routes['stops']}</td>";
                            echo "<td>{$routes['price']}</td>";
                            echo "<td>{$routes['priceperkm']}</td>";
                            echo "<td><button class='update-btn' onclick='updateRoute(\"{$routes['routeNumber']}\")'>Update</button></td>";
                            echo "<td><button class='delete-btn' onclick='openConfirmPopup(\"{$routes['routeNumber']}\")'>Delete</button></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No bus data available.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div id="confirmModal" class="popup-modal">
        <div class="popup-content">
            <h2>Are you sure?</h2>
            <p>Do you really want to delete this route?</p>
            <div class="popup-buttons">
                <button class="btn-yes" id="confirmYesBtn">Yes</button>
                <button class="btn-no" onclick="closeConfirm()">No</button>
            </div>
        </div>
    </div>

    
    <div id="successModal" class="popup-modal">
        <div class="popup-content">
            <h2>Success</h2>
            <p id="popupMessage">Route deleted successfully!</p>
            <button class="ok-button" onclick="closeSuccess()">OK</button>
        </div>
    </div>

    <script>
        let routeToDelete = null;

        
        function openConfirmPopup(routeNumber) {
            routeToDelete = routeNumber;
            document.getElementById('confirmModal').style.display = 'flex';
        }

        
        function closeConfirm() {
            document.getElementById('confirmModal').style.display = 'none';
        }

        
        document.getElementById('confirmYesBtn').addEventListener('click', function() {
            deleteRoute(routeToDelete);
        });
        function updateRoute(routeNumber) {
            
            const rows = Array.from(document.querySelectorAll("table.routes-table tbody tr"));
            const row = rows.find(row => row.cells[0].innerText === routeNumber);

            if (row) {
                
                const route = row.cells[1].innerText;
                const stops = row.cells[2].innerText;
                const price = row.cells[3].innerText;
                const pricePerKm = row.cells[4].innerText;

            
                const url = new URL('<?php echo URLROOT; ?>/SuperAdminPages/AddRoute');
                url.searchParams.append('routeNumber', routeNumber);
                url.searchParams.append('route', route);
                url.searchParams.append('stops', encodeURIComponent(stops));
                url.searchParams.append('price', price);
                url.searchParams.append('priceperkm', pricePerKm);

                window.location.href = url.toString();
            } else {
                alert("Route not found.");
            }
        }
    
        function deleteRoute(routeNumber) {
            fetch('<?php echo URLROOT; ?>/SuperAdminPages/deleteRoute', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ routeNumber })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    
                    const rows = Array.from(document.querySelectorAll("table.routes-table tbody tr"));
                    const row = rows.find(row => row.cells[0].innerText === routeNumber);
                    if (row) {
                        row.remove();
                    }

                    
                    document.getElementById('popupMessage').innerText = data.message;
                    document.getElementById('successModal').style.display = 'flex'; 
                    closeConfirm(); 
                } else {
                    alert(data.message);
                    closeConfirm(); 
                }
            })
            .catch(() => alert('Error deleting the route.'));
        }

        
        function closeSuccess() {
            document.getElementById('successModal').style.display = 'none'; 
        }
    </script>
</body>
</html>
