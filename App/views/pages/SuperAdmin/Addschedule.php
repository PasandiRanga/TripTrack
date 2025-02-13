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
            <label for="License_id">License ID:</label>
            <select type="text" id="License_id" name="License_id" required>
                <option value="">Select BusID</option>
                <?php foreach ($data['bus'] as $bus): ?>
                    <option value="<?php echo $bus['License_id']; ?>"
                        data-seats="<?php echo $bus['passengers']; ?>">
                        <?php echo $bus['License_id']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="availableSeats">Available Seats:</label>
            <input type="number" id="availableSeats" name="availableSeats" required readonly>
        </div>

        <div class="form-group">
            <label for="direction">Direction:</label>
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



        <!-- Form buttons -->
        <div class="button-group">
            <button type="button" onclick="clearForm()">Clear</button>
            <button type="submit">Add Schedule</button>
        </div>
    </form>

<script>

    document.addEventListener("DOMContentLoaded", function () {
        // Function to clear the form fields
        function clearForm() {
            document.getElementById("schedule-form").reset();
        }

        // Function to update availableSeats when License_id is selected
        document.getElementById("License_id").addEventListener("change", function () {
            let selectedOption = this.options[this.selectedIndex]; // Get selected <option>
            let availableSeats = selectedOption.getAttribute("data-seats"); // Get availableSeats from data attribute
            document.getElementById("availableSeats").value = availableSeats || ""; // Set input value

            console.log("Selected License ID:", selectedOption.value);
            console.log("Available Seats:", availableSeats);
        });

        function calculateDuration() {
            let departureTime = document.getElementById("departureTime").value;
            let arrivalTime = document.getElementById("arrivalTime").value;
            let durationTime = document.getElementById("duration");

            if(departureTime && arrivalTime){

                let departure = new Date(`1970-01-01T${departureTime}:00`);
                let arrival = new Date(`1970-01-01T${arrivalTime}:00`);

                if (arrival < departure){
                    arrival.setDate(arrival.getDate() + 1); //handle the nextday arrival

                }

                let diffMs = arrival - departure; //difference in miliseconds
                let hours = Math.floor(diffMs/ (1000 * 60 * 60));
                let minutes = Math.floor((diffMs % (1000*60*60)) / (1000 *60));

                let durationFormatted = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:00`;

                durationTime.value = durationFormatted;
                
            } else {
                durationTime.value = "";
            }
        }

        document.getElementById("departureTime").addEventListener("change", calculateDuration);
        document.getElementById("arrivalTime").addEventListener("change", calculateDuration);

        // Form validation function
        function validateForm() {
            const License_id = document.getElementById("License_id").value.trim();
            const date = document.getElementById("date").value;
            const departureTime = document.getElementById("departureTime").value;
            const arrivalTime = document.getElementById("arrivalTime").value;
            const duration = document.getElementById("duration").value.trim();
            const type = document.getElementById("type").value.trim();

            let errorMessage = "";

            // License ID validation
            if (License_id === "") {
                errorMessage += "Please select a Bus License ID.\n";
            }

            // Date validation
            if (date === "") {
                errorMessage += "Date is required.\n";
            }

            // Departure and Arrival Time validation
            if (departureTime === "") {
                errorMessage += "Departure time is required.\n";
            }
            if (arrivalTime === "") {
                errorMessage += "Arrival time is required.\n";
            }

            // Duration validation (ensure it's not empty)
            if (duration === "") {
                errorMessage += "Duration is required.\n";
            }

            // Type validation
            if (type === "") {
                errorMessage += "Type is required.\n";
            }

            // Display error message if validation fails
            if (errorMessage !== "") {
                alert(errorMessage);
                return false;
            }

            return true; // If no errors, allow form submission
        }

        // Handle form submission
        document.getElementById("schedule-form").addEventListener("submit", function (event) {
            event.preventDefault(); // Correct event prevention

            // Call validation before proceeding
            if (!validateForm()) {
                return;
            }

            let formData = {
                License_id: document.getElementById("License_id").value.trim(),
                availableSeats: document.getElementById("availableSeats").value.trim(),
                date: document.getElementById("date").value.trim(),
                departureTime: document.getElementById("departureTime").value.trim(),
                arrivalTime: document.getElementById("arrivalTime").value.trim(),
                duration: document.getElementById("duration").value.trim(),
                type: document.getElementById("type").value.trim(),
                direction: document.getElementById("direction").value.trim()
            };

            console.log("Form Data:", formData); // Debugging output

            fetch('<?php echo URLROOT; ?>/SuperAdminPages/addschedules', {
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
                        alert("Schedule added successfully!");
                        window.location.href = '<?php echo URLROOT; ?>/SuperAdminPages/schedule';
                    } else {
                        alert("Error: " + data.message);
                    }
                })
                .catch(error => alert("An error occurred: " + error.message));
        });
    });
</script>

</body>
</html>
