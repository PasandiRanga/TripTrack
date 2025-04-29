<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Conductor' , 'Driver']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Conductor/newHome.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
</head>

<body>
    <script>
        var userRole = <?php echo json_encode($_SESSION['userRole'] ?? 'Conductor'); ?>;
        localStorage.setItem('userRole', userRole);

        const URLROOT = '<?php echo URLROOT; ?>';
    </script>

    <?php
        $currentDate = date('Y-m-d');

        $userId = $_SESSION['user_id'] ?? '';
        $upcomingScheduleData = $data['upcomingSchedule'] ?? [];
        $pastScheduleData = $data['pastSchedule'] ??[];
        $totalSchedules = $data['totalSchedules'] ?? [];
    ?>

    <div class="grid-container">
        <header class="header">

            <div class="header-right" id="menuIcon" onclick="openSidebar()">
                <div class="sidebar-menu-icon">
                    <span class="material-icons-outlined">menu</span>
                </div>
            </div>
            
        </header>

        <aside id="sidebar">
            <div class="sidebar-title" onclick="location.href='<?php echo URLROOT; ?>/ConductorPages/profile'">

                <i class="fa-solid fa-user"></i>
                <span class="text">Employee</span>

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

                <li class="sidebar-list-item" onclick="location.href='<?php echo URLROOT; ?>/ConductorPages/AcceptBookingForm'">
                    <i class="fa-solid fa-upload"></i>
                    <span class="text">Accept Bookings</span>
                </li>

                <li class="sidebar-list-item" onclick="location.href='<?php echo URLROOT; ?>/ConductorPages/informDelays'">
                    <i class="fa-solid fa-clock"></i>
                    <span class="text">Inform Delays</span>
                </li>

                <li class="sidebar-list-item" onclick="location.href='<?php echo URLROOT; ?>/ConductorPages/notifications'">
                    <i class="fa-solid fa-bell"></i>
                    <span class="text">Notifications</span>
                </li>

                <li class="sidebar-list-item" onclick="openLogoutModal()">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span class="text">LogOut</span>
                </li>
            </ul>
        </aside>

        <main class="main-container">
            <div class="main-title">
                    <h2>Employee Dashboard</h2> 
                    <img class="logo-right" src="../images/logo2.png" alt="Logo">
                </div>

            <div class="main-cards">
                <div class="card">
                    <div class="card-inner">
                        <h3 class="card-title">Total Completed Schedules</h3>
                        <span class="material-icons-outlined">beenhere</span>
                    </div>
                    <h1 class="card-value-notification"><?php echo $data['totalSchedules']['monthly_count']; ?></h1>
                </div>

                <div class="card">
                    <div class="card-inner">
                        <h3 class="card-title">Employee Route</h3>
                        <span class="material-icons-outlined">alt_route</span>
                    </div>
                    <h2><?php echo $data['upcomingSchedule'][0]['start_location'] . ' - ' . $data['upcomingSchedule'][0]['destination']; ?></h2>
                </div>

                <?php if (!empty($data['latestNotification'])): ?>
                    <div class="card">
                        <div class="card-inner">
                            <h3 class="card-title">Latest Notification</h3>
                            <span class="material-icons-outlined">notifications</span>
                        </div>
                        <h3 class="card-value-notification"><?php echo $data['latestNotification']['title']; ?></h3>
                        <p class="notification-time"><?php echo $data['latestNotification']['created_at']; ?></p>
                    </div>
                <?php else: ?>
                    <div class="card">
                        <div class="card-inner">
                            <h3 class="card-title">Notifications</h3>
                            <span class="material-icons-outlined">notifications</span>
                        </div>
                        <h2 class="card-value-notification">No Notifications Available</h2>
                        <p class="notification-time">—</p>
                    </div>
                <?php endif; ?>
            </div>

            <center>
            <div class="container">
            
                <div class="column">
                    <div class="wrapper">
                        <header>
                            <p class="current-date"></p>
                            <div class="icons">
                                <span id="prev" class="prev">&#10094;</span>
                                <span id="next" class="next">&#10095;</span>
                            </div>
                        </header>
                        <div class="calendar">
                            <ul class="weeks">
                                <li>Sun</li>
                                <li>Mon</li>
                                <li>Tue</li>
                                <li>Wed</li>
                                <li>Thu</li>
                                <li>Fri</li>
                                <li>Sat</li>
                            </ul>
                            <ul class="days"></ul>
                        </div>
                    </div> 
                </div>
                <div class="column">
                    <div class="close-column-btn">&times;</div>
                    <div class="date-details">
                        <div id="date-info">
                        </div>
                    </div>
                </div>
            </div>
            </center>
        </main>
    </div>

    <div class="modal-overlay" id="logoutModal">
        <div class="modal-content">
            <h1>Are you sure you want to logout?</h1>
            <h4>You won't be able to revert this !</h4>
            <p>
                <button id="yes" onclick="proceedLogout()">Yes</button>
                <button id="no" onclick="cancelLogout()">No</button>
            </p>
            <div class="close-btn" onclick="cancelLogout()">×</div>
        </div>
    </div>

    <script>
        var sidebarOpen = false;
        var sidebar = document.getElementById("sidebar");
        var menuIcon = document.getElementById("menuIcon");

        function openSidebar() {
            if (!sidebarOpen) {
                sidebar.classList.add("sidebar-responsive");
                sidebarOpen = true;
            }
        }

        function closeSidebar() {
            if (sidebarOpen) {
                sidebar.classList.remove("sidebar-responsive");
                sidebarOpen = false;
            }
        }

        function openLogoutModal() {
            document.getElementById("logoutModal").classList.add("open-modal");
        }

        function cancelLogout() {
            document.getElementById("logoutModal").classList.remove("open-modal");
        }

        function proceedLogout() {
            window.location.href = "<?php echo URLROOT; ?>/GuestPages/logout";
        }

        window.toggleDetails = function(event, element) {
            if (event.target.closest('.three-dots') || event.target.closest('.menu')) {
                return;
            }

            const details = element.querySelector('.schedule-details');
            if (!details) return;
            
            const arrow = element.querySelector('.arrow-icon');
            
            if (details.style.display === 'block') {
                details.style.display = 'none';
                if (arrow) arrow.textContent = '▼';
            } else {
                details.style.display = 'block';
                if (arrow) arrow.textContent = '▲';
            }
        };

        document.addEventListener("DOMContentLoaded", function () {
            const currentDate = document.querySelector(".current-date");
            const daysTag = document.querySelector(".days");
            const prevNextIcons = document.querySelectorAll(".icons span");
            const column2 = document.querySelector(".column:nth-child(2)");

            const closeBtn = document.querySelector('.close-column-btn');

            closeBtn.addEventListener('click', function() { 
                column2.style.display = 'none';
                document.getElementById("date-info").innerHTML = ""; 
            });

            const upcomingScheduleData = <?php echo json_encode($upcomingScheduleData); ?>;
            const pastScheduleData = <?php echo json_encode($pastScheduleData); ?>;

            let date = new Date(),
                currYear = date.getFullYear(),
                currMonth = date.getMonth();

            const months = [
                "January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
            ];

            function getScheduleStatusForDate(dateStr) {
                const scheduleIds = new Set();
                let hasUpcoming = false;
                let hasPast = false;

                upcomingScheduleData.forEach(schedule => {
                    if (schedule.date === dateStr) {
                        hasUpcoming = true;
                        scheduleIds.add(schedule.scheduleId);
                    }
                });

                pastScheduleData.forEach(schedule => {
                    if (schedule.date === dateStr) {
                        hasPast = true;
                        scheduleIds.add(schedule.scheduleId);
                    }
                });

                return {
                    hasUpcoming,
                    hasPast,
                    scheduleIds: Array.from(scheduleIds)
                };
            }

            function renderCalendar() {
                let firstDayofMonth = new Date(currYear, currMonth, 1).getDay();
                let lastDateofMonth = new Date(currYear, currMonth + 1, 0).getDate();
                let lastDayofMonth = new Date(currYear, currMonth, lastDateofMonth).getDay();
                let lastDateofLastMonth = new Date(currYear, currMonth, 0).getDate();
                let liTag = "";

                for (let i = firstDayofMonth; i > 0; i--) {
                    liTag += `<li class="inactive">${lastDateofLastMonth - i + 1}</li>`;
                }

                for (let i = 1; i <= lastDateofMonth; i++) {
                    const dateStr = `${currYear}-${String(currMonth + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
                    const scheduleStatus = getScheduleStatusForDate(dateStr); 
                    
                    let className = "";
                    if (i === date.getDate() && currMonth === new Date().getMonth() && currYear === new Date().getFullYear()) {
                        className = "active";
                    }
                    if (scheduleStatus.hasUpcoming) className += " upcoming-schedule";
                    if (scheduleStatus.hasPast) className += " past-schedule";

                    liTag += `<li class="${className}" data-date="${dateStr}">${i}</li>`;
                }

                console.log("Rendering Calendar for:", currMonth + 1, currYear);
                console.log("Upcoming:", upcomingScheduleData);
                console.log("Past:", pastScheduleData);

                for (let i = lastDayofMonth; i < 6; i++) {
                    liTag += `<li class="inactive">${i - lastDayofMonth + 1}</li>`;
                }

                currentDate.innerText = `${months[currMonth]} ${currYear}`;
                daysTag.innerHTML = liTag;

                document.querySelectorAll(".days li").forEach(day => {
                    day.addEventListener("click", function () {
                        if (!this.classList.contains("inactive")) {
                            showDateDetails(this.getAttribute("data-date"));
                            column2.style.display = "block";
                        }
                    });
                });
            }

            function showDateDetails(dateStr) {
                const scheduleStatus = getScheduleStatusForDate(dateStr);
                let dateInfo = `<h3>Schedule for ${dateStr}</h3>`;

                if (!scheduleStatus.scheduleIds.length) {
                    dateInfo += `<p>No schedule available for this date.</p>
                                <br><br>
                                <img class="cal" src="<?php echo URLROOT; ?>/public/images/calendar.png" alt="calendar">`;
                } else {
                    dateInfo += `<div class="schedule-list">`;
                    
                    if (scheduleStatus.hasUpcoming) {
                        dateInfo += `<h4>Upcoming Schedule</h4>`;

                        upcomingScheduleData.forEach(schedule => {
                            if (schedule.date === dateStr) {
                                dateInfo += `
                                    <div class="schedule-item upcoming" onclick="toggleDetails(event, this)">
                                        
                                        <div class="schedule-summary">
                                            <span class="arrow-icon">▼</span>
                                            <p><strong>${schedule.start_location} - </strong>
                                            <strong>${schedule.destination}</strong></p>
                                        </div>
                                        <div class="schedule-details">
                                            <p><strong>From:</strong> ${schedule.start_location}</p>
                                            <p><strong>To:</strong> ${schedule.destination}</p>
                                            <p><strong>Departure Time:</strong> ${schedule.departureTime}</p>
                                            <p><strong>Arrival Time:</strong> ${schedule.arrivalTime}</p>
                                            <p><strong>Route Number:</strong> ${schedule.routeNumber}</p>
                                            <p><strong>License ID:</strong> ${schedule.License_id}</p>
                                            <p><strong>Total Ticket Price:</strong> ${schedule.price}</p>
                                            <p><strong>Price Per KM:</strong> ${schedule.priceperkm}</p>
                                        </div>
                                    </div>
                                `;
                            }
                        });
                    }

                    if (scheduleStatus.hasPast) {
                        dateInfo += `<h4>Past Schedule</h4>`;

                        pastScheduleData.forEach(schedule => {
                            if (schedule.date === dateStr) {

                                dateInfo += `
                                    <div class="schedule-item past" onclick="toggleDetails(event, this)">
                                        
                                        <div class="schedule-summary">
                                            <span class="arrow-icon">▼</span>
                                            <p><strong>${schedule.start_location} - </strong>
                                            <strong>${schedule.destination}</strong></p>
                                        </div>
                                        <div class="schedule-details">
                                            <p><strong>From:</strong> ${schedule.start_location}</p>
                                            <p><strong>To:</strong> ${schedule.destination}</p>
                                            <p><strong>Departure Time:</strong> ${schedule.departureTime}</p>
                                            <p><strong>Arrival Time:</strong> ${schedule.arrivalTime}</p>
                                            <p><strong>Route Number:</strong> ${schedule.routeNumber}</p>
                                            <p><strong>License ID:</strong> ${schedule.License_id}</p>
                                            <p><strong>Total Ticket Price:</strong> ${schedule.price}</p>
                                            <p><strong>Price Per KM:</strong> ${schedule.priceperkm}</p>
                                        </div>
                                    </div>
                                `;
                            }
                        });
                    }

                    dateInfo += `</div>`;
                }

                document.getElementById("date-info").innerHTML = dateInfo;
            }

            prevNextIcons.forEach(icon => {
                icon.addEventListener("click", () => {
                    currMonth = icon.id === "prev" ? currMonth - 1 : currMonth + 1;
                    if (currMonth < 0 || currMonth > 11) {
                        date = new Date(currYear, currMonth);
                        currYear = date.getFullYear();
                        currMonth = date.getMonth();
                    } else {
                        date = new Date();
                    }
                    renderCalendar();
                });
            });

            renderCalendar();

        });

    </script>
</body>
</html>
