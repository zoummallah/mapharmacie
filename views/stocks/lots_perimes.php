<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="page-title">
    <span>Lots Périmés à Traiter</span>
    <div>
        <a href="<?= BASE_URL ?>/index.php?page=lots" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Retour aux Lots</a>
        <a href="<?= BASE_URL ?>/index.php?page=lots&action=historique" class="btn btn-secondary"><i class="fa-solid fa-history"></i> Historique</a>
    </div>
</div>

<div class="card">
    <div class="card-header" style="background: rgba(239, 68, 68, 0.08); border-bottom: 1px solid rgba(239, 68, 68, 0.15);">
        <span style="color: var(--danger); font-weight: 600;">
            <i class="fa-solid fa-circle-exclamation"></i> Lots dont la date de péremption est dépassée et nécessitant une destruction
        </span>
    </div>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Médicament</th>
                    <th>N° Lot</th>
                    <th>Date d'expiration</th>
                    <th>Quantité en Stock</th>
                    <?php if (estAdmin()): ?>
                        <th style="text-align: center;">Action</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($lots)): ?>
                    <tr>
                        <td colspan="<?= estAdmin() ? 5 : 4 ?>" style="text-align: center; padding: 3rem; color: var(--text-muted);">
                            <i class="fa-solid fa-shield-halved" style="color: var(--success); font-size: 2.5rem; display: block; margin-bottom: 1rem;"></i>
                            Aucun lot périmé avec du stock résiduel à traiter.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($lots as $l): ?>
                        <tr style="background: rgba(239, 68, 68, 0.02);">
                            <td style="font-weight: 600;"><?= htmlspecialchars($l['medicament_nom']) ?></td>
                            <td><code style="background: rgba(100, 116, 139, 0.1); padding: 2px 6px; border-radius: 4px; font-weight: 500;"><?= htmlspecialchars($l['numero_lot']) ?></code></td>
                            <td>
                                <span class="badge badge-danger" style="font-size: 0.85rem; padding: 0.35em 0.65em;">
                                    <i class="fa-solid fa-calendar-times"></i> <?= formatDate($l['date_expiration'], 'd/m/Y') ?> (Périmé)
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-primary" style="font-size: 0.9rem; padding: 0.35em 0.65em; background: #64748b;">
                                    <?= $l['quantite'] ?> unités
                                </span>
                            </td>
                            <?php if (estAdmin()): ?>
                                <td style="text-align: center;">
                                    <form action="<?= BASE_URL ?>/index.php?page=lots&action=perimes" method="POST" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir mettre ce lot (<?= htmlspecialchars($l['numero_lot']) ?>) au rebut ?\nCette action est irréversible, videra le stock restant et générera un mouvement de destruction.');">
                                        <input type="hidden" name="id_lot" value="<?= $l['id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" style="font-size: 0.85rem; padding: 0.4rem 0.8rem; display: inline-flex; align-items: center; gap: 0.4rem;">
                                            <i class="fa-solid fa-trash-can"></i> Mettre au rebut
                                        </button>
                                    </form>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
