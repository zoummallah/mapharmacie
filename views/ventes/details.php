<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="page-title">
    <span>Détails de la Vente</span>
    <div>
        <a href="<?= BASE_URL ?>/index.php?page=ventes&action=facture&id=<?= $vente['id'] ?>" class="btn btn-primary" target="_blank"><i class="fa-solid fa-print"></i> Imprimer Facture</a>
        <a href="<?= BASE_URL ?>/index.php?page=ventes&action=historique" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Retour</a>
    </div>
</div>

<div class="form-row">
    <div class="form-col">
        <div class="card">
            <div class="card-header">Informations Générales</div>
            <div class="card-body">
                <p><strong>N° Facture :</strong> <?= htmlspecialchars($vente['numero_facture']) ?></p>
                <p><strong>Date :</strong> <?= formatDate($vente['date_vente']) ?></p>
                <p><strong>Vendeur :</strong> <?= htmlspecialchars($vente['vendeur_nom']) ?></p>
                <p><strong>Total :</strong> <span style="font-size: 1.2rem; font-weight: bold; color: var(--primary-color);"><?= formatMontant($vente['montant_total']) ?></span></p>
            </div>
        </div>
    </div>
    
    <div class="form-col">
        <div class="card">
            <div class="card-header">Informations Client</div>
            <div class="card-body">
                <?php if ($vente['client_nom']): ?>
                    <p><strong>Nom :</strong> <?= htmlspecialchars($vente['client_nom']) ?></p>
                    <p><strong>Adresse :</strong> <?= htmlspecialchars($vente['client_adresse']) ?></p>
                    <?php if ($vente['id_ordonnance']): ?>
                        <p><strong>Ordonnance :</strong> Liée <i class="fa-solid fa-check" style="color: var(--success);"></i></p>
                    <?php endif; ?>
                <?php else: ?>
                    <p style="color: var(--text-muted); font-style: italic;">Client de passage (non enregistré)</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">Lignes de Vente</div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Médicament</th>
                    <th>Lot</th>
                    <th>Prix Unitaire</th>
                    <th>Quantité</th>
                    <th>Sous-total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lignes as $ligne): ?>
                <tr>
                    <td style="font-weight: 500;"><?= htmlspecialchars($ligne['medicament_nom']) ?></td>
                    <td><?= $ligne['numero_lot'] ? htmlspecialchars($ligne['numero_lot']) : '-' ?></td>
                    <td><?= formatMontant($ligne['prix_unitaire']) ?></td>
                    <td><?= $ligne['quantite'] ?></td>
                    <td style="font-weight: bold;"><?= formatMontant($ligne['quantite'] * $ligne['prix_unitaire']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" style="text-align: right; font-size: 1.1rem;">Total :</th>
                    <th style="font-size: 1.1rem; color: var(--primary-color);"><?= formatMontant($vente['montant_total']) ?></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
