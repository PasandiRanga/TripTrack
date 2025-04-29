<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Admin']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reply to Leave Request</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/ReplyLeaves.css?v=<?php echo time(); ?>">
</head>
<body>

    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/leaverequests'">Back</button>

    <h2>Reply to Leave Request</h2>

    <?php
    // Check if 'request_id' is present in the GET request
    $requestId = isset($_GET['request_id']) ? htmlspecialchars($_GET['request_id']) : null;

    if ($requestId) {
    ?>
        <form id="replyForm" onsubmit="return submitReply()" class="reply-form">
            <input type="hidden" name="requestId" id="requestId" value="<?php echo $requestId; ?>">

            <p>Do you want to accept or reject this leave request?</p>
            <button type="button" class="accept-btn" onclick="processReply('Accepted')">Accept</button>
            <button type="button" class="reject-btn" onclick="processReply('Rejected')">Reject</button>
        </form>
    <?php
    } else {
        echo "<p>Error: No request ID provided.</p>";
    }
    ?>

    <script>
        function processReply(status) {
            const requestId = document.getElementById("requestId").value;

            // Confirm with admin
            const confirmReply = confirm(`Are you sure you want to mark this request as ${status}?`);
            if (confirmReply) {
                alert(`Leave request #${requestId} has been ${status}.`);

                // Return to leave requests page and update status
                window.opener.updateLeaveStatus(requestId, status); // Update status in the parent page
                window.location.href = "<?php echo URLROOT; ?>/SuperAdminPages/leaverequests";
            }
        }

    </script>
</body>
</html>
