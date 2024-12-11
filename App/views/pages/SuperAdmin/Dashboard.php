<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Admin']);

?>
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
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/Dashboard.css?v=<?php echo time(); ?>">
</head>
<body>

    <div class="grid-container">
        <header class="header">
            <div class="menu-icons" onclick="openSidebar()">
                <span class="material-icons-outlined">menu</span>
            </div>
            <div class="header-left">
                <img src="../images/logo.png" alt="Logo" class="logo">
            </div>
            <!--
            <div class="header-right">
                <span id="current-date"></span> 
            </div>
            -->
        </header>

        <aside id="sidebar">
            <div class="sidebar-title">
                <div class="sidebar-brand">
                    <span class="material-icons-outlined">admin_panel_settings</span> Admin
                </div>
                <span class="material-icons-outlined" onclick="closeSidebar()">close</span>
            </div>

            <ul class="sidebar-list">
                <li class="sidebar-list-item">
                    <span class="material-icons-outlined">dashboard_customize</span> Dashboard
                </li>
                <li class="sidebar-list-item" onclick="location.href='<?php echo URLROOT; ?>/SuperAdminPages/fleet'">
                    <span class="material-icons-outlined">queue</span> Fleet
                </li>
                
                <li class="sidebar-list-item" onclick="location.href='<?php echo URLROOT; ?>/SuperAdminPages/bookings'">
                    <span class="material-icons-outlined">book</span> Bookings
                </li>

                <li class="sidebar-list-item" onclick="location.href='<?php echo URLROOT; ?>/SuperAdminPages/employees'">
                    <span class="material-icons-outlined">group_add</span> Employees
                </li>

                <li class="sidebar-list-item" onclick="location.href='<?php echo URLROOT; ?>/SuperAdminPages/reviews'">
                    <span class="material-icons-outlined">fact_check</span> Reviews
                </li>

                <li class="sidebar-list-item" onclick="location.href='<?php echo URLROOT; ?>/SuperAdminPages/reports'">
                    <span class="material-icons-outlined">poll</span> Reports
                </li>

                <li class="sidebar-list-item" onclick="location.href='<?php echo URLROOT; ?>/SuperAdminPages/notifications'">
                    <span class="material-icons-outlined">notifications</span> Notifications
                </li>

                <li class="sidebar-list-item" onclick="location.href='<?php echo URLROOT; ?>/SuperAdminPages/leaverequests'">
                    <span class="material-icons-outlined">publish</span> Leave Requests
                </li>

                <li class="sidebar-list-item" onclick="location.href='<?php echo URLROOT; ?>/SuperAdminPages/schedule'">
                    <span class="material-icons-outlined">schedule</span> Schedule
                </li>

                <li class="sidebar-list-item" onclick="location.href='<?php echo URLROOT; ?>/SuperAdminPages/assigns'">
                    <span class="material-icons-outlined">assignment_ind</span> Assigns
                </li>

                <li class="sidebar-list-item" onclick="location.href='<?php echo URLROOT; ?>/SuperAdminPages/contacts'">
                    <span class="material-icons-outlined">contact_page</span> Contacts
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
                        <h3>Total Monthly Income</h3>
                        <span class="material-icons-outlined">local_atm</span>
                    </div>
                    <h1><?php echo 'LKR ', $data['total_income']; ?></h1>
                </div>

                <div class="card">
                    <div class="card-inner">
                        <h3>Customers</h3>
                        <span class="material-icons-outlined">groups</span>
                    </div>
                    <h1><?php echo $data['total_customers']; ?></h1>
                </div>

                <div class="card">
                    <div class="card-inner">
                        <h3>Monthly Bookings</h3>
                        <span class="material-icons-outlined">book</span>
                    </div>
                    <h1><?php echo '1234'; // Example PHP dynamic content ?></h1>
                </div>

                <div class="card">
                    <div class="card-inner">
                        <h3>Completed Schedules</h3>
                        <span class="material-icons-outlined">beenhere</span>
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
    <script>
        var sidebarOpean = false;
        var sidebar = document.getElementById("sidebar");

        function openSidebar(){
            if (!sidebarOpean){
                sidebar.classList.add("sidebar-responsive");
                sidebarOpean = true;
            }
        }

        function closeSidebar(){
            if (sidebarOpean){
                sidebar.classList.remove("sidebar-responsive");
                sidebarOpean = false;
            }
        }

        const barChartOptions = {
            series: [
            {
                data: [12034, 8500, 6250, 4235, 2856],
                name: 'routes_income',
            },
            ],
            chart: {
            type: 'bar',
            background: 'transparent',
            height: 350,
            toolbar: {
                show: false,
            },
            },
            colors: ['#2962ff', '#d50000', '#2e7d32', '#ff6d00', '#583cb3'],
            plotOptions: {
            bar: {
                distributed: true,
                borderRadius: 4,
                horizontal: false,
                columnWidth: '40%',
            },
            },
            dataLabels: {
            enabled: false,
            },
            fill: {
            opacity: 1,
            },
            grid: {
            borderColor: '#55596e',
            yaxis: {
                lines: {
                show: true,
                },
            },
            xaxis: {
                lines: {
                show: true,
                },
            },
            },
            legend: {
            labels: {
                colors: '#f5f7ff',
            },
            show: true,
            position: 'top',
            },
            stroke: {
            colors: ['transparent'],
            show: true,
            width: 2,
            },
            tooltip: {
            shared: true,
            intersect: false,
            theme: 'dark',
            },
            xaxis: {
            categories: ['Galle-Makubura', 'Colombo-Homagama', 'Galle-Matara', 'Kottawa-Petta', 'Colombo-Kandy'],
            title: {
                style: {
                color: '#f5f7ff',
                },
            },
            axisBorder: {
                show: true,
                color: '#55596e',
            },
            axisTicks: {
                show: true,
                color: '#55596e',
            },
            labels: {
                style: {
                colors: '#f5f7ff',
                },
            },
            },
            yaxis: {
            title: {
                text: 'Amount',
                style: {
                color: '#f5f7ff',
                },
            },
            axisBorder: {
                color: '#55596e',
                show: true,
            },
            axisTicks: {
                color: '#55596e',
                show: true,
            },
            labels: {
                style: {
                colors: '#f5f7ff',
                },
            },
            },
        };
        
        const barChart = new ApexCharts(
            document.querySelector('#bar-chart'),
            barChartOptions
        );
        barChart.render();
        


        
        // AREA CHART
        const areaChartOptions = {
            series: [
            {
                name: 'Bookings',
                data: [31, 40, 28, 51, 42, 109, 100],
            },
            {
                name: 'Cancellations',
                data: [11, 32, 22, 32, 34, 52, 41],
            },
            ],
            chart: {
            type: 'area',
            background: 'transparent',
            height: 350,
            stacked: false,
            toolbar: {
                show: false,
            },
            },
            colors: ['#00ab57', '#d50000'],
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            dataLabels: {
            enabled: false,
            },
            fill: {
            gradient: {
                opacityFrom: 0.4,
                opacityTo: 0.1,
                shadeIntensity: 1,
                stops: [0, 100],
                type: 'vertical',
            },
            type: 'gradient',
            },
            grid: {
            borderColor: '#55596e',
            yaxis: {
                lines: {
                show: true,
                },
            },
            xaxis: {
                lines: {
                show: true,
                },
            },
            },
            legend: {
            labels: {
                colors: '#f5f7ff',
            },
            show: true,
            position: 'top',
            },
            markers: {
            size: 6,
            strokeColors: '#1b2635',
            strokeWidth: 3,
            },
            stroke: {
            curve: 'smooth',
            },
            xaxis: {
            axisBorder: {
                color: '#55596e',
                show: true,
            },
            axisTicks: {
                color: '#55596e',
                show: true,
            },
            labels: {
                offsetY: 5,
                style: {
                colors: '#f5f7ff',
                },
            },
            },
            yaxis: [
            {
                title: {
                text: 'Purchase Orders',
                style: {
                    color: '#f5f7ff',
                },
                },
                labels: {
                style: {
                    colors: ['#f5f7ff'],
                },
                },
            },
            {
                opposite: true,
                title: {
                text: 'Sales Orders',
                style: {
                    color: '#f5f7ff',
                },
                },
                labels: {
                style: {
                    colors: ['#f5f7ff'],
                },
                },
            },
            ],
            tooltip: {
            shared: true,
            intersect: false,
            theme: 'dark',
            },
        };
        
        const areaChart = new ApexCharts(
            document.querySelector('#area-chart'),
            areaChartOptions
        );
        areaChart.render();


        //logout
        let popup = document.getElementById("logoutModal");
        function Openpopup(){
        popup.classList.add("open-popup");
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
            window.location.href = "<?php echo URLROOT; ?>/SuperAdminPages/home"; // Replace with your dashboard file
        }

        // Function to display the current date
        function displayCurrentDate() {
            const dateElement = document.getElementById("current-date");
            const currentDate = new Date();
            const formattedDate = currentDate.toLocaleDateString("en-US", {
                weekday: "long",
                year: "numeric",
                month: "long",
                day: "numeric"
            });
            dateElement.textContent = formattedDate; // Update the date span
        }

        // Call the function on page load
        document.addEventListener("DOMContentLoaded", displayCurrentDate);

    </script>
</body>
</html>
