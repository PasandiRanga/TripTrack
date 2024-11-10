<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reply to Review</title>
    <link rel="stylesheet" href="Reply.css">
</head>
<body>

    <!-- Back Button -->
    <button class="back-button" onclick="window.location.href='Reviews.php'">Back</button>

    <h2>Reply to Review</h2>

    <?php
    // Example review data (replace with dynamic data from the review selected in Reviews.php)
    $review = [
        'reviewId' => 1,
        'userId' => 1001,
        'review' => 'Great service! Very comfortable seats.',
        'dateTime' => '2024-11-10 08:30:00',
        'busId' => 2001
    ];
    ?>

    <!-- Display Review Information -->
    <div class="review-info">
        <p><strong>Review ID:</strong> <?php echo $review['reviewId']; ?></p>
        <p><strong>User ID:</strong> <?php echo $review['userId']; ?></p>
        <p><strong>Review:</strong> <?php echo $review['review']; ?></p>
        <p><strong>Date and Time:</strong> <?php echo $review['dateTime']; ?></p>
        <p><strong>Bus ID:</strong> <?php echo $review['busId']; ?></p>
    </div>

    <!-- Reply Form -->
<form onsubmit="return submitReply()" class="reply-form">
    <input type="hidden" name="reviewId" value="<?php echo $review['reviewId']; ?>">
    <label for="reply">Your Reply:</label>
    <textarea id="reply" name="reply" rows="5" required></textarea>
    <button type="submit">Submit Reply</button>
</form>


    <script src="Reply.js"></script>
</body>
</html>
