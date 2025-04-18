<?php
        // show the seat layout
        echo '<div class="box right-box">';
        //echo "<script>console.log('Bus layout:', " . json_encode($busLayout) . ");</script>";
        foreach ($busLayout as $row) {
            echo '<div class="button-container">';
            foreach ($row as $seat) {
                if ($seat === '') {
                    echo '<button class="disable"></button>'; // Disabled seat (empty spaces)
                } elseif (in_array(trim($seat), $notAcceptedSeats)) {
                    echo '<button class="number-button booked" disabled>' . htmlspecialchars($seat) . '</button>';
                } elseif (in_array(trim($seat), $acceptedSeats)) {
                    echo '<button class="number-button accepted" disabled>' . htmlspecialchars($seat) . '</button>';
                } else {
                    echo '<button class="number-button">' . htmlspecialchars($seat) . '</button>';
                }
            }
            echo '</div>';
        }
         echo '</div>';
    ?>