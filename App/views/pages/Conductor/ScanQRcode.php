<?php
require_once APPROOT.'/helpers/auth_check.php';
authCheck(['Conductor', 'Driver']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Conductor/ScanQRcode.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar/navbar.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=ABeeZee&display=swap" rel="stylesheet">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan QR Code</title>
</head>

<body>
    <script>
        var userRole = <?php echo json_encode($_SESSION['userRole'] ?? 'Conductor'); ?>;
        localStorage.setItem('userRole', userRole);
    </script>

    <?php
    $userRole = $_SESSION['userRole'] ?? 'Conductor';
    $data = [
        'currentController' => 'ConductorPages',
        'currentMethod' => 'scanQRcode',
        'userRole' => $userRole
    ];
    ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/ConductorPages/home'">Back</button>

    <button class="acceptBooking-button" onclick="window.location.href='<?php echo URLROOT; ?>/ConductorPages/acceptBookingForm'">Accept Booking by ID</button>

    <!--<button id="viewLayout1" onclick="window.location.href='<?php echo URLROOT; ?>/ConductorPages/busLayout'">View Bus Layout</button>-->

    <h1>Scan QR Code</h1>

    <div class="container">
        <div class="qr-reader-container">
            <div id="qr-reader" style="width: 500px;"></div>
        </div>

        <!-- Modal for QR code result -->
        <div class="modal-overlay" id="qrModal">
            <div class="modal-content">
                
                <p id="qrResultText"></p>
                <button onclick="closeModal()">Close</button>
                <button id="viewLayout" onclick="redirectToBusLayout()">View Bus Layout</button>

            </div>
        </div>

        <script src="https://unpkg.com/html5-qrcode"></script>

        <script>

            function domReady(fn) {
                if (document.readyState === "complete" || document.readyState === "interactive") {
                    setTimeout(fn, 1);
                } else {
                    document.addEventListener("DOMContentLoaded", fn);
                }
            }

            domReady(function () {
                var lastResult, countResults = 0;

                // If QR code is found
                function onScanSuccess(decodedText) {

                    if (decodedText !== lastResult) {
                        ++countResults;
                        lastResult = decodedText;

                        // Show QR result in the modal
                        showModal(decodedText);
                    }
                }

                var htmlscanner = new Html5QrcodeScanner(
                    "qr-reader", { fps: 10, qrbox: 250 });

                htmlscanner.render(onScanSuccess);
            });

            // Function to show modal with parsed data
            function showModal(decodedText) {

                const data = parseQrText(decodedText);

                document.getElementById('qrResultText').innerHTML =
                    `<h2 class="qr-title">Booking is Accepted!!</h2>
                    <pre>${decodedText}</pre>`;

                document.getElementById('qrModal').style.display = 'flex';

                sendToServer(data["Schedule ID"], data["Seats"]);
            }

            // Function to parse QR code text into key-value pairs
            function parseQrText(decodedText) {
                const data = {};

                const lines = decodedText.split('\n');

                lines.forEach(line => {
                    if (line.includes("Schedule ID:")) {
                        const scheduleId = line.split("Schedule ID:")[1].trim();
                        data["Schedule ID"] = scheduleId;

                        // Save to localStorage
                        localStorage.setItem("scheduleId", scheduleId);
                    }

                    if (line.includes("Seats:")) {
                        let rawSeats = line.split("Seats:")[1].trim();
                        let seatArray = rawSeats.split(',').map(seat => seat.trim());

                        // Add quotes around the joined string
                        data["Seats"] = seatArray;
                    }
                });

                return data;
            }

            // Function to close modal
            function closeModal() {
                document.getElementById('qrModal').style.display = 'none';
            }

            function sendToServer(scheduleId, seats) {
                console.log("Sending to server:", scheduleId, seats);
                fetch('<?php echo URLROOT; ?>/ConductorPages/processScannedQR', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        schedule_id: scheduleId,
                        seats: seats
                    })
                })
                .then(res => {
                    if (!res.ok) {
                        throw new Error('Server response was not ok');
                    }
                    return res.json();
                })
                .then(data => {
                    console.log('Response from server:', data);

                    if (data.status === 'success') {
                        document.getElementById('qrResultText').innerHTML +=
                            `<p class="success">${data.message}</p>`;
                    } else {
                        document.getElementById('qrResultText').innerHTML +=
                            `<p class="error">${data.message}</p>`;
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    document.getElementById('qrResultText').innerHTML +=
                        '<p class="error">Error updating server. Please try again.</p>';
                });

            }

            function redirectToBusLayout() {
                const scheduleId = localStorage.getItem("scheduleId") || "[]";
                const seatsParam = encodeURIComponent(scheduleId);

                window.location.href = `<?php echo URLROOT; ?>/ConductorPages/busLayout?schedule=${seatsParam}`;
            }


        </script>
    </div>
</body>
</html>
