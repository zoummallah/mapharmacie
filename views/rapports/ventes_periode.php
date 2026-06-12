<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="page-title">
    <span>Rapport : Ventes par Période</span>
    <button onclick="window.print()" class="btn btn-primary"><i class="fa-solid fa-print"></i> Imprimer</button>
</div>

<div class="card no-print">
    <div class="card-header">
        <form action="<?= BASE_URL ?>/index.php" method="GET" style="display: flex; gap: 1rem; align-items: center;">
            <input type="hidden" name="page" value="rapports">
            <input type="hidden" name="action" value="ventes_periode">
            <input type="date" name="date_debut" class="form-control" value="<?= htmlspecialchars($date_debut) ?>" required>
            <span>à</span>
            <input type="date" name="date_fin" class="form-control" value="<?= htmlspecialchars($date_fin) ?>" required>
            <button type="submit" class="btn btn-secondary">Filtrer</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        Ventes du <?= formatDate($date_debut, 'd/m/Y') ?> au <?= formatDate($date_fin, 'd/m/Y') ?>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>N° Facture</th>
                    <th>Vendeur</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $ca_total = 0;
                if(empty($ventes)): ?>
                    <tr><td colspan="4" style="text-align: center;">Aucune vente sur cette période.</td></tr>
                <?php else: ?>
                    <?php foreach($ventes as $v): 
                        $ca_total += $v['montant_total'];
                    ?>
                    <tr>
                        <td><?= formatDate($v['date_vente'], 'd/m/Y H:i') ?></td>
                        <td><?= htmlspecialchars($v['numero_facture']) ?></td>
                        <td><?= htmlspecialchars($v['vendeur_nom']) ?></td>
                        <td style="font-weight: 500;"><?= formatMontant($v['montant_total']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" style="text-align: right; font-size: 1.2rem;">Chiffre d'Affaires Total :</th>
                    <th style="font-size: 1.2rem; color: var(--primary-color);"><?= formatMontant($ca_total) ?></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
