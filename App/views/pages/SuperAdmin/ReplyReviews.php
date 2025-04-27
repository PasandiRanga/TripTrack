<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Admin']);

    // Get the review ID from the URL parameter
    $rating_id = isset($_GET['rating_id']) ? htmlspecialchars_decode($_GET['rating_id']) : null;
    $User_id = isset($_GET['User_id']) ? htmlspecialchars_decode($_GET['User_id']) : null;
    $License_id = isset($_GET['License_id']) ? htmlspecialchars_decode($_GET['License_id']) : null;
    $review = isset($_GET['Review']) ? preg_replace('/\d+/', '', htmlspecialchars_decode($_GET['Review'])) : null;
    $date = isset($_GET['Date']) ? htmlspecialchars_decode($_GET['Date']) : null;
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
<div class="box">
    <h2>Reply to Review</h2>
    <br>

    <!-- Display Review Information in a 2x2 Grid -->
    <div class="review-info">
        <div class="review-details">
            <div class="row">
                <p><strong>Review ID:</strong> <?php echo htmlspecialchars($rating_id); ?></p>
                <p><strong>User ID:</strong> <?php echo htmlspecialchars($User_id); ?></p>
            </div>
            <div class="row">
                <p><strong>Date and Time:</strong> <?php echo htmlspecialchars($date); ?></p>
                <p><strong>Bus ID:</strong> <?php echo htmlspecialchars($License_id); ?></p>
            </div>
        </div>
    </div>

    <!-- Review Message Box (Styled Like Other Boxes) -->
    <div class="review-box">
        <div class="box-header">
            <strong>Review:</strong>
        </div>
        <div class="box-content">
            <p><?php echo htmlspecialchars($review); ?></p>
        </div>
    </div>

    <!-- Reply Form -->
    <form method="post" action="<?php echo URLROOT; ?>/SuperAdminPages/replyreview" class="reply-form" id="replyForm">
        <input type="hidden" name="rating_id" value="<?php echo htmlspecialchars($rating_id); ?>">
        <label for="reply">Your Reply:</label>
        <textarea id="reply" name="reply" rows="5" required></textarea>
        <br>
        <button type="submit">Submit Reply</button>
    </form>
</div>
    <script>
        // Function to handle reply form submission
        document.getElementById("replyForm").addEventListener("submit", submitReply);

        async function submitReply(event) {
            event.preventDefault();

            const replyText = document.getElementById("reply").value.trim();
            const rating_id = document.querySelector('input[name="rating_id"]').value;

            if (replyText === "") {
                alert("Please enter a reply before submitting.");
                return;
            }

            const confirmSubmit = confirm("Are you sure you want to submit this reply?");
            if (!confirmSubmit) return;

            try {
                const response = await fetch('<?php echo URLROOT; ?>/SuperAdminPages/replyreview', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        rating_id: rating_id,
                        reply: replyText
                    })
                });

                const result = await response.json();

                if (result.status === "success") {
                    alert("Reply submitted successfully!");
                    window.location.href = '<?php echo URLROOT; ?>/SuperAdminPages/reviews';
                } else {
                    alert(result.message || "Failed to submit reply.");
                }
            } catch (error) {
                console.error(error);
                alert("An error occurred while submitting the reply.");
            }
        }
    </script>
</body>
</html>
