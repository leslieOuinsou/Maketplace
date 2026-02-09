<?php require __DIR__ . '/layout/header.php'; ?>

<div class="container search-page">
    <div class="search-header">
        <h1 class="fade-in">Recherche Avancée</h1>
        <p class="search-subtitle fade-in">Trouvez l'article parfait parmi des milliers d'annonces</p>
    </div>

    <form action="/search" method="GET" class="search-form-modern">
        <div class="input-group search-input-group">
            <span class="input-group-icon">🔍</span>
            <input 
                type="text" 
                name="q" 
                class="form-control input-modern" 
                placeholder="Ex: vêtements, iPhone, canapé, sport..." 
                value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
                autocomplete="off"
            >
        </div>
        <button type="submit" class="btn btn-gradient btn-icon">
            <span>Rechercher</span>
        </button>
    </form>

    <div class="search-results-header">
        <h3>
            <?php if (!empty($_GET['q'])): ?>
                <span class="badge badge-primary"><?= count($results) ?></span>
                Résultats pour "<?= htmlspecialchars($_GET['q']) ?>"
            <?php else: ?>
                <span class="badge badge-info"><?= count($results) ?></span>
                Tous les articles à découvrir
            <?php endif; ?>
        </h3>
    </div>

    <?php if (empty($results)): ?>
        <div class="empty-state-modern">
            <div class="empty-state-icon">🔍</div>
            <h3>Aucun résultat trouvé</h3>
            <p class="empty-state-hint">Essayez avec d'autres termes de recherche</p>
            <div class="empty-state-suggestions">
                <p style="font-weight: 600; margin-bottom: 0.75rem;">Suggestions populaires :</p>
                <div class="suggestion-tags">
                    <a href="/search?q=vêtements" class="badge badge-outline">Vêtements</a>
                    <a href="/search?q=électronique" class="badge badge-outline">Électronique</a>
                    <a href="/search?q=maison" class="badge badge-outline">Maison</a>
                    <a href="/search?q=sport" class="badge badge-outline">Sport</a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="search-grid-modern">
            <?php foreach ($results as $index => $item): ?>
                <div class="card-modern item-card-search" style="animation-delay: <?= $index * 0.05 ?>s">
                    <a href="/items/view?id=<?= (int)($item['id']) ?>" class="item-link">
                        <div class="item-image-wrapper">
                            <img 
                                src="<?= htmlspecialchars($item['image'] ?? '') ?>" 
                                alt="<?= htmlspecialchars($item['title']) ?>"
                                loading="lazy"
                            >
                            <div class="item-overlay">
                                <span class="overlay-badge">Voir l'annonce →</span>
                            </div>
                        </div>
                        <div class="item-content">
                            <span class="item-category badge badge-outline">
                                <?= htmlspecialchars($item['category'] ?? '') ?>
                            </span>
                            <h4 class="item-title"><?= htmlspecialchars($item['title']) ?></h4>
                            <div class="item-price"><?= number_format((float)($item['price'] ?? 0), 2) ?> €</div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.search-page {
    animation: fadeIn 0.4s ease;
}

.search-header {
    text-align: center;
    margin-bottom: 2rem;
}

.search-subtitle {
    color: var(--text-light);
    font-size: 1.1rem;
    margin-top: 0.5rem;
}

.search-form-modern {
    max-width: 700px;
    margin: 0 auto 3rem;
    display: flex;
    gap: 1rem;
    align-items: stretch;
}

.search-input-group {
    flex: 1;
}

.search-results-header {
    margin-bottom: 2rem;
}

.search-results-header h3 {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 1.25rem;
}

/* Empty State */
.empty-state-modern {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 20px;
    box-shadow: var(--shadow-md);
    max-width: 600px;
    margin: 2rem auto;
    animation: scaleIn 0.4s ease;
}

.empty-state-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-state-modern h3 {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
    color: var(--text-dark);
}

.empty-state-hint {
    color: var(--text-light);
    font-size: 1rem;
    margin-bottom: 2rem;
}

.empty-state-suggestions {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid var(--border-light);
}

.suggestion-tags {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
    flex-wrap: wrap;
}

.suggestion-tags .badge {
    cursor: pointer;
    transition: all 0.2s;
}

.suggestion-tags .badge:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
}

/* Search Grid */
.search-grid-modern {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
}

.item-card-search {
    animation: slideUp 0.4s ease both;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.item-card-search:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-xl);
}

.item-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.item-image-wrapper {
    position: relative;
    height: 220px;
    overflow: hidden;
    border-radius: 12px;
    margin-bottom: 1rem;
    background: var(--bg-tertiary);
}

.item-image-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.item-card-search:hover .item-image-wrapper img {
    transform: scale(1.1);
}

.item-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0, 119, 130, 0.9), transparent 60%);
    display: flex;
    align-items: flex-end;
    justify-content: center;
    padding: 1.5rem;
    opacity: 0;
    transition: opacity 0.3s;
}

.item-card-search:hover .item-overlay {
    opacity: 1;
}

.overlay-badge {
    background: white;
    color: var(--primary);
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.9rem;
    transform: translateY(10px);
    transition: transform 0.3s;
}

.item-card-search:hover .overlay-badge {
    transform: translateY(0);
}

.item-content {
    padding: 0 0.5rem;
}

.item-category {
    font-size: 0.7rem;
    margin-bottom: 0.5rem;
}

.item-title {
    margin: 0.5rem 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-dark);
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.4;
}

.item-price {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--primary);
    margin-top: 0.75rem;
}

/* Responsive */
@media (max-width: 768px) {
    .search-form-modern {
        flex-direction: column;
    }
    
    .search-grid-modern {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    
    .item-image-wrapper {
        height: 180px;
    }
}

@media (max-width: 480px) {
    .search-grid-modern {
        grid-template-columns: 1fr;
    }
    
    .search-header h1 {
        font-size: 1.75rem;
    }
    
    .search-subtitle {
        font-size: 1rem;
    }
}
</style>

<script>
// Debounce pour la recherche en temps réel (optionnel)
let searchTimeout;
const searchInput = document.querySelector('.search-input-group input');

if (searchInput) {
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        // Optionnel: implémenter la recherche en temps réel ici
    });
}
</script>

<?php require __DIR__ . '/layout/footer.php'; ?>
