<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="page-title">
    <span>Rapport : Marge Bénéficiaire</span>
    <button onclick="window.print()" class="btn btn-primary"><i class="fa-solid fa-print"></i> Imprimer</button>
</div>

<div class="card no-print">
    <div class="card-header">
        <form action="<?= BASE_URL ?>/index.php" method="GET" style="display: flex; gap: 1rem; align-items: center;">
            <input type="hidden" name="page" value="rapports">
            <input type="hidden" name="action" value="marge">
            <input type="date" name="date_debut" class="form-control" value="<?= htmlspecialchars($date_debut) ?>" required>
            <span>à</span>
            <input type="date" name="date_fin" class="form-control" value="<?= htmlspecialchars($date_fin) ?>" required>
            <button type="submit" class="btn btn-secondary">Filtrer</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        Marges du <?= formatDate($date_debut, 'd/m/Y') ?> au <?= formatDate($date_fin, 'd/m/Y') ?>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Médicament</th>
                    <th>Quantité Vendue</th>
                    <th>Chiffre d'Affaires</th>
                    <th>Coût (Achat)</th>
                    <th>Marge Brute</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($marges)): ?>
                    <tr><td colspan="5" style="text-align: center;">Aucune vente sur cette période.</td></tr>
                <?php else: ?>
                    <?php foreach($marges as $m): ?>
                    <tr>
                        <td style="font-weight: 500;"><?= htmlspecialchars($m['nom']) ?></td>
                        <td><?= $m['qte_vendue'] ?></td>
                        <td><?= formatMontant($m['ca']) ?></td>
                        <td><?= formatMontant($m['cout']) ?></td>
                        <td style="font-weight: bold; color: var(--success);"><?= formatMontant($m['marge']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" style="text-align: right; font-size: 1.2rem;">Marge Brute Globale :</th>
                    <th style="font-size: 1.2rem; color: var(--success);"><?= formatMontant($marge_globale) ?></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
