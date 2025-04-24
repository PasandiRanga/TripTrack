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
            <?php echo $review['reviewId']; ?>,
            '<?php echo $review['License_id']; ?>',
            '<?php echo $review['User_id']; ?>',
            '<?php echo htmlspecialchars($review['review'], ENT_QUOTES); ?>',
            '<?php echo $review['created_at']; ?>'
        )">
            <h3>Review #<?php echo $review['reviewId']; ?> - Bus <?php echo $review['License_id']; ?></h3>
            <p><strong>User ID:</strong> <?php echo $review['User_id']; ?></p>
            <p><strong>Review:</strong> <?php echo $review['review']; ?></p>
            <p><strong>Date:</strong> <?php echo $review['created_at']; ?></p>
            <p><strong>Reply:</strong> <?php echo $review['reply']; ?></p>
            <p class="status"><strong>Status:</strong> <?php echo $review['replied'] ? 'Replied' : 'Not Replied'; ?></p>
        </div>
    <?php endforeach; ?>
</div>
</div>

<script>
    function goToReplyPage(reviewId, License_id, User_id, review, created_at) {
        const url = new URL('<?php echo URLROOT; ?>/SuperAdminPages/replyreviews');
        url.searchParams.append('review_id', reviewId);
        url.searchParams.append('License_id', License_id);
        url.searchParams.append('User_id', User_id);
        url.searchParams.append('review', review);
        url.searchParams.append('created_at', created_at);

        window.location.href = url.toString();
    }
</script>

</body>
</html>
