<?php
// Define the full seat layout data
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

// Handle the selected seats and number of seats
$numSeats = isset($_GET['numSeats']) ? (int)$_GET['numSeats'] : 0;

echo '<div class="box right-box">';

$selectedSeats = [];

// Generate the seat layout with the ability to select seats
foreach ($seats as $row) {
    echo '<div class="button-container">';
    foreach ($row as $seat) {
        if ($seat === '') {
            echo '<button class="disable"></button>';  // Disabled seat button
        } else {
            // Display each seat with a unique class for selection
            $seatId = "seat-" . $seat;
            echo '<button class="number-button" id="' . $seatId . '" data-seat="' . $seat . '">' . htmlspecialchars($seat) . '</button>';
        }
    }
    echo '</div><br/>';
}

echo '</div>';
?>

<script>
// JavaScript to handle seat selection
let selectedSeats = [];  // Array to hold selected seats

// Attach click event to all seat buttons
document.querySelectorAll('.number-button').forEach(button => {
    button.addEventListener('click', function () {
        const seatNumber = this.getAttribute('data-seat');

        // Toggle seat selection
        if (selectedSeats.includes(seatNumber)) {
            selectedSeats = selectedSeats.filter(seat => seat !== seatNumber);
            this.classList.remove('selected');
        } else if (selectedSeats.length < <?php echo $numSeats; ?>) {
            selectedSeats.push(seatNumber);
            this.classList.add('selected');
        }

        // Update the selected seats input field (you can use it to pass the seats in the booking form)
        document.getElementById('selectedSeats').value = selectedSeats.join(',');
    });
});
</script>
