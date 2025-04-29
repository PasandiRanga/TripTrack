<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Conductor' , 'Driver']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Conductor/InformDelays.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar/navbar.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=ABeeZee&display=swap" rel="stylesheet">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inform Delays</title>
</head>

<body>
    <script>
        var userRole = <?php echo json_encode($_SESSION['userRole'] ?? 'Conductor'); ?>;
        localStorage.setItem('userRole', userRole);
    </script>

    <?php
    $userRole = $_SESSION['userRole'] ?? 'Conductor';
    ?>

    <?php
    echo '<script>console.log("Data", ' . json_encode($data) . ');</script>';

    $scheduleData = $data['schedules'] ?? [];
    $busData = $data['buses'] ?? [];

    echo '<script>console.log("Schedules", ' . json_encode($scheduleData) . ');</script>';
    echo '<script>console.log("Buses", ' . json_encode($busData) . ');</script>';

    $data['currentController'] = 'RegisteredPages';
    $data['currentMethod'] = 'allNotifications';
    $data['userRole'] = $userRole;
    ?>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/ConductorPages/newhome'">Back</button>

    <button class="view_delays-button" onclick="window.location.href='<?php echo URLROOT; ?>/ConductorPages/viewDelays'">Previous Delays</button>

    <h1>Inform Delays</h1>

    <div class="container">
        <div class="delay-form">
            <h2>Fill the following details</h2>

            <form id="delayForm" method="POST" action="<?php echo URLROOT; ?>/ConductorPages/addInformDelays">
                
                <div class="form-group">
                    <div>
                        <label for="scheduleID">Schedule ID</label>
                        <select class="schedule" id="scheduleID" name="scheduleID" required onchange="fetchDepartureTime()">
                            <option value="">Select Schedule</option>
                            <?php if(isset($scheduleData)): ?>
                                <?php foreach($scheduleData as $scheduleItem): ?>
                                    <?php 
                                        $schedule = $scheduleItem[0]; 
                                    ?>
                                    <?php foreach($busData as $bus): ?>
                                        <?php if($schedule['License_id'] == $bus['License_id']): ?>
                                            <option value="<?php echo $schedule['scheduleId']; ?>" 
                                                data-departure-time="<?php echo $schedule['departureTime']; ?>">
                                                <?php echo $schedule['scheduleId']; ?> - <?php echo $bus['start_location']; ?> to <?php echo $bus['destination']; ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div>
                        <label for="time">Departure Time</label>
                        <input type="time" id="time" name="time" readonly required>
                    </div>
                    <div>
                        <label for="newTime">New Departure Time</label>
                        <input type="time" id="newTime" name="newTime" required>
                    </div>
                </div>

                <div class="form-group">
                    <div>
                        <label for="reason">Reason</label>
                        <textarea id="reason" name="reason" rows="5" required></textarea>
                    </div>
                </div>

                <br>
                <button type="submit" class="submit-btn">Submit</button>

            </form>
        </div>
    </div>

    <script>
        document.getElementById('delayForm').addEventListener('submit', function(event) {
            const newDepartureTime = document.getElementById('newTime').value;
            const departureTime = document.getElementById('time').value;

            if (newDepartureTime <= departureTime) {
                alert('New departure time must be later than the original departure time.');
                event.preventDefault();
            }
        });

        function fetchDepartureTime() {
            const scheduleSelect = document.getElementById('scheduleID');
            const selectedOption = scheduleSelect.options[scheduleSelect.selectedIndex];
            
            if (selectedOption.value) {
                document.getElementById('time').value = selectedOption.getAttribute('data-departure-time');
            } else {
                document.getElementById('time').value = '';
            }
        }
    </script>
</body>
</html>