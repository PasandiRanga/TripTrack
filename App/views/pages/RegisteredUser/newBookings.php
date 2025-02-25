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
        $scheduleData = $data['schedule'] ?? [];
        $bookingData = $data['bookingsDetails'] ?? [];
        $busData = $data['bus'] ?? [];
        $userData = $data['user'] ?? [];
        $data = [
            'currentController' => 'RegisteredPages', // Adjust this based on your controller
            'currentMethod' => 'bookings', // Adjust this based on the method
            'userRole' => $userRole
        ];

        // echo '<script> console.log(' . json_encode($upcomingBookingData) . ') </script>';
        // echo '<script> console.log(' . json_encode($pastBookingData) . ') </script>';
        // echo '<script> console.log(' . json_encode($userId) . ') </script>';
        // echo '<script> console.log(' . json_encode($scheduleData) . ') </script>';
        // echo '<script> console.log(' . json_encode($bookingData) . ') </script>';   
        // echo '<script> console.log(' . json_encode($busData) . ') </script>';
        
    ?>

    <!-- <script>
        var scheduleData = <?php echo json_encode($scheduleData); ?>;
        console.log("Schedule Data: ", scheduleData);
        var upcomingBookingData = <?php echo json_encode($upcomingBookingData); ?>;
        console.log("Upcoming Booking Data: ", upcomingBookingData);
        var pastBookingData = <?php echo json_encode($pastBookingData); ?>;
        console.log("Past Booking Data: ", pastBookingData);
        var bookingData = <?php echo json_encode($bookingData); ?>;
        console.log("Booking Data: ", bookingData);
        var userId = <?php echo json_encode($userId); ?>;
        console.log("User ID: ", userId);
    </script> -->

  

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
            $matchedSchedules = array_filter($scheduleData, function ($s) use ($upcoming) {
                return $s['scheduleId'] == $upcoming['schedule_id']; // Match schedule by ID
            });

            // Merge results to ensure all schedules are collected
            $upcomingSchedules = array_merge($upcomingSchedules, $matchedSchedules);
        }

        foreach($pastBookingData as $past) {
            $matchedSchedules = array_filter($scheduleData, function ($s) use ($past) {
                return $s['scheduleId'] == $past['schedule_id']; // Match schedule by ID
            });

            // Merge results to ensure all schedules are collected
            $pastSchedules = array_merge($pastSchedules, $matchedSchedules);
        }

        
        // foreach ($bookingData as $booking) {
        //     // Assuming each booking has a `schedule_id` to match the scheduleData
        //     $schedule = array_filter($scheduleData, function($s) use ($booking) {
        //         return $s['scheduleId'] == $booking['schedule_id']; // Match schedule by ID
        //     });

        //     $schedule = reset($schedule); // Get the first matching schedule entry
        //     echo($schedule);

        //     if ($schedule) {
        //         // Compare booking date with schedule date
        //         if ($currentDate <= $schedule['date']) {
        //             $upcomingBookings[] = $booking; // Upcoming booking
        //         } else {
        //             $pastBookings[] = $booking; // Past booking
        //         }
        //     }
        // }
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
    const scheduleData = <?php echo json_encode($scheduleData); ?>;
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
            const schedule = scheduleData.find(s => s.scheduleId === booking.schedule_id);
            if (schedule && schedule.date === dateStr) {
                hasUpcoming = true;
                scheduleIds.add(booking.schedule_id);
            }
        });

        // Check past bookings
        pastBookings.forEach(booking => {
            const schedule = scheduleData.find(s => s.scheduleId === booking.schedule_id);
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
            dateInfo += `<p>No bookings available for this date.</p>`;
        } else {
            dateInfo += `<div class="booking-list">`;
            
            // Show upcoming bookings
            if (bookingStatus.hasUpcoming) {
                dateInfo += `<h4>Upcoming Bookings</h4>`;
                upcomingBookings.forEach(booking => {
                    const schedule = scheduleData.find(s => s.scheduleId === booking.schedule_id);
                    if (schedule && schedule.date === dateStr) {
                        const bus = busData.find(b => b.busId === schedule.busId);
                        dateInfo += `
                            <div class="booking-item upcoming">
                                <div class="three-dots" onclick="toggleMenu(event)">
                                    &#x22EE; <!-- Three dots icon -->
                                </div>
                                <div class="menu">
                                    <ul>
                                        <li>Option 1</li>
                                        <li>Option 2</li>
                                        <li>Option 3</li>
                                    </ul>
                                </div>
                                <div class="booking-content">
                                    <p>From: ${booking.from_location}</p>
                                    <p>To: ${booking.to_location}</p>
                                    <p>Time: ${schedule.departureTime}</p>
                                    <p>Bus: ${bus ? bus.License_id : 'N/A'}</p>
                                    <p>Booking ID: ${booking.id}</p>
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
                    const schedule = scheduleData.find(s => s.scheduleId === booking.schedule_id);
                    if (schedule && schedule.date === dateStr) {
                        const bus = busData.find(b => b.busId === schedule.busId);
                        dateInfo += `
                            <div class="booking-item past">
                                <div class="three-dots" onclick="toggleMenu(event)">
                                    &#x22EE; <!-- Three dots icon -->
                                </div>
                                <div class="menu">
                                    <ul>
                                        <li>Option 1</li>
                                        <li>Option 2</li>
                                        <li>Option 3</li>
                                    </ul>
                                </div>
                                <div class="booking-content">
                                    <p>From: ${booking.from_location}</p>
                                    <p>To: ${booking.to_location}</p>
                                    <p>Time: ${schedule.departureTime}</p>
                                    <p>Bus: ${bus ? bus.License_id : 'N/A'}</p>
                                    <p>Booking ID: ${booking.id}</p>
                                </div>
                            </div>
                        `;
                    }
                });
            }
            
            dateInfo += `</div>`;
        }

        document.getElementById("date-info").innerHTML = dateInfo;
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
    const menu = event.target.nextElementSibling;
    menu.classList.toggle('show');
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

</script>
