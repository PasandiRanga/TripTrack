<!DOCTYPE html>
<html lang="en">
<head>
    
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Conductor/ViewLeaveRequests.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Leave Requests</title>
</head>

<body>
    <script>
        var userRole = <?php echo json_encode($_SESSION['userRole'] ?? 'Conductor'); ?>;
        localStorage.setItem('userRole', userRole);
    </script>

    <?php
    // Retrieve user role from session or set to a default value
    $userRole = $_SESSION['userRole'] ?? 'Conductor';
    ?>

    <?php
    $data = [
        'currentController' => 'ConductorPages', // Adjust this based on your controller
        'currentMethod' => 'home', // Adjust this based on the method
        'userRole' => $userRole
    ];
    ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/ConductorPages/requestLeave'">Back</button>

    <h1>View Leave Requests</h1>

    <main class="main-container">

        <div class="container">
            <h3 class="clickable" id="showReviewedRequests">Reviewed Requests</h3>
            <h3 class="clickable" id="showNotReviewed">Not Reviewed Requests</h3>
        </div>
        
        

        <table id="reviewedRequests">
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>From-Date</th>
                    <th>To-Date</th>
                    <th>Number of Days</th>
                    <th>Reason</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                <?php
                if (isset($data['leaveRequest']) && is_array($data['leaveRequest'])) {
                    foreach ($data['leaveRequest'] as $leaveRequest) {
                        if ($leaveRequest['status'] != "Yet to review"){
                            echo "<tr>";
                            echo "<td>{$leaveRequest['leave_id']}</td>";
                            echo "<td>{$leaveRequest['from_date']}</td>";
                            echo "<td>{$leaveRequest['to_date']}</td>";
                            echo "<td>{$leaveRequest['no_of_days']}</td>";
                            echo "<td>{$leaveRequest['reason']}</td>";
                            echo "<td>{$leaveRequest['status']}</td>";
                            echo "</tr>";
                        }
                    }
                } else {
                    echo "<tr><td colspan='14'>No leave request data available.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <table id="notReviewedRequests">
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>From-Date</th>
                    <th>To-Date</th>
                    <th>Number of Days</th>
                    <th>Reason</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                <?php
                if (isset($data['leaveRequest']) && is_array($data['leaveRequest'])) {
                    foreach ($data['leaveRequest'] as $leaveRequest) {
                        if ($leaveRequest['status'] == "Yet to review"){
                            echo "<tr>";
                            echo "<td>{$leaveRequest['leave_id']}</td>";
                            echo "<td>{$leaveRequest['from_date']}</td>";
                            echo "<td>{$leaveRequest['to_date']}</td>";
                            echo "<td>{$leaveRequest['no_of_days']}</td>";
                            echo "<td>{$leaveRequest['reason']}</td>";
                            echo "<td>{$leaveRequest['status']}</td>";
                        }
                    }
                } else {
                    echo "<tr><td colspan='14'>No leave request data available.</td></tr>";
                }
                ?>
            </tbody>
        </table>

    </main>

    <script>

        const defaultColor = '#9e9ea4';

        document.getElementById('reviewedRequests').style.display = 'none';
        document.getElementById('notReviewedRequests').style.display = 'table';
        document.getElementById('showNotReviewed').style.color = '#4CAF50';

        document.getElementById('showReviewedRequests').addEventListener('click', function() {
            document.getElementById('reviewedRequests').style.display = 'table';
            document.getElementById('notReviewedRequests').style.display = 'none';

            // Change color of the clicked text
            document.getElementById('showReviewedRequests').style.color = '#4CAF50';

            // Revert the other text to default color
            document.getElementById('showNotReviewed').style.color = defaultColor;
        });

        document.getElementById('showNotReviewed').addEventListener('click', function() {
            document.getElementById('reviewedRequests').style.display = 'none';
            document.getElementById('notReviewedRequests').style.display = 'table';

            // Change color of the clicked text
            document.getElementById('showNotReviewed').style.color = '#4CAF50';

            // Revert the other text to default color
            document.getElementById('showReviewedRequests').style.color = defaultColor;
        });

        /*document.querySelectorAll('.search-icon').forEach(icon => {
            const popUpMenu = icon.nextElementSibling;

            // Show the pop-up menu on mouseenter
            icon.addEventListener('mouseenter', function() {
                popUpMenu.classList.add('show');
            });

            // Keep the pop-up menu visible when hovering over it
            popUpMenu.addEventListener('mouseenter', function() {
                popUpMenu.classList.add('show');
            });

            // Hide the pop-up menu when leaving the icon
            icon.addEventListener('mouseleave', function() {
                setTimeout(function() {
                    if (!popUpMenu.matches(':hover')) {
                        popUpMenu.classList.remove('show');
                    }
                }, 200); // Slight delay to allow moving from icon to menu
            });

            // Hide the pop-up menu when leaving the menu
            popUpMenu.addEventListener('mouseleave', function() {
                popUpMenu.classList.remove('show');
            });
        });*/
    </script>

</body>
</html>