<?php 
$busSchedules = [
    [
        'busId' => '1',
        'busNumber' => 'NA-1234',
        'route' => 'Colombo - Ampara',
        'schedule' => [
            [
                'scheduleId' => '1',
                'date' => '2024-11-10',
                'departureTime' => '6:00 AM',
                'arrivalTime' => '3:00 PM',
                'duration' => '9 hours 30 mins',
                'price' => 'Rs. 700',
                'availableSeats' => 4,
                'bookedSeats' => [1, 3] // Booked seats for this schedule
            ],
            [
                'scheduleId' => '2',
                'date' => '2024-11-11',
                'departureTime' => '2:00 PM',
                'arrivalTime' => '11:30 PM',
                'duration' => '9 hours 30 mins',
                'price' => 'Rs. 700',
                'availableSeats' => 8,
                'bookedSeats' => [2, 4, 5, 7] // Booked seats for this schedule
            ],
            // Additional schedules can follow
        ]
    ],
    [
        'busId' => '2',
        'busNumber' => 'NA-5678',
        'route' => 'Colombo - Kandy',
        'schedule' => [
            [
                'scheduleId' => '3',
                'date' => '2024-11-10',
                'departureTime' => '8:00 AM',
                'arrivalTime' => '11:30 AM',
                'duration' => '3 hours 30 mins',
                'price' => 'Rs. 1200',
                'availableSeats' => 10,
                'bookedSeats' => [1, 3, 5] // Booked seats for this schedule
            ],
            [
                'scheduleId' => '4',
                'date' => '2024-11-12',
                'departureTime' => '4:00 PM',
                'arrivalTime' => '7:30 PM',
                'duration' => '3 hours 30 mins',
                'price' => 'Rs. 1200',
                'availableSeats' => 5,
                'bookedSeats' => [2, 4] // Booked seats for this schedule
            ],
        ]
    ],
    // Add more buses and their schedules if needed
];
?>
