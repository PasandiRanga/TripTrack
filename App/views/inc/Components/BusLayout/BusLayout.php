<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Bus Layout</title>
        <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/BusLayout/BusLayout.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/BusLayout/busInfo.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/BusLayout/bookingForm.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/BusLayout/seatLayout.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar/navbar.css?v=<?php echo time(); ?>">
    </head>
    <body>
        <script>
            var userRole = <?php echo json_encode($_SESSION['userRole'] ?? 'GuestUser'); ?>;
            localStorage.setItem('userRole', userRole);
        </script>

        <?php
            $userID = $_SESSION['user_id'] ?? null;
            $userRole = $_SESSION['user_role'] ?? 'RegisteredUser';
            $userData = $data['user'] ?? [];
            $scheduleData = $data['schedule'] ?? [];
            $busData = $data['bus'] ?? [];
            $routeData = $data['route'] ?? [];
            $distanceData = $data['distance'] ?? [];
            $pastNotArrivedBookings = $data['pastNotArrivedBookings'] ?? [];
            $averageRatings = $data['averageRatings'] ?? [];

            // Retrieve the user role from the form submission or session
            $formUserRole = ($_SESSION['user_role'] ?? 'GuestUser');

            // Set zuserRole and currentController based on the form data or session
            if ($formUserRole === 'GuestUser') {
                $userRole = 'GuestUser';
                $currentController = 'GuestPages';
            } elseif ($formUserRole === 'RegisteredUser') {
                $userRole = 'RegisteredUser';
                $currentController = 'RegisteredPages';
                $notifications = $data['notifications'];
            } else {
                $userRole = 'GuestUser'; // Default to GuestUser if no valid role is provided
                $currentController = 'GuestPages';
            }

            $data['currentController'] = $currentController;
            $data['currentMethod'] = 'home';
            $data['userRole'] = $userRole;

            include 'seatData.php';
                
            // Retrieve data from POST
            $License_id = $_GET['Licenseid'] ?? null;
            $scheduleId = $_GET['scheduleId'] ?? null;

            // Find the selected bus and schedule to get booked seats
            $selectedBus = null;
            $bookedSeats = [];
            $pricePerSeat = 0;
            $busLayout = [];
            $busType = null;
            $busType = null;
                
            // Find the selected bus and schedule to get booked seats
            foreach ($scheduleData as $schedule) {
                if ($schedule['scheduleId'] === $scheduleId) {
                    $bookedSeatsString = trim($schedule['bookedSeats']);
                    $bookedSeats = !empty($bookedSeatsString) ? 
                    array_map('trim', explode(',', $bookedSeatsString)) : [];
                    break;
                }
            }

            // Find the respective bus and see the bus type
            foreach($busData as $bus) {
                if ($bus['License_id'] === $License_id) {
                    $selectedBus = $bus;
                    $busType = $bus['passengers'];
                    $leastPrice = $bus['priceperkm'];
                    break;
                }
            }

            //Find the respective route and stops
            $busStops = [];
            foreach ($routeData as $route) {
                if ($route['routeNumber'] === $selectedBus['routeNumber']) {
                    $busStops = explode(',', $route['stops']);
                    $pricePerSeat = $route['price'];
                    break;
                }
            }

            // get the respective seat layout
            foreach ($seatData as $layout) {
                if($layout['seatType'] == $busType) {
                    $busLayout = $layout['seats']; 
                    break;
                }
            }

            // Find the selected schedule for the bus
            if ($selectedBus) {
                foreach ($scheduleData as $schedule) {
                    if ($schedule['License_id'] == $License_id && $schedule['scheduleId'] == $scheduleId) {
                        $selectedSchedule = $schedule;
                        break;
                    }
                }
            }
        ?>

        <script>
            var averageRatings = <?php echo json_encode($averageRatings); ?>;
            console.log("Average ratings: ",averageRatings);
        </script>

        <div class="hero-container">
            <br/>
            <?php require APPROOT . '/views/inc/Components/Header/header.php'; ?>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
   
            <div class="layout-container">
                <div class="seat-layout">
                    <?php require APPROOT . '/views/inc/Components/BusLayout/seatLayout.php'; ?>
                </div>

                <div class="all-container"> 
                    <!-- Bus info and review -->
                    <div class="businfocontainer">
                        <?php require APPROOT . '/views/inc/Components/busLayout/busInforAndReviews.php'; ?>
                    </div>
                    <!-- Booking form -->
                    <div class="bookForm">
                        <?php require APPROOT . '/views/inc/Components/busLayout/bookingForm.php'; ?>
                    </div>
                </div>
            </div>
        </div>

        <?php 
            $selectedBusJSON = json_encode($selectedBus);
            $distanceDataJSON = json_encode($distanceData);
            echo "<script>
                    const selectedBus = $selectedBusJSON;
                    const distanceData = $distanceDataJSON;
                    const leastPrice = $leastPrice;
                </script>";
        ?>


<script src="<?php echo URLROOT; ?>/public/js/bookingForm.js?v=<?php echo time(); ?>"></script>

</body>
</html>