<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Conductor' , 'Driver']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Conductor/busLayout.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar/navbar.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=ABeeZee&display=swap" rel="stylesheet">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Layout</title>
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
        'currentMethod' => 'busLayout', // Adjust this based on the method
        'userRole' => $userRole
    ];
    ?>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/ConductorPages/home'">Back</button>

    <h1>Bus Layout</h1>

  <div class="layout-wrapper">

    <!-- Left Panel: Seat Summary -->
    <div class="summary-panel">
      <h2>Seat Status Summary</h2>
      <ul>
        <li><strong>Accepted Seats:</strong> <span id="acceptedList"></span></li>
        <li><strong>To Be Accepted Seats:</strong> <span id="toBeAcceptedList"></span></li>
        <li><strong>All Booked Seats:</strong> <span id="allBookedList"></span></li>
        <li><strong>Newly Accepted Seats (from QR):</strong> <span id="newlyAcceptedList"></span></li>
      </ul>

      <div class="legend">
        <div class="legend-item"><span class="legend-color accepted"></span> Accepted</div>
        <div class="legend-item"><span class="legend-color to-be-accepted"></span> To Be Accepted</div>
        <div class="legend-item"><span class="legend-color newly-accepted"></span> Newly Accepted</div>
        <div class="legend-item"><span class="legend-color available"></span> Available</div>
      </div>
    </div>

    <!-- Right Panel: Seat Layout -->
    <div id="seatLayout" class="seat-layout"></div>

  </div>

  <script>
    const acceptedSeats = ["1", "2", "3", "10", "15"];
    const toBeAcceptedSeats = ["4", "5", "6", "20"];

    const urlParams = new URLSearchParams(window.location.search);
    const newlyAcceptedString = urlParams.get("accepted") || "";
    const newlyAcceptedSeats = newlyAcceptedString.split(',').map(s => s.trim()).filter(Boolean);

    const allBookedSeats = [...new Set([...acceptedSeats, ...toBeAcceptedSeats])];

    const layoutContainer = document.getElementById("seatLayout");
    for (let i = 1; i <= 40; i++) {
      const seat = i.toString();
      const seatDiv = document.createElement("div");
      seatDiv.className = "seat";

      if (newlyAcceptedSeats.includes(seat)) {
        seatDiv.classList.add("newly-accepted");
      } else if (acceptedSeats.includes(seat)) {
        seatDiv.classList.add("accepted");
      } else if (toBeAcceptedSeats.includes(seat)) {
        seatDiv.classList.add("to-be-accepted");
      } else {
        seatDiv.classList.add("available");
      }

      seatDiv.textContent = seat;
      layoutContainer.appendChild(seatDiv);

      if (i % 4 === 0) {
        layoutContainer.appendChild(document.createElement("br"));
      }
    }

    const formatList = arr => arr.length ? arr.join(', ') : "None";
    document.getElementById("acceptedList").textContent = formatList(acceptedSeats);
    document.getElementById("toBeAcceptedList").textContent = formatList(toBeAcceptedSeats);
    document.getElementById("allBookedList").textContent = formatList(allBookedSeats);
    document.getElementById("newlyAcceptedList").textContent = formatList(newlyAcceptedSeats);
  </script>

</body>
</html>