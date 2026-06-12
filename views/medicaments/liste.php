<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="page-title">
    <span>Médicaments</span>
    <a href="<?= BASE_URL ?>/index.php?page=medicaments&action=create" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Nouveau Médicament</a>
</div>

<div class="card">
    <div class="card-header">
        <form action="<?= BASE_URL ?>/index.php" method="GET" style="display: flex; gap: 1rem; align-items: center; width: 100%;">
            <input type="hidden" name="page" value="medicaments">
            <input type="text" name="q" class="form-control" placeholder="Rechercher (Nom, DCI, Lot)..." value="<?= htmlspecialchars($search) ?>" style="max-width: 300px;">
            <select name="categorie" class="form-control" style="max-width: 200px;">
                <option value="">Toutes les catégories</option>
                <?php foreach($categories as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= (isset($_GET['categorie']) && $_GET['categorie'] == $c['id']) ? 'selected' : '' ?>><?= htmlspecialchars($c['nom']) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-secondary">Filtrer</button>
            <?php if($search || isset($_GET['categorie'])): ?>
                <a href="<?= BASE_URL ?>/index.php?page=medicaments" class="btn btn-secondary" style="color: var(--danger); border-color: transparent;"><i class="fa-solid fa-times"></i> Réinitialiser</a>
            <?php endif; ?>
        </form>
    </div>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Nom / DCI</th>
                    <th>Catégorie</th>
                    <th>Dosage / Forme</th>
                    <?php if(estAdmin()): ?>
                        <th>Prix Achat</th>
                    <?php endif; ?>
                    <th>Prix Vente</th>
                    <th>Stock Global</th>
                    <th>Fournisseur(s)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($medicaments)): ?>
                    <tr><td colspan="<?= estAdmin() ? 8 : 7 ?>" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                        <i class="fa-solid fa-box-open" style="font-size: 1.5rem; display: block; margin-bottom: 0.5rem;"></i>
                        Aucun médicament trouvé.
                    </td></tr>
                <?php else: ?>
                    <?php foreach($medicaments as $m): ?>
                    <tr>
                        <td>
                            <div style="font-weight: 600;"><?= htmlspecialchars($m['nom']) ?></div>
                            <div style="font-size: 0.8rem; color: var(--text-muted);"><?= htmlspecialchars($m['principe_actif']) ?></div>
                        </td>
                        <td><?= htmlspecialchars($m['categorie_nom']) ?></td>
                        <td><?= htmlspecialchars($m['dosage']) ?> / <?= htmlspecialchars($m['forme']) ?></td>
                        <?php if(estAdmin()): ?>
                            <td style="color: var(--text-muted);"><?= formatMontant($m['prix_achat']) ?></td>
                        <?php endif; ?>
                        <td style="font-weight: 500; color: var(--primary-color);"><?= formatMontant($m['prix_vente']) ?></td>
                        <td>
                            <?php if($m['quantite_stock'] <= $m['stock_minimum']): ?>
                                <span class="badge badge-danger" title="Min: <?= $m['stock_minimum'] ?>"><?= $m['quantite_stock'] ?></span>
                            <?php else: ?>
                                <span class="badge badge-success"><?= $m['quantite_stock'] ?></span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($m['fournisseur_nom']) ?></td>
                        <td>
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="<?= BASE_URL ?>/index.php?page=medicaments&action=edit&id=<?= $m['id'] ?>" class="btn btn-secondary btn-icon"><i class="fa-solid fa-pen"></i></a>
                                <?php if(estAdmin()): ?>
                                    <a href="<?= BASE_URL ?>/index.php?page=medicaments&action=delete&id=<?= $m['id'] ?>" class="btn btn-danger btn-icon" onclick="return confirm('Voulez-vous vraiment désactiver ce médicament ?');"><i class="fa-solid fa-trash"></i></a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
