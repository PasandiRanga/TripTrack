<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Cards</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/busCard/busCard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">


</head>
<body>

    <?php 
        // Assuming $data['currentController'] and $data['currentMethod'] are passed to this view
        $currentController = $data['currentController'] ?? '';
        // echo "Current controller is: " . $currentController;
        $currentMethod = $data['currentMethod'] ?? '';
        // echo "Current method is: " . $currentMethod;
        $userRole = $_SESSION['user_role'] ?? '';
        ?>
       
<?php
// Get the selected date from the query parameter, default to current date if none selected
$selectedDate = $_GET['date'] ?? date('Y-m-d');

// Initialize the counter before the loop
$displayedCards = 0;

// Instead, iterate through all buses
foreach ($busData as $bus) {
    // Find the matching schedule data for the bus
    foreach ($scheduleData as $schedule) {
        //find the matching route data for the bus
        foreach ($routeData as $route) {
            if ($route['routeNumber'] === $bus['routeNumber'] ) {
                $busRoute = $route;
                $stops = $busRoute['stops'];
            }
        }
        // Check if the schedule matches the selected date
        if ($schedule['License_id'] === $bus['License_id'] && $schedule['date'] === $selectedDate) {
            // Get the specific rating for this bus
            $busRating = isset($averageRatings[$bus['License_id']]) ? $averageRatings[$bus['License_id']] : 0;
            // Check if the rating is numeric
            $ratingIsNumeric = is_numeric($busRating);
            // If rating is not numeric, set a default value for star display
            $numericRating = $ratingIsNumeric ? (float)$busRating : 0;
            
            // Round to nearest 0.5 for star display
            $roundedRating = round($numericRating * 2) / 2;
            ?>
            <div class="bus-card" onclick="window.location.href = '<?php 
                if ($userRole === 'GuestUser') {
                    echo URLROOT . '/GuestPages/busLayout?Licenseid=' . urlencode($bus['License_id']) . '&scheduleId=' . urlencode($schedule['scheduleId']);
                } elseif ($userRole === 'RegisteredUser') {
                    echo URLROOT . '/RegisteredPages/busLayout?Licenseid=' . urlencode($bus['License_id']) . '&scheduleId=' . urlencode($schedule['scheduleId']);
                } else {
                    echo URLROOT . '/GuestPages/busLayout?Licenseid=' . urlencode($bus['License_id']) . '&scheduleId=' . urlencode($schedule['scheduleId']);
                }
            ?>'">
                <div class="bus-card-header">
                    <div class="route-info">
                        <h2>
                            <?php 
                            if ($schedule['direction'] === 'backward') {
                                echo $bus['destination'] . ' - ' . $bus['start_location'];
                            } else {
                                echo $bus['start_location'] . ' - ' . $bus['destination'];
                            }
                            ?>
                        </h2>
                        <span class="bus-type">Route :<?php echo $bus['routeNumber']; ?></span>
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
                    <?php
                        $duration = $schedule['duration'];
                        list($hours, $minutes, $seconds) = explode(':', $duration);

                        // Prepare the human-readable duration
                        $humanReadableDuration = '';
                        if ($hours > 0) {
                            $humanReadableDuration .= $hours . ' hour' . ($hours > 1 ? 's' : '');
                        }
                        if ($minutes > 0) {
                            $humanReadableDuration .= ($hours > 0 ? ' ' : '') . $minutes . ' minute' . ($minutes > 1 ? 's' : '');
                        }
                        ?>
                        <span><?php echo $humanReadableDuration; ?></span>

                    </div>
                    <div class="route-stops">
                        <?php 
                        // Ensure that 'stops' is not empty and is a string before processing
                            if (!empty($stops) && is_string($stops)) {
                                // Convert the string of stops into an array
                                $stopsArray = explode(',', $stops);

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
                    // Modified stars display code to handle decimal ratings correctly
                    for ($i = 1; $i <= 5; $i++) {
                        // Full star
                        if ($i <= floor($roundedRating)) {
                            echo '<i class="fas fa-star" style="color: #FFD700;"></i>';
                        } 
                        // Half star
                        elseif ($i - 0.5 <= $roundedRating) {
                            echo '<i class="fas fa-star-half-alt" style="color: #FFD700;"></i>';
                        } 
                        // Empty star
                        else {
                            echo '<i class="fas fa-star" style="color: #ccc;"></i>';
                        }
                    }
                    ?>
                    <span><?php echo $ratingIsNumeric ? number_format($numericRating, 1) : $busRating; ?></span> <!-- Display rating value -->
                </div>

                    <div class="price">
                        <span><?php echo $bus['price']; ?></span>
                    </div>
                </div>
            </div>
            <?php
            $displayedCards++; // Increment counter when a bus card is displayed
        }
    }
}

// If no buses found for the selected date
if ($displayedCards === 0) {
    echo '<div class="no-buses-message">No buses available for the selected date.</div>';
}
?>