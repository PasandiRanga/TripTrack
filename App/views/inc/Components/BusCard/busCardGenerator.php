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
        $currentController = $data['currentController'] ?? '';
        $currentMethod = $data['currentMethod'] ?? '';
        $userRole = $_SESSION['user_role'] ?? '';
    ?>
       
<?php
$selectedDate = $_GET['date'] ?? date('Y-m-d');

$displayedCards = 0;
$totalCards = 0;

foreach ($busData as $bus) {
    foreach ($scheduleData as $schedule) {
        if ($schedule['License_id'] === $bus['License_id'] && $schedule['date'] === $selectedDate) {
            $totalCards++;
        }
    }
}

//Finding the matching schedule dataa and route data for a bus
foreach ($busData as $bus) {
    foreach ($scheduleData as $schedule) {
        foreach ($routeData as $route) {
            if ($route['routeNumber'] === $bus['routeNumber'] ) {
                $busRoute = $route;
                $stops = $busRoute['stops'];
            }
        }
        if ($schedule['License_id'] === $bus['License_id'] && $schedule['date'] === $selectedDate) {

            $busRating = isset($averageRatings[$bus['License_id']]) ? $averageRatings[$bus['License_id']] : 0;
            $ratingIsNumeric = is_numeric($busRating);
            $numericRating = $ratingIsNumeric ? (float)$busRating : 0;
            $roundedRating = round($numericRating * 2) / 2;

            $cardClass = ($displayedCards >= 8) ? 'bus-card hidden-card' : 'bus-card';
            ?>
            <div class="<?php echo $cardClass; ?>" onclick="window.location.href = '<?php 
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
                            if (!empty($stops) && is_string($stops)) {

                                $stopsArray = explode(',', $stops);
                                $totalStops = count($stopsArray);
                                $middleIndex = floor($totalStops / 2); 

                                if ($totalStops > 0) {
                                    echo '<span>' . htmlspecialchars($stopsArray[0]) . '</span>';
                                }

                                if ($totalStops > 1) {
                                    echo '<div class="route-line"></div>'; 
                                    echo '<span>' . htmlspecialchars($stopsArray[$middleIndex]) . '</span>';
                                }

                                if ($totalStops > 2) {
                                    echo '<div class="route-line"></div>'; 
                                    echo '<span>' . htmlspecialchars($stopsArray[$totalStops - 1]) . '</span>';
                                }
                            } else {
                                echo '<span>No stops available</span>';
                            }

                        ?>
                    </div>

                </div>
                <div class="bus-card-footer">
                <div class="rating">
                    <?php
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= floor($roundedRating)) {
                            echo '<i class="fas fa-star" style="color: #FFD700;"></i>';
                        } 
                        elseif ($i - 0.5 <= $roundedRating) {
                            echo '<i class="fas fa-star-half-alt" style="color: #FFD700;"></i>';
                        } 
                        else {
                            echo '<i class="fas fa-star" style="color: #ccc;"></i>';
                        }
                    }
                    ?>
                    <span><?php echo $ratingIsNumeric ? number_format($numericRating, 1) : $busRating; ?></span> 
                </div>

                    <div class="price">
                        <span><?php echo $bus['price']; ?></span>
                    </div>
                </div>
            </div>
            <?php
            $displayedCards++; 
        }
    }
}


if ($displayedCards === 0) {
    echo '<div class="no-buses-message">No buses available for the selected date.</div>';
} elseif ($totalCards > 8) {
    echo '<div class="show-more-container">
        <button id="show-more-btn" class="show-more-btn">Show More</button>
    </div>';
}
?>




<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log("Script loaded");
    const showMoreBtn = document.getElementById('show-more-btn');
    
    if (showMoreBtn) {
        showMoreBtn.addEventListener('click', function() {
            console.log("Show more button clicked");

            const hiddenCards = document.querySelectorAll('.hidden-card');
            console.log("Additional cards found:", hiddenCards.length);
            
            hiddenCards.forEach(card => {
                card.style.display = 'block';
                console.log("Card display set to block");
            });
            
            this.style.display = 'none';
            console.log("Button hidden");
        });
    } else {
        console.log("Show more button not found");
    }
});
</script>