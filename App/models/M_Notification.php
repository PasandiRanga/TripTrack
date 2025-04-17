<?php
// NotificationModel.php - Place this in your models folder

class M_Notification {
    private $db;
    
    public function __construct() {
        $this->db = new Database;
    }
    
    /**
     * Get all notifications for a user
     */
    public function getNewNotifications($userId) {
        $this->db->query('SELECT * FROM notifications WHERE user_id = :user_id AND is_dismissed=0 AND is_read = 0 ORDER BY created_at DESC');
        $this->db->bind(':user_id', $userId);
        
        $results = $this->db->resultSet();
        // echo '<script> console.log("Notifications : "' . json_encode($results) . '); </script>';
        // exit();
        
        return $results;
    }

    public function getAllUserNotifications($userId){
        $this->db->query('SELECT * FROM notifications WHERE user_id = :user_id AND is_deleted=0 ORDER BY created_at DESC');
        $this->db->bind(':user_id', $userId);
        
        $results = $this->db->resultSet();
        // echo '<script> console.log("Notifications : "' . json_encode($results) . '); </script>';
        // exit();
        
        return $results;
    }
    
    /**
     * Mark notifications as seen
     */
    public function markNotificationsAsSeen($userId) {
        $this->db->query('UPDATE notifications SET is_seen = 1 WHERE user_id = :user_id AND is_seen = 0');
        $this->db->bind(':user_id', $userId);
        
        return $this->db->execute();
    }
    
    /**
     * Update notification read status
     */
    public function updateReadStatus($notificationId, $isRead, $userId) {
        $this->db->query('UPDATE notifications SET is_read = :is_read WHERE id = :id AND user_id = :user_id');
        $this->db->bind(':is_read', $isRead ? 1 : 0);
        $this->db->bind(':id', $notificationId);
        $this->db->bind(':user_id', $userId);
        
        return $this->db->execute();
    }

    public function updateReadStatusOfAll($notiID , $userID){
        $this->db->query('UPDATE notifications SET is_read = :is_read WHERE id = :id AND user_id = :user_id');
        $this->db->bind(':is_read', 1);
        $this->db->bind(':id', $notiID);
        $this->db->bind(':user_id', $userID);
        return $this->db->execute();
    }
    
    /**
     * Dismiss notification
     */
    public function dismissNotification($notificationId, $userId) {
        $this->db->query('UPDATE notifications SET is_dismissed = 1 WHERE id = :id AND user_id = :user_id');
        $this->db->bind(':id', $notificationId);
        $this->db->bind(':user_id', $userId);
        
        return $this->db->execute();
    }

    public function deleteNotification($notificationId,$userId) {
            $this->db->query('UPDATE notifications SET is_deleted =1 WHERE id=:id AND user_id = :user_id');
            $this->db->bind(':id', $notificationId);
            $this->db->bind(':user_id', $userId);
            return $this->db->execute();    
    }
    
    /**
     * Format time to "time ago" format
     */
    private function formatTimeAgo($timestamp) {
        $datetime = new DateTime($timestamp);
        $now = new DateTime();
        $interval = $now->diff($datetime);
        
        if ($interval->y >= 1) {
            return $interval->y . ' year' . ($interval->y > 1 ? 's' : '') . ' ago';
        } else if ($interval->m >= 1) {
            return $interval->m . ' month' . ($interval->m > 1 ? 's' : '') . ' ago';
        } else if ($interval->d >= 1) {
            if ($interval->d >= 7) {
                $weeks = floor($interval->d / 7);
                return $weeks . ' week' . ($weeks > 1 ? 's' : '') . ' ago';
            } else {
                return $interval->d . ' day' . ($interval->d > 1 ? 's' : '') . ' ago';
            }
        } else if ($interval->h >= 1) {
            return $interval->h . ' hour' . ($interval->h > 1 ? 's' : '') . ' ago';
        } else if ($interval->i >= 1) {
            return $interval->i . ' minute' . ($interval->i > 1 ? 's' : '') . ' ago';
        } else {
            return 'Just now';
        }
    }
}