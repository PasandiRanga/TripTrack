<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Bus</title>
    <link rel="stylesheet" href="Add_fleet.css">
</head>
<body>
    <!-- Back button -->
    <button class="back-button" onclick="location.href='../Fleet.php'">Back</button>

    <h1>Add New Bus</h1>

    <!-- Fleet form -->
    <form id="fleet-form" >
        <div class="form-group">
            <label for="licence_id">Licence ID:</label>
            <input type="text" id="licence_id" name="licence_id" required>
        </div>
        
        <div class="form-group">
            <label for="driver_id">Driver ID:</label>
            <input type="text" id="driver_id" name="driver_id" required>
        </div>
        
        <div class="form-group">
            <label for="conductor_id">Conductor ID:</label>
            <input type="text" id="conductor_id" name="conductor_id" required>
        </div>
        
        <div class="form-group">
            <label for="no_of_seats">No of Seats:</label>
            <input type="number" id="no_of_seats" name="no_of_seats" required min="1">
        </div>
        
        <div class="form-group">
            <label for="bus_route_no">Bus Route No:</label>
            <input type="text" id="bus_route_no" name="bus_route_no" required>
        </div>
        
        <!-- Submit and Clear buttons -->
        <button type="button">Add Bus</button>
        <button type="button" onclick="clearForm()">Clear</button>
    </form>

    <script src="Add_fleet.js"></script>
</body>
</html>
