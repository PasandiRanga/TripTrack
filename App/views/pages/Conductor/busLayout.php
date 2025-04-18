<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Conductor' , 'Driver']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Conductor/busLayout.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Conductor/seatLayout.css?v=<?php echo time(); ?>">
    <!--css files-->
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar/navbar.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=ABeeZee&display=swap" rel="stylesheet">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Layout</title>
</head>

<body>

    <script>
        var userRole = <?php echo json_encode($_SESSION['userRole'] ?? 'Conductor'); ?>;
        localStorage.setItem('userRole', userRole);
    </script>

    <?php
        $userRole = $_SESSION['user_role'] ?? 'Conductor';
        $scheduleData = $data['scheduleData'] ?? [];
        //$acceptedSeats = $scheduleData['acceptedSeats'] ?? [];
        $busData = $data['busData'] ?? [];

        /*$bookedSeatsArray = explode(',', $bookedSeats);
        $acceptedSeatsArray = explode(',', $acceptedSeats);

        $notAcceptedSeats = array_diff($bookedSeatsArray, $acceptedSeatsArray);
        $notAcceptedSeats = array_values($notAcceptedSeats);*/

        $data['currentController'] = 'ConductorPages';
        $data['currentMethod'] = 'busLayout';
        $data['userRole'] = $userRole;

        include APPROOT . '/views/inc/Components/BusLayout/seatData.php';

        $scheduleId = $_GET['schedule'];
        echo "<script>console.log('Schedule id :', " . json_encode($scheduleId) . ");</script>";

        $selectedBus = null;
        $bookedSeats = [];
        $acceptedSeats = [];
        $busLayout = [];
        $busType = null;

        foreach ($scheduleData as $schedule) {
          if ($schedule['scheduleId'] === $scheduleId) {
            echo "<script>console.log('Selected schedule:', " . json_encode($schedule) . ");</script>";
            $bookedSeatsString = trim($schedule['bookedSeats']);
            $bookedSeats = !empty($bookedSeatsString) ? array_map('trim', explode(',', $bookedSeatsString)) : [];
            $acceptedSeatsString = trim($schedule['acceptedSeats']);
            $acceptedSeats = !empty($acceptedSeatsString) ? array_map('trim', explode(',', $acceptedSeatsString)) : [];
            break;
          }
        }

        $notAcceptedSeats = array_diff($bookedSeats, $acceptedSeats);
        $notAcceptedSeats = array_values($notAcceptedSeats);

        foreach ($scheduleData as $schedule) {
          if ($schedule['scheduleId'] === $scheduleId) {
            $licenseId = $schedule['License_id'];
            break;
          }
        }

        foreach($busData as $bus) {
          if ($bus['License_id'] === $licenseId) {
            $selectedBus = $bus;
            echo "<script>console.log('Selected bus:', " . json_encode($selectedBus) . ");</script>";
            $busType = $bus['passengers'];
            echo "<script>console.log('bus type:', " . json_encode($busType) . ");</script>";
            break;
          }
        }

        echo "<script>console.log('Seat Data:', " . json_encode($seatData) . ");</script>";
        foreach ($seatData as $layout) {
            echo "<script>console.log('Seat type:', " . json_encode($layout) . ");</script>";
            //echo "<script>console.log('Seat type 2:', " . json_encode($layout['seats']) . ");</script>";
            echo "<script>console.log('bus type 1:', " . json_encode($busType) . ");</script>";
            if($layout['seatType'] == $busType) {
              //echo "<script>console.log('bus type 2:', " . json_encode($busType) . ");</script>";
              //echo "<script>console.log('Seat type 3:', " . json_encode($layout['seats']) . ");</script>";
              echo "<script>console.log('Seat Type 2:', " . json_encode($layout['seatType']) . ");</script>";
              $busLayout = $layout['seats'];
              //echo "<script>console.log('Bus layout:', " . json_encode($busLayout) . ");</script>";
              echo "<script>console.log('BusLayout:', " . json_encode($busLayout) . ");</script>";
              break;
            }
        }

        if ($selectedBus) {
                foreach ($scheduleData as $schedule) {
                    if ($schedule['License_id'] == $licenseId && $schedule['scheduleId'] == $scheduleId) {
                        $selectedSchedule = $schedule;
                        break;
                    }
                }
            }
    ?>
    <div class="page-top">
      <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/ConductorPages/home'">Back</button>
      
      <h1>Seat Layout</h1>  
    </div>
    <div class="layout-container">
        <div class="seat-layout">
            <?php require APPROOT . '/views/pages/Conductor/seatLayout.php'; ?>
        </div>
        <div class="detail-container">
            <div class="bus-info">
                <div class="route-container">
                    <h2><?php 
                            if ($selectedSchedule['direction'] === 'backward') {
                                echo $selectedBus['destination'] . ' - ' . $selectedBus['start_location'];
                            } else {
                                echo $selectedBus['start_location'] . ' - ' . $selectedBus['destination'];
                            }
                        ?>
                    </h2>
                    <p class="date"><?php echo htmlspecialchars($selectedSchedule['date']); ?></p>
                </div>

            <p><strong>Bus Number:</strong> <?php echo htmlspecialchars($selectedBus['License_id']); ?></p>
            <p><strong>Route Number:</strong> <?php echo htmlspecialchars($selectedBus['routeNumber']); ?></p>
            <p><strong>Total Available Seats:</strong> <?php echo htmlspecialchars($selectedSchedule['availableSeats']); ?></p>
            <p><strong>Booked Seats:</strong> <?php echo htmlspecialchars($selectedSchedule['bookedSeats']); ?></p>
            <p><strong>Accepted Seats:</strong> <?php echo htmlspecialchars($selectedSchedule['acceptedSeats']); ?></p>
            <p><strong>Not Yet Accepted Seats:</strong> <?php echo implode(', ', $notAcceptedSeats); ?></p>
            <!--not accepted seats-->

            <div class="info-details">
                <div class="info-item">
                    <div>
                        <h3><?php echo htmlspecialchars($selectedSchedule['departureTime']); ?></h3>                    <p>Departure</p>
                    </div>
                    <span class="icon-time">
                        <i class="fas fa-bus"></i>
                    </span> 
                </div>
                <div class="time-container">
                    <hr class="dotted-line">
                </div>
                <div class="info-item">
                    <span class="icon-time"><i class="fas fa-map-marker-alt"></i></span> <!-- Location icon -->
                    <div>
                        <h3><?php echo htmlspecialchars($selectedSchedule['arrivalTime']); ?></h3>
                        <p>Arrival</p>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</body>
</html>


