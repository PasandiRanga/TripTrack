<?php
require 'busData.php'; // Include the bus data

foreach ($busDetails as $bus) {
    ?>
<div class="bus-card" onclick="window.location.href = '<?php echo URLROOT; ?>/RegisteredUser/Bus_layout.php?busId=<?php echo urlencode($bus['route']); ?>'">
    <div class="bus-card-header">
            <div class="route-info">
                <h2><?php echo $bus['route']; ?></h2>
                <span class="bus-type"><?php echo $bus['busType']; ?></span>
            </div>
            <?php if ($bus['cheapest']) { ?>
                <div class="cheapest-badge">CHEAPEST</div>
            <?php } else { ?>
                <div class="expensive-badge">EXPENSIVE</div>
            <?php } ?>
        </div>
        <div class="bus-card-timing">
            <div class="timing-info">
                <div class="departure-time">
                    <span><?php echo $bus['departure']; ?></span>
                </div>
                <div class="arrival-time">
                    <span><?php echo $bus['arrival']; ?></span>
                </div>
            </div>
            <div class="duration">
                <span><?php echo $bus['duration']; ?></span>
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
                <i class="fas fa-star"></i>
                <span><?php echo $bus['rating']; ?></span>
            </div>
            
            <div class="price">
                <span><?php echo $bus['price']; ?></span>
            </div>
        </div>
    </div>
    <?php
}
?>
