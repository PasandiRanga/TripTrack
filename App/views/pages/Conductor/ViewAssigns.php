<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Conductor' , 'Driver']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Conductor/ViewAssigns.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar/navbar.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=ABeeZee&display=swap" rel="stylesheet">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Assigns</title>
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
        'currentMethod' => 'viewAssigns', // Adjust this based on the method
        'userRole' => $userRole
    ];
    ?>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

    <div class="hero-container">
        <?php require APPROOT.'/views/inc/Components/Header/header.php'; ?>
        <?php require APPROOT.'/views/inc/Components/NavBar/navbar.php'; ?>

        <div class="background"></div>
    </div>

    <div class="main">
        <br>
        <div class="container">
            <h3 class="clickable" id="showPastAssigns">Past Schedule</h3>
            <h3 class="clickable" id="showUpcomingAssigns">Upcoming Schedule</h3>
            <div class="input-group">
                <div class="icon"><i class="fas fa-calendar-alt"></i></div>
                <input type="date" class="search-input">
            </div>
        </div>

        <!--data file-->

        <?php require 'AssignsData.php'; ?>

        <table id="pastAssigns">
        <thead>
            <tr>
            <th>Date</th>
            <th>Time</th>
            <th>Route</th>
            <th>From</th>
            <th>To</th>
            <th>Bus No</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($assignsDetails as $assign): ?>
                <?php if ($assign['date'] < $currentDate): // Past assign ?>
                    <tr>
                        <td data-label="Date"><?php echo $assign['date']; ?></td>
                        <td data-label="Time"><?php echo $assign['time']; ?></td>
                        <td data-label="Route"><?php echo $assign['route']; ?></td>
                        <td data-label="From"><?php echo $assign['from']; ?></td>
                        <td data-label="To"><?php echo $assign['to']; ?></td>
                        <td data-label="Bus No"><?php echo $assign['busNo']; ?></td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>

    <table id="upcomingAssigns">
    <thead>
        <tr>
            <th>Date</th>
            <th>Time</th>
            <th>Route</th>
            <th>From</th>
            <th>To</th>
            <th>Bus No</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($assignsDetails as $assign): ?>
            <?php if ($assign['date'] >= $currentDate): // Upcoming assign ?>
                <tr>
                    <td data-label="Date"><?php echo $assign['date']; ?></td>
                    <td data-label="Time"><?php echo $assign['time']; ?></td>
                    <td data-label="Route"><?php echo $assign['route']; ?></td>
                    <td data-label="From"><?php echo $assign['from']; ?></td>
                    <td data-label="To"><?php echo $assign['to']; ?></td>
                    <td data-label="Bus No"><?php echo $assign['busNo']; ?></td>
                        <!--<i class="fas fa-search search-icon"></i>
                        <div class="pop-up-menu">
                            <a href="./SeeTicket.html"><p>See Ticket</p></a>
                            <a href="./CancelBooking.html"><p>Cancel Booking</p></a>
                        </div>-->
                    </td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    </tbody>
</table>

<script>

    // Default color for the non-clicked element
    const defaultColor = 'black'; // or whatever the default color is

    // Show only the "Past Assigns" table by default
    document.getElementById('pastAssigns').style.display = 'table';
    document.getElementById('upcomingAssigns').style.display = 'none';
    document.getElementById('showPastAssigns').style.color = '#4CAF50';

    // Add click event listeners for the headers
    document.getElementById('showPastAssigns').addEventListener('click', function() {
        document.getElementById('pastAssigns').style.display = 'table';
        document.getElementById('upcomingAssigns').style.display = 'none';

            // Change color of the clicked text
        document.getElementById('showPastAssigns').style.color = '#4CAF50';

            // Revert the other text to default color
        document.getElementById('showUpcomingAssigns').style.color = defaultColor;
    });

    document.getElementById('showUpcomingAssigns').addEventListener('click', function() {
        document.getElementById('pastAssigns').style.display = 'none';
        document.getElementById('upcomingAssigns').style.display = 'table';

        // Change color of the clicked text
        document.getElementById('showUpcomingAssigns').style.color = '#4CAF50';

        // Revert the other text to default color
        document.getElementById('showPastAssigns').style.color = defaultColor;
    });

    // Handle pop-up menu visibility
    document.querySelectorAll('.search-icon').forEach(icon => {
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
});



    </script>