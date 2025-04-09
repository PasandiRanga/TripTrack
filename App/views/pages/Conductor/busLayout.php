<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Conductor' , 'Driver']);

$data = [
    'totalSeats' => 40,
    'bookedSeats' => ['5', '12', '25', '30'], // Seats booked but not yet scanned
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Conductor/busLayout.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar/navbar.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=ABeeZee&display=swap" rel="stylesheet">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Layout</title>
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
        'currentMethod' => 'busLayout', // Adjust this based on the method
        'userRole' => $userRole
    ];
    ?>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/ConductorPages/home'">Back</button>

    <div id="accepted-seats-msg" class="accepted-msg-text"></div>

    <div class="bus-layout">
        <?php
        $seatsPerRow = 4;

        for ($i = 1; $i <= $data['totalSeats']; $i++) {
            $status = in_array((string)$i, $data['bookedSeats']) ? 'booked' : 'available';

            echo "<div class='seat $status' data-seat='$i'>$i</div>";

            // Line break after each row
            if ($i % $seatsPerRow == 0) {
                echo '<div class="clear-row"></div>';
            }
        }
        ?>
    </div>

    <div class="legend">
        <div><span class="seat available"></span> Available</div>
        <div><span class="seat booked"></span> Booked (Pending Scan)</div>
        <div><span class="seat accepted"></span> Accepted (Scanned)</div>
    </div>

    <script>
        const acceptedSeats = JSON.parse(localStorage.getItem("acceptedSeats") || "[]");

        document.querySelectorAll('.seat').forEach(seat => {
            const seatId = seat.getAttribute('data-seat');
            if (acceptedSeats.includes(seatId)) {
                seat.classList.remove('booked');
                seat.classList.add('accepted');
            }
        });

        const acceptedMsgBox = document.getElementById("accepted-seats-msg");

        if (acceptedSeats.length > 0) {
            acceptedMsgBox.innerHTML = `
                <h3>✅ Accepted Seats: ${acceptedSeats.join(", ")}</h3>
                
            `;
        } else {
            acceptedMsgBox.innerHTML = `
                <p>No seats have been accepted yet.</p>
            `;
        }

    </script>

</body>
</html>