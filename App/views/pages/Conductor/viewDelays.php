<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Conductor' , 'Driver']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Delays</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Conductor/ViewDelays.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">
</head>
<body>
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/ConductorPages/informDelays';">Back</button>

    <h1>View Delays</h1>

    <table id="delays">
    <thead>
        <tr>
            <th>Delay ID</th>
            <th>Route Nuber</th>
            <th>Bus Number</th>
            <th>Departure Time</th>
            <th>New Departure Time</th>
            <th>Reason</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($data['delay'])): ?>
            <?php foreach ($data['delay'] as $delay): ?>
                <tr>
                    <td><?php echo htmlspecialchars($delay['delay_id']); ?></td>
                    <td><?php echo htmlspecialchars($delay['route_no']); ?></td>
                    <td><?php echo htmlspecialchars($delay['license_id']); ?></td>
                    <td><?php echo htmlspecialchars($delay['dep_time']); ?></td>
                    <td><?php echo htmlspecialchars($delay['new_dep_time']); ?></td>
                    <td><?php echo htmlspecialchars($delay['reason']); ?></td>
                </tr>
                
            <?php endforeach; ?>
        <?php endif;?>
    </tbody>
    </table>
</body>
</html>