<?php
namespace App\Controllers;

use App\Config\Database;
use App\Config\SampleItems;

class SearchController {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /** Récupère les articles depuis la base de données */
    private function getDbItems(?string $query = null): array {
        try {
            if ($query !== null && $query !== '') {
                $q = '%' . $query . '%';
                $stmt = $this->db->prepare("
                    SELECT i.id, i.title, i.price, i.image_url, c.name as category
                    FROM items i
                    LEFT JOIN categories c ON i.category_id = c.id
                    WHERE i.status = 'available'
                    AND (i.title LIKE ? OR c.name LIKE ?)
                    ORDER BY i.created_at DESC
                ");
                $stmt->execute([$q, $q]);
            } else {
                $stmt = $this->db->query("
                    SELECT i.id, i.title, i.price, i.image_url, c.name as category
                    FROM items i
                    LEFT JOIN categories c ON i.category_id = c.id
                    WHERE i.status = 'available'
                    ORDER BY i.created_at DESC
                ");
            }
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $items = [];
            $placeholder = 'https://picsum.photos/seed/placeholder/400/300';
            foreach ($rows as $row) {
                $items[] = [
                    'id' => (int) $row['id'],
                    'title' => $row['title'],
                    'price' => (float) $row['price'],
                    'category' => $row['category'] ?? 'Autre',
                    'image' => $row['image_url'] ?: $placeholder,
                ];
            }
            return $items;
        } catch (\Throwable $e) {
            return [];
        }
    }

    /** Exclut les annonces fictives masquées par l'utilisateur */
    private function filterHiddenSamples(array $items): array {
        $hidden = $_SESSION['hidden_samples'] ?? [];
        return array_values(array_filter($items, fn($i) => empty($hidden[(int)$i['id']])));
    }

    public function index() {
        $query = trim($_GET['q'] ?? '');
        $sampleItems = $this->filterHiddenSamples(SampleItems::getAll());

        // Articles créés par les utilisateurs (base de données)
        $dbItems = $this->getDbItems($query === '' ? null : $query);

        if ($query !== '') {
            $q = mb_strtolower($query);
            $filteredSamples = array_filter($sampleItems, function ($item) use ($q) {
                return str_contains(mb_strtolower($item['title']), $q)
                    || str_contains(mb_strtolower($item['category']), $q);
            });
            $sampleResults = array_values($filteredSamples);

            // Optionnel : enrichir avec le microservice Java
            $apiUrl = "http://java-service:8080/api/search?q=" . urlencode($query);
            $ctx = stream_context_create(['http' => ['timeout' => 2]]);
            $response = @file_get_contents($apiUrl, false, $ctx);
            if ($response) {
                $data = json_decode($response, true);
                $apiResults = $data['results'] ?? [];
                foreach ($apiResults as $r) {
                    $sampleResults[] = [
                        'id' => $r['id'] ?? 0,
                        'title' => $r['title'] ?? '',
                        'price' => $r['price'] ?? 0,
                        'category' => 'API',
                        'image' => 'https://picsum.photos/seed/api' . ($r['id'] ?? 0) . '/400/300',
                    ];
                }
            }

            // DB items en premier (annonces réelles), puis fictives
            $results = array_merge($dbItems, $sampleResults);
        } else {
            // Tous les articles : DB d'abord, puis fictifs
            $results = array_merge($dbItems, $sampleItems);
        }

        require __DIR__ . '/../../templates/search.php';
    }
}
