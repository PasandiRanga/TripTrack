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
<div class="box">
<h2 class="title">Reviews</h2>
<div class="cards-container">
    <?php foreach ($data['reviews'] as $review): 
        $cardClass = !$review['replied'] ? 'review-card new-review' : 'review-card replied-review';
    ?>
        <div class="<?php echo $cardClass; ?>" onclick="goToReplyPage(
            <?php echo $review['rating_id']; ?>,
            '<?php echo $review['License_id']; ?>',
            '<?php echo $review['User_id']; ?>',
            '<?php echo htmlspecialchars($review['Review'], ENT_QUOTES); ?>',
            '<?php echo $review['Date']; ?>'
        )">
            <h3>Review #<?php echo $review['rating_id']; ?> - Bus <?php echo $review['License_id']; ?></h3>
            <p><strong>User ID:</strong> <?php echo $review['User_id']; ?></p>
            <p><strong>Review:</strong> <?php echo $review['Review']; ?></p>
            <p><strong>Date:</strong> <?php echo $review['Date']; ?></p>
            <p><strong>Reply:</strong> <?php echo $review['reply']; ?></p>
            <p class="status"><strong>Status:</strong> <?php echo $review['replied'] ? 'Replied' : 'Not Replied'; ?></p>
        </div>
    <?php endforeach; ?>
</div>
</div>

<script>
    function goToReplyPage(rating_id, License_id, User_id, review, date) {
        const url = new URL('<?php echo URLROOT; ?>/SuperAdminPages/replyreviews');
        url.searchParams.append('rating_id', rating_id);
        url.searchParams.append('License_id', License_id);
        url.searchParams.append('User_id', User_id);
        url.searchParams.append('Review', review);
        url.searchParams.append('Date', date);

        window.location.href = url.toString();
    }
</script>

</body>
</html>
