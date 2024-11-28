<!DOCTYPE html>
<html lang="en">
<head>
    
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Conductor/RequestLeave.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar/navbar.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=ABeeZee&display=swap" rel="stylesheet">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Leave</title>
</head>

<body>
<script>
    var userRole = <?php echo json_encode($_SESSION['userRole'] ?? 'Conductor'); ?>;
    localStorage.setItem('userRole', userRole);
</script>

    <?php
    // Retrieve user role from session or set to a default value
    $userRole = $_SESSION['userRole'] ?? 'Conductor';
    ?>


    <?php
    $data = [
        'currentController' => 'ConductorPages', // Adjust this based on your controller
        'currentMethod' => 'requestLeave', // Adjust this based on the method
        'userRole' => $userRole
    ];
    ?>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/ConductorPages/home'">Back</button>

    <button class="view_requests-button" onclick="window.location.href='<?php echo URLROOT; ?>/ConductorPages/viewLeaveRequests'">Previous Requests</button>
    
    <div class="page-header">
        <h1>Request Leaves</h1>
    </div>

        <div class="container">
            <div class="leave-form">
                <h2>Fill the following details</h2>

                <form id="leaveForm" method="POST" action="<?php echo URLROOT; ?>/ConductorPages/RequestLeave">

                    <div class="form-group">
                        <div>
                            <label for="employeeId">Employee ID</label>
                            <input 
                                type="text" 
                                id="employeeId" 
                                name="employeeId" 
                                value="<?php echo htmlspecialchars($_SESSION['user_id']); ?>" 
                                readonly
                                required
                            >
                            
                        </div>
                    </div>

                    <div class="form-group">
                        <div>
                            <label for="from_date">From:</label>
                            <input type="date" id="from_date" name="from_date" required>
                        </div>
                        <div>
                            <label for="to_date">To:</label>
                            <input type="date" id="to_date" name="to_date" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <div>
                            <label for="noOfDays">Number of Days</label>
                            <input type="number" id="noOfDays" name="noOfDays" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <div>
                            <label for="reason">Reason</label>
                            <textarea id="reason" name="reason" rows="5" required></textarea>
                        </div>
                    </div>                     

                    <br>
                    <button type="submit" class="submit-btn">Submit</button>
                </form>
            </div>
        </div>

        <script>

            // Set today's date as the minimum date for "From" and "To" fields
            const today = new Date().toISOString().split('T')[0];
            const fromDateInput = document.getElementById('from_date');
            const toDateInput = document.getElementById('to_date');

            fromDateInput.min = today;
            toDateInput.min = today;

            // Automatically set default dates to today's date
            fromDateInput.value = today;
            toDateInput.value = today;

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

            
            function goBack() {
                window.history.back();
            }

            function submitDelayForm(event) {
                event.preventDefault();

                /*const delayData = {

                }
                console.log("Delay Form submitted:");
                alert("Form submitted successfully");*/
                
                clearForm();
            }

            function clearForm() {
                document.getElementById("delay-form").reset();
            }

        </script>
</body>
</html>