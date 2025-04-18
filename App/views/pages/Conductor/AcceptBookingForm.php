<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Conductor/AcceptBookingForm.css?v=<?php echo time(); ?>">
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

    <h2>Accept Booking Form</h2>

    <form id="acceptBookingForm" method="POST" action="<?php echo URLROOT . '/ConductorPages/acceptBookingForm'; ?>">
        <label for="bookingId">Booking ID:</label>
        <input type="text" id="bookingId" name="bookingId" value="<?php echo htmlspecialchars($booking_id); ?>">

        <label for="nic">NIC:</label>
        <input type="text" id="nic" name="nic" value="<?php echo htmlspecialchars($nic); ?>">

        <button type="submit" id="submitBtn" class="submit-btn">Submit</button>
    </form>

    
        