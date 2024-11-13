<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home and Bus Schedules</title>
    
    <!-- Font Awesome and Google Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Component and Page Styles -->
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar/navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/buttons/button.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/searchBar/searchBar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/RotateText/rotateText.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/BusCard/busCard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/GuestUser/home.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/Footer/footer.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/RegionalAdmin/Schedule.css?v=<?php echo time(); ?>">

    <!-- Pass user role to JavaScript -->
    <script>
        var userRole = <?php echo json_encode($_SESSION['userRole'] ?? 'Admin'); ?>;
        localStorage.setItem('userRole', userRole);
    </script>

</head>
<body>
    <!-- Retrieve User Role and Current Page Data -->
    <?php
    $userRole = $_SESSION['userRole'] ?? 'Admin';
    $data = [
        'currentController' => 'RegionalAdminPages',
        'currentMethod' => 'schedule',
        'userRole' => $userRole
    ];
    ?>

    <!-- Load Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

    <!-- Hero Section with Header and NavBar -->
    <div class="hero-container">
        <?php require APPROOT . '/views/inc/Components/Header/header.php'; ?>
        <?php require APPROOT . '/views/inc/Components/NavBar/navbar.php'; ?>

        <div class="background"></div>

        <!-- Rotating Text and Main Image -->
        <div class="text-container">
            <?php require APPROOT . '/views/inc/Components/RotateText/rotateText.php'; ?>
        </div>
        <img class="name-image" src="<?php echo URLROOT; ?>/public/images/BlackName.png" alt="Name Image" />
    </div>

    <!-- Bus Schedules Section -->
    <div class="body-section">
        <h1>Bus Schedules</h1>
        
        <!-- Schedule Table -->
        <div class="table-container">
            <table class="schedule-table">
                <thead>
                    <tr>
                        <th>Schedule ID</th>
                        <th>Date</th>
                        <th>Departure Time</th>
                        <th>Arrival Time</th>
                        <th>Duration</th>
                        <th>Price</th>
                        <th>Bus ID</th>
                        <th>Bus Number</th>
                        <th>Route</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $schedules = [
                        ['scheduleId' => '1', 'date' => '2024-11-10', 'departureTime' => '6:00 AM', 'arrivalTime' => '3:00 PM', 'duration' => '9 hours 30 mins', 'price' => 'Rs. 700', 'busId' => '1', 'busNumber' => 'NA-1234', 'route' => 'Colombo - Ampara'],
                        ['scheduleId' => '2', 'date' => '2024-11-11', 'departureTime' => '7:00 AM', 'arrivalTime' => '4:00 PM', 'duration' => '9 hours', 'price' => 'Rs. 750', 'busId' => '2', 'busNumber' => 'NA-5678', 'route' => 'Colombo - Galle']
                    ];

                    foreach ($schedules as $schedule) {
                        echo "<tr onclick='selectRow(this)'>";
                        echo "<td>{$schedule['scheduleId']}</td>";
                        echo "<td>{$schedule['date']}</td>";
                        echo "<td>{$schedule['departureTime']}</td>";
                        echo "<td>{$schedule['arrivalTime']}</td>";
                        echo "<td>{$schedule['duration']}</td>";
                        echo "<td>{$schedule['price']}</td>";
                        echo "<td>{$schedule['busId']}</td>";
                        echo "<td>{$schedule['busNumber']}</td>";
                        echo "<td>{$schedule['route']}</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Action Buttons -->
        <div class="button-group">
            <button onclick="addSchedule()">Add Schedule</button>
            <button onclick="updateSchedule()">Update</button>
            <button onclick="deleteSchedule()">Delete</button>
        </div>
    </div>

    <!-- Footer Section -->
    <div class="footer-container">
        <?php require APPROOT . '/views/inc/Components/Footer/footer.php'; ?>
    </div>

    <!-- JavaScript Functions -->
    <script>
        // Function to select a row in the table
        function selectRow(row) {
            const selectedRow = document.querySelector(".schedule-table tr.selected");
            if (selectedRow) {
                selectedRow.classList.remove("selected");
            }
            row.classList.add("selected");
        }

        // Function to handle adding a schedule
        function addSchedule() {
            window.location.href = "<?php echo URLROOT; ?>/RegionalAdminPages/addschedule";
        }

        // Function to handle updating a selected schedule
        function updateSchedule() {
            const selectedRow = document.querySelector(".schedule-table tr.selected");
            if (selectedRow) {
                const scheduleId = selectedRow.cells[0].textContent;
                window.location.href = `update_schedule.php?scheduleId=${scheduleId}`;
            } else {
                alert("Please select a schedule to update.");
            }
        }

        // Function to handle deleting a selected schedule
        function deleteSchedule() {
            const selectedRow = document.querySelector(".schedule-table tr.selected");
            if (selectedRow) {
                const scheduleId = selectedRow.cells[0].textContent;
                if (confirm(`Are you sure you want to delete schedule ID ${scheduleId}?`)) {
                    alert(`Schedule ID ${scheduleId} has been deleted.`);
                }
            } else {
                alert("Please select a schedule to delete.");
            }
        }
    </script>
</body>
</html>
