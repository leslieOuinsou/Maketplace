<?php
$featuredItems = \App\Config\SampleItems::getAll();
$hidden = $_SESSION['hidden_samples'] ?? [];
$featuredItems = array_values(array_filter($featuredItems, fn($i) => empty($hidden[(int)$i['id']])));
$featuredItems = array_slice($featuredItems, 0, 6);
require __DIR__ . '/layout/header.php';
?>

<?php if (isset($_GET['error'])): ?>
<div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;"><?= htmlspecialchars($_GET['error']) ?></div>
<?php endif; ?>

<!-- Hero Section -->
<section class="hero-home">
    <div class="hero-bg" style="background-image: url('https://picsum.photos/seed/hero1/1920/800');"></div>
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1 class="hero-title">Vendez. Achetez. Revendez.</h1>
        <p class="hero-subtitle">La marketplace où vos affaires trouvent une seconde vie</p>
        <form action="/search" method="GET" class="hero-search">
            <input type="text" name="q" placeholder="Rechercher un article, une catégorie..." class="hero-search-input">
            <button type="submit" class="btn btn-primary hero-search-btn">Rechercher</button>
        </form>
        <div class="hero-ctas">
            <a href="/register" class="btn btn-primary btn-lg">Commencer à vendre</a>
            <a href="/search" class="btn btn-hero-outline">Parcourir les annonces</a>
        </div>
    </div>
</section>

<!-- Catégories -->
<section class="section categories-home">
    <h2 class="section-title">Explorer par catégorie</h2>
    <div class="categories-grid">
        <a href="/search?q=vêtements" class="category-card">
            <div class="category-img" style="background-image: url('https://picsum.photos/seed/cat1/600/400');"></div>
            <span class="category-label">Vêtements</span>
        </a>
        <a href="/search?q=électronique" class="category-card">
            <div class="category-img" style="background-image: url('https://picsum.photos/seed/cat2/600/400');"></div>
            <span class="category-label">Électronique</span>
        </a>
        <a href="/search?q=maison" class="category-card">
            <div class="category-img" style="background-image: url('https://picsum.photos/seed/cat3/600/400');"></div>
            <span class="category-label">Maison</span>
        </a>
        <a href="/search?q=divertissement" class="category-card">
            <div class="category-img" style="background-image: url('https://picsum.photos/seed/cat4/600/400');"></div>
            <span class="category-label">Divertissement</span>
        </a>
        <a href="/search?q=sport" class="category-card">
            <div class="category-img" style="background-image: url('https://picsum.photos/seed/cat5/600/400');"></div>
            <span class="category-label">Sport</span>
        </a>
    </div>
</section>

<!-- Tendances -->
<section class="section trends-home">
    <div class="section-header">
        <h2 class="section-title">À la une</h2>
        <a href="/search" class="link-more">Voir tout →</a>
    </div>
    <div class="items-grid">
        <?php foreach ($featuredItems as $item): ?>
        <a href="/items/view?id=<?= (int)$item['id'] ?>" class="item-card">
            <div class="item-card-img">
                <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
            </div>
            <div class="item-card-body">
                <span class="item-category"><?= htmlspecialchars($item['category']) ?></span>
                <h3 class="item-title"><?= htmlspecialchars($item['title']) ?></h3>
                <span class="item-price"><?= number_format($item['price'], 2) ?> €</span>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</section>

<!-- Comment ça marche -->
<section class="section steps-home">
    <h2 class="section-title">Comment ça marche ?</h2>
    <div class="steps-grid">
        <div class="step-card">
            <div class="step-num">1</div>
            <h3>Créez un compte</h3>
            <p>Inscrivez-vous en quelques clics pour commencer à vendre ou acheter.</p>
        </div>
        <div class="step-card">
            <div class="step-num">2</div>
            <h3>Déposez vos annonces</h3>
            <p>Photographiez vos articles et publiez-les en quelques minutes.</p>
        </div>
        <div class="step-card">
            <div class="step-num">3</div>
            <h3>Vendez en toute sérénité</h3>
            <p>Échangez avec les acheteurs et concluez vos ventes en toute sécurité.</p>
        </div>
    </div>
