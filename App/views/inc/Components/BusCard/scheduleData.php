<?php 
$busSchedules = [
    [
        'busId' => '1',
        'busNumber' => 'NA-1234',
        'route' => 'Colombo - Ampara',
        'schedule' => [
            [
                'date' => '2024-11-10',
                'departureTime' => '6:00 AM',
                'arrivalTime' => '3:00 PM',
                'duration' => '9 hours 30 mins',
                'price' => 'Rs. 700',
                'availableSeats' => 4
            ],
            [
                'date' => '2024-11-11',
                'departureTime' => '2:00 PM',
                'arrivalTime' => '11:30 PM',
                'duration' => '9 hours 30 mins',
                'price' => 'Rs. 700',
                'availableSeats' => 8
            ],
            // Additional schedules
        ]
    ],
    [
        'busId' => '2',
        'busNumber' => 'NA-5678',
        'route' => 'Colombo - Kandy',
        'schedule' => [
            [
                'date' => '2024-11-10',
                'departureTime' => '8:00 AM',
                'arrivalTime' => '11:30 AM',
                'duration' => '3 hours 30 mins',
                'price' => 'Rs. 1200',
                'availableSeats' => 10
            ],
            [
                'date' => '2024-11-12',
                'departureTime' => '4:00 PM',
                'arrivalTime' => '7:30 PM',
                'duration' => '3 hours 30 mins',
                'price' => 'Rs. 1200',
                'availableSeats' => 5
            ],
        ]
    ],
    // Add more buses and their schedules
];
?>
