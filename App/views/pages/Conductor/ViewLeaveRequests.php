<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Conductor' , 'Driver']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Leave Requests</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Conductor/ViewLeaveRequests.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">
</head>
<body>
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/ConductorPages/requestLeave';">Back</button>

    <h1>View Leave Requests</h1>

    <span class="container">
        <h3 id="showReviewedRequests" class="clickable">Reviewed Requests</h3>
        <h3 id="showNotReviewed" class="clickable">Not Reviewed Requests</h3>
    </span>

    <table id="reviewedRequests">
    <thead>
        <tr>
            <th>Request ID</th>
            <th>From Date</th>
            <th>To Date</th>
            <th>Number of Days</th>
            <th>Reason</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($data['leaveRequest'])): ?>
            <?php $hasReviewed = false; ?>
            <?php foreach ($data['leaveRequest'] as $request): ?>
                <?php if ($request['status'] == "Approved" || $request['status'] == "Not approved"): ?>
                    <?php $hasReviewed = true; ?>
                    <tr>
                        <td><?php echo htmlspecialchars($request['leave_id']); ?></td>
                        <td><?php echo htmlspecialchars($request['from_date']); ?></td>
                        <td><?php echo htmlspecialchars($request['to_date']); ?></td>
                        <td><?php echo htmlspecialchars($request['no_of_days']); ?></td>
                        <td><?php echo htmlspecialchars($request['reason']); ?></td>
                        <td><?php echo htmlspecialchars($request['status']); ?></td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php if (!$hasReviewed): ?>
                <tr><td colspan="6">No reviewed leave requests found.</td></tr>
            <?php endif; ?>
            <?php else: ?>
                <tr><td colspan="6">No leave request data available.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <table id="notReviewedRequests">
        <thead>
            <tr>
                <th>Request ID</th>
                <th>From Date</th>
                <th>To Date</th>
                <th>Number of Days</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Update</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($data['leaveRequest'])): ?>
                <?php $hasNotReviewed = false; ?>
                <?php foreach ($data['leaveRequest'] as $request): ?>
                    <?php if ($request['status'] != "Approved" && $request['status'] != "Not approved"): ?>
                        <?php $hasNotReviewed = true; ?>
                        <tr>
                            <td><?php echo htmlspecialchars($request['leave_id']); ?></td>
                            <td><?php echo htmlspecialchars($request['from_date']); ?></td>
                            <td><?php echo htmlspecialchars($request['to_date']); ?></td>
                            <td><?php echo htmlspecialchars($request['no_of_days']); ?></td>
                            <td><?php echo htmlspecialchars($request['reason']); ?></td>
                            <td><?php echo htmlspecialchars($request['status']); ?></td>
                            <td>
                                <button class="update-button" onclick="updateRequest('<?php echo $request['leave_id']; ?>')">Update</button>
                            </td>
                            <td>
                                <button class="delete-button" onclick="deleteRequest('<?php echo $request['leave_id']; ?>')">Delete</button>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php if (!$hasNotReviewed): ?>
                    <tr><td colspan="8">No unreviewed leave requests found.</td></tr>
                <?php endif; ?>
            <?php else: ?>
                <tr><td colspan="8">No leave request data available.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <script>

        const defaultColor = '#9e9ea4';

        // Default display
        document.getElementById('reviewedRequests').style.display = 'none';
        document.getElementById('notReviewedRequests').style.display = 'table';
        document.getElementById('showNotReviewed').style.color = '#4CAF50';

        // Tab click events
        document.getElementById('showReviewedRequests').addEventListener('click', function () {
            document.getElementById('reviewedRequests').style.display = 'table';
            document.getElementById('notReviewedRequests').style.display = 'none';

            document.getElementById('showReviewedRequests').style.color = '#4CAF50';

            document.getElementById('showNotReviewed').style.color = defaultColor;
        });

        document.getElementById('showNotReviewed').addEventListener('click', function () {
            document.getElementById('reviewedRequests').style.display = 'none';
            document.getElementById('notReviewedRequests').style.display = 'table';

            document.getElementById('showNotReviewed').style.color = '#4CAF50';

            document.getElementById('showReviewedRequests').style.color = defaultColor;
        });

        function updateRequest(leave_id) {
            window.location.href = '<?php echo URLROOT; ?>/ConductorPages/updateLeaveRequests?leave_id=' + encodeURIComponent(leave_id);
        }

        function deleteRequest(leave_id) {
            if (confirm("Are you sure you want to delete this request?")) {
                fetch('<?php echo URLROOT; ?>/ConductorPages/deleteRequest', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ leave_id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        
                        const rows = Array.from(document.querySelectorAll("table.notReviewedRequests tbody tr"));
                        const row = rows.find(row => row.cells[0].innerText === leave_id);
                        if (row) {
                            row.remove(); 
                            window.location.reload();
                        }
                        window.location.reload();
                        alert(data.message);
                        
                    } else {
                        alert(data.message);
                    }
                })
                .catch(() => alert('Error deleting the request from the table.'));
            }
        }

    </script>
    
</body>
</html>
