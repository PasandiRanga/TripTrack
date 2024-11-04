<!-- busLayout.php -->
<?php
// Define the seat layout data
$seats = [
    [1, 2, 51, 19, 20, 21],
    [3, 4, 51, 22, 23, 24],
    [5, 6, 51, 25, 26, 27],
    [7, 8, 51, 28, 29, 30],
    [9, 10, '', 31, 32, 33],
    [11, 12, '', 34, 35, 36],
    [13, 14, '', 37, 38, 39],
    [15, 16, '', 40, 41, 42],
    [17, 18, '', 43, 44, 45],
    ['', '', '', 46, 47, 48],
    [49, 50, 51, 52, 53, 54]
];

echo '<div class="box right-box">';

foreach ($seats as $row) {
    echo '<div class="button-container">';
    foreach ($row as $seat) {
        if ($seat === '') {
            echo '<button class="disable"></button>';
        } else {
            echo '<button class="number-button">' . htmlspecialchars($seat) . '</button>';
        }
    }
    echo '</div><br/>';
}

echo '</div>';
?>
