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
        <div class="header-container">
            <?php require APPROOT.'/views/inc/Components/Header/header.php'; ?>
        </div>
        <br/>
    </div>
    

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
                            <td data-label="Route"><?php echo $bus['start_location']; ?> - <?php echo $bus['destination']; ?></td>
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
                            <td data-label="Route"><?php echo $bus['start_location']; ?> - <?php echo $bus['destination']; ?></td>
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

                                    <button type="button" class="cancel" onclick="showCancelPolicy(<?php echo htmlspecialchars(json_encode($booking), ENT_QUOTES, 'UTF-8');?>,<?php echo htmlspecialchars(json_encode($schedule), ENT_QUOTES, 'UTF-8');?>)">
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

    <!--Cancel Policy pop up -->
    <div id="cancelPolicyPopup" class="policypopup hidden">
        <div class="policypopup-content">
            <h3>Cancel Booking</h3>
            <p id="policypopup-details"></p>
            <!--Content will come here -->
            <div class="policypopup-actions">
                <button id="understand" class="uderstant-btn">I understand</button>
                <button id="closePopup" class="cancel-btn" onclick="closePolicyBox()">Close</button>
            </div>
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

        function calculateCancellationFee(bookingDate) {
            const today = new Date();
            const scheduleDate = new Date(bookingDate);
            const diffTime = scheduleDate.getTime() - today.getTime();
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            if (diffDays >= 1) {
                return 0.10; // 10% fee
            } else {
                return 0.50; // 50% fee
            }
        }

        function showCancelPolicy(booking , schedule){
            const policypopup = document.getElementById('cancelPolicyPopup');
            const policydetails = document.getElementById('policypopup-details');
            const understandBtn = document.getElementById('understand');

            policydetails.innerHTML = `
                <div class="p-4">
                    <h3 class="text-lg font-bold mb-4">Cancellation Policy</h3>
                    <p>Please review our cancellation policy:</p>
                    <ul class="my-4">
                        <li>• Cancellation 1 or more days before departure: 10% cancellation fee</li>
                        <li>• Cancellation within 24 hours of departure: 50% cancellation fee</li>
                    </ul>
                </div>
            `;
            understandBtn.onclick = function(){
                showCancelPopup(booking, schedule);
                policypopup.classList.add('hidden'); 

            };

            policypopup.classList.remove('hidden');

        }
      
        // Show the popup with booking details
        function showCancelPopup(booking , schedule) {
            const popup = document.getElementById('cancelPopup');
            const details = document.getElementById('popup-details');
            const confirmBtn = document.getElementById('confirmCancel');

            const cancellationFee = calculateCancellationFee(schedule.date);
            const feeAmount = booking.total_price * cancellationFee;
            const refundAmount = booking.total_price - feeAmount;

            if(booking.paymentMethod ==='Online'){
                // Populate the popup with booking details
                details.innerHTML = `
                    <style>
                        .cancellation-form {
                            background-color: #ffffff;
                            padding: 1rem;
                            border-radius: 6px;
                            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
                            font-size: 0.9rem;
                            max-width: 500px;
                        }

                        .booking-details {
                            margin-bottom: 1rem;
                            line-height: 1.5;
                        }

                        .detail-row {
                            display: flex;
                            margin-bottom: 0.25rem;
                            align-items: baseline;
                        }

                        .detail-label {
                            font-weight: 600;
                            color: #374151;
                            min-width: 140px;
                            font-size: 0.9rem;
                        }

                        .detail-value {
                            color: #4B5563;
                            font-size: 0.9rem;
                        }

                        .bank-details-section {
                            background-color: #F9FAFB;
                            padding: 0.75rem;
                            border-radius: 4px;
                            margin-top: 0.75rem;
                        }

                        .section-title {
                            color: #1F2937;
                            font-size: 1rem;
                            font-weight: 600;
                            margin-bottom: 0.75rem;
                            padding-bottom: 0.25rem;
                            border-bottom: 1px solid #E5E7EB;
                        }

                        .form-group {
                            margin-bottom: 0.5rem;
                        }

                        .form-label {
                            display: block;
                            font-weight: 500;
                            color: #4B5563;
                            margin-bottom: 0.25rem;
                            font-size: 0.85rem;
                        }

                        .form-input {
                            width: 80%;
                            padding: 0.375rem 0.5rem;
                            border: 1px solid #D1D5DB;
                            border-radius: 4px;
                            font-size: 0.85rem;
                            transition: border-color 0.15s ease-in-out;
                        }

                        .form-input:focus {
                            outline: none;
                            border-color: #60A5FA;
                            box-shadow: 0 0 0 2px rgba(96, 165, 250, 0.1);
                        }

                        .amount-highlight {
                            font-weight: 600;
                            padding: 0.125rem 0.375rem;
                            border-radius: 3px;
                            font-size: 0.85rem;
                        }

                        .fee-amount {
                            background-color: #FEE2E2;
                            color: #991B1B;
                        }

                        .refund-amount {
                            background-color: #D1FAE5;
                            color: #065F46;
                        }
                        </style>

                        <div class="cancellation-form">
                            <div class="booking-details">
                                <div class="detail-row">
                                    <span class="detail-label">Booking ID:</span>
                                    <span class="detail-value">${booking.id}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Route:</span>
                                    <span class="detail-value">${booking.from_location} to ${booking.to_location}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Date:</span>
                                    <span class="detail-value">${schedule.date}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Total Price:</span>
                                    <span class="detail-value">LKR ${Number(booking.total_price).toFixed(2)}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Cancellation Fee:</span>
                                    <span class="detail-value amount-highlight fee-amount">LKR ${feeAmount.toFixed(2)}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Refund Amount:</span>
                                    <span class="detail-value amount-highlight refund-amount">LKR ${refundAmount.toFixed(2)}</span>
                                </div>
                            </div>

                            <div class="bank-details-section">
                                <h4 class="section-title">Bank Details for Refund</h4>
                                <div class="form-group">
                                    <label class="form-label">Account Holder Name</label>
                                    <input type="text" id="accountName" class="form-input" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Bank Name</label>
                                    <input type="text" id="bankName" class="form-input" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Account Number</label>
                                    <input type="text" id="accountNumber" class="form-input" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Branch</label>
                                    <input type="text" id="branch" class="form-input" required>
                                </div>
                            </div>
                        </div>

                `;

                // Add event listener to confirm button
                confirmBtn.onclick = function() {
                    const bankDetails = {
                        accountName: document.getElementById('accountName').value,
                        bankName: document.getElementById('bankName').value,
                        accountNumber: document.getElementById('accountNumber').value,
                        branch: document.getElementById('branch').value
                    };
                    
                    if (!bankDetails.accountName || !bankDetails.bankName || 
                        !bankDetails.accountNumber || !bankDetails.branch) {
                        alert('Please fill in all bank details');
                        return;
                    }
                    
                    confirmCancellation(booking.id, cancellationFee, refundAmount, bankDetails);
                };
            }else if(booking.paymentMethod === "Cash"){
                details.innerHTML = `
                    <style>
                        <style>
                        .cancellation-form {
                            background-color: #ffffff;
                            padding: 1rem;
                            border-radius: 6px;
                            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
                            font-size: 0.9rem;
                            max-width: 500px;
                        }

                        .booking-details {
                            margin-bottom: 1rem;
                            line-height: 1.5;
                        }

                        .detail-row {
                            display: flex;
                            margin-bottom: 0.25rem;
                            align-items: baseline;
                        }

                        .detail-label {
                            font-weight: 600;
                            color: #374151;
                            min-width: 140px;
                            font-size: 0.9rem;
                        }

                        .detail-value {
                            color: #4B5563;
                            font-size: 0.9rem;
                        }
                    </style>
                    <div class="cancellation-form">
                        <div class="booking-details">
                            <div class="detail-row">
                                <span class="detail-label">Booking ID:</span>
                                <span class="detail-value">${booking.id}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Route:</span>
                                <span class="detail-value">${booking.from_location} to ${booking.to_location}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Date:</span>
                                <span class="detail-value">${schedule.date}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Total Price:</span>
                                <span class="detail-value">LKR ${Number(booking.total_price).toFixed(2)}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Cancellation Fee:</span>
                                <span class="detail-value amount-highlight fee-amount">LKR ${feeAmount.toFixed(2)}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Refund Amount:</span>
                                <span class="detail-value amount-highlight refund-amount">LKR ${refundAmount.toFixed(2)}</span>
                            </div>
                        </div>
                        <p> Confirm will take you to the payment portal to collect the cancellation fee</p>
                `;

                // Add event listener to confirm button
                confirmBtn.onclick = function() {
                        window.location.href = `paymentPortal.php?bookingId=${booking.id}&cancellationFee=${feeAmount}`;
                };

            }

            popup.classList.remove('hidden');
        }

        // Close the popup
        function closeCancelBox() {
            console.log("closeCancelBox");
            document.getElementById('cancelPopup').classList.add('hidden');
        }

        function closePolicyBox() {
            console.log("closeCancelBox");
            document.getElementById('cancelPolicyPopup').classList.add('hidden');
        }

        // Confirm cancellation (AJAX or form submission)
        function confirmCancellation(bookingId, cancellationFee, refundAmount, bankDetails) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?php echo URLROOT; ?>/RegisteredPages/cancelBooking';
            
            const data = {
                booking_id: bookingId,
                cancellation_fee: cancellationFee,
                refund_amount: refundAmount,
                ...bankDetails
            };
            
            // Create hidden inputs for all data
            Object.entries(data).forEach(([key, value]) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                form.appendChild(input);
            });
            
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
