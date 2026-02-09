<?php
namespace App\Controllers;

use App\Config\Database;
use App\Config\SampleItems;
use App\Utils\Upload;
use PDO;

class ItemController {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        $role = $_SESSION['role'] ?? 'les_deux';
        if ($role === 'acheteur') {
            header('Location: /?error=Votre compte est configuré comme acheteur uniquement');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? 0;
            $category_id = $_POST['category_id'] ?? null;
            $user_id = $_SESSION['user_id'];

            $image_url = null;
            $upload = new Upload();
            $uploadedUrl = $upload->handleImageUpload();
            if ($uploadedUrl !== null) {
                $image_url = $uploadedUrl;
            } elseif ($upload->getLastError() !== null) {
                header('Location: /items/create?error=' . urlencode($upload->getLastError()));
                exit;
            }

            if (empty($title) || empty($price)) {
                header('Location: /items/create?error=' . urlencode('Titre et prix requis'));
                exit;
            }

            $stmt = $this->db->prepare("INSERT INTO items (user_id, category_id, title, description, price, image_url) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$user_id, $category_id, $title, $description, $price, $image_url])) {
                $id = $this->db->lastInsertId();
                header("Location: /items/view?id=$id");
                exit;
            } else {
                header('Location: /items/create?error=' . urlencode('Erreur serveur'));
                exit;
            }
        }
    }

    public function show($id) {
        $item = null;

        // Essayer d'abord la base de données
        try {
            $stmt = $this->db->prepare("
                SELECT i.*, u.username, c.name as category_name 
                FROM items i 
                JOIN users u ON i.user_id = u.id 
                LEFT JOIN categories c ON i.category_id = c.id 
                WHERE i.id = ?");
            $stmt->execute([$id]);
            $item = $stmt->fetch();
            if ($item) {
                $item['is_sample'] = false;
                $item['is_owner'] = isset($_SESSION['user_id']) && (int)$item['user_id'] === (int)$_SESSION['user_id'];
                $item['can_edit'] = $item['is_owner'];
                $item['can_message'] = isset($_SESSION['user_id']) && !$item['is_owner'];
                $item['receiver_id'] = (int) $item['user_id'];
            }
        } catch (\Throwable $e) {
            // DB indisponible ou erreur
        }

        // Si pas trouvé en BDD, utiliser les annonces fictives (IDs 1001-1012)
        $idInt = (int) $id;
        if (!$item && $idInt >= 1001 && $idInt <= 1012) {
            $sample = SampleItems::getById($idInt);
            if ($sample) {
                $item = [
                    'id' => $sample['id'],
                    'title' => $sample['title'],
                    'description' => $sample['description'] ?? '',
                    'price' => $sample['price'],
                    'image_url' => $sample['image'] ?? null,
                    'category_name' => $sample['category'] ?? 'Autre',
                    'username' => 'Vendeur (annonce exemple)',
                    'is_sample' => true,
                    'is_owner' => false,
                    'can_edit' => isset($_SESSION['user_id']),
                    'can_message' => false,
                    'receiver_id' => null,
                ];
            }
        }

        if (!$item) {
            header('HTTP/1.0 404 Not Found');
            require __DIR__ . '/../../templates/404.php';
            exit;
        }

        require __DIR__ . '/../../templates/items/show.php';
    }

    // Helper to get categories for the form
    public function getCategories() {
        $stmt = $this->db->query("SELECT * FROM categories ORDER BY name");
        return $stmt->fetchAll();
    }

    /** Affiche le formulaire d'édition ou crée une copie si annonce fictive */
    public function edit($id) {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $idInt = (int) $id;
        $item = null;

        // Annonce fictive : créer une copie en BDD puis rediriger vers l'édition
        if ($idInt >= 1001 && $idInt <= 1012) {
            $sample = SampleItems::getById($idInt);
            if ($sample) {
                $categoryId = $this->getCategoryIdByName($sample['category'] ?? 'Autre');
                $stmt = $this->db->prepare("INSERT INTO items (user_id, category_id, title, description, price, image_url) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $_SESSION['user_id'],
                    $categoryId,
                    $sample['title'],
                    $sample['description'] ?? '',
                    $sample['price'],
                    $sample['image'] ?? null,
                ]);
                $newId = $this->db->lastInsertId();
                header("Location: /items/edit?id=$newId");
                exit;
            }
        }

        // Annonce en BDD
        $stmt = $this->db->prepare("SELECT * FROM items WHERE id = ? AND user_id = ?");
        $stmt->execute([$idInt, $_SESSION['user_id']]);
        $item = $stmt->fetch();

        if (!$item) {
            header('Location: /');
            exit;
        }

        $categories = $this->getCategories();
        require __DIR__ . '/../../templates/items/edit.php';
    }

    /** Met à jour une annonce (BDD uniquement) */
    public function update() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $id = (int) ($_POST['id'] ?? 0);
        if (!$id) {
            header('Location: /');
            exit;
        }

        $stmt = $this->db->prepare("SELECT id FROM items WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $_SESSION['user_id']]);
        if (!$stmt->fetch()) {
            header('Location: /');
            exit;
        }

        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $price = $_POST['price'] ?? 0;
        $category_id = $_POST['category_id'] ?? null;

        if (empty($title) || empty($price)) {
            header("Location: /items/edit?id=$id&error=Titre et prix requis");
            exit;
        }

        $image_url = null;
        $stmt = $this->db->prepare("SELECT image_url FROM items WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if ($row && $row['image_url']) {
            $image_url = $row['image_url'];
        }

        $upload = new Upload();
        $uploadedUrl = $upload->handleImageUpload();
        if ($uploadedUrl !== null) {
            $image_url = $uploadedUrl;
        } elseif ($upload->getLastError() !== null) {
            header('Location: /items/edit?id=' . (int)$id . '&error=' . urlencode($upload->getLastError()));
            exit;
        }

        $stmt = $this->db->prepare("UPDATE items SET title = ?, description = ?, price = ?, category_id = ?, image_url = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$title, $description, $price, $category_id ?: null, $image_url, $id, $_SESSION['user_id']]);

        header("Location: /items/view?id=$id");
        exit;
    }

    /** Supprime une annonce. BDD : suppression réelle. Fictive : masquage pour l'utilisateur. */
    public function delete($id) {
        $idInt = (int) $id;

        // Annonce fictive : masquer pour cet utilisateur
        if ($idInt >= 1001 && $idInt <= 1012) {
            if (!isset($_SESSION['hidden_samples'])) {
                $_SESSION['hidden_samples'] = [];
            }
            $_SESSION['hidden_samples'][$idInt] = true;
            header('Location: /search');
            exit;
        }

        // Annonce BDD : suppression si propriétaire
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $stmt = $this->db->prepare("DELETE FROM items WHERE id = ? AND user_id = ?");
        $stmt->execute([$idInt, $_SESSION['user_id']]);

        header('Location: /search');
        exit;
    }

    private function getCategoryIdByName(string $name): ?int {
        $stmt = $this->db->prepare("SELECT id FROM categories WHERE name = ?");
        $stmt->execute([$name]);
        $row = $stmt->fetch();
        if ($row) return (int) $row['id'];
        $stmt = $this->db->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->execute([$name]);
        return (int) $this->db->lastInsertId();
    }
}
