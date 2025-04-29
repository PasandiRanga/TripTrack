<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Admin']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delay Notifications</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/Notifications.css?v=<?php echo time(); ?>">
</head>
<body>
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/home'">Back</button>

    <div class="container">
        <h2>Delay Notifications</h2>

        
        <div class="button-container">
            <button 
                class="send-notification-button" 
                onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/sendnotifications';">
                Send Notification
            </button>
        </div>

     
        <div class="table-wrapper">
            <table class="delay-table">
                <thead>
                    <tr>
                        <th>Delay ID</th>
                        <th>Schedule ID</th>
                        <th>Employee ID</th>
                        <th>Departure Time</th>
                        <th>New Departure Time</th>
                        <th>Reason</th>
                        <th>Date Time</th>
                        <th>Marked</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['delays'])): ?>
                        <?php foreach ($data['delays'] as $delay): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($delay['delay_id']); ?></td>
                                <td><?php echo htmlspecialchars($delay['schedule_id']); ?></td>
                                <td><?php echo htmlspecialchars($delay['employee_id']); ?></td>
                                <td><?php echo htmlspecialchars($delay['dep_time']); ?></td>
                                <td><?php echo htmlspecialchars($delay['new_dep_time']); ?></td>
                                <td><?php echo htmlspecialchars($delay['reason']); ?></td>
                                <td><?php echo htmlspecialchars($delay['created_at']); ?></td>
                                <td>
                                    <?php if ($delay['viewed'] == 0): ?>
                                        <form method="post" action="<?php echo URLROOT; ?>/SuperAdminPages/markDelays">
                                            <input type="hidden" name="delay_id" value="<?php echo $delay['delay_id']; ?>">
                                            <button type="submit" class="mark-button">Mark</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="marked-text">Marked</span>
                                    <?php endif; ?>
                                </td>
                                
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6">No delay notifications found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
