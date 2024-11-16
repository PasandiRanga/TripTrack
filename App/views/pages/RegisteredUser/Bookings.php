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
                if ($currentDate < $schedule['date']) {
                    $upcomingBookings[] = $booking; // Upcoming booking
                } else {
                    $pastBookings[] = $booking; // Past booking
                }
            }
        }
    ?>

    <?php
    if (isset($_SESSION['error'])) {
        echo "<p class='error'>" . $_SESSION['error'] . "</p>"; // Display the error message
        unset($_SESSION['error']); // Clear the error message from session after displaying
    }
    ?>


    
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
                <?php if (empty($pastBookings)): ?>
                    <tr>
                        <td colspan="8" style="text-align: center;">No past bookings available</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($pastBookings as $booking): 
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
                    
                        $bus = reset($bus);
                        // echo($bus);

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
                <?php endif; ?>
            </tbody>
        </table>

        <div class ="table-wrapper">
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
                    <th>Seats</th>
                    <th>Price (LKR)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($upcomingBookings)): ?>
                    <tr>
                        <td colspan="8" style="text-align: center;">No upcoming bookings available</td>
                    </tr>
                <?php else: ?>
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
                            <td data-label="Seats"><?php echo $booking['Seats']; ?></td>
                            <td data-label="Price (LKR)"><?php echo $booking['total_price']; ?></td>
                            <td data-label="Action">
                                <!-- Show buttons one after the other -->
                                <form method="POST" action="<?php echo URLROOT; ?>/RegisteredPages/cancelBooking" style="display:inline;">
                                    <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                    <?php echo($booking['id']); ?>
                                    <input type="hidden" name="schedule_id" value="<?php echo $booking['schedule_id']; ?>">
                                    <?php echo($booking['schedule_id']); ?>
                                    <input type="hidden" name="seats" value="<?php echo $booking['Seats']; ?>">
                                    <?php echo($booking['Seats']); ?>
                                    <script console.log(<?php echo $booking['id']; ?>)></script>
                                    <script console.log(<?php echo $booking['schedule_id']; ?>)></script>
                                    <!-- <script console.log(<?php echo $booking['Seats']; ?>)></script> -->
                                    <button type="submit" onclick="return confirm('Are you sure you want to cancel this booking?')">Cancel Booking</button>
                                </form><br/>
                                <button>Update Booking</button><br/>
                                <button>View Ticket</button><br/>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        </div>
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

        document.querySelector('#upcoming-bookings-container').addEventListener('click', function (event) {
            if (event.target.classList.contains('action-button')) {
                console.log('Action button clicked!');
            }
        });

        


    </script>

</body>
</html>
