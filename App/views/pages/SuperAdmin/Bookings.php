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
<div class="box-wrraper">
    <h1>Booking Records</h1>

    <br>
    <div class='box'>
    <!-- Select box for filtering booking types -->
    <div class="filter-box-container">
        <label for="bookingType">Select Booking Type:</label>
        <select id="bookingType" onchange="toggleBookingType()">
            <option value="guest">Guest User Bookings</option>
            <option value="registered">Registered User Bookings</option>
            <option value="cancel_online">Cancelled Online Bookings</option>
            <option value="cancel_cash">Cancelled Cash Bookings</option>
        </select>
    </div>

<!-- Search box -->
    <div class="search-container">
        <label for="searchBox">Search:</label>
        <input type="text" id="searchBox" onkeyup="searchTable()" placeholder="Search for bookings...">
    </div>

<!-- Filter checkboxes -->
    <div class="filter-container">
        <label><input type="checkbox" id="filterToday" class="filter-checkbox"> Today</label>
        <label><input type="checkbox" id="filterYesterday" class="filter-checkbox"> Yesterday</label>
        <label><input type="checkbox" id="filterThisWeek" class="filter-checkbox"> This Week</label>
        <label><input type="checkbox" id="filterThisMonth" class="filter-checkbox"> This Month</label>
    </div>
    </div>

<div class="booking-table-container">
    <table class="booking-table">
        <!-- Guest bookings table -->
        <thead id="guest-thead">
            <tr>
                <th>ID</th>
                <th>Booking Date</th>
                <th>Booking Time</th>
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
                <tr class="booking-row" onclick="toggleDetails(this)">
                    <td><?= $booking['id'] ?></td>
                    <td><?= $booking['booking_date'] ?></td>
                    <td><?= $booking['booking_time'] ?></td>
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
                <!-- Hidden row for additional details -->
                <tr class="details-row" style="display:none;">
                    <td colspan="13">
                        <div class="additional-details">
                            <p><strong>Selected Seats:</strong> <?= $booking['selected_seats'] ?></p>
                            <p><strong>Total Price:</strong> <?= $booking['total_price'] ?></p>
                            <p><strong>Schedule ID:</strong> <?= $booking['schedule_id'] ?></p>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

        <!-- Registered bookings table -->
<div class="booking-table-container">
    <table class="booking-table">
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
                <tr class="booking-row" onclick="toggleDetails(this)">
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
                <!-- Hidden row for additional details -->
                <tr class="details-row" style="display:none;">
                    <td colspan="10">
                        <div class="additional-details">
                            <p><strong>Selected Seats:</strong> <?= $booking1['selected_seats'] ?></p>
                            <p><strong>Total Price:</strong> <?= $booking1['total_price'] ?></p>
                            <p><strong>Schedule ID:</strong> <?= $booking1['schedule_id'] ?></p>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Cancelled online bookings table -->
<div class="booking-table-container">
    <table class="booking-table">
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
                <tr class="booking-row" onclick="toggleDetails(this)">
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
                <!-- Hidden row for additional details -->
                <tr class="details-row" style="display:none;">
                    <td colspan="11">
                        <div class="additional-details">
                            <p><strong>Seats:</strong> <?= $cancel['Seats'] ?></p>
                            <p><strong>Total Price:</strong> <?= $cancel['total_price'] ?></p>
                            <p><strong>Schedule ID:</strong> <?= $cancel['schedule_id'] ?></p>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Cancelled cash bookings table -->
<div class="booking-table-container">
    <table class="booking-table">
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
                <tr class="booking-row" onclick="toggleDetails(this)">
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
                <!-- Hidden row for additional details -->
                <tr class="details-row" style="display:none;">
                    <td colspan="11">
                        <div class="additional-details">
                            <p><strong>Seats:</strong> <?= $cancel['Seats'] ?></p>
                            <p><strong>Total Price:</strong> <?= $cancel['total_price'] ?></p>
                            <p><strong>Schedule ID:</strong> <?= $cancel['schedule_id'] ?></p>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</div>
    <script>
        function toggleDetails(row) {
            // Find the next sibling of the clicked row (which is the hidden details row)
            const detailsRow = row.nextElementSibling;
            
            // Toggle the visibility of the details row
            if (detailsRow.style.display === 'none' || detailsRow.style.display === '') {
                detailsRow.style.display = 'table-row';
            } else {
                detailsRow.style.display = 'none';
            }
        }

        function searchTable() {
            const searchValue = document.getElementById('searchBox').value.toLowerCase();
            const rows = document.querySelectorAll('.booking-table tbody tr');

            rows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                row.style.display = rowText.includes(searchValue) ? '' : 'none';
            });
        }

        document.querySelectorAll('.filter-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', (event) => {
                if (event.target.checked) {
                    document.querySelectorAll('.filter-checkbox').forEach(cb => {
                        if (cb !== event.target) cb.checked = false;
                    });
                }
                filterTable(); // Call the filter function on change
            });
        });

        function filterTable() {
            const filterToday = document.getElementById('filterToday').checked;
            const filterYesterday = document.getElementById('filterYesterday').checked;
            const filterThisWeek = document.getElementById('filterThisWeek').checked;
            const filterThisMonth = document.getElementById('filterThisMonth').checked;

            const rows = document.querySelectorAll('.booking-table tbody tr');
            const today = new Date();
            const oneDay = 24 * 60 * 60 * 1000;

            rows.forEach(row => {
                const bookingDate = new Date(row.cells[1]?.textContent);
                let isVisible = true;

                if (filterToday) {
                    isVisible = bookingDate.toDateString() === today.toDateString();
                } else if (filterYesterday) {
                    const yesterday = new Date(today.getTime() - oneDay);
                    isVisible = bookingDate.toDateString() === yesterday.toDateString();
                } else if (filterThisWeek) {
                    const startOfWeek = new Date(today);
                    startOfWeek.setDate(today.getDate() - today.getDay());
                    isVisible = bookingDate >= startOfWeek && bookingDate <= today;
                } else if (filterThisMonth) {
                    const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
                    isVisible = bookingDate >= startOfMonth && bookingDate <= today;
                }

                row.style.display = isVisible ? '' : 'none';
            });
        }


function toggleBookingType() {
    const bookingType = document.getElementById('bookingType').value;

    // Get all table containers (guest, registered, cancel online, and cancel cash)
    const guestTable = document.querySelector('#guest-thead').closest('table').parentElement;
    const registeredTable = document.querySelector('#registered-thead').closest('table').parentElement;
    const cancelOnlineTable = document.querySelector('#cancel-online-thead').closest('table').parentElement;
    const cancelCashTable = document.querySelector('#cancel-cash-thead').closest('table').parentElement;

    // Hide all tables by default
    guestTable.style.display = 'none';
    registeredTable.style.display = 'none';
    cancelOnlineTable.style.display = 'none';
    cancelCashTable.style.display = 'none';

    // Show the selected table based on the booking type
    if (bookingType === 'guest') {
        guestTable.style.display = '';
    } else if (bookingType === 'registered') {
        registeredTable.style.display = '';
    } else if (bookingType === 'cancel_online') {
        cancelOnlineTable.style.display = '';
    } else if (bookingType === 'cancel_cash') {
        cancelCashTable.style.display = '';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    toggleBookingType();  // Make sure the correct table is shown when the page loads
});
    </script>
</body>
</html>
