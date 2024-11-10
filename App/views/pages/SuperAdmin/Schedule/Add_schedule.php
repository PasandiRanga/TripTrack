<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Schedule</title>
    <link rel="stylesheet" href="Add_schedule.css">
</head>
<body>
    <!-- Back button -->
    <button class="back-button" onclick="window.history.back()">Back</button>

    <h1>Add New Schedule</h1>

    <!-- Schedule form -->
    <form id="schedule-form" action="submit_schedule.php" method="POST" onsubmit="return validateForm()">
        <div class="form-group">
            <label for="scheduleId">Schedule ID:</label>
            <input type="text" id="scheduleId" name="scheduleId" required>
        </div>

        <div class="form-group">
            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required>
        </div>

        <div class="form-group">
            <label for="departureTime">Departure Time:</label>
            <input type="time" id="departureTime" name="departureTime" required>
        </div>

        <div class="form-group">
            <label for="arrivalTime">Arrival Time:</label>
            <input type="time" id="arrivalTime" name="arrivalTime" required>
        </div>

        <div class="form-group">
            <label for="duration">Duration:</label>
            <input type="text" id="duration" name="duration" placeholder="e.g., 9 hours 30 mins" required>
        </div>

        <div class="form-group">
            <label for="price">Price:</label>
            <input type="text" id="price" name="price" placeholder="e.g., Rs. 700" required>
        </div>

        <div class="form-group">
            <label for="busId">Bus ID:</label>
            <input type="text" id="busId" name="busId" required>
        </div>

        <div class="form-group">
            <label for="busNumber">Bus Number:</label>
            <input type="text" id="busNumber" name="busNumber" required>
        </div>

        <div class="form-group">
            <label for="route">Route:</label>
            <input type="text" id="route" name="route" placeholder="e.g., Colombo - Ampara" required>
        </div>

        <!-- Form buttons -->
        <div class="button-group">
            <button type="button" onclick="clearForm()">Clear</button>
            <button type="submit">Add Schedule</button>
        </div>
    </form>

    <script src="Add_schedule.js"></script>
</body>
</html>
