<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Admin']);

    // Get the review ID from the URL parameter
    $reviewId = isset($_GET['review_id']) ? htmlspecialchars_decode($_GET['review_id']) : null;
    $User_id = isset($_GET['User_id']) ? htmlspecialchars_decode($_GET['User_id']) : null;
    $License_id = isset($_GET['License_id']) ? htmlspecialchars_decode($_GET['License_id']) : null;
    $review = isset($_GET['review']) ? preg_replace('/\d+/', '', htmlspecialchars_decode($_GET['review'])) : null;
    $created_at = isset($_GET['created_at']) ? htmlspecialchars_decode($_GET['created_at']) : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reply to Review</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/ReplyReviews.css?v=<?php echo time(); ?>">
</head>
<body>

    <!-- Back Button -->
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/reviews'">Back</button>

    <h2>Reply to Review</h2>
    <br>

    <!-- Display Review Information -->
    <div class="review-info">
        <p><strong>Review ID:</strong> <?php echo htmlspecialchars($reviewId); ?></p>
        <p><strong>User ID:</strong> <?php echo htmlspecialchars($User_id); ?></p>
        <p><strong>Date and Time:</strong> <?php echo htmlspecialchars($created_at); ?></p>
        <p><strong>Bus ID:</strong> <?php echo htmlspecialchars($License_id); ?></p>
        <p><strong>Review:</strong> <?php echo htmlspecialchars($review); ?></p>
    </div>

    <!-- Reply Form -->
    <form method="post" action="<?php echo URLROOT; ?>/SuperAdminPages/replyreview" onsubmit="return submitReply()" class="reply-form">
        <input type="hidden" name="reviewId" value="<?php echo htmlspecialchars($reviewId); ?>">
        <label for="reply">Your Reply:</label>
        <textarea id="reply" name="reply" rows="5" required></textarea>
        <br>
        <button type="submit">Submit Reply</button>
    </form>


    <script>
        // Function to handle reply form submission
    async function submitReply(event) {
        event.preventDefault(); // Stop normal form submission

        const replyText = document.getElementById("reply").value.trim();
        const reviewId = document.querySelector('input[name="reviewId"]').value;

        if (replyText === "") {
            alert("Please enter a reply before submitting.");
            return false;
        }

        const confirmSubmit = confirm("Are you sure you want to submit this reply?");
        if (!confirmSubmit) return false;

        try {
            const response = await fetch('<?php echo URLROOT; ?>/SuperAdminPages/replyreview', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    reviewId: reviewId,
                    reply: replyText
                })
            });

            if (!response.ok) {
                throw new Error('Failed to submit reply');
            }

            const result = await response.json();

            if (result.success) {
                alert("Reply submitted successfully!");
                window.location.href = '<?php echo URLROOT; ?>/SuperAdminPages/reviews';
            } else {
                alert("Failed to submit reply. Please try again.");
            }
        } catch (error) {
            console.error(error);
            alert("An error occurred while submitting the reply.");
        }

        return false;
    }

    </script>
</body>
</html>
