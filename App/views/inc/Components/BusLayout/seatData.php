<?php
// Seat data for the bus with 56 seats
$busData = [
    // Bus 56 Seat Layout
    1 => [
        'seatType' => 38,
        'seats' => [
            [1, '', '',  '', ''],     // No seat in this row, should be empty spaces for visualization
            [2, 3, '',  4, 5],         // Row with seats 2, 3, 4, 5
            [6, 7, '', 8, 9],        // Row with seats 6, 7, 8, 9, 10, 11
            [10, 11, '', 12, 13],    // Row with seats 12-17
            [14, 15,'', 16 , 17],    // Row with seats 18-23
            [18, 19, '', 20, 21],    // Row with seats 24-29
            [22,23,'',24,25],    // Row with seats 30-35
            [26,27,'',28,29],    // Row with seats 36-41
            [30,31,'',32,33],    // Row with seats 42-47
            [34,35,36,37,38],    // Row with seats 48-53    // Back row with seats 54-56 (empty spaces for visual purpose)
        ]
    ],
    // Bus 36 Seat Layout
    2 => [
        'seatType' => 58,
        'seats' => [
            [1, 2, 3, 4, 5, 6],           // Row with seats 1-6
            [7, 8, 9, 10, 11, 12],        // Row with seats 7-12
            [13, 14, 15, 16, 17, 18],     // Row with seats 13-18
            [19, 20, 21, 22, 23, 24],     // Row with seats 19-24
            [25, 26, 27, 28, 29, 30],     // Row with seats 25-30
            [31, 32, 33, 34, 35, 36],     // Row with seats 31-36
        ]
    ]
];
?>
