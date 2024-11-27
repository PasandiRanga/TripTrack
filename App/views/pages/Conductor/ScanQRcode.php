<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Conductor' , 'Driver']);
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
    // Retrieve user role from session or set to a default value
    $userRole = $_SESSION['userRole'] ?? 'Conductor';
    ?>

    <?php
    $data = [
        'currentController' => 'ConductorPages', // Adjust this based on your controller
        'currentMethod' => 'scanQRcode', // Adjust this based on the method
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
        <div id="qr-result"></div>

        <script src="https://unpkg.com/html5-qrcode"></script>

        <script>
            function domReady(fn) {
                if(document.readyState === "complete" || document.readyState === "interactive") {
                    setTimeout(fn,1)
                }else{
                    document.addEventListener("DOMContentLoaded",fn)
                }
            }

            domReady(function(){
                var myqr = document.getElementById('qr-result')
                var lastResult,countResults = 0;

                //IF FOUND QR CODE
                function onScanSuccess(decodeText,decodeResult) {
                    if(decodeText !== lastResult) {
                        ++countResults;
                        lastResult = decodeText;

                        //ALERT QR HERE
                        alert("Qr is : " + decodeText,decodeResult)
                        myqr.innerHTML = ` you scan ${countResults} : ${decodeText}`
                    }
                }

                var htmlscanner = new Html5QrcodeScanner(
                    "qr-reader",{fps:10,qrbox:250})

                htmlscanner.render(onScanSuccess)
            })

        </script>
    </div>

</body>
</html>