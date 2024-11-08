<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Bookings <?php echo SITENAME; ?></title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar/navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/RegisteredUser/Bookings.css?v=<?php echo time(); ?>">
</head>
<body>
    <script>
        var userRole = <?php echo json_encode($_SESSION['userRole'] ?? 'RegisteredUser'); ?>;
        localStorage.setItem('userRole', userRole);
    </script>

    <?php
    // Retrieve user role from session or set to a default value
    $userRole = $_SESSION['userRole'] ?? 'RegisteredUser';
    ?>


    <?php
    $data = [
        'currentController' => 'RegisteredPages', // Adjust this based on your controller
        'currentMethod' => 'bookings', // Adjust this based on the method
        'userRole' => $userRole
    ];
    ?>

    <?php require 'bookingsData.php'; ?>
    
    <!-- Header and Navbar -->
    <?php require APPROOT.'/views/inc/Components/Header/header.php'; ?>
    <?php require APPROOT.'/views/inc/Components/NavBar/navbar.php'; ?>

    <br/> 

    <div class="main">
        <br>
        <div class="container">
            <h3 class="clickable" id="showPastBookings">Past Bookings</h3>
            <h3 class="clickable" id="showUpcomingBookings">Upcoming Bookings</h3>
            <div class="input-group">
                <div class="icon"><i class="fas fa-calendar-alt"></i></div>
                <input type="date" class="search-input">
            </div>
        </div>

        <?php require 'bookingsData.php'; ?>

<!-- Past Bookings Table -->
<table id="pastBookings">
    <thead>
        <tr>
            <th>Date</th>
            <th>Time</th>
            <th>Route</th>
            <th>From</th>
            <th>To</th>
            <th>Bus No</th>
            <th>Price (LKR)</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($bookingsDetails as $booking): ?>
            <?php if ($booking['date'] < $currentDate): // Past booking ?>
                <tr>
                    <td data-label="Date"><?php echo $booking['date']; ?></td>
                    <td data-label="Time"><?php echo $booking['time']; ?></td>
                    <td data-label="Route"><?php echo $booking['route']; ?></td>
                    <td data-label="From"><?php echo $booking['from']; ?></td>
                    <td data-label="To"><?php echo $booking['to']; ?></td>
                    <td data-label="Bus No"><?php echo $booking['busNo']; ?></td>
                    <td data-label="Price (LKR)"><?php echo $booking['price']; ?></td>
                    <td data-label="Status" class="status"><?php echo $booking['status']; ?></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Upcoming Bookings Table -->
<table id="upcomingBookings">
    <thead>
        <tr>
            <th>Date</th>
            <th>Time</th>
            <th>Route</th>
            <th>From</th>
            <th>To</th>
            <th>Bus No</th>
            <th>Price (LKR)</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($bookingsDetails as $booking): ?>
            <?php if ($booking['date'] >= $currentDate): // Upcoming booking ?>
                <tr>
                    <td data-label="Date"><?php echo $booking['date']; ?></td>
                    <td data-label="Time"><?php echo $booking['time']; ?></td>
                    <td data-label="Route"><?php echo $booking['route']; ?></td>
                    <td data-label="From"><?php echo $booking['from']; ?></td>
                    <td data-label="To"><?php echo $booking['to']; ?></td>
                    <td data-label="Bus No"><?php echo $booking['busNo']; ?></td>
                    <td data-label="Price (LKR)"><?php echo $booking['price']; ?></td>
                    <td data-label="Action">
                        <i class="fas fa-search search-icon"></i>
                        <div class="pop-up-menu">
                            <a href="./SeeTicket.html"><p>See Ticket</p></a>
                            <a href="./CancelBooking.html"><p>Cancel Booking</p></a>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    </tbody>
</table>

    </div>

    <!-- External JavaScript -->
    <script src="./../../Component/Header/header.js"></script>
    <script src="./../../Component/NavBar/navbar.js"></script>
    <script src="./Bookings.js"></script>

    <script>

        // Default color for the non-clicked element
        const defaultColor = 'black'; // or whatever the default color is

        // Show only the "Past Bookings" table by default
        document.getElementById('pastBookings').style.display = 'table';
        document.getElementById('upcomingBookings').style.display = 'none';
        document.getElementById('showPastBookings').style.color = '#4CAF50';

        // Add click event listeners for the headers
        document.getElementById('showPastBookings').addEventListener('click', function() {
            document.getElementById('pastBookings').style.display = 'table';
            document.getElementById('upcomingBookings').style.display = 'none';

            // Change color of the clicked text
            document.getElementById('showPastBookings').style.color = '#4CAF50';

            // Revert the other text to default color
            document.getElementById('showUpcomingBookings').style.color = defaultColor;
        });

        document.getElementById('showUpcomingBookings').addEventListener('click', function() {
            document.getElementById('pastBookings').style.display = 'none';
            document.getElementById('upcomingBookings').style.display = 'table';

            // Change color of the clicked text
            document.getElementById('showUpcomingBookings').style.color = '#4CAF50';

            // Revert the other text to default color
            document.getElementById('showPastBookings').style.color = defaultColor;
        });

    // Handle pop-up menu visibility
    document.querySelectorAll('.search-icon').forEach(icon => {
    const popUpMenu = icon.nextElementSibling;

    // Show the pop-up menu on mouseenter
    icon.addEventListener('mouseenter', function() {
        popUpMenu.classList.add('show');
    });

    // Keep the pop-up menu visible when hovering over it
    popUpMenu.addEventListener('mouseenter', function() {
        popUpMenu.classList.add('show');
    });

    // Hide the pop-up menu when leaving the icon
    icon.addEventListener('mouseleave', function() {
        setTimeout(function() {
            if (!popUpMenu.matches(':hover')) {
                popUpMenu.classList.remove('show');
            }
        }, 200); // Slight delay to allow moving from icon to menu
    });

    // Hide the pop-up menu when leaving the menu
    popUpMenu.addEventListener('mouseleave', function() {
        popUpMenu.classList.remove('show');
    });
});



    </script>
</body>
</html>
