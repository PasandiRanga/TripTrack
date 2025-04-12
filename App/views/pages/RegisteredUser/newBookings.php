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

    <title>newBookings <?php echo SITENAME; ?></title>

    <!-- External Stylesheets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar/navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/RegisteredUser/newBookings.css?v=<?php echo time(); ?>">
</head>
    <!-- Set user role in localStorage -->
    <script>
        var userRole = <?php echo json_encode($_SESSION['userRole'] ?? 'RegisteredUser'); ?>;
        localStorage.setItem('userRole', userRole);
    </script>

    <?php
        echo '<script> console.log(' . json_encode($data) . ') </script>';
        
        $userId = $_SESSION['user_id'] ?? '';
        $userRole = $_SESSION['user_role'] ?? 'RegisteredUser';
        $upcomingBookingData = $data['upcomingbookings'] ?? [];
        $pastBookingData = $data['pastbookings'] ?? [];
        $upcomingScheduleData = $data['upcomingSchedule'] ?? [];
        $pastScheduleData = $data['pastSchedule'] ??[];
        $notifications = $data['notifications'] ?? [];
        // $bookingData = $data['bookingsDetails'] ?? [];
        $busData = $data['bus'] ?? [];
        // $userData = $data['user'] ?? [];
        $data['currentController'] = 'RegisteredPages';
        $data['currentMethod'] = 'allNotifications';
        $data['userRole'] = $userRole;

    ?>

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
        // echo($currentDate);
        
        // Filter upcoming and past bookings based on the schedule date
        $upcomingBookings = [];
        $pastBookings = [];

        $upcomingSchedules = [];
        $pastSchedules = [];

        foreach ($upcomingBookingData as $upcoming) {
            $matchedSchedules = array_filter($upcomingScheduleData, function ($s) use ($upcoming) {
                return $s['scheduleId'] == $upcoming['schedule_id']; // Match schedule by ID
            });
            // Merge results to ensure all schedules are collected
            $upcomingSchedules = array_merge($upcomingSchedules, $matchedSchedules);
            echo '<script> console.log(' . json_encode(value: $upcomingSchedules) . ') </script>';

        }

        foreach($pastBookingData as $past) {
            $matchedSchedules = array_filter($pastScheduleData, function ($s) use ($past) {
                return $s['scheduleId'] == $past['schedule_id']; // Match schedule by ID
            });
            // Merge results to ensure all schedules are collected
            $pastSchedules = array_merge($pastSchedules, $matchedSchedules);
            echo '<script> console.log(' . json_encode(value: $pastSchedules) . ') </script>';

        }

    ?>

    <?php
    if (isset($_SESSION['error'])) {
        echo "<p class='error'>" . $_SESSION['error'] . "</p>"; // Display the error message
        unset($_SESSION['error']); // Clear the error message from session after displaying
    }
    ?>


    <body>
        <center>
        <div class="container">
            <div class="column">
                <div class="wrapper">
                    <header>
                        <p class="current-date"></p>
                        <div class="icons">
                            <span id="prev" class="prev">&#10094;</span>
                            <span id="next" class="next">&#10095;</span>
                        </div>
                    </header>
                    <div class="calendar">
                        <ul class="weeks">
                            <li>Sun</li>
                            <li>Mon</li>
                            <li>Tue</li>
                            <li>Wed</li>
                            <li>Thu</li>
                            <li>Fri</li>
                            <li>Sat</li>
                        </ul>
                        <ul class="days"></ul>
                    </div>
                </div> 
            </div>
            <div class="column">
                <div class="date-details">
                        <div id="date-info">
                        </div>
                </div>
            </div>
        </div>
    </center>

    <!--Cancel Policy pop up -->
    <div id="cancelPolicyPopup" class="policypopup hidden">
        <div class="policypopup-content">
            <h3 align="center">Cancel Booking</h3>
            <p id="policypopup-details"></p>
            <!--Content will come here -->
            <div class="policypopup-actions">
                <button id="understand" class="uderstant-btn">I understand</button>
                <button id="closePopup" class="cancel-btn" onclick="closePolicyBox()">Close</button>
            </div>
        </div>
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

    </body>
    </html>

    <script>
