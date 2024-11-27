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
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/Updatefleet.css?v=<?php echo time(); ?>">
</head>
<body>

    <!-- Back button -->
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/ConductorPages/viewLeaveRequests'">Back</button>

    <h1>Update Leave Requests</h1>

    <form id="leave-form" method="POST" action="<?php echo URLROOT; ?>/ConductorPages/updateLeave">
        <!-- License ID (Primary Key) - Read-only -->
        <div class="form-group">
            <label for="Employee_id">Employee ID</label>
            <input 
                type="text" 
                id="employee_id" 
                name="employee_id" 
                value="<?php echo isset($data['leaveDetails']['employee_id']) ? htmlspecialchars($data['leaveDetails']['employee_id']) : ''; ?>" 
                readonly
            >
        </div>

        <div class="form-group">
            <div>
            <label for="from_date">From:</label>
            <input 
                type="date" 
                id="from_date" 
                name="from_date" 
                value="<?php echo isset($data['leaveDetails']['from_date']) ? htmlspecialchars($data['leaveDetails']['from_date']) : ''; ?>" 
                required
            >
            </div>
            <div>
            <label for="to_date">To:</label>
            <input 
                type="date" 
                id="to_date" 
                name="to_date" 
                value="<?php echo isset($data['leaveDetails']['to_date']) ? htmlspecialchars($data['leaveDetails']['to_date']) : ''; ?>" 
                required
            >
            </div>
        </div>

        <div class="form-group">
            <label for="noOfDays">Number of Days</label>
            <input 
                type="number" 
                id="noOfDays" 
                name="noOfdays" 
                value="<?php echo isset($data['leaveDetails']['noOfDays']) ? htmlspecialchars($data['leaveDetails']['noOfDays']) : ''; ?>" 
                required
            >
        </div>

        <div class="form-group">
            <label for="reason">Reason</label>
            <input 
                type="text" 
                id="reason" 
                name="reason" 
                value="<?php echo isset($data['leaveDetails']['reason']) ? htmlspecialchars($data['leaveDetails']['reason']) : ''; ?>" 
                readonly
            >
        </div>

        <br>
        <button type="submit" class="submit-btn">Submit</button>
    </form>
</body>
</html>