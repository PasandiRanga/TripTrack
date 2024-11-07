<?php
// seatData.php

// Array containing bus data, including bus ID, number of seats, and seat layout
$seatData = [
    // Example for a bus with 32 seats
    [
        'busId' => 1,
        'noOfSeats' => 32,
        'layout' => [
            'rows' => 8, // number of rows
            'seatsPerRow' => 4, // number of seats per row
            'seatConfiguration' => [
                ['A', 'B', 'C', 'D'], // Row 1 seats
                ['A', 'B', 'C', 'D'], // Row 2 seats
                ['A', 'B', 'C', 'D'], // Row 3 seats
                ['A', 'B', 'C', 'D'], // Row 4 seats
                ['A', 'B', 'C', 'D'], // Row 5 seats
                ['A', 'B', 'C', 'D'], // Row 6 seats
                ['A', 'B', 'C', 'D'], // Row 7 seats
                ['A', 'B', 'C', 'D'], // Row 8 seats
            ],
        ],
    ],
    // Example for a bus with 56 seats
    [
        'busId' => 2,
        'noOfSeats' => 56,
        'layout' => [
            'rows' => 14, // number of rows
            'seatsPerRow' => 4, // number of seats per row
            'seatConfiguration' => [
                ['A', 'B', 'C', 'D'], // Row 1 seats
                ['A', 'B', 'C', 'D'], // Row 2 seats
                ['A', 'B', 'C', 'D'], // Row 3 seats
                ['A', 'B', 'C', 'D'], // Row 4 seats
                ['A', 'B', 'C', 'D'], // Row 5 seats
                ['A', 'B', 'C', 'D'], // Row 6 seats
                ['A', 'B', 'C', 'D'], // Row 7 seats
                ['A', 'B', 'C', 'D'], // Row 8 seats
                ['A', 'B', 'C', 'D'], // Row 9 seats
                ['A', 'B', 'C', 'D'], // Row 10 seats
                ['A', 'B', 'C', 'D'], // Row 11 seats
                ['A', 'B', 'C', 'D'], // Row 12 seats
                ['A', 'B', 'C', 'D'], // Row 13 seats
                ['A', 'B', 'C', 'D'], // Row 14 seats
            ],
        ],
    ],
    // Additional bus examples can be added here following the same structure
];

// You can return this array or include it in other files to access bus seat data
return $seatData;
?>
