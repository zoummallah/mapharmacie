<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="page-title">
    <span>Clients</span>
    <a href="<?= BASE_URL ?>/index.php?page=clients&action=create" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Nouveau Client</a>
</div>

<div class="card">
    <div class="card-header">
        <form action="<?= BASE_URL ?>/index.php" method="GET" style="display: flex; gap: 1rem; width: 100%;">
            <input type="hidden" name="page" value="clients">
            <input type="text" name="q" class="form-control" placeholder="Rechercher par nom ou téléphone..." value="<?= htmlspecialchars($search) ?>" style="max-width: 400px;">
            <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-search"></i></button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Nom Complet</th>
                    <th>Téléphone</th>
                    <th>Email</th>
                    <th>Adresse</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($clients)): ?>
                    <tr><td colspan="5" style="text-align: center;">Aucun client trouvé.</td></tr>
                <?php else: ?>
                    <?php foreach($clients as $c): ?>
                    <tr>
                        <td style="font-weight: 600;"><?= htmlspecialchars($c['nom']) ?></td>
                        <td><?= htmlspecialchars($c['telephone']) ?></td>
                        <td><?= htmlspecialchars($c['email']) ?></td>
                        <td><?= htmlspecialchars($c['adresse']) ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>/index.php?page=clients&action=edit&id=<?= $c['id'] ?>" class="btn btn-secondary btn-icon"><i class="fa-solid fa-pen"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
