<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="container">
    <h1 style="margin-bottom: 1.5rem;">Mes messages</h1>

    <div style="display: grid; gap: 2rem;">
        <!-- Messages reçus (en tant que vendeur) -->
        <section>
            <h2 style="font-size: 1.25rem; margin-bottom: 0.25rem; color: var(--text-dark);">Reçus</h2>
            <p style="font-size: 0.9rem; color: var(--text-light); margin-bottom: 1rem;">Messages que des acheteurs vous ont envoyés sur vos annonces</p>
            <?php if (empty($received)): ?>
                <p style="color: var(--text-light); padding: 1.5rem; background: white; border-radius: 8px;">Aucun message reçu.</p>
            <?php else: ?>
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <?php foreach ($received as $m): ?>
                        <?php $itemUrl = !empty($m['item_id']) ? '/items/view?id=' . (int)$m['item_id'] : '#'; ?>
                        <a href="<?= $itemUrl ?>" style="display: block; background: white; padding: 1rem 1.25rem; border-radius: 8px; text-decoration: none; color: inherit; box-shadow: 0 1px 3px rgba(0,0,0,0.06); transition: box-shadow 0.2s;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 0.5rem;">
                                <div>
                                    <strong><?= htmlspecialchars($m['sender_name']) ?></strong>
                                    <span style="color: var(--text-light); font-size: 0.9rem;"> — À propos de <?= htmlspecialchars($m['item_title'] ?? 'cette annonce') ?></span>
                                </div>
                                <span style="font-size: 0.85rem; color: var(--text-light);"><?= date('d/m/Y H:i', strtotime($m['sent_at'])) ?></span>
                            </div>
                            <p style="margin: 0.5rem 0 0; color: var(--text-light); font-size: 0.95rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"><?= htmlspecialchars($m['content']) ?></p>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <!-- Messages envoyés (en tant qu'acheteur) -->
        <section>
            <h2 style="font-size: 1.25rem; margin-bottom: 0.25rem; color: var(--text-dark);">Envoyés</h2>
            <p style="font-size: 0.9rem; color: var(--text-light); margin-bottom: 1rem;">Messages que vous avez envoyés à des vendeurs</p>
            <?php if (empty($sent)): ?>
                <p style="color: var(--text-light); padding: 1.5rem; background: white; border-radius: 8px;">Aucun message envoyé.</p>
            <?php else: ?>
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <?php foreach ($sent as $m): ?>
                        <?php $itemUrl = !empty($m['item_id']) ? '/items/view?id=' . (int)$m['item_id'] : '#'; ?>
                        <a href="<?= $itemUrl ?>" style="display: block; background: white; padding: 1rem 1.25rem; border-radius: 8px; text-decoration: none; color: inherit; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 0.5rem;">
                                <div>
                                    <span style="color: var(--text-light); font-size: 0.9rem;">À </span><strong><?= htmlspecialchars($m['receiver_name']) ?></strong>
                                    <span style="color: var(--text-light); font-size: 0.9rem;"> — <?= htmlspecialchars($m['item_title'] ?? 'cette annonce') ?></span>
                                </div>
                                <span style="font-size: 0.85rem; color: var(--text-light);"><?= date('d/m/Y H:i', strtotime($m['sent_at'])) ?></span>
                            </div>
                            <p style="margin: 0.5rem 0 0; color: var(--text-light); font-size: 0.95rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"><?= htmlspecialchars($m['content']) ?></p>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
