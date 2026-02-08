<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="auth-page">
<div class="auth-form">
    <a href="/" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; margin-bottom: 2rem; text-decoration: none;">
        <img src="/images/logo.png" alt="Marketplace" style="height: 60px;">
        <span style="font-size: 1.5rem; font-weight: bold; color: var(--primary);">Marketplace</span>
    </a>
    <h2 style="text-align: center; margin-bottom: 2rem;">Se connecter</h2>
    
    <?php if (isset($_GET['error'])): ?>
        <div style="background: #fdf2f2; color: #dc3545; padding: 10px; border-radius: 4px; margin-bottom: 1rem;">
            <?= htmlspecialchars($_GET['error']) ?>
        </div>
    <?php endif; ?>

    <form action="/login" method="POST">
        <?php if (!empty($_GET['redirect'])): ?>
            <input type="hidden" name="redirect" value="<?= htmlspecialchars($_GET['redirect']) ?>">
        <?php endif; ?>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Connexion</button>
    </form>
    
    <p style="text-align: center; margin-top: 1.5rem; color: #757575;">
        Pas encore de compte ? <a href="/register" style="color: var(--primary);">S'inscrire</a>
    </p>
</div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