document.addEventListener("DOMContentLoaded", function () {
    const currentDate = document.querySelector(".current-date");
    const daysTag = document.querySelector(".days");
    const prevNextIcons = document.querySelectorAll(".icons span");
    const column2 = document.querySelector(".column:nth-child(2)");

    // Get PHP variables
    const upcomingBookings = <?php echo json_encode($upcomingBookingData); ?>;
    const pastBookings = <?php echo json_encode($pastBookingData); ?>;
    const upcomingScheduleData = <?php echo json_encode($upcomingScheduleData); ?>;
    const pastScheduleData = <?php echo json_encode($pastScheduleData); ?>;
    const busData = <?php echo json_encode($busData); ?>;

    let date = new Date(),
        currYear = date.getFullYear(),
        currMonth = date.getMonth();

    const months = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];

    function getBookingStatusForDate(dateStr) {
        const scheduleIds = new Set();
        let hasUpcoming = false;
        let hasPast = false;

        // Check upcoming bookings
        upcomingBookings.forEach(booking => {
            const schedule = upcomingScheduleData.find(s => s.scheduleId === booking.schedule_id);
            if (schedule && schedule.date === dateStr) {
                hasUpcoming = true;
                scheduleIds.add(booking.schedule_id);
            }
        });

        // Check past bookings
        pastBookings.forEach(booking => {
            const schedule = pastScheduleData.find(s => s.scheduleId === booking.schedule_id);
            if (schedule && schedule.date === dateStr) {
                hasPast = true;
                scheduleIds.add(booking.schedule_id);
            }
        });

        return {
            hasUpcoming,
            hasPast,
            scheduleIds: Array.from(scheduleIds)
        };
    }

    function renderCalendar() {
        let firstDayofMonth = new Date(currYear, currMonth, 1).getDay();
        let lastDateofMonth = new Date(currYear, currMonth + 1, 0).getDate();
        let lastDayofMonth = new Date(currYear, currMonth, lastDateofMonth).getDay();
        let lastDateofLastMonth = new Date(currYear, currMonth, 0).getDate();
        let liTag = "";

        for (let i = firstDayofMonth; i > 0; i--) {
            liTag += `<li class="inactive">${lastDateofLastMonth - i + 1}</li>`;
        }

        for (let i = 1; i <= lastDateofMonth; i++) {
            const dateStr = `${currYear}-${String(currMonth + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
            const bookingStatus = getBookingStatusForDate(dateStr);
            
            let className = "";
            if (i === date.getDate() && currMonth === new Date().getMonth() && currYear === new Date().getFullYear()) {
                className = "active";
            }
            if (bookingStatus.hasUpcoming) className += " upcoming-booking";
            if (bookingStatus.hasPast) className += " past-booking";

            liTag += `<li class="${className}" data-date="${dateStr}">${i}</li>`;
        }

        for (let i = lastDayofMonth; i < 6; i++) {
            liTag += `<li class="inactive">${i - lastDayofMonth + 1}</li>`;
        }

        currentDate.innerText = `${months[currMonth]} ${currYear}`;
        daysTag.innerHTML = liTag;

        // Attach event listeners to dates
        document.querySelectorAll(".days li").forEach(day => {
            day.addEventListener("click", function () {
                if (!this.classList.contains("inactive")) {
                    showDateDetails(this.getAttribute("data-date"));
                    column2.style.display = "block";
                }
            });
        });
    }

    function showDateDetails(dateStr) {
        const bookingStatus = getBookingStatusForDate(dateStr);
        let dateInfo = `<h3>Bookings for ${dateStr}</h3>`;

        if (!bookingStatus.scheduleIds.length) {
            dateInfo += `<p>No bookings available for this date.</p>
                         <br><br>
                         <img class="cal" src="<?php echo URLROOT; ?>/public/images/calender.png" alt="calendar">`;
        } else {
            dateInfo += `<div class="booking-list">`;
            
            // Show upcoming bookings
            if (bookingStatus.hasUpcoming) {
                dateInfo += `<h4>Upcoming Bookings</h4>`;
                upcomingBookings.forEach(booking => {
                    const schedule = upcomingScheduleData.find(s => s.scheduleId === booking.schedule_id);
                    if (schedule && schedule.date === dateStr) {
                        const bus = busData.find(b => b.busId === schedule.busId);
                        dateInfo += `
                            <div class="booking-item upcoming" onclick="toggleDetails(event, this)">
                                <div class="three-dots" onclick="toggleMenu(event)">&#x22EE;</div>
                                <div class="menu">
                                    <ul>
                                        <li>Option 1</li>
                                        <li class="cancel-booking" data-booking-id="${booking.id}" data-schedule-id="${schedule.scheduleId}">Cancel</li>
                                        <li>Option 3</li>
                                    </ul>
                                </div>
                                <div class="booking-summary">
                                    <span class="arrow-icon">▼</span>
                                    <p><strong>${booking.from_location} - </strong>
                                    <strong>${booking.to_location}</strong></p>
                                </div>
                                <div class="booking-details">
                                    <p><strong>From:</strong> ${booking.from_location}</p>
                                    <p><strong>To:</strong> ${booking.to_location}</p>
                                    <p><strong>Time:</strong> ${schedule.departureTime}</p>
                                    <p><strong>Bus:</strong> ${bus ? bus.License_id : 'N/A'}</p>
                                    <p><strong>Booking ID:</strong> ${booking.id}</p>
                                    <p><strong>Payment Method:</strong> ${booking.paymentMethod}</p>
                                </div>
                            </div>
                        `;
                    }
                });
            }

            // Show past bookings
            if (bookingStatus.hasPast) {
                dateInfo += `<h4>Past Bookings</h4>`;
                pastBookings.forEach(booking => {
                    const schedule = pastScheduleData.find(s => s.scheduleId === booking.schedule_id);
                    if (schedule && schedule.date === dateStr) {
                        const bus = busData.find(b => b.busId === schedule.busId);
                        dateInfo += `
                            <div class="booking-item past" onclick="toggleDetails(event, this)">
                                <div class="three-dots" onclick="toggleMenu(event)">&#x22EE;</div>
                                <div class="menu">
                                    <ul>
                                        <li>Option 1</li>
                                        <li>Option 2</li>
                                        <li>Option 3</li>
                                    </ul>
                                </div>
                                <div class="booking-summary">
                                    <span class="arrow-icon">▼</span>
                                    <p><strong>${booking.from_location} - </strong>
                                    <strong>${booking.to_location}</strong></p>
                                </div>
                                <div class="booking-details">
                                    <p><strong>From:</strong> ${booking.from_location}</p>
                                    <p><strong>To:</strong> ${booking.to_location}</p>
                                    <p><strong>Time:</strong> ${schedule.departureTime}</p>
                                    <p><strong>Bus:</strong> ${bus ? bus.License_id : 'N/A'}</p>
                                    <p><strong>Booking ID:</strong> ${booking.id}</p>
                                </div>
                            </div>
                        `;
                    }
                });
            }

            
            dateInfo += `</div>`;
        }

        document.getElementById("date-info").innerHTML = dateInfo;

        // Add this after the dateInfo is inserted into the DOM
        document.querySelectorAll('.cancel-booking').forEach(item => {
            item.addEventListener('click', function() {
                const bookingId = this.getAttribute('data-booking-id');
                const scheduleId = this.getAttribute('data-schedule-id');
                
                // Find the booking and schedule objects
                const bookingObj = [...upcomingBookings, ...pastBookings].find(b => b.id == bookingId);
                const scheduleObj = upcomingScheduleData.find(s => s.scheduleId == scheduleId) || pastScheduleData.find(s => s.scheduleId == scheduleId);
                
                if (bookingObj && scheduleObj) {
                    showCancelPolicy(bookingObj, scheduleObj);
                }
            });
        });
    }

    renderCalendar();

    prevNextIcons.forEach(icon => {
        icon.addEventListener("click", () => {
            currMonth = icon.id === "prev" ? currMonth - 1 : currMonth + 1;
            if (currMonth < 0 || currMonth > 11) {
                date = new Date(currYear, currMonth);
                currYear = date.getFullYear();
                currMonth = date.getMonth();
            } else {
                date = new Date();
            }
            renderCalendar();
        });
    });
});

function toggleMenu(event) {
    event.stopPropagation(); // Prevent bubbling
    const menu = event.target.nextElementSibling;
    console.log("Clicked menu:", menu); // Debug log

    if (menu && menu.classList.contains('menu')) {
        document.querySelectorAll('.menu').forEach(m => {
            if (m !== menu) m.classList.remove('show');
        });
        menu.classList.toggle('show');
    }
}


// Add event listener to detect clicks outside the menu
document.addEventListener("click", function(event) {
    const menu = document.querySelector('.menu');
    const threeDots = document.querySelector('.three-dots');
    
    // If the click is outside the menu and the three dots
    if (!menu.contains(event.target) && event.target !== threeDots) {
        menu.classList.remove('show');
    }
});

function showCancelPolicy(booking , schedule){
    const policypopup = document.getElementById('cancelPolicyPopup');
    const policydetails = document.getElementById('policypopup-details');
    const understandBtn = document.getElementById('understand');

    const containerDiv = document.createElement('div');
    containerDiv.className = 'cancellation-form';

    policydetails.innerHTML = `
        <div class="p-4">
            <h3 class="text-lg font-bold mb-4" align="center">Cancellation Policy</h3>
            <ul class="my-4">
                <li>Cancellation 1 or more days before departure: 10% cancellation fee </li>
                <li>Cancellation within 24 hours of departure: 50% cancellation fee</li>
            </ul>
        </div>
    `;

    // policydetails.innerHTML = '';
    // policydetails.appendChild(containerDiv);

    understandBtn.onclick = function(){
        showCancelPopup(booking , schedule);
        policypopup.classList.add('hidden');
    };

    policypopup.classList.remove('hidden');
}

function calculateCancellationFeePercentage(scheduledate) {
    const today = new Date();
    const scheduleDate = new Date(scheduledate);
    const diffTime = scheduleDate.getTime() - today.getTime();
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
    if (diffDays >= 1) {
        return 0.10; // 10% fee
    } else {
        return 0.50; // 50% fee
    }
}

function showCancelPopup(booking , schedule){
    const popup = document.getElementById('cancelPopup');
    const details = document.getElementById('popup-details');
    const confirmBtn = document.getElementById('confirmCancel');

    const cancellationFeePercentage = calculateCancellationFeePercentage(schedule.date);
    const feeAmount = booking.total_price * cancellationFeePercentage;
    const refundAmount = booking.total_price - feeAmount;

    // const containerDiv = document.createElement('div');
    // containerDiv.className = 'cancellation-form';

    if(booking.paymentMethod == 'Online'){
        details.innerHTML = `
        <div class ="cancellation-form">
            <div class="booking-details">
                <div class="detail-row">
                    <span class="detail-label">Booking ID:</span>
                    <span class="detail-value">${booking.id}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Date : </span>
                    <span class="detail-value">${schedule.date}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Total Price : </span>
                    <span class="detail-value">LKR ${Number(booking.total_price).toFixed(2)}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Cancellation Fee : </span>
                    <span class="detail-value amount-highlight fee-amount">LKR ${feeAmount.toFixed(2)}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Refund Amount : </span>
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

        // details.innerHTML = '';
        // details.appendChild(containerDiv);
        
        // Add event listener to confirm button
        confirmBtn.onclick = function() {
            const bankDetails = {
                accountName: document.getElementById('accountName').value,
                bankName: document.getElementById('bankName').value,
                accountNumber: document.getElementById('accountNumber').value,
                branch: document.getElementById('branch').value
            };
            console.log(bankDetails);
                        
            if (!bankDetails.accountName || !bankDetails.bankName || 
                !bankDetails.accountNumber || !bankDetails.branch) {
                alert('Please fill in all bank details');
                return;
            }
                    
        confirmOnlineCancellation(booking.id, feeAmount, refundAmount, bankDetails,schedule.scheduleId);

        };

    } else if(booking.paymentMethod === "Cash"){
        details.innerHTML = `
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

        // Clear previous content and append the new container
        // details.innerHTML = '';
        // details.appendChild(containerDiv);

        confirmBtn.onclick = function() {
            const queryParams = new URLSearchParams({
                bookingId: booking.id,
                cancellationFee: feeAmount,
                refundAmount: refundAmount,
                scheduleId: schedule.scheduleId
            }).toString();

            window.location.href = `<?php echo URLROOT; ?>/RegisteredPages/paymentPortal?${queryParams}`;
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
function confirmOnlineCancellation(bookingId, cancellationFee, refundAmount, bankDetails, scheduleId) {
    console.log(bankDetails);
    console.log(scheduleId);
    console.log(refundAmount);
    console.log(cancellationFee);
    console.log("inside confirmOnlineCancellation");
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?php echo URLROOT; ?>/RegisteredPages/cancelBooking';
            
    const data = {
        schedule_id:scheduleId, 
        booking_id: bookingId,
        cancellation_fee: cancellationFee,
        refund_amount: refundAmount,
        bank_details_json: JSON.stringify(bankDetails)
    };

    console.log(data);

            
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

function toggleDetails(event, element) {
    // Don't toggle details if click is on the three-dots or menu
    if (event.target.closest('.three-dots') || event.target.closest('.menu')) {
        return;
    }

    const details = element.querySelector('.booking-details');
    const arrow = element.querySelector('.arrow-icon');

    if (details.classList.contains('show')) {
        details.classList.remove('show');
        arrow.textContent = '▼';
    } else {
        details.classList.add('show');
        arrow.textContent = '▲';
    }
}



</script>
