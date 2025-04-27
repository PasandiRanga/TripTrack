<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Admin']);

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

    <div class="review-section">
        <div class="review-info">
            <div class="info-row">
                <p><strong>Review ID:</strong> <?php echo htmlspecialchars($rating_id); ?></p>
                <p><strong>User ID:</strong> <?php echo htmlspecialchars($User_id); ?></p>
                <p><strong>Date and Time:</strong> <?php echo htmlspecialchars($date); ?></p>
                <p><strong>Bus ID:</strong> <?php echo htmlspecialchars($License_id); ?></p>
            </div>
        </div>

        <div class="review-box">
            <div class="box-header">
                <strong>Review:</strong>
            </div>
            <div class="box-content">
                <p><?php echo htmlspecialchars($review); ?></p>
            </div>
        </div>
    </div>

    <form method="post" action="<?php echo URLROOT; ?>/SuperAdminPages/replyreview" class="reply-form" id="replyForm">
        <input type="hidden" name="rating_id" value="<?php echo htmlspecialchars($rating_id); ?>">
        <label for="reply">Your Reply:</label>
        <textarea id="reply" name="reply" rows="5" required></textarea>
        <br>
        <button type="submit">Submit Reply</button>
    </form>
</div>

<!-- Confirmation Popup -->
<div id="confirmModal" class="popup-modal" style="display:none;">
  <div class="popup-content">
    <p>Are you sure you want to submit this reply?</p>
    <button id="confirmYesBtn">Yes</button>
    <button onclick="closeConfirm()">No</button>
  </div>
</div>

<!-- Success Popup -->
<div id="popupModal" class="popup-modal" style="display:none;">
  <div class="popup-content">
    <p id="popupMessage"></p>
    <button onclick="closePopup()">OK</button>
  </div>
</div>


<script>
let replyTextGlobal = '';
let ratingIdGlobal = '';

// When Submit Button Clicked
document.getElementById("replyForm").addEventListener("submit", function(event) {
    event.preventDefault();
    replyTextGlobal = document.getElementById("reply").value.trim();
    ratingIdGlobal = document.querySelector('input[name="rating_id"]').value;

    if (replyTextGlobal === "") {
        alert("Please enter a reply before submitting.");
        return;
    }
    // Open Confirmation Modal
    document.getElementById('confirmModal').style.display = 'flex';
});

// If user clicks "Yes" in Confirm Modal
document.getElementById('confirmYesBtn').addEventListener('click', function() {
    submitReply();
    closeConfirm();
});

function closeConfirm() {
    document.getElementById('confirmModal').style.display = 'none';
}

async function submitReply() {
    try {
        const response = await fetch('<?php echo URLROOT; ?>/SuperAdminPages/replyreview', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                rating_id: ratingIdGlobal,
                reply: replyTextGlobal
            })
        });

        const result = await response.json();

        if (result.status === "success") {
            showPopup("Reply submitted successfully!");
        } else {
            alert(result.message || "Failed to submit reply.");
        }
    } catch (error) {
        console.error(error);
        alert("An error occurred while submitting the reply.");
    }
}

function showPopup(message) {
    document.getElementById('popupMessage').innerText = message;
    document.getElementById('popupModal').style.display = 'flex';
}

function closePopup() {
    document.getElementById('popupModal').style.display = 'none';
    window.location.href = '<?php echo URLROOT; ?>/SuperAdminPages/reviews';
}
</script>

</body>
</html>
