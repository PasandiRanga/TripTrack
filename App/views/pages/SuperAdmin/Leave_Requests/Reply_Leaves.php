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

    <script src="JS/reply_leave_request.js"></script>
</body>
</html>
