<?php
require_once __DIR__ . '/../config.php';

class MessageModel {
    private $pdo;

    public function __construct() {
        $this->pdo = config::getConnexion();
    }

    // ========== CREATE ==========
    public function getOrCreateConversation($user1, $user2) {
        $stmt = $this->pdo->prepare("SELECT id FROM conversations WHERE (user1_id = ? AND user2_id = ?) OR (user1_id = ? AND user2_id = ?)");
        $stmt->execute([$user1, $user2, $user2, $user1]);
        $conv = $stmt->fetch();
        if ($conv) return $conv['id'];

        $stmt = $this->pdo->prepare("INSERT INTO conversations (user1_id, user2_id) VALUES (?, ?)");
        $stmt->execute([$user1, $user2]);
        return $this->pdo->lastInsertId();
    }

    public function sendMessage($conversation_id, $sender_id, $content, $file_name = null) {
        $stmt = $this->pdo->prepare("INSERT INTO messages (conversation_id, sender_id, content, file_name) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$conversation_id, $sender_id, $content, $file_name]);
    }

    // ========== READ ==========
    public function getMessages($conversation_id) {
        $stmt = $this->pdo->prepare("SELECT m.*, u.nom as sender_name 
            FROM messages m 
            JOIN utilisateurs u ON m.sender_id = u.id 
            WHERE conversation_id = ? AND m.is_deleted = 0 
            ORDER BY m.created_at ASC");
        $stmt->execute([$conversation_id]);
        return $stmt->fetchAll();
    }

    public function getConversations($user_id) {
        $sql = "SELECT c.id, 
                       CASE WHEN c.user1_id = ? THEN c.user2_id ELSE c.user1_id END as other_user_id,
                       u.nom as other_user_name,
                       u.role as other_user_role,
                       (SELECT content FROM messages WHERE conversation_id = c.id AND is_deleted = 0 ORDER BY created_at DESC LIMIT 1) as last_message,
                       (SELECT created_at FROM messages WHERE conversation_id = c.id AND is_deleted = 0 ORDER BY created_at DESC LIMIT 1) as last_time,
                       (SELECT COUNT(*) FROM messages WHERE conversation_id = c.id AND sender_id != ? AND is_read = 0 AND is_deleted = 0) as unread
                FROM conversations c
                JOIN utilisateurs u ON u.id = (CASE WHEN c.user1_id = ? THEN c.user2_id ELSE c.user1_id END)
                WHERE (c.user1_id = ? OR c.user2_id = ?)
                ORDER BY last_time DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user_id, $user_id, $user_id, $user_id, $user_id]);
        return $stmt->fetchAll();
    }

    public function countUnreadMessages($user_id) {
        $sql = "SELECT COUNT(*) as total FROM messages m
                JOIN conversations c ON m.conversation_id = c.id
                WHERE (c.user1_id = ? OR c.user2_id = ?) 
                AND m.sender_id != ? 
                AND m.is_read = 0 
                AND m.is_deleted = 0";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user_id, $user_id, $user_id]);
        $result = $stmt->fetch();
        return $result['total'];
    }

    // ========== UPDATE ==========
    public function updateMessage($message_id, $sender_id, $new_content) {
        $stmt = $this->pdo->prepare("UPDATE messages SET content = ?, updated_at = NOW() WHERE id = ? AND sender_id = ? AND is_deleted = 0");
        return $stmt->execute([$new_content, $message_id, $sender_id]);
    }

    public function markAllAsRead($conversation_id, $user_id) {
        $stmt = $this->pdo->prepare("UPDATE messages SET is_read = 1 WHERE conversation_id = ? AND sender_id != ? AND is_deleted = 0");
        return $stmt->execute([$conversation_id, $user_id]);
    }

    // ========== DELETE ==========
    public function softDeleteMessage($message_id, $user_id) {
        $stmt = $this->pdo->prepare("UPDATE messages SET is_deleted = 1 WHERE id = ? AND sender_id = ?");
        return $stmt->execute([$message_id, $user_id]);
    }

    public function deleteConversation($conversation_id, $user_id) {
        $stmt = $this->pdo->prepare("SELECT id FROM conversations WHERE id = ? AND (user1_id = ? OR user2_id = ?)");
        $stmt->execute([$conversation_id, $user_id, $user_id]);
        if (!$stmt->fetch()) {
            return false;
        }
        $stmt = $this->pdo->prepare("UPDATE messages SET is_deleted = 1 WHERE conversation_id = ?");
        $stmt->execute([$conversation_id]);
        $stmt = $this->pdo->prepare("DELETE FROM conversations WHERE id = ?");
        return $stmt->execute([$conversation_id]);
    }
}
?>