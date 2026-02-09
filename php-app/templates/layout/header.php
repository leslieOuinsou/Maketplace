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
            flex-wrap: nowrap;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary);
            text-decoration: none;
            flex-shrink: 0;
        }
        .logo img {
            height: 40px;
            width: auto;
        }

        .nav-wrapper {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text-light);
            font-weight: 500;
            transition: color 0.2s;
            white-space: nowrap;
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

        /* Hamburger - caché sur desktop */
        .hamburger {
            display: none;
            flex-direction: column;
            justify-content: center;
            gap: 5px;
            width: 44px;
            height: 44px;
            padding: 10px;
            background: none;
            border: none;
            cursor: pointer;
            -webkit-tap-highlight-color: transparent;
        }
        .hamburger span {
            display: block;
            width: 24px;
            height: 2px;
            background: var(--text-dark);
            border-radius: 2px;
            transition: transform 0.3s, opacity 0.3s;
        }
        .hamburger.active span:nth-child(1) {
            transform: translateY(7px) rotate(45deg);
        }
        .hamburger.active span:nth-child(2) {
            opacity: 0;
        }
        .hamburger.active span:nth-child(3) {
            transform: translateY(-7px) rotate(-45deg);
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
            cursor: pointer;
            border: 1px solid transparent;
            display: inline-flex;
            align-items: center;
            justify-content: center;
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

        /* Responsive - Mobile */
        @media (max-width: 768px) {
            .navbar { padding: 0.6rem 1rem; }
            .navbar-inner { flex-wrap: wrap; }
            .logo { font-size: 1.2rem; }
            .logo img { height: 32px; }
            .logo span { max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
            .hamburger { display: flex; }
            .nav-wrapper {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: var(--white);
                flex-direction: column;
                gap: 0;
                padding: 1rem;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                border-top: 1px solid #eee;
            }
            .nav-wrapper.open { display: flex; }
            .nav-links {
                flex-direction: column;
                width: 100%;
                gap: 0;
                padding-bottom: 1rem;
                border-bottom: 1px solid #eee;
                margin-bottom: 1rem;
            }
            .nav-links a {
                padding: 0.75rem 0;
                width: 100%;
                min-height: 44px;
                display: flex;
                align-items: center;
                font-size: 1rem;
                -webkit-tap-highlight-color: transparent;
            }
            .auth-buttons {
                width: 100%;
                display: flex;
                flex-direction: column;
                flex-wrap: nowrap;
                gap: 0.75rem;
            }
            .auth-buttons .btn,
            .auth-buttons .btn-primary,
            .auth-buttons .btn-outline {
                width: 100%;
                max-width: 100%;
                justify-content: center;
                min-height: 48px;
                font-size: 1rem;
                padding: 1rem 1.25rem;
                box-sizing: border-box;
            }
            .user-avatar {
                flex-direction: column;
                width: 100%;
                gap: 1rem;
                align-items: stretch;
            }
            .user-menu {
                flex-direction: column;
                gap: 0.5rem;
            }
            .user-menu .btn { width: 100%; justify-content: center; min-height: 44px; }
            .container { margin: 1rem auto; padding: 0 1rem; }
            .auth-form { margin: 1.5rem auto; padding: 1.25rem; }
        }
        @media (max-width: 600px) {
            .auth-buttons {
                flex-direction: column;
            }
            .auth-buttons .btn {
                width: 100%;
            }
        }
        @media (max-width: 480px) {
            .navbar { padding: 0.5rem 0.75rem; }
            .logo span { max-width: 100px; }
            .user-initials { width: 36px; height: 36px; font-size: 0.9rem; }
            .auth-buttons .btn {
                font-size: 1rem;
                padding: 1rem;
            }
        }
    </style>
    <link rel="stylesheet" href="/css/responsive.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-inner">
            <a href="/" class="logo">
                <img src="/images/logo.png" alt="Marketplace">
                <span>Marketplace</span>
            </a>
            <button type="button" class="hamburger" id="hamburger" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>
            <div class="nav-wrapper" id="nav-wrapper">
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
            </div>
        </div>
    </nav>
    <script>
        (function() {
            var h = document.getElementById('hamburger');
            var n = document.getElementById('nav-wrapper');
            if (h && n) {
                h.addEventListener('click', function() {
                    h.classList.toggle('active');
                    n.classList.toggle('open');
                    document.body.style.overflow = n.classList.contains('open') ? 'hidden' : '';
                });
                n.querySelectorAll('a').forEach(function(a) {
                    a.addEventListener('click', function() {
                        h.classList.remove('active');
                        n.classList.remove('open');
                        document.body.style.overflow = '';
                    });
                });
            }
        })();
    </script>
    <div class="container">
