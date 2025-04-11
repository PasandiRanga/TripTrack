<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Admin']);
?>
<?php
    $scheduleId = $_GET['scheduleId'] ?? '';
    $licenseId = $_GET['License_id'] ?? '';
    $date = $_GET['date'] ?? '';
    $departureTime = $_GET['departureTime'] ?? '';
    $arrivalTime = $_GET['arrivalTime'] ?? '';
    $duration = $_GET['duration'] ?? '';
    $type = $_GET['type'] ?? '';
    $direction = $_GET['direction'] ?? '';
    $isUpdate = !empty($scheduleId);   
   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isUpdate ? 'Update Schedule' : 'Add Schedule'; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/Addschedule.css?v=<?php echo time(); ?>">
</head>
<body>
    <!-- Back button -->
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/schedule'">Back</button>

    <h1><?php echo $isUpdate ? 'Update Schedule' : 'Add New Schedule'; ?></h1>

    <!-- Schedule form -->
    <form id="schedule-form" method="POST" action="<?php echo $isUpdate ? URLROOT . '/SuperAdminPages/updateschedule' : URLROOT . '/SuperAdminPages/addschedule'; ?>">

        <?php if ($isUpdate): ?>
            <input type="hidden" id="scheduleId" name="scheduleId" value="<?php echo htmlspecialchars($scheduleId); ?>">
        <?php endif; ?>

        <div class="form-group">
            <label for="License_id">License ID:</label>
            <select type="text" id="License_id" name="License_id" required>
                <option value="">Select BusID</option>
                <?php foreach ($data['bus'] as $bus): ?>
                    <option value="<?php echo $bus['License_id']; ?>"
                        data-seats="<?php echo $bus['passengers']; ?>"
                        <?php echo $bus['License_id'] === $licenseId ? 'selected' : ''; ?>>
                        <?php echo $bus['License_id']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="availableSeats">Available Seats:</label>
            <input type="number" id="availableSeats" name="availableSeats" value="<?php echo htmlspecialchars($data['availableSeats'] ?? ''); ?>" required readonly>
        </div>

        <div class="form-group">
            <label for="direction">Direction:</label>
            <select type="text" id="direction" name="direction" required>
                <option value="Onward" <?php echo $direction === 'Onward' ? 'selected' : ''; ?>>Onward</option>
                <option value="Backward" <?php echo $direction === 'Backward' ? 'selected' : ''; ?>>Backward</option>
            </select>
        </div>

        <div>
            <label for="type">Type:</label>
            <select type="text" id="type" name="type">
                <option value="Daily" <?php echo $type === 'Daily' ? 'selected' : ''; ?>>Daily</option>
                <option value="Weekend" <?php echo $type === 'Weekend' ? 'selected' : ''; ?>>Weekend</option>
                <option value="Special" <?php echo $type === 'Special' ? 'selected' : ''; ?>>Special</option>
            </select>
        </div>

        <div class="form-group">
            <label for="date">Date:</label>
            <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($date); ?>" required>
        </div>

        <div class="form-group">
            <label for="departureTime">Departure Time:</label>
            <input type="time" id="departureTime" name="departureTime" value="<?php echo htmlspecialchars($departureTime); ?>" required>
        </div>

        <div class="form-group">
            <label for="arrivalTime">Arrival Time:</label>
            <input type="time" id="arrivalTime" name="arrivalTime" value="<?php echo htmlspecialchars($arrivalTime); ?>" required>
        </div>

        <div class="form-group">
            <label for="duration">Duration:</label>
            <input type="text" id="duration" name="duration" value="<?php echo htmlspecialchars($duration); ?>" placeholder="e.g., 09:30:00" required>
        </div>



        <!-- Form buttons -->
        <div class="button-group">
            <button type="button" onclick="clearForm()">Clear</button>
            <button type="submit"><?php echo $isUpdate ? 'Update Schedule' : 'Add Schedule'; ?></button>
        </div>
    </form>

<script>

 document.addEventListener("DOMContentLoaded", function () {
        // Function to clear the form fields
        function clearForm() {
            document.getElementById("schedule-form").reset();
            document.getElementById("scheduleId").value = ""; // Clear schedule ID
        }

        // Function to update availableSeats when License_id is selected
        document.getElementById("License_id").addEventListener("change", function () {
            let selectedOption = this.options[this.selectedIndex];
            let availableSeats = selectedOption.getAttribute("data-seats");
            document.getElementById("availableSeats").value = availableSeats || "";
        });

        function calculateDuration() {
            let departureTime = document.getElementById("departureTime").value;
            let arrivalTime = document.getElementById("arrivalTime").value;
            let durationTime = document.getElementById("duration");

            if (departureTime && arrivalTime) {
                let departure = new Date(`1970-01-01T${departureTime}:00`);
                let arrival = new Date(`1970-01-01T${arrivalTime}:00`);

                if (arrival < departure) {
                    arrival.setDate(arrival.getDate() + 1); // Handle next-day arrival
                }

                let diffMs = arrival - departure;
                let hours = Math.floor(diffMs / (1000 * 60 * 60));
                let minutes = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));

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

            if (License_id === "") {
                errorMessage += "Please select a Bus License ID.\n";
            }
            if (date === "") {
                errorMessage += "Date is required.\n";
            }
            if (departureTime === "") {
                errorMessage += "Departure time is required.\n";
            }
            if (arrivalTime === "") {
                errorMessage += "Arrival time is required.\n";
            }
            if (duration === "") {
                errorMessage += "Duration is required.\n";
            }
            if (type === "") {
                errorMessage += "Type is required.\n";
            }

            if (errorMessage !== "") {
                alert(errorMessage);
                return false;
            }

            return true;
        }

        // Handle form submission
        document.getElementById("schedule-form").addEventListener("submit", function (event) {
            event.preventDefault();

            if (!validateForm()) {
                return;
            }

            let formData = {
                //scheduleId: document.getElementById("scheduleId").value.trim(), 
                License_id: document.getElementById("License_id").value.trim(),
                availableSeats: document.getElementById("availableSeats").value.trim(),
                date: document.getElementById("date").value.trim(),
                departureTime: document.getElementById("departureTime").value.trim(),
                arrivalTime: document.getElementById("arrivalTime").value.trim(),
                duration: document.getElementById("duration").value.trim(),
                type: document.getElementById("type").value.trim(),
                direction: document.getElementById("direction").value.trim(),
                bookedSeats: null
            };
            const isUpdate = <?php echo json_encode($isUpdate); ?>;
            if (isUpdate) {
                formData.scheduleId = "<?php echo htmlspecialchars($scheduleId); ?>";
            }
            //console.log(formData);
            const endpoint = <?php echo $isUpdate ? "'".URLROOT."/SuperAdminPages/updateSchedule'" : "'".URLROOT."/SuperAdminPages/addschedule'"; ?>;

            fetch(endpoint, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(formData)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        alert(isUpdate ? "Schedule updated successfully!" : "Schedule added successfully!");
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