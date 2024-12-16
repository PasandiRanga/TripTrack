<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['RegisteredUser']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Bookings <?php echo SITENAME; ?></title>

    <!-- External Stylesheets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
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
        $userData = $data['user'] ?? [];
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

  

    <!-- Header and Navbar -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <div class="hero-container">
        <br/>
        <?php require APPROOT.'/views/inc/Components/Header/header.php'; ?>
    </div>
    

    <?php
        $currentDate = date("Y-m-d"); // Current date to compare with booking dates
        // echo($currentDate);
        
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
                if ($currentDate <= $schedule['date']) {
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
                    <th>Ticket</th>
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
                                return $b['License_id'] == $schedule['License_id']; // Match bus by ID
                            });
                        }
                    
                        $bus = reset($bus);

                        // var_dump($user);

                    ?>
                        <tr>
                            <td data-label="Date"><?php echo $schedule['date']; ?></td>
                            <td data-label="Time"><?php echo $booking['Booking_time']; ?></td>
                            <td data-label="Route"><?php echo $bus['route']; ?></td>
                            <td data-label="From"><?php echo $booking['from_location']; ?></td>
                            <td data-label="To"><?php echo $booking['to_location']; ?></td>
                            <td data-label="Bus No"><?php echo $bus['License_id']; ?></td>
                            <td data-label="Price (LKR)"><?php echo $booking['total_price']; ?></td>
                            <td data-label="Action">
                                <button
                                    class="view" 
                                    onclick="toggleTicketBox(
                                        <?php echo htmlspecialchars(json_encode($booking), ENT_QUOTES, 'UTF-8'); ?>, 
                                        <?php echo htmlspecialchars(json_encode($schedule), ENT_QUOTES, 'UTF-8'); ?>,
                                        <?php echo htmlspecialchars(json_encode($bus), ENT_QUOTES, 'UTF-8'); ?>,
                                        <?php echo htmlspecialchars(json_encode($userData), ENT_QUOTES, 'UTF-8'); ?>
                                    )"
                                >View Ticket</button>
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
                                return $b['License_id'] == $schedule['License_id']; // Match bus by ID
                            });
                        }
                        $bus = reset($bus); // Get the first matching bus entry

                    ?>
                        <tr>
                            <td data-label="Date"><?php echo $schedule['date']; ?></td>
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
                                    <!-- <?php echo($booking['id']); ?> -->
                                    <input type="hidden" name="schedule_id" value="<?php echo $booking['schedule_id']; ?>">
                                    <!-- <?php echo($booking['schedule_id']); ?> -->
                                    <input type="hidden" name="seats" value="<?php echo $booking['Seats']; ?>">
                                    <!-- <?php echo($booking['Seats']); ?> -->
                                    <script console.log(<?php echo $booking['id']; ?>)></script> 
                                    <script console.log(<?php echo $booking['schedule_id']; ?>)></script>
                                    <script console.log(<?php echo $booking['Seats']; ?>)></script> 

                                    <button type="button" class="cancel" onclick="showCancelPopup(<?php echo htmlspecialchars(json_encode($booking), ENT_QUOTES, 'UTF-8');?>)">
                                        Cancel Booking
                                    </button>
                                </form>
                                <!-- <button><i class="fa-solid fa-pen-to-square" style="color:blue"></i>  Edit Booking</button> -->
                                <button class="view" onclick="toggleTicketBox(
                                        <?php echo htmlspecialchars(json_encode($booking), ENT_QUOTES, 'UTF-8'); ?>, 
                                        <?php echo htmlspecialchars(json_encode($schedule), ENT_QUOTES, 'UTF-8'); ?>,
                                        <?php echo htmlspecialchars(json_encode($bus), ENT_QUOTES, 'UTF-8'); ?>,
                                        <?php echo htmlspecialchars(json_encode($userData), ENT_QUOTES, 'UTF-8'); ?>

                                )">View Ticket</button>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        </div>
    </div>

    <!--ticket box pop up-->
    <div id="overlay" class="overlay hidden"></div>
    <div id="ticketBox" class="ticketBox hidden">
            <div class="ticket-content">
                <!-- Dynamic content will be injected here -->
            </div>
            <div class="close-btn" onclick="closeTicketBox()">×</div>
    </div>

    <!--Cancel Booking pop up -->
    <div id="cancelPopup" class="popup hidden">
        <div class="popup-content">
            <h3>Cancel Booking</h3>
            <p id="popup-details"></p>
            <!--Content will come here -->
            <div class="popup-actions">
                <button id="confirmCancel" class="confirm-btn">Confirm</button>
                <button id="closePopup" class="cancel-btn" onclick="closeCancelBox()">Close</button>
            </div>
            <!-- <div class="close-btn" onclick="closeCancelBox()">×</div> -->
        </div>
    </div>



<script>

    // Toggle the visibility of the ticket box and populate data
