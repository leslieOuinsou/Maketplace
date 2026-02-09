<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="container">
    <?php if (isset($_GET['success'])): ?>
        <div class="msg-alert msg-success"><?= htmlspecialchars($_GET['success']) ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="msg-alert msg-error"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <a href="/search" class="back-link" style="margin-bottom: 1rem;">← Retour à la recherche</a>

    <div class="item-detail" style="display: flex; gap: 2rem; margin-top: 1rem; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); flex-wrap: wrap;">
        <div style="flex: 1; min-width: 280px;">
            <?php if (!empty($item['image_url'])): ?>
                <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" style="width: 100%; border-radius: 8px; object-fit: cover;">
            <?php else: ?>
                <div style="width: 100%; height: 350px; background: #eee; display: flex; align-items: center; justify-content: center; border-radius: 8px; color: #999;">Pas d'image</div>
            <?php endif; ?>
        </div>
        
        <div style="flex: 1; min-width: 280px;">
            <span style="background: var(--secondary); padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.9rem;">
                <?= htmlspecialchars($item['category_name'] ?? 'Autre') ?>
            </span>
            
            <h1 style="margin-top: 0.5rem; font-size: 1.75rem;"><?= htmlspecialchars($item['title']) ?></h1>
            <div style="font-size: 1.5rem; font-weight: bold; color: var(--primary); margin: 1rem 0;"><?= number_format((float)($item['price'] ?? 0), 2) ?> €</div>
            
            <p style="color: #757575;">Vendu par <strong><?= htmlspecialchars($item['username'] ?? 'Annonceur') ?></strong></p>
            
            <div style="margin: 2rem 0; line-height: 1.6;">
                <h3 style="font-size: 1rem; color: var(--text-light); margin-bottom: 0.5rem;">Description</h3>
                <p><?= nl2br(htmlspecialchars($item['description'] ?? '')) ?></p>
            </div>

            <div class="actions" style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                <button class="btn btn-primary">Acheter</button>
                <?php if (!empty($item['can_message'])): ?>
                    <button type="button" class="btn btn-outline" onclick="document.getElementById('messageModal').style.display='flex'">Envoyer un message</button>
                <?php elseif (empty($item['is_owner']) && empty($item['is_sample'])): ?>
                    <a href="/login?redirect=<?= urlencode('/items/view?id=' . ($item['id'] ?? '')) ?>" class="btn btn-outline">Envoyer un message (connexion requise)</a>
                <?php endif; ?>
                <?php if (!empty($item['can_edit'])): ?>
                    <a href="/items/edit?id=<?= (int)$item['id'] ?>" class="btn btn-outline">Modifier</a>
                    <a href="/items/delete?id=<?= (int)$item['id'] ?>" class="btn btn-outline" style="color: var(--danger); border-color: var(--danger);" onclick="return confirm('Supprimer cette annonce ?');">Supprimer</a>
                <?php endif; ?>
            </div>

            <?php if (!empty($item['is_sample'])): ?>
                <p style="margin-top: 1rem; font-size: 0.85rem; color: var(--text-light);">Ceci est une annonce fictive à titre d'exemple.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if (!empty($item['can_message'])): ?>
<div id="messageModal" class="modal" onclick="if(event.target===this)this.style.display='none'">
    <div class="modal-content">
        <span class="modal-close" onclick="document.getElementById('messageModal').style.display='none'">&times;</span>
        <h3>Envoyer un message à <?= htmlspecialchars($item['username'] ?? '') ?></h3>
        <p style="color: var(--text-light); margin-bottom: 1rem;">À propos de : <?= htmlspecialchars($item['title'] ?? '') ?></p>
        <form action="/messages/send" method="POST">
            <input type="hidden" name="item_id" value="<?= (int)($item['id'] ?? 0) ?>">
            <input type="hidden" name="receiver_id" value="<?= (int)($item['receiver_id'] ?? 0) ?>">
            <div class="form-group">
                <label for="messageContent">Votre message</label>
                <textarea id="messageContent" name="content" class="form-control" rows="4" required placeholder="Écrivez votre message..."></textarea>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" class="btn btn-primary">Envoyer</button>
                <button type="button" class="btn btn-outline" onclick="document.getElementById('messageModal').style.display='none'">Annuler</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<style>
.msg-alert { padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1rem; }
.msg-success { background: #d4edda; color: #155724; }
.msg-error { background: #f8d7da; color: #721c24; }
.modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; z-index: 2000; padding: 1rem; }
.modal-content { background: white; padding: 1.5rem; border-radius: 8px; max-width: 500px; width: 100%; position: relative; box-shadow: 0 4px 20px rgba(0,0,0,0.2); }
.modal-close { position: absolute; top: 1rem; right: 1rem; font-size: 1.5rem; cursor: pointer; color: #999; }
.modal-close:hover { color: #333; }
@media (max-width: 768px) {
    .item-detail { flex-direction: column !important; padding: 1rem !important; }
    .item-detail > div { min-width: 100% !important; }
    .item-detail h1 { font-size: 1.35rem !important; }
    .item-detail .actions {
        flex-direction: column;
        flex-wrap: nowrap;
        gap: 0.75rem;
        width: 100%;
    }
    .item-detail .actions .btn {
        width: 100%;
        max-width: 100%;
        min-height: 48px;
        justify-content: center;
        box-sizing: border-box;
        padding: 1rem 1.25rem;
    }
}
@media (max-width: 480px) {
    .item-detail .actions .btn {
        font-size: 1rem;
        padding: 1rem;
    }
}
</style>

<?php require __DIR__ . '/../layout/footer.php'; ?>
