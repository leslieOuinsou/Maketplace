<?php
namespace App\Utils;

/**
 * Utilitaire d'upload sécurisé.
 * Gère la vérification du dossier, les permissions et les erreurs sans produire de sortie (évite "headers already sent").
 */
class Upload {
    private string $uploadDir;
    private int $maxSize = 5 * 1024 * 1024; // 5 Mo
    private array $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    private ?string $lastError = null;

    public function __construct(string $uploadDir = null) {
        $this->uploadDir = $uploadDir ?? dirname(__DIR__, 2) . '/public/uploads/';
    }

    /** Retourne la dernière erreur (sans afficher de warning) */
    public function getLastError(): ?string {
        return $this->lastError;
    }

    /**
     * Vérifie que le dossier existe, est accessible en écriture, et le crée si nécessaire.
     * Retourne true si OK, false sinon (sans produire de sortie).
     */
    public function ensureUploadDir(): bool {
        if (!is_dir($this->uploadDir)) {
            if (!@mkdir($this->uploadDir, 0775, true)) {
                $this->lastError = 'Impossible de créer le dossier uploads.';
                return false;
            }
        }
        if (!is_writable($this->uploadDir)) {
            $this->lastError = 'Le dossier uploads n\'est pas accessible en écriture.';
            return false;
        }
        return true;
    }

    /**
     * Upload un fichier image de $_FILES['image'].
     * Retourne l'URL publique relative (/uploads/xxx.jpg) ou null en cas d'erreur.
     */
    public function handleImageUpload(): ?string {
        $this->lastError = null;

        if (!isset($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
            return null; // Pas de fichier = upload optionnel, pas d'erreur
        }
        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $this->lastError = $this->getUploadErrorMessage($_FILES['image']['error']);
            return null;
        }

        $file = $_FILES['image'];

        if ($file['size'] > $this->maxSize) {
            $this->lastError = 'Fichier trop volumineux (max 5 Mo).';
            return null;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        if (!in_array($mime, $this->allowedTypes, true)) {
            $this->lastError = 'Type de fichier non autorisé (JPEG, PNG, GIF, WebP uniquement).';
            return null;
        }

        if (!$this->ensureUploadDir()) {
            return null;
        }

        $baseName = preg_replace('/[^a-zA-Z0-9\.\-_]/', '_', basename($file['name']));
        $filename = uniqid() . '-' . $baseName;
        $destPath = $this->uploadDir . $filename;

        if (!@move_uploaded_file($file['tmp_name'], $destPath)) {
            $this->lastError = 'Erreur lors de l\'enregistrement du fichier. Vérifiez les permissions du dossier uploads.';
            return null;
        }

        return '/uploads/' . $filename;
    }

    private function getUploadErrorMessage(int $code): string {
        $messages = [
            UPLOAD_ERR_INI_SIZE   => 'Fichier trop volumineux (limite serveur).',
            UPLOAD_ERR_FORM_SIZE  => 'Fichier trop volumineux.',
            UPLOAD_ERR_PARTIAL    => 'Upload partiel, réessayez.',
            UPLOAD_ERR_NO_FILE    => 'Aucun fichier sélectionné.',
            UPLOAD_ERR_NO_TMP_DIR => 'Dossier temporaire manquant.',
            UPLOAD_ERR_CANT_WRITE => 'Impossible d\'écrire le fichier.',
            UPLOAD_ERR_EXTENSION  => 'Extension bloquée.',
        ];
        return $messages[$code] ?? 'Erreur d\'upload inconnue.';
    }
}
