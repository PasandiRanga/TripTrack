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
                    <th>Reply</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if (isset($data['contact']) && is_array($data['contact'])) {
                        foreach ($data['contact'] as $contact) {
                            $email = $contact['email'];
                            $name = $contact['name'];
                            $message = $contact['message'];

                            // Build a properly encoded mailto link with new lines
                            $subject = rawurlencode("Support Request Reply");
                            $body = rawurlencode("Hello {$name},\n\nRegarding your message:\n{$message}\n\n---\nReply here.");
                            $mailto = "mailto:{$email}?subject={$subject}&body={$body}";

                            echo "<tr>";
                            echo "<td>{$contact['Request_id']}</td>";
                            echo "<td>{$contact['name']}</td>";
                            echo "<td>{$contact['email']}</td>";
                            echo "<td>{$contact['contactNo']}</td>";
                            echo "<td>{$contact['message']}</td>";
                            echo "<td><button class='reply-btn' onclick=\"replyAndRemoveRow(this, '{$mailto}')\">Reply</button></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No contact requests available.</td></tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>

    <!-- JavaScript for reply + remove row -->
    <script>
        function replyAndRemoveRow(button, mailtoUrl) {
            // Open email client
            window.location.href = mailtoUrl;

            // Wait 1 second, then remove the row smoothly
            setTimeout(() => {
                const row = button.closest('tr');
                row.style.transition = 'opacity 0.3s ease-out';
                row.style.opacity = '0';
                setTimeout(() => row.remove(), 300);
            }, 1000);
        }
    </script>
</body>
</html>
