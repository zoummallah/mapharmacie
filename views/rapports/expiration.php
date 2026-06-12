<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="page-title">
    <span>Rapport : Périmés &amp; Expirations Proches</span>
    <button onclick="window.print()" class="btn btn-primary no-print">Imprimer</button>
</div>

<?php
// Compter séparément
$nb_perime = 0;
$nb_proche = 0;
foreach ($medicaments as $m) {
    if ($m['date_expiration'] < date('Y-m-d')) $nb_perime++;
    else $nb_proche++;
}
?>

<div class="form-row no-print" style="margin-bottom: 1.5rem; gap: 1rem;">
    <?php if ($nb_perime > 0): ?>
    <div style="padding: 1rem 1.5rem; background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.3); border-radius: var(--radius-md); flex: 1; display: flex; align-items: center; justify-content: space-between; gap: 0.75rem;">
        <div style="display: flex; align-items: center; gap: 0.75rem;">

            <div>
                <div style="font-size: 1.8rem; font-weight: 700; color: var(--danger);"><?= $nb_perime ?></div>
                <div style="font-size: 0.85rem; color: var(--text-muted);">Médicament(s) périmé(s) en stock</div>
            </div>
        </div>
        <?php if (estAdmin()): ?>
            <a href="<?= BASE_URL ?>/index.php?page=lots&action=perimes" class="btn btn-danger btn-sm" style="font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.4rem;">
                Mettre au rebut
            </a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    <?php if ($nb_proche > 0): ?>
    <div style="padding: 1rem 1.5rem; background: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.3); border-radius: var(--radius-md); flex: 1; display: flex; align-items: center; gap: 0.75rem;">

        <div>
            <div style="font-size: 1.8rem; font-weight: 700; color: var(--warning);"><?= $nb_proche ?></div>
            <div style="font-size: 0.85rem; color: var(--text-muted);">Expirant dans les 30 jours</div>
        </div>
    </div>
    <?php endif; ?>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Médicament</th>
                    <th>N° Lot par défaut</th>
                    <th>Date d'expiration</th>
                    <th>Stock global</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($medicaments)): ?>
                    <tr><td colspan="5" style="text-align: center; padding: 2rem;">

                        Aucun médicament périmé ni expirant dans les 30 prochains jours.
                    </td></tr>
                <?php else: ?>
                    <?php foreach($medicaments as $m):
                        $estPerime = $m['date_expiration'] < date('Y-m-d');
                    ?>
                    <tr style="<?= $estPerime ? 'background: rgba(239,68,68,0.04);' : '' ?>">
                        <td style="font-weight: 500;"><?= htmlspecialchars($m['nom']) ?></td>
                        <td style="color: var(--text-muted);"><?= htmlspecialchars($m['lot_numero'] ?? '—') ?></td>
                        <td>
                            <span class="badge <?= $estPerime ? 'badge-danger' : 'badge-warning' ?>">
                                <?= formatDate($m['date_expiration'], 'd/m/Y') ?>
                            </span>
                        </td>
                        <td><?= $m['quantite_stock'] ?></td>
                        <td>
                            <?php if ($estPerime): ?>
                                <span style="font-weight: 600; color: var(--danger); font-size: 0.8rem;">
                                    PÉRIMÉ
                                </span>
                            <?php else: ?>
                                <span style="font-weight: 600; color: var(--warning); font-size: 0.8rem;">
                                    Expire bientôt
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
