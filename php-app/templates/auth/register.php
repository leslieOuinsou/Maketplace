<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="auth-page">
<div class="auth-form auth-form-enhanced">
    <a href="/" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; margin-bottom: 2rem; text-decoration: none;">
        <img src="/images/logo.png" alt="Marketplace" style="height: 60px;">
        <span style="font-size: 1.5rem; font-weight: bold; color: var(--primary);">Marketplace</span>
    </a>
    <h2 style="text-align: center; margin-bottom: 2rem;">Créer un compte</h2>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="msg-alert msg-error"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <form action="/register" method="POST" id="register-form">
        <div class="form-group">
            <label for="username">Nom d'utilisateur <span class="required">*</span></label>
            <input 
                type="text" 
                id="username" 
                name="username" 
                class="form-control" 
                required
                minlength="3"
                maxlength="50"
                placeholder="JohnDoe"
                autocomplete="username"
            >
        </div>
        
        <div class="form-group">
            <label for="email">Email <span class="required">*</span></label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                class="form-control" 
                required
                placeholder="votre@email.com"
                autocomplete="email"
            >
        </div>
        
        <div class="form-group">
            <label for="password">Mot de passe <span class="required">*</span></label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                class="form-control" 
                required
                minlength="8"
                placeholder="••••••••"
                autocomplete="new-password"
            >
        </div>
        
        <div class="form-group">
            <label for="confirm_password">Confirmer le mot de passe <span class="required">*</span></label>
            <input 
                type="password" 
                id="confirm_password" 
                name="confirm_password" 
                class="form-control" 
                required
                minlength="8"
                placeholder="••••••••"
                autocomplete="new-password"
            >
        </div>
        
        <div class="form-group">
            <label for="role">Je suis sur Marketplace pour <span class="required">*</span></label>
            <select name="role" id="role" class="form-control" required>
                <option value="acheteur">Acheter uniquement</option>
                <option value="vendeur">Vendre uniquement</option>
                <option value="les_deux" selected>Acheter et vendre</option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
            S'inscrire
        </button>
    </form>
    
    <p style="text-align: center; margin-top: 1.5rem; color: #757575;">
        Déjà un compte ? <a href="/login" style="color: var(--primary);">Se connecter</a>
    </p>
</div>
</div>

<style>
.auth-form-enhanced {
    animation: slideUp 0.4s ease;
}

.required {
    color: #dc2626;
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
// Validation de confirmation de mot de passe
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('register-form');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    
    if (form && password && confirmPassword) {
        confirmPassword.addEventListener('input', function() {
            if (password.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity('Les mots de passe ne correspondent pas');
            } else {
                confirmPassword.setCustomValidity('');
            }
        });
        
        password.addEventListener('input', function() {
            if (confirmPassword.value && password.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity('Les mots de passe ne correspondent pas');
            } else {
                confirmPassword.setCustomValidity('');
            }
        });
    }
});
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>
