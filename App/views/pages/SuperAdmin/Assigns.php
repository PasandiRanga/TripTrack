<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Admin']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assigns</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/Assigns.css?v=<?php echo time(); ?>">
</head>
<body>
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/home'">Back</button>

<div class="box">
    <h2>Assigns</h2>

    <div class="top-actions">
        <button class="add-assign-btn" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/addassigns'">Add Assigns</button>
    </div>

    <div class="assign-table-container">
        <table class="assign-table">
            <thead>
                <tr>
                    <th>Schedule ID</th>
                    <th>Conductor Name</th>
                    <th>Conductor ID</th>
                    <th>Driver Name</th>
                    <th>Driver ID</th>
                    <th>Assign Time</th>
                    <th>Assign Date</th>
                    <th>Update</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($data['assign']) && is_array($data['assign'])) {
                    foreach ($data['assign'] as $assign) {
                        echo "<tr>
                                <td>{$assign['scheduleId']}</td>
                                <td>{$assign['conductor_name']}</td>
                                <td>{$assign['conductor_id']}</td>
                                <td>{$assign['driver_name']}</td>
                                <td>{$assign['driver_id']}</td>
                                <td>{$assign['assign_time']}</td>
                                <td>{$assign['assign_date']}</td>
                                <td><button class='update-btn' onclick='updateAssign(\"{$assign['scheduleId']}\")'>Update</button></td>
                                <td><button class='delete-btn' onclick='confirmDelete(\"{$assign['scheduleId']}\")'>Delete</button></td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No Assigns data available.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Confirmation Popup -->
<div id="confirmModal" class="popup-modal" style="display:none;">
  <div class="popup-content">
    <p>Are you sure you want to delete this assign?</p>
    <button id="confirmYesBtn">Yes</button>
    <button onclick="closeConfirm()">No</button>
  </div>
</div>

<!-- Success Popup -->
<div id="popupModal" class="popup-modal" style="display:none;">
  <div class="popup-content">
    <p id="popupMessage"></p>
    <button onclick="closePopup()">OK</button>
  </div>
</div>

<style>
/* Common Popup Overlay */
.popup-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* semi-transparent dark background */
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    display: none; /* hidden by default */
}

/* Popup Content (First Box) */
.popup-content {
    background-color: white;
    width: 400px;
    padding: 30px 25px;
    box-sizing: border-box;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    text-align: center;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    animation: popupFadeIn 0.3s ease-out;
}

/* Popup Message (Text inside popup-content) */
.popup-content p {
    font-size: 18px;
    color: #424242;
    margin-bottom: 20px;
}

/* Buttons inside .popup-content */
.popup-content button {
    width: 120px;
    height: 40px;
    font-size: 16px;
    font-weight: 600;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    margin: 10px;
}

/* Confirm Button */
.popup-content button:nth-child(1) {
    background-color: #2ecc71; /* Green color */
    color: white;
}

.popup-content button:nth-child(1):hover {
    background-color: #45a049;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(46, 204, 113, 0.2);
}

.popup-content button:nth-child(1):active {
    background-color: #3e8e41;
}

/* Cancel Button (No button) */
.popup-content button:nth-child(2) {
    background-color: #e74c3c; /* Red color */
    color: white;
}

.popup-content button:nth-child(2):hover {
    background-color: #c0392b;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(231, 76, 60, 0.2);
}

.popup-content button:nth-child(2):active {
    background-color: #e74c3c;
}

/* Animation */
@keyframes popupFadeIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

</style>

<script>
let deleteScheduleId = null;

function confirmDelete(scheduleId) {
    deleteScheduleId = scheduleId;
    document.getElementById('confirmModal').style.display = 'flex';
}

function closeConfirm() {
    document.getElementById('confirmModal').style.display = 'none';
}

document.getElementById('confirmYesBtn').addEventListener('click', function() {
    if (deleteScheduleId) {
        deleteAssign(deleteScheduleId);
        closeConfirm();
    }
});

function deleteAssign(scheduleId) {
    fetch('<?php echo URLROOT; ?>/SuperAdminPages/deleteAssign', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json'},
        body: JSON.stringify({scheduleId})
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            const rows = Array.from(document.querySelectorAll("table.assign-table tbody tr"));
            const row = rows.find(row => row.cells[0].innerText.trim() === String(scheduleId));
            if (row) {
                row.remove();
            }
            showPopup('Assign deleted successfully!');
        } else {
            alert(data.message);
        }
    })
    .catch(() => alert('Error deleting the assign.'));
}

function updateAssign(scheduleId) {
    const rows = Array.from(document.querySelectorAll("table.assign-table tbody tr"));
    const row = rows.find(row => row.cells[0].innerText.trim() === String(scheduleId));

    if (row) {
        const scheduleId = row.cells[0].innerText.trim();
        const conductorName = row.cells[1].innerText.trim();
        const conductorId = row.cells[2].innerText.trim();
        const driverName = row.cells[3].innerText.trim();
        const driverId = row.cells[4].innerText.trim();

        const url = new URL('<?php echo URLROOT; ?>/SuperAdminPages/Addassigns');
        url.searchParams.append('scheduleId', scheduleId);
        url.searchParams.append('conductorName', conductorName);
        url.searchParams.append('conductor_id', conductorId);
        url.searchParams.append('driverName', driverName);
        url.searchParams.append('driver_id', driverId);

        window.location.href = url.toString();
    } else {
        alert("Assign not found.");
    }
}

function showPopup(message) {
    document.getElementById('popupMessage').innerText = message;
    document.getElementById('popupModal').style.display = 'flex';
}

function closePopup() {
    document.getElementById('popupModal').style.display = 'none';
    window.location.reload();
}
</script>

</body>
</html>
