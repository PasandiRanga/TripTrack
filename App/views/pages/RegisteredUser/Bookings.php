<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Bookings <?php echo SITENAME; ?></title>

    <!-- External Stylesheets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar/navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/RegisteredUser/Bookings.css?v=<?php echo time(); ?>">
</head>
<body>
    <!-- Set user role in localStorage -->
    <script>
        var userRole = <?php echo json_encode($_SESSION['userRole'] ?? 'RegisteredUser'); ?>;
        localStorage.setItem('userRole', userRole);
    </script>

    <?php
        $userRole = $_SESSION['userRole'] ?? 'RegisteredUser';
        $data = [
            'currentController' => 'RegisteredPages', // Adjust this based on your controller
            'currentMethod' => 'bookings', // Adjust this based on the method
            'userRole' => $userRole
        ];
    ?>

    <?php require 'bookingsData.php'; ?>

    <!-- Header and Navbar -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

    <?php require APPROOT.'/views/inc/Components/Header/header.php'; ?>
    <?php require APPROOT.'/views/inc/Components/NavBar/navbar.php'; ?>

    <div class="main">
        <div class="container">
            <h3 class="clickable" id="showPastBookings">Past Bookings</h3>
            <h3 class="clickable" id="showUpcomingBookings">Upcoming Bookings</h3>
            <div class="input-group">
                <div class="icon"><i class="fas fa-calendar-alt"></i></div>
                <input type="date" class="search-input">
            </div>
        </div>

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
                    <?php if ($booking['date'] < $currentDate): ?>
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
                    <?php if ($booking['date'] >= $currentDate): ?>
                        <tr>
                            <td data-label="Date"><?php echo $booking['date']; ?></td>
                            <td data-label="Time"><?php echo $booking['time']; ?></td>
                            <td data-label="Route"><?php echo $booking['route']; ?></td>
                            <td data-label="From"><?php echo $booking['from']; ?></td>
                            <td data-label="To"><?php echo $booking['to']; ?></td>
                            <td data-label="Bus No"><?php echo $booking['busNo']; ?></td>
                            <td data-label="Price (LKR)"><?php echo $booking['price']; ?></td>
                            <!-- Search Icon -->
                            <td data-label="Action">
                                <i class="fas fa-search search-icon" onclick="toggleTicketBox()"></i>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<!-- Ticket Box Pop-Up -->
<div id="ticketBox" class="ticketBox hidden">
    <div class="signInContent">
        <?php require APPROOT . '/views/inc/Components/busTicket/busTicket.php'; ?>
        <div class="close-btn" onclick="closeTicketBox()">×</div>
        <button class="cancel">Cancel Booking</button>
    </div>
</div>



<script>
    // Toggle the visibility of the ticket box
    function toggleTicketBox() {
        document.getElementById('ticketBox').classList.toggle('hidden');
    }

    // Close the ticket box
    function closeTicketBox() {
        document.getElementById('ticketBox').classList.add('hidden');
    }
</script>

    <!-- External JavaScript -->
    <script src="./../../Component/Header/header.js"></script>
    <script src="./../../Component/NavBar/navbar.js"></script>
    <script src="./Bookings.js"></script>

    <script>
        const defaultColor = 'black';

        // Show only "Past Bookings" table by default
        document.getElementById('pastBookings').style.display = 'table';
        document.getElementById('upcomingBookings').style.display = 'none';
        document.getElementById('showPastBookings').style.color = '#4CAF50';

        // Toggle between Past and Upcoming Bookings
        document.getElementById('showPastBookings').addEventListener('click', function() {
            document.getElementById('pastBookings').style.display = 'table';
            document.getElementById('upcomingBookings').style.display = 'none';
            document.getElementById('showPastBookings').style.color = '#4CAF50';
            document.getElementById('showUpcomingBookings').style.color = defaultColor;
        });

        document.getElementById('showUpcomingBookings').addEventListener('click', function() {
            document.getElementById('pastBookings').style.display = 'none';
            document.getElementById('upcomingBookings').style.display = 'table';
            document.getElementById('showUpcomingBookings').style.color = '#4CAF50';
            document.getElementById('showPastBookings').style.color = defaultColor;
        });

        // Pop-up menu handling
        document.querySelectorAll('.search-icon').forEach(icon => {
            const popUpMenu = icon.nextElementSibling;

            icon.addEventListener('mouseenter', function() {
                popUpMenu.classList.add('show');
            });

            popUpMenu.addEventListener('mouseenter', function() {
                popUpMenu.classList.add('show');
            });

            icon.addEventListener('mouseleave', function() {
                setTimeout(function() {
                    if (!popUpMenu.matches(':hover')) {
                        popUpMenu.classList.remove('show');
                    }
                }, 200);
            });

            popUpMenu.addEventListener('mouseleave', function() {
                popUpMenu.classList.remove('show');
            });
        });
    </script>
</body>
</html>
