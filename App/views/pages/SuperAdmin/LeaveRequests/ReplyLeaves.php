<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reply to Leave Request</title>
    <link rel="stylesheet" href="CSS/reply_leave_request.css">
</head>
<body>

    <div class="back-btn">
        <a href="leave_request.php">← Back to Leave Requests</a>
    </div>

    <h2>Reply to Leave Request</h2>

    <form id="replyForm" onsubmit="return submitReply()" class="reply-form">
        <input type="hidden" name="requestId" id="requestId" value="<?php echo $_GET['requestId']; ?>">

        <p>Do you want to accept or reject this leave request?</p>
        <button type="button" onclick="processReply('Accepted')">Accept</button>
        <button type="button" onclick="processReply('Rejected')">Reject</button>
    </form>

    <script>
                // Function to process the reply to a leave request
        function processReply(status) {
            const requestId = document.getElementById("requestId").value;

            // Confirm with the admin
            const confirmReply = confirm(`Are you sure you want to mark this request as ${status}?`);
            if (confirmReply) {
                alert(`Leave request #${requestId} has been ${status}.`);
                window.location.href = "leave_request.php"; // Redirect back to leave requests page
            }
        }

    </script>
</body>
</html>
