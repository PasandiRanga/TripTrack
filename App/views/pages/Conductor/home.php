
<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Conductor' , 'Driver']);

    // Get current date
    $currentDate = date('Y-m-d');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Conductor/home.css?v=<?php echo time(); ?>">
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

    

    <div class="grid-container">
        <header class="header">
            <div class="menu-icons" onclick="openSidebar()">
                <span class="material-icons-outlined">menu</span>
            </div>

            <div class="header-left">
                
                <img src="../Public/images/logo.png" alt="Logo" class="logo">
            </div>
            <!--
            <div class="header-right">
                <span class="material-icons-outlined">notifications</span>
                <span class="material-icons-outlined">email</span>
                <span class="material-icons-outlined">account_circle</span>
            </div>
            -->
        </header>

        <aside id="sidebar">
            <div class="sidebar-title" onclick="location.href='<?php echo URLROOT; ?>/ConductorPages/profile'">

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

                <li class="sidebar-list-item" onclick="location.href='<?php echo URLROOT; ?>/ConductorPages/requestLeave'">
                    <i class="fa-solid fa-upload"></i>
                    <span class="text">Request Leaves</span>
                </li>

                <li class="sidebar-list-item" onclick="location.href='<?php echo URLROOT; ?>/ConductorPages/informDelays'">
                    <i class="fa-solid fa-clock"></i>
                    <span class="text">Inform Delays</span>
                </li>

                <li class="sidebar-list-item" onclick="location.href='<?php echo URLROOT; ?>/ConductorPages/notifications'">
                    <i class="fa-solid fa-bell"></i>
                    <span class="text">Notifications</span>
                </li>

                <li class="sidebar-list-item" onclick="Openpopup()">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span class="text">LogOut</span>
                </li>
            </ul>
        </aside>

        <main class="main-container">

            <div class="main-title">
                <h2>Dashboard</h2>
            </div>

            <div class="container">
                <h3 class="clickable" id="showUpcomingAssigns">Upcoming Schedule</h3>
                <h3 class="clickable" id="showPastAssigns">Past Schedule</h3>

                <div class="filter-container">
                    <button class="clear-button" onclick="resetFilter()">Clear</button>
                    <div class="input-group">
                        <div class="icon"><i class="fas fa-calendar-alt"></i></div>
                        <input type="date" class="search-input" id="filterDate" onchange="filterSchedule()">
                    </div>
                </div>
            </div>
            
            <table id="upcomingAssigns" border="1">

                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Departure Time</th>
                            <th>Arrival Time</th>
                            <th>Route Number</th>
                            <th>Start Location</th>
                            <th>Destination</th>
                            <th>License ID</th>
                            <th>Available Seats</th>
                            <th>Booked Seats</th>
                            <th>Price</th>
                            <th>Price Per KM</th>
                            <th>Type</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if(!empty($data['schedule'])): ?>
                            <?php $upcomingSchedule = false; ?>
                            <?php foreach ($data['schedule'] as $item): ?>
                                <?php if ($item['date'] >= $currentDate): ?>
                                    <?php $upcomingSchedule = true; ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['date']) ?></td>
                                        <td><?= htmlspecialchars($item['departureTime']) ?></td>
                                        <td><?= htmlspecialchars($item['arrivalTime']) ?></td>
                                        <td><?= htmlspecialchars($item['routeNumber']) ?></td>
                                        <td><?= htmlspecialchars($item['start_location']) ?></td>
                                        <td><?= htmlspecialchars($item['destination']) ?></td>
                                        <td><?= htmlspecialchars($item['License_id']) ?></td>
                                        <td><?= htmlspecialchars($item['availableSeats']) ?></td>
                                        <td><?= htmlspecialchars($item['bookedSeats']) ?></td>
                                        <td><?= htmlspecialchars($item['price']) ?></td>
                                        <td><?= htmlspecialchars($item['priceperkm']) ?></td>
                                        <td><?= htmlspecialchars($item['type']) ?></td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>

                            <?php if (!$upcomingSchedule): ?>
                                <tr><td colspan="6">No upcoming schedule data available.</td></tr>
                            <?php endif; ?>
                        <?php else: ?>
                            <tr><td colspan="6">No schedule data available.</td></tr>
                        <?php endif; ?>
                    </tbody>
            </table>

            <table id="pastAssigns" border="1">

                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Departure Time</th>
                            <th>Arrival Time</th>
                            <th>Route Number</th>
                            <th>Start Location</th>
                            <th>Destination</th>
                            <th>License ID</th>
                            <th>Available Seats</th>
                            <th>Booked Seats</th>
                            <th>Price</th>
                            <th>Price Per KM</th>
                            <th>Type</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if(!empty($data['schedule'])): ?>
                            <?php $pastSchedule = false; ?>
                            <?php foreach ($data['schedule'] as $item): ?>
                                <?php if ($item['date'] < $currentDate): ?>
                                    <?php $pastSchedule = true; ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['date']) ?></td>
                                        <td><?= htmlspecialchars($item['departureTime']) ?></td>
                                        <td><?= htmlspecialchars($item['arrivalTime']) ?></td>
                                        <td><?= htmlspecialchars($item['routeNumber']) ?></td>
                                        <td><?= htmlspecialchars($item['start_location']) ?></td>
                                        <td><?= htmlspecialchars($item['destination']) ?></td>
                                        <td><?= htmlspecialchars($item['License_id']) ?></td>
                                        <td><?= htmlspecialchars($item['availableSeats']) ?></td>
                                        <td><?= htmlspecialchars($item['bookedSeats']) ?></td>
                                        <td><?= htmlspecialchars($item['price']) ?></td>
                                        <td><?= htmlspecialchars($item['priceperkm']) ?></td>
                                        <td><?= htmlspecialchars($item['type']) ?></td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>

                            <?php if (!$pastSchedule): ?>
                                <tr><td colspan="6">No past schedule data available.</td></tr>
                            <?php endif; ?>
                        <?php else: ?>
                            <tr><td colspan="6">No schedule data available.</td></tr>
                        <?php endif; ?>
                    </tbody>
            </table>

            <script>
                const defaultColor = '#9e9ea4';

                // Default display
                document.getElementById('pastAssigns').style.display = 'none';
                document.getElementById('upcomingAssigns').style.display = 'table';
                document.getElementById('showUpcomingAssigns').style.color = '#4CAF50';

                // Tab click events
                document.getElementById('showUpcomingAssigns').addEventListener('click', function () {
                    document.getElementById('upcomingAssigns').style.display = 'table';
                    document.getElementById('pastAssigns').style.display = 'none';

                    document.getElementById('showUpcomingAssigns').style.color = '#4CAF50';

                    document.getElementById('showPastAssigns').style.color = defaultColor;
                });

                document.getElementById('showPastAssigns').addEventListener('click', function () {
                    document.getElementById('pastAssigns').style.display = 'table';
                    document.getElementById('upcomingAssigns').style.display = 'none';

                    document.getElementById('showPastAssigns').style.color = '#4CAF50';

                    document.getElementById('showUpcomingAssigns').style.color = defaultColor;
                });
            </script>

        </main>
    </div>
</body>