<?php
namespace App\Controllers;

use App\Config\Database;

class MessageController {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /** Nombre de messages non lus pour l'utilisateur connecté */
    public static function getUnreadCount(): int {
        if (!isset($_SESSION['user_id'])) return 0;
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT COUNT(*) FROM messages WHERE receiver_id = ? AND is_read = 0");
            $stmt->execute([$_SESSION['user_id']]);
            return (int) $stmt->fetchColumn();
        } catch (\Throwable $e) {
            return 0;
        }
    }

    /** Liste des messages reçus et envoyés */
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $userId = (int) $_SESSION['user_id'];

        // Messages reçus (en tant que vendeur/receiver)
        $stmt = $this->db->prepare("
            SELECT m.*, u.username as sender_name, i.title as item_title, i.id as item_id
            FROM messages m
            JOIN users u ON m.sender_id = u.id
            LEFT JOIN items i ON m.item_id = i.id
            WHERE m.receiver_id = ?
            ORDER BY m.sent_at DESC
        ");
        $stmt->execute([$userId]);
        $received = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Marquer comme lus
        $this->db->prepare("UPDATE messages SET is_read = 1 WHERE receiver_id = ?")->execute([$userId]);

        // Messages envoyés (en tant qu'acheteur/sender)
        $stmt = $this->db->prepare("
            SELECT m.*, u.username as receiver_name, i.title as item_title, i.id as item_id
            FROM messages m
            JOIN users u ON m.receiver_id = u.id
            LEFT JOIN items i ON m.item_id = i.id
            WHERE m.sender_id = ?
            ORDER BY m.sent_at DESC
        ");
        $stmt->execute([$userId]);
        $sent = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        require __DIR__ . '/../../templates/messages/index.php';
    }

    public function send() {
        if (!isset($_SESSION['user_id'])) {
            $item_id = (int) ($_POST['item_id'] ?? 0);
            $redirect = $item_id ? '/items/view?id=' . $item_id : '/';
            header('Location: /login?redirect=' . urlencode($redirect));
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /');
            exit;
        }

        $item_id = (int) ($_POST['item_id'] ?? 0);
        $receiver_id = (int) ($_POST['receiver_id'] ?? 0);
        $content = trim($_POST['content'] ?? '');

        if (!$item_id || !$receiver_id || $content === '') {
            header('Location: /items/view?id=' . $item_id . '&error=Message invalide');
            exit;
        }

        if ($receiver_id === (int) $_SESSION['user_id']) {
            header('Location: /items/view?id=' . $item_id . '&error=Vous ne pouvez pas vous envoyer un message à vous-même');
            exit;
        }

        $stmt = $this->db->prepare("SELECT user_id FROM items WHERE id = ?");
        $stmt->execute([$item_id]);
        $item = $stmt->fetch();
        if (!$item || (int) $item['user_id'] !== $receiver_id) {
            header('Location: /items/view?id=' . $item_id . '&error=Destinataire invalide');
            exit;
        }

        $stmt = $this->db->prepare("INSERT INTO messages (sender_id, receiver_id, item_id, content) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $receiver_id, $item_id, $content]);

        header('Location: /items/view?id=' . $item_id . '&success=Votre message a été envoyé');
        exit;
    }
}
