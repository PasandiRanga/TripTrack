<?php
// Seat data for different bus layouts
$seatData = [
    // Bus with 56 seats layout (seatType 1)
    [
        'seatType' => '37',
        'seats' => [
            [1, '', '', '', ''],      // Row with a gap in the middle
            [2, 3, '', 4, 5],
            [6, 7, '', 8, 9],
            [10, 11, '', 12, 13],
            [14, 15, '', 16, 17],
            [18, 19, '', 20, 21],
            [22, 23, '', 24, 25],
            [26, 27, '', 28, 29],
            [30, 31, '', 32, 33],
            [34, 35, '', 37, 38],
            [39, 40, '', 41, 42],
            [43, ]    // Full row with 5 seats
        ]
    ],
    // Bus with 36 seats layout (seatType 2)
    [
        'seatType' => '2',
        'seats' => [
            [1, '', '', '', '', ''],      // Row with gaps
            [2, 3, '', 4, 5, 6],
            [7, 8, '', 9, 10, 11],
            [12, 13, '', 14, 15, 16],
            [17, 18, '', 19, 20, 21],
            [22, 23, '', 24, 25, 26],
        ]
    ]
];
?>
