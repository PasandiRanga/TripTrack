<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Admin']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Records</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/Bookings.css?v=<?php echo time(); ?>">
</head>
<body>
    <!-- Back button -->
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/home'">Back</button>

    <h1>Booking Records</h1>

    <!-- Select box for filtering booking types -->
    <div class="filter-container">
        <label for="bookingType">Select Booking Type:</label>
        <select id="bookingType" onchange="toggleBookingType()">
            <option value="guest">Guest User Bookings</option>
            <option value="registered">Registered User Bookings</option>
            <option value="cancel_online">Cancelled Online Bookings</option>
            <option value="cancel_cash">Cancelled Cash Bookings</option>
        </select>
    </div>

    <!-- Booking tables -->
    <div class="booking-table-container">
    <table class="booking-table">
        <!-- Guest bookings table -->
        <thead id="guest-thead">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Contact</th>
                <th>NIC</th>
                <th>From</th>
                <th>To</th>
                <th>No. of Seats</th>
                <th>Selected Seats</th>
                <th>Total Price</th>
                <th>Schedule ID</th>
            </tr>
        </thead>
        <tbody id="guest-tbody">
            <?php foreach ($data['book'] as $booking): ?>
                <tr>
                    <td><?= $booking['id'] ?></td>
                    <td><?= $booking['name'] ?></td>
                    <td><?= $booking['email'] ?></td>
                    <td><?= $booking['contact'] ?></td>
                    <td><?= $booking['nic'] ?></td>
                    <td><?= $booking['from_location'] ?></td>
                    <td><?= $booking['to_location'] ?></td>
                    <td><?= $booking['number_of_seats'] ?></td>
                    <td><?= $booking['selected_seats'] ?></td>
                    <td><?= $booking['total_price'] ?></td>
                    <td><?= $booking['schedule_id'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>

        <!-- Registered bookings table -->
        <thead id="registered-thead">
            <tr>
                <th>ID</th>
                <th>Booking Date</th>
                <th>Booking Time</th>
                <th>No. of Seats</th>
                <th>Seats</th>
                <th>User ID</th>
                <th>Schedule ID</th>
                <th>From</th>
                <th>To</th>
                <th>Total Price</th>
            </tr>
        </thead>
        <tbody id="registered-tbody">
            <?php foreach ($data['book1'] as $booking1): ?>
                <tr>
                    <td><?= $booking1['id'] ?></td>
                    <td><?= $booking1['booking_date'] ?></td>
                    <td><?= $booking1['booking_time'] ?></td>
                    <td><?= $booking1['number_of_seats'] ?></td>
                    <td><?= $booking1['selected_seats'] ?></td>
                    <td><?= $booking1['User_id'] ?></td>
                    <td><?= $booking1['schedule_id'] ?></td>
                    <td><?= $booking1['from_location'] ?></td>
                    <td><?= $booking1['to_location'] ?></td>
                    <td><?= $booking1['total_price'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>

        <!-- Cancelled online bookings table -->
        <thead id="cancel-online-thead">
            <tr>
                <th>ID</th>
                <th>Booking Date</th>
                <th>Booking Time</th>
                <th>No. of Seats</th>
                <th>Seats</th>
                <th>User ID</th>
                <th>Schedule ID</th>
                <th>From</th>
                <th>To</th>
                <th>Total Price</th>
                <th>Time and Date of Cancellation</th>
            </tr>
        </thead>
        <tbody id="cancel-online-tbody">
            <?php foreach ($data['cancel_online_bookings'] as $cancel): ?>
                <tr>
                    <td><?= $cancel['id'] ?></td>
                    <td><?= $cancel['Booking_date'] ?></td>
                    <td><?= $cancel['Booking_time'] ?></td>
                    <td><?= $cancel['No_of_seats'] ?></td>
                    <td><?= $cancel['Seats'] ?></td>
                    <td><?= $cancel['User_id'] ?></td>
                    <td><?= $cancel['schedule_id'] ?></td>
                    <td><?= $cancel['from_location'] ?></td>
                    <td><?= $cancel['to_location'] ?></td>
                    <td><?= $cancel['total_price'] ?></td>
                    <td><?= $cancel['time_date'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>

        <!-- Cancelled cash bookings table -->
        <thead id="cancel-cash-thead">
            <tr>
                <th>ID</th>
                <th>Booking Date</th>
                <th>Booking Time</th>
                <th>No. of Seats</th>
                <th>Seats</th>
                <th>User ID</th>
                <th>Schedule ID</th>
                <th>From</th>
                <th>To</th>
                <th>Total Price</th>
                <th>Time and Date of Cancellation</th>
            </tr>
        </thead>

        <tbody id="cancel-cash-tbody">
            <?php foreach ($data['cancel_cash_bookings'] as $cancel): ?>
                <tr>
                    <td><?= $cancel['id'] ?></td>
                    <td><?= $cancel['Booking_date'] ?></td>
                    <td><?= $cancel['Booking_time'] ?></td>
                    <td><?= $cancel['No_of_seats'] ?></td>
                    <td><?= $cancel['Seats'] ?></td>
                    <td><?= $cancel['User_id'] ?></td>
                    <td><?= $cancel['schedule_id'] ?></td>
                    <td><?= $cancel['from_location'] ?></td>
                    <td><?= $cancel['to_location'] ?></td>
                    <td><?= $cancel['total_price'] ?></td>
                    <td><?= $cancel['time_date'] ?></td>
                </tr>
            <?php endforeach; ?>
    </table>
    </div>
    
    <script>
        function toggleBookingType() {
            const bookingType = document.getElementById('bookingType').value;

            const guestThead = document.getElementById('guest-thead');
            const guestTbody = document.getElementById('guest-tbody');
            const registeredThead = document.getElementById('registered-thead');
            const registeredTbody = document.getElementById('registered-tbody');
            const cancelOnlineThead = document.getElementById('cancel-online-thead');
            const cancelOnlineTbody = document.getElementById('cancel-online-tbody');
            const cancelCashThead = document.getElementById('cancel-cash-thead');
            const cancelCashTbody = document.getElementById('cancel-cash-tbody');

            if (bookingType === 'guest') {
            guestThead.style.display = '';
            guestTbody.style.display = '';
            registeredThead.style.display = 'none';
            registeredTbody.style.display = 'none';
            cancelOnlineThead.style.display = 'none';
            cancelOnlineTbody.style.display = 'none';
            cancelCashThead.style.display = 'none';
            cancelCashTbody.style.display = 'none';
            } else if (bookingType === 'registered') {
            guestThead.style.display = 'none';
            guestTbody.style.display = 'none';
            registeredThead.style.display = '';
            registeredTbody.style.display = '';
            cancelOnlineThead.style.display = 'none';
            cancelOnlineTbody.style.display = 'none';
            cancelCashThead.style.display = 'none';
            cancelCashTbody.style.display = 'none';
            } else if (bookingType === 'cancel_online') {
            guestThead.style.display = 'none';
            guestTbody.style.display = 'none';
            registeredThead.style.display = 'none';
            registeredTbody.style.display = 'none';
            cancelOnlineThead.style.display = '';
            cancelOnlineTbody.style.display = '';
            cancelCashThead.style.display = 'none';
            cancelCashTbody.style.display = 'none';
            } else if (bookingType === 'cancel_cash') {
            guestThead.style.display = 'none';
            guestTbody.style.display = 'none';
            registeredThead.style.display = 'none';
            registeredTbody.style.display = 'none';
            cancelOnlineThead.style.display = 'none';
            cancelOnlineTbody.style.display = 'none';
            cancelCashThead.style.display = '';
            cancelCashTbody.style.display = '';
            }
        }

        // Set default visibility on load
        toggleBookingType();
    </script>
</body>
</html>
