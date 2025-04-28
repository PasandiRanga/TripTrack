const URLROOT = 'http://localhost/TripTrack';

document.addEventListener('DOMContentLoaded', function() {
    const bellIcon = document.getElementById('bell-icon');
    const notificationBox = document.getElementById('notification-box');
    const notificationList = document.getElementById('notification-list');
    
    bellIcon.addEventListener('click', function(event) {
        event.stopPropagation();
        notificationBox.classList.toggle('active');
        
        if (notificationBox.classList.contains('active')) {
            markNotificationsAsSeen();
        }
    });
    
    document.addEventListener('click', function(event) {
        if (!notificationBox.contains(event.target) && !bellIcon.contains(event.target)) {
            notificationBox.classList.remove('active');
        }
    });
    
    document.querySelectorAll('.dropdown-arrow').forEach(arrow => {
        arrow.addEventListener('click', function(event) {
            event.stopPropagation();
            const notificationItem = this.closest('.notifi-item');
            const content = notificationItem.querySelector('.notification-content');
            
            if (content.classList.contains('active')) {
                content.classList.remove('active');
                this.innerHTML = '&#9660;'; 
            } else {
                content.classList.add('active');
                this.innerHTML = '&#9650;'; 
            }
        });
    });
    
    document.querySelectorAll('.notifi-item').forEach(item => {
        item.addEventListener('click', function(event) {
            if (event.target.closest('.notification-controls') || 
                event.target.closest('.notification-actions')) {
                return;
            }
            
            const notificationId = this.dataset.notificationId;
            const content = this.querySelector('.notification-content');
            const arrow = this.querySelector('.dropdown-arrow');
            
            if (!content.classList.contains('active')) {
                content.classList.add('active');
                arrow.innerHTML = '&#9650;'; 
            }
        });
    });
    
    document.querySelectorAll('.close-icon').forEach(closeIcon => {
        closeIcon.addEventListener('click', function(event) {
            event.stopPropagation();
            const notificationItem = this.closest('.notifi-item');
            const notificationId = notificationItem.dataset.notificationId;
            
            dismissNotification(notificationId);
            
            notificationItem.remove();
            
            updateNotificationCount();
        });
    });
    
    document.querySelectorAll('.mark-read-btn').forEach(button => {
        button.addEventListener('click', function(event) {
            event.stopPropagation();
            const btn = this; 
            const notificationId = this.dataset.id;
            const notificationItem = this.closest('.notifi-item');
            
            const isCurrentlyRead = notificationItem.classList.contains('read');
            console.log('Notification read status before click:', isCurrentlyRead);
            
            updateReadStatus(notificationId, !isCurrentlyRead);
            
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
            
            updateNotificationCount();
        });
    });
    
    function markNotificationsAsSeen() {
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
                updateNotificationCount();
            }
        })
        .catch(error => console.error('Error updating notification read status:', error));
    }


    
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
    
    function updateNotificationCount() {
        const unreadCount = document.querySelectorAll('.notifi-item.unread').length;
        const badge = document.getElementById('notification-count');
        badge.textContent = unreadCount;
        
        if (unreadCount === 0) {
            badge.style.display = 'none';
        } else {
            badge.style.display = 'block';
        }
    }
    
    const urlParams = new URLSearchParams(window.location.search);
    const openNotificationId = urlParams.get('open');
    
    if (openNotificationId) {
        const targetNotification = document.querySelector(`.notifi-item[data-notification-id="${openNotificationId}"]`);
        if (targetNotification) {
            targetNotification.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            const content = targetNotification.querySelector('.notification-content');
            const arrow = targetNotification.querySelector('.dropdown-arrow');
            
            content.classList.add('active');
            arrow.innerHTML = '&#9650;';
            
            targetNotification.classList.add('highlight');
            setTimeout(() => {
                targetNotification.classList.remove('highlight');
            }, 2000);
        }
    }
});