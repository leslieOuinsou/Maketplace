<?php
namespace App\Controllers;

use App\Config\Database;
use PDO;

class AuthController {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            $role = $_POST['role'] ?? 'les_deux';
            if (!in_array($role, ['acheteur', 'vendeur', 'les_deux'])) $role = 'les_deux';

            // Basic Validation
            if (empty($username) || empty($email) || empty($password)) {
                header('Location: /register?error=Tous les champs sont requis');
                exit;
            }

            if ($password !== $confirm_password) {
                header('Location: /register?error=Les mots de passe ne correspondent pas');
                exit;
            }

            // Check if user exists
            $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
            $stmt->execute([$email, $username]);
            if ($stmt->fetch()) {
                header('Location: /register?error=Cet utilisateur existe déjà');
                exit;
            }

            // Create User
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $this->db->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)");
            try {
                $stmt->execute([$username, $email, $passwordHash, $role]);
                // Auto login or redirect to login
                header('Location: /login?success=Compte créé avec succès');
                exit;
            } catch (\Exception $e) {
                header('Location: /register?error=Erreur lors de la création du compte');
                exit;
            }
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'] ?? 'les_deux';
                $redirect = $_POST['redirect'] ?? $_GET['redirect'] ?? '/';
                if ($redirect && str_starts_with($redirect, '/')) {
                    header('Location: ' . $redirect);
                } else {
                    header('Location: /');
                }
                exit;
            } else {
                header('Location: /login?error=Identifiants incorrects');
                exit;
            }
        }
    }

    public function logout() {
        session_destroy();
        header('Location: /');
        exit;
    }
}
