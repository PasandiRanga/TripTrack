<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Admin']);
?>
<?php
    $scheduleId = isset($_GET['scheduleId']) ? urldecode($_GET['scheduleId']) : '';
    $licenseId = isset($_GET['License_id']) ? urldecode($_GET['License_id']) : '';
    $date = isset($_GET['date']) ? urldecode($_GET['date']) : '';
    $departureTime = isset($_GET['departureTime']) ? urldecode($_GET['departureTime']) : '';
    $arrivalTime = isset($_GET['arrivalTime']) ? urldecode($_GET['arrivalTime']) : '';
    $duration = isset($_GET['duration']) ? urldecode($_GET['duration']) : '';
    $type = isset($_GET['type']) ? urldecode($_GET['type']) : '';
    $direction = isset($_GET['direction']) ? urldecode($_GET['direction']) : '';
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
<div class="box">
    <h1><?php echo $isUpdate ? 'Update Schedule' : 'Add New Schedule'; ?></h1>

    <!-- Schedule form -->
    <form id="schedule-form" method="POST" action="<?php echo $isUpdate ? URLROOT . '/SuperAdminPages/updateschedule' : URLROOT . '/SuperAdminPages/addschedule'; ?>">

        <?php if ($isUpdate): ?>
            <input type="hidden" id="scheduleId" name="scheduleId" value="<?php echo htmlspecialchars($scheduleId); ?>">
        <?php endif; ?>

        <div class="form-group">
            <label for="License_id">License ID:</label>
            <select id="License_id" name="License_id" required>
                <?php if (!$isUpdate): ?>
                    <option value="">Select BusID</option>
                <?php endif; ?>
                <?php foreach ($data['availableBuses'] as $bus): ?>
                    <option value="<?php echo $bus['License_id']; ?>" 
                        data-seats="<?php echo $bus['passengers']; ?>" 
                        <?php echo $bus['License_id'] == $licenseId ? 'selected' : ''; ?>>
                        <?php echo $bus['License_id']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="availableSeats">Available Seats:</label>
            <input type="number" id="availableSeats" name="availableSeats" value="<?php echo $isUpdate ? htmlspecialchars($data['availableSeats'] ?? '') : ''; ?>" <?php echo $isUpdate ? 'disabled' : 'readonly'; ?>>
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
            <input type="time" id="departureTime" name="departureTime" value="<?php echo $isUpdate ? htmlspecialchars($departureTime) : ''; ?>" required>
        </div>

        <div class="form-group">
            <label for="arrivalTime">Arrival Time:</label>
            <input type="time" id="arrivalTime" name="arrivalTime" value="<?php echo htmlspecialchars($arrivalTime); ?>" required>
        </div>

        <div class="form-group">
            <label for="duration">Duration:</label>
            <input type="text" id="duration" name="duration" value="<?php echo htmlspecialchars($duration); ?>" placeholder="e.g., 09:30:00" readonly required>
        </div>



        <!-- Form buttons -->
        <div class="button-group">
            <button type="submit"><?php echo $isUpdate ? 'Update Schedule' : 'Add Schedule'; ?></button>
            <button type="button" class="clear-button" onclick="clearForm()">Clear</button>
        </div>
    </form>

    <div class="popup-overlay" id="popupOverlay">
        <div class="popup-box">
            <p id="popupMessage"></p>
            <button onclick="closePopup()">OK</button>
        </div>
    </div>
</div>

<script>

 document.addEventListener("DOMContentLoaded", function () {
        // Function to clear the form fields
        function clearForm() {
            document.getElementById("schedule-form").reset();
            document.getElementById("scheduleId").value = ""; // Clear schedule ID
        }

         // Function to show the popup
        function showPopup(message) {
            const popupOverlay = document.getElementById("popupOverlay");
            const popupMessage = document.getElementById("popupMessage");

            popupMessage.innerText = message;
            popupOverlay.style.display = "flex";
        }

        // Function to close the popup
        function closePopup() {
            const popupOverlay = document.getElementById("popupOverlay");
            popupOverlay.style.display = "none";
        }


        // Function to update availableSeats when License_id is selected
        document.getElementById("License_id").addEventListener("change", function () {
            let selectedOption = this.options[this.selectedIndex];
            let availableSeats = selectedOption.getAttribute("data-seats");
            document.getElementById("availableSeats").value = availableSeats || "";
        });

        function calculateDuration() {
            const departureTime = document.getElementById("departureTime").value;
            const arrivalTime = document.getElementById("arrivalTime").value;
            const durationInput = document.getElementById("duration");

            if (departureTime && arrivalTime) {
                const [depHours, depMinutes] = departureTime.split(":").map(Number);
                const [arrHours, arrMinutes] = arrivalTime.split(":").map(Number);

                let depTotalMinutes = depHours * 60 + depMinutes;
                let arrTotalMinutes = arrHours * 60 + arrMinutes;

                if (arrTotalMinutes < depTotalMinutes) {
                    arrTotalMinutes += 24 * 60; // Handle next-day arrival
                }

                const diffMinutes = arrTotalMinutes - depTotalMinutes;
                const hours = Math.floor(diffMinutes / 60);
                const minutes = diffMinutes % 60;

                durationInput.value = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:00`;
            } else {
                durationInput.value = "";
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
                        showPopup(isUpdate ? "Schedule updated successfully!" : "Schedule added successfully!");
                        document.getElementById("popupOverlay").querySelector("button").onclick = function () {
                            window.location.href = '<?php echo URLROOT; ?>/SuperAdminPages/schedule';
                        };
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