<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['RegisteredUser'])
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar/navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/buttons/button.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/searchBar/searchBar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/RotateText/rotateText.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/BusCard/busCard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/GuestUser/home.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/Footer/footer.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/DateBar/dateBar.css?v=<?php echo time(); ?>">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>

    <script>
        var userRole = <?php echo json_encode($_SESSION['userRole'] ?? 'RegisteredUser'); ?>;
        localStorage.setItem('userRole', userRole);
    </script>

    <?php
    $userId = $_SESSION['user_id'] ?? '';
    // Retrieve user role from session or set to a default value
    $userRole = $_SESSION['userRole'] ?? 'RegisteredUser';
    $scheduleData = $data['schedule'] ?? [];
    $busData = $data['bus'] ?? [];
    $distanceData = $data['distance'] ?? [];
    $routeData = $data['route'] ?? [];
    $notifications = $data['notifications'] ?? [];
    $averageRatings = $data['averageRatings'] ?? [];
    echo '<script>console.log("Notifications in page:", ' . json_encode($notifications) . ');</script>';

    ?>

    <script>
        var scheduleData = <?php echo json_encode($scheduleData); ?>;
        var busData = <?php echo json_encode($busData); ?>;
        var userId = <?php echo json_encode($userId); ?>;
        var userRole = <?php echo json_encode($userRole); ?>;
        var routeData = <?php echo json_encode($routeData); ?>;
        var averageRatings = <?php echo json_encode($averageRatings); ?>;
        console.log("Schedule Data: ", scheduleData);
        console.log("Bus Data: ", busData);
        console.log("User ID: ", userId);
        console.log("User Role: ", userRole);  
        console.log("Route Data: ", routeData);
        console.log("Average ratings: ",averageRatings);
    </script>

<?php
   
    // $headerData = [
    //     'showPopup' => $data['show_pop'] ?? false,
    //     'email' => $data['email'] ?? '',
    //     'password_err' => $data['password_err'] ?? '',
    //     'email_err' => $data['email_err'] ?? ''
    // ];

    $data['currentController'] = 'RegisteredPages';
    $data['currentMethod'] = 'home';
    $data['userRole'] = $userRole;
    // echo '<pre>' . print_r($headerData,true) . '</pre>';

    
    ?>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    

    <div class="hero-container">
        <?php require APPROOT.'/views/inc/Components/imageSlide/imageSlide.php'; ?>
    </div>

    <div class="header-container">
        <?php require APPROOT.'/views/inc/Components/Header/header.php'; ?>
    </div>

    
    <div class="body-section">
        <br>
        <div class="searchbar-container">
            <?php require APPROOT.'/views/inc/Components/SearchBar/searchBar.php'; ?>
        </div>

        <div class="date-bar-container">
    <div class="date-scroll">
        <?php
        // Get current date and create dates for next 7 days
        $dates = [];
        for ($i = 0; $i < 20; $i++) {
            $date = date('Y-m-d', strtotime("+$i days"));
            $formattedDate = date('M d', strtotime($date));
            $dayName = date('D', strtotime($date));
            $isToday = $i === 0;
            
            echo "<div class='date-item" . ($isToday ? " active" : "") . "' data-date='$date'>
                    <span class='day-name'>$dayName</span>
                    <span class='date-number'>$formattedDate</span>
                  </div>";
        }
        ?>
    </div>
</div>
        
        <div id="bus-card-container" class="bus-card-container">
            <?php 
                // Pass $data['schedule'] to busCardGenerator.php
                require APPROOT . '/views/inc/Components/BusCard/busCardGenerator.php';
            ?>
        </div>    
    </div>
<?php require APPROOT.'/views/inc/Components/Footer/footer.php'; ?>

<script>
// Replace the two separate script blocks with this single, optimized version
document.addEventListener('DOMContentLoaded', function() {
    var averageRatings = <?php echo json_encode($averageRatings); ?>;
    const dateItems = document.querySelectorAll('.date-item');
    const travelDateInput = document.getElementById('travelDate');
    
    // Apply ratings initially when page loads
    applyRatingsToCards();
    
    dateItems.forEach(item => {
        item.addEventListener('click', function() {
            // Prevent multiple rapid clicks
            if (this.classList.contains('processing')) {
                return;
            }
            
            // Add processing class to prevent multiple clicks
            this.classList.add('processing');
            
            // Remove active class from all items
            dateItems.forEach(di => di.classList.remove('active'));
            
            // Add active class to clicked item
            this.classList.add('active');
            
            // Get the selected date
            const selectedDate = this.dataset.date;
            
            // Update the search bar date input
            if (travelDateInput) {
                travelDateInput.value = selectedDate;
            }
            
            // Show loading indicator
            const busCardContainer = document.getElementById('bus-card-container');
            busCardContainer.innerHTML = '<div class="loading">Loading...</div>';
            
            fetch(`${URLROOT}/RegisteredPages/filterBusByDate?date=${selectedDate}`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('bus-card-container').innerHTML = html;
                    
                    // After loading the new HTML, reapply ratings
                    setTimeout(applyRatingsToCards, 100);
                    
                    // Remove processing class
                    this.classList.remove('processing');
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.classList.remove('processing');
                });
        });
    });
    
    // Function to apply ratings to bus cards
    function applyRatingsToCards() {
        const busCards = document.querySelectorAll('.bus-card');
        console.log("Applying ratings to", busCards.length, "cards");
        
        busCards.forEach(card => {
            // Extract the license ID from the card's onclick attribute
            const onclickAttr = card.getAttribute('onclick');
            if (!onclickAttr) return;
            
            const licenseIdMatch = onclickAttr.match(/Licenseid=([^&]+)/);
            
            if (licenseIdMatch && licenseIdMatch[1]) {
                const licenseId = decodeURIComponent(licenseIdMatch[1]);
                const rating = averageRatings[licenseId];
                
                if (rating) {
                    const ratingDiv = card.querySelector('.rating');
                    if (ratingDiv) {
                        const numericRating = isNaN(parseFloat(rating)) ? 0 : parseFloat(rating);
                        const roundedRating = Math.round(numericRating * 2) / 2;
                        
                        // Generate stars HTML
                        let starsHTML = '';
                        for (let i = 1; i <= 5; i++) {
                            if (i <= Math.floor(roundedRating)) {
                                starsHTML += '<i class="fas fa-star" style="color: #FFD700;"></i>';
                            } else if (i - 0.5 <= roundedRating) {
                                starsHTML += '<i class="fas fa-star-half-alt" style="color: #FFD700;"></i>';
                            } else {
                                starsHTML += '<i class="fas fa-star" style="color: #ccc;"></i>';
                            }
                        }
                        
                        starsHTML += `<span>${isNaN(parseFloat(rating)) ? rating : parseFloat(rating).toFixed(1)}</span>`;
                        ratingDiv.innerHTML = starsHTML;
                        console.log("Applied rating", rating, "to bus", licenseId);
                    }
                }
            }
        });
    }
    
    // Handle show more button
    const showMoreBtn = document.getElementById('show-more-btn');
    if (showMoreBtn) {
        showMoreBtn.addEventListener('click', function() {
            // Show all additional cards
            const hiddenCards = document.querySelectorAll('.hidden-card');
            
            hiddenCards.forEach(card => {
                card.style.display = 'block';
            });
            
            // Hide the "Show More" button
            this.style.display = 'none';
        });
    }
});

</script>

</body>
</html>
