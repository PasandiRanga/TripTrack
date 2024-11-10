<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">

    <link rel="stylesheet" href="CSS/styles.css">
</head>
<body>

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
                <div class="sidebar-brand">
                    <span class="material-icons-outlined">man</span> Admin
                </div>
                <span class="material-icons-outlined" onclick="closeSidebar()">close</span>
            </div>

            <ul class="sidebar-list">
                <li class="sidebar-list-item">
                    <span class="material-icons-outlined">dashboard</span> Dashboard
                </li>
                <li class="sidebar-list-item" onclick="location.href='Fleet.php'">
                    <span class="material-icons-outlined">queue</span> Fleet
                </li>
                
                <li class="sidebar-list-item" onclick="location.href='Bookings.php'">
                    <span class="material-icons-outlined">book</span> Bookings
                </li>
                <li class="sidebar-list-item" onclick="location.href='Users.php'">
                    <span class="material-icons-outlined">groups</span> Users
                </li>
                <li class="sidebar-list-item" onclick="location.href='Reviews.php'">
                    <span class="material-icons-outlined">fact_check</span> Reviews
                </li>
                <li class="sidebar-list-item" onclick="location.href='Reports.php'">
                    <span class="material-icons-outlined">poll</span> Reports
                </li>
                <li class="sidebar-list-item" onclick="location.href='Notifications.php'">
                    <span class="material-icons-outlined">notifications</span> Notifications
                </li>
                <li class="sidebar-list-item" onclick="location.href='Schedule.php'">
                    <span class="material-icons-outlined">schedule</span> Schedule
                </li>
                <li class="sidebar-list-item" onclick="Openpopup()">
                    <span class="material-icons-outlined">logout</span> Logout
                </li>
            </ul>
        </aside>

        <main class="main-container">
            <div class="main-title">

                <h2>Dashboard</h2>
            </div>

            <div class="main-cards">
                <div class="card">
                    <div class="card-inner">
                        <h3>Total Income</h3>
                        <span class="material-icons-outlined">money</span>
                    </div>
                    <h1><?php echo '345890LKR'; ?></h1>
                </div>

                <div class="card">
                    <div class="card-inner">
                        <h3>Categories</h3>
                        <span class="material-icons-outlined">groups</span>
                    </div>
                    <h1><?php echo '25'; // Example PHP dynamic content ?></h1>
                </div>

                <div class="card">
                    <div class="card-inner">
                        <h3>Customers</h3>
                        <span class="material-icons-outlined">groups</span>
                    </div>
                    <h1><?php echo '1500'; // Example PHP dynamic content ?></h1>
                </div>

                <div class="card">
                    <div class="card-inner">
                        <h3>Alerts</h3>
                        <span class="material-icons-outlined">notification_important</span>
                    </div>
                    <h1><?php echo '56'; // Example PHP dynamic content ?></h1>
                </div>
            </div>

            <div class="charts">
                <div class="charts-card">
                    <h2 class="chart-title">Top 5 Routes</h2>
                    <div id="bar-chart"></div>
                </div>

                <div class="charts-card">
                    <h2 class="chart-title">Income Summary</h2>
                    <div id="area-chart"></div>
                </div>
            </div>
        </main>
    </div>

    <div class="modal-overlay" id="logoutModal">
        <div class="modal-content">
            <h2>Are you sure you want to logout?</h2>
            <p>This will end your current session.</p>
            <div class="modal-buttons">
                <button class="modal-button btn-yes" onclick="proceedLogout()">Yes</button>
                <button class="modal-button btn-no" onclick="cancelLogout()">No</button>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.52.0/apexcharts.min.js"></script>
    <script src="JS/script.js"></script>
</body>
</html>
