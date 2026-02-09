<?php require __DIR__ . '/layout/header.php'; ?>

<div class="error-404-page">
    <div class="error-404-content">
        <div class="error-404-animation">
            <svg width="200" height="200" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="100" cy="100" r="80" stroke="var(--primary)" stroke-width="4" opacity="0.2"/>
                <text x="100" y="120" font-size="72" font-weight="bold" fill="var(--primary)" text-anchor="middle">404</text>
            </svg>
        </div>
        
        <h1 style="font-size: 2rem; color: var(--text-dark); margin: 1.5rem 0 0.5rem;">Page non trouvée</h1>
        <p style="color: var(--text-light); margin-bottom: 2rem;">Oups ! La page que vous cherchez n'existe pas ou a été déplacée.</p>
        
        <div class="error-404-actions">
            <a href="/" class="btn btn-primary">
                ← Retour à l'accueil
            </a>
            <a href="/search" class="btn btn-outline">
                Parcourir les annonces
            </a>
        </div>
        
        <div class="error-404-suggestions">
            <h3 style="font-size: 1.1rem; margin: 2rem 0 1rem; color: var(--text-dark);">Suggestions :</h3>
            <ul style="list-style: none; padding: 0; color: var(--text-light);">
                <li style="margin: 0.5rem 0;">
                    <a href="/" style="color: var(--primary); text-decoration: none;">🏠 Accueil</a>
                </li>
                <li style="margin: 0.5rem 0;">
                    <a href="/search" style="color: var(--primary); text-decoration: none;">🔍 Rechercher des articles</a>
                </li>
                <li style="margin: 0.5rem 0;">
                    <a href="/items/create" style="color: var(--primary); text-decoration: none;">💰 Vendre un article</a>
                </li>
            </ul>
        </div>
    </div>
</div>

<style>
.error-404-page {
    min-height: 70vh;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 2rem 1rem;
}

.error-404-content {
    max-width: 500px;
    animation: fadeSlideUp 0.6s ease;
}

.error-404-animation svg {
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

@keyframes fadeSlideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.error-404-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.error-404-suggestions {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #e5e7eb;
}

.error-404-suggestions ul li a {
    transition: all 0.2s;
    display: inline-block;
}

.error-404-suggestions ul li a:hover {
    transform: translateX(5px);
}

@media (max-width: 480px) {
    .error-404-actions {
        flex-direction: column;
        width: 100%;
    }
    
    .error-404-actions .btn {
        width: 100%;
    }
}
</style>

<?php require __DIR__ . '/layout/footer.php'; ?>
