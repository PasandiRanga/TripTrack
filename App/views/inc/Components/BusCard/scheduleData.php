<?php
// scheduleData.php

$busSchedules = [
    [
        'scheduleId' => 'SCH001',
        'busId' => 'BUS101',
        'date' => '2024-11-15',
        'departureTime' => '08:00:00',
        'arrivalTime' => '12:00:00',
        'duration' => '4 hours',
        'price' => 1200.00,
        'availableSeats' => 40,
        'bookedSeats' => [1, 5, 10] // Example selected seats
    ],
    [
        'scheduleId' => 'SCH002',
        'busId' => 'BUS102',
        'date' => '2024-11-15',
        'departureTime' => '09:30:00',
        'arrivalTime' => '13:30:00',
        'duration' => '4 hours',
        'price' => 1300.00,
        'availableSeats' => 35,
        'bookedSeats' => [3, 8, 15] // Example selected seats
    ],
    [
        'scheduleId' => 'SCH003',
        'busId' => 'BUS103',
        'date' => '2024-11-16',
        'departureTime' => '14:00:00',
        'arrivalTime' => '18:30:00',
        'duration' => '4 hours 30 mins',
        'price' => 1250.00,
        'availableSeats' => 50,
        'bookedSeats' => [7, 12, 20, 25] // Example selected seats
    ],
    [
        'scheduleId' => 'SCH004',
        'busId' => 'BUS104',
        'date' => '2024-11-16',
        'departureTime' => '06:00:00',
        'arrivalTime' => '10:00:00',
        'duration' => '4 hours',
        'price' => 1400.00,
        'availableSeats' => 20,
        'bookedSeats' => [2, 9, 18] // Example selected seats
    ],
    [
        'scheduleId' => 'SCH005',
        'busId' => 'BUS105',
        'date' => '2024-11-17',
        'departureTime' => '16:00:00',
        'arrivalTime' => '20:00:00',
        'duration' => '4 hours',
        'price' => 1100.00,
        'availableSeats' => 45,
        'bookedSeats' => [4, 6, 22, 30] // Example selected seats
    ]
];
?>
