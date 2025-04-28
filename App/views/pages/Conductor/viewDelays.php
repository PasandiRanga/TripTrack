<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Conductor', 'Driver']);
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
                <th>Schedule ID</th>
                <th>Bus Number</th>
                <th>Bus Route</th>
                <th>Departure Time</th>
                <th>New Departure Time</th>
                <th>Reason</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($data['delays'])): ?>
                <?php
                // Prepare lookup arrays for schedules and buses for faster access
                $scheduleMap = [];
                foreach ($data['schedules'] as $schedule) {
                    $scheduleMap[$schedule['scheduleId']] = $schedule;
                }

                $busMap = [];
                foreach ($data['buses'] as $bus) {
                    $busMap[$bus['License_id']] = $bus;
                }
                ?>

                <?php foreach ($data['delays'] as $delay): ?>
                    <?php
                    $schedule = isset($scheduleMap[$delay['schedule_id']]) ? $scheduleMap[$delay['schedule_id']] : null;
                    $bus = ($schedule && isset($busMap[$schedule['License_id']])) ? $busMap[$schedule['License_id']] : null;
                    ?>

                    <?php if ($schedule && $bus): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($delay['schedule_id']); ?></td>
                            <td><?php echo htmlspecialchars($bus['License_id']); ?></td>
                            <td><?php echo htmlspecialchars($bus['start_location']); ?> - <?php echo htmlspecialchars($bus['destination']); ?></td>
                            <td><?php echo htmlspecialchars($delay['dep_time']); ?></td>
                            <td><?php echo htmlspecialchars($delay['new_dep_time']); ?></td>
                            <td><?php echo htmlspecialchars($delay['reason']); ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
