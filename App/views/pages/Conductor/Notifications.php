<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Conductor' , 'Driver']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Conductor/Notifications.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar/navbar.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=ABeeZee&display=swap" rel="stylesheet">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
</head>

<body>
<script>
    var userRole = <?php echo json_encode($_SESSION['userRole'] ?? 'Conductor'); ?>;
    localStorage.setItem('userRole', userRole);
</script>

<?php
    $Allnotifications = $data['allnotifications'] ?? [];
    $newnotifications = $data['newnotifications'] ??[];

    // Retrieve user role from session or set to a default value
    $userRole = $_SESSION['userRole'] ?? 'Conductor';
    $data = [
        'currentController' => 'ConductorPages', // Adjust this based on your controller
        'currentMethod' => 'notifications', // Adjust this based on the method
        'userRole' => $userRole
    ];
?>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/ConductorPages/newhome'">Back</button>

    <div class="all-notifications-container">
        <div class="all-notifications-heading">
            <h1>All Notifications</h1>
            <p class="subtitle">Stay up to date with all your travel updates and alerts</p>
        </div>
        
        <div class="notification-filters">
            <div class="notification-search">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="notification-search" placeholder="      Search notifications...">
            </div>
            <div class="filter-buttons">
                <button id="filter-all" class="active">All</button>
                <button id="filter-unread">Unread</button>
                <button id="filter-read">Read</button>
                <button id="mark-all-read">Mark All Read</button>
            </div>
        </div>

        <div class="notification-lists detailed" id="all-notification-list">
        <?php if (!empty($Allnotifications)): ?>
            <?php foreach ($Allnotifications as $Anotification): ?>
                <div class="notifi-items <?php echo $Anotification['is_read'] ? 'read' : 'unread'; ?>" 
                     data-notification-id="<?php echo $Anotification['id']; ?>">
                    <div class="notification-headers">
                        <?php if (!$Anotification['is_read']): ?>
                            <div class="unread-indicator"></div>
                        <?php endif; ?>
                        <div class="notification-icon">
                            <i class="fas fa-bell"></i>
                        </div>
                        <div class="notification-info">
                            <div class="notification-titles"><?php echo $Anotification['title']; ?></div>
                            <div class="notification-times"><?php echo $Anotification['created_at']; ?></div>
                        </div>
                        <div class="notification-controlsall">
                            <span class="dropdown-arrows">&#9660;</span>
                        </div>
                    </div>
                    <div class="notification-contents">
                        <p><?php echo $Anotification['message']; ?></p>
                        <div class="notification-actionsall">
                            <button class="mark-read-btn" data-id="<?php echo $Anotification['id']; ?>">
                                <i class="fas <?php echo $Anotification['is_read'] ? 'fa-envelope' : 'fa-envelope-open'; ?>"></i>
                                Mark as <?php echo $Anotification['is_read'] ? 'unread' : 'read'; ?>
                            </button>
                            <?php if (isset($Anotification['link'])): ?>
                                <a href="<?php echo $Anotification['link']; ?>" class="notification-action-btn">
                                    <i class="fas fa-external-link-alt"></i> View Details
                                </a>
                            <?php endif; ?>
                            <button class="delete-notification-btn" data-id="<?php echo $Anotification['id']; ?>">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-notifications">
                <div class="empty-state">
                    <i class="fas fa-bell-slash empty-icon"></i>
                    <p>You have no notifications at the moment.</p>
                </div>
            </div>
        <?php endif; ?>
        </div>

        <div class="pagination-container">
        <?php if (!empty($data['pagination']) && $data['pagination']['totalPages'] > 1): ?>
            <div class="pagination">
                <?php if ($data['pagination']['currentPage'] > 1): ?>
                    <a href="?page=<?php echo ($data['pagination']['currentPage'] - 1); ?>" class="pagination-arrow">&laquo; Prev</a>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $data['pagination']['totalPages']; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" class="pagination-number <?php echo ($i == $data['pagination']['currentPage']) ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
                
                <?php if ($data['pagination']['currentPage'] < $data['pagination']['totalPages']): ?>
                    <a href="?page=<?php echo ($data['pagination']['currentPage'] + 1); ?>" class="pagination-arrow">Next &raquo;</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        </div>
    </div>

    <script>

    const URLROOT = 'http://localhost/TripTrack';

    document.addEventListener('DOMContentLoaded', function() {
        const filterAll = document.getElementById('filter-all');
        const filterUnread = document.getElementById('filter-unread');
        const filterRead = document.getElementById('filter-read');
        const markAllRead = document.getElementById('mark-all-read');
        const searchInput = document.getElementById('notification-search');
        const notificationItems = document.querySelectorAll('.notifi-items');

        function setActiveFilter(activeButton) {
            [filterAll, filterUnread, filterRead].forEach(button => {
                button.classList.remove('active');
            });
            activeButton.classList.add('active');
        }

        function filterNotifications() {
            const searchText = searchInput.value.toLowerCase();
            const activeFilter = document.querySelector('.filter-buttons button.active').id;
            
            notificationItems.forEach(item => {
                const title = item.querySelector('.notification-titles').textContent.toLowerCase();
                const content = item.querySelector('.notification-contents p').textContent.toLowerCase();
                const isRead = item.classList.contains('read');
                
                const matchesSearch = title.includes(searchText) || content.includes(searchText);
                let matchesFilter = true;
                
                if (activeFilter === 'filter-unread') {
                    matchesFilter = !isRead;
                } else if (activeFilter === 'filter-read') {
                    matchesFilter = isRead;
                }
                
                if (matchesSearch && matchesFilter) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        document.querySelectorAll('.notification-headers').forEach(header => {
            header.addEventListener('click', function() {
                const item = this.parentElement;
                const content = item.querySelector('.notification-contents');
                const arrow = item.querySelector('.dropdown-arrows');
                
                content.classList.toggle('active');
                
                if (content.classList.contains('active')) {
                    arrow.innerHTML = '&#9650;';
                } else {
                    arrow.innerHTML = '&#9660;';
                }
            });
        });

        document.querySelectorAll('.mark-read-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                console.log("Button clicked");
                e.stopPropagation();
                
                const id = this.getAttribute('data-id');
                const item = this.closest('.notifi-items');
                const isCurrentlyRead = item.classList.contains('read');
                console.log("id: ",id)
                console.log("item: ",item)
                console.log("isCurrentlyRead: ",isCurrentlyRead)
                
                item.classList.toggle('read');
                item.classList.toggle('unread');
                
                this.innerHTML = isCurrentlyRead ? 
                    '<i class="fas fa-envelope-open"></i> Mark as read' : 
                    '<i class="fas fa-envelope"></i> Mark as unread';
                
                const indicator = item.querySelector('.unread-indicator');
                if (isCurrentlyRead) {
                    if (!indicator) {
                        const newIndicator = document.createElement('div');
                        newIndicator.className = 'unread-indicator';
                        item.querySelector('.notification-headers').prepend(newIndicator);
                    }
                } else {
                    if (indicator) {
                        indicator.remove();
                    }
                }

                 // Check if we're in the unread filter view and hide the item if it's now read
                const activeFilter = document.querySelector('.filter-buttons button.active').id;
                if (activeFilter === 'filter-unread' && !isCurrentlyRead) {
                    // If we're in unread filter and marking as read, hide this item
                    item.style.display = 'none';
                } else if (activeFilter === 'filter-read' && isCurrentlyRead) {
                    // If we're in read filter and marking as unread, hide this item
                    item.style.display = 'none';
                }

                fetch(`${URLROOT}/ConductorPages/toggleReadStatus`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'  // Add this header
                    },
                    body: JSON.stringify({ 
                        notification_id: id,
                        is_read: !isCurrentlyRead 
                    })
                })
                .then(response => {
                    // Check if response is empty
                    if (response.status === 204) {
                        console.log('Empty response with status 204');
                        return {success: true}; // Handle no-content response
                    }
                        
                    // Log the raw response for debugging
                    response.clone().text().then(text => {
                        console.log('Raw server response:', text);
                    });
                        
                    return response.json();
                })
                .then(data => {
                    if (!data.success) {
                        console.error('Failed to update notification status');
                        // Revert changes if failed
                        item.classList.toggle('read');
                        item.classList.toggle('unread');

                        if (activeFilter === 'filter-unread' || activeFilter === 'filter-read') {
                            item.style.display = 'block';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error type :', error.name);
                    console.error('Error message:', error.message);

                    // Revert changes on error
                    item.classList.toggle('read');
                    item.classList.toggle('unread');

                    if (activeFilter === 'filter-unread' || activeFilter === 'filter-read') {
                        item.style.display = 'block';
                    }
                });
            });
        });

        // Delete notification functionality
        document.querySelectorAll('.delete-notification-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation(); // Prevent triggering the parent click event
                
                const id = this.getAttribute('data-id');
                const item = this.closest('.notifi-items');
                
                if (confirm('Are you sure you want to delete this notification?')) {
                    // Delete animation
                    item.classList.add('deleting');

                    // Delete from server via AJAX
                    fetch(`${URLROOT}/ConductorPages/deleteNotification`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            notification_id: id
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove the notification from the UI
                            setTimeout(() => {
                                item.remove();
                                
                                // Check if there are no more notifications
                                if (document.querySelectorAll('.notifi-items').length === 0) {
                                    const noNotifications = document.createElement('div');
                                    noNotifications.className = 'no-notifications';
                                    noNotifications.innerHTML = `
                                        <div class="empty-state">
                                            <i class="fas fa-bell-slash empty-icon"></i>
                                            <p>You have no notifications at the moment.</p>
                                        </div>
                                    `;
                                    document.getElementById('all-notification-list').appendChild(noNotifications);
                                }
                            }, 300);
                        } else {
                            item.classList.remove('deleting');
                            alert('Failed to delete notification. Please try again.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        item.classList.remove('deleting');
                        alert('An error occurred while deleting the notification. Please try again.');
                    });
                }
            });
        });

        // Mark all as read functionality
        markAllRead.addEventListener('click', function() {
            const unreadItems = document.querySelectorAll('.notifi-items.unread');
            
            if (unreadItems.length === 0) {
                alert('No unread notifications to mark');
                return;
            }
            
            const unreadIds = Array.from(unreadItems).map(item => item.getAttribute('data-notification-id'));
            
            // Update UI first for responsive feel
            unreadItems.forEach(item => {
                item.classList.remove('unread');
                item.classList.add('read');
                
                // Update button text
                const markBtn = item.querySelector('.mark-read-btn');
                markBtn.innerHTML = '<i class="fas fa-envelope"></i> Mark as unread';
                
                // Remove unread indicator
                const indicator = item.querySelector('.unread-indicator');
                if (indicator) {
                    indicator.remove();
                }
            });

            // Send to server
            fetch(`${URLROOT}/ConductorPages/markAllAsRead`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'  // Add this for consistency
                },
                body: JSON.stringify({ ids: unreadIds })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    console.error('Failed to mark all as read');
                    // Could revert changes if needed
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
        
        // Add event listeners to filter buttons
        filterAll.addEventListener('click', function() {
            setActiveFilter(this);
            filterNotifications();
        });
        
        filterUnread.addEventListener('click', function() {
            setActiveFilter(this);
            filterNotifications();
        });
        
        filterRead.addEventListener('click', function() {
            setActiveFilter(this);
            filterNotifications();
        });
        
        // Add event listener to search input
        searchInput.addEventListener('input', filterNotifications);
        
        // Apply initial filtering
        filterNotifications();
        
        // Auto-expand the notification specified in the URL if any
        const urlParams = new URLSearchParams(window.location.search);
        const openNotificationId = urlParams.get('open');

        if (openNotificationId) {
            const targetNotification = document.querySelector(`.notifi-items[data-notification-id="${openNotificationId}"]`);
            if (targetNotification) {
                // Expand the notification
                const content = targetNotification.querySelector('.notification-contents');
                const arrow = targetNotification.querySelector('.dropdown-arrows');
                
                content.classList.add('active');
                arrow.innerHTML = '&#9650;'; // Up arrow
                
                // Mark as read if it was unread
                if (targetNotification.classList.contains('unread')) {
                    const markReadBtn = targetNotification.querySelector('.mark-read-btn');
                    markReadBtn.click(); // Simulate click on the mark as read button
                }
                
                // Scroll to the notification
                setTimeout(() => {
                    targetNotification.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    
                    // Add highlight effect
                    targetNotification.classList.add('highlight');
                    setTimeout(() => {
                        targetNotification.classList.remove('highlight');
                    }, 2000);
                }, 300);
            }
        }
    });
    </script>

</body>
</html>

            