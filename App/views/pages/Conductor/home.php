<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Conductor' , 'Driver']);
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


            <table id="upcomingAssigns">

                <?php if (!empty($data['schedule'])): ?>
                    <table border="1">
                        <thead>
                            <tr>
                                <th>Assign Time</th>
                                <th>Date</th>
                                <th>Route Number</th>
                                <th>Start Location</th>
                                <th>Destination</th>
                                <th>License ID</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['schedule'] as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['assign_time']) ?></td>
                                    <td><?= htmlspecialchars($item['date']) ?></td>
                                    <td><?= htmlspecialchars($item['routeNumber']) ?></td>
                                    <td><?= htmlspecialchars($item['start_location']) ?></td>
                                    <td><?= htmlspecialchars($item['destination']) ?></td>
                                    <td><?= htmlspecialchars($item['License_id']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No schedule data available.</p>
                <?php endif; ?>
            </table>
        </main>
    </div>
</body>