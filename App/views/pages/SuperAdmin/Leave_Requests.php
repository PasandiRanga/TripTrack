<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Requests</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/Leave_Requests.css?v=<?php echo time(); ?>">
</head>
<body>
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/home'">Back </button>

    <h2>Leave Requests</h2>

    <table>
        <tr>
            <th>Request ID</th>
            <th>Employee ID</th>
            <th>From</th>
            <th>To</th>
            <th>Number of Days</th>
            <th>Reason</th>
            <th>Action</th>
        </tr>

        <?php
        // Array to simulate leave request data
        $leaveRequests = [
            ['requestId' => 1, 'employeeId' => 1001, 'from' => '2024-11-01', 'to' => '2024-11-05', 'days' => 5, 'reason' => 'Medical leave'],
            ['requestId' => 2, 'employeeId' => 1002, 'from' => '2024-11-03', 'to' => '2024-11-04', 'days' => 2, 'reason' => 'Family event'],
            ['requestId' => 3, 'employeeId' => 1003, 'from' => '2024-11-10', 'to' => '2024-11-12', 'days' => 3, 'reason' => 'Personal leave'],
        ];

        foreach ($leaveRequests as $request) {
            echo "<tr onclick=\"goToReplyPage({$request['requestId']})\">";
            echo "<td>{$request['requestId']}</td>";
            echo "<td>{$request['employeeId']}</td>";
            echo "<td>{$request['from']}</td>";
            echo "<td>{$request['to']}</td>";
            echo "<td>{$request['days']}</td>";
            echo "<td>{$request['reason']}</td>";
            echo "<td><button onclick=\"goToReplyPage({$request['requestId']})\">Reply</button></td>";
            echo "</tr>";
        }
        ?>
    </table>

    <script>
        // Function to navigate to the reply page
        function goToReplyPage(requestId) {
            // Redirect to reply page with the request ID
            window.location.href = "reply_leave_request.php?requestId=" + requestId;
        }

    </script>
</body>
</html>
