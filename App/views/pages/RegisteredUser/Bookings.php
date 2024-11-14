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
        $userId = $_SESSION['user_id'] ?? '';
        $userRole = $_SESSION['user_role'] ?? 'RegisteredUser';
        $scheduleData = $data['schedule'] ?? [];
        $bookingData = $data['bookingsDetails'] ?? [];
        $busData = $data['bus'] ?? [];
        $data = [
            'currentController' => 'RegisteredPages', // Adjust this based on your controller
            'currentMethod' => 'bookings', // Adjust this based on the method
            'userRole' => $userRole
        ];
        
    ?>

    <script>
        var scheduleData = <?php echo json_encode($scheduleData); ?>;
        console.log("Schedule Data: ", scheduleData);
        var bookingData = <?php echo json_encode($bookingData); ?>;
        console.log("Booking Data: ", bookingData);
        var userId = <?php echo json_encode($userId); ?>;
        console.log("User ID: ", userId);
    </script>

    <?php require 'bookingsData.php'; ?>

    <!-- Header and Navbar -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

    <?php require APPROOT.'/views/inc/Components/Header/header.php'; ?>
    <?php require APPROOT.'/views/inc/Components/NavBar/navbar.php'; ?>

    <?php
        $currentDate = date("Y-m-d"); // Current date to compare with booking dates
        
        // Filter upcoming and past bookings based on the schedule date
        $upcomingBookings = [];
        $pastBookings = [];

        foreach ($bookingData as $booking) {
            // Assuming each booking has a `schedule_id` to match the scheduleData
            $schedule = array_filter($scheduleData, function($s) use ($booking) {
                return $s['scheduleId'] == $booking['schedule_id']; // Match schedule by ID
            });

            $schedule = reset($schedule); // Get the first matching schedule entry

            if ($schedule) {
                // Compare booking date with schedule date
                if ($booking['Booking_date'] >= $schedule['date']) {
                    $upcomingBookings[] = $booking; // Upcoming booking
                } else {
                    $pastBookings[] = $booking; // Past booking
                }
            }
        }
    ?>


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
                <?php foreach ($pastBookings as $booking): ?>
                    <tr>
                        <td data-label="Date"><?php echo $booking['Booking_date']; ?></td>
                        <td data-label="Time"><?php echo $booking['Booking_time']; ?></td>
                        <td data-label="Route"><?php echo $booking['route']; ?></td>
                        <td data-label="From"><?php echo $booking['from_location']; ?></td>
                        <td data-label="To"><?php echo $booking['to_location']; ?></td>
                        <td data-label="Bus No"><?php echo $booking['busNo']; ?></td>
                        <td data-label="Price (LKR)"><?php echo $booking['total_price']; ?></td>
                        <td data-label="Status" class="status"><?php echo $booking['status']; ?></td>
                    </tr>
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
                <?php foreach ($upcomingBookings as $booking):

                    //Find the corresponding schedule for this booking based on schedule_id
                    $schedule = array_filter($scheduleData, function($s) use ($booking) {
                        return $s['scheduleId'] == $booking['schedule_id']; // Match schedule by ID
                    });

                    $schedule = reset($schedule); // Get the first matching schedule entry

                    if($schedule) {
                        $bus = array_filter($busData, function($b) use ($schedule) {
                            return $b['busId'] == $schedule['busId']; // Match bus by ID
                        });
                    }
                    $bus = reset($bus); // Get the first matching bus entry

                ?>
                    <tr>
                        <td data-label="Date"><?php echo $booking['Booking_date']; ?></td>
                        <td data-label="Time"><?php echo $booking['Booking_time']; ?></td>
                        <td data-label="Route"><?php echo $bus['route']; ?></td>
                        <td data-label="From"><?php echo $booking['from_location']; ?></td>
                        <td data-label="To"><?php echo $booking['to_location']; ?></td>
                        <td data-label="Bus No"><?php echo $bus['License_id']; ?></td>
                        <td data-label="Price (LKR)"><?php echo $booking['total_price']; ?></td>
                        <td data-label="Action">
                            <i class="fas fa-search search-icon" onclick="toggleTicketBox()"></i>
                        </td>
                    </tr>
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

        // Show only "Upcoming Bookings" table by default
        document.getElementById('upcomingBookings').style.display = 'table';  // Change this line
        document.getElementById('pastBookings').style.display = 'none';       // Ensure Past bookings are hidden
        document.getElementById('showUpcomingBookings').style.color = '#4CAF50'; // Highlight Upcoming
        document.getElementById('showPastBookings').style.color = defaultColor; // Set Past Bookings color to black

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
