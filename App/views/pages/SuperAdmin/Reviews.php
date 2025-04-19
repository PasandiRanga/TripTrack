<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Admin']);
?>
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
<br>
<div class="review-table-container"> 
<table class="review-table">
    <tr>
        <th>ReviewID</th>
        <th>BusID</th>
        <th>UserID</th>
        <th>Review</th>
        <th>Date Time</th>
        <th>Reply</th>
        <th>Status</th>        
    </tr>

    <?php
    // Array of reviews
    // $reviews = [
    //     ['reviewId' => 1, 'userId' => 1001, 'review' => 'Great service! Very comfortable seats.', 'dateTime' => '2024-11-10 08:30:00', 'busId' => 2001, 'replied' => false],
    //     ['reviewId' => 2, 'userId' => 1002, 'review' => 'Driver was late by 10 minutes but overall good experience.', 'dateTime' => '2024-11-11 10:00:00', 'busId' => 2002, 'replied' => true],
    //     ['reviewId' => 3, 'userId' => 1003, 'review' => 'Bus was clean and on time. Highly recommend!', 'dateTime' => '2024-11-12 15:45:00', 'busId' => 2003, 'replied' => false]
    // ];

    // Loop through the reviews array and display each review
    foreach ($data['reviews'] as $review) {
        // Apply 'new-review' class if review is unreplied, otherwise apply 'replied-review'
        $rowClass = !$review['replied'] ? 'new-review' : 'replied-review';
        echo "<tr onclick=\"goToReplyPage({$review['reviewId']})\" class=\"$rowClass\">";
        echo "<td>{$review['reviewId']}</td>";
        echo "<td>{$review['License_id']}</td>";
        echo "<td>{$review['User_id']}</td>";
        echo "<td>{$review['review']}</td>";
        echo "<td>{$review['created_at']}</td>";
        echo "<td>{$review['reply']}</td>";
        echo "<td>" . ($review['replied'] ? 'Yes' : 'No') . "</td>"; // Display "Yes" or "No" for replied status
        echo "</tr>";
    }
    ?>
</table>
</div>

<script>
    // Function to navigate to the reply page with all row information
    function goToReplyPage(reviewId) {
        // Find the row with the matching review ID
        const rows = Array.from(document.querySelectorAll("table.review-table tbody tr"));
        const row = rows.find(row => row.cells[0].innerText == reviewId);

        if (row) {
            // Extract data from the row
            const License_id = row.cells[1].innerText;
            const User_id = row.cells[2].innerText;
            const review = row.cells[3].innerText;
            const created_at = row.cells[4].innerText;

            // Redirect to the replyreviews page with pre-filled data
            const url = new URL('<?php echo URLROOT; ?>/SuperAdminPages/replyreviews');
            url.searchParams.append('review_id', reviewId);
            url.searchParams.append('License_id', License_id);
            url.searchParams.append('User_id', User_id);
            url.searchParams.append('review', review);
            url.searchParams.append('created_at', created_at);

            window.location.href = url.toString();
        } else {
            alert("Review not found.");
        }
    }

    function updateReviewRowStyles() {
        const rows = document.querySelectorAll("table.review-table tbody tr");

        rows.forEach(row => {
            const repliedStatus = row.cells[6].innerText; // Assuming the 'Status' column is at index 6
            if (repliedStatus === 'No') {
                row.classList.add('new-review');
                row.classList.remove('replied-review');
            } else {
                row.classList.add('replied-review');
                row.classList.remove('new-review');
            }
        });
    }
</script>

</body>
</html>
