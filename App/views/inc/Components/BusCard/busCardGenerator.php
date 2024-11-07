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
require_once 'busData.php'; // Include the bus data
require_once 'scheduleData.php'; // Include the schedule data

foreach ($busDetails as $bus) {
    // Find the matching bus schedule in scheduleData
    $busScheduleData = array_filter($busSchedules, function($schedule) use ($bus) {
        return $schedule['busId'] === $bus['busId'];
    });

    // Check if schedule data exists for the bus
    if (!empty($busScheduleData)) {
        $busScheduleData = array_values($busScheduleData)[0]; // Get the first matched schedule entry
        foreach ($busScheduleData['schedule'] as $schedule) { // Loop through each schedule for the bus
            ?>
            <div class="bus-card" onclick="window.location.href = '<?php echo URLROOT; ?>/GuestPages/BusBooking?busId=<?php echo urlencode($bus['busId']); ?>&scheduleId=<?php echo urlencode($schedule['scheduleId']); ?>'">
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
                        <?php foreach ($bus['stops'] as $index => $stop) { ?>
                            <span><?php echo $stop; ?></span>
                            <?php if ($index < count($bus['stops']) - 1) { ?>
                                <div class="route-line"></div>
                            <?php } ?>
                        <?php } ?>
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
