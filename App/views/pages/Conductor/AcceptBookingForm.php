<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Conductor/acceptBookingForm.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar/navbar.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=ABeeZee&display=swap" rel="stylesheet">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accept Booking Form</title>
</head>

<body>
    <script>
        var userRole = <?php echo json_encode($_SESSION['userRole'] ?? 'Conductor'); ?>;
        localStorage.setItem('userRole', userRole);
    </script>

    <?php if (!empty($data['bookingData'])): ?>
        <script>
            const bookingData = <?php echo json_encode($data['bookingData']); ?>;
            window.addEventListener('DOMContentLoaded', () => {
                showModal({ message: 'Booking Accepted!', bookingData: bookingData });
            });
        </script>
    <?php endif; ?>


    <?php
    $userRole = $_SESSION['userRole'] ?? 'Conductor';
    $data = [
        'currentController' => 'ConductorPages',
        'currentMethod' => 'acceptBookingForm',
        'userRole' => $userRole
    ];

    $booking_id = $booking_id ?? '';
    $nic = $nic ?? '';

    ?>

    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/ConductorPages/newHome'">Back</button>

    <div class="form-container">
        <h2 class="form-title">Accept Booking</h2>

        <form id="acceptBookingForm" method="POST" action="<?php echo URLROOT . '/ConductorPages/acceptBookingForm'; ?>">
            <div class="form-row">
                <div class="form-field">
                    <label for="bookingId">Booking ID:</label>
                    <input type="text" id="bookingId" name="bookingId" value="<?php echo htmlspecialchars($booking_id); ?>" required>
                </div>

                <div class="form-field">
                    <label for="nic">NIC:</label>
                    <input type="text" id="nic" name="nic" value="<?php echo htmlspecialchars($nic); ?>" required>
                </div>
            </div>

            <button type="submit" id="submitBtn" class="submit-btn">Submit</button>
        </form>
    </div>

    <div class="modal-overlay" id="resultModal" style="display: none;">
        <div class="modal-content">        
            <p id="resultText"></p>
            <button id="close" class="close" onclick="closeModal()">Close</button>
            <button id="viewLayout" class="viewLayout" onclick="redirectToBusLayout()">View Bus Layout</button>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const nicInput = document.getElementById('nic');
            const bookingIdInput = document.getElementById('bookingId');
            const form = document.getElementById('acceptBookingForm');
            const submitBtn = document.getElementById('submitBtn');

            const nicError = document.createElement('div');
            nicError.id = 'nicError';
            nicError.classList.add('error-message'); 
            nicInput.parentNode.appendChild(nicError);

            // Create Booking ID error message element
            const bookingIdError = document.createElement('div');
            bookingIdError.id = 'bookingIdError';
            bookingIdError.classList.add('error-message'); 
            bookingIdInput.parentNode.appendChild(bookingIdError);

            function validateNIC() {
                const nicValue = nicInput.value.trim();
                const validOldNIC = /^[0-9]{9}[vV]$/;
                const validNewNIC = /^[0-9]{12}$/;

                if (nicValue === "") {
                    nicInput.classList.remove('input-error');
                    nicError.textContent = "";
                    return false;
                }
                else if (validOldNIC.test(nicValue) || validNewNIC.test(nicValue)) {
                    nicInput.classList.remove('input-error');
                    nicError.textContent = "";
                    return true;
                } else {
                    nicInput.classList.add('input-error');
                    nicError.textContent = "NIC must be either 9 digits followed by V or exactly 12 digits";
                    return false;
                }
            }

            function validateBookingId() {
                const bookingIdValue = bookingIdInput.value.trim();
                const validBookingId = /^[RG][0-9]{3}$/; // R + 3 digits

                if (bookingIdValue === "") {
                    bookingIdInput.classList.remove('input-error');
                    bookingIdError.textContent = "";
                    return false;
                }
                else if (validBookingId.test(bookingIdValue)) {
                    bookingIdInput.classList.remove('input-error');
                    bookingIdError.textContent = "";
                    return true;
                } else {
                    bookingIdInput.classList.add('input-error');
                    bookingIdError.textContent = "Booking ID must start with R or G followed by 3 digits (e.g., R123 or G456)";
                    return false;
                }
            }

            function checkFormValidity() {
                const isNicValid = validateNIC();
                const isBookingIdValid = validateBookingId();
                submitBtn.disabled = !(isNicValid && isBookingIdValid);
            }

            nicInput.addEventListener('input', function () {
                nicInput.value = nicInput.value.toUpperCase(); // force uppercase V
                validateNIC();
                checkFormValidity();
            });

            bookingIdInput.addEventListener('input', function () {
                bookingIdInput.value = bookingIdInput.value.toUpperCase(); // auto capitalize R if needed
                validateBookingId();
                checkFormValidity();
            });

            form.addEventListener('submit', function(event) {
                if (!(validateNIC() && validateBookingId())) {
                    event.preventDefault(); // stop submission if invalid
                }
            });

            // Initial validation in case fields are pre-filled
            checkFormValidity();
        });

        const form = document.getElementById("acceptBookingForm");
        const popup = document.getElementById("resultModal");

        form.addEventListener("submit", function(event) {
            event.preventDefault(); // Stop default form submit

            const formData = new FormData(form);

            fetch(form.action, {
                method: "POST",
                body: formData
            })
            .then(response => response.json()) 
            .then(data => {
                // Show success or error message from backend
                showModal(data);
            })
            .catch(error => {
                showModal({ message: "An error occurred: " + error });
            });
        });

        function closeModal() {
            popup.style.display = "none";
        }

        function showModal(data) {
            const popupMessage = document.getElementById("resultText");

            let html = `<h2 class="result-title">${data.message || 'Booking Accepted!'}</h2>`;

            if (data.bookingData) {
                const bookingData = data.bookingData;

                html += `
                    <p><strong>Schedule ID:</strong> ${bookingData.schedule_id}</p>
                    <p><strong>Selected Seats:</strong> ${bookingData.selected_seats}</p>
                    <p><strong>Total Price:</strong> ${bookingData.total_price}</p>
                    <p><strong>Payment Method:</strong> ${bookingData.paymentMethod}</p>
                `;
            }

            popupMessage.innerHTML = html;
            popup.style.display = "block";
        }

        function redirectToBusLayout() {
            const scheduleId = localStorage.getItem("scheduleId") || "[]";
            const seatsParam = encodeURIComponent(scheduleId);

            window.location.href = `<?php echo URLROOT; ?>/ConductorPages/busLayout?schedule=${seatsParam}`;
        }

    </script>
</body>
</html>