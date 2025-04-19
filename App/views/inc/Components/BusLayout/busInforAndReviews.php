<div class="bus-info">
    <div class="route-container">
        <h2><?php 
                if ($selectedSchedule['direction'] === 'backward') {
                    echo $selectedBus['destination'] . ' - ' . $selectedBus['start_location'];
                } else {
                    echo $selectedBus['start_location'] . ' - ' . $selectedBus['destination'];
                }
            ?>
        </h2>
        <p class="date"><?php echo htmlspecialchars($selectedSchedule['date']); ?></p>
    </div>
    <p><strong>Bus Number:</strong> <?php echo htmlspecialchars($selectedBus['License_id']); ?></p>
    <p><strong>Route Number:</strong> <?php echo htmlspecialchars($selectedBus['routeNumber']); ?></p>
    <p><strong>Available Seats:</strong> <?php echo htmlspecialchars($selectedSchedule['availableSeats']); ?></p>
    <div class="rating">
        <?php
            // Get the current bus license ID
            $busLicenseId = $selectedBus['License_id'];
            
            // Get the rating for this specific bus from the averageRatings array
            $rating = isset($averageRatings[$busLicenseId]) && $averageRatings[$busLicenseId] !== "No ratings" 
                ? (float)$averageRatings[$busLicenseId] 
                : 0.0;
                
            $fullStars = floor($rating);
            $halfStar = $rating - $fullStars >= 0.5; 
            $maxStars = 5; 

            for ($i = 0; $i < $fullStars; $i++) {
                echo '<i class="fas fa-star filled-star"></i>';
            }
            if ($halfStar) {
                echo '<i class="fas fa-star-half-alt filled-star"></i>';
            }
            for ($i = $fullStars + $halfStar; $i < $maxStars; $i++) {
                echo '<i class="far fa-star empty-star"></i>';
            }
        ?>
    <span>
        <?php 
            if (isset($averageRatings[$busLicenseId]) && $averageRatings[$busLicenseId] !== "No ratings") {
                echo number_format($rating, 1);
            } else {
                echo "No ratings";
            }
        ?>
    </span>
</div>
    <div class="info-details">
        <div class="info-item">
            <div>
                <h3><?php echo htmlspecialchars($selectedSchedule['departureTime']); ?></h3>                    <p>Departure</p>
            </div>
            <span class="icon-time">
                <i class="fas fa-bus"></i>
            </span> 
        </div>
        <div class="time-container">
            <hr class="dotted-line">
        </div>
        <div class="info-item">
            <span class="icon-time"><i class="fas fa-map-marker-alt"></i></span> <!-- Location icon -->
            <div>
                <h3><?php echo htmlspecialchars($selectedSchedule['arrivalTime']); ?></h3>
                <p>Arrival</p>
            </div>
        </div>
    </div>
    <div class="price-container">
        <p class="highlight">Rs. <?php echo htmlspecialchars($selectedBus['price']); ?></p>
    </div>
    <div class="view-button">
        <button onclick="openReviewsModal('<?php echo htmlspecialchars($selectedBus['License_id']); ?>')">View reviews</button>
    </div>
</div>
<div id="reviewsModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeReviewsModal()">&times;</span>
        <h2>Bus Reviews</h2>
        <div id="reviewsContainer">
            <!-- Reviews -->
        </div>
    </div>
</div>

<script>
    console.log('Script loading...');

    function openReviewsModal(licenseId) {
        console.log(licenseId);
        const modal = document.getElementById('reviewsModal');
        const reviewsContainer = document.getElementById('reviewsContainer');
        modal.style.display = 'block';

        // Clear previous reviews
        reviewsContainer.innerHTML = '<p>Loading reviews...</p>';

        fetch('<?php echo URLROOT; ?>/RegisteredPages/getReviews?License_id=' + licenseId)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log(data.reviews);
                    const reviewsHtml = data.reviews.map(review => `
                    <div class="review-item">
                        <div class="review-name">${review.Name}</div>
                        <div class="review-text">${review.review}</div>
                    </div>
                    `).join('');
                    reviewsContainer.innerHTML = reviewsHtml || '<p>No reviews available for this bus.</p>';
                } else {
                    reviewsContainer.innerHTML = '<p>Error loading reviews. Please try again later.</p>';
                }
            })
            .catch(error => {
                console.error('Error fetching reviews:', error);
                reviewsContainer.innerHTML = '<p>Error fetching reviews. Please try again later.</p>';
            });
    }

    function closeReviewsModal() {
        const modal = document.getElementById('reviewsModal');
         modal.style.display = 'none';
    }
</script>