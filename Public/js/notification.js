const URLROOT = 'http://localhost/TripTrack';

document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const bellIcon = document.getElementById('bell-icon');
    const notificationBox = document.getElementById('notification-box');
    const notificationList = document.getElementById('notification-list');
    
    // Toggle notification box visibility
    bellIcon.addEventListener('click', function(event) {
        event.stopPropagation();
        notificationBox.classList.toggle('active');
        
        // If opening the box, mark as seen (not read)
        if (notificationBox.classList.contains('active')) {
            markNotificationsAsSeen();
        }
    });
    
    // Close notification box when clicking outside
    document.addEventListener('click', function(event) {
        if (!notificationBox.contains(event.target) && !bellIcon.contains(event.target)) {
            notificationBox.classList.remove('active');
        }
    });
    
    // Handle dropdown arrows to expand/collapse notifications
    document.querySelectorAll('.dropdown-arrow').forEach(arrow => {
        arrow.addEventListener('click', function(event) {
            event.stopPropagation();
            const notificationItem = this.closest('.notifi-item');
            const content = notificationItem.querySelector('.notification-content');
            
            // Toggle content visibility
            if (content.classList.contains('active')) {
                content.classList.remove('active');
                this.innerHTML = '&#9660;'; // Down arrow
            } else {
                content.classList.add('active');
                this.innerHTML = '&#9650;'; // Up arrow
            }
        });
    });
    
    // Handle notification item clicks
    document.querySelectorAll('.notifi-item').forEach(item => {
        item.addEventListener('click', function(event) {
            // Don't process if clicking on controls
            if (event.target.closest('.notification-controls') || 
                event.target.closest('.notification-actions')) {
                return;
            }
            
            const notificationId = this.dataset.notificationId;
            const content = this.querySelector('.notification-content');
            const arrow = this.querySelector('.dropdown-arrow');
            
            // Toggle content
            if (!content.classList.contains('active')) {
                content.classList.add('active');
                arrow.innerHTML = '&#9650;'; // Up arrow
            }
        });
    });
    
    // Handle close icon to dismiss notifications
    document.querySelectorAll('.close-icon').forEach(closeIcon => {
        closeIcon.addEventListener('click', function(event) {
            event.stopPropagation();
            const notificationItem = this.closest('.notifi-item');
            const notificationId = notificationItem.dataset.notificationId;
            
            // Send AJAX request to dismiss notification
            dismissNotification(notificationId);
            
            // Remove notification from the UI
            notificationItem.remove();
            
            // Update the notification count
            updateNotificationCount();
        });
    });
    
    // Handle "Mark as read/unread" buttons
    document.querySelectorAll('.mark-read-btn').forEach(button => {
        button.addEventListener('click', function(event) {
            event.stopPropagation();
            const btn = this; // Store reference to button
            const notificationId = this.dataset.id;
            const notificationItem = this.closest('.notifi-item');
            
            // Toggle read status - check both classes to be certain
            const isCurrentlyRead = notificationItem.classList.contains('read');
            console.log('Notification read status before click:', isCurrentlyRead);
            
            // Send AJAX request to update read status
            updateReadStatus(notificationId, !isCurrentlyRead);
            
            // Update UI with explicit console logging
            console.log('Updating button text. Current text:', btn.textContent);
            if (isCurrentlyRead) {
                notificationItem.classList.remove('read');
                notificationItem.classList.add('unread');
                btn.textContent = 'Mark as read';
                console.log('Set button text to:', btn.textContent);
            } else {
                notificationItem.classList.remove('unread');
                notificationItem.classList.add('read');
                btn.textContent = 'Mark as unread';
                console.log('Set button text to:', btn.textContent);
            }
            
            // Update notification count
            updateNotificationCount();
        });
    });
    
    // Function to mark notifications as seen when opening the notification box
    function markNotificationsAsSeen() {
        // You can implement this with AJAX to mark notifications as "seen" on the server
        fetch(`${URLROOT}/RegisteredPages/markNotificationsAsSeen`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Notifications marked as seen');
            }
        })
        .catch(error => console.error('Error marking notifications as seen:', error));
    }
    
    // Modify the updateReadStatus function in notification.js to ensure proper UI update
    function updateReadStatus(notificationId, isRead) {
        fetch(`${URLROOT}/RegisteredPages/toggleReadStatus`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                notification_id: notificationId,
                is_read: isRead,
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log(`Notification ${notificationId} read status updated`);
                
                // Make sure updateNotificationCount is called after the UI is updated
                updateNotificationCount();
            }
        })
        .catch(error => console.error('Error updating notification read status:', error));
    }


    
    // Function to dismiss a notification
    function dismissNotification(notificationId) {
        fetch(`${URLROOT}/RegisteredPages/dismissNotification`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                notification_id: notificationId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log(`Notification ${notificationId} dismissed`);
            }
        })
        .catch(error => console.error('Error dismissing notification:', error));
    }
    
    // Function to update the notification count in the badge
    function updateNotificationCount() {
        const unreadCount = document.querySelectorAll('.notifi-item.unread').length;
        const badge = document.getElementById('notification-count');
        badge.textContent = unreadCount;
        
        // Hide badge if no unread notifications
        if (unreadCount === 0) {
            badge.style.display = 'none';
        } else {
            badge.style.display = 'block';
        }
    }
    
    // For the All Notifications page
    const urlParams = new URLSearchParams(window.location.search);
    const openNotificationId = urlParams.get('open');
    
    if (openNotificationId) {
        const targetNotification = document.querySelector(`.notifi-item[data-notification-id="${openNotificationId}"]`);
        if (targetNotification) {
            // Scroll to the notification
            targetNotification.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            // Expand the notification
            const content = targetNotification.querySelector('.notification-content');
            const arrow = targetNotification.querySelector('.dropdown-arrow');
            
            content.classList.add('active');
            arrow.innerHTML = '&#9650;'; // Up arrow
            
            // Add highlight effect
            targetNotification.classList.add('highlight');
            setTimeout(() => {
                targetNotification.classList.remove('highlight');
            }, 2000);
        }
    }
});