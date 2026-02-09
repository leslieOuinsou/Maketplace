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
        <div class="msg-alert msg-error"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <form action="/items/create" method="POST" enctype="multipart/form-data" class="form-card form-enhanced">
        <div class="form-group">
            <label for="title">Titre <span class="required">*</span></label>
            <input 
                type="text" 
                id="title" 
                name="title" 
                class="form-control" 
                required 
                placeholder="ex: Jean Levi's 501"
                maxlength="100"
            >
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea 
                id="description" 
                name="description" 
                class="form-control" 
                rows="5" 
                placeholder="Décrivez votre article..."
                maxlength="1000"
            ></textarea>
        </div>

        <div class="form-group">
            <label for="category_id">Catégorie <span class="required">*</span></label>
            <select name="category_id" id="category_id" class="form-control" required>
                <option value="">-- Sélectionnez une catégorie --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="price">Prix (€) <span class="required">*</span></label>
            <input 
                type="number" 
                id="price" 
                name="price" 
                step="0.01" 
                min="0.01"
                max="999999.99"
                class="form-control" 
                required
                placeholder="0.00"
            >
        </div>

        <div class="form-group">
            <label for="image">Photo</label>
            <div class="file-upload-wrapper">
                <input 
                    type="file" 
                    id="image" 
                    name="image" 
                    class="form-control file-input" 
                    accept="image/*"
                >
                <div class="file-upload-hint">
                    📸 Glissez-déposez une image ou cliquez pour sélectionner (max 5MB)
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
            Publier l'annonce
        </button>
    </form>
</div>

<style>
.form-enhanced {
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

.required {
    color: #dc2626;
}

.file-upload-wrapper {
    position: relative;
}

.file-upload-hint {
    text-align: center;
    padding: 2rem;
    border: 2px dashed #cbd5e1;
    border-radius: 12px;
    color: var(--text-light);
    font-size: 0.95rem;
    margin-top: 0.5rem;
    transition: all 0.3s;
    cursor: pointer;
}

.file-upload-hint:hover {
    border-color: var(--primary);
    background: rgba(0, 119, 130, 0.05);
}

.file-input {
    position: absolute;
    opacity: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
}
</style>

<script>
// Drag & drop pour l'upload d'image
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('image');
    const uploadHint = document.querySelector('.file-upload-hint');
    
    if (fileInput && uploadHint) {
        // Clic sur la zone
        uploadHint.addEventListener('click', () => fileInput.click());
        
        // Drag & drop
        uploadHint.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadHint.style.borderColor = 'var(--primary)';
            uploadHint.style.background = 'rgba(0, 119, 130, 0.1)';
        });
        
        uploadHint.addEventListener('dragleave', () => {
            uploadHint.style.borderColor = '#cbd5e1';
            uploadHint.style.background = '';
        });
        
        uploadHint.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadHint.style.borderColor = '#cbd5e1';
            uploadHint.style.background = '';
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                // Déclencher l'événement change pour la prévisualisation
                fileInput.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });
    }
});
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>
