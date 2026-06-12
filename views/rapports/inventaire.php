<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="page-title">
    <span>Rapport : Inventaire Complet</span>
    <button onclick="window.print()" class="btn btn-primary"><i class="fa-solid fa-print"></i> Imprimer</button>
</div>

<div class="stats-grid no-print">
    <div class="stat-card">
        <div class="stat-icon" style="color: var(--warning); background: rgba(245, 158, 11, 0.1);"><i class="fa-solid fa-coins"></i></div>
        <div class="stat-info">
            <h3>Valeur d'Achat Totale</h3>
            <div class="value"><?= formatMontant($valeur_totale_achat) ?></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="color: var(--primary-color); background: rgba(13, 148, 136, 0.1);"><i class="fa-solid fa-money-bill-wave"></i></div>
        <div class="stat-info">
            <h3>Valeur de Vente Totale</h3>
            <div class="value"><?= formatMontant($valeur_totale_vente) ?></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Catégorie</th>
                    <th>Médicament</th>
                    <th>N° Lot</th>
                    <th>Expiration</th>
                    <th>Quantité</th>
                    <th>P. Achat</th>
                    <th>P. Vente</th>
                    <th>Valeur Vente</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($lots)): ?>
                    <tr><td colspan="8" style="text-align: center;">Aucun lot en stock.</td></tr>
                <?php else: ?>
                    <?php foreach($lots as $l): ?>
                    <tr>
                        <td><?= htmlspecialchars($l['categorie_nom']) ?></td>
                        <td style="font-weight: 500;"><?= htmlspecialchars($l['medicament_nom']) ?></td>
                        <td><?= htmlspecialchars($l['numero_lot']) ?></td>
                        <td><?= formatDate($l['date_expiration'], 'd/m/Y') ?></td>
                        <td><span class="badge badge-primary"><?= $l['quantite'] ?></span></td>
                        <td><?= formatMontant($l['prix_achat']) ?></td>
                        <td><?= formatMontant($l['prix_vente']) ?></td>
                        <td style="font-weight: bold;"><?= formatMontant($l['quantite'] * $l['prix_vente']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
