<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/Reviews.css?v=<?php echo time(); ?>">
</head>
<body>

<button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/home'">Back</button>

<h2 class="title">Reviews</h2>
<div class="review-table-container"> 
<table class="review-table">
    <tr>
        <th>ReviewID</th>
        <th>UserID</th>
        <th>Review</th>
        <th>Date Time</th>
        <th>BusID</th>
        <th>Replied</th> <!-- New column for Replied status -->
    </tr>

    <?php
    // Array of reviews
    $reviews = [
        ['reviewId' => 1, 'userId' => 1001, 'review' => 'Great service! Very comfortable seats.', 'dateTime' => '2024-11-10 08:30:00', 'busId' => 2001, 'replied' => false],
        ['reviewId' => 2, 'userId' => 1002, 'review' => 'Driver was late by 10 minutes but overall good experience.', 'dateTime' => '2024-11-11 10:00:00', 'busId' => 2002, 'replied' => true],
        ['reviewId' => 3, 'userId' => 1003, 'review' => 'Bus was clean and on time. Highly recommend!', 'dateTime' => '2024-11-12 15:45:00', 'busId' => 2003, 'replied' => false]
    ];

    // Loop through the reviews array and display each review
    foreach ($reviews as $review) {
        $rowClass = !$review['replied'] ? 'new-review' : '';
        echo "<tr onclick=\"goToReplyPage({$review['reviewId']})\" class=\"$rowClass\">";
        echo "<td>{$review['reviewId']}</td>";
        echo "<td>{$review['userId']}</td>";
        echo "<td>{$review['review']}</td>";
        echo "<td>{$review['dateTime']}</td>";
        echo "<td>{$review['busId']}</td>";
        echo "<td>" . ($review['replied'] ? 'Yes' : 'No') . "</td>"; // Display "Yes" or "No" for replied status
        echo "</tr>";
    }
    ?>
</table>
</div>

<script>
    // Function to navigate to the reply page
    function goToReplyPage(reviewId) {
        // Ensure reviewId is passed correctly
        if (reviewId) {
            // Redirect to the correct URL with the review_id parameter
            window.location.href = "<?php echo URLROOT; ?>/SuperAdminPages/replyreviews?review_id=" + reviewId;
        } else {
            alert("Invalid review ID!");
        }
    }
</script>

</body>
</html>
