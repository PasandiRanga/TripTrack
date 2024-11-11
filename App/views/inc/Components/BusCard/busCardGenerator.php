<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Cards</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/busCard/busCard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css?v=<?php echo time(); ?>">

</head>
<body>

    <?php
        // Assuming $data['currentController'] and $data['currentMethod'] are passed to this view
        $currentController = $data['currentController'] ?? '';
        // echo "Current controller is: " . $currentController;
        $currentMethod = $data['currentMethod'] ?? '';
        // echo "Current method is: " . $currentMethod;
        $userRole = $data['userRole'] ?? '';
    ?>

<?php

foreach ($busData as $bus) {
    // Find the matching schedule data for the bus
    foreach ($scheduleData as $schedule) {
        if ($schedule['busId'] === $bus['busId']) {
            ?>
            <div class="bus-card" onclick="window.location.href = '<?php 
                // Check userRole and adjust the URL accordingly
                if ($userRole === 'GuestUser') {
                    echo URLROOT . '/GuestPages/BusBooking?busId=' . urlencode($bus['busId']) . '&scheduleId=' . urlencode($schedule['scheduleId']);
                } elseif ($userRole === 'RegisteredUser') {
                    echo URLROOT . '/RegisteredPages/BusBooking?busId=' . urlencode($bus['busId']) . '&scheduleId=' . urlencode($schedule['scheduleId']);
                } else {
                    // Default case for other roles (if any)
                    echo URLROOT . '/GuestPages/BusBooking?busId=' . urlencode($bus['busId']) . '&scheduleId=' . urlencode($schedule['scheduleId']);
                }
            ?>'">            
            <div class="bus-card-header">
                    <div class="route-info">
                        <h2><?php echo $bus['route']; ?></h2>
                        <span class="bus-type"><?php echo $bus['busType']; ?></span>
                    </div>
                </div>
                <div class="bus-card-timing">
                    <div class="date">
                        <span><?php echo $schedule['date']; ?></span>
                    </div>
                    <div class="timing-info">
                        <div class="departure-time">
                            <span><?php echo $schedule['departureTime']; ?></span>
                        </div>
                        <div class="arrival-time">
                            <span><?php echo $schedule['arrivalTime']; ?></span>
                        </div>
                    </div>
                    <div class="duration">
                        <span><?php echo $schedule['duration']; ?></span>
                    </div>
                    <div class="route-stops">
                        <?php 
                        // Ensure that 'stops' is not empty and is a string before processing
                            if (!empty($bus['stops']) && is_string($bus['stops'])) {
                                // Convert the string of stops into an array
                                $stopsArray = explode(',', $bus['stops']);

                                // Count the number of stops
                                $totalStops = count($stopsArray);
                                $middleIndex = floor($totalStops / 2); // Calculate the middle stop index

                                // Display the stops only if there are any
                                if ($totalStops > 0) {
                                    echo '<span>' . htmlspecialchars($stopsArray[0]) . '</span>';
                                }

                                if ($totalStops > 1) {
                                    echo '<div class="route-line"></div>'; // Optional separator
                                    echo '<span>' . htmlspecialchars($stopsArray[$middleIndex]) . '</span>';
                                }

                                if ($totalStops > 2) {
                                    echo '<div class="route-line"></div>'; // Optional separator
                                    echo '<span>' . htmlspecialchars($stopsArray[$totalStops - 1]) . '</span>';
                                }
                            } else {
                                // Display a default message if there are no stops
                                echo '<span>No stops available</span>';
                            }

                        ?>
                    </div>

                </div>
                <div class="bus-card-footer">
                <div class="rating">
                    <?php
                    $rating = $bus['rating']; // Assume $bus['rating'] is an integer (e.g., 4 for 4 stars)
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $rating) {
                            // Display a yellow star for each rating point
                            echo '<i class="fas fa-star" style="color: #FFD700;"></i>'; // Yellow star
                        } else {
                            // Display a gray star for the remaining
                            echo '<i class="fas fa-star" style="color: #ccc;"></i>'; // Gray star
                        }
                    }
                    ?>
                    <span><?php echo $rating; ?></span> <!-- Display rating value -->
                </div>

                    <div class="price">
                        <span><?php echo $schedule['price']; ?></span>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
?>

</body>
</html>
