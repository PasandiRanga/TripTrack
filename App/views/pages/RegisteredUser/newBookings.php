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


<body onload="randerDate()">
    
    <div class="container">

        <div class="calendar">

            <div class="digital_clock">

                <div class="time">
                    <span class="hours">00</span>:
                    <span class="minutes">00</span>:
                    <span class="seconds">00</span>
                    <span class="format">AM</span>
                </div>

            </div>

            <div class="month">

                <div class="prev" onclick="moveDate('prev')">
                    <span class="arrow">&#10094</span>
                </div>

                <div>
                    <h2 id="month">April-2023</h2>
                    <p id="date">Tue April 20 2023</p>
                </div>

                <div class="next" onclick="moveDate('next')">
                    <span class="arrow">&#10095</span>
                </div>

            </div>

            <div class="week">
                <div>Sun</div>
                <div>Mon</div>
                <div>Tue</div>
                <div>Wed</div>
                <div>Thu</div>
                <div>Fri</div>
                <div>Sat</div>
            </div>

            <div class="dates">
                
            </div>

        </div>

        <div class="date-details">
            <h2>Details for the selected date will appear here</h2>
            <div id="date-info"></div>
        </div>

    </div>


   <script src="<?php echo URLROOT; ?>/public/js/newBookings.js"></script>
</body>
</html>
