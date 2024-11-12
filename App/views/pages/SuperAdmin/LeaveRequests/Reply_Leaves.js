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
