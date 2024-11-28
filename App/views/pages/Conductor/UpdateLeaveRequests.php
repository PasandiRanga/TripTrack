<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Conductor' , 'Driver']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Leave Requests</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Conductor/UpdateLeave.css?v=<?php echo time(); ?>">
</head>
<body>

    <!-- Back button -->
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/ConductorPages/viewLeaveRequests'">Back</button>

    <h1>Update Leave Requests</h1>

    <div class="leave-form">
        <form id="leaveForm" method="POST" action="<?php echo URLROOT; ?>/ConductorPages/updateLeave">
            <!-- Employee ID (Primary Key) - Read-only -->
            <input 
                type="hidden" 
                id="leave_id" 
                name="leave_id" 
                value="<?php echo htmlspecialchars($data['leaveDetails']['leave_id'] ?? ''); ?>"
            >
            <div class="form-group">
                <label for="employeeId">Employee ID</label>
                <input 
                    type="text" 
                    id="employeeId" 
                    name="employeeId" 
                    value="<?php echo htmlspecialchars($data['leaveDetails']['employee_id'] ?? ''); ?>" 
                    readonly
                >
            </div>

            <!-- Leave Dates -->
            <div class="form-group">
                <label for="from_date">From:</label>
                <input 
                    type="date" 
                    id="from_date" 
                    name="from_date" 
                    value="<?php echo htmlspecialchars($data['leaveDetails']['from_date'] ?? ''); ?>" 
                    required
                >
            </div>
            <div class="form-group">
                <label for="to_date">To:</label>
                <input 
                    type="date" 
                    id="to_date" 
                    name="to_date" 
                    value="<?php echo htmlspecialchars($data['leaveDetails']['to_date'] ?? ''); ?>" 
                    required
                >
            </div>

            <!-- Number of Days -->
            <div class="form-group">
                <label for="noOfDays">Number of Days</label>
                <input 
                    type="number" 
                    id="noOfDays" 
                    name="noOfDays" 
                    value="<?php echo htmlspecialchars($data['leaveDetails']['no_of_days'] ?? ''); ?>" 
                    required
                >
            </div>

            <!-- Reason -->
            <div class="form-group">
                <label for="reason">Reason</label>
                <input 
                    type="text" 
                    id="reason" 
                    name="reason" 
                    value="<?php echo htmlspecialchars($data['leaveDetails']['reason'] ?? ''); ?>" 
                    required
                >
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Update Leave</button>
        </form>
    </div>

    <script>
        const fromDateInput = document.getElementById('from_date');
        const toDateInput = document.getElementById('to_date');

        // Set minimum dates to today or the current values
        const today = new Date().toISOString().split('T')[0];
        fromDateInput.min = today;
        toDateInput.min = today;

        // Calculate and update "Number of Days"
        function calculateDays() {
            const fromDate = new Date(fromDateInput.value);
            const toDate = new Date(toDateInput.value);
            if (fromDate && toDate) {
                const difference = (toDate - fromDate) / (1000 * 60 * 60 * 24) + 1; // Inclusive of both dates
                document.getElementById('noOfDays').value = difference > 0 ? difference : '';
            }
        }

        // Attach event listeners to recalculate days and ensure "To" date is not before "From" date
        fromDateInput.addEventListener('change', () => {
            if (toDateInput.value < fromDateInput.value) {
                toDateInput.value = fromDateInput.value; // Reset "To" date to match "From" date
            }
            toDateInput.min = fromDateInput.value; // Update "To" date's minimum value
            calculateDays();
        });

        toDateInput.addEventListener('change', calculateDays);

        // Recalculate days on page load in case prefilled dates are set
        calculateDays();
    </script>
</body>
</html>