<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Conductor/home.css?v=<?php echo time(); ?>">
    <!--<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee-Dashboard</title>
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

    <div class="grid-container">
        <header class="header">
            <div class="menu-icons" onclick="openSidebar()">
                <span class="material-icons-outlined">menu</span>
            </div>
            <!--
            <div class="header-left">
                <span class="material-icons-outlined">search</span>
            </div>
            
            <div class="header-right">
                <span class="material-icons-outlined">notifications</span>
                <span class="material-icons-outlined">email</span>
                <span class="material-icons-outlined">account_circle</span>
            </div>
            -->
        </header>

        <aside id="sidebar">
            <div class="sidebar-title">

                <i class="fa-solid fa-user"></i>
                <span class="text">Employee</span>

                <!--<span class="material-icons-outlined" onclick="closeSidebar()">close</span>-->
            </div>

            <ul class="sidebar-list">

                <li class="sidebar-list-item">
                    <i class="fa-solid fa-house"></i>
                    <span class="text">Dashboard</span>
                </li>

                <li class="sidebar-list-item" onclick="location.href='<?php echo URLROOT; ?>/ConductorPages/scanQRcode'">
                    <i class="fa-solid fa-qrcode"></i>
                    <span class="text">Scan QR Code</span>
                </li>

                <li class="sidebar-list-item" onclick="location.href='<?php echo URLROOT; ?>/ConductorPages/informDelays'">
                    <i class="fa-solid fa-clock"></i>
                    <span class="text">Inform Delays</span>
                </li>

                <li class="sidebar-list-item" onclick="location.href='<?php echo URLROOT; ?>/ConductorPages/requestLeave'">
                    <i class="fa-solid fa-upload"></i>
                    <span class="text">Request Leaves</span>
                </li>

                <li class="sidebar-list-item" onclick="location.href='<?php echo URLROOT; ?>/ConductorPages/notifications'">
                    <i class="fa-solid fa-bell"></i>
                    <span class="text">Notifications</span>
                </li>

                <!--<li class="sidebar-list-item" onclick="Openpopup()">
                    <span class="material-icons-outlined">logout</span> Logout
                </li>-->
            </ul>
        </aside>

        <main class="main-container">

            <div class="main-title">
                <h2>Dashboard</h2>
            </div>

            <br>
            <div class="container">
                <h3 class="clickable" id="showUpcomingAssigns">Upcoming Schedule</h3>
                <h3 class="clickable" id="showPastAssigns">Past Schedule</h3>

                <div class="input-group">
                    <div class="icon"><i class="fas fa-calendar-alt"></i></div>
                    <input type="date" class="search-input">
                </div>
            </div>

                <!--data file-->

                <?php require 'AssignsData.php'; ?>

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
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>

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

        </main>
    </div>

   <script>

        // Default color for the non-clicked element
        const defaultColor = '#9e9ea4';

        // Show only the "Upcoming Assigns" table by default
        document.getElementById('pastAssigns').style.display = 'none';
        document.getElementById('upcomingAssigns').style.display = 'table';
        document.getElementById('showUpcomingAssigns').style.color = '#4CAF50';

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

</body>
</html>
