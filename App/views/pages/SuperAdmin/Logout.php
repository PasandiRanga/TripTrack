<!--
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <style>
        /* Embedded CSS */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            overflow: hidden;
        }

        /* Semi-transparent overlay */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4); /* Semi-transparent to show the background */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Modal box styling */
        .modal-content {
            background-color: #fff;
            padding: 30px;
            width: 400px;
            text-align: center;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }

        /* Heading */
        .modal-content h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        /* Buttons */
        .modal-buttons {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

        .modal-button {
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .btn-yes {
            background-color: #4CAF50;
            color: #fff;
        }

        .btn-no {
            background-color: #f44336;
            color: #fff;
        }
    </style>
</head>
<body>

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
        // Function to redirect to login page
        function proceedLogout() {
            window.location.href = "login.php"; // Replace with your login form file
        }

        // Function to redirect back to dashboard
        function cancelLogout() {
            window.location.href = "Dashboard.php"; // Replace with your dashboard file
        }
    </script>

</body>
</html>
-->