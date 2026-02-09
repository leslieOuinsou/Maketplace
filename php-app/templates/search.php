<?php require __DIR__ . '/layout/header.php'; ?>

<div class="container">
    <div style="text-align: center; margin-bottom: 2rem;">
        <h1>Recherche Avancée</h1>
    </div>

    <form action="/search" method="GET" class="search-form" style="max-width: 600px; margin: 0 auto 3rem; display: flex; gap: 10px;">
        <input type="text" name="q" class="form-control" placeholder="Ex: vêtements, iPhone, canapé, sport..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
        <button type="submit" class="btn btn-primary">Rechercher</button>
    </form>

    <h3 style="margin-bottom: 1rem;">
        <?php if (!empty($_GET['q'])): ?>
            Résultats pour "<?= htmlspecialchars($_GET['q']) ?>" (<?= count($results) ?>)
        <?php else: ?>
            Tous les articles à découvrir
        <?php endif; ?>
    </h3>

    <?php if (empty($results)): ?>
        <div class="empty-state">
            <p>Aucun résultat trouvé.</p>
            <p class="empty-state-hint">Essayez avec d'autres termes : vêtements, électronique, maison, sport...</p>
        </div>
    <?php else: ?>
        <div class="search-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1.5rem;">
            <?php foreach ($results as $item): ?>
                <div class="card search-card" style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); transition: transform 0.2s;">
                    <a href="/items/view?id=<?= (int)($item['id']) ?>" style="text-decoration: none; color: inherit;">
                        <div style="height: 200px; overflow: hidden; background: #eee;">
                            <img src="<?= htmlspecialchars($item['image'] ?? '') ?>" alt="<?= htmlspecialchars($item['title']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div style="padding: 1rem;">
                            <span style="font-size: 0.75rem; color: var(--text-light); text-transform: uppercase;"><?= htmlspecialchars($item['category'] ?? '') ?></span>
                            <h4 style="margin: 0.3rem 0 0.5rem; font-size: 1.1rem;"><?= htmlspecialchars($item['title']) ?></h4>
                            <div style="font-weight: bold; color: var(--primary);"><?= number_format((float)($item['price'] ?? 0), 2) ?> €</div>
                            <span class="btn btn-outline" style="display: block; text-align: center; margin-top: 1rem; width: 100%; box-sizing: border-box;">Voir l'annonce</span>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.search-card:hover { transform: translateY(-6px); box-shadow: 0 8px 24px rgba(0,0,0,0.1); }
.empty-state {
    text-align: center;
    padding: 3rem 2rem;
    background: white;
    border-radius: 14px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06);
}
.empty-state p { margin: 0.5rem 0; }
.empty-state-hint { color: var(--text-light); font-size: 0.95rem; }
@media (max-width: 768px) {
    .search-form { flex-direction: column !important; }
    .search-form .btn { width: 100%; }
    .search-grid { grid-template-columns: repeat(2, 1fr) !important; gap: 1rem !important; }
}
@media (max-width: 480px) {
    .search-grid { grid-template-columns: 1fr !important; }
}
</style>

<?php require __DIR__ . '/layout/footer.php'; ?>