</section>

<!-- Stats / Confiance -->
<section class="section stats-home">
    <div class="stats-grid">
        <div class="stat-item">
            <span class="stat-value">+12 000</span>
            <span class="stat-label">Annonces actives</span>
        </div>
        <div class="stat-item">
            <span class="stat-value">+5 000</span>
            <span class="stat-label">Vendeurs inscrits</span>
        </div>
        <div class="stat-item">
            <span class="stat-value">98%</span>
            <span class="stat-label">Clients satisfaits</span>
        </div>
    </div>
</section>

<!-- CTA Final -->
<section class="section cta-home">
    <div class="cta-box">
        <h2>Prêt à faire du tri dans vos placards ?</h2>
        <p>Rejoignez des milliers de vendeurs et acheteurs sur Marketplace.</p>
        <a href="/register" class="btn btn-primary btn-lg">Créer mon compte gratuit</a>
    </div>
</section>

<style>
/* Hero */
.hero-home {
    position: relative;
    min-height: 520px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    margin: 0 -1rem;
}
.hero-bg {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    animation: kenburns 20s ease-in-out infinite alternate;
}
@keyframes kenburns {
    0% { transform: scale(1); }
    100% { transform: scale(1.08); }
}
.hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(0,119,130,0.85) 0%, rgba(0,80,90,0.75) 100%);
}
.hero-content {
    position: relative;
    z-index: 1;
    text-align: center;
    padding: 2rem 1rem;
    max-width: 700px;
}
.hero-title {
    font-size: clamp(2rem, 5vw, 3rem);
    font-weight: 800;
    color: white;
    margin: 0 0 0.5rem;
    letter-spacing: -0.02em;
    text-shadow: 0 2px 20px rgba(0,0,0,0.2);
}
.hero-subtitle {
    font-size: 1.2rem;
    color: rgba(255,255,255,0.95);
    margin-bottom: 2rem;
}
.hero-search {
    display: flex;
    max-width: 560px;
    margin: 0 auto 1.5rem;
    background: white;
    border-radius: 50px;
    overflow: hidden;
    box-shadow: 0 8px 30px rgba(0,0,0,0.2);
}
.hero-search-input {
    flex: 1;
    padding: 1rem 1.5rem;
    border: none;
    font-size: 1rem;
    outline: none;
}
.hero-search-btn {
    padding: 1rem 1.5rem;
    border-radius: 0;
}
.hero-ctas {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}
.btn-lg { padding: 0.9rem 2rem; font-size: 1.1rem; }
.btn-hero-outline {
    background: rgba(255,255,255,0.2);
    color: white;
    border: 2px solid white;
    padding: 0.9rem 2rem;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s;
}
.btn-hero-outline:hover { background: white; color: var(--primary); }

/* Sections */
.section {
    padding: 3rem 0;
}
.section-title {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    color: var(--text-dark);
}
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}
.link-more {
    color: var(--primary);
    text-decoration: none;
    font-weight: 500;
    transition: opacity 0.2s;
}
.link-more:hover { opacity: 0.8; }

/* Categories */
.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1rem;
}
.category-card {
    position: relative;
    aspect-ratio: 1;
    border-radius: 12px;
    overflow: hidden;
    text-decoration: none;
    display: block;
    transition: transform 0.3s;
}
.category-card:hover { transform: translateY(-6px); }
.category-img {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    transition: transform 0.5s;
}
.category-card:hover .category-img { transform: scale(1.08); }
.category-label {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 1rem;
    background: linear-gradient(transparent, rgba(0,0,0,0.7));
    color: white;
    font-weight: 600;
    font-size: 1rem;
}

