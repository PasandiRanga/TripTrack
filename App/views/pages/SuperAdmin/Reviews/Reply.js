// Function to handle reply form submission
function submitReply() {
    const replyText = document.getElementById("reply").value.trim();

    if (replyText === "") {
        alert("Please enter a reply before submitting.");
        return false; // Prevents form submission
    }

    // Optional: Display a confirmation message before submitting
    const confirmSubmit = confirm("Are you sure you want to submit this reply?");
    if (confirmSubmit) {
        alert("Reply submitted successfully!");
        return true; // Allows form submission
    } else {
        return false; // Prevents form submission if canceled
    }
}
