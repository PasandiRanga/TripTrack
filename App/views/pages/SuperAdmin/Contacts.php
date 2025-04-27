<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Admin']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Requests</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/Contacts.css?v=<?php echo time(); ?>">
</head>
<body>

    <!-- Back Button -->
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/home'">Back</button>

    <div class="box">
        <!-- Page Title -->
        <h2>Customer Support Requests</h2><br>

        <!-- Contact Requests in Card Style -->
        <div class="contact-table-container">
            <?php if (isset($data['contact']) && is_array($data['contact'])): ?>
                <?php foreach ($data['contact'] as $contact): ?>
                    <?php
                        $email = $contact['email'];
                        $name = $contact['name'];
                        $message = $contact['message'];
                        $subject = rawurlencode("Support Request Reply");
                        $body = rawurlencode("Hello {$name},\n\nRegarding your message:\n{$message}\n\n---\nReply - \n");
                        $mailto = "mailto:{$email}?subject={$subject}&body={$body}";
                        $replied = $contact['replied'] == 'Yes' || $contact['replied'] == 1;
                    ?>
                    <div class="contact-card <?php echo $replied ? 'replied-card' : ''; ?>">
                        <p><strong>Request ID:</strong> <?php echo $contact['Request_id']; ?></p>
                        <p><strong>Name:</strong> <?php echo $name; ?></p>
                        <p><strong>Email:</strong> <?php echo $email; ?></p>
                        <p><strong>Contact No:</strong> <?php echo $contact['contactNo']; ?></p>
                        <p><strong>Message:</strong> <?php echo $message; ?></p>
                        <p><strong>Replied:</strong> <?php echo $replied ? 'Yes' : 'No'; ?></p>

                        <?php if (!$replied): ?>
                            <button class="reply-btn" onclick="reply(this, '<?php echo $mailto; ?>', <?php echo $contact['Request_id']; ?>)">Reply</button>
                        <?php else: ?>
                            <button class="reply-btn replied" disabled>Replied</button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center;">No contact requests available.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Popup Overlay -->
    <div class="popup-overlay" id="popupOverlay" style="display: none;">
        <div class="popup-box">
            <p id="popupMessage"></p>
            <button class="ok-button" onclick="closePopup()">OK</button>
        </div>
    </div>

    <!-- JavaScript for reply + update -->
    <script>
        // Function to show the popup with a custom message
        function show(message) {
            document.getElementById('popupMessage').textContent = message;
            document.getElementById('popupOverlay').style.display = 'flex'; // Show the popup
        }

        // Function to close the popup
        function closePopup() {
            document.getElementById('popupOverlay').style.display = 'none'; // Hide the popup
        }

        // Reply to a contact request and mark it as replied
        function reply(button, mailtoUrl, requestId) {
            window.location.href = mailtoUrl; // Open the email client for replying

            button.textContent = 'Replied';
            button.disabled = true;
            button.classList.add('replied');

            const card = button.closest('.contact-card');
            card.classList.add('replied-card');
            card.querySelector('p:nth-last-child(2)').textContent = 'Replied: Yes';

            fetch('<?php echo URLROOT; ?>/SuperAdminPages/markReplied', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ request_id: requestId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    show("Marked as replied on the server."); // Show the popup with success message
                } else {
                    alert("Failed to mark as replied on the server.");
                }
            })
            .catch(err => {
                console.error("Error updating status:", err);
                show("An error occurred while updating the status."); // Show the popup with error message
            });
        }
    </script>

</body>
</html>
