<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Admin']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Schedule</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/Addschedule.css?v=<?php echo time(); ?>">
</head>
<body>
    <!-- Back button -->
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/schedule'">Back</button>

    <h1>Add New Schedule</h1>

    <!-- Schedule form -->
    <form id="schedule-form" action="submit_schedule.php" method="POST" onsubmit="return validateForm()">
        <div class="form-group">
            <label for="licenseId">License ID:</label>
            <input type="text" id="licenseId" name="licenseId" required>
        </div>

        <div class="form-group">
            <label for="direction">Derection:</label>
            <select type="text" id="direction" name="direction" required>
                <option>Onward</option>
                <option>Backward</option>
            </select>
        </div>

        <div>
            <label for="type">Type:</label>
            <select type="text" id="type" name="type">
                <option>Daily</option>
                <option>Weekend</option>
                <option>Special</option>
            </select>
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
            <input type="text" id="duration" name="duration" placeholder="e.g., 09:30:00" required>
        </div>

        <div class="form-group">
            <label for="price">Price:</label>
            <input type="text" id="price" name="price" placeholder="e.g., Rs. 700" required>
        </div>

        <!-- Form buttons -->
        <div class="button-group">
            <button type="button" onclick="clearForm()">Clear</button>
            <button type="submit">Add Schedule</button>
        </div>
    </form>

    <script>
        // Function to clear the form fields
        function clearForm() {
            document.getElementById("schedule-form").reset();
        }

        // Form validation function with validation logic
        function validateForm() {
            const licenseId = document.getElementById("licenseId").value.trim();
            const date = document.getElementById("date").value;
            const departureTime = document.getElementById("departureTime").value;
            const arrivalTime = document.getElementById("arrivalTime").value;
            const duration = document.getElementById("duration").value.trim();
            const price = document.getElementById("price").value.trim();

            let errorMessage = "";

            // License ID validation (must be numeric)
            if (licenseId === "" || isNaN(licenseId)) {
                errorMessage += "License ID must be a valid number.\n";
            }

            // Date validation
            if (date === "") {
                errorMessage += "Date is required.\n";
            }

            // Departure and Arrival Time validation (must be in HH:MM format)
            if (departureTime === "") {
                errorMessage += "Departure time is required.\n";
            }
            if (arrivalTime === "") {
                errorMessage += "Arrival time is required.\n";
            }

            // Duration validation (simple format check, e.g., "9 hours 30 mins")
            const durationPattern = /^[0-9]+ hours [0-9]+ mins$/;
            if (!durationPattern.test(duration)) {
                errorMessage += "Duration must be in the format 'X hours Y mins'.\n";
            }

            // Price validation (must start with 'Rs.' followed by a number)
            const pricePattern = /^Rs\.?\s?\d+$/;
            if (!pricePattern.test(price)) {
                errorMessage += "Price must be in the format 'Rs. amount'.\n";
            }

            // Display error message if any validation fails
            if (errorMessage !== "") {
                alert(errorMessage);
                return false;
            }

            // If no errors, proceed with form submission
            return true;
        }
    </script>
</body>
</html>
