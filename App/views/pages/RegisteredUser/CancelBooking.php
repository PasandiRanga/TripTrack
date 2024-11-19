<?php
require_once '../../../Database.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bookingId = $_POST['booking_id'];
    echo($bookingId);
    $scheduleId = $_POST['schedule_id'];
    $seatsToCancel = $_POST['seats']; // E.g., "1,2,3"

    try {
        $db = new Database();

        // Begin transaction
        $db->beginTransaction();

        // Step 1: Delete the booking record from `registeredbooking`
        $db->query("DELETE FROM registeredbooking WHERE id = :id");
        $db->bind(':id', $bookingId);
        $db->execute();

        // Step 2: Update the `schedule` table
        // Fetch the current booked seats for the schedule
        $db->query("SELECT bookedSeat FROM schedule WHERE scheduleId = :scheduleId");
        $db->bind(':scheduleId', $scheduleId);
        $currentBookedSeats = $db->single()['bookedSeat'];

        // Remove the cancelled seats from bookedSeat
        $currentBookedSeatsArray = explode(',', $currentBookedSeats);
        $seatsToCancelArray = explode(',', $seatsToCancel);
        $updatedBookedSeatsArray = array_diff($currentBookedSeatsArray, $seatsToCancelArray);
        $updatedBookedSeats = implode(',', $updatedBookedSeatsArray);

        // Update the `schedule` table
        $db->query("UPDATE schedule SET bookedSeat = :bookedSeat, availableSeats = availableSeats + :seatCount WHERE scheduleId = :scheduleId");
        $db->bind(':bookedSeat', $updatedBookedSeats);
        $db->bind(':seatCount', count($seatsToCancelArray));
        $db->bind(':scheduleId', $scheduleId);
        $db->execute();

        // Commit transaction
        $db->endTransaction();

        // Redirect back to bookings page with success message
        header("Location: " . URLROOT . "/RegisteredPages/bookings?status=success");
        exit();
    } catch (Exception $e) {
        // Rollback transaction on error
        $db->rollBack();
        // Redirect back with error message
        header("Location: " . URLROOT . "/RegisteredPages/bookings?status=error&message=" . $e->getMessage());
        exit();
    }
}
?>
