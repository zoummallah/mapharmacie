<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="page-title">
    <span>Historique des Mouvements</span>
    <a href="<?= BASE_URL ?>/index.php?page=lots" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Retour au Stock</a>
</div>

<div class="card">
    <div class="card-header">
        <form action="<?= BASE_URL ?>/index.php" method="GET" style="display: flex; gap: 1rem; align-items: center;">
            <input type="hidden" name="page" value="lots">
            <input type="hidden" name="action" value="historique">
            <select name="type" class="form-control" style="width: auto;">
                <option value="">Tous les types</option>
                <option value="entree" <?= (isset($_GET['type']) && $_GET['type'] == 'entree') ? 'selected' : '' ?>>Entrée</option>
                <option value="sortie" <?= (isset($_GET['type']) && $_GET['type'] == 'sortie') ? 'selected' : '' ?>>Sortie (Autre)</option>
                <option value="retour_fournisseur" <?= (isset($_GET['type']) && $_GET['type'] == 'retour_fournisseur') ? 'selected' : '' ?>>Retour Fournisseur</option>
            </select>
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
                    <th>Type</th>
                    <th>Médicament (Lot)</th>
                    <th>Quantité</th>
                    <th>Motif</th>
                    <th>Auteur</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($mouvements)): ?>
                    <tr><td colspan="6" style="text-align: center;">Aucun mouvement trouvé.</td></tr>
                <?php else: ?>
                    <?php foreach ($mouvements as $mouv): ?>
                    <tr>
                        <td><?= formatDate($mouv['date_mouvement']) ?></td>
                        <td>
                            <?php if($mouv['type_mouvement'] == 'entree'): ?>
                                <span class="badge badge-success"><i class="fa-solid fa-arrow-down"></i> Entrée</span>
                            <?php elseif($mouv['type_mouvement'] == 'retour_fournisseur'): ?>
                                <span class="badge badge-warning"><i class="fa-solid fa-undo"></i> Retour</span>
                            <?php else: ?>
                                <span class="badge badge-danger"><i class="fa-solid fa-arrow-up"></i> Sortie</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="font-weight: 500;"><?= htmlspecialchars($mouv['medicament_nom']) ?></div>
                            <div style="font-size: 0.8rem; color: var(--text-muted);">Lot: <?= $mouv['numero_lot'] ? htmlspecialchars($mouv['numero_lot']) : '-' ?></div>
                        </td>
                        <td style="font-weight: bold;"><?= $mouv['type_mouvement'] == 'entree' ? '+' : '-' ?><?= $mouv['quantite'] ?></td>
                        <td><?= htmlspecialchars($mouv['motif']) ?></td>
                        <td><?= htmlspecialchars($mouv['utilisateur_nom']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
