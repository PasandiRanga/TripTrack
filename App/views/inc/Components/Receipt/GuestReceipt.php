<?php

require_once APPROOT . '/libraries/Database.php';

// Retrieve booking data from POST
$busId = $_POST['busId'] ?? 'Unknown Bus';
$scheduleId = $_POST['scheduleId'] ?? 'Unknown Schedule';
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$contact = $_POST['contact'] ?? '';
$nic = $_POST['nic'] ?? '';
$from = $_POST['from'] ?? '';
$to = $_POST['to'] ?? '';
$noOfSeats = $_POST['noOfseats'] ?? '0';
$pricePerSeat = $_POST['pricePerSeat'] ?? '0';
$totalPrice = $_POST['totalPrice'] ?? '0';
$selectedSeats = $_POST['selectedSeats'] ?? [];


// Create the booking details text for QR code
$qrText = "Booking Receipt\n";
$qrText .= "Schedule ID: $scheduleId\n";
$qrText .= "Seats: $selectedSeats\n";
$qrText .= "Total Price: Rs. $totalPrice\n";

// Convert selectedSeats array to JSON format for storage
$selectedSeatsJSON = json_encode($selectedSeats);

// Insert the booking data into the GuestBooking table
try {
    // Instantiate the Database
    $db = new Database();

    // Prepare the insert query
    $db->query("INSERT INTO GuestBooking (name, email, contact, nic, from_location, to_location, number_of_seats,  selected_seats, total_price, schedule_id)
                VALUES (:name, :email, :contact, :nic, :fromLocation, :toLocation, :noOfSeats,  :selectedSeats, :totalPrice , :scheduleId)");

    // Bind parameters
    $db->bind(':name', $name);
    $db->bind(':email', $email);
    $db->bind(':contact', $contact);
    $db->bind(':nic', $nic);
    $db->bind(':fromLocation', $from);
    $db->bind(':toLocation', $to);
    $db->bind(':noOfSeats', $noOfSeats);
    $db->bind(':selectedSeats', $selectedSeatsJSON);
    $db->bind(':totalPrice', $totalPrice);
    $db->bind(':scheduleId', $scheduleId);

    // Execute the query
    $db->execute();

    // Step 2: Retrieve current bookedSeats from the schedule table
        
    $db->query("SELECT bookedSeats FROM schedule WHERE scheduleId = :scheduleId");
    $db->bind(':scheduleId', $scheduleId);
    $currentBookedSeats = $db->single()['bookedSeats'];

    // Convert current bookedSeats from CSV to array if it's not empty
    $currentBookedSeatsArray = $currentBookedSeats ? explode(',', $currentBookedSeats) : [];

    // Ensure $selectedSeats is an array
    $selectedSeatsArray = is_array($selectedSeats) ? $selectedSeats : explode(',', $selectedSeats);

    // Step 3: Merge selected seats with current booked seats
    $updatedBookedSeatsArray = array_merge($currentBookedSeatsArray, $selectedSeatsArray);
    $updatedBookedSeatsArray = array_unique($updatedBookedSeatsArray); // Ensure unique values

    // Convert back to CSV for storage in database
    $updatedBookedSeats = implode(',', $updatedBookedSeatsArray);

    // Step 4: Update the bookedSeats field in the schedule table
    $db->query("UPDATE schedule SET bookedSeats = :updatedBookedSeats WHERE scheduleId = :scheduleId");
    $db->bind(':updatedBookedSeats', $updatedBookedSeats);
    $db->bind(':scheduleId', $scheduleId);

    $db->execute();


    echo "Booking and seat reservation successfully saved.";

} catch (Exception $e) {
    echo "An error occurred while saving the booking: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Receipt</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/Receipt/receipt.css?v=<?php echo time(); ?>">
    <!-- External QR code library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <!-- Add html2pdf library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
</head>
</head>
<body>
    <div id="receipt-content" class="receipt-container">
        <h1>Booking Receipt</h1>
        
        <!-- Booking Details -->
        <div class="details">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($name); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
            <p><strong>Contact:</strong> <?php echo htmlspecialchars($contact); ?></p>
            <p><strong>NIC:</strong> <?php echo htmlspecialchars($nic); ?></p>
            <p><strong>From:</strong> <?php echo htmlspecialchars($from); ?></p>
            <p><strong>To:</strong> <?php echo htmlspecialchars($to); ?></p>
            <p><strong>Bus ID:</strong> <?php echo htmlspecialchars($busId); ?></p>
            <p><strong>Schedule ID:</strong> <?php echo htmlspecialchars($scheduleId); ?></p>
            <p><strong>Number of Seats:</strong> <?php echo htmlspecialchars($noOfSeats); ?></p>
            <p><strong>Seats:</strong> <?php echo htmlspecialchars($selectedSeats); ?></p>
            <p><strong>Price per Seat:</strong> Rs. <?php echo htmlspecialchars($pricePerSeat); ?></p>
            <p><strong>Total Price:</strong> Rs. <?php echo htmlspecialchars($totalPrice); ?></p>
        </div>
        
        <!-- QR Code -->
        <div class="qr-code">
            <h2>Your QR Code</h2>
            <!-- This is where the QR code will appear -->
            <div class="qr-box" id="qrcode"></div>
        </div>
        <br/><br/>
        <!-- Download Button -->
        <button class="download no-print" onclick="downloadPDF()">Download PDF</button>

    </div>

    <script>
        // Generate the QR code using the booking details
        var qrText = <?php echo json_encode($qrText); ?>;
        new QRCode(document.getElementById("qrcode"), {
            text: qrText,
            width: 400,  // Smaller QR code size
            height: 400
        });

        // Function to download the receipt as PDF
        function downloadPDF() {
        // Hide the download button before generating the PDF
            var downloadButton = document.querySelector('.download');
            downloadButton.style.display = 'none';

            var element = document.getElementById('receipt-content');
            html2pdf().from(element).save('Booking_Receipt.pdf');

            // Show the download button again after PDF generation
            downloadButton.style.display = 'inline-block';
        }
    </script>
</body>
</html>
