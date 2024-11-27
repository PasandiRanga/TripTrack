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
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/Contacts.css?v=<?php echo time(); ?>">
</head>
<body>
    <!-- Back Button -->
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/home'">Back</button>

    <!-- Page Title -->
    <h2>Customer Support Requests</h2>

    <!-- Assignments Table -->
    <div class="contact-table-container">
        <table class="contact-table">
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact no</th>
                    <th>Message</th>
                    <th>User ID</th>
                    <th>Reply</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Example data (replace with actual database query results)
                    /*
                    $requests = [
                        ['request_id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com', 'contact_no' => '1234567890', 'message' => 'Need help with my account.', 'user_id' => 101],
                        ['request_id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com', 'contact_no' => '0987654321', 'message' => 'Issue with recent order.', 'user_id' => 102],
                    ]; */
                    if(isset($data['contact']) && is_array($data['contact'])){
                        foreach ($data['contact'] as $contact) {
                            echo "<tr>";
                            echo "<td>{$contact['Request_id']}</td>";
                            echo "<td>{$contact['name']}</td>";
                            echo "<td>{$contact['email']}</td>";
                            echo "<td>{$contact['contactNo']}</td>";
                            echo "<td>{$contact['message']}</td>";
                            echo "<td>{$contact['User_id']}</td>";
                            echo "<td><button class='reply-btn' onclick=\"window.location.href='mailto:{$contact['email']}?subject=Support%20Request%20Reply&body=Hello%20{$contact['name']},%0A%0ARegarding%20your%20message:%20{$contact['message']}%0A%0A---%0AReply%20here.'\">Reply</button></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='14'>No bus data available.</td></tr>";
                    }
                    
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Function to handle the Delete button click
        
    </script>
</body>
</html>
