<?php 
require __DIR__ . '/../layout/header.php'; 
// Fetch categories directly or pass from controller. 
// For simplicity in this non-framework setup, we'll instantiate controller helper or use direct DB if needed.
// Better practice: Pass variable from router/controller.
// Let's assume we can use the controller helper statically or instantiate it.
$controller = new \App\Controllers\ItemController();
$categories = $controller->getCategories();
?>

<div class="container" style="max-width: 600px;">
    <h2>Vendre un article</h2>
    
    <?php if (isset($_GET['error'])): ?>
        <div style="background: #fdf2f2; color: #dc3545; padding: 10px; margin-bottom: 1rem;">
            <?= htmlspecialchars($_GET['error']) ?>
        </div>
    <?php endif; ?>

    <form action="/items/create" method="POST" enctype="multipart/form-data" style="background: white; padding: 2rem; border-radius: 8px;">
        <div class="form-group">
            <label for="title">Titre</label>
            <input type="text" id="title" name="title" class="form-control" required placeholder="ex: Jean Levi's 501">
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control" rows="5" placeholder="Décrivez votre article..."></textarea>
        </div>

        <div class="form-group">
            <label for="category_id">Catégorie</label>
            <select name="category_id" id="category_id" class="form-control">
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="price">Prix (€)</label>
            <input type="number" id="price" name="price" step="0.01" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="image">Photo</label>
            <input type="file" id="image" name="image" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Ajouter</button>
    </form>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
