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
                <button id="viewLayout" onclick="window.location.href='<?php echo URLROOT; ?>/ConductorPages/busLayout'">View Bus Layout</button>
            </div>
        </div>

        <script src="https://unpkg.com/html5-qrcode"></script>
        <script>
            // Function to parse QR code text into key-value pairs
            function parseQrText(decodedText) {
                const lines = decodedText.split('\n');
                const data = {};

                lines.forEach(line => {
                    const [key, value] = line.split(':');
                    if (value) {
                        data[key.trim()] = value.trim();
                    }
                });

                return data;
            }

            // Function to show modal with parsed data
            function showModal(decodedText) {
                const data = parseQrText(decodedText);

                if (data["Seats"]) {
                    let acceptedSeats = JSON.parse(localStorage.getItem("acceptedSeats") || "[]");
                    const newSeats = data["Seats"].split(',').map(seat => seat.trim());
                    
                    // Add new seats to accepted list if not already there
                    newSeats.forEach(seat => {
                        if (!acceptedSeats.includes(seat)) {
                            acceptedSeats.push(seat);
                        }
                    });

                    localStorage.setItem("acceptedSeats", JSON.stringify(acceptedSeats));
                }

                document.getElementById('qrResultText').innerHTML =
                    `<h2 class="qr-title">Booking is Accepted!!</h2>
                    <strong>Booking Receipt</strong><br>
                    Schedule ID: ${data["Schedule ID"]}<br>
                    Seats: ${data["Seats"]}<br>
                    Total Price: ${data["Total Price"]}`;
                    

                document.getElementById('qrModal').style.display = 'flex';

                sendToServer(data["Schedule ID"], data["Seats"]);
            }

            // Function to close modal
            function closeModal() {
                document.getElementById('qrModal').style.display = 'none';
            }

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
                function onScanSuccess(decodedText, decodedResult) {
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

            function sendToServer(scheduleId, seats) {
                fetch('<?php echo URLROOT; ?>/ConductorPages/scanQRcode', {
                    method: 'POST',
                    
                    body: new URLSearchParams({
                        schedule_id: scheduleId,
                        seats: seats
                    })
                })
                .then(res => res.json())
                .then(data => {
                    console.log(data.message);
                })
                .catch(err => console.error('Error:', err));
            }

        </script>
    </div>
</body>
</html>
