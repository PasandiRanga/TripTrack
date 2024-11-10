// Function to navigate to the previous page
function goBack() {
    window.history.back();
}

// Function to navigate to the reply page
function goToReplyPage(reviewId) {
    window.location.href = "Reviews/Reply.php?review_id=" + reviewId;
}
