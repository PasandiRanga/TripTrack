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

    <h1>Scan QR Code</h1>

    <div class="container">
        <div class="qr-reader-container">
            <div id="qr-reader" style="width: 500px;"></div>
        </div>

        <!-- Modal for QR code result -->
        <div class="modal-overlay" id="qrModal">
            <div class="modal-content">
                <h2>QR Code Result</h2>
                <p id="qrResultText"></p>
                <button onclick="closeModal()">Close</button>
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

            // Function to show modal with QR result
            function showModal(message) {
                document.getElementById('qrResultText').innerText = message;
                document.getElementById('qrModal').style.display = 'flex';
            }

            // Function to close modal
            function closeModal() {
                document.getElementById('qrModal').style.display = 'none';
            }

            domReady(function () {
                var lastResult, countResults = 0;

                // If QR code is found
                function onScanSuccess(decodedText, decodedResult) {
                    if (decodedText !== lastResult) {
                        ++countResults;
                        lastResult = decodedText;

                        // Show QR result in the modal
                        showModal(`You scanned: ${decodedText}`);
                    }
                }

                var htmlscanner = new Html5QrcodeScanner(
                    "qr-reader", { fps: 10, qrbox: 250 });

                htmlscanner.render(onScanSuccess);
            });
        </script>
    </div>
</body>
</html>
