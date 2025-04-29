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

    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/ConductorPages/newhome'">Back</button>

    <h1>Scan QR Code</h1>

    <div class="container">
        <div class="qr-reader-container">
            <div id="qr-reader" style="width: 500px;"></div>
        </div>

        <div class="modal-overlay" id="qrModal">
            <div class="modal-content">
                <div id="qrResultText"></div>
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
                var lastResult;

                function onScanSuccess(decodedText) {

                    if (decodedText !== lastResult) {
                        lastResult = decodedText;
                        processQRCode(decodedText);
                    }
                }

                var htmlscanner = new Html5QrcodeScanner(
                    "qr-reader", { fps: 10, qrbox: 250 });

                htmlscanner.render(onScanSuccess);
            });

            function processQRCode(decodedText) {
                const data = parseQrText(decodedText);
                document.getElementById('qrModal').style.display = 'flex';

                sendToServer(data.scheduleId, data.seats, decodedText);
            }

            // Function to show modal with parsed data
            function showModal(decodedText) {

                const data = parseQrText(decodedText);

                let qrContent = `<h2 class="qr-title">Booking is Accepted!!</h2>
                     <pre>${decodedText}</pre>`;

                document.getElementById('qrResultText').innerHTML = qrContent;
    
                document.getElementById('qrModal').style.display = 'flex';

                sendToServer(data["Schedule ID"], data["Seats"], qrContent);
            }

            function parseQrText(decodedText) {
                const lines = decodedText.split('\n');
                let scheduleId = '';
                let seats = [];

                lines.forEach(line => {
                    if (line.includes("Schedule ID:")) {
                        scheduleId = line.split("Schedule ID:")[1].trim();
                        localStorage.setItem("scheduleId", scheduleId);
                    }
                    if (line.includes("Seats:")) {
                        seats = line.split("Seats:")[1].split(',').map(seat => seat.trim());
                    }
                });

                return { scheduleId, seats };
            }

            // Function to close modal
            function closeModal() {
                document.getElementById('qrModal').style.display = 'none';
                document.getElementById('qrResultText').innerHTML = '';
            }

            function sendToServer(scheduleId, seats, decodedText) {
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
                    console.log("Got response:", res.status);
                    if (!res.ok) {
                        throw new Error('Server response was not ok');
                    }
                    return res.json();
                })
                .then(data => {
                    console.log("Server response data:", data);
                    if (data.status === 'error') {
                        if(data.message === 'Schedule date is not today.') {
                            document.getElementById('qrResultText').innerHTML = `
                                <h2 class="error-title">This QR is not from today schedule!</h2>
                                <p class="error">${data.message}</p>`;
                        }else {
                            document.getElementById('qrResultText').innerHTML = `
                                <h2 class="error-title">Already Accepted!</h2>
                                <p class="error">${data.message}</p>`;
                        }
                    } else {
                        console.log("Server response data inside else:", decodedText);
                        document.getElementById('qrResultText').innerHTML = `
                            <h2 class="success-title">Booking Accepted!</h2>
                            <pre>${decodedText}</pre>
                        `;
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    document.getElementById('qrResultText').innerHTML +=
                        '<p class="error">Invalid QR code. Please try again.</p>';
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
