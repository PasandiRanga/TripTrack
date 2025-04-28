<?php
        echo '<div class="box right-box">';
        foreach ($busLayout as $row) {
            echo '<div class="button-container">';
            foreach ($row as $seat) {
                if ($seat === '') {
                    echo '<button class="disable"></button>';
                } elseif (in_array(trim($seat), $bookedSeats)) {
                    echo '<button class="number-button booked" disabled>' . htmlspecialchars($seat) . '</button>';
                } else {
                    echo '<button class="number-button">' . htmlspecialchars($seat) . '</button>';
                }
            }
            echo '</div>';
        }
         echo '</div>';
    ?>