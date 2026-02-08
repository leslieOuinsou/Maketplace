<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace</title>
    <style>
        :root {
            --primary: #007782; /* Vinted-ish teal */
            --secondary: #ebf1f3;
            --text-dark: #1e1e1e;
            --text-light: #757575;
            --white: #ffffff;
            --danger: #dc3545;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f6f7;
            color: var(--text-dark);
            overflow-x: hidden;
        }

        /* Navbar */
        .navbar {
            background-color: var(--white);
            padding: 0.8rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary);
            text-decoration: none;
        }
        .logo img {
            height: 40px;
            width: auto;
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text-light);
            font-weight: 500;
            transition: color 0.2s;
        }

        .nav-links a:hover {
            color: var(--primary);
        }
        .nav-messages {
            position: relative;
        }
        .msg-badge {
            position: absolute;
            top: -6px;
            right: -10px;
            background: var(--danger);
            color: white;
            font-size: 0.7rem;
            min-width: 18px;
            height: 18px;
            border-radius: 9px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
            cursor: pointer;
            border: 1px solid transparent;
        }

        .btn-primary {
            background-color: var(--primary);
            color: var(--white);
        }
        
        .btn-primary:hover {
            opacity: 0.9;
        }

        .btn-outline {
            border-color: var(--primary);
            color: var(--primary);
            background: transparent;
        }

        .btn-outline:hover {
            background-color: var(--secondary);
        }

        .user-avatar {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .user-initials {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
        }
        .user-menu {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .user-menu .btn-outline {
            padding: 0.4rem 0.8rem;
            font-size: 0.9rem;
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
            min-height: 80vh;
        }

        /* Auth pages - centrage */
        .auth-page {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(80vh - 4rem);
        }
        .auth-form {
            max-width: 400px;
            width: 100%;
            margin: 2rem auto;
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-light);
        }
        
        .form-control {
            width: 100%;
            padding: 0.6rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        img {
            max-width: 100%;
            height: auto;
        }

        h1, h2, h3 { color: var(--text-dark); }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar { flex-wrap: wrap; padding: 0.75rem 1rem; gap: 0.5rem; }
            .navbar .logo { font-size: 1.25rem; }
            .navbar .logo img { height: 32px; }
            .navbar .nav-links { width: 100%; order: 3; justify-content: center; padding-top: 0.5rem; border-top: 1px solid #eee; }
            .navbar .auth-buttons { order: 2; margin-left: auto; }
            .user-avatar { flex-wrap: wrap; gap: 0.5rem; }
            .user-menu .btn { padding: 0.35rem 0.6rem; font-size: 0.85rem; }
            .auth-buttons .btn-outline, .auth-buttons .btn-primary { padding: 0.4rem 0.75rem; font-size: 0.9rem; }
            .container { margin: 1rem auto; padding: 0 0.75rem; }
            .auth-form { margin: 1.5rem auto; padding: 1.25rem; }
        }
        @media (max-width: 480px) {
            .user-initials { width: 36px; height: 36px; font-size: 0.9rem; }
        }
    </style>
    <link rel="stylesheet" href="/css/responsive.css">
</head>
<body>
    <nav class="navbar">
        <a href="/" class="logo">
            <img src="/images/logo.png" alt="Marketplace">
            <span>Marketplace</span>
        </a>
        <div class="nav-links">
            <a href="/">Accueil</a>
            <a href="/search">Rechercher</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php $unreadCount = \App\Controllers\MessageController::getUnreadCount(); ?>
                <a href="/messages" class="nav-messages">
                    Messages
                    <?php if ($unreadCount > 0): ?>
                        <span class="msg-badge"><?= $unreadCount ?></span>
                    <?php endif; ?>
                </a>
            <?php endif; ?>
        </div>
        <div class="auth-buttons">
            <?php
            $initials = '?';
            if (isset($_SESSION['user_id'], $_SESSION['username'])) {
                $u = preg_split('/[\s_\.\-]+/', trim($_SESSION['username']), 2);
                $initials = strtoupper(mb_substr($u[0], 0, 1));
                if (isset($u[1])) $initials .= strtoupper(mb_substr($u[1], 0, 1));
            }
            ?>
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php
                $role = $_SESSION['role'] ?? 'les_deux';
                $canSell = in_array($role, ['vendeur', 'les_deux']);
                ?>
                <div class="user-avatar">
                    <span class="user-initials" title="<?= htmlspecialchars($_SESSION['username'] ?? '') ?> (<?= $role === 'acheteur' ? 'Acheteur' : ($role === 'vendeur' ? 'Vendeur' : 'Acheteur & Vendeur') ?>)"><?= htmlspecialchars($initials) ?></span>
                    <div class="user-menu">
                        <?php if ($canSell): ?>
                            <a href="/items/create" class="btn btn-primary">Vendre</a>
                        <?php endif; ?>
                        <a href="/logout" class="btn btn-outline">Déconnexion</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="/login" class="btn btn-outline">Se connecter</a>
                <a href="/register" class="btn btn-primary">S'inscrire</a>
            <?php endif; ?>
        </div>
    </nav>
    <div class="container">
