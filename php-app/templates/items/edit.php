<?php
$categories = $categories ?? [];
$item = $item ?? [];
require __DIR__ . '/../layout/header.php';
?>

<div class="container" style="max-width: 600px;">
    <a href="/items/view?id=<?= (int)($item['id'] ?? 0) ?>" class="back-link" style="margin-bottom: 1rem;">← Retour à l'annonce</a>
    <h2>Modifier l'annonce</h2>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="msg-alert msg-error"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <form action="/items/update" method="POST" enctype="multipart/form-data" class="form-card">
        <input type="hidden" name="id" value="<?= (int)($item['id'] ?? 0) ?>">
        
        <div class="form-group">
            <label for="title">Titre</label>
            <input type="text" id="title" name="title" class="form-control" required value="<?= htmlspecialchars($item['title'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control" rows="5"><?= htmlspecialchars($item['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label for="category_id">Catégorie</label>
            <select name="category_id" id="category_id" class="form-control">
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= (isset($item['category_id']) && (int)$item['category_id'] === (int)$cat['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="price">Prix (€)</label>
            <input type="number" id="price" name="price" step="0.01" class="form-control" required value="<?= htmlspecialchars($item['price'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="image">Photo (laisser vide pour garder l'actuelle)</label>
            <?php if (!empty($item['image_url'])): ?>
                <p style="font-size: 0.9rem; color: var(--text-light); margin-bottom: 0.5rem;">Photo actuelle :</p>
                <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="" style="max-width: 150px; height: auto; border-radius: 4px; margin-bottom: 0.5rem;">
            <?php endif; ?>
            <input type="file" id="image" name="image" class="form-control" accept="image/*">
        </div>

        <div style="display: flex; gap: 0.75rem; margin-top: 1rem;">
            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
            <a href="/items/view?id=<?= (int)($item['id'] ?? 0) ?>" class="btn btn-outline">Annuler</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
