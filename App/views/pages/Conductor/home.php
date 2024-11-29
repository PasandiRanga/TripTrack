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

                    <!--<tbody>
                    /*
                        /*if (isset($data['scheduleDetails']) && is_array($data['scheduleDetails'])) {
                            foreach ($data['scheduleDetails'] as $scheduleDetails) {
                                if ($scheduleDetails['date'] < $currentDate): //Past assign
                                    echo "<tr>";
                                    echo "<td>{$scheduleDetails['date']}</td>";
                                    echo "<td>{$scheduluDetails['departureTime']}</td>";'
                                    echo "<td>{null
                    </tbody>-->
                </table>

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

        function Openpopup() {
            const popup = document.getElementById("logoutModal");
            popup.classList.add("open-popup"); // Add the class to make modal visible
        }
        
        function showLogoutModal() {
            document.getElementById('logoutModal').style.display = 'flex';
        }

        // Hide the logout modal
        function hideLogoutModal() {
            document.getElementById('logoutModal').style.display = 'none';
        }

        // Proceed with logout and redirect to login page
        function proceedLogout() {
            window.location.href = "<?php echo URLROOT; ?>/GuestPages/logout";// Replace with your login form file
        }


        // Function to redirect back to dashboard
        function cancelLogout() {
            window.location.href = "<?php echo URLROOT; ?>/ConductorPages/home"; // Replace with your dashboard file
        }

        function filterSchedule() {
            // Get the filter date value
            const filterDate = document.getElementById("filterDate").value;
            const table = document.getElementById("upcomingAssigns");
            const rows = table.getElementsByTagName("tr");

            // Loop through the table rows (start from index 1 to skip the header)
            for (let i = 1; i < rows.length; i++) {
                const dateCell = rows[i].getElementsByTagName("td")[0];
                if (dateCell) {
                    const rowDate = dateCell.textContent || dateCell.innerText;

                    // Compare the row's date with the filter date
                    if (filterDate && rowDate !== filterDate) {
                        rows[i].style.display = "none"; // Hide non-matching rows
                    } else {
                        rows[i].style.display = ""; // Show matching rows
                    }
                }
            }
        }

        function resetFilter() {
            const rows = document.getElementById("upcomingAssigns").getElementsByTagName("tr");
            document.getElementById("filterDate").value = ""; // Clear the date input
            for (let i = 1; i < rows.length; i++) { // Start from index 1 to skip the header
                rows[i].style.display = ""; // Show all rows
            }
        }

    </script>

</body>
</html>
