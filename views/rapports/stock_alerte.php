<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="page-title">
    <span>Rapport : Alertes Stock Bas</span>
    <button onclick="window.print()" class="btn btn-primary"><i class="fa-solid fa-print"></i> Imprimer</button>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Médicament</th>
                    <th>Catégorie</th>
                    <th>Fournisseur</th>
                    <th>Stock Actuel</th>
                    <th>Seuil Minimum</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($medicaments)): ?>
                    <tr><td colspan="5" style="text-align: center;">Aucun médicament en stock bas.</td></tr>
                <?php else: ?>
                    <?php foreach($medicaments as $m): ?>
                    <tr>
                        <td style="font-weight: 500;"><?= htmlspecialchars($m['nom']) ?></td>
                        <td><?= htmlspecialchars($m['id_categorie']) ?></td> <!-- Dans un vrai cas, on ferait une jointure -->
                        <td><?= htmlspecialchars($m['id_fournisseur']) ?></td>
                        <td><span class="badge badge-danger"><?= $m['quantite_stock'] ?></span></td>
                        <td><?= $m['stock_minimum'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
