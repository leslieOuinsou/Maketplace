<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="auth-page">
<div class="auth-form">
    <a href="/" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; margin-bottom: 2rem; text-decoration: none;">
        <img src="/images/logo.png" alt="Marketplace" style="height: 60px;">
        <span style="font-size: 1.5rem; font-weight: bold; color: var(--primary);">Marketplace</span>
    </a>
    <h2 style="text-align: center; margin-bottom: 2rem;">Créer un compte</h2>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="msg-alert msg-error"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <form action="/register" method="POST">
        <div class="form-group">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" id="username" name="username" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirmer le mot de passe</label>
            <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="role">Je suis sur Marketplace pour</label>
            <select name="role" id="role" class="form-control" required>
                <option value="acheteur">Acheter uniquement</option>
                <option value="vendeur">Vendre uniquement</option>
                <option value="les_deux" selected>Acheter et vendre</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">S'inscrire</button>
    </form>
    
    <p style="text-align: center; margin-top: 1.5rem; color: #757575;">
        Déjà un compte ? <a href="/login" style="color: var(--primary);">Se connecter</a>
    </p>
</div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
