<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Requests</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/LeaveRequests.css?v=<?php echo time(); ?>">
</head>
<body>
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/home'">Back</button>

    <h2>Leave Requests</h2>

    <table id="leaveRequestsTable">
        <tr>
            <th>Request ID</th>
            <th>Employee ID</th>
            <th>From</th>
            <th>To</th>
            <th>Number of Days</th>
            <th>Reason</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php
        // Array to simulate leave request data with status
        $leaveRequests = [
            ['requestId' => 1, 'employeeId' => 1001, 'from' => '2024-11-01', 'to' => '2024-11-05', 'days' => 5, 'reason' => 'Medical leave', 'status' => 'Pending'],
            ['requestId' => 2, 'employeeId' => 1002, 'from' => '2024-11-03', 'to' => '2024-11-04', 'days' => 2, 'reason' => 'Family event', 'status' => 'Pending'],
            ['requestId' => 3, 'employeeId' => 1003, 'from' => '2024-11-10', 'to' => '2024-11-12', 'days' => 3, 'reason' => 'Personal leave', 'status' => 'Pending'],
        ];

        foreach ($leaveRequests as $request) {
            echo "<tr id='request-{$request['requestId']}'>";
            echo "<td>{$request['requestId']}</td>";
            echo "<td>{$request['employeeId']}</td>";
            echo "<td>{$request['from']}</td>";
            echo "<td>{$request['to']}</td>";
            echo "<td>{$request['days']}</td>";
            echo "<td>{$request['reason']}</td>";
            echo "<td id='status-{$request['requestId']}'>{$request['status']}</td>";
            echo "<td><button onclick=\"goToReplyPage({$request['requestId']})\">Reply</button></td>";
            echo "</tr>";
        }
        ?>
    </table>

    <script>
        // Redirect to reply page
        function goToReplyPage(requestId) {
            // Open reply page with the request ID as a query parameter
            window.location.href = "<?php echo URLROOT; ?>/SuperAdminPages/replyleaves?request_id=" + requestId;
        }

        // Simulate updating the status of a leave request after reply
        function updateLeaveStatus(requestId, status) {
            // Find the status cell in the table and update it
            const statusCell = document.getElementById(`status-${requestId}`);
            if (statusCell) {
                statusCell.textContent = status; // Update status text
                alert(`Leave request #${requestId} has been marked as ${status}.`);
            }
        }
    </script>
</body>
</html>