function toggleTicketBox(booking, schedule, bus , user) {
    console.log("Inside toggleTicketBox");
    // Find the ticket box element
    const ticketBox = document.getElementById('ticketBox');
    if(ticketBox){
        console.log("found element");    
    }

    // Update the ticket content dynamically
    const ticketFrame = ticketBox.querySelector('.ticket-content');
    ticketFrame.innerHTML = `
        <div class="bus-ticket">
            <div class="ticket-header">
                <div class="location">
                    <h2>${booking.from_location.toUpperCase()}</h2>
                    
                </div>
                <hr class="dotted-line">
                <div class="icon">
                    <i class="fas fa-bus-alt"></i>
                </div>
                <hr class="dotted-line">
                <div class="location">
                    <h2>${booking.to_location.toUpperCase()}</h2>

                </div>
            </div>

            <hr class="dotted-separator">

            <div class="ticket-body">
                <div class="info">
                    <p><strong>Route No:</strong>&nbsp;&nbsp; ${bus.route}</p>
                    <p><strong>Bus Number:</strong>&nbsp;&nbsp; ${bus.License_id}</p>
                    <p><strong>Ticket Reference No:</strong>&nbsp;&nbsp; ${booking.id}</p>
                </div>
                <div class="price">
                    <h3>LKR ${booking.total_price ? Number(booking.total_price).toFixed(2) : "0.00"}</h3>

                </div>
            </div>

            <hr class="dotted-separator">

            <div class="ticket-footer">
                <div class="passenger-info">
                    <p><strong>Name:</strong>&nbsp;&nbsp; ${user.Name}</p>
                    <p><strong>NIC No:</strong>&nbsp;&nbsp; ${user.NIC}</p>
                    <p><strong>Seat Numbers:</strong>&nbsp;&nbsp; ${booking['Seats']}</p>
                    <p><strong>No of Seats:</strong>&nbsp;&nbsp; ${booking.No_of_seats}</p>
                </div>
                <div class="qr-code">
                    <!-- QR code placeholder -->
                    <i class="fas fa-qrcode"></i>
                </div>
            </div>
        </div>

    `;

    // Toggle visibility
    ticketBox.classList.remove('hidden');
    if(ticketBox.classList.remove('hidden')){
        console.log("showing ticket");
    }
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
        document.getElementById('showUpcomingBookings').style.color = '#43cea2'; // Highlight Upcoming
        document.getElementById('showPastBookings').style.color = defaultColor; // Set Past Bookings color to black

        // Toggle between Past and Upcoming Bookings
        document.getElementById('showPastBookings').addEventListener('click', function() {
            document.getElementById('pastBookings').style.display = 'table';
            document.getElementById('upcomingBookings').style.display = 'none';
            document.getElementById('showPastBookings').style.color = '#43cea2';
            document.getElementById('showUpcomingBookings').style.color = defaultColor;
        });

        document.getElementById('showUpcomingBookings').addEventListener('click', function() {
            document.getElementById('pastBookings').style.display = 'none';
            document.getElementById('upcomingBookings').style.display = 'table';
            document.getElementById('showUpcomingBookings').style.color = '#43cea2';
            document.getElementById('showPastBookings').style.color = defaultColor;
        });

        document.querySelector('#upcoming-bookings-container').addEventListener('click', function (event) {
            if (event.target.classList.contains('action-button')) {
                console.log('Action button clicked!');
            }
        });

        
//----------------Booking cancel handling ------------------------------------------------
      
        // Show the popup with booking details
        function showCancelPopup(booking) {
            const popup = document.getElementById('cancelPopup');
            const details = document.getElementById('popup-details');
            const confirmBtn = document.getElementById('confirmCancel');

            // Populate the popup with booking details
            details.innerHTML = `
                <strong>Booking ID:</strong> ${booking.id} <br>
                <strong>Route:</strong> ${booking.from_location} to ${booking.to_location} <br>
                <strong>Date:</strong> ${booking.date} <br>
                <strong>Total Price:</strong> LKR ${Number(booking.total_price).toFixed(2)}
            `;

            // Add event listener to confirm button
            confirmBtn.onclick = function () {
                confirmCancellation(booking.id); // Call the cancellation function
            };

            popup.classList.remove('hidden');
        }

        // Close the popup
        function closeCancelBox() {
            console.log("closeCancelBox");
        document.getElementById('cancelPopup').classList.add('hidden');
    }

        // Confirm cancellation (AJAX or form submission)
        function confirmCancellation(bookingId) {
            // Send the bookingId to the server for cancellation
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?php echo URLROOT; ?>/RegisteredPages/cancelBooking';

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'booking_id';
            input.value = bookingId;

            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }

        function openTicketBox() {
            document.getElementById('ticketBox').classList.remove('hidden');
            document.getElementById('overlay').classList.remove('hidden');
        }

        function closeTicketBox() {
            document.getElementById('ticketBox').classList.add('hidden');
            document.getElementById('overlay').classList.add('hidden');
        }
    </script>

    

</body>
</html>
