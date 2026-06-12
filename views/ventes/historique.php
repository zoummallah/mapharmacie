<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="page-title">
    <span>Historique des Ventes</span>
    <a href="<?= BASE_URL ?>/index.php?page=ventes&action=pos" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Nouvelle Vente</a>
</div>

<div class="card">
    <div class="card-header">
        <form action="<?= BASE_URL ?>/index.php" method="GET" style="display: flex; gap: 1rem; align-items: center;">
            <input type="hidden" name="page" value="ventes">
            <input type="hidden" name="action" value="historique">
            <input type="date" name="date_debut" class="form-control" value="<?= $_GET['date_debut'] ?? '' ?>" style="width: auto;">
            <span>à</span>
            <input type="date" name="date_fin" class="form-control" value="<?= $_GET['date_fin'] ?? '' ?>" style="width: auto;">
            <button type="submit" class="btn btn-secondary">Filtrer</button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>N° Facture</th>
                    <th>Vendeur</th>
                    <th>Client</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($ventes)): ?>
                    <tr><td colspan="6" style="text-align: center;">Aucune vente trouvée.</td></tr>
                <?php else: ?>
                    <?php foreach ($ventes as $vente): ?>
                    <tr>
                        <td><?= formatDate($vente['date_vente']) ?></td>
                        <td style="font-weight: 500;"><?= htmlspecialchars($vente['numero_facture']) ?></td>
                        <td><?= htmlspecialchars($vente['vendeur_nom']) ?></td>
                        <td><?= $vente['client_nom'] ? htmlspecialchars($vente['client_nom']) : '<span style="color:var(--text-muted)">Passage</span>' ?></td>
                        <td style="font-weight: bold; color: var(--primary-color);"><?= formatMontant($vente['montant_total']) ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>/index.php?page=ventes&action=details&id=<?= $vente['id'] ?>" class="btn btn-secondary btn-icon" title="Détails"><i class="fa-solid fa-eye"></i></a>
                            <a href="<?= BASE_URL ?>/index.php?page=ventes&action=facture&id=<?= $vente['id'] ?>" class="btn btn-secondary btn-icon" title="Imprimer Facture" target="_blank"><i class="fa-solid fa-print"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
