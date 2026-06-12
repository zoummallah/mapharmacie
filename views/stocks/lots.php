<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="page-title">
    <span>Stock & Lots</span>
    <div>
        <a href="<?= BASE_URL ?>/index.php?page=lots&action=entree" class="btn btn-success"><i class="fa-solid fa-arrow-down"></i> Entrée</a>
        <a href="<?= BASE_URL ?>/index.php?page=lots&action=sortie" class="btn btn-warning"><i class="fa-solid fa-arrow-up"></i> Sortie</a>
        <a href="<?= BASE_URL ?>/index.php?page=lots&action=perimes" class="btn btn-danger" style="display: inline-flex; align-items: center; gap: 0.4rem;">
            Lots Périmés
            <?php if (isset($lotsPerimesCount) && $lotsPerimesCount > 0): ?>
                <span style="background: white; color: var(--danger); font-size: 0.75rem; font-weight: bold; border-radius: 10px; padding: 2px 6px; line-height: 1; display: inline-block;">
                    <?= $lotsPerimesCount ?>
                </span>
            <?php endif; ?>
        </a>
        <a href="<?= BASE_URL ?>/index.php?page=lots&action=historique" class="btn btn-secondary"><i class="fa-solid fa-history"></i> Historique</a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <span><i class="fa-solid fa-boxes-stacked"></i> Lots Actifs en Stock</span>
    </div>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Médicament</th>
                    <th>N° Lot</th>
                    <th>Date d'expiration</th>
                    <th>Quantité</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($lots)): ?>
                    <tr><td colspan="5" style="text-align: center;">Aucun lot actif.</td></tr>
                <?php else: ?>
                    <?php foreach($lots as $l): ?>
                    <tr>
                        <td style="font-weight: 500;"><?= htmlspecialchars($l['medicament_nom']) ?></td>
                        <td><?= htmlspecialchars($l['numero_lot']) ?></td>
                        <td>
                            <?php 
                            $dateExp = new DateTime($l['date_expiration']);
                            $now = new DateTime();
                            $diff = $now->diff($dateExp)->days;
                            $invert = $now->diff($dateExp)->invert;
                            
                            if ($invert) {
                                echo '<span class="badge badge-danger">Périmé</span>';
                            } elseif ($diff <= 30) {
                                echo '<span class="badge badge-warning" title="Expire dans '.$diff.' jours">' . formatDate($l['date_expiration'], 'd/m/Y') . '</span>';
                            } else {
                                echo formatDate($l['date_expiration'], 'd/m/Y');
                            }
                            ?>
                        </td>
                        <td><span class="badge badge-primary" style="font-size: 0.9rem;"><?= $l['quantite'] ?></span></td>
                        <td>
                            <span class="badge badge-success">Actif</span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
