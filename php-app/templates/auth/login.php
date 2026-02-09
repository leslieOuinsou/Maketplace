<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="auth-page">
<div class="auth-form auth-form-enhanced">
    <a href="/" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; margin-bottom: 2rem; text-decoration: none;">
        <img src="/images/logo.png" alt="Marketplace" style="height: 60px;">
        <span style="font-size: 1.5rem; font-weight: bold; color: var(--primary);">Marketplace</span>
    </a>
    <h2 style="text-align: center; margin-bottom: 2rem;">Se connecter</h2>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="msg-alert msg-error"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="msg-alert msg-success"><?= htmlspecialchars($_GET['success']) ?></div>
    <?php endif; ?>

    <form action="/login" method="POST" id="login-form">
        <?php if (!empty($_GET['redirect'])): ?>
            <input type="hidden" name="redirect" value="<?= htmlspecialchars($_GET['redirect']) ?>">
        <?php endif; ?>
        
        <div class="form-group">
            <label for="email">Email</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                class="form-control" 
                required 
                autocomplete="email"
                placeholder="votre@email.com"
                aria-describedby="email-error"
            >
        </div>
        
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                class="form-control" 
                required
                autocomplete="current-password"
                placeholder="••••••••"
                aria-describedby="password-error"
            >
        </div>
        
        <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
            Connexion
        </button>
    </form>
    
    <p style="text-align: center; margin-top: 1.5rem; color: #757575;">
        Pas encore de compte ? <a href="/register" style="color: var(--primary);">S'inscrire</a>
    </p>
</div>
</div>

<style>
.auth-form-enhanced {
    animation: slideUp 0.4s ease;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<script>
// Afficher un message de succès si présent
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('success')) {
        Toast.success(urlParams.get('success'));
    }
    if (urlParams.get('error')) {
        Toast.error(urlParams.get('error'));
    }
});
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>