/* Items grid */
.items-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1.25rem;
}
.item-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    text-decoration: none;
    color: inherit;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    transition: transform 0.2s, box-shadow 0.2s;
}
.item-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.1);
}
.item-card-img {
    aspect-ratio: 1;
    overflow: hidden;
}
.item-card-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.item-card-body { padding: 1rem; }
.item-category {
    font-size: 0.75rem;
    color: var(--text-light);
    text-transform: uppercase;
}
.item-title {
    margin: 0.25rem 0 0.5rem;
    font-size: 1rem;
    font-weight: 600;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.item-price {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--primary);
}

/* Steps */
.steps-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 2rem;
}
.step-card {
    text-align: center;
    padding: 1.5rem;
}
.step-num {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: var(--primary);
    color: white;
    font-weight: 700;
    font-size: 1.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}
.step-card h3 {
    margin: 0 0 0.5rem;
    font-size: 1.1rem;
}
.step-card p {
    margin: 0;
    color: var(--text-light);
    font-size: 0.95rem;
    line-height: 1.5;
}

/* Stats */
.stats-home {
    background: linear-gradient(135deg, var(--primary) 0%, #005a64 100%);
    margin: 0 -1rem;
    padding: 3rem 1rem !important;
}
.stats-grid {
    display: flex;
    justify-content: center;
    gap: 3rem;
    flex-wrap: wrap;
}
.stat-item {
    text-align: center;
    color: white;
}
.stat-value {
    display: block;
    font-size: 2rem;
    font-weight: 800;
}
.stat-label {
    font-size: 0.95rem;
    opacity: 0.9;
}

/* CTA box */
.cta-home {
    padding: 4rem 0;
}
.cta-box {
    text-align: center;
    background: white;
    padding: 3rem 2rem;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}
.cta-box h2 {
    margin: 0 0 0.5rem;
    font-size: 1.5rem;
}
.cta-box p {
    margin: 0 0 1.5rem;
    color: var(--text-light);
}

/* Responsive mobile */
@media (max-width: 768px) {
    .hero-home {
        min-height: 420px;
        margin: 0;
    }
    .hero-content {
        padding: 1.5rem 0.75rem;
    }
    .hero-title {
        font-size: 1.6rem;
    }
    .hero-subtitle {
        font-size: 1rem;
        margin-bottom: 1.5rem;
    }
    .hero-search {
        flex-direction: column;
        border-radius: 12px;
        margin: 0 auto 1rem;
    }
    .hero-search-input {
        padding: 0.9rem 1rem;
    }
    .hero-search-btn {
        width: 100%;
    }
    .hero-ctas {
        flex-direction: column;
        gap: 0.75rem;
    }
    .hero-ctas .btn {
        width: 100%;
        text-align: center;
    }
    .section {
        padding: 2rem 0;
    }
    .section-title {
        font-size: 1.4rem;
    }
    .categories-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
    }
    .category-label {
        font-size: 0.9rem;
    }
    .items-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
    }
    .item-card-body {
        padding: 0.75rem;
    }
    .item-title {
        font-size: 0.9rem;
    }
    .item-price {
        font-size: 1rem;
    }
    .steps-grid {
        flex-direction: column;
        gap: 1.5rem;
    }
    .stats-grid {
        gap: 2rem;
    }
    .stat-value {
        font-size: 1.5rem;
    }
    .stat-label {
        font-size: 0.85rem;
    }
    .stats-home {
        padding: 2rem 0.75rem !important;
    }
    .cta-box {
        padding: 2rem 1rem;
    }
    .cta-box h2 {
        font-size: 1.25rem;
    }
}
@media (max-width: 480px) {
    .hero-home {
        min-height: 380px;
    }
    .hero-title {
        font-size: 1.4rem;
    }
    .categories-grid {
        grid-template-columns: 1fr;
    }
    .items-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php require __DIR__ . '/layout/footer.php'; ?>
