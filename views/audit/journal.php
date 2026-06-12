<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="page-title">
    <span>Journal d'Audit</span>
</div>

<div class="card">
    <div class="card-header">
        <form action="<?= BASE_URL ?>/index.php" method="GET" style="display: flex; gap: 1rem; align-items: center;">
            <input type="hidden" name="page" value="audit">
            <input type="text" name="id_utilisateur" class="form-control" placeholder="ID Utilisateur" value="<?= $_GET['id_utilisateur'] ?? '' ?>" style="width: 150px;">
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
                    <th>Date / Heure</th>
                    <th>Utilisateur</th>
                    <th>Action</th>
                    <th>Entité</th>
                    <th>Détails</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($logs)): ?>
                    <tr><td colspan="5" style="text-align: center;">Aucun log trouvé.</td></tr>
                <?php else: ?>
                    <?php foreach($logs as $log): ?>
                    <tr>
                        <td style="white-space: nowrap;"><?= formatDate($log['date_action'], 'd/m/Y H:i:s') ?></td>
                        <td><?= htmlspecialchars($log['utilisateur_nom'] ?? 'Système/Non connecté') ?></td>
                        <td><span class="badge badge-secondary"><?= htmlspecialchars($log['action']) ?></span></td>
                        <td><?= htmlspecialchars($log['entite']) ?></td>
                        <td><?= htmlspecialchars($log['details']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
