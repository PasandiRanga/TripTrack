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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel ="stylesheet" href="file:///E:/fontawesome/css/all.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar/navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/RegisteredUser/newBookings.css?v=<?php echo time(); ?>">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

</head>
    <script>
        var userRole = <?php echo json_encode($_SESSION['userRole'] ?? 'RegisteredUser'); ?>;
        localStorage.setItem('userRole', userRole);
    </script>

    <?php
    echo '<script> console.log(' . json_encode($data) . ') </script>';
    $cancellationData = $data['cancellations'] ?? [];
    echo '<script> console.log("Cancellation data:", ' . json_encode($cancellationData) . ') </script>';
    $userId = $_SESSION['user_id'] ?? '';
    $userRole = $_SESSION['user_role'] ?? 'RegisteredUser';
    $upcomingBookingData = $data['upcomingbookings'] ?? [];
    $pastBookingData = $data['pastbookings'] ?? [];
    $upcomingScheduleData = $data['upcomingSchedule'] ?? [];
    $pastScheduleData = $data['pastSchedule'] ??[];
    $userData = $data['user'] ?? [];
    $notifications = $data['notifications'] ?? [];
    $busData = $data['bus'] ?? [];
    $data['currentController'] = 'RegisteredPages';
    $data['currentMethod'] = 'bookings';
    $data['userRole'] = $userRole;
?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <div class="hero-container">
        <br/>
        <center><div class="header-container">
            <?php require APPROOT.'/views/inc/Components/Header/header.php'; ?>
        </div></center>
        <br/>
    </div>
    

    <?php
        $currentDate = date("Y-m-d");
        $upcomingBookings = [];
        $pastBookings = [];

        $upcomingSchedules = [];
        $pastSchedules = [];

        foreach ($upcomingBookingData as $upcoming) {
            $matchedSchedules = array_filter($upcomingScheduleData, function ($s) use ($upcoming) {
                return $s['scheduleId'] == $upcoming['schedule_id']; 
            });
            $upcomingSchedules = array_merge($upcomingSchedules, $matchedSchedules);
            echo '<script> console.log(' . json_encode(value: $upcomingSchedules) . ') </script>';

        }

        foreach($pastBookingData as $past) {
            $matchedSchedules = array_filter($pastScheduleData, function ($s) use ($past) {

                return $s['scheduleId'] == $past['schedule_id']; 
            });
            $pastSchedules = array_merge($pastSchedules, $matchedSchedules);
            echo '<script> console.log(' . json_encode(value: $pastSchedules) . ') </script>';

        }

    ?>

    <?php
    if (isset($_SESSION['error'])) {
        echo "<p class='error'>" . $_SESSION['error'] . "</p>"; 
        unset($_SESSION['error']); 
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
                <div class="close-column-btn">&times;</div>
                <div class="date-details">
                        <div id="date-info">
                        </div>
                </div>
            </div>
        </div>
    </center>

    <div id="cancelPolicyPopup" class="policypopup hidden">
        <div class="policypopup-content">
            <h3 align="center">Cancel Booking</h3>
            <p id="policypopup-details"></p>
            <div class="policypopup-actions">
                <button id="understand" class="uderstant-btn">I understand</button>
                <button id="closePopup" class="cancel-btn" onclick="closePolicyBox()">Close</button>
            </div>
        </div>
    </div>

    <div id="cancelPopup" class="popup hidden">
        <div class="cancel-popup-content">
            <h3>Cancel Booking</h3>
            <p id="popup-details"></p>
            <div class="popup-actions">
                <button id="confirmCancel" class="confirm-btn">Confirm</button>
                <button id="closePopup" class="cancel-btn" onclick="closeCancelBox()">Close</button>
            </div>
        </div>
    </div>


    <div id="ticketViewPopup" class="popup hidden">
        <div id="ticketBox" class="ticketPopup-content">
            <sapan id="closeTicketBtn" class="close-popup">&times;</sapan>
            <div class="ticketView-popup-details">
            </div>
        </div>
    </div>

    
    <div id="reviewPopup" class="popup hidden">
        <div class="reviewPopup-content">
            <div id="review-popup-details"></div>
            <h3>Tell Us How the Wheels Rolled!</h3>
                
            <form action="<?php echo URLROOT ?>/RegisteredPages/addReviews" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="license_id" id="license_id_input" value="">
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($_SESSION['user_id']); ?>">

                <div class="rating">
                    <input type="number" name="rating" hidden>
                    <i class='bx bx-star star' style="--i: 0;"></i>
                    <i class='bx bx-star star' style="--i: 1;"></i>
                    <i class='bx bx-star star' style="--i: 2;"></i>
                    <i class='bx bx-star star' style="--i: 3;"></i>
                    <i class='bx bx-star star' style="--i: 4;"></i>
                </div>
                <textarea name="opinion" cols="30" rows="5" placeholder="Let your travel tale ride with us..." required></textarea>
                <div class="popup-actions">
                    <button type="submit" class="post">Post</button>
                    <button class="cancel-btn" id="closeButton">close</button>
                </div>
            </form>    
        </div>
    </div>

    <script>
    const upcomingBookings = <?php echo json_encode($upcomingBookingData); ?>;
    const pastBookings = <?php echo json_encode($pastBookingData); ?>;
    const upcomingScheduleData = <?php echo json_encode($upcomingScheduleData); ?>;
    const pastScheduleData = <?php echo json_encode($pastScheduleData); ?>;
    const busData = <?php echo json_encode($busData); ?>;
    const cancelledBookings = <?php echo json_encode($cancellationData); ?>;
    const userData = <?php echo json_encode($data['user'] ?? []); ?>;
    </script>

<script>
    const URLROOT = '<?php echo URLROOT; ?>';
</script>
    </body>
    </html>

    <script src="<?php echo URLROOT; ?>/public/js/bookings.js?v=<?php echo time(); ?>"></script>
